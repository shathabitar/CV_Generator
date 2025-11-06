<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class OpenRouterAgent extends Command
{
    protected $signature = 'openrouter:chat 
                            {prompt? : The prompt to send to the agent}
                            {--model=minimax/minimax-m2:free : The model to use}
                            {--file= : Specific file to include in context}
                            {--interactive : Start interactive mode}';

    protected $description = 'Chat with OpenRouter AI agent that can directly modify project files';

    private string $apiKey;
    private string $apiBase;
    private array $conversationHistory = [];

    public function __construct()
    {
        parent::__construct();
        $this->apiKey = env('OPENROUTER_API_KEY');
        $this->apiBase = env('OPENROUTER_API_BASE', 'https://openrouter.ai/api/v1');
    }

    public function handle(): int
    {
        if (!$this->apiKey) {
            $this->error('OPENROUTER_API_KEY not found in environment variables');
            return self::FAILURE;
        }

        // Warning about file modifications
        $this->warn('⚠️  WARNING: This agent can directly modify your project files!');
        $this->line('Make sure you have committed your changes to git before proceeding.');
        $this->newLine();

        if ($this->option('interactive')) {
            return $this->startInteractiveMode();
        }

        $prompt = $this->argument('prompt') ?? '';

        if (trim($prompt) === '') {
            $this->error('You must provide a prompt. Example:');
            $this->line('php artisan openrouter:chat "Add user authentication"');
            return self::FAILURE;
        }

        return $this->sendPrompt($prompt);
    }

    private function startInteractiveMode(): int
    {
        $this->info('OpenRouter Agent - Interactive Mode');
        $this->info('Type "exit" to quit, "clear" to clear history, "files" to list project files');
        $this->newLine();

        while (true) {
            $prompt = $this->ask('You');
            
            if (strtolower($prompt) === 'exit') {
                $this->info('Goodbye!');
                break;
            }

            if (strtolower($prompt) === 'clear') {
                $this->conversationHistory = [];
                $this->info('Conversation history cleared.');
                continue;
            }

            if (strtolower($prompt) === 'files') {
                $this->listProjectFiles();
                continue;
            }

            $this->sendPrompt($prompt, false);
        }

        return self::SUCCESS;
    }

    private function sendPrompt(string $prompt, bool $exit = true): int
    {
        try {
            $this->info('Thinking...');

            $context = $this->buildContext();
            $messages = $this->buildMessages($prompt, $context);

            $response = Http::timeout(120)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->apiBase . '/chat/completions', [
                    'model' => $this->option('model'),
                    'messages' => $messages,
                    'temperature' => 0.7,
                    'max_tokens' => 4000,
                ]);

            if ($response->failed()) {
                $this->error('API request failed: ' . $response->body());
                return $exit ? self::FAILURE : 0;
            }

            $data = $response->json();
            $agentResponse = $data['choices'][0]['message']['content'] ?? 'No response received';

            $this->displayResponse($agentResponse);

            $this->conversationHistory[] = ['role' => 'user', 'content' => $prompt];
            $this->conversationHistory[] = ['role' => 'assistant', 'content' => $agentResponse];

            $this->handleFileModifications($agentResponse);

            return $exit ? self::SUCCESS : 0;

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return $exit ? self::FAILURE : 0;
        }
    }

    private function buildContext(): string
    {
        $context = '';

        if (File::exists(base_path('AGENTS.md'))) {
            $context .= "# Agent Configuration\n";
            $context .= File::get(base_path('AGENTS.md')) . "\n\n";
        }

        if ($file = $this->option('file')) {
            if (File::exists(base_path($file))) {
                $context .= "# Current File: {$file}\n";
                $context .= "```\n" . File::get(base_path($file)) . "\n```\n\n";
            }
        }

        $context .= "# Project Structure\n";
        $context .= $this->getProjectStructure() . "\n\n";

        return $context;
    }

    private function buildMessages(string $prompt, string $context): array
    {
        $systemContent = <<<SYSTEM
You are a Senior Laravel Engineer with full write-access to the project.

Your responsibilities:
- Understand the user's request.
- Determine which files need to be created or modified.
- For each file you modify, you must output the **entire updated file**, not just a snippet.

Output Format (strict):
For each updated or newly created file, respond using this exact structure:

```php
// File: <path/to/the/file.php>
<entire file contents here>
```

For non-PHP files, use the appropriate language identifier:
```javascript
// File: <path/to/file.js>
<entire file contents here>
```

```css
// File: <path/to/file.css>
<entire file contents here>
```

```html
// File: <path/to/file.blade.php>
<entire file contents here>
```

```json
// File: <path/to/file.json>
<entire file contents here>
```

```yaml
// File: <path/to/file.yml>
<entire file contents here>
```

```md
// File: <path/to/file.md>
<entire file contents here>
```

IMPORTANT RULES:
1. Always include the "// File: <path>" comment as the first line in each code block
2. Provide the complete file content, not partial updates
3. Use relative paths from the project root (e.g., app/Models/User.php)
4. Make sure all syntax is correct and the file will work immediately
5. If creating new files, ensure proper directory structure
6. Follow Laravel best practices and PSR standards
7. Include proper namespaces, imports, and class declarations

Project Context:
{$context}
SYSTEM;

        $messages = [
            [
                'role' => 'system',
                'content' => $systemContent
            ]
        ];

        foreach ($this->conversationHistory as $message) {
            $messages[] = $message;
        }

        $messages[] = ['role' => 'user', 'content' => $prompt];

        return $messages;
    }

    private function displayResponse(string $response): void
    {
        $this->newLine();
        $this->info('Agent:');
        $this->line($response);
        $this->newLine();
    }

    private function handleFileModifications(string $response): void
    {
        // Look for code blocks with file paths
        if (preg_match_all('/```(?:php|javascript|css|html|json|yaml|md|blade)?\s*\/\/ File: (.+?)\n(.*?)```/s', $response, $matches, PREG_SET_ORDER)) {
            $this->info('🔧 Applying file modifications...');
            
            foreach ($matches as $match) {
                $filePath = trim($match[1]);
                $content = trim($match[2]);

                if ($filePath) {
                    $fullPath = base_path($filePath);
                    
                    // Create directory if it doesn't exist
                    $directory = dirname($fullPath);
                    if (!File::exists($directory)) {
                        File::makeDirectory($directory, 0755, true);
                        $this->line("📁 Created directory: " . str_replace(base_path(), '', $directory));
                    }

                    // Write the file
                    File::put($fullPath, $content);
                    
                    // Determine if it's a new file or update
                    $action = File::exists($fullPath) ? 'Updated' : 'Created';
                    $this->info("✅ {$action}: {$filePath}");
                }
            }
            
            $this->newLine();
            $this->info('🎉 All file modifications applied successfully!');
        } else {
            // Fallback to old pattern for backward compatibility
            if (preg_match_all('/```(?:php|javascript|css|html|json|yaml|md)?\s*(?:\/\/ File: (.+?)\n)?(.*?)```/s', $response, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $filePath = $match[1] ?? null;
                    $content = trim($match[2]);

                    if ($filePath && $this->confirm("Apply changes to {$filePath}?")) {
                        $fullPath = base_path($filePath);
                        
                        $directory = dirname($fullPath);
                        if (!File::exists($directory)) {
                            File::makeDirectory($directory, 0755, true);
                        }

                        File::put($fullPath, $content);
                        $this->info("✅ Updated: {$filePath}");
                    }
                }
            }
        }
    }

    private function getProjectStructure(): string
    {
        $structure = '';
        $directories = ['app', 'config', 'database', 'resources', 'routes'];

        foreach ($directories as $dir) {
            if (File::exists(base_path($dir))) {
                $structure .= $this->getDirectoryStructure(base_path($dir), $dir);
            }
        }

        return $structure;
    }

    private function getDirectoryStructure(string $path, string $prefix = '', int $depth = 0): string
    {
        if ($depth > 2) return '';

        $structure = '';
        $items = File::glob($path . '/*');

        foreach ($items as $item) {
            $name = basename($item);

            if (File::isDirectory($item)) {
                $structure .= str_repeat('  ', $depth) . "{$name}/\n";
                $structure .= $this->getDirectoryStructure($item, $prefix . '/' . $name, $depth + 1);
            } else {
                $structure .= str_repeat('  ', $depth) . "{$name}\n";
            }
        }

        return $structure;
    }

    private function listProjectFiles(): void
    {
        $this->info('Project Files:');
        
        $files = [
            'Configuration' => ['composer.json', 'package.json', '.env.example'],
            'Routes' => ['routes/web.php'],
            'Controllers' => File::glob(base_path('app/Http/Controllers/*.php')),
            'Models' => File::glob(base_path('app/Models/*.php')),
            'Services' => File::glob(base_path('app/Services/*.php')),
        ];

        foreach ($files as $category => $fileList) {
            $this->line("  {$category}:");
            foreach ($fileList as $file) {
                $displayPath = is_string($file) ? $file : str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file);
                $this->line("    - {$displayPath}");
            }
        }
    }
}