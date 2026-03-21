# Arkani — Database Schema

## Tables Overview

| Table | Description |
|-------|-------------|
| `users` | Admin panel users (Laravel default + roles) |
| `adhkar_categories` | Categories for adhkar (e.g. صباح، مساء، بعد الصلاة) |
| `adhkar` | Individual dhikr texts |
| `motivational_messages` | Messages shown when user taps "Adhan & Nafl" button |
| `ruling_topics` | Topics for Islamic rulings (e.g. الطهارة، الصلاة) |
| `islamic_rulings` | Individual rulings with evidence |
| `daily_notifications` | Scheduled daily notifications content |
| `app_devices` | Registered Flutter app devices (for FCM) |
| `notification_logs` | History of sent push notifications |

---

## Detailed Schema

### `users`
```sql
id                  BIGINT UNSIGNED PK AI
name                VARCHAR(255)
email               VARCHAR(255) UNIQUE
password            VARCHAR(255)
role                ENUM('super_admin','editor') DEFAULT 'editor'
remember_token      VARCHAR(100) NULL
email_verified_at   TIMESTAMP NULL
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

---

### `adhkar_categories`
```sql
id          BIGINT UNSIGNED PK AI
name        VARCHAR(100)          -- e.g. "أذكار الصباح"
slug        VARCHAR(100) UNIQUE   -- e.g. "morning"
icon        VARCHAR(50) NULL      -- emoji or icon key e.g. "☀️"
order       TINYINT DEFAULT 0
is_active   BOOLEAN DEFAULT TRUE
created_at  TIMESTAMP
updated_at  TIMESTAMP
deleted_at  TIMESTAMP NULL
```

---

### `adhkar`
```sql
id          BIGINT UNSIGNED PK AI
category_id BIGINT UNSIGNED FK → adhkar_categories.id
text        TEXT                  -- Arabic dhikr text
source      VARCHAR(255) NULL     -- e.g. "رواه البخاري"
count       TINYINT DEFAULT 1     -- recommended repetition count
order       SMALLINT DEFAULT 0
is_active   BOOLEAN DEFAULT TRUE
created_at  TIMESTAMP
updated_at  TIMESTAMP
deleted_at  TIMESTAMP NULL
```

---

### `motivational_messages`
```sql
id           BIGINT UNSIGNED PK AI
text         TEXT                  -- Arabic message shown after tapping button
prayer_time  ENUM('fajr','dhuhr','asr','maghrib','isha','any') DEFAULT 'any'
             -- 'any' = shown for all prayers randomly
is_active    BOOLEAN DEFAULT TRUE
created_at   TIMESTAMP
updated_at   TIMESTAMP
deleted_at   TIMESTAMP NULL
```

---

### `ruling_topics`
```sql
id          BIGINT UNSIGNED PK AI
name        VARCHAR(100)          -- e.g. "الطهارة"
icon        VARCHAR(50) NULL      -- emoji e.g. "💧"
order       TINYINT DEFAULT 0
is_active   BOOLEAN DEFAULT TRUE
created_at  TIMESTAMP
updated_at  TIMESTAMP
deleted_at  TIMESTAMP NULL
```

---

### `islamic_rulings`
```sql
id          BIGINT UNSIGNED PK AI
topic_id    BIGINT UNSIGNED FK → ruling_topics.id
question    VARCHAR(500)          -- "ما حكم الصلاة في السفر؟"
answer      TEXT                  -- Full ruling answer
evidence    TEXT NULL             -- Quran/Hadith reference
is_active   BOOLEAN DEFAULT TRUE
created_at  TIMESTAMP
updated_at  TIMESTAMP
deleted_at  TIMESTAMP NULL
```

---

### `daily_notifications`
```sql
id              BIGINT UNSIGNED PK AI
title           VARCHAR(255)          -- notification title (Arabic)
body            TEXT                  -- notification body
type            ENUM('khulq','nafl','dua','reminder') DEFAULT 'reminder'
                -- خلق / نافلة / دعاء / تذكير
scheduled_date  DATE NULL             -- specific date or null for repeating
send_time       TIME DEFAULT '07:00:00'
is_sent         BOOLEAN DEFAULT FALSE
sent_at         TIMESTAMP NULL
is_active       BOOLEAN DEFAULT TRUE
created_at      TIMESTAMP
updated_at      TIMESTAMP
deleted_at      TIMESTAMP NULL
```

---

### `app_devices`
```sql
id            BIGINT UNSIGNED PK AI
device_id     VARCHAR(255) UNIQUE    -- UUID from Flutter app
fcm_token     TEXT                   -- Firebase FCM token
platform      ENUM('android','ios') DEFAULT 'android'
app_version   VARCHAR(20) NULL
last_seen_at  TIMESTAMP NULL
created_at    TIMESTAMP
updated_at    TIMESTAMP
```

---

### `notification_logs`
```sql
id              BIGINT UNSIGNED PK AI
notification_id BIGINT UNSIGNED FK → daily_notifications.id
devices_count   INT DEFAULT 0        -- how many devices received it
success_count   INT DEFAULT 0
failure_count   INT DEFAULT 0
sent_at         TIMESTAMP
created_at      TIMESTAMP
updated_at      TIMESTAMP
```

---

## Relationships

```
adhkar_categories ──< adhkar
ruling_topics ──< islamic_rulings
daily_notifications ──< notification_logs
```

---

## Indexes

```sql
-- Performance indexes
INDEX idx_adhkar_category    ON adhkar(category_id, is_active)
INDEX idx_messages_prayer    ON motivational_messages(prayer_time, is_active)
INDEX idx_rulings_topic      ON islamic_rulings(topic_id, is_active)
INDEX idx_notifs_date        ON daily_notifications(scheduled_date, is_sent)
INDEX idx_devices_token      ON app_devices(fcm_token(100))
```

---

## Seed Data Summary

After running `php artisan db:seed`:

| Table | Seed Count |
|-------|-----------|
| users | 1 (admin) |
| adhkar_categories | 4 (صباح، مساء، بعد الصلاة، أذكار الأذان) |
| adhkar | 20+ real adhkar texts |
| motivational_messages | 15+ messages (3 per prayer time) |
| ruling_topics | 5 (الطهارة، الصلاة، الصيام، الزكاة، عام) |
| islamic_rulings | 10+ rulings |
| daily_notifications | 7 (one per day of week) |
