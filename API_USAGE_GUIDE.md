# 📘 دليل استخدام API - أركان

## 📋 فهرس المحتويات

1. [مقدمة](#مقدمة)
2. [الإعدادات الأساسية](#الإعدادات-الأساسية)
3. [هيكل الاستجابة](#هيكل-الاستجابة)
4. [نقاط النهاية (Endpoints)](#نقاط-النهاية-endpoints)
   - [تسجيل الجهاز](#1-تسجيل-الجهاز)
   - [أوقات الصلاة](#2-أوقات-الصلاة)
   - [الأذكار والأدعية](#3-الأذكار-والأدعية)
   - [الرسائل التحفيزية](#4-الرسائل-التحفيزية)
   - [الأحكام الشرعية](#5-الأحكام-الشرعية)
   - [الإشعارات اليومية](#6-الإشعارات-اليومية)
   - [المساجد القريبة](#7-المساجد-القريبة)
5. [القيود والحدود](#القيود-والحدود-rate-limiting)
6. [أمثلة بالكود](#أمثلة-بالكود)

---

## مقدمة

هذا الدليل يشرح كيفية استخدام API الخاص بتطبيق **أركان** - تطبيق إسلامي شامل يوفر:

- 🕌 أوقات الصلاة
- 📿 الأذكار والأدعية
- 📖 الأحكام الشرعية
- 🔔 إشعارات يومية
- 📍 المساجد القريبة
- 💬 رسائل تحفيزية

### رابط API الأساسي

```
https://your-domain.com/api
```

---

## الإعدادات الأساسية

### Headers المطلوبة

```http
Accept: application/json
Content-Type: application/json
```

### ترميز البيانات

جميع البيانات ترجع بصيغة **JSON** وترميز **UTF-8**.

---

## هيكل الاستجابة

### الاستجابة الناجحة

```json
{
  "success": true,
  "data": { ... },
  "message": null
}
```

### الاستجابة الفاشلة

```json
{
  "success": false,
  "data": null,
  "message": "وصف الخطأ"
}
```

### رموز HTTP الشائعة

| الرمز | المعنى |
|-------|--------|
| 200 | نجاح |
| 400 | خطأ في البيانات المدخلة |
| 404 | غير موجود |
| 429 | تجاوز الحد المسموح من الطلبات |
| 500 | خطأ في الخادم |
| 503 | الخدمة غير متوفرة |

---

## نقاط النهاية (Endpoints)

### 1. تسجيل الجهاز

يُستخدم لتسجيل أجهزة المستخدمين لإرسال الإشعارات.

**الرابط:**
```http
POST /api/devices/register
```

**Rate Limit:** 10 طلبات/دقيقة

#### المدخلات (Body)

| الحقل | النوع | إلزامي | الوصف |
|-------|-------|--------|-------|
| device_id | string | نعم | معرف فريد للجهاز |
| player_id | string | نعم | معرف OneSignal للإشعارات |
| platform | string | نعم | android أو ios |
| app_version | string | لا | إصدار التطبيق |

#### مثال على الطلب

```json
{
  "device_id": "abc123def456",
  "player_id": "onesignal-player-id-xyz",
  "platform": "android",
  "app_version": "1.0.0"
}
```

#### مثال على الاستجابة الناجحة

```json
{
  "success": true,
  "data": {
    "registered": true
  },
  "message": null
}
```

#### أخطاء شائعة

| الخطأ | الوصف |
|-------|-------|
| `device_id مطلوب` | لم يتم إرسال معرف الجهاز |
| `player_id مطلوب` | لم يتم إرسال معرف OneSignal |
| `platform غير صالح` | يجب أن يكون android أو ios |

---

### 2. أوقات الصلاة

يحصل على أوقات الصلاة لموقع جغرافي محدد.

**الرابط:**
```http
GET /api/prayers/times
```

**Rate Limit:** 60 طلب/دقيقة

#### المدخلات (Query Parameters)

| الحقل | النوع | إلزامي | الوصف | القيم الافتراضية |
|-------|-------|--------|-------|------------------|
| lat | float | نعم | خط العرض | - |
| lng | float | نعم | خط الطول | - |
| date | string | لا | التاريخ (YYYY-MM-DD) | اليوم |
| method | integer | لا | طريقة الحساب | 4 (Umm Al-Qura) |

#### طرق حساب أوقات الصلاة

| الرقم | الطريقة |
|-------|---------|
| 0 | جمعية العلوم الإسلامية بأمريكا الشمالية |
| 1 | رابطة العالم الإسلامي |
| 2 | جامعة الأزهر - الكريتر |
| 3 | جامعة أم القرى، مكة المكرمة |
| 4 | **Umm Al-Qura (افتراضي)** |
| 5 | الهيئة العامة للمساحة والإسناد الجغرافي |
| 7 | المعهد الجيوفيزيائي بجامعة طهران |
| 11 | اتحاد المنظمات الإسلامية في فرنسا |
| 12 | إدارة الأوقات في ماليزيا |
| 18 | الجامعة الإسلامية كراتشي |

#### مثال على الطلب

```http
GET /api/prayers/times?lat=24.7136&lng=46.6753&date=2024-01-15&method=4
```

#### مثال على الاستجابة الناجحة

```json
{
  "success": true,
  "data": {
    "date": "2024-01-15",
    "hijri_date": "1445-07-04",
    "timezone": "Asia/Riyadh",
    "times": {
      "fajr": "05:32",
      "sunrise": "06:52",
      "dhuhr": "12:20",
      "asr": "15:28",
      "maghrib": "17:49",
      "isha": "19:19"
    }
  },
  "message": null
}
```

#### أخطاء شائعة

| الرمز | الخطأ |
|-------|-------|
| 400 | `lat مطلوب` - يجب أن يكون بين -90 و 90 |
| 400 | `lng مطلوب` - يجب أن يكون بين -180 و 180 |
| 500 | تعذّر جلب أوقات الصلاة |

---

### 3. الأذكار والأدعية

#### 3.1 الحصول على التصنيفات

**الرابط:**
```http
GET /api/adhkar/categories
```

**Rate Limit:** 60 طلب/دقيقة

**Cache:** 1 ساعة

#### مثال على الاستجابة الناجحة

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "أذكار الصباح",
      "slug": "morning",
      "icon": "sun",
      "adhkar_count": 12
    },
    {
      "id": 2,
      "name": "أذكار المساء",
      "slug": "evening",
      "icon": "moon",
      "adhkar_count": 15
    },
    {
      "id": 3,
      "name": "أذكار النوم",
      "slug": "sleep",
      "icon": "bed",
      "adhkar_count": 8
    }
  ],
  "message": null
}
```

#### 3.2 الحصول على الأذكار حسب التصنيف

**الرابط:**
```http
GET /api/adhkar/{slug}
```

**Rate Limit:** 60 طلب/دقيقة

**Cache:** 1 ساعة

#### المدخلات (URL Parameters)

| الحقل | النوع | إلزامي | الوصف |
|-------|-------|--------|-------|
| slug | string | نعم | معرف التصنيف |

#### مثال على الطلب

```http
GET /api/adhkar/morning
```

#### مثال على الاستجابة الناجحة

```json
{
  "success": true,
  "data": {
    "category": {
      "id": 1,
      "name": "أذكار الصباح",
      "icon": "sun"
    },
    "adhkar": [
      {
        "id": 1,
        "text": "اللهم بك أصبحنا، وبك أمسينا، وبك نحيا، وبك نموت، وإليك النشور",
        "source": "رواه الترمذي",
        "count": 1,
        "order": 1
      },
      {
        "id": 2,
        "text": "أصبحنا وأصبح الملك لله، والحمد لله لا إله إلا الله وحده لا شريك له...",
        "source": "رواه مسلم",
        "count": 1,
        "order": 2
      }
    ]
  },
  "message": null
}
```

#### أخطاء شائعة

| الرمز | الخطأ |
|-------|-------|
| 404 | التصنيف غير موجود |

---

### 4. الرسائل التحفيزية

يحصل على رسالة عشوائية تحفيزية.

**الرابط:**
```http
GET /api/messages/random
```

**Rate Limit:** 60 طلب/دقيقة

#### المدخلات (Query Parameters)

| الحقل | النوع | إلزامي | الوصف | القيم الافتراضية |
|-------|-------|--------|-------|------------------|
| prayer | string | لا | وقت الصلاة | any |

#### قيم prayer المسموحة

| القيمة | الوصف |
|--------|-------|
| any | أي وقت (افتراضي) |
| fajr | الفجر |
| dhuhr | الظهر |
| asr | العصر |
| maghrib | المغرب |
| isha | العشاء |

#### مثال على الطلب

```http
GET /api/messages/random?prayer=fajr
```

#### مثال على الاستجابة الناجحة

```json
{
  "success": true,
  "data": {
    "id": 42,
    "text": "إن الصلاة كانت على المؤمنين كتاباً موقوتاً",
    "prayer_time": "fajr"
  },
  "message": null
}
```

#### أخطاء شائعة

| الرمز | الخطأ |
|-------|-------|
| 404 | لا توجد رسائل متاحة |

---

### 5. الأحكام الشرعية

#### 5.1 الحصول على المواضيع

**الرابط:**
```http
GET /api/rulings/topics
```

**Rate Limit:** 60 طلب/دقيقة

**Cache:** 1 ساعة

#### مثال على الاستجابة الناجحة

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "الطهارة",
      "icon": "water",
      "rulings_count": 25
    },
    {
      "id": 2,
      "name": "الصلاة",
      "icon": "mosque",
      "rulings_count": 42
    }
  ],
  "message": null
}
```

#### 5.2 الحصول على الأحكام

**الرابط:**
```http
GET /api/rulings
```

**Rate Limit:** 60 طلب/دقيقة

#### المدخلات (Query Parameters)

| الحقل | النوع | إلزامي | الوصف |
|-------|-------|--------|-------|
| topic_id | integer | لا | معرف الموضوع |
| search | string | لا | البحث في الأسئلة |
| page | integer | لا | رقم الصفحة (افتراضي: 1) |

#### مثال على الطلب

```http
GET /api/rulings?topic_id=1&search=الماء&page=1
```

#### مثال على الاستجابة الناجحة

```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 10,
    "total": 42,
    "items": [
      {
        "id": 1,
        "topic": {
          "id": 1,
          "name": "الطهارة"
        },
        "question": "ما حكم الماء المتغير لونه أو طعمه أو ريحه؟",
        "answer": "الماء المتغير نجس إذا تغير بسبب نجاسة، أما إذا تغير لأجل طول المكث...",
        "evidence": "قال الله تعالى: {وَأَنزَلْنَا مِنَ السَّمَاءِ مَاءً طَهُورًا}"
      }
    ]
  },
  "message": null
}
```

---

### 6. الإشعارات اليومية

يحصل على الإشعار اليومي.

**الرابط:**
```http
GET /api/notifications/today
```

**Rate Limit:** 60 طلب/دقيقة

**Cache:** حتى نهاية اليوم

#### مثال على الاستجابة الناجحة

```json
{
  "success": true,
  "data": {
    "id": 15,
    "title": "فضل صيام الاثنين والخميس",
    "body": "عن أبي هريرة رضي الله عنه عن النبي صلى الله عليه وسلم قال: «يُصْلِحُ اللَّهُ يَوْمَ الاثْنَيْنِ وَالْخَمِيسِ»",
    "type": "daily"
  },
  "message": null
}
```

**ملاحظة:** قد ترجع `data` بقيمة `null` إذا لم يكن هناك إشعار لليوم.

---

### 7. المساجد القريبة

يحصل على المساجد القريبة من موقع المستخدم.

**الرابط:**
```http
GET /api/mosques/nearby
```

**Rate Limit:** 60 طلب/دقيقة

**Cache:** 10 دقائق

#### المدخلات (Query Parameters)

| الحقل | النوع | إلزامي | الوصف | القيم الافتراضية |
|-------|-------|--------|-------|------------------|
| lat | float | نعم | خط العرض | - |
| lng | float | نعم | خط الطول | - |
| radius | integer | لا | نصف قطر البحث (متر) | 2000 |

**ملاحظة:** يجب أن يكون نصف القطر بين 100 و 10000 متر.

#### مثال على الطلب

```http
GET /api/mosques/nearby?lat=24.7136&lng=46.6753&radius=3000
```

#### مثال على الاستجابة الناجحة

```json
{
  "success": true,
  "data": [
    {
      "place_id": "ChIJ1234567890",
      "name": "جامع الدرعية الكبير",
      "address": "الدرعية، الرياض",
      "latitude": 24.7356,
      "longitude": 46.5723,
      "rating": 4.8,
      "distance_meters": 1250
    },
    {
      "place_id": "ChIJ0987654321",
      "name": "مسجد الفهد",
      "address": "شارع الملك فهد، الرياض",
      "latitude": 24.6987,
      "longitude": 46.6832,
      "rating": 4.5,
      "distance_meters": 2100
    }
  ],
  "message": null
}
```

#### أخطاء شائعة

| الرمز | الخطأ |
|-------|-------|
| 400 | lat أو lng غير صالح |
| 400 | radius يجب أن يكون بين 100 و 10000 |
| 503 | تعذر الاتصال بخدمة تحديد المواقع |

---

## القيود والحدود (Rate Limiting)

### نظام الحدود

| النقطة | الحد |
|--------|------|
| تسجيل الجهاز | 10 طلبات/دقيقة |
| باقي النقاط | 60 طلب/دقيقة |

### استجابة تجاوز الحد

```json
{
  "success": false,
  "data": null,
  "message": "Too many attempts. Please try again in 60 seconds."
}
```

**رمز HTTP:** 429

### نصائح لتجنب تجاوز الحدود

1. قم بتخزين البيانات محلياً (Caching)
2. استخدم الـ Headers للتحقق من الـ Rate Limit
3. أضف تأخير بين الطلبات المتكررة

---

## أمثلة بالكود

### JavaScript / React Native

```javascript
// تهيئة الرابط الأساسي
const API_BASE = 'https://your-domain.com/api';

// دالة مساعدة للطلبات
async function apiRequest(endpoint, options = {}) {
  const url = `${API_BASE}${endpoint}`;
  
  const response = await fetch(url, {
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
    },
    ...options,
  });
  
  const data = await response.json();
  
  if (!data.success) {
    throw new Error(data.message);
  }
  
  return data.data;
}

// 1. تسجيل الجهاز
async function registerDevice(deviceInfo) {
  return apiRequest('/devices/register', {
    method: 'POST',
    body: JSON.stringify(deviceInfo),
  });
}

// 2. الحصول على أوقات الصلاة
async function getPrayerTimes(lat, lng, date, method = 4) {
  const params = new URLSearchParams({ lat, lng, date, method });
  return apiRequest(`/prayers/times?${params}`);
}

// 3. الحصول على تصنيفات الأذكار
async function getAdhkarCategories() {
  return apiRequest('/adhkar/categories');
}

// 4. الحصول على الأذكار حسب التصنيف
async function getAdhkarByCategory(slug) {
  return apiRequest(`/adhkar/${slug}`);
}

// 5. الحصول على رسالة تحفيزية
async function getRandomMessage(prayer = 'any') {
  const params = new URLSearchParams({ prayer });
  return apiRequest(`/messages/random?${params}`);
}

// 6. الحصول على مواضيع الأحكام
async function getRulingTopics() {
  return apiRequest('/rulings/topics');
}

// 7. الحصول على الأحكام
async function getRulings(topicId, search, page = 1) {
  const params = new URLSearchParams({ topic_id: topicId, search, page });
  return apiRequest(`/rulings?${params}`);
}

// 8. الحصول على الإشعارات اليومية
async function getTodayNotification() {
  return apiRequest('/notifications/today');
}

// 9. الحصول على المساجد القريبة
async function getNearbyMosques(lat, lng, radius = 2000) {
  const params = new URLSearchParams({ lat, lng, radius });
  return apiRequest(`/mosques/nearby?${params}`);
}

// مثال الاستخدام
async function initApp() {
  try {
    // تسجيل الجهاز
    await registerDevice({
      device_id: 'unique-device-id',
      player_id: 'onesignal-player-id',
      platform: 'android',
      app_version: '1.0.0',
    });
    
    // الحصول على أوقات الصلاة
    const prayerTimes = await getPrayerTimes(24.7136, 46.6753, '2024-01-15', 4);
    console.log(prayerTimes);
    
    // الحصول على أذكار الصباح
    const morningAdhkar = await getAdhkarByCategory('morning');
    console.log(morningAdhkar);
    
  } catch (error) {
    console.error('Error:', error.message);
  }
}
```

### Dart / Flutter

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

class ArkanApi {
  static const String baseUrl = 'https://your-domain.com/api';
  
  Future<Map<String, dynamic>> _request(
    String endpoint, {
    String method = 'GET',
    Map<String, dynamic>? body,
    Map<String, String>? queryParams,
  }) async {
    
    Uri url = Uri.parse('$baseUrl$endpoint');
    
    if (queryParams != null) {
      url = url.replace(queryParameters: queryParams);
    }
    
    final headers = {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
    };
    
    late final http.Response response;
    
    switch (method) {
      case 'POST':
        response = await http.post(
          url,
          headers: headers,
          body: jsonEncode(body),
        );
        break;
      default:
        response = await http.get(url, headers: headers);
    }
    
    final data = jsonDecode(response.body);
    
    if (data['success'] != true) {
      throw Exception(data['message']);
    }
    
    return data['data'];
  }
  
  // تسجيل الجهاز
  Future<Map<String, dynamic>> registerDevice({
    required String deviceId,
    required String playerId,
    required String platform,
    String? appVersion,
  }) async {
    return _request('/devices/register', method: 'POST', body: {
      'device_id': deviceId,
      'player_id': playerId,
      'platform': platform,
      'app_version': appVersion,
    });
  }
  
  // أوقات الصلاة
  Future<Map<String, dynamic>> getPrayerTimes({
    required double lat,
    required double lng,
    String? date,
    int method = 4,
  }) async {
    return _request('/prayers/times', queryParams: {
      'lat': lat.toString(),
      'lng': lng.toString(),
      if (date != null) 'date': date,
      'method': method.toString(),
    });
  }
  
  // تصنيفات الأذكار
  Future<List<dynamic>> getAdhkarCategories() async {
    return _request('/adhkar/categories');
  }
  
  // الأذكار حسب التصنيف
  Future<Map<String, dynamic>> getAdhkarByCategory(String slug) async {
    return _request('/adhkar/$slug');
  }
  
  // رسالة عشوائية
  Future<Map<String, dynamic>> getRandomMessage({String prayer = 'any'}) async {
    return _request('/messages/random', queryParams: {'prayer': prayer});
  }
  
  // مواضيع الأحكام
  Future<List<dynamic>> getRulingTopics() async {
    return _request('/rulings/topics');
  }
  
  // الأحكام
  Future<Map<String, dynamic>> getRulings({
    int? topicId,
    String? search,
    int page = 1,
  }) async {
    return _request('/rulings', queryParams: {
      if (topicId != null) 'topic_id': topicId.toString(),
      if (search != null) 'search': search,
      'page': page.toString(),
    });
  }
  
  // الإشعارات اليومية
  Future<Map<String, dynamic>?> getTodayNotification() async {
    return _request('/notifications/today');
  }
  
  // المساجد القريبة
  Future<List<dynamic>> getNearbyMosques({
    required double lat,
    required double lng,
    int radius = 2000,
  }) async {
    return _request('/mosques/nearby', queryParams: {
      'lat': lat.toString(),
      'lng': lng.toString(),
      'radius': radius.toString(),
    });
  }
}
```

### cURL

```bash
# 1. تسجيل الجهاز
curl -X POST https://your-domain.com/api/devices/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "device_id": "abc123def456",
    "player_id": "onesignal-player-id",
    "platform": "android",
    "app_version": "1.0.0"
  }'

# 2. أوقات الصلاة
curl "https://your-domain.com/api/prayers/times?lat=24.7136&lng=46.6753&date=2024-01-15&method=4" \
  -H "Accept: application/json"

# 3. تصنيفات الأذكار
curl "https://your-domain.com/api/adhkar/categories" \
  -H "Accept: application/json"

# 4. أذكار الصباح
curl "https://your-domain.com/api/adhkar/morning" \
  -H "Accept: application/json"

# 5. رسالة تحفيزية
curl "https://your-domain.com/api/messages/random?prayer=fajr" \
  -H "Accept: application/json"

# 6. مواضيع الأحكام
curl "https://your-domain.com/api/rulings/topics" \
  -H "Accept: application/json"

# 7. الأحكام
curl "https://your-domain.com/api/rulings?topic_id=1&search=الماء&page=1" \
  -H "Accept: application/json"

# 8. الإشعارات اليومية
curl "https://your-domain.com/api/notifications/today" \
  -H "Accept: application/json"

# 9. المساجد القريبة
curl "https://your-domain.com/api/mosques/nearby?lat=24.7136&lng=46.6753&radius=3000" \
  -H "Accept: application/json"
```

---

## 📞 دعم فني

للاستفسارات أو الإبلاغ عن مشاكل:

- **البريد الإلكتروني:** support@arkan.app
- **الموقع:** https://arkan.app

---

**آخر تحديث:** 2024-01-15  
**إصدار API:** v1.0
