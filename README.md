# Waste Management System

A complete, professional waste management web application built with plain PHP, MySQL, HTML, and CSS - **no frameworks or packages required**.

## Features

- ✅ **User Authentication** - Secure login/logout system with role-based access
- ✅ **Multi-role Support** - Admin, Staff, and Resident roles with different permissions
- ✅ **Dashboard** - Summary statistics and recent activities
- ✅ **User Management** - Add, edit, delete, and manage users
- ✅ **Waste Bin Management** - Track bins, locations, fill levels, and status
- ✅ **Pickup Requests** - Residents can request, admins can assign to staff
- ✅ **Complaint Management** - File and track complaints
- ✅ **Staff Management** - Manage collection staff and assign routes
- ✅ **Payments & Billing** - Track payment status and generate invoices
- ✅ **Reports** - Generate pickups, complaints, and payment reports
- ✅ **Contact Form** - Public feedback form
- ✅ **Responsive Design** - Fully responsive on desktop, tablet, and mobile
- ✅ **Security** - Prepared statements, password hashing, session-based auth

## Project Structure

```
waste-management-system/
├── assets/
│   └── css/
│       └── style.css              # Main stylesheet
├── config/
│   └── database.php               # Database configuration
├── includes/
│   ├── init.php                   # Application initialization
│   ├── session_check.php          # Session and auth functions
│   ├── header.php                 # HTML header template
│   ├── footer.php                 # HTML footer template
│   └── sidebar.php                # Navigation sidebar
├── modules/
│   ├── users/
│   │   ├── index.php              # User list
│   │   ├── add.php                # Add user
│   │   └── edit.php               # Edit user
│   ├── bins/
│   │   ├── index.php              # Waste bins list
│   │   ├── add.php                # Add bin
│   │   └── edit.php               # Edit bin
│   ├── pickups/
│   │   ├── index.php              # Pickup requests list
│   │   ├── add.php                # Create request
│   │   ├── request.php            # Resident request shortcut
│   │   └── edit.php               # Edit/assign request
│   ├── complaints/
│   │   ├── index.php              # Complaints list
│   │   ├── create.php             # File complaint
│   │   ├── view.php               # View complaint
│   │   └── edit.php               # Edit complaint
│   ├── staff/
│   │   ├── index.php              # Staff management
│   │   └── assign.php             # Assign routes
│   ├── payments/
│   │   ├── index.php              # Payments list
│   │   ├── add.php                # Add payment
│   │   ├── edit.php               # Edit payment
│   │   └── view.php               # View invoice
│   └── reports/
│       └── index.php              # Reports generation
├── database.sql                   # Database schema
├── index.php                      # Public home page
├── login.php                      # Login page
├── logout.php                     # Logout page
├── dashboard.php                  # Admin/staff dashboard
├── contact.php                    # Contact/feedback page
└── README.md                      # This file
```

## System Requirements

- **Web Server**: Apache with mod_rewrite enabled (or any PHP-capable server)
- **PHP**: 7.4 or higher
- **MySQL**: 5.7 or higher
- **Browser**: Modern browser (Chrome, Firefox, Safari, Edge)

## Installation & Setup

### Option 1: Using XAMPP (Windows/Mac/Linux)

1. **Download XAMPP** from https://www.apachefriends.org/

2. **Install XAMPP** and start Apache and MySQL from the Control Panel

3. **Place Project**:
   - Copy `waste-management-system` folder to `C:\xampp\htdocs\` (Windows) or `/Applications/XAMPP/htdocs/` (Mac)

4. **Create Database**:
   - Open phpMyAdmin: http://localhost/phpmyadmin
   - Click "New" → Enter database name `waste_management` → Create
   - Go to "Import" tab
   - Select `database.sql` file from the project → Import

5. **Configure Database** (if needed):
   - Open `config/database.php`
   - Verify or update these settings:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'waste_management');
     ```

6. **Access Application**:
   - Open browser: http://localhost/waste-management-system/

### Option 2: Using WAMP (Windows)

1. **Download WAMP** from http://www.wampserver.com/

