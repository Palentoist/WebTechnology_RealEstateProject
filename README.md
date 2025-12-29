# Real Estate Management System

A comprehensive web-based real estate management platform built with Laravel framework for managing property listings, agents, and client interactions.

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [Running the Application](#running-the-application)
- [Project Structure](#project-structure)
- [API Documentation](#api-documentation)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

## 🎯 Overview

This Real Estate Management System is a full-stack web application designed to streamline property management operations. It provides a robust platform for listing properties, managing client inquiries, tracking sales, and handling real estate transactions efficiently.

## ✨ Features

### Core Functionality
- **Property Management**: Create, read, update, and delete property listings
- **User Authentication**: Secure login system for admins, agents, and clients
- **Search & Filter**: Advanced property search with multiple filter options
- **Image Gallery**: Multiple image uploads for each property
- **Agent Management**: Dedicated profiles for real estate agents
- **Inquiry System**: Client inquiry management and tracking
- **Dashboard**: Analytics and reporting for business insights
- **Responsive Design**: Mobile-friendly interface using Bootstrap

### Additional Features
- Property categorization (residential, commercial, rental, sale)
- Location-based search
- Price range filtering
- Featured property listings
- Contact form integration
- Email notifications
- Activity logs

## 🛠️ Tech Stack

### Backend
- **Framework**: Laravel 10.x
- **Language**: PHP 8.1+
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Sanctum/Breeze

### Frontend
- **Framework**: Blade Templates
- **CSS Framework**: Bootstrap 5.x
- **JavaScript**: Vanilla JS / jQuery
- **Build Tool**: Vite

### Development Tools
- **Dependency Manager**: Composer
- **Package Manager**: NPM
- **Version Control**: Git

## 📦 Prerequisites

Before you begin, ensure you have the following installed:

- PHP >= 8.1
- Composer >= 2.x
- Node.js >= 16.x
- NPM >= 8.x
- MySQL >= 8.0
- Git

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/Palentoist/WebTechnology_RealEstateProject.git
cd WebTechnology_RealEstateProject
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Configuration

Copy the example environment file and generate application key:

```bash
cp .env.example .env
php artisan key:generate
```

## ⚙️ Configuration

### Environment Variables

Edit the `.env` file and configure the following:

```env
# Application
APP_NAME="Real Estate Management"
APP_ENV=local
APP_KEY=base64:your-generated-key
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=realestate_db
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Mail Configuration (Optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@realestate.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### File Storage

Configure file storage for property images:

```bash
php artisan storage:link
```

## 🗄️ Database Setup

### 1. Create Database

Create a MySQL database named `realestate_db`:

```sql
CREATE DATABASE realestate_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Run Migrations

Execute database migrations to create tables:

```bash
php artisan migrate
```

### 3. Seed Database (Optional)

Populate the database with sample data:

```bash
php artisan db:seed
```

## 🏃 Running the Application

### Development Server

Start the Laravel development server:

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

### Build Frontend Assets

In a separate terminal, compile frontend assets:

```bash
# Development mode with hot reload
npm run dev

# Production build
npm run build
```

### Background Queue Worker (Optional)

If using queues for email notifications:

```bash
php artisan queue:work
```

## 📁 Project Structure

```
WebTechnology_RealEstateProject/
├── app/
│   ├── Console/           # Artisan commands
│   ├── Exceptions/        # Exception handlers
│   ├── Http/
│   │   ├── Controllers/   # Application controllers
│   │   ├── Middleware/    # HTTP middleware
│   │   └── Requests/      # Form request validators
│   ├── Models/            # Eloquent models
│   └── Providers/         # Service providers
├── bootstrap/             # Framework bootstrap files
├── config/                # Configuration files
├── database/
│   ├── factories/         # Model factories
│   ├── migrations/        # Database migrations
│   └── seeders/           # Database seeders
├── public/                # Public assets (images, CSS, JS)
├── resources/
│   ├── css/               # CSS source files
│   ├── js/                # JavaScript source files
│   └── views/             # Blade templates
├── routes/
│   ├── api.php           # API routes
│   ├── channels.php      # Broadcasting channels
│   ├── console.php       # Console routes
│   └── web.php           # Web routes
├── storage/
│   ├── app/              # Application storage
│   ├── framework/        # Framework files
│   └── logs/             # Application logs
├── tests/                # Automated tests
├── .env.example          # Environment variables template
├── artisan               # Artisan CLI
├── composer.json         # PHP dependencies
├── package.json          # Node dependencies
├── phpunit.xml           # PHPUnit configuration
└── vite.config.js        # Vite configuration
```

## 📚 API Documentation

### Authentication Endpoints

#### Register User
```http
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "client"
}
```

#### Login
```http
POST /api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

### Property Endpoints

#### Get All Properties
```http
GET /api/properties?page=1&per_page=10
Authorization: Bearer {token}
```

#### Get Single Property
```http
GET /api/properties/{id}
Authorization: Bearer {token}
```

#### Create Property
```http
POST /api/properties
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Modern Villa",
  "description": "Beautiful 3-bedroom villa",
  "price": 500000,
  "location": "Downtown",
  "property_type": "residential",
  "status": "available",
  "bedrooms": 3,
  "bathrooms": 2,
  "area": 2500,
  "images": ["image1.jpg", "image2.jpg"]
}
```

#### Update Property
```http
PUT /api/properties/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Updated Villa Name",
  "price": 550000
}
```

#### Delete Property
```http
DELETE /api/properties/{id}
Authorization: Bearer {token}
```

### Inquiry Endpoints

#### Create Inquiry
```http
POST /api/inquiries
Authorization: Bearer {token}
Content-Type: application/json

{
  "property_id": 1,
  "message": "I'm interested in this property",
  "preferred_contact": "email"
}
```

#### Get User Inquiries
```http
GET /api/inquiries
Authorization: Bearer {token}
```

## 🧪 Testing

### Run Unit Tests

```bash
php artisan test
```

### Run Specific Test Suite

```bash
php artisan test --testsuite=Feature
```

### Run with Coverage

```bash
php artisan test --coverage
```

## 📝 Usage Examples

### Adding a New Property (Admin/Agent)

1. Log in to the admin panel
2. Navigate to "Properties" > "Add New"
3. Fill in property details:
   - Title
   - Description
   - Price
   - Location
   - Property Type
   - Number of bedrooms/bathrooms
   - Area size
4. Upload property images (minimum 1, maximum 10)
5. Set property status (Available, Sold, Rented)
6. Click "Save Property"

### Searching for Properties (Client)

1. Visit the home page
2. Use the search filters:
   - Location
   - Price range
   - Property type
   - Number of bedrooms
   - Area size
3. Click "Search"
4. Browse results
5. Click on a property to view details
6. Submit an inquiry using the contact form

## 🔧 Common Tasks

### Clear Application Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Reset Database

```bash
php artisan migrate:fresh --seed
```

### Create New Migration

```bash
php artisan make:migration create_table_name
```

### Create New Controller

```bash
php artisan make:controller PropertyController --resource
```

### Create New Model

```bash
php artisan make:model Property -m
```

## 🐛 Troubleshooting

### Database Connection Error

- Verify database credentials in `.env`
- Ensure MySQL service is running
- Check database exists

### Permission Errors

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Composer Dependencies Issue

```bash
composer clear-cache
composer install --no-cache
```

### NPM Build Errors

```bash
rm -rf node_modules package-lock.json
npm install
npm run dev
```

## 🤝 Contributing

We welcome contributions to improve this project!

### Steps to Contribute

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Commit your changes: `git commit -m 'Add amazing feature'`
4. Push to the branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

### Coding Standards

- Follow PSR-12 coding standards for PHP
- Use meaningful variable and function names
- Add comments for complex logic
- Write tests for new features
- Update documentation accordingly

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👥 Team

- **Project Lead**: [Your Name]
- **Developers**: [Team Members]
- **Contributors**: See [CONTRIBUTORS.md](CONTRIBUTORS.md)

## 📞 Support

For support and questions:

- **Email**: support@realestate.com
- **Documentation**: [Link to docs]
- **Issues**: [GitHub Issues](https://github.com/Palentoist/WebTechnology_RealEstateProject/issues)

## 🗺️ Roadmap

### Upcoming Features
- [ ] Advanced analytics dashboard
- [ ] Property comparison tool
- [ ] Virtual tour integration
- [ ] Mobile application
- [ ] Multi-language support
- [ ] Payment gateway integration
- [ ] Chat functionality
- [ ] Property recommendations using AI
- [ ] Google Maps integration
- [ ] PDF report generation

## 📊 Performance

- Average page load time: < 2 seconds
- Database query optimization implemented
- Image lazy loading enabled
- CDN integration for static assets

## 🔒 Security

- CSRF protection enabled
- XSS prevention
- SQL injection protection via Eloquent ORM
- Password hashing using bcrypt
- Input validation and sanitization
- Rate limiting on API endpoints
- Secure session management

---

**Last Updated**: December 2024

**Version**: 1.0.0

For more information, visit our [Wiki](https://github.com/Palentoist/WebTechnology_RealEstateProject/wiki)
