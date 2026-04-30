<div align="center">

# 🩸 Wareed — وريد

### Blood Donation Network

**A real-time, hospital-centered blood donation platform connecting hospitals with compatible donors across Egypt — instantly.**

[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://mysql.com)
[![Reverb](https://img.shields.io/badge/Laravel_Reverb-WebSockets-6366F1?style=flat-square)](https://reverb.laravel.com)
[![PWA](https://img.shields.io/badge/PWA-Ready-5A0FC8?style=flat-square&logo=pwa&logoColor=white)](https://web.dev/progressive-web-apps)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)

[Report Bug](https://github.com/Abdulrahman1Fiqi/Wareed-Platform/issues) · [Request Feature](https://github.com/Abdulrahman1Fiqi/Wareed-Platform/issues)

</div>

---

## 📋 Table of Contents

- [About the Project](#-about-the-project)
- [Key Features](#-key-features)
- [Tech Stack](#-tech-stack)
- [System Architecture](#-system-architecture)
- [Database Schema](#-database-schema)
- [Real-Time Flow](#-real-time-flow)
- [Getting Started](#-getting-started)
- [Environment Setup](#-environment-setup)
- [Running the Project](#-running-the-project)
- [Usage Guide](#-usage-guide)
- [Routes Reference](#-routes-reference)
- [PWA Installation](#-pwa-installation)
- [Project Structure](#-project-structure)
- [Blood Type Compatibility](#-blood-type-compatibility)
- [Roadmap](#-roadmap)
- [Contributing](#-contributing)
- [License](#-license)
- [Author](#-author)

---

## 🧠 About the Project

**Wareed** (وريد — Arabic for *vein*) is a hospital-centered blood donation platform built for the Egyptian market. Instead of donors searching for cases, the system flips the model — hospitals post emergency requests and Wareed's matching engine automatically finds, ranks, and notifies the nearest compatible donors within seconds.

### The Problem

Most blood donation solutions in Egypt suffer from:

- ❌ Poor real-time performance — donors refresh manually to see requests
- ❌ No trust system — unverified donors and hospitals
- ❌ Weak hospital integration — hospitals are not in control
- ❌ Low adoption — complicated UX, no mobile-first design

### The Solution

Wareed solves this with three layers:

| Layer | Technology | Purpose |
|-------|-----------|---------|
| **Real-time engine** | Laravel Reverb + Echo | Instant push without page refresh |
| **PWA** | Web App Manifest + Service Worker | Installable on mobile, no app store |
| **Trust system** | Badge ladder + hospital verification | Builds credibility and priority matching |

---

## ✨ Key Features

### For Donors
- 📱 **Instant push notifications** — receive blood requests the moment they are created
- 🏅 **Trust badge system** — earn badges as you donate (New → Active → Trusted → Hero)
- 🔄 **Availability toggle** — easily mark yourself unavailable
- 📊 **Donation history** — full log of every request you responded to
- 🔒 **Automatic cooldown** — system enforces 56-day recovery period after each donation
- 📴 **Lock screen notifications** — Web Push API delivers alerts even when the app is closed

### For Hospitals
- 🚨 **Emergency request creation** — post a request in under 60 seconds
- 📍 **City-based matching** — donors in your city are notified instantly
- 📡 **Live donor responses** — acceptances appear on screen without refresh
- ✅ **Donation confirmation** — confirm when a donor actually arrived and donated
- 📈 **Request lifecycle management** — active → fulfilled / cancelled

### For Admins
- 🛡️ **Hospital verification** — approve or reject hospital registrations
- 👥 **User management** — view, monitor, and suspend donors
- 📊 **Platform statistics** — live overview of donors, hospitals, and requests

### Platform-Wide
- ⚡ **Sub-3-second notification delivery** via WebSocket
- 🌍 **Egypt city + district selector** — 27 governorates with dynamic district lists
- 🔐 **Three-guard authentication** — separate login systems for donors, hospitals, and admins
- 🎨 **Tailwind CSS UI** — clean, mobile-first design with consistent component system

---

## 🛠️ Tech Stack

### Backend

| Technology | Version | Role |
|-----------|---------|------|
| **Laravel** | 11.x | Application framework |
| **PHP** | 8.2+ | Server language |
| **MySQL** | 8.0 | Primary database |
| **Laravel Reverb** | Latest | Self-hosted WebSocket server |
| **Laravel Echo** | Latest | JavaScript WebSocket client |
| **Laravel Queues** | — | Async job processing |
| **Laravel Notifications** | — | Database notification storage |
| **minishlink/web-push** | Latest | Web Push API (VAPID) |

### Frontend

| Technology | Version | Role |
|-----------|---------|------|
| **Blade** | — | Server-side templating |
| **Alpine.js** | 3.x | Lightweight JS reactivity |
| **Tailwind CSS** | 3.x | Utility-first styling |
| **Vite** | 5.x | Asset bundler |

---

## 🏗️ System Architecture

```
┌──────────────────────────────────────────────────────────────┐
│                          CLIENTS                             │
│  ┌───────────────┐  ┌───────────────┐  ┌─────────────────┐  │
│  │  Donor PWA    │  │ Hospital PWA  │  │  Admin Panel    │  │
│  │  Echo + SW    │  │  Echo + Live  │  │  HTTP only      │  │
│  └──────┬────────┘  └──────┬────────┘  └───────┬─────────┘  │
└─────────┼─────────────────┼────────────────────┼────────────┘
          │  HTTP            │  HTTP              │  HTTP
          ▼                  ▼                    ▼
┌──────────────────────────────────────────────────────────────┐
│                        APP LAYER                             │
│  ┌────────────────────┐   ┌──────────────────┐              │
│  │   Laravel Core     │──▶│ Matching Engine  │              │
│  │  Routes · Auth     │   │ Blood type compat│              │
│  │  Controllers       │   │ City + cooldown  │              │
│  └──────────┬─────────┘   └────────┬─────────┘              │
│             │ dispatch              │ broadcast              │
│             ▼                      ▼                         │
│  ┌────────────────────┐   ┌──────────────────┐              │
│  │   Queue Worker     │──▶│ Laravel Reverb   │              │
│  │  Async processing  │   │ WebSocket server │              │
│  └──────────┬─────────┘   │ donor.{id}       │              │
│             │ INSERT       │ hospital.{id}    │              │
│             ▼              └────────┬─────────┘              │
│           MySQL                     │ WS push                │
└─────────────────────────────────────┼──────────────────────--┘
                                      ▼
                          Donor & Hospital browsers
                          Live UI updates instantly
```

---

## 🗄️ Database Schema

```
users                          hospitals
─────────────────────          ──────────────────────
id                             id
name                           name
email               ◀──────── approved_by (FK → users.id)
password                       email
role (donor|admin)             password
blood_type                     phone
phone                          city
city                           district
district                       license_number
status                         status (pending|approved|rejected|suspended)
last_donation_date             approved_at
donation_count                 created_at
email_verified_at              │
created_at                     │ creates
        │                      ▼
        │              blood_requests
        │              ──────────────────────
        │              id
        │              hospital_id (FK)
        │              blood_type
        │              units_needed
        │              urgency (critical|urgent|standard)
        │              status (active|partially_fulfilled|fulfilled|expired|cancelled)
        │              contact_person
        │              contact_phone
        │              expires_at
        │              created_at
        │                      │
        │                      │ has
        │                      ▼
        │              donor_responses
        └─────────────▶──────────────────────
                       id
                       blood_request_id (FK)
                       donor_id (FK)
                       status (notified|accepted|declined|confirmed)
                       responded_at
                       confirmed_at
                       created_at

notifications
──────────────────────
id
notifiable_type
notifiable_id
type
data (JSON)
read_at
created_at
```

---

## ⚡ Real-Time Flow

```
Hospital submits blood request
           │
           ├──▶ HTTP 201 (instant response to hospital)
           │
           └──▶ MatchDonorsJob → Queue
                        │
                        ▼
              SELECT donors WHERE
                blood_type compatible
                AND city matches
                AND status = available
                AND cooldown expired
                        │
                        ▼
              For each matched donor:
                ├── INSERT donor_responses
                ├── INSERT notifications
                ├── broadcast BloodRequestCreated
                │         → private-donor.{id}
                └── Send Web Push (lock screen)
                        │
                        ▼
              Donor sees notification instantly
                        │
                        ▼
              Donor taps Accept
                        │
                        ├── UPDATE donor_responses (accepted)
                        └── broadcast DonorResponded
                                  → private-hospital.{id}
                                            │
                                            ▼
                            Hospital dashboard updates live
                            Donor name + phone appear instantly
```

---

## 🚀 Getting Started

### Prerequisites

```bash
php --version     # 8.2 or higher
composer --version
node --version    # 18 or higher
npm --version
mysql --version   # 8.0 or higher
```

### Installation

**1. Clone the repository**

```bash
git clone https://github.com/Abdulrahman1Fiqi/Wareed-Platform.git
cd Wareed-Platform
```

**2. Install PHP dependencies**

```bash
composer install
```

**3. Install Node dependencies**

```bash
npm install
```

**4. Copy the environment file**

```bash
cp .env.example .env
```

**5. Generate the application key**

```bash
php artisan key:generate
```

---

## ⚙️ Environment Setup

Configure `.env` with your values:

```env
# Application
APP_NAME=Wareed
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wareed
DB_USERNAME=root
DB_PASSWORD=your_password

# Queue
QUEUE_CONNECTION=database

# Broadcasting
BROADCAST_CONNECTION=reverb

# Reverb WebSocket
REVERB_APP_ID=wareed
REVERB_APP_KEY=wareed-key
REVERB_APP_SECRET=wareed-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"

# Mail (log driver for development)
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@wareed.com"
MAIL_FROM_NAME="Wareed"

# Web Push VAPID keys
# Generate with: php artisan tinker → VAPID::createVapidKeys()
VAPID_PUBLIC_KEY=your_public_key
VAPID_PRIVATE_KEY=your_private_key
VAPID_SUBJECT=mailto:admin@wareed.com
```

**Create the database:**

```bash
mysql -u root -p -e "CREATE DATABASE wareed CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

**Run migrations and seed:**

```bash
php artisan migrate
php artisan db:seed --class=AdminSeeder
```

---

## ▶️ Running the Project

You need **four terminals** running simultaneously:

```bash
# Terminal 1 — Laravel server
php artisan serve

# Terminal 2 — Queue worker
php artisan queue:work

# Terminal 3 — Reverb WebSocket server
php artisan reverb:start

# Terminal 4 — Vite asset watcher
npm run dev
```

Visit `http://localhost:8000`

### Default Admin Credentials

```
Email:    admin@wareed.com
Password: password123
```

> ⚠️ Change these immediately in production.

**Test the expire command manually:**

```bash
php artisan wareed:expire-requests
```

---

## 📖 Usage Guide

### Donor Flow

```
1. Register at /register/donor
   └── Select blood type, city, district from Egypt dropdown

2. Verify your email
   └── In development: check storage/logs/laravel.log for the link

3. Log in → land on donor dashboard

4. When a hospital creates a matching request:
   ├── Push notification arrives instantly (WebSocket)
   ├── Lock screen alert if PWA is installed and app is closed
   └── Card appears on dashboard without refresh

5. Click Respond → Accept or Decline

6. If accepted:
   └── Hospital contact details are revealed

7. Go donate → hospital confirms donation

8. Your donation count increases
   └── System places you on 56-day automatic cooldown
   └── After 56 days → automatically back to available
```

### Hospital Flow

```
1. Register at /register/hospital
   └── Provide license number for verification

2. Wait for admin approval
   └── Email notification sent on decision

3. Log in → hospital dashboard

4. Click New Request → fill details:
   ├── Blood type needed
   ├── Units needed
   ├── Urgency (critical 6h / urgent 24h / standard 72h)
   └── Contact person and phone

5. Submit → matching engine fires in background
   └── Hospital gets instant redirect (not waiting for matching)

6. Open request detail page
   └── Donor responses appear live as they come in

7. Confirm donations when donors arrive

8. Mark request as Fulfilled
```

### Admin Flow

```
1. Log in at /admin/login

2. Review pending hospitals
   └── /admin/hospitals?status=pending

3. Click hospital → review license number and details

4. Approve or Reject
   └── Hospital receives email notification immediately

5. Monitor platform from /admin/dashboard
   └── Live stats: donors, hospitals, requests
```

---

## 🗺️ Routes Reference

### Public

| Method | URI | Description |
|--------|-----|-------------|
| `GET` | `/` | Landing page |
| `GET/POST` | `/register/donor` | Donor registration |
| `GET/POST` | `/register/hospital` | Hospital registration |
| `GET/POST` | `/login` | Donor / Admin login |
| `GET/POST` | `/hospital/login` | Hospital login |
| `GET` | `/email/verify/{id}/{hash}` | Email verification |
| `POST` | `/broadcasting/auth` | Reverb channel auth |

### Donor `[auth · verified]`

| Method | URI | Description |
|--------|-----|-------------|
| `GET` | `/donor/dashboard` | Live dashboard |
| `GET/PUT` | `/donor/profile` | Profile management |
| `POST` | `/donor/availability` | Toggle availability |
| `GET` | `/donor/notifications` | Live notifications |
| `POST` | `/donor/notifications/{id}/read` | Mark as read |
| `GET` | `/donor/requests/{id}` | Request detail |
| `POST` | `/donor/requests/{id}/respond` | Accept or decline |

### Hospital `[auth · approved]`

| Method | URI | Description |
|--------|-----|-------------|
| `GET` | `/hospital/dashboard` | Dashboard |
| `GET/POST` | `/hospital/requests` | List / create requests |
| `GET` | `/hospital/requests/{id}` | Detail + live responses |
| `PATCH` | `/hospital/requests/{id}/status` | Fulfill or cancel |
| `POST` | `/hospital/requests/{req}/responses/{res}/confirm` | Confirm donation |

### Admin

| Method | URI | Description |
|--------|-----|-------------|
| `GET` | `/admin/dashboard` | Platform stats |
| `GET` | `/admin/hospitals` | All hospitals (`?status=`) |
| `GET` | `/admin/hospitals/{id}` | Hospital detail |
| `POST` | `/admin/hospitals/{id}/approve` | Approve |
| `POST` | `/admin/hospitals/{id}/reject` | Reject |
| `GET` | `/admin/users` | All donors |
| `GET` | `/admin/users/{id}` | Donor detail |
| `POST` | `/admin/users/{id}/suspend` | Suspend |
| `GET` | `/admin/requests` | All requests |
| `GET` | `/admin/requests/{id}` | Request detail |

### WebSocket Channels

| Channel | Event | Description |
|---------|-------|-------------|
| `private-donor.{id}` | `.blood-request.created` | New matching request |
| `private-hospital.{id}` | `.donor.responded` | Donor accepted or declined |

---

## 📱 PWA Installation

### Android (Chrome)
1. Open the site in Chrome
2. Tap the menu (⋮) → **Add to Home Screen**
3. Tap **Install**

### iOS (Safari)
1. Open the site in Safari
2. Tap the Share button (□↑)
3. Tap **Add to Home Screen** → **Add**

### Features when installed
- ✅ Fullscreen experience (no browser UI)
- ✅ Works from home screen like a native app
- ✅ Lock screen push notifications via Web Push API
- ✅ Offline fallback page

---

## 📁 Project Structure

```
wareed/
├── app/
│   ├── Console/Commands/
│   │   └── ExpireBloodRequests.php     # php artisan wareed:expire-requests
│   ├── Events/
│   │   ├── BloodRequestCreated.php     # Broadcasts to donor.{id}
│   │   └── DonorResponded.php          # Broadcasts to hospital.{id}
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                  # Dashboard, Hospital, Donor, Request
│   │   │   ├── Auth/                   # Donor, Hospital, Admin auth
│   │   │   ├── Donor/                  # Dashboard, Profile, Notifications, Response
│   │   │   └── Hospital/              # Dashboard, BloodRequest
│   │   └── Middleware/
│   │       ├── EnsureAdmin.php
│   │       ├── EnsureDonor.php
│   │       └── EnsureHospital.php
│   ├── Jobs/
│   │   ├── MatchDonorsJob.php          # Core matching engine (queued)
│   │   └── SetDonorCooldownJob.php     # Lifts cooldown after 56 days (delayed)
│   ├── Mail/
│   │   ├── HospitalApproved.php
│   │   └── HospitalRejected.php
│   ├── Models/
│   │   ├── BloodRequest.php            # compatibleBloodTypes() method
│   │   ├── DonorResponse.php
│   │   ├── Hospital.php
│   │   ├── PushSubscription.php
│   │   └── User.php                    # badge() + badgeClass() methods
│   └── Notifications/
│       └── BloodRequestNotification.php
├── database/
│   ├── migrations/
│   └── seeders/AdminSeeder.php
├── public/
│   ├── icons/                          # icon-192.png, icon-512.png
│   ├── js/egypt-cities.js              # 27 governorates + districts
│   ├── manifest.json                   # PWA manifest
│   ├── offline.html                    # Offline fallback
│   └── sw.js                           # Service worker + push handler
├── resources/
│   ├── css/app.css                     # Tailwind + custom components
│   ├── js/
│   │   ├── app.js                      # Alpine + SW + push subscription
│   │   └── echo.js                     # Reverb connection config
│   └── views/
│       ├── admin/                      # dashboard, hospitals/, donors/, requests/
│       ├── auth/                       # login, register, verify views
│       ├── components/
│       │   └── city-district-select.blade.php
│       ├── donor/                      # dashboard, profile, notifications, requests/
│       ├── hospital/                   # dashboard, requests/
│       ├── layouts/app.blade.php
│       └── welcome.blade.php           # Hero landing page
└── routes/
    ├── channels.php                    # WebSocket channel auth rules
    ├── console.php                     # Scheduled command registration
    └── web.php                         # All application routes
```

---

## 🩸 Blood Type Compatibility

| Donor \ Recipient | O− | O+ | A− | A+ | B− | B+ | AB− | AB+ |
|:-----------------:|:--:|:--:|:--:|:--:|:--:|:--:|:---:|:---:|
| **O−** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| **O+** | — | ✅ | — | ✅ | — | ✅ | — | ✅ |
| **A−** | — | — | ✅ | ✅ | — | — | ✅ | ✅ |
| **A+** | — | — | — | ✅ | — | — | — | ✅ |
| **B−** | — | — | — | — | ✅ | ✅ | ✅ | ✅ |
| **B+** | — | — | — | — | — | ✅ | — | ✅ |
| **AB−** | — | — | — | — | — | — | ✅ | ✅ |
| **AB+** | — | — | — | — | — | — | — | ✅ |

> **O−** is the universal donor — can donate to all blood types.
> **AB+** is the universal receiver — can receive from all blood types.

---

## 🗺️ Roadmap

- [x] Three-guard authentication (donor, hospital, admin)
- [x] Blood type compatibility matching engine
- [x] Real-time WebSocket notifications (Reverb + Echo)
- [x] PWA with service worker and offline support
- [x] Lock screen push notifications (Web Push API / VAPID)
- [x] Trust badge system (New → Active → Trusted → Hero)
- [x] Egypt city and district selector (27 governorates)
- [x] Admin hospital approval system
- [x] 56-day automatic donor cooldown
- [x] Live donor responses on hospital dashboard
- [x] Live notifications page
- [ ] SMS notifications via Vonage / Twilio
- [ ] GPS-based proximity matching
- [ ] Platelet and plasma donation support
- [ ] Arabic RTL multi-language support
- [ ] Native iOS / Android apps
- [ ] Donor leaderboard by city
- [ ] Hospital EHR system integration
- [ ] Analytics dashboard with charts

---

## 🤝 Contributing

Contributions are welcome.

```bash
# 1. Fork the repository
# 2. Create your branch
git checkout -b feat/your-feature

# 3. Commit your changes
git commit -m "feat: describe your change"

# 4. Push and open a Pull Request
git push origin feat/your-feature
```

### Commit Convention

| Prefix | Usage |
|--------|-------|
| `feat` | New feature |
| `fix` | Bug fix |
| `chore` | Setup, config, dependencies |
| `refactor` | Restructure without behavior change |
| `style` | UI / CSS changes |
| `docs` | Documentation only |

## 👤 Author

**Abdulrahman Al-Fiqi**

[![GitHub](https://img.shields.io/badge/GitHub-Abdulrahman1Fiqi-181717?style=flat-square&logo=github)](https://github.com/Abdulrahman1Fiqi)

---

<div align="center">

Built with ❤️ in Egypt

**Wareed — وريد — Every second matters.**

</div>