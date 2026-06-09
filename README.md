# PRIMEDocs — Pharma RFQ & CPR Tracker

A **Laravel 12** + **Livewire** internal system for managing pharmaceutical RFQs (Request for Quotations), agencies, CPR document tracking, activity logs, and user management — with multi-theme support (Light / Dark / Prime Link).

---

## Features

| Feature | Description |
|---------|-------------|
| **RFQ Management** | Create, edit, print, and track RFQs with status workflow (`Received → Reviewing → Quoted → Awarded / Lost / Declined`) |
| **RFQ Line Items** | Add/edit/remove line items with brand, description, unit, quantity, unit price. Supports paste-import from Excel. |
| **Agency Management** | Manage agencies with RFQ statistics breakdown (received, reviewing, quoted, awarded, lost) |
| **CPR Tracker** | Scan and track CPR documents with progress monitoring, PDF viewing, and search |
| **User Management** | Admin can create/edit/delete users with role-based access (admin/staff) |
| **Self-Service Profile** | Users can edit their own name, email, password, and upload profile picture |
| **Activity Log** | Tracks user actions (RFQ created/updated/status changed, user management events) |
| **Chat Box** | Real-time messaging between users with file attachments |
| **Settings** | Admin-configurable system settings |
| **Multi-Theme** | Light, Dark, and "Prime Link" (green accent) themes with persistent `localStorage` preference |
| **Responsive** | Mobile-friendly layout with hamburger menu |
| **RFQ Printing** | Print-ready RFQ view for physical documentation |

---

## Requirements

- **PHP 8.2+**
- **Composer**
- **Node.js & npm**
- **SQLite** (default) or MySQL / PostgreSQL
- **GD or Imagick** PHP extension (for image processing)

---

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

Edit `.env` and configure your database. The default uses **SQLite** — no additional setup needed:

```
DB_CONNECTION=sqlite
```

For **MySQL**, update:

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

### 6. Create storage link (for profile photos & CPR files)

```bash
php artisan storage:link
```

### 7. Build frontend assets

```bash
npm run build
```

For development (with hot reload):

```bash
npm run dev
```

### 8. Start the development server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

---

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

---

## Project Structure

```
app/
├── Console/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php         # Login / logout
│   │   ├── UserController.php         # User CRUD + profile editing + password reset
│   │   ├── RfqController.php          # RFQ CRUD + printing + declining
│   │   ├── AgencyController.php       # Agency listing + create/edit
│   │   ├── CprController.php          # CPR tracking, scanning, PDF viewing
│   │   └── SettingsController.php     # Admin settings
│   └── Middleware/
│       └── AdminMiddleware.php        # Restrict routes to admin role
├── Livewire/
│   ├── RfqTracker.php                 # RFQ list with search, filter, pagination
│   ├── RfqForm.php                    # RFQ create/edit form with line items
│   ├── AgencyList.php                 # Agency list component
│   ├── AgencyForm.php                 # Agency form component
│   ├── ActivityLogPage.php            # Activity log viewer
│   └── ChatBox.php                    # Real-time messaging
├── Models/
│   ├── ActivityLog.php                # Activity audit trail
│   ├── Agency.php                     # Agency model
│   ├── CprRecord.php                  # CPR document model
│   ├── Message.php                    # Chat message model
│   ├── Rfq.php                        # RFQ model with relationships
│   ├── RfqItem.php                    # RFQ line item model
│   ├── Setting.php                    # System settings model
│   └── User.php                       # User model with avatar support
├── Services/
│   └── ...
resources/
├── layouts/
│   └── app.blade.php                  # Main layout with navbar, sidebar, theme toggle
├── auth/
│   └── login.blade.php                # Login page
├── profile/
│   └── edit.blade.php                 # Self-service profile editor
├── users/
│   ├── index.blade.php                # Admin user management table
│   ├── edit.blade.php                 # Admin user editor
│   └── create.blade.php               # Admin user creator
├── rfqs/
│   ├── index.blade.php                # RFQ list view
│   ├── create.blade.php               # RFQ create view
│   ├── edit.blade.php                 # RFQ edit view
│   ├── show.blade.php                 # RFQ detail view
│   ├── print.blade.php                # Print-ready RFQ view
│   └── decline.blade.php              # Decline RFQ view
├── agencies/
│   └── ...
├── cpr/
│   └── ...
├── l/                                 # Livewire views
│   ├── rfq-tracker.blade.php
│   ├── rfq-form.blade.php
│   ├── agency-list.blade.php
│   ├── agency-form.blade.php
│   ├── activity-log-page.blade.php
│   └── chat-box.blade.php
database/
├── migrations/
│   ├── create_users_table.php
│   ├── create_agencies_table.php
│   ├── create_rfqs_table.php
│   ├── create_rfq_items_table.php
│   ├── create_cpr_records_table.php
│   ├── create_messages_table.php
│   ├── create_settings_table.php
│   ├── create_activity_logs_table.php
│   └── ...
routes/
└── web.php                            # All web routes
```

---

## Routes

