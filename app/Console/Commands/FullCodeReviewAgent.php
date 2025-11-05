<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use GuzzleHttp\Client;

class FullCodeReviewAgent extends Command
{
    protected $signature = 'coding:review';
    protected $description = 'Perform a full project code review using OpenRouter AI';

    public function handle()
    {
        $this->info("🚀 Starting full code review...");

        $client = new Client();
        $apiKey = env('OPENROUTER_API_KEY');

        if (!$apiKey) {
            $this->error("❌ OPENROUTER_API_KEY is missing in .env");
            return;
        }

        $outputDir = base_path('code-review');
        if (!is_dir($outputDir)) mkdir($outputDir);

        // Scan the project for php files
        $directory = new RecursiveDirectoryIterator(base_path());
        $iterator = new RecursiveIteratorIterator($directory);

        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') continue;
            $filePath = $file->getPathname();

            // Skip vendor directory
            if (str_contains($filePath, 'vendor')) continue;

            $this->info("📄 Reviewing: " . str_replace(base_path(), '', $filePath));

            $fileContent = file_get_contents($filePath);

            $prompt = "You are a senior Laravel architect. Review this PHP file. 
            Suggest improvements, point out bad practices, 
            recommend design enhancements, and refactor where possible.
            Provide improved code examples.\n\n" . $fileContent;

            try {
                $response = $client->post('https://openrouter.ai/api/v1/chat/completions', [
                    'headers' => [
                        'Authorization' => "Bearer $apiKey",
                        'Content-Type' => 'application/json'
                    ],
                    'json' => [
                        'model' => 'minimax/minimax-m2:free',
                        'messages' => [
                            ['role' => 'user', 'content' => $prompt]
                        ]
                    ]
                ]);

                $data = json_decode($response->getBody(), true);
                $analysis = $data['choices'][0]['message']['content'] ?? 'No response.';

                // Create mirrored directory structure
                $relative = str_replace(base_path(), '', $filePath);
                $outputPath = $outputDir . $relative . '.md';

                if (!is_dir(dirname($outputPath))) {
                    mkdir(dirname($outputPath), 0777, true);
                }

                file_put_contents($outputPath, $analysis);

            } catch (\Exception $e) {
                $this->error("❗ Error reviewing file: " . $filePath);
                $this->error($e->getMessage());
            }
        }

        $this->info("\n✅ Full code review complete!");
        $this->info("📁 Results saved in: code-review/");
    }
}
