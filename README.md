# Laravel API Starter Kit

This Laravel API Starter Kit is a robust foundation for building Laravel-based APIs, enabling fast development and adhering to best practices. It comes with a range of features including real-time monitoring with Laravel Telescope, organized architecture using the Controller-Service-Repository pattern, and comprehensive testing using Pest. The kit also generates API documentation via Scribe, leveraging tested responses as examples.

## Features

- **Real-Time Monitoring**: Integrated with Laravel Telescope for tracking requests, exceptions, and more.
- **Organized Architecture**: Follows Controller-Service-Repository pattern for clear separation of concerns.
- **Automated Testing**: Uses Pest for unit and feature testing; supports saving tested responses as JSON files.
- **API Documentation**: Generates comprehensive API documentation with Scribe using actual tested responses as examples.
- **Extensible**: Easily add features or modify existing ones, with robust scaffolding to build on.

## Getting Started

### Prerequisites

- **PHP**: >= 8.2
- **Composer**: Installed on your system
- **Database**: MySQL recommended (default configuration in `.env`)
  
### Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/yourusername/laravel-api-starter-kit.git
   cd laravel-api-starter-kit
   ```

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Set up environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure Database**:
   - Update `.env` with your database credentials.
   - Run migrations:
     ```bash
     php artisan migrate
     ```

5. **Run the application**:
   ```bash
   php artisan serve
   ```

6. **Access Telescope**:
   Navigate to `/telescope` to view real-time monitoring.

### Testing

To run tests with Pest, use:
```bash
php artisan test
```

### Documentation

Generate documentation using:
```bash
php artisan scribe:generate
```
Access the generated documentation at `/docs`.

## Contributing

Contributions are welcome! Please see the [CONTRIBUTING.md](CONTRIBUTING.md) file for details.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
