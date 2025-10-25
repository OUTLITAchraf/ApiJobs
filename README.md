# Job API

A Laravel-based REST API for a job application that allows employers to post job offers, job seekers to apply, and administrators to manage users and applications.

## Features

- **User Authentication**: Registration, login, and logout using Laravel Sanctum
- **Role-Based Access Control**: Support for employer, job seeker, and admin roles using Spatie Laravel Permission
- **Job Offers Management**: Employers can create, update, and delete job offers
- **Job Applications**: Job seekers can apply to offers and track their applications
- **Admin Panel**: Administrators can manage users, view applications, and update application statuses
- **API Documentation**: Integrated Swagger documentation for easy API exploration
- **Search Functionality**: Search job offers by various criteria

## Tech Stack

- **Framework**: Laravel 10
- **Authentication**: Laravel Sanctum
- **Permissions**: Spatie Laravel Permission
- **API Documentation**: L5 Swagger
- **Database**: MySQL/PostgreSQL (configurable)
- **Testing**: PHPUnit

## Installation

1. **Clone the repository**:
   ```bash
   git clone <repository-url>
   cd <project-directory>
   ```

2. **Install dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**:
   - Copy `.env.example` to `.env`
   - Configure your database settings in `.env`
   - Generate application key:
     ```bash
     php artisan key:generate
     ```

4. **Database setup**:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Generate API documentation**:
   ```bash
   php artisan l5-swagger:generate
   ```

6. **Start the development server**:
   ```bash
   php artisan serve
   ```

## Usage

### Authentication

- **Register**: `POST /api/register`
- **Login**: `POST /api/login`
- **Logout**: `POST /api/logout` (requires authentication)

### Job Offers

- **List offers**: `GET /api/offers` (authenticated)
- **Search offers**: `GET /api/offers/search` (authenticated)
- **View offer**: `GET /api/offer/{id}` (authenticated)
- **Create offer**: `POST /api/create-offer` (employer/admin only)
- **Update offer**: `PUT /api/update-offer/{id}` (employer/admin only)
- **Delete offer**: `DELETE /api/delete-offer/{id}` (employer/admin only)

### Applications

- **Apply to offer**: `POST /api/offer/{id}/apply` (authenticated)
- **View my applications**: `GET /api/my-applications` (authenticated)
- **Update application**: `PUT /api/update-application/{id}` (employer/admin only)

### Admin Functions

- **List users**: `GET /api/admin/users` (admin only)
- **View user**: `GET /api/admin/user/{id}` (admin only)
- **Update user**: `PUT /api/admin/update-user/{id}` (admin only)
- **Delete user**: `DELETE /api/admin/delete-user/{id}` (admin only)

## API Documentation

Access the Swagger documentation at `/api/documentation` after running the application.

## Testing

Run the test suite:
```bash
php artisan test
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests
5. Submit a pull request

## License

This project is licensed under the MIT License.