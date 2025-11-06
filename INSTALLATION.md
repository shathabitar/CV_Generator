# CV Generator - Installation Guide

## 📋 Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & npm
- SQLite (default) or MySQL/PostgreSQL

## 🚀 Quick Installation

### 1. Clone & Setup
```bash
git clone <repository-url>
cd CVGenerator
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
```bash
# Create SQLite database (default)
touch database/database.sqlite

# Run migrations
php artisan migrate

# Seed database with sample data (optional)
php artisan db:seed
```

### 5. Storage Setup
```bash
# Create storage link for file uploads
php artisan storage:link
```

### 6. Build Assets
```bash
# Build frontend assets
npm run build

# Or for development
npm run dev
```

### 7. Optimize Application
```bash
# Run optimization command
php artisan app:optimize
```

## 🏃‍♂️ Running the Application

### Development Server
```bash
# Start Laravel development server
php artisan serve

# Or use the convenient composer script
composer run dev
```

### Production Deployment
```bash
# Optimize for production
composer run optimize

# Set environment to production in .env
APP_ENV=production
APP_DEBUG=false
```

## ⚙️ Configuration Options

### Database Configuration
Edit `.env` file:
```env
# For SQLite (default)
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

# For MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cv_generator
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Mail Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email
MAIL_FROM_NAME="CV Generator"
```

### Cache Configuration
```env
# File cache (default, good for single server)
CACHE_STORE=file

# Redis cache (recommended for production)
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## 🔧 Troubleshooting

### Common Issues

#### Permission Errors
```bash
# Fix storage permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### Database Connection Issues
```bash
# Check database file exists and is writable
ls -la database/database.sqlite

# Recreate database if needed
rm database/database.sqlite
touch database/database.sqlite
php artisan migrate
```

#### Asset Build Issues
```bash
# Clear npm cache
npm cache clean --force

# Reinstall dependencies
rm -rf node_modules package-lock.json
npm install
npm run build
```

#### Cache Issues
```bash
# Clear all caches
php artisan app:optimize
```

### Performance Optimization

#### Enable OPcache (Production)
Add to your PHP configuration:
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

#### Queue Workers (Production)
```bash
# Start queue worker
php artisan queue:work --daemon

# Or use supervisor for process management
```

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Composer Documentation](https://getcomposer.org/doc/)
- [Node.js Documentation](https://nodejs.org/docs/)

## 🆘 Getting Help

If you encounter issues:
1. Check the troubleshooting section above
2. Review Laravel logs: `storage/logs/laravel.log`
3. Check web server error logs
4. Ensure all prerequisites are met