# TickLyst Setup Guide

## Quick Start (5 minutes)

### Prerequisites
- PHP 8.0+
- MySQL 8.0+
- Web server (Apache/Nginx) or PHP built-in server

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/Guillaume0402/TickyList.git
   cd TickyList
   ```

2. **Configure environment**
   ```bash
   cp .env.example .env
   nano .env  # Edit database credentials
   ```

3. **Create database**
   ```bash
   mysql -u root -p
   ```
   ```sql
   CREATE DATABASE ticklyst CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   SOURCE database/schema.sql;
   SOURCE database/seed.sql;  -- Optional: demo data
   exit;
   ```

4. **Start the application**
   
   **Option A: PHP Built-in Server (Development)**
   ```bash
   cd public
   php -S localhost:8000
   ```
   Access: http://localhost:8000

   **Option B: Apache (Production)**
   ```apache
   <VirtualHost *:80>
       ServerName ticklyst.local
       DocumentRoot /path/to/TickyList/public
       
       <Directory /path/to/TickyList/public>
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

5. **Login**
   - Email: demo@ticklyst.com
   - Password: password123

## Production Deployment

### Security Checklist

- [ ] Change APP_ENV to "production" in .env
- [ ] Use strong database passwords
- [ ] Enable HTTPS/SSL
- [ ] Set proper file permissions (755 for directories, 644 for files)
- [ ] Disable PHP error display
- [ ] Enable PHP opcache
- [ ] Configure session security
- [ ] Set up database backups
- [ ] Configure firewall rules

### Recommended File Permissions

```bash
# Application files
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;

# Writable directories
chmod -R 775 storage/logs
chown -R www-data:www-data storage

# Executable scripts
chmod +x reminder.php
```

### Apache Configuration

```apache
<VirtualHost *:80>
    ServerName ticklyst.example.com
    DocumentRoot /var/www/ticklyst/public
    
    <Directory /var/www/ticklyst/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    # Deny access to sensitive files
    <FilesMatch "^\.">
        Require all denied
    </FilesMatch>
    
    # PHP settings
    php_value upload_max_filesize 10M
    php_value post_max_size 10M
    php_value memory_limit 128M
    php_value session.cookie_httponly 1
    php_value session.cookie_secure 1
    
    ErrorLog ${APACHE_LOG_DIR}/ticklyst-error.log
    CustomLog ${APACHE_LOG_DIR}/ticklyst-access.log combined
</VirtualHost>
```

### Nginx Configuration

```nginx
server {
    listen 80;
    server_name ticklyst.example.com;
    root /var/www/ticklyst/public;
    index index.php;
    
    # Security headers
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    # Deny access to sensitive files
    location ~ /\. {
        deny all;
    }
    
    location ~ /\.env {
        deny all;
    }
    
    location ~* \.(sql)$ {
        deny all;
    }
}
```

### Cron Setup (Reminders)

Add to crontab (`crontab -e`):

```bash
# Send email reminders every hour
0 * * * * /usr/bin/php /var/www/ticklyst/reminder.php >> /var/www/ticklyst/storage/logs/cron.log 2>&1
```

### Database Optimization

```sql
-- Add indexes for better performance
ALTER TABLE tasks ADD INDEX idx_user_status (user_id, status);
ALTER TABLE tasks ADD INDEX idx_user_deleted (user_id, deleted_at);

-- Regular maintenance
OPTIMIZE TABLE users;
OPTIMIZE TABLE projects;
OPTIMIZE TABLE tasks;
```

## Docker Deployment (Optional)

Create `docker-compose.yml`:

```yaml
version: '3.8'

services:
  web:
    image: php:8.1-apache
    ports:
      - "8000:80"
    volumes:
      - .:/var/www/html
    environment:
      - APP_ENV=development
    depends_on:
      - db

  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: ticklyst
      MYSQL_USER: ticklyst
      MYSQL_PASSWORD: ticklyst123
    ports:
      - "3306:3306"
    volumes:
      - mysql_data:/var/lib/mysql
      - ./database/schema.sql:/docker-entrypoint-initdb.d/1-schema.sql
      - ./database/seed.sql:/docker-entrypoint-initdb.d/2-seed.sql

volumes:
  mysql_data:
```

