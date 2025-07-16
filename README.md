# 🚀 LaravelShare - Secure File Sharing Service

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Deployed](https://img.shields.io/badge/Deployed-Render-success.svg)](https://laravelshare.onrender.com)

A modern, secure, and feature-rich file sharing service built with Laravel 11. Similar to WeTransfer, but with enhanced security features and admin dashboard. **🌐 Live Demo: [https://laravelshare.onrender.com](https://laravelshare.onrender.com)**

## 🌟 Features

### 🔒 Security First
- **Password Protection** - Secure files with custom passwords
- **File Encryption** - Files encrypted at rest using AES-256
- **Rate Limiting** - Prevents abuse with configurable limits
- **Virus Scanning** - Optional malware detection
- **Activity Logging** - Complete audit trail of all actions
- **IP Restrictions** - Control access by IP address
- **CSRF Protection** - Built-in Laravel security

### 📁 File Management
- **Drag & Drop Upload** - Modern file upload interface
- **File Size Limits** - Configurable upload limits (default 5MB)
- **File Type Restrictions** - Whitelist allowed file types
- **Auto-Expiration** - Files automatically deleted after set time
- **Download Limits** - Control maximum number of downloads
- **Bulk Operations** - Manage multiple files at once

### 👤 User Management
- **User Authentication** - Secure login and registration with Laravel Breeze
- **Admin Dashboard** - Comprehensive administration panel with user management
- **User Profiles** - Account settings and file management
- **Role-Based Access** - Admin and regular user roles
- **Admin Login** - Separate admin authentication portal (`/admin/login`)

### 📊 Analytics & Monitoring
- **Download Statistics** - Track file access and downloads
- **User Activity** - Monitor user behavior and system usage
- **Admin Analytics** - File management and user statistics
- **System Monitoring** - Real-time system health
- **Activity Logs** - Comprehensive logging system for debugging

### 🎨 User Experience
- **Responsive Design** - Mobile-first responsive layout
- **Modern UI** - Clean interface using Tailwind CSS and Alpine.js (replaced with vanilla JS)
- **About Page** - Developer information and project details
- **Copy to Clipboard** - One-click link sharing functionality
- **Mobile Optimized** - Touch-friendly interface for all devices

## 🛠️ Technology Stack

- **Backend**: Laravel 11.x (PHP 8.2+)
- **Frontend**: Blade Templates, Tailwind CSS, Vanilla JavaScript (replaced Alpine.js)
- **Database**: PostgreSQL 13+ (MySQL 8.0+ supported)
- **Deployment**: Docker, Render Cloud Platform
- **Storage**: Local filesystem with cloud-ready configuration
- **Authentication**: Laravel Breeze with custom admin authentication
- **Icons**: Font Awesome 6

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- PostgreSQL 13+ (MySQL 8.0+ also supported)
- Web server (Apache/Nginx)
- Docker (for deployment)

## 🚀 Quick Start & Deployment

### 🌐 Live Demo
Visit our deployed application: **[https://laravelshare.onrender.com](https://laravelshare.onrender.com)**

### 📦 Docker Deployment (Render/Production)

This project is configured for easy deployment to Render using Docker:

1. **Fork this repository**
2. **Connect to Render**:
   - Create new Web Service on [Render](https://render.com)
   - Connect your GitHub repository
   - Set build command: `docker build`
   - Set start command: `docker run`

3. **Environment Variables** (set in Render dashboard):
   ```env
   APP_NAME="Laravel File Share"
   APP_ENV=production
   APP_KEY="base64:7cKsxNhWv6iZDF08RhttrlyWK7qc1otlqEwvfrtnoHs="
   APP_DEBUG=false
   APP_URL=https://your-app-name.onrender.com
   
   # PostgreSQL Database (provided by Render)
   DB_CONNECTION=pgsql
   DB_HOST=your-db-host
   DB_PORT=5432
   DB_DATABASE=your-db-name
   DB_USERNAME=your-db-user
   DB_PASSWORD=your-db-password
   
   # File Upload Settings
   MAX_FILE_SIZE=5242880
   UPLOADS_PER_HOUR=5
   DOWNLOADS_PER_HOUR=25
   ```

### 🐳 Docker Files Included
- `Dockerfile` - Complete PHP 8.2 + Apache + Node.js setup
- `docker/startup.sh` - Automated Laravel optimization and migrations
- `docker/apache-config.conf` - Apache configuration for Laravel

## 🚀 Local Development

### Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/FireAmun/laravelshare
   cd laravelshare
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   Edit `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravelshare
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   
   # File Upload Settings (optimized for free hosting)
   MAX_FILE_SIZE=5242880  # 5MB
   UPLOADS_PER_HOUR=5
   DOWNLOADS_PER_HOUR=25
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Create storage symlink**
   ```bash
   php artisan storage:link
   ```

8. **Build assets**
   ```bash
   npm run build
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` to access the application.

### 🔑 Admin Access

#### Development
After running seeders, create an admin user:
```bash
php artisan tinker
```
```php
$user = \App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => bcrypt('admin123'),
    'is_admin' => true
]);
```

#### Production
Access admin panel at: `https://laravelshare.onrender.com/admin/login`

### Production Deployment

1. **Set environment to production**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   ```

2. **Optimize for production**
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   npm run build
   ```

3. **Set up web server**
   Point your web server document root to the `public` directory.

4. **Set permissions**
   ```bash
   chown -R www-data:www-data storage bootstrap/cache
   chmod -R 775 storage bootstrap/cache
   ```

## ⚙️ Configuration

### File Upload Settings (Optimized for Free Hosting)

Edit your `.env` file:

```env
# File upload limits (in bytes) - 5MB default for free hosting
MAX_FILE_SIZE=5242880

# Rate limiting (optimized for free tier)
UPLOADS_PER_HOUR=5
DOWNLOADS_PER_HOUR=25

# Security settings
LOGIN_ATTEMPTS=3
REMEMBER_TOKEN_LIFETIME=525600
TWO_FACTOR_ENABLED=false

# File encryption and scanning (disabled for performance)
ENCRYPT_FILES=false
SCAN_FILES_FOR_MALWARE=false
```

### Security Configuration

```env
# Rate limiting (requests per hour)
UPLOADS_PER_HOUR=5
DOWNLOADS_PER_HOUR=25

# Admin access
# Create admin users through tinker or database seeder
```

### Email Configuration (Optional)

```env
MAIL_MAILER=log  # Uses log driver by default
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

## 👥 User Roles

### Regular Users
- Upload files with security options
- Manage their uploaded files
- View download statistics
- Update profile settings

### Administrators
- Access comprehensive admin dashboard
- Manage all users and their files
- View system statistics and analytics
- Configure security settings and limits
- Monitor activity logs and system health
- Perform cleanup and maintenance tasks
- Access admin panel at `/admin/login`

### Default Admin Setup
Create admin users manually using Laravel Tinker:
```bash
php artisan tinker
```
```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com', 
    'password' => bcrypt('secure_password'),
    'is_admin' => true
]);
```

## 📖 Usage

### File Upload Process

1. **Upload File**: Drag & drop or select file (up to 5MB)
2. **Set Security**: Optional password and expiration
3. **Configure Limits**: Set download limits if needed
4. **Generate Link**: Get shareable URL with copy button
5. **Share**: Send link to recipients

### File Download Process

1. **Access Link**: Recipient clicks shared URL
2. **Password Entry**: Enter password if required
3. **Download**: File downloads automatically
4. **Tracking**: Download is logged and counted

### Admin Functions

- **User Management**: Create, edit, delete users
- **File Management**: View, delete any files
- **Security Monitoring**: Review security logs
- **System Settings**: Configure global settings
- **Statistics**: View usage analytics

## 🔧 API Documentation

### Upload Endpoint
```http
POST /upload
Content-Type: multipart/form-data

