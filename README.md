# BloodLink — Blood Donation Coordination System

A role-based web application for coordinating blood donations between donors and hospitals, built with PHP 8+, MySQL/MariaDB, and Bootstrap 5.

## What it does

Hospitals post blood requests. The system automatically finds and ranks compatible donors using a composite scoring algorithm (distance, donation recency, profile freshness, urgency). Donors can browse requests, book time slots, and track their donations. Admins manage the platform — verify hospitals, handle user reports, import location data.

## Tech stack

- **Backend:** PHP 8+ (no framework, vanilla)
- **Database:** MySQL 8 / MariaDB via PDO
- **Frontend:** Bootstrap 5, Font Awesome 6, Chart.js 4
- **Font:** Plus Jakarta Sans (Google Fonts)
- **Server:** XAMPP (Apache + MySQL) on localhost

## Setup

1. Start Apache and MySQL in XAMPP
2. Open phpMyAdmin and run `sql/schema.sql` — this creates the database and all 16 tables
3. Visit `http://localhost/bloodbank/seed_admin.php` to create the first admin account
4. Delete `seed_admin.php` after use
5. Log in at `http://localhost/bloodbank/modules/auth/login.php`

Default admin credentials (set in seed_admin.php before running):
- Email: admin@bloodlink.com
- Password: Admin123
- PIN: 1234
- Security Q: What is the system name? → bloodlink

## Importing location data

1. Log in as admin
2. Go to Dashboard → Import Locations
3. Upload `sample_locations.csv` (67 Nepal locations included)
4. Or create your own CSV with columns: country, city, area, latitude, longitude

## Project structure

```
bloodbank/
├── api/                    # AJAX endpoints for location picker
├── config/                 # Database credentials and app settings
├── includes/               # Shared components (header, footer, functions)
├── modules/
│   ├── admin/              # Admin dashboard, hospitals, users, locations, reports
│   ├── auth/               # Login, register, logout, forgot password
│   ├── booking/            # Donor booking, hospital booking management
│   ├── donor/              # Donor dashboard, profile, history
│   ├── hospital/           # Hospital dashboard, requests, matched donors, campaigns
│   ├── matching/           # Donor request browser (find open requests)
│   └── notifications/      # Notification centre
├── public/
│   ├── css/style.css       # Custom theme (562 lines on top of Bootstrap)
│   ├── js/app.js           # Client-side helpers
│   └── uploads/            # Hospital verification documents
├── sql/schema.sql          # Database schema (16 tables)
├── sample_locations.csv    # 67 Nepal locations ready to import
├── contact.php             # Public contact form
├── index.php               # Landing page
├── privacy.php             # Privacy policy
├── terms.php               # Terms of service
└── seed_admin.php          # First-run admin setup (delete after use)
```

## Key features

**Matching algorithm** — composite scoring (0-100) using four weighted factors:
- Geographic distance via Haversine formula (40 pts)
- Donation recency (25 pts)
- Urgency boost (20 pts)
- Profile freshness (15 pts)

**Progressive radius expansion** — if not enough donors are found nearby, the search automatically widens from primary radius to expanded radius to same city to country-wide.

**90-day eligibility enforcement** — donors are automatically marked ineligible after completing a donation and re-enabled after 90 days.

**Hospital verification pipeline** — hospitals must be approved by admin before they can post blood requests.

**Booking workflow** — five states: pending → confirmed → completed / cancelled / rejected. Database-level concurrency protection prevents double-booking.

**Per-admin PIN authentication** — each admin has their own PIN stored in a separate credentials table. No shared security keys.

**Security measures:**
- CSRF tokens on every form (single-use, timing-safe comparison)
- POST-only logout (prevents CSRF-based forced logout)
- Brute force protection (5 attempts, 15 min lockout)
- Password hashing with bcrypt (cost 12)
- XSS prevention via htmlspecialchars on all output
- Session timeout after 30 minutes of inactivity
- Role-based access control on every page

## Database

16 tables: users, admin_credentials, donor_profiles, hospital_profiles, blood_requests, time_slots, bookings, donation_history, campaigns, notifications, system_logs, contact_messages, reports, login_attempts, countries, cities, areas.

Blood type compatibility map supports all 8 types (A+, A-, B+, B-, AB+, AB-, O+, O-) with correct donor-recipient rules.

## Constraints

- Localhost only (no live deployment)
- Simulated data (no real hospital or donor records)
- Security questions instead of real TOTP (localhost limitation)
- In-system notifications only (no email/SMS)
- Small-scale evaluation with proxy users

## Built for

BSc Computer Science thesis — Development Project module.