Run with:
```bash
docker-compose up -d
```

## Troubleshooting

### Common Issues

**1. Database Connection Failed**
- Verify .env credentials
- Check MySQL is running: `sudo service mysql status`
- Test connection: `mysql -u username -p -h localhost`

**2. 404 Errors**
- Ensure mod_rewrite is enabled (Apache)
- Check .htaccess file exists in public/
- Verify DocumentRoot points to public/ directory

**3. CSRF Token Validation Failed**
- Clear browser cookies
- Check session.save_path is writable
- Verify session is starting (check session_start() in index.php)

**4. Blank Page**
- Enable error display in .env: `APP_ENV=development`
- Check PHP error logs
- Verify all required PHP extensions are installed

**5. CSS/JS Not Loading**
- Check CDN URLs are accessible
- Verify style.css exists in public/assets/css/
- Check browser console for errors

### Required PHP Extensions

```bash
# Check installed extensions
php -m

# Required extensions
- pdo
- pdo_mysql
- session
- json
- mbstring
```

Install missing extensions (Ubuntu/Debian):
```bash
sudo apt-get install php8.1-mysql php8.1-mbstring
```

## Backup Strategy

### Database Backup

```bash
# Daily backup
mysqldump -u ticklyst -p ticklyst > backup-$(date +%Y%m%d).sql

# Automated backup script
#!/bin/bash
BACKUP_DIR="/var/backups/ticklyst"
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u ticklyst -ptickl yst123 ticklyst | gzip > "$BACKUP_DIR/ticklyst-$DATE.sql.gz"

# Keep only last 7 days
find $BACKUP_DIR -name "ticklyst-*.sql.gz" -mtime +7 -delete
```

### File Backup

```bash
# Backup application files
tar -czf ticklyst-files-$(date +%Y%m%d).tar.gz \
    --exclude='node_modules' \
    --exclude='.git' \
    --exclude='storage/logs/*.log' \
    /path/to/TickyList/
```

## Performance Tuning

### PHP Configuration (php.ini)

```ini
; Production settings
memory_limit = 256M
max_execution_time = 60
upload_max_filesize = 10M
post_max_size = 10M

; Session settings
session.cookie_httponly = 1
session.cookie_secure = 1
session.use_strict_mode = 1

; Opcache (recommended for production)
opcache.enable = 1
opcache.memory_consumption = 128
opcache.max_accelerated_files = 10000
opcache.validate_timestamps = 0
```

### MySQL Configuration (my.cnf)

```ini
[mysqld]
innodb_buffer_pool_size = 256M
max_connections = 100
query_cache_type = 1
query_cache_size = 32M
```

## Monitoring

### Health Check Endpoint

Create `public/health.php`:

```php
<?php
header('Content-Type: application/json');

$health = [
    'status' => 'ok',
    'timestamp' => date('c'),
    'checks' => []
];

// Database check
try {
    require_once __DIR__ . '/../config/env.php';
    loadEnv(__DIR__ . '/../.env');
    require_once __DIR__ . '/../config/db.php';
    $pdo = getDbConnection();
    $health['checks']['database'] = 'connected';
} catch (Exception $e) {
    $health['status'] = 'error';
    $health['checks']['database'] = 'failed';
}

// Disk space check
$free = disk_free_space('/');
$total = disk_total_space('/');
$percent = round(($free / $total) * 100);
$health['checks']['disk_space'] = $percent . '% free';

echo json_encode($health, JSON_PRETTY_PRINT);
```

Access: http://localhost:8000/health.php

## Support

For issues or questions:
1. Check this setup guide
2. Review the main README.md
3. Check application logs in storage/logs/
4. Open an issue on GitHub

## License

MIT License - Free to use for personal or commercial projects.
