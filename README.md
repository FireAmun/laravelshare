# 🚀 LaravelShare - Secure File Sharing Service

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)](CONTRIBUTING.md)

A modern, secure, and feature-rich file sharing service built with Laravel 11. Similar to WeTransfer, but self-hosted with enhanced security features and customization options.

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
- **User Authentication** - Secure login and registration
- **Admin Dashboard** - Comprehensive administration panel
- **User Profiles** - Manage account settings
- **Role-Based Access** - Admin and user roles
- **Account Verification** - Email verification system

### 📊 Analytics & Monitoring
- **Download Statistics** - Track file access and downloads
- **User Activity** - Monitor user behavior
- **Security Logs** - Detailed security event logging
- **System Monitoring** - Server health and performance
- **Admin Dashboard** - Real-time statistics and insights

### 🎨 User Experience
- **Responsive Design** - Works on all devices
- **Modern UI** - Clean, intuitive interface using Tailwind CSS
- **Dark Mode Ready** - Prepared for theme switching
- **Copy to Clipboard** - One-click link sharing
- **Mobile Optimized** - Touch-friendly mobile interface

## 🛠️ Technology Stack

- **Backend**: Laravel 11.x (PHP 8.2+)
- **Frontend**: Blade Templates, Tailwind CSS, Vanilla JavaScript
- **Database**: MySQL 8.0+ / PostgreSQL 13+
- **Storage**: Local filesystem (configurable for cloud)
- **Caching**: Redis (optional)
- **Queue**: Database/Redis
- **Icons**: Font Awesome 6

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- MySQL 8.0+ or PostgreSQL 13+
- Web server (Apache/Nginx)
- Redis (optional, for caching and queues)

## 🚀 Installation

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

### File Upload Settings

Edit `config/filesystems.php` and your `.env` file:

```env
# File upload limits (in MB)
FILE_MAX_SIZE=5

# Allowed file extensions (comma-separated)
ALLOWED_EXTENSIONS=pdf,doc,docx,xls,xlsx,ppt,pptx,txt,csv,rtf,jpg,jpeg,png,gif,bmp,webp,svg,zip,rar,7z,gz

# File encryption (true/false)
ENCRYPT_FILES=true
```

### Security Configuration

```env
# Rate limiting (requests per hour)
RATE_LIMIT_UPLOADS=10
RATE_LIMIT_DOWNLOADS=50

# Admin credentials (change these!)
ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=secure_password_here
```

### Email Configuration

```env
MAIL_MAILER=smtp
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
- Access admin dashboard
- Manage all users and files
- View system statistics
- Configure security settings
- Monitor activity logs
- Perform system maintenance

### Default Admin Account
After running seeders, you can login with:
- **Email**: admin@example.com
- **Password**: Change this in production!

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
expires_in_days: [optional integer]
max_downloads: [optional integer]
```

### Download Endpoint
```http
GET /d/{uuid}
```

### Authentication
All API endpoints support Laravel Sanctum authentication.

## 🐛 Troubleshooting

### Common Issues

1. **File upload fails**
   - Check PHP `upload_max_filesize` and `post_max_size`
   - Verify storage permissions
   - Check available disk space

2. **Files not downloading**
   - Verify storage symlink exists
   - Check file permissions
   - Review server error logs

3. **Admin dashboard not accessible**
   - Run `php artisan db:seed --class=AdminUserSeeder`
   - Clear application cache
   - Check user role in database

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

## 📞 Support

- **Issues**: [GitHub Issues](https://github.com/yourusername/laravelshare/issues)
- **Discussions**: [GitHub Discussions](https://github.com/yourusername/laravelshare/discussions)
- **Email**: support@yourdomain.com

## 🗺️ Roadmap

### Version 2.0 (Planned)
- [ ] File preview system
- [ ] Bulk upload functionality
- [ ] Email notifications
- [ ] API v2 with better documentation
- [ ] Mobile app (PWA)
- [ ] Advanced analytics
- [ ] Team collaboration features

### Version 2.1 (Future)
- [ ] Cloud storage integration
- [ ] Video/audio streaming
- [ ] Real-time collaboration
- [ ] AI-powered features
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

**Made with ❤️ using Laravel**

*Star ⭐ this repository if you find it helpful!*
