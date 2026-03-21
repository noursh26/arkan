# أركاني — Arkani | Project Overview

## About the Project

**Arkani** is a Muslim daily companion mobile app targeting youth and teens. It connects them to daily worship in a simple, beautiful, and non-heavy way.

**Core concept:** A single screen with one big button — "Adhan & Nafl" — that the user taps when they hear the call to prayer. The app responds with a short motivational message, the adhan dhikr, and a nafl prayer reminder.

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend / Admin | Laravel 12 |
| Admin UI | Livewire 3 + Tailwind CSS v3 |
| Database | MySQL 8+ |
| API Auth | Laravel Sanctum |
| Push Notifications | Firebase Cloud Messaging (FCM) |
| Mobile App | Flutter (separate repo) |
| Prayer Times | Aladhan.com API (external) |
| Mosques | Google Places API (proxied) |

---

## Two Systems in One Codebase

### 1. Admin Dashboard (`/admin`)
Web interface for content managers to:
- Manage motivational messages (زر أذان ونفل)
- Manage adhkar (الأذكار) with categories
- Manage Islamic rulings (الأحكام الشرعية) with topics
- Schedule and send daily notifications
- View app statistics and registered devices

### 2. REST API (`/api`)
JSON API consumed by the Flutter mobile app:
- Prayer times (proxied from Aladhan)
- Adhkar by category
- Random motivational messages
- Islamic rulings with search
- Today's daily notification
- Device registration for FCM

---

## Directory Structure (Key Files)

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── PrayerController.php
│   │       ├── AdhkarController.php
│   │       ├── MotivationalMessageController.php
│   │       ├── IslamicRulingController.php
│   │       ├── DailyNotificationController.php
│   │       └── DeviceController.php
│   └── Livewire/
│       ├── Admin/
│       │   ├── Dashboard.php
│       │   ├── Messages/
│       │   ├── Adhkar/
│       │   ├── Rulings/
│       │   ├── Notifications/
│       │   └── Devices/
├── Models/
│   ├── AdhkarCategory.php
│   ├── Dhikr.php
│   ├── MotivationalMessage.php
│   ├── RulingTopic.php
│   ├── IslamicRuling.php
│   ├── DailyNotification.php
│   ├── AppDevice.php
│   └── NotificationLog.php
├── Services/
│   ├── PrayerTimesService.php
│   └── FcmService.php
database/
├── migrations/
└── seeders/
resources/
└── views/
    ├── layouts/
    │   └── admin.blade.php
    └── livewire/
        └── admin/
routes/
├── web.php     ← admin routes
└── api.php     ← Flutter API routes
docs/           ← this folder
```

---

## Admin Credentials (Initial Seeded)
- **Email:** admin@arkani.app
- **Password:** Admin@1234 *(change after first login)*

---

## Environment Variables Required

```env
# App
APP_NAME="Arkani Admin"
APP_URL=http://localhost

# DB
DB_CONNECTION=mysql
DB_DATABASE=arkani
DB_USERNAME=root
DB_PASSWORD=

# FCM
FCM_SERVER_KEY=your_firebase_server_key
FCM_PROJECT_ID=your_firebase_project_id

# Google Places (for mosque search proxy)
GOOGLE_PLACES_API_KEY=your_google_places_key

# Prayer Times (no key needed - free API)
ALADHAN_API_URL=https://api.aladhan.com/v1
```

---

## Principles

1. **No empty states** — every section has seed data
2. **Arabic-first** — all content is in Arabic, admin UI in Arabic (RTL)
3. **API must be versioned** — all routes under `/api/v1/`
4. **Soft deletes** on all content models
5. **Activity logging** — all admin CRUD actions logged
