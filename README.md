# The Touchstone - Campus Publication CMS

<div align="center">
  <img src="public/images/Touchstone_Logo.png" alt="The Touchstone Logo">
</div>

## Project Overview

**The Touchstone** is a modern content management system (CMS) designed specifically for the campus publication of Cagayan State University – Sanchez Mira Campus. This platform empowers administrators, editors, and writers to create, edit, publish, and manage campus news and articles.

## Features

- **Role-Based Access Control**: Separate permissions for admins, editors, writers, and readers
- **Article Management**: Full CRUD functionality for articles with drafts and publishing workflow
- **Categorization**: Organize content by categories for better navigation
- **Commenting System**: Engage readers with a threaded comment system
- **Responsive Design**: Clean, modern UI using Tailwind CSS that works on all devices
- **Admin Dashboard**: Comprehensive statistics and management tools
- **Author Attribution**: Proper crediting for all content creators

## Technology Stack

- **Backend**: Laravel 10
- **Frontend**: Blade Templates + Tailwind CSS
- **Database**: MySQL
- **Authentication**: Laravel Breeze / Fortify

## Installation and Setup

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js and NPM
- MySQL or compatible database

### Installation Steps

1. Clone the repository
   ```
   git clone https://github.com/yourusername/TheTouchstone.git
   cd TheTouchstone
   ```

2. Install PHP dependencies
   ```
   composer install
   ```

3. Install JavaScript dependencies and compile assets
   ```
   npm install
   npm run dev
   ```

4. Create and configure your environment file
   ```
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure your database connection in the `.env` file

6. Run migrations and seed the database
   ```
   php artisan migrate --seed
   ```

7. Start the development server
   ```
   php artisan serve
   ```

## Project Structure

- `/app` - Core application code
- `/database` - Migrations and seeders
- `/resources` - Frontend assets, Blade templates
- `/routes` - Application routes
- `/public` - Publicly accessible files

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is proprietary software developed for Cagayan State University – Sanchez Mira Campus.

## Acknowledgments

- "We Reveal What Is Real" — The Touchstone
- Developed for Cagayan State University – Sanchez Mira Campus
