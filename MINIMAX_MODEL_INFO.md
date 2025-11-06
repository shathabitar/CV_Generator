# MiniMax M2 Free Model Information

## 🤖 Model Details

**Model Name**: `minimax/minimax-m2:free`  
**Provider**: MiniMax  
**Tier**: Free  
**Optimized For**: General purpose AI tasks including coding

## ✅ **Successfully Configured!**

Your OpenRouter agent is now using the MiniMax M2 free model as the default. This provides you with:

- **Free Usage**: No cost for API calls
- **Good Performance**: Capable of handling coding tasks
- **Full Project Access**: Complete file read/write permissions
- **Laravel Expertise**: Understanding of Laravel best practices

## 🚀 Quick Test Commands

```powershell
# Test the model (Windows)
.\agent.ps1 chat "Hello, can you see my Laravel project?"

# Interactive mode
.\agent.ps1 interactive

# File review
.\agent.ps1 review app/Http/Controllers/CVController.php
```

```bash
# Test the model (Linux/Mac)
./agent.sh chat "Hello, can you see my Laravel project?"

# Interactive mode  
./agent.sh interactive

# File review
./agent.sh review app/Http/Controllers/CVController.php
```

## 📊 Model Comparison

| Model | Cost | Speed | Code Quality | Context Length |
|-------|------|-------|--------------|----------------|
| `minimax/minimax-m2:free` | Free | Fast | Good | Large |
| `anthropic/claude-3.5-sonnet` | Paid | Fast | Excellent | Very Large |
| `openai/gpt-4-turbo` | Paid | Medium | Excellent | Large |

## 🔧 Switching Models

If you want to use a different model for specific tasks:

```bash
# Use Claude for complex refactoring
php artisan agent:chat "Refactor this code" --model=anthropic/claude-3.5-sonnet

# Use GPT-4 for documentation
php artisan agent:chat "Write documentation" --model=openai/gpt-4-turbo

# Back to MiniMax (default)
php artisan agent:chat "Simple task"
```

## 💡 Best Practices with MiniMax M2

### Optimal Use Cases
- ✅ Code reviews and suggestions
- ✅ Bug fixing and debugging  
- ✅ Feature implementation
- ✅ Laravel-specific tasks
- ✅ Performance optimization
- ✅ Test creation

### Tips for Better Results
1. **Be Specific**: Clear, detailed prompts work best
2. **Provide Context**: Include relevant file paths when needed
3. **Break Down Complex Tasks**: Split large requests into smaller parts
4. **Use Interactive Mode**: For ongoing conversations and refinements

### Example Prompts That Work Well
```bash
# Specific and actionable
"Review the CVController for potential security vulnerabilities"

# Clear scope
"Add input validation to the CV form submission process"

# Focused task
"Optimize the PDF generation performance in GenerateCvPdf job"

# With context
"Fix the form submission issue in resources/views/cv/form.blade.php"
```

## 🎯 Your Setup is Ready!

The MiniMax M2 free model is now your default AI coding assistant with complete access to your Laravel CV Generator project. 

**Start coding with AI assistance:**
```powershell
.\agent.ps1 interactive
```

The agent understands your project structure, Laravel conventions, and can help with any development task while keeping costs at zero with the free tier!