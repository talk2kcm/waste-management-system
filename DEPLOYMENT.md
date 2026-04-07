# Deployment Guide

## Production Deployment Checklist

### Pre-Deployment

- [ ] Review all code for debug statements
- [ ] Update BASE_URL in `includes/init.php`
- [ ] Change all demo credentials
- [ ] Backup development database
- [ ] Test all features one more time
- [ ] Generate fresh database backup
- [ ] Update configuration files
- [ ] Run security audit

### Server Requirements

- PHP 7.4+ (8.0+ recommended)
- MySQL 5.7+ or MariaDB 10.2+
- Apache 2.4+ with mod_rewrite
- SSL Certificate (HTTPS)
- Minimum 1GB RAM
- Minimum 50MB disk space
- Regular backup system

## Deployment Steps

### 1. Upload Project Files

```bash
# Using FTP/SFTP
scp -r waste-management-system/ user@server:/var/www/

# Or using git
git clone https://your-repo.git /var/www/waste-management-system
```

### 2. Set Permissions

```bash
# Set correct ownership
chown -R www-data:www-data /var/www/waste-management-system

# Set correct permissions
chmod -R 755 /var/www/waste-management-system
chmod -R 775 /var/www/waste-management-system/assets

# Restrict sensitive directories
chmod 750 /var/www/waste-management-system/config
chmod 750 /var/www/waste-management-system/includes
```

### 3. Create Production Database

```bash
# Connect to MySQL
mysql -h localhost -u root -p

# Create database
CREATE DATABASE waste_management;
CREATE USER 'wms_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON waste_management.* TO 'wms_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Import schema
mysql -u wms_user -p waste_management < database.sql
```

### 4. Update Configuration

Edit `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'wms_user');              // Changed from 'root'
define('DB_PASS', 'strong_password_here'); // New password
define('DB_NAME', 'waste_management');
```

Edit `includes/init.php`:
```php
define('BASE_URL', 'https://yourdomain.com/waste-management-system/');
```

### 5. Configure Apache

Create `/etc/apache2/sites-available/waste-management.conf`:
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    
    DocumentRoot /var/www
    
    # Redirect HTTP to HTTPS
    Redirect permanent / https://yourdomain.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    
    DocumentRoot /var/www
    SSLEngine on
    SSLCertificateFile /etc/ssl/certs/your_cert.crt
    SSLCertificateKeyFile /etc/ssl/private/your_key.key
    
    <Directory /var/www/waste-management-system>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Enable configuration:
```bash
a2ensite waste-management
a2enmod ssl
a2enmod rewrite
systemctl restart apache2
```

### 6. Configure HTTPS/SSL

```bash
# Using Let's Encrypt (free)
sudo apt-get install certbot python3-certbot-apache
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# Auto-renewal
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer
```

### 7. Set Up Backups

Create backup script `/usr/local/bin/backup-wms.sh`:
```bash
#!/bin/bash

BACKUP_DIR="/backup/waste-management"
DATE=$(date +%Y%m%d_%H%M%S)

# Create directory
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u wms_user -p'password' waste_management > \
    $BACKUP_DIR/database_$DATE.sql

# Backup files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz \
    /var/www/waste-management-system

# Remove backups older than 30 days
find $BACKUP_DIR -type f -mtime +30 -delete

echo "Backup completed: $DATE"
```

Add to crontab:
```bash
# Run daily at 2 AM
0 2 * * * /usr/local/bin/backup-wms.sh >> /var/log/wms-backup.log 2>&1
```

### 8. Configure Firewall

```bash
# UFW (Uncomplicated Firewall)
ufw allow 22/tcp
ufw allow 80/tcp
ufw allow 443/tcp
ufw enable
```

### 9. Enable Logging

Create `/var/www/waste-management-system/logs` directory:
```bash
mkdir -p /var/www/waste-management-system/logs
chmod 775 /var/www/waste-management-system/logs
```

Configure PHP error logging in `php.ini`:
```ini
error_reporting = E_ALL
log_errors = On
error_log = /var/www/waste-management-system/logs/php_errors.log
```

### 10. Performance Optimization

#### Enable compression

In `.htaccess`:
```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript 
</IfModule>
```

#### Enable caching

In `.htaccess`:
```apache
<FilesMatch "\.(css|js|jpg|png|gif)$">
    Header set Cache-Control "max-age=31536000, public"
</FilesMatch>
```

#### Optimize database

```sql
OPTIMIZE TABLE Users;
OPTIMIZE TABLE waste_bins;
OPTIMIZE TABLE pickup_requests;
OPTIMIZE TABLE complaints;
OPTIMIZE TABLE payments;
OPTIMIZE TABLE audit_logs;

-- Create indexes
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_bins_status ON waste_bins(status);
CREATE INDEX idx_pickups_status ON pickup_requests(status);
```

