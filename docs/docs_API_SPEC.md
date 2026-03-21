# Arkani — REST API Specification

## Base URL
```
https://yourdomain.com/api/v1
```

## Authentication
- **Public routes** — no auth required (Flutter app reads content without login)
- **Device registration** — uses device UUID as identity
- **Admin routes** — Laravel Sanctum token (web session, not API)

All API responses follow this envelope:
```json
{
  "success": true,
  "data": { ... },
  "message": null
}
```

Error response:
```json
{
  "success": false,
  "data": null,
  "message": "Human-readable error in Arabic"
}
```

---

## Endpoints

---

### 1. Register Device
Register or update FCM token for push notifications.

```
POST /api/v1/devices/register
```

**Request Body:**
```json
{
  "device_id": "uuid-string",
  "fcm_token": "firebase-fcm-token",
  "platform": "android",
  "app_version": "1.0.0"
}
```

**Response:**
```json
{
  "success": true,
  "data": { "registered": true },
  "message": null
}
```

---

### 2. Prayer Times
Proxy to Aladhan API. Returns prayer times for a given location and date.

```
GET /api/v1/prayers/times?lat={lat}&lng={lng}&date={YYYY-MM-DD}&method={1-23}
```

**Query Params:**
| Param | Type | Required | Default |
|-------|------|----------|---------|
| lat | float | yes | — |
| lng | float | yes | — |
| date | string | no | today |
| method | int | no | 4 (Umm al-Qura) |

**Response:**
```json
{
  "success": true,
  "data": {
    "date": "2025-01-01",
    "timings": {
      "Fajr": "05:14",
      "Dhuhr": "12:02",
      "Asr": "15:23",
      "Maghrib": "17:59",
      "Isha": "19:29"
    },
    "location": {
      "latitude": 24.7136,
      "longitude": 46.6753,
      "timezone": "Asia/Riyadh"
    }
  },
  "message": null
}
```

---

### 3. Get All Adhkar Categories
```
GET /api/v1/adhkar/categories
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "أذكار الصباح",
      "slug": "morning",
      "icon": "☀️",
      "adhkar_count": 7
    }
  ],
  "message": null
}
```

---

### 4. Get Adhkar by Category
```
GET /api/v1/adhkar/{slug}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "category": {
      "id": 1,
      "name": "أذكار الصباح",
      "icon": "☀️"
    },
    "adhkar": [
      {
        "id": 1,
        "text": "أَصْبَحْنَا وَأَصْبَحَ الْمُلْكُ لِلَّهِ...",
        "source": "رواه أبو داود",
        "count": 1,
        "order": 1
      }
    ]
  },
  "message": null
}
```

---

### 5. Get Motivational Message
Returns a random motivational message for the given prayer time.

```
GET /api/v1/messages/random?prayer={fajr|dhuhr|asr|maghrib|isha}
```

**Query Params:**
| Param | Type | Required | Default |
|-------|------|----------|---------|
| prayer | string | no | any |

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 3,
    "text": "أذّنت؟ أحسن تصلي ركعتين قبل الفريضة إن استطعت 🤍",
    "prayer_time": "fajr"
  },
  "message": null
}
```

---

### 6. Get Islamic Ruling Topics
```
GET /api/v1/rulings/topics
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "الطهارة",
      "icon": "💧",
      "rulings_count": 5
    }
  ],
  "message": null
}
```

---

### 7. Get Rulings by Topic (with search)
```
GET /api/v1/rulings?topic_id={id}&search={query}&page={n}
```

**Query Params:**
| Param | Type | Required |
|-------|------|----------|
| topic_id | int | no |
| search | string | no |
| page | int | no (default 1) |

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 10,
    "total": 28,
    "items": [
      {
        "id": 1,
        "topic": { "id": 1, "name": "الطهارة" },
        "question": "ما حكم الصلاة بدون وضوء؟",
        "answer": "لا تصح الصلاة بدون وضوء...",
        "evidence": "قال رسول الله ﷺ: لا تُقبل صلاة بغير طهور"
      }
    ]
  },
  "message": null
}
```

---

### 8. Today's Daily Notification
```
GET /api/v1/notifications/today
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 5,
    "title": "خلق اليوم 🌿",
    "body": "الصدق: قال النبي ﷺ «عليكم بالصدق فإن الصدق يهدي إلى البر»",
    "type": "khulq"
  },
  "message": null
}
```

If no notification scheduled for today:
```json
{
  "success": true,
  "data": null,
  "message": null
}
```

---

### 9. Nearby Mosques
Proxy to Google Places API.

```
GET /api/v1/mosques/nearby?lat={lat}&lng={lng}&radius={meters}
```

**Query Params:**
| Param | Type | Required | Default |
|-------|------|----------|---------|
| lat | float | yes | — |
| lng | float | yes | — |
| radius | int | no | 2000 (2km) |

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "place_id": "ChIJ...",
      "name": "مسجد الرحمن",
      "address": "شارع الملك فهد، الرياض",
      "distance_meters": 340,
      "latitude": 24.712,
      "longitude": 46.670,
      "rating": 4.5
    }
  ],
  "message": null
}
```

---

## HTTP Status Codes

| Code | Meaning |
|------|---------|
| 200 | Success |
| 400 | Bad request / missing params |
| 404 | Resource not found |
| 422 | Validation error |
| 500 | Server error |

---

## Rate Limiting

| Route | Limit |
|-------|-------|
| All API routes | 60 requests/minute per IP |
| `/devices/register` | 10 requests/minute per IP |
| `/mosques/nearby` | 20 requests/minute per IP |

---

## Caching Strategy

| Endpoint | Cache Duration |
|----------|---------------|
| Prayer times | 24 hours (per lat/lng/date) |
| Adhkar | 1 hour |
| Ruling topics | 1 hour |
| Rulings list | 30 minutes |
| Today's notification | Until midnight |
| Nearby mosques | 10 minutes |
