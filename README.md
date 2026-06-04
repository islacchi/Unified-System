# PRIMEDocs - Pharma RFQ & CPR Tracker

A Laravel-based internal system for managing pharmaceutical RFQs (Request for Quotations), agencies, CPR document tracking, and user management with multi-theme support (Light / Dark / Prime Link).

## Features

- **RFQ Management** — Create, edit, print, and track RFQs with status workflow (Received → Reviewing → Quoted → Awarded / Lost)
- **Agency Management** — Manage agencies with RFQ statistics (received, reviewing, quoted, awarded, lost)
- **CPR Tracker** — Scan and track CPR documents with progress monitoring and PDF viewing
- **User Management** — Admin can create/delete users with role-based access (admin/staff)
- **Self-Service Profile** — Users can edit their own name, email, password, and upload profile picture
- **Multi-Theme** — Light, Dark, and "Prime Link" themes with persistent localStorage preference
- **Responsive** — Mobile-friendly layout with hamburger menu

## Requirements

- PHP 8.2+
- Composer
- Node.js & npm
- SQLite (default) or MySQL / PostgreSQL
- GD or Imagick PHP extension (for image processing)

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/peterkyle123/Unified-System.git
cd Unified-System
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install frontend dependencies

```bash
npm install
```

### 4. Environment configuration

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and configure your database. The default uses SQLite — no additional setup needed:

```
DB_CONNECTION=sqlite
```

For MySQL, update:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run migrations

```bash
php artisan migrate
```

### 6. Create storage link (for profile photos)

```bash
php artisan storage:link
```

### 7. Build frontend assets

```bash
npm run build
```

### 8. Start the development server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Creating an Admin User

Use Laravel Tinker to create your first admin user:

```bash
php artisan tinker
```

```php
User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => Hash::make('password123'),
    'role' => 'admin',
]);
```

Then log in at `http://localhost:8000/login`.

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php      # Login / logout
│   │   ├── UserController.php      # User CRUD + profile editing
│   │   ├── RfqController.php       # RFQ CRUD + printing
│   │   ├── AgencyController.php    # Agency listing
│   │   └── CprController.php       # CPR tracking
│   └── Middleware/
│       └── AdminMiddleware.php     # Restrict routes to admin role
├── Livewire/
│   ├── RfqTracker.php              # Livewire RFQ list component
│   ├── RfqForm.php                 # Livewire RFQ create/edit form
│   ├── AgencyList.php              # Livewire agency list component
│   └── AgencyForm.php              # Livewire agency form component
├── Models/
│   └── User.php                    # User model with avatarUrl()
├── Services/
│   └── ...
resources/views/
├── layouts/
│   └── app.blade.php               # Main layout with navbar + dropdown
├── auth/
│   └── login.blade.php             # Login page
├── profile/
│   └── edit.blade.php              # Self-service profile editor
├── users/
│   ├── index.blade.php             # Admin user management table
│   ├── edit.blade.php              # Admin user editor
│   └── create.blade.php            # Admin user creator
├── rfqs/
├── agencies/
└── cpr/
```

## Routes

| Method | URI | Middleware | Description |
|--------|-----|-----------|-------------|
| GET | `/login` | guest | Login page |
| POST | `/login` | guest | Login action |
| POST | `/logout` | auth | Logout |
| GET | `/rfqs` | auth | RFQ list |
| GET/POST | `/rfqs/create` | auth | Create RFQ |
| GET/PUT | `/rfqs/{rfq}` | auth | View/Update RFQ |
| GET | `/rfqs/{rfq}/edit` | auth | Edit RFQ |
| GET | `/rfqs/{rfq}/print` | auth | Print RFQ |
| DELETE | `/rfqs/{rfq}` | auth | Delete RFQ |
| GET | `/agencies` | auth | Agency list |
| GET | `/cpr` | auth | CPR tracker |
| GET | `/profile/edit` | auth | Edit own profile |
| PUT | `/profile` | auth | Update own profile |
| GET | `/users` | admin | User management |
| GET/POST | `/users/create` | admin | Create user |
| GET/PUT | `/users/{user}/edit` | admin | Edit user |
| DELETE | `/users/{user}` | admin | Delete user |

## Theme Toggle

Click the theme icon in the navbar to cycle through:
- **Light** — Default light theme
- **Dark** — Dark mode with blue accents
- **Prime Link** — Light theme with green accents

Theme preference is saved in `localStorage`.

## Profile Picture Upload

Users can upload a profile picture from either:
1. **Self-service** — Click your name in the navbar → "Edit Profile" → upload photo
2. **Admin edit** — Admin can set profile pictures for other users via `/users/{id}/edit`

Click the avatar to view it full-size in a modal. Photos are stored in `storage/app/public/avatars/`.

## License

This project is for internal use.