| Method | URI | Middleware | Description |
|--------|-----|-----------|-------------|
| GET | `/login` | guest | Login page |
| POST | `/login` | guest | Login action |
| POST | `/logout` | auth | Logout |
| GET | `/` | auth | Redirect to RFQ list |
| GET | `/rfqs` | auth | RFQ list (Livewire) |
| GET/POST | `/rfqs/create` | auth | Create RFQ |
| GET | `/rfqs/{rfq}` | auth | View RFQ details |
| GET | `/rfqs/{rfq}/edit` | auth | Edit RFQ |
| PUT | `/rfqs/{rfq}` | auth | Update RFQ |
| DELETE | `/rfqs/{rfq}` | auth | Delete RFQ |
| GET | `/rfqs/{rfq}/print` | auth | Print RFQ |
| POST | `/rfqs/{rfq}/decline` | auth | Decline RFQ |
| GET | `/agencies` | auth | Agency list |
| GET/POST | `/agencies/create` | auth | Create agency |
| GET | `/agencies/{agency}/edit` | auth | Edit agency |
| GET | `/cpr` | auth | CPR tracker |
| POST | `/cpr/scan` | auth | Scan CPR document |
| GET | `/cpr/edit/{id}` | auth | Edit CPR record |
| POST | `/cpr/update/{id}` | auth | Update CPR record |
| GET | `/cpr/open-pdf` | auth | View CPR PDF |
| GET | `/cpr/progress` | auth | CPR progress data |
| GET | `/cpr/results` | auth | CPR search results |
| GET | `/cpr/search` | auth | CPR search |
| GET | `/profile/edit` | auth | Edit own profile |
| PUT | `/profile` | auth | Update own profile |
| GET | `/users` | admin | User management |
| GET/POST | `/users/create` | admin | Create user |
| GET/PUT | `/users/{user}/edit` | admin | Edit user |
| DELETE | `/users/{user}` | admin | Delete user |
| POST | `/users/{user}/reset-password` | admin | Reset user password |
| GET | `/settings` | admin | System settings |
| PUT | `/settings` | admin | Update settings |
| GET | `/activity-log` | admin | Activity log |

---

## Database Schema

### Users
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| name | string | |
| email | string | Unique |
| password | string | Hashed |
| role | string | `admin` or `staff` |
| avatar | string | Nullable — profile photo path |
| timestamps | | created_at, updated_at |

### Agencies
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| name | string | Agency name |
| timestamps | | |

### RFQs
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| rfq_number | string | Auto-generated or manual |
| agency_id | bigint | Foreign key → agencies |
| date_received | date | |
| deadline | date | Nullable |
| abc | decimal | Approved Budget for Contract |
| status | string | `Received`, `Reviewing`, `Quoted`, `Awarded`, `Lost`, `Declined` |
| notes | text | Nullable |
| philgeps_ref | string | Nullable — PhilGEPS reference number |
| documents | json | Nullable — NOA, PO, NTP document flags |
| timestamps | | |

### RFQ Items
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| rfq_id | bigint | Foreign key → rfqs |
| brand | string | Nullable |
| item_description | string | |
| unit | string | |
| quantity | integer | |
| unit_price | decimal | Nullable |
| total_price | decimal | Nullable — calculated |
| timestamps | | |

### CPR Records
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| filename | string | |
| normalized_filename | string | |
| file_path | string | |
| timestamp | | |

### Messages
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| user_id | bigint | Sender |
| content | text | Message body |
| file | string | Nullable — attachment path |
| deleted | boolean | Soft delete flag |
| deleted_by | bigint | Nullable — who deleted |
| timestamps | | |

### Settings
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| key | string | Unique |
| value | text | |
| timestamps | | |

### Activity Log
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| event | string | e.g. `rfq.created`, `rfq.status_changed`, `user.created` |
| model_type | string | Eloquent model class |
| model_id | bigint | Model instance ID |
| old_data | json | Nullable — previous state |
| new_data | json | Nullable — new state |
| description | string | Human-readable log message |
| user_id | bigint | Who performed the action |
| timestamps | | |

---

## Theme Toggle

Click the theme icon in the navbar to cycle through:

| Theme | Description |
|-------|-------------|
| **Light** | Default light theme |
| **Dark** | Dark mode with blue accents |
| **Prime Link** | Light theme with green accents |

Theme preference is saved in `localStorage`.

---

## Profile Picture Upload

Users can upload a profile picture from either:

1. **Self-service** — Click your name in the navbar → "Edit Profile" → upload photo
2. **Admin edit** — Admin can set profile pictures for other users via `/users/{id}/edit`

Click the avatar to view it full-size in a modal. Photos are stored in `storage/app/public/avatars/`.

---

## Network Access (LAN)

To make the system accessible on your local network:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Other devices on the same network can then access it at `http://<your-local-ip>:8000`.

---

## Auto-Start on Boot (Windows + XAMPP)

PRIMEDocs is set up to run via **XAMPP Apache + MySQL** and auto-start on boot.

### How it works

1. A batch file (`serve-app.bat`) is placed in the Windows **Startup folder**.
2. On every boot/login, the script launches `C:\xampp\xampp_start.exe`, which starts **Apache** and **MySQL** in the background.
3. Anyone on the same network can access the system at:

```
http://<this-pc-lan-ip>/merged/public/
```

For example, if your PC's local IP is `192.168.254.167`, the URL is:
```
http://192.168.254.167/merged/public/
```

### Script location

```
C:\Users\primelink\AppData\Roaming\Microsoft\Windows\Start Menu\Programs\Startup\serve-app.bat
```

### Script content

```bat
@echo off
REM Start XAMPP Apache and MySQL
start "" "C:\xampp\xampp_start.exe"

REM Give the services a moment to be ready
timeout /t 8 /nobreak >nul

exit
```

### Verify Apache is listening on all interfaces

```cmd
netstat -an | findstr ":80 " | findstr "LISTENING"
```

You should see `0.0.0.0:80` (not just `127.0.0.1:80`) — that means it's reachable from the network.

### Find your local IP

```cmd
ipconfig | findstr "IPv4"
```

### Firewall

If users on the network can't connect, allow port 80 (HTTP) through **Windows Firewall**:
- Windows Security → Firewall & network protection → Advanced settings
- Inbound Rules → New Rule → Port → TCP 80 → Allow

---

## License

This project is for internal use.