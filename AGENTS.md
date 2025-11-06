# OpenRouter Code Agent Configuration

## Agent Role & Permissions
You are a full-stack development agent with complete access to this Laravel CV Generator project. You have permission to:

- **READ**: All project files including source code, configuration, documentation
- **WRITE**: Create, modify, and delete any files in the project
- **EXECUTE**: Run commands, tests, and build processes
- **ANALYZE**: Review code quality, performance, and architecture
- **REFACTOR**: Improve code structure and optimize performance

## Project Context

### Application Overview
This is a Laravel 12 CV Generator application that allows users to:
- Create CVs through a web form
- Generate PDF downloads
- Store CV data in database
- Queue background PDF generation with email notifications

### Technology Stack
- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Blade templates, TailwindCSS 4.0, Vite
- **Database**: SQLite (default), supports MySQL/PostgreSQL
- **Queue**: Database driver
- **Cache**: File-based (configurable to Redis)
- **PDF**: DomPDF library
- **Mail**: SMTP configuration

### Key Components
- **Models**: User, Skill, Education, Experience, Reference, Certificate
- **Services**: CvService (data transformation), PerformanceService (monitoring)
- **Jobs**: GenerateCvPdf (background PDF generation)
- **Controllers**: CVController (main application logic)
- **Views**: Blade templates in resources/views/cv/

### Current Architecture
```
app/
├── Console/Commands/     # Artisan commands
├── Http/
│   ├── Controllers/      # CVController
│   └── Requests/         # Form validation
├── Jobs/                 # Background jobs
├── Mail/                 # Email notifications
├── Models/               # Eloquent models
├── Providers/            # Service providers
├── Services/             # Business logic
└── ViewModels/           # Data presentation
```

### File Structure
- Controllers: `app/Http/Controllers/`
- Models: `app/Models/`
- Views: `resources/views/`
- Services: `app/Services/`
- Jobs: `app/Jobs/`
- Routes: `routes/web.php`

### New Code Placement
- Business logic → Service Classes (not controllers)
- Form validation → `app/Http/Requests/`
- Long view logic → ViewModels in `app/ViewModels/`

### Response Format
When proposing code changes:
- State the purpose of the change
- Specify the file path
- Provide complete code snippets (not partial lines)
- Avoid speculative or unverified assumptions

### Security
- Do NOT modify `.env`
- Do NOT output secret keys in logs, chat, or code

## File Access Patterns

### Configuration Files
- `.env` - Environment configuration
- `config/` - Laravel configuration files
- `composer.json` - PHP dependencies
- `package.json` - Node.js dependencies

### Source Code
- `app/` - Application logic
- `resources/` - Views, assets, language files
- `routes/` - Route definitions
- `database/` - Migrations, seeders, factories

### Assets & Public
- `public/` - Web-accessible files
- `storage/` - File uploads, logs, cache

## Development Guidelines

### Code Standards
- Follow PSR-12 coding standards
- Use Laravel best practices and conventions
- Implement proper error handling and validation
- Write clear, self-documenting code
- Add appropriate comments for complex logic

### Performance Considerations
- Use eager loading for relationships
- Implement caching where appropriate
- Optimize database queries
- Consider queue jobs for heavy operations

### Security Best Practices
- Validate all user inputs
- Use Laravel's built-in security features
- Sanitize file uploads
- Implement proper authentication/authorization when needed

## Available Commands

### Laravel Artisan
```bash
php artisan app:optimize          # Optimize application
php artisan migrate              # Run database migrations
php artisan queue:work           # Process queue jobs
php artisan serve               # Start development server
php artisan tinker              # Interactive shell
```

### Composer Scripts
```bash
composer run optimize           # Application optimization
composer run fresh             # Fresh install with optimization
composer run dev               # Start development environment
composer run test             # Run tests
```

### NPM Scripts
```bash
npm run dev                    # Development build
npm run build                  # Production build
```

## Current Issues & Priorities

### Recently Fixed
- ✅ Form submission redirecting properly
- ✅ Code duplication removed
- ✅ Performance optimizations implemented
- ✅ Caching strategy added

### Potential Improvements
- [ ] Add comprehensive testing suite
- [ ] Implement API authentication
- [ ] Add Redis caching for production
- [ ] Create admin interface
- [ ] Add CV templates/themes
- [ ] Implement user accounts system
- [ ] Add CV sharing functionality
- [ ] Optimize PDF generation performance

## Agent Instructions

### When Making Changes
1. **Analyze First**: Understand the current implementation before modifying
2. **Test Changes**: Ensure modifications don't break existing functionality
3. **Follow Patterns**: Maintain consistency with existing code structure
4. **Document Changes**: Update relevant documentation and comments
5. **Consider Impact**: Think about performance, security, and maintainability

### File Modification Protocol
1. Read existing file to understand current implementation
2. Identify specific changes needed
3. Make minimal, focused modifications
4. Verify syntax and logic
5. Test if possible
6. Update related files if necessary

### Communication Style
- Be concise but thorough in explanations
- Provide code examples when helpful
- Explain the reasoning behind changes
- Highlight any potential risks or considerations
- Suggest alternatives when appropriate

## Environment Variables
Key environment variables you may need to reference:
```env
APP_NAME=Laravel
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

CACHE_STORE=file
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.office365.com
MAIL_PORT=587
MAIL_USERNAME=sbitar@cpf.jo
MAIL_FROM_ADDRESS=sbitar@cpf.jo

OPENROUTER_API_KEY=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
OPENROUTER_API_BASE=https://openrouter.ai/api/v1
```

## Project Goals
The main objectives for this CV Generator are:
1. **User Experience**: Simple, intuitive CV creation process
2. **Performance**: Fast loading and responsive interface
3. **Reliability**: Stable PDF generation and email delivery
4. **Maintainability**: Clean, well-organized codebase
5. **Scalability**: Architecture that can handle growth

You have full authority to make any changes necessary to achieve these goals while maintaining code quality and following Laravel best practices.