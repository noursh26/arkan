# Arkani — Admin Panel Features

## Tech Stack
- **Framework:** Laravel 12
- **Reactive UI:** Livewire 3
- **Styling:** Tailwind CSS v3
- **Language:** Arabic (RTL layout)
- **Auth:** Laravel built-in + Sanctum
- **Icons:** Heroicons (via blade-heroicons)

---

## Layout & Design System

### Sidebar Navigation (RTL)
```
أركاني  [Logo + Brand]
─────────────────────
لوحة التحكم          [Dashboard]
─────────────────────
المحتوى
  رسائل الأذان       [Messages]
  الأذكار            [Adhkar]
  الأحكام الشرعية    [Rulings]
─────────────────────
الإشعارات
  الإشعارات اليومية  [Daily Notifications]
─────────────────────
المستخدمون
  الأجهزة المسجلة    [App Devices]
─────────────────────
الإعدادات           [Settings]
```

### Color Palette
- **Primary:** Teal/Green `#0F6E56` (Islamic green)
- **Secondary:** Amber `#BA7517`
- **Background:** `gray-50`
- **Sidebar:** `gray-900`
- **Cards:** white with `gray-200` border

---

## Pages & Features

---

### 1. Dashboard (`/admin`)

**Stats Cards (top row):**
- إجمالي الأجهزة المسجلة
- إشعارات أُرسلت هذا الأسبوع
- إجمالي الأذكار النشطة
- إجمالي الأحكام الشرعية

**Quick Actions:**
- إضافة رسالة جديدة
- إضافة حكم شرعي
- جدولة إشعار اليوم

**Recent Activity:**
- آخر 10 أجهزة سُجّلت (device_id + platform + created_at)

---

### 2. الرسائل التحفيزية — Motivational Messages (`/admin/messages`)

**List View (Livewire table):**
- Columns: النص (truncated 60 chars) | وقت الصلاة | الحالة | الإجراءات
- Filter by: prayer_time
- Toggle active/inactive inline

**Create/Edit (Modal or inline form):**
```
حقل: نص الرسالة (textarea, required, max 300 chars)
حقل: وقت الصلاة (select: الكل / الفجر / الظهر / العصر / المغرب / العشاء)
حقل: الحالة (toggle: نشط / غير نشط)
```

**Validation:**
- text: required, min:10, max:300
- prayer_time: required, in:any,fajr,dhuhr,asr,maghrib,isha

---

### 3. الأذكار — Adhkar (`/admin/adhkar`)

**Two sub-tabs:**
- التصنيفات (Categories)
- الأذكار (Adhkar list)

**Categories Table:**
- Columns: الاسم | الأيقونة | الترتيب | عدد الأذكار | الحالة | الإجراءات
- Inline sort (drag or up/down arrows)

**Category Form:**
```
حقل: اسم التصنيف (text, required)
حقل: الأيقونة (text, emoji picker or type)
حقل: الترتيب (number)
حقل: الحالة (toggle)
```

**Adhkar Table:**
- Columns: نص الذكر (truncated) | التصنيف | المصدر | العدد | الحالة | الإجراءات
- Filter by: category

**Dhikr Form:**
```
حقل: نص الذكر (textarea, required, Arabic)
حقل: التصنيف (select, required)
حقل: المصدر (text, optional — e.g. "رواه البخاري")
حقل: عدد التكرار (number, default 1)
حقل: الترتيب (number)
حقل: الحالة (toggle)
```

---

### 4. الأحكام الشرعية — Islamic Rulings (`/admin/rulings`)

**Two sub-tabs:**
- المواضيع (Topics)
- الأحكام (Rulings)

**Topics Table:**
- Columns: الاسم | الأيقونة | عدد الأحكام | الحالة | الإجراءات

**Topic Form:**
```
حقل: اسم الموضوع (text)
حقل: الأيقونة (emoji)
حقل: الترتيب (number)
```

**Rulings Table:**
- Columns: السؤال (truncated) | الموضوع | الحالة | الإجراءات
- Filter by: topic
- Search by: question text

**Ruling Form:**
```
حقل: الموضوع (select, required)
حقل: السؤال (text, required, max 500)
حقل: الجواب (textarea + rich text, required)
حقل: الدليل الشرعي (textarea, optional)
حقل: الحالة (toggle)
```

---

### 5. الإشعارات اليومية — Daily Notifications (`/admin/notifications`)

**Two tabs:**
- الجدولة (Upcoming)
- السجل (Sent History)

**Upcoming Table:**
- Columns: العنوان | النص (truncated) | النوع | التاريخ | وقت الإرسال | الحالة | الإجراءات
- Filter by: type, is_sent

**Notification Form:**
```
حقل: العنوان (text, required, max 100)
حقل: النص (textarea, required, max 300)
حقل: النوع (select: خلق / نافلة / دعاء / تذكير)
حقل: تاريخ الإرسال (date picker, optional — null = repeating)
حقل: وقت الإرسال (time picker, default 07:00)
حقل: الحالة (toggle)
```

**Send Now Button:**
- Sends immediately to all registered devices via FCM
- Shows confirmation modal before sending

**Sent History Table:**
- Columns: العنوان | تاريخ الإرسال | الأجهزة | ناجح | فشل

---

### 6. الأجهزة المسجلة — App Devices (`/admin/devices`)

**Read-only table:**
- Columns: معرف الجهاز (truncated) | المنصة | الإصدار | آخر نشاط | تاريخ التسجيل
- Filter by: platform (Android / iOS)
- Search by: device_id

**Stats at top:**
- إجمالي الأجهزة
- Android count
- iOS count
- نشط خلال 7 أيام

---

### 7. الإعدادات — Settings (`/admin/settings`)

```
قسم: الحساب
  تغيير الاسم
  تغيير البريد الإلكتروني
  تغيير كلمة المرور

قسم: الإشعارات
  وقت الإشعار اليومي الافتراضي (time picker)

قسم: معلومات التطبيق
  إصدار API
  عدد السجلات (read-only stats)
```

---

## Shared UI Components

### Confirmation Delete Modal
Reusable Livewire component. Shows confirmation before any delete.
```
"هل أنت متأكد من حذف هذا العنصر؟"
[إلغاء] [حذف]
```

### Toast Notifications
Livewire flash notifications:
- نجاح ✅ (green)
- خطأ ❌ (red)
- تحذير ⚠️ (amber)

### Empty State
Every table shows Arabic empty state with icon when no records.

### Pagination
Arabic pagination: "السابق" / "التالي" with page numbers.

---

## Authorization

| Role | Permissions |
|------|------------|
| `super_admin` | Full access including Settings and user management |
| `editor` | CRUD on content only (no Settings, no Devices delete) |

---

## Activity Log (using spatie/laravel-activitylog)

Track all CRUD operations:
```
"أضاف admin@arkani.app رسالة جديدة: «أذنت؟ أحسن تصلي...»"
"حذف admin@arkani.app ذكراً من فئة أذكار الصباح"
"أرسل admin@arkani.app إشعار: خلق اليوم — إلى 1,205 جهاز"
```
