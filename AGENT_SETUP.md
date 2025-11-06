# OpenRouter Code Agent Setup Guide

## 🤖 Overview

This setup provides you with a powerful AI code agent that has full access to your Laravel project files through OpenRouter's API. The agent can read, write, and modify any files in your project.

## 📋 Prerequisites

1. **OpenRouter API Key**: Already configured in your `.env` file
2. **Laravel Project**: Your CV Generator application
3. **Command Line Access**: Terminal or PowerShell

## 🚀 Quick Start

### 1. Verify Setup
```bash
# Check if your API key is configured
php artisan tinker --execute="echo env('OPENROUTER_API_KEY') ? 'API Key found' : 'API Key missing';"
```

### 2. Test the Agent
```bash
# Simple test
php artisan agent:chat "Hello, can you see my project files?"

# Interactive mode
php artisan agent:chat --interactive
```

### 3. Use Helper Scripts

**On Windows (PowerShell):**
```powershell
# Quick chat
.\agent.ps1 chat "Review my CVController"

# File review
.\agent.ps1 review app/Http/Controllers/CVController.php

# Fix an issue
.\agent.ps1 fix "Form submission not working"
```

**On Linux/Mac (Bash):**
```bash
# Make executable (first time only)
chmod +x agent.sh

# Quick chat
./agent.sh chat "Review my CVController"

# File review
./agent.sh review app/Http/Controllers/CVController.php

# Fix an issue
./agent.sh fix "Form submission not working"
```

## 🛠️ Available Commands

### Direct Laravel Artisan Commands

```bash
# Basic chat with OpenRouter agent
php artisan coding:agent "Your prompt here"

# Review specific functionality
php artisan coding:agent "Review the CVController for security issues"

# Ask for improvements
php artisan coding:agent "Optimize the CV generation performance"
```

### Helper Script Commands

Both `agent.ps1` (Windows) and `agent.sh` (Linux/Mac) support:

```bash
# Start interactive chat
./agent.sh interactive

# Send a prompt
./agent.sh chat "Add user authentication to the app"

# Review a specific file
./agent.sh review app/Models/User.php

# Fix an issue
./agent.sh fix "Form validation not working"

# Optimize codebase
./agent.sh optimize

# Create tests
./agent.sh test "CVController methods"

# Show help
./agent.sh help
```

## 🎯 Agent Capabilities

### What the Agent Can Do

1. **Read Files**: Access any file in your project
2. **Write Files**: Create new files or modify existing ones
3. **Analyze Code**: Review architecture, performance, security
4. **Debug Issues**: Identify and fix bugs
5. **Refactor Code**: Improve code structure and quality
6. **Add Features**: Implement new functionality
7. **Create Tests**: Write comprehensive test suites
8. **Optimize Performance**: Improve speed and efficiency

### Project Context

The agent has full context of your Laravel CV Generator:
- **Current Architecture**: Models, Controllers, Services, Jobs
- **Configuration**: Environment variables, routes, dependencies
- **Recent Changes**: Git history and modifications
- **File Structure**: Complete project layout
- **Documentation**: All markdown files and comments

## 💡 Usage Examples

### 1. Code Review
```bash
# Review entire controller
./agent.sh review app/Http/Controllers/CVController.php

# Review with specific concerns
./agent.sh chat "Review CVController for security vulnerabilities and performance issues"
```

### 2. Bug Fixing
```bash
# General bug fix
./agent.sh fix "PDF generation is failing"

# Specific error
./agent.sh chat "Fix this error: Call to undefined method on line 45 of CVController"
```

### 3. Feature Development
```bash
# Add new feature
./agent.sh chat "Add user authentication with login/register forms"

# Enhance existing feature
./agent.sh chat "Add CV templates - users should be able to choose from 3 different designs"
```

### 4. Performance Optimization
```bash
# General optimization
./agent.sh optimize

# Specific optimization
./agent.sh chat "Optimize database queries in the CV generation process"
```

### 5. Testing
```bash
# Create comprehensive tests
./agent.sh test

# Test specific functionality
./agent.sh test "PDF generation and email sending"
```

## 🔧 Configuration

### Environment Variables
```env
# Required
OPENROUTER_API_KEY=sk-or-v1-your-key-here
OPENROUTER_API_BASE=https://openrouter.ai/api/v1

# Optional - Model preferences
AGENT_DEFAULT_MODEL=minimax/minimax-m2:free
```

### Available Models
- `minimax/minimax-m2:free` (default, free tier)
- `anthropic/claude-3.5-sonnet` (premium, best for coding)
- `openai/gpt-4-turbo`
- `openai/gpt-3.5-turbo`
- `meta-llama/llama-3.1-70b-instruct`

### Agent Configuration File
The `agent.md` file contains:
- Project context and architecture
- Development guidelines
- Security best practices
- Current issues and priorities
- File modification protocols

## 🔒 Security & Best Practices

### File Modification Safety
- Agent will ask for confirmation before modifying files
- Always review changes before applying them
- Keep backups of important files
- Use version control (git) to track changes

### API Usage
- Monitor your OpenRouter usage and costs
- Set reasonable token limits for long conversations
- Use appropriate models for different tasks

### Development Workflow
1. **Analyze First**: Let agent understand the current state
2. **Plan Changes**: Discuss approach before implementation
3. **Incremental Updates**: Make small, focused changes
4. **Test Changes**: Verify functionality after modifications
5. **Document Updates**: Update relevant documentation

## 🚨 Troubleshooting

### Common Issues

#### API Key Not Working
```bash
# Check API key
php artisan tinker --execute="echo env('OPENROUTER_API_KEY');"

# Test API connection
curl -H "Authorization: Bearer YOUR_API_KEY" https://openrouter.ai/api/v1/models
```

#### Command Not Found
```bash
# Clear Laravel cache
php artisan config:clear
php artisan cache:clear

# Re-register commands
composer dump-autoload
```

#### Permission Errors
```bash
# On Linux/Mac
chmod +x agent.sh

# On Windows, run PowerShell as Administrator
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### Getting Help
```bash
# Show available commands
php artisan agent:chat --help

# Use helper script help
./agent.sh help
```

## 📈 Advanced Usage

### Custom Prompts
Create specialized prompts for common tasks:

```bash
# Architecture review
./agent.sh chat "Perform a comprehensive architecture review focusing on SOLID principles, design patterns, and Laravel best practices"

# Security audit
./agent.sh chat "Conduct a security audit of the application, checking for OWASP top 10 vulnerabilities"

# Performance analysis
./agent.sh chat "Analyze the application for performance bottlenecks and suggest optimizations with benchmarks"
```

### Batch Operations
```bash
# Review multiple files
./agent.sh chat "Review all controllers in app/Http/Controllers/ and suggest improvements"

# Refactor entire feature
./agent.sh chat "Refactor the CV generation feature to use the Repository pattern"
```

### Integration with Development Workflow
```bash
# Before committing
./agent.sh chat "Review my recent changes and ensure code quality before git commit"

# After pulling changes
./agent.sh chat "Analyze the recent changes and update me on what's new"
```

## 🎉 You're Ready!

Your OpenRouter code agent is now fully configured with complete project access. Start with:

```bash
# Interactive mode to get familiar
./agent.sh interactive

# Or jump right in with a task
./agent.sh chat "Help me understand the current codebase and suggest the next improvements"
```

The agent has full context of your CV Generator project and can help with any development task!