file: [binary]
password: [optional string]
expires_in_days: [optional integer, default: 7]
max_downloads: [optional integer, default: unlimited]
```

### Download Endpoint
```http
GET /d/{uuid}
POST /d/{uuid}  # With password if required
```

### Admin Routes
- `/admin/login` - Admin authentication
- `/admin/dashboard` - Admin panel
- `/admin/users` - User management  
- `/admin/files` - File management

## 🐛 Troubleshooting

### Common Issues

1. **500 Server Error**
   - Check APP_KEY is properly set with quotes
   - Verify database connection settings
   - Ensure PostgreSQL extension is installed
   - Check file permissions on storage directories

2. **File upload fails**
   - Check PHP `upload_max_filesize` and `post_max_size`
   - Verify storage permissions (775)
   - Check available disk space
   - Review MAX_FILE_SIZE setting

3. **Admin dashboard not accessible**
   - Create admin user using Tinker
   - Check `is_admin` field in database
   - Clear application cache
   - Verify admin routes are registered

4. **Database connection issues**
   - Verify PostgreSQL credentials in environment
   - Check database server is running
   - Ensure PostgreSQL PHP extensions are installed

### Deployment Debugging

Visit debugging endpoints (remove after fixing):
- `/debug-enhanced.php` - Comprehensive system info
- `/logs.php` - Error logs viewer

### Debug Mode

Enable debug mode in development:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

Run specific test types:
```bash
# Unit tests
php artisan test --testsuite=Unit

