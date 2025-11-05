<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;

class CodingAgent extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'coding:agent {prompt*}';

    /**
     * The console command description.
     */
    protected $description = 'Ask OpenRouter AI to help you with coding tasks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //$prompt = implode(' ', $this->argument('prompt'));

        // Example: analyze one file (User.php)
        $fileContent = file_get_contents(base_path('app/Models/User.php'));

        $prompt = "Review this PHP code and suggest improvements. 
        Explain why your suggested improvements are beneficial, and provide corrected code examples.\n\n" 
            . $fileContent;


        $this->info("Sending prompt to coding agent...");

        $client = new Client();
        $response = $client->post('https://openrouter.ai/api/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
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
        $suggestion = $data['choices'][0]['message']['content'] ?? 'No response from agent.';

        $this->info("\n=== Suggestion from Coding Agent ===\n");
        $this->line($suggestion);

        // Save to a file
        file_put_contents(base_path('.suggestions.md'), "# Coding Agent Suggestions\n\n" . $suggestion);

        $this->info("\nSaved suggestions to .suggestions.md");
    }
}