2. **Install and start WAMP**

3. **Place Project**:
   - Copy folder to `C:\wamp64\www\waste-management-system\`

4. **Create Database**:
   - Access phpMyAdmin through WAMP interface
   - Create `waste_management` database
   - Import `database.sql`

5. **Access**: http://localhost/waste-management-system/

### Option 3: Using LAMP (Linux)

1. **Install Dependencies**:
   ```bash
   sudo apt-get update
   sudo apt-get install apache2 php mysql-server php-mysql
   ```

2. **Enable mod_rewrite**:
   ```bash
   sudo a2enmod rewrite
   sudo systemctl restart apache2
   ```

3. **Place Project**:
   ```bash
   sudo cp -r waste-management-system /var/www/html/
   sudo chown -R www-data:www-data /var/www/html/waste-management-system
   ```

4. **Create Database**:
   ```bash
   mysql -u root -p
   CREATE DATABASE waste_management;
   USE waste_management;
   SOURCE /var/www/html/waste-management-system/database.sql;
   EXIT;
   ```

5. **Access**: http://localhost/waste-management-system/

## Demo Credentials

After installation, use these test accounts to login:

### Admin Account
- **Email**: `admin@waste.local`
- **Password**: `Admin@123`
- **Role**: Administrator (full system access)

### Staff Account
- **Email**: `john@waste.local`
- **Password**: `Admin@123`
- **Role**: Staff (manage pickups, bins, complaints)

### Resident Account
- **Email**: `jane@waste.local`
- **Password**: `Admin@123`
- **Role**: Resident (request pickups, file complaints, view payments)

## User Roles & Permissions

### Admin
- Full system access
- Manage users (create, edit, delete)
- Manage waste bins
- View and manage pickup requests
- Review and manage complaints
- Manage staff and route assignments
- View and manage payments
- Generate reports

### Staff
- View dashboard
- View waste bins
- View and update pickup requests
- View complaints
- Generate reports

### Resident
- View personal dashboard (limited)
- Request waste pickups
- File complaints
- View personal payment history
- Submit contact form

## Database Schema

### Tables

1. **users** - User accounts and profiles
2. **waste_bins** - Waste bin locations and information
3. **pickup_requests** - Pickup request records
4. **complaints** - Complaint records
5. **staff_assignments** - Routes and task assignments
6. **payments** - Payment/invoice records
7. **audit_logs** - Activity logging

## Security Features

- ✅ Password hashing using bcrypt (PHP's password_hash)
- ✅ Prepared statements prevent SQL injection
- ✅ Session-based authentication
- ✅ Role-based access control (RBAC)
- ✅ Output escaping (htmlspecialchars)
- ✅ Activity logging for auditing
- ✅ Input validation and sanitization
- ✅ Account status verification (active/inactive/suspended)

## Features in Detail

### Authentication System
- Secure login with email and password
- Session management
- Role-based routing
- Logout functionality
- Activity logging

### Dashboard
- Statistics cards showing totals
- Recent pickup requests
- Recent complaints
- Quick status overview

### User Management
- Add new users (admin only)
- Edit user details
- Delete users (except self)
- Filter by role and status
- Search functionality

### Waste Bin Management
- Add waste bins with capacity
- Track fill levels
- Assign to zones/areas
- Set status (active/maintenance/inactive)
- Location tracking with coordinates

### Pickup Management
- Residents request pickups
- Admin assigns to staff
- Track status through workflow
- Set urgency levels
- Assign dates and times

### Complaint Management
- File complaints through form
- Categorize issues
- Set priority levels
- Assign to staff for resolution
- Track resolution progress

### Staff Management
- View staff members
- Assign routes and zones
- Track assigned pickups
- Manage staff status

### Payment Management
- Create payment records
- Track payment status
- Generate invoices
- Record payment dates
- Payment reports

### Reports
- Pickup request reports
- Complaint reports
- Payment reports
- Date range filtering
- Printable reports

## Customization

### Change Application Name/Logo
Edit the header in `includes/header.php`:
```php
<h1 class="logo">♻ Your Company Name</h1>
```

### Change Color Scheme
Edit CSS variables in `assets/css/style.css`:
```css
:root {
    --primary-color: #2ecc71;    /* Change green color */
    --secondary-color: #3498db;  /* Change blue color */
    --danger-color: #e74c3c;     /* Change red color */
    /* ... more colors ... */
}
```

### Change Database Settings
Edit `config/database.php`:
```php
define('DB_HOST', 'your_host');
define('DB_USER', 'your_user');
define('DB_PASS', 'your_password');
define('DB_NAME', 'your_database');
```

### Change Base URL
Edit `includes/init.php`:
```php
define('BASE_URL', 'http://your-domain.com/waste-management-system/');
```

## Troubleshooting

### Issue: "Connection failed: No such file or directory"
**Solution**: Ensure MySQL is running and credentials in `config/database.php` are correct.

### Issue: "Call to undefined function password_hash()"
**Solution**: Update to PHP 7.4+. The system requires PHP 7.4 or higher.

### Issue: "Access denied for user 'root'@'localhost'"
**Solution**: Check MySQL credentials. Update `config/database.php` with correct username and password.

### Issue: Sidebar not appearing or styling issues
**Solution**: Clear browser cache (Ctrl+F5 on Windows, Cmd+Shift+R on Mac) and verify `assets/css/style.css` is accessible.

### Issue: "database.sql import fails"
**Solution**: Ensure the database `waste_management` was created first, then import the SQL file.

## Adding New Users

1. Login as Admin
2. Click "Users" in sidebar
3. Click "+ Add User"
4. Fill in details and select role
5. Set password
6. Click "Add User"

## Common Tasks

### Create Admin User
- Go to Users → Add User
- Set Role to "Admin"
- Share credentials securely

### Register New Resident
- Go to Users → Add User
- Set Role to "Resident"
- Share login credentials

### Assign Staff to Route
- Go to Staff Management
- Click "Assign" on staff member
- Fill route details
- Click "Assign Route"

### Generate Payment Report
- Go to Reports
- Select "Payments"
- Set date range
- Click "Generate"
- Click "Print" to print

## support & Maintenance

### Regular Backups
Backup your database regularly:
```bash
# Linux/Mac
mysqldump -u root -p waste_management > backup.sql