# Feature tests
php artisan test --testsuite=Feature
```

## 🤝 Contributing

We welcome contributions! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Laravel framework and community
- Tailwind CSS for styling
- Font Awesome for icons
- All contributors and testers

## 📞 Support & Links

- **Live Demo**: [https://laravelshare.onrender.com](https://laravelshare.onrender.com)
- **Admin Panel**: [https://laravelshare.onrender.com/admin/login](https://laravelshare.onrender.com/admin/login)
- **Issues**: [GitHub Issues](https://github.com/FireAmun/laravelshare/issues)
- **Repository**: [GitHub Repository](https://github.com/FireAmun/laravelshare)

## 🚀 Deployment Status

- ✅ **Production Ready** - Deployed on Render
- ✅ **Docker Configured** - Complete containerization
- ✅ **PostgreSQL Support** - Production database
- ✅ **Admin Dashboard** - Fully functional
- ✅ **File Upload/Download** - Core functionality working
- ✅ **Responsive UI** - Mobile-friendly interface

## 🗺️ Roadmap

### ✅ Completed (v1.0)
- [x] Core file upload/download functionality
- [x] User authentication and registration
- [x] Admin dashboard and user management
- [x] Password protection for files
- [x] File expiration and download limits
- [x] Responsive UI with Tailwind CSS
- [x] Docker deployment configuration
- [x] PostgreSQL database support
- [x] Rate limiting and security features

### 🚧 Version 1.1 (In Progress)
- [ ] Email notifications for file uploads
- [ ] Advanced file analytics
- [ ] Bulk file operations
- [ ] API improvements and documentation

### 📋 Version 2.0 (Planned)
- [ ] File preview system
- [ ] Cloud storage integration (AWS S3, etc.)
- [ ] Team collaboration features
- [ ] Mobile app (PWA)
- [ ] Advanced analytics dashboard
- [ ] Multi-language support

## 📊 Performance

- **Upload Speed**: Optimized for large files
- **Download Speed**: Direct file serving
- **Storage**: Efficient file organization
- **Caching**: Redis integration for performance
- **CDN Ready**: Easy integration with CDNs

## 🔒 Security

This application follows security best practices:
- Input validation and sanitization
- SQL injection prevention
- XSS protection
- CSRF protection
- Rate limiting
- File encryption
- Secure file handling
- Activity logging

For security issues, please email security@yourdomain.com

---

**🌟 Star this repository if you find it helpful!**

**Made with ❤️ using Laravel 11 | Deployed on Render**

*Visit the live demo: [https://laravelshare.onrender.com](https://laravelshare.onrender.com)*