## Post-Deployment

### 1. Verify Installation

```bash
# Check directory structure
ls -la /var/www/waste-management-system/

# Check permissions
ls -l /var/www/waste-management-system/config/

# Test database connection
mysql -u wms_user -p -e "USE waste_management; SHOW TABLES;"
```

### 2. Delete Demo Accounts

```sql
-- After verifying system works, delete demo users
DELETE FROM users WHERE email IN ('admin@waste.local', 'john@waste.local', 'jane@waste.local');
```

### 3. Create Production Admin Account

```php
// Run this once to create admin
$users_sql = "INSERT INTO users (first_name, last_name, email, phone, address, role, password_hash, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
// Set strong password using: password_hash('YourPassword123!', PASSWORD_BCRYPT)
```

### 4. Monitor and Test

- Test login with new admin account
- Check dashboard loads correctly
- Verify SSL certificate
- Run reports to test database
- Test contact form

## Monitoring

### Key Metrics to Monitor

```bash
# Disk usage
df -h /var/www

# Memory usage
free -h

# MySQL disk usage
du -sh /var/lib/mysql

# Apache logs
tail -f /var/log/apache2/access.log
tail -f /var/log/apache2/error.log

# PHP errors
tail -f /var/www/waste-management-system/logs/php_errors.log
```

### Set Up Alerts

```bash
# Monitor disk space
df -h / | grep -E "9[0-9]|100" && echo "ALERT: Disk space critical!" | mail -s "Server Alert" admin@example.com
```

## Maintenance Schedule

### Daily
- [ ] Check error logs
- [ ] Monitor system resources

### Weekly
- [ ] Backup database
- [ ] Review access logs
- [ ] Check for failed logins

### Monthly
- [ ] Security audit
- [ ] Database optimization
- [ ] Performance review
- [ ] Archive old logs

### Quarterly
- [ ] Create off-site backup
- [ ] Update PHP/MySQL
- [ ] Review user accounts
- [ ] Performance tuning

### Annually
- [ ] Full security audit
- [ ] Penetration testing
- [ ] Update SSL certificate
- [ ] Code review
- [ ] Disaster recovery test

## Troubleshooting

### Issue: 500 Internal Server Error

1. Check Apache error log:
```bash
tail -f /var/log/apache2/error.log
```

2. Check PHP error log:
```bash
tail -f /var/www/waste-management-system/logs/php_errors.log
```

3. Verify permissions:
```bash
ls -la /var/www/waste-management-system/config/
```

### Issue: Database Connection Failed

1. Verify credentials:
```bash
mysql -u wms_user -p waste_management
```

2. Check MySQL service:
```bash
systemctl status mysql
```

3. Verify database exists:
```bash
mysql -e "SHOW DATABASES;"
```

### Issue: HTTPS Certificate Error

```bash
# Renew certificate
certbot renew

# Check certificate details
openssl x509 -in /etc/letsencrypt/live/yourdomain.com/cert.pem -text -noout
```

## Rollback Procedure

If something goes wrong:

```bash
# Stop application
systemctl stop apache2

# Restore from backup
tar -xzf /backup/waste-management/files_20240101_020000.tar.gz -C /var/www/

# Restore database
mysql -u wms_user -p waste_management < /backup/waste-management/database_20240101_020000.sql

# Start application
systemctl start apache2
```

## Security Hardening

### 1. Disable Directory Listing
Already configured in `.htaccess`

### 2. Remove Sensitive Files
```bash
rm -f /var/www/waste-management-system/{README.md,SETUP.md,PROJECT_SUMMARY.md,database.sql}
```

### 3. Change PHP Settings

In `php.ini`:
```ini
display_errors = Off
expose_php = Off
session.use_strict_mode = On
session.use_only_cookies = On
session.cookie_httponly = On
session.cookie_secure = On
```

### 4. Configure Firewall Rules

```bash
# Block direct access to config/includes
ModSecurity rules or nginx location blocks
```

### 5. Regular Security Updates

```bash
# Auto security updates on Ubuntu/Debian
apt-get install unattended-upgrades
dpkg-reconfigure -plow unattended-upgrades
```

## Support & Documentation

- Check `README.md` for feature documentation
- Review `PROJECT_SUMMARY.md` for architecture details
- Check `SETUP.md` for initial setup help
- Review code comments for development

---

**Last Updated**: 2026
**Deployment Version**: 1.0
**Support**: Documentation provided in project
