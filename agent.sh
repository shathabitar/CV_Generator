#!/bin/bash

# OpenRouter Agent CLI Helper
# Usage: ./agent.sh [command] [options]

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Check if Laravel is available
if [ ! -f "artisan" ]; then
    echo -e "${RED}Error: This script must be run from the Laravel project root${NC}"
    exit 1
fi

# Function to display help
show_help() {
    echo -e "${BLUE}OpenRouter Agent CLI Helper${NC}"
    echo ""
    echo "Usage: ./agent.sh [command] [options]"
    echo ""
    echo "Commands:"
    echo "  chat [prompt]     - Send a prompt to the agent"
    echo "  interactive       - Start interactive chat mode"
    echo "  review [file]     - Ask agent to review a specific file"
    echo "  fix [issue]       - Ask agent to fix a specific issue"
    echo "  optimize          - Ask agent to optimize the codebase"
    echo "  test              - Ask agent to create/run tests"
    echo "  help              - Show this help message"
    echo ""
    echo "Options:"
    echo "  --file [path]     - Include specific file in context"
    echo ""
    echo "Examples:"
    echo "  ./agent.sh chat \"Add user authentication\""
    echo "  ./agent.sh review app/Http/Controllers/CVController.php"
    echo "  ./agent.sh fix \"Form submission not working\""
    echo "  ./agent.sh interactive"
}

# Function to run agent command
run_agent() {
    local prompt=""
    
    # Combine all arguments into a single prompt
    for arg in "$@"; do
        if [[ $arg != --* ]]; then
            if [ -z "$prompt" ]; then
                prompt="$arg"
            else
                prompt="$prompt $arg"
            fi
        fi
    done
    
    local cmd="php artisan openrouter:chat \"$prompt\""
    
    echo -e "${YELLOW}Running: $cmd${NC}"
    eval $cmd
}

# Main script logic
case "$1" in
    "chat")
        shift
        if [ -z "$1" ]; then
            echo -e "${YELLOW}Enter your prompt:${NC}"
            read -r prompt
            run_agent "$prompt" "${@:2}"
        else
            run_agent "$@"
        fi
        ;;
    "interactive")
        echo -e "${YELLOW}Interactive mode not available with coding:agent.${NC}"
        echo -e "${YELLOW}Enter your prompt:${NC}"
        read -r prompt
        run_agent "$prompt"
        ;;
    "review")
        if [ -z "$2" ]; then
            echo -e "${RED}Error: Please specify a file to review${NC}"
            exit 1
        fi
        run_agent "Please review this file and suggest improvements: $2" --file "$2" "${@:3}"
        ;;
    "fix")
        shift
        if [ -z "$1" ]; then
            echo -e "${YELLOW}Describe the issue to fix:${NC}"
            read -r issue
            run_agent "Please help fix this issue: $issue" "${@:2}"
        else
            run_agent "Please help fix this issue: $*"
        fi
        ;;
    "optimize")
        run_agent "Please analyze the codebase and suggest optimizations. Focus on performance, code quality, and best practices." "${@:2}"
        ;;
    "test")
        shift
        if [ -z "$1" ]; then
            run_agent "Please create comprehensive tests for this Laravel application and show me how to run them."
        else
            run_agent "Please create tests for: $*"
        fi
        ;;
    "help"|"--help"|"-h")
        show_help
        ;;
    "")
        echo -e "${YELLOW}No command specified. Enter your prompt:${NC}"
        read -r prompt
        run_agent "$prompt"
        ;;
    *)
        echo -e "${RED}Unknown command: $1${NC}"
        echo "Use './agent.sh help' for available commands"
        exit 1
        ;;
esac