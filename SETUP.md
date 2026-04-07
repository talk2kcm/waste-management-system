# Quick Start Guide - Waste Management System

## Installation Steps (5 minutes)

### Step 1: Extract Project
Extract the project files to your web server:
- **XAMPP**: `C:\xampp\htdocs\waste-management-system\`
- **WAMP**: `C:\wamp64\www\waste-management-system\`
- **LAMP**: `/var/www/html/waste-management-system/`

### Step 2: Create Database

#### Using phpMyAdmin (Easiest)
1. Open: http://localhost/phpmyadmin
2. Click "New" → Enter `waste_management` → Click Create
3. Click "Import" tab
4. Click "Choose File" → Select `database.sql` from project folder
5. Click "Go"

#### Using Command Line
```bash
mysql -u root -p
CREATE DATABASE waste_management;
USE waste_management;
source /path/to/database.sql;
EXIT;
```

### Step 3: Verify Configuration
Open `config/database.php` and verify (usually defaults work):
```php
define('DB_HOST', 'localhost');     // Usually correct
define('DB_USER', 'root');           // Usually correct 
define('DB_PASS', '');               // Usually empty for local
define('DB_NAME', 'waste_management'); // Should match above
```

### Step 4: Access Application
Open your browser:
- http://localhost/waste-management-system/

### Step 5: Login
Use demo credentials:
- **Email**: `admin@waste.local`
- **Password**: `Admin@123`

> **Note**: If login fails with "Invalid credentials", see **Troubleshooting** section below.

## That's It! 🎉

Your waste management system is now running!

## First Things To Do

1. **Change Admin Password**
   - Go to Users → Edit Admin
   - Update password
   - Save

2. **Customize Company Name**
   - Edit `includes/header.php`
   - Change `<h1 class="logo">♻ Waste Management</h1>`

3. **Add Your Data**
   - Add waste bins (Modules → Bins)
   - Add staff members (Users)
   - Add residents (Users)

4. **Test Features**
   - Login as different roles
   - Create sample data
   - Generate reports

## File Structure Overview

```
waste-management-system/
├── config/          ← Database configuration
├── includes/        ← Shared files (header, footer, etc.)
├── modules/         ← Each feature has its own folder
├── assets/css/      ← Styling
├── database.sql     ← Database schema
├── README.md        ← Full documentation
└── Various PHP pages (login, dashboard, etc.)
```

## Accessing Different Areas

### Admin Dashboard
- http://localhost/waste-management-system/dashboard.php

### User Management
- http://localhost/waste-management-system/modules/users/index.php

### Waste Bins
- http://localhost/waste-management-system/modules/bins/index.php

### Pickup Requests
- http://localhost/waste-management-system/modules/pickups/index.php

### Complaints
- http://localhost/waste-management-system/modules/complaints/index.php

### Staff Management
- http://localhost/waste-management-system/modules/staff/index.php

### Payments
- http://localhost/waste-management-system/modules/payments/index.php

### Reports
- http://localhost/waste-management-system/modules/reports/index.php

## Common Issues & Fixes

### Issue: Blank Screen / White Page
**Solution**: 
- Check PHP error logs
- Verify `config/database.php` settings
- Ensure MySQL is running

### Issue: "Database Connection Failed"
**Solution**:
- Verify MySQL is running
- Check credentials in `config/database.php`
- Ensure database `waste_management` exists

### Issue: Styling Looks Broken
**Solution**:
- Hard refresh: Ctrl+F5 (Windows) or Cmd+Shift+R (Mac)
- Clear browser cache
- Verify `assets/css/style.css` exists

### Issue: Login Not Working / Invalid Credentials
**Solution**:
1. Open the debug tool: http://localhost/waste-management-system/debug-login.php
2. This will show you:
   - Database connection status
   - All users in the database
   - Password verification results
   - Exact SQL command to fix if needed
3. **If password verification fails:**
   - Copy the SQL command shown
   - Go to phpMyAdmin → SQL tab
   - Paste and click Go
4. Try login again
5. **Delete `debug-login.php` file after testing** (security)

## Next Steps

1. Read full `README.md` for detailed documentation
2. Explore all modules to understand features
3. Customize the application for your needs
4. Add real data to the system
5. Set up regular backups

## Creating New Admin Account

1. Login with default admin
2. Go to Users → Add User
3. Fill in details:
   - First Name: (your name)
   - Last Name: (your name)
   - Email: (unique email)
   - Password: (strong password)
   - Role: Admin
4. Click "Add User"
5. You can now login with this account

## Default Accounts (Delete After Setup!)

These are sample accounts. Create your own and delete these:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@waste.local | Admin@123 |
| Staff | john@waste.local | Admin@123 |
| Resident | jane@waste.local | Admin@123 |

⚠️ **Important**: Create proper accounts and delete demo accounts before production!

## Support

If you encounter issues:
1. Check browser console (F12 → Console)
2. Check PHP error logs
3. Verify database connection
4. Review README.md for troubleshooting
5. Check that all files are in correct locations

---

**Ready to start?** Login now: http://localhost/waste-management-system/
