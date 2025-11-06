# OpenRouter Agent CLI Helper for PowerShell
# Usage: .\agent.ps1 [command] [options]

param(
    [Parameter(Position=0)]
    [string]$Command = "",
    
    [Parameter(Position=1, ValueFromRemainingArguments=$true)]
    [string[]]$Arguments = @(),
    
    [string]$Model = "minimax/minimax-m2:free",
    [string]$File = ""
)

# Colors for output
$Red = "Red"
$Green = "Green"
$Yellow = "Yellow"
$Blue = "Blue"

# Check if Laravel is available
if (-not (Test-Path "artisan")) {
    Write-Host "Error: This script must be run from the Laravel project root" -ForegroundColor $Red
    exit 1
}

# Function to display help
function Show-Help {
    Write-Host "OpenRouter Agent CLI Helper" -ForegroundColor $Blue
    Write-Host ""
    Write-Host "Usage: .\agent.ps1 [command] [options]"
    Write-Host ""
    Write-Host "Commands:"
    Write-Host "  chat [prompt]     - Send a prompt to the agent"
    Write-Host "  interactive       - Start interactive chat mode"
    Write-Host "  review [file]     - Ask agent to review a specific file"
    Write-Host "  fix [issue]       - Ask agent to fix a specific issue"
    Write-Host "  optimize          - Ask agent to optimize the codebase"
    Write-Host "  test              - Ask agent to create/run tests"
    Write-Host "  help              - Show this help message"
    Write-Host ""
    Write-Host "Options:"
    Write-Host "  -File [path]      - Include specific file in context"
    Write-Host ""
    Write-Host "Examples:"
    Write-Host "  .\agent.ps1 chat 'Add user authentication'"
    Write-Host "  .\agent.ps1 review app/Http/Controllers/CVController.php"
    Write-Host "  .\agent.ps1 fix 'Form submission not working'"
    Write-Host "  .\agent.ps1 interactive"
}

# Function to run agent command
function Invoke-Agent {
    param(
        [string]$Prompt,
        [switch]$Interactive,
        [string]$SpecificFile = ""
    )
    
    if ($Interactive) {
        Write-Host "Interactive mode not available with coding:agent. Starting single prompt mode..." -ForegroundColor $Yellow
        $Prompt = Read-Host "Enter your prompt"
    }
    
    if ($SpecificFile) {
        $Prompt = "Please review this file: $SpecificFile. $Prompt"
    }
    
    $cmd = "php artisan openrouter:chat `"$Prompt`""
    
    Write-Host "Running: $cmd" -ForegroundColor $Yellow
    Invoke-Expression $cmd
}

# Main script logic
switch ($Command.ToLower()) {
    "chat" {
        if ($Arguments.Count -eq 0) {
            $prompt = Read-Host "Enter your prompt"
            Invoke-Agent -Prompt $prompt
        } else {
            $prompt = $Arguments -join " "
            Invoke-Agent -Prompt $prompt
        }
    }
    
    "interactive" {
        Invoke-Agent -Interactive
    }
    
    "review" {
        if ($Arguments.Count -eq 0) {
            Write-Host "Error: Please specify a file to review" -ForegroundColor $Red
            exit 1
        }
        $fileToReview = $Arguments[0]
        $prompt = "Please review this file and suggest improvements: $fileToReview"
        Invoke-Agent -Prompt $prompt -SpecificFile $fileToReview
    }
    
    "fix" {
        if ($Arguments.Count -eq 0) {
            $issue = Read-Host "Describe the issue to fix"
            $prompt = "Please help fix this issue: $issue"
        } else {
            $issue = $Arguments -join " "
            $prompt = "Please help fix this issue: $issue"
        }
        Invoke-Agent -Prompt $prompt
    }
    
    "optimize" {
        $prompt = "Please analyze the codebase and suggest optimizations. Focus on performance, code quality, and best practices."
        Invoke-Agent -Prompt $prompt
    }
    
    "test" {
        if ($Arguments.Count -eq 0) {
            $prompt = "Please create comprehensive tests for this Laravel application and show me how to run them."
        } else {
            $testSubject = $Arguments -join " "
            $prompt = "Please create tests for: $testSubject"
        }
        Invoke-Agent -Prompt $prompt
    }
    
    { $_ -in @("help", "--help", "-h") } {
        Show-Help
    }
    
    "" {
        Write-Host "No command specified. Starting interactive mode..." -ForegroundColor $Yellow
        Invoke-Agent -Interactive
    }
    
    default {
        Write-Host "Unknown command: $Command" -ForegroundColor $Red
        Write-Host "Use '.\agent.ps1 help' for available commands"
        exit 1
    }
}