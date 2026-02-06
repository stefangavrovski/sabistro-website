# SABistro - Online Food Ordering System

**Course:** Web Programming
**Faculty:** CST, SEE University  
**Semester:** 5th Semester (2024/2025)

## About

This is my semester project where I built a full-stack e-commerce system for a fictional bistro. The main idea was to create something that could actually be used by a real restaurant - customers can browse the menu, add items to their cart, place orders, and pay online. There's also an admin panel for managing everything.

## Features

**Customer Side:**
- Browse products by category
- Search and filter menu items
- Shopping cart with real-time updates
- Secure checkout with Stripe payment integration
- Order history and tracking
- Leave reviews and ratings on products

**Admin Panel:**
- Dashboard with order statistics
- Product management (CRUD operations)
- Category management
- Order management and status updates
- Review moderation

## Tech Stack

- **Backend:** Laravel 11.x
- **Frontend:** Blade templates, Tailwind CSS, Alpine.js
- **Database:** MySQL
- **Payment Processing:** Stripe API
- **Email:** Mailtrap (for testing)
- **Development:** Vite for asset bundling

## Prerequisites

Before you begin, make sure you have the following installed:
- PHP >= 8.2
- Composer
- Node.js >= 18.x and npm
- MySQL >= 8.0
- Git

## Installation

If you want to run this locally (for testing or whatever), here's how:

1. **Clone the repository**
```bash
git clone https://github.com/yourusername/SABistro.git
cd SABistro
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node.js dependencies**
```bash
npm install
```

4. **Set up environment file**
```bash
cp .env.example .env
```

5. **Generate application key**
```bash
php artisan key:generate
```

6. **Configure your `.env` file**

Update the following variables in your `.env` file:

```env
APP_NAME=SABistro
APP_URL=http://localhost:8000

DB_DATABASE=sabistro
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_FROM_ADDRESS="noreply@sabistro.com"
MAIL_FROM_NAME="SABistro"

STRIPE_KEY=your_stripe_publishable_key
STRIPE_SECRET=your_stripe_secret_key
STRIPE_WEBHOOK_SECRET=your_stripe_webhook_secret
```

7. **Create the database**
```bash
mysql -u root -p
CREATE DATABASE sabistro;
EXIT;
```

8. **Run migrations and seeders**
```bash
php artisan migrate --seed
```

9. **Create storage symlink**
```bash
php artisan storage:link
```

10. **Build frontend assets**
```bash
npm run dev
```

11. **Start the development server**

In a new terminal:
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Default Admin Credentials

After running the seeders, you can log in to the admin panel with:
- **Email:** admin@sabistro.local
- **Password:** password

**Important:** Change these credentials immediately in production!

## Stripe Testing

For testing payments, use Stripe's test card numbers:
- **Card Number:** 4242 4242 4242 4242
- **Expiry:** Any future date
- **CVC:** Any 3 digits
- **ZIP:** Any 5 digits

## Project Structure

```
SABistro/
├── app/
│   ├── Http/Controllers/      # Application controllers
│   ├── Models/                # Eloquent models
│   └── ...
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
├── public/                    # Public assets
├── resources/
│   ├── views/                 # Blade templates
│   ├── css/                   # Stylesheets
│   └── js/                    # JavaScript files
├── routes/
│   ├── web.php               # Web routes
│   └── api.php               # API routes
└── ...
```

## Key Features Implementation

### Shopping Cart
The cart uses session storage for guest users and database storage for authenticated users, ensuring cart persistence across sessions.

### Payment Processing
Integrated with Stripe for secure payment processing. All sensitive payment data is handled by Stripe's secure checkout.

### Order Management
Real-time order status updates with email notifications sent to customers at each stage.

### Admin Dashboard
Comprehensive admin panel with statistics, charts, and full CRUD operations for products, categories, and orders.

## Development Notes

- All routes are defined in `routes/web.php`
- Database schema is managed through Laravel migrations
- Frontend uses Tailwind CSS for styling with custom components
- Alpine.js handles interactive UI components
- Form validation uses Laravel's built-in validation

## Testing

Run the test suite:
```bash
php artisan test
```

## Known Issues

- Webhook handling for Stripe requires ngrok or similar for local testing
- Email notifications require proper mail configuration
- Some images may not display if storage link is not created

## License

This project is developed for educational purposes as part of my university coursework.