# Restore
mysql -u root -p waste_management < backup.sql
```

### Logs
Check activity logs in the database:
- Find all logs: `SELECT * FROM audit_logs;`
- User-specific logs: `SELECT * FROM audit_logs WHERE user_id = 1;`

### Database Optimization
Run periodically:
```sql
OPTIMIZE TABLE users;
OPTIMIZE TABLE waste_bins;
OPTIMIZE TABLE pickup_requests;
OPTIMIZE TABLE complaints;
OPTIMIZE TABLE payments;
```

## File Permissions (Linux)

For proper operation, ensure correct permissions:
```bash
sudo chmod -R 755 /var/www/html/waste-management-system
sudo chmod -R 775 /var/www/html/waste-management-system/assets
```

## Code Standards

- **Prepared Statements**: Used for all database queries
- **Session Management**: Centralized in `includes/session_check.php`
- **DRY Principle**: Reusable includes for header, footer, sidebar
- **HTML Escaping**: All user output escaped with `escape()` or `htmlspecialchars()`
- **Error Handling**: Try-catch blocks and user-friendly messages
- **Comments**: Detailed comments for code documentation

## License

This project is provided as-is for educational purposes.

## Next Steps

1. ✅ Install and setup
2. ✅ Test with demo credentials
3. ✅ Create admin account
4. ✅ Add real users
5. ✅ Start entering data
6. ✅ Customize branding
7. ✅ Deploy to production

## Support

For issues or questions:
1. Check the Troubleshooting section
2. Review code comments
3. Check browser console for JavaScript errors
4. Verify database connection in `config/database.php`

---

**Developed**: 2026  
**Version**: 1.0  
**PHP Version**: 7.4+  
**MySQL Version**: 5.7+
