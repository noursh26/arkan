# Arkani Admin — Windsurf Prompt 1: Foundation & Database

> **Context:** Laravel 12 is already installed in the current directory. Read all files inside the `docs/` folder before starting. Do not skip any step. Verify each step before moving to the next.

---

## YOUR TASK

You are building the **Arkani Admin Panel** — an Arabic RTL web dashboard for a Muslim daily companion app. The project spec is in `docs/PROJECT_OVERVIEW.md`. The database schema is in `docs/DATABASE_SCHEMA.md`.

**Do not hallucinate or leave any placeholder empty.** Every file you create must be complete and working.

---

## STEP 1 — Install Required Packages

Run these commands one by one and verify each succeeds:

```bash
composer require livewire/livewire
composer require laravel/sanctum
composer require spatie/laravel-activitylog
npm install -D tailwindcss@3 postcss autoprefixer @tailwindcss/forms @tailwindcss/typography
npx tailwindcss init -p
```

Then publish configs:
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan livewire:publish --config
```

---

## STEP 2 — Environment Setup

Update `.env` file. Add these keys below the existing DB config (do not overwrite existing values):

```env
FCM_SERVER_KEY=your_firebase_server_key_here
FCM_PROJECT_ID=your_project_id_here
GOOGLE_PLACES_API_KEY=your_google_places_key_here
ALADHAN_API_URL=https://api.aladhan.com/v1
```

---

## STEP 3 — Tailwind Config

Replace `tailwind.config.js` with:

```js
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './app/Livewire/**/*.php',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50:  '#E1F5EE',
          100: '#9FE1CB',
          200: '#5DCAA5',
          400: '#1D9E75',
          600: '#0F6E56',
          800: '#085041',
          900: '#04342C',
        },
      },
      fontFamily: {
        arabic: ['Cairo', 'Tajawal', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
```

Update `resources/css/app.css`:

```css
@tailwind base;
@tailwind components;
@tailwind utilities;

@import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap');

body {
  font-family: 'Cairo', sans-serif;
  direction: rtl;
}

@layer components {
  .btn-primary {
    @apply bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-800 transition-colors text-sm font-medium;
  }
  .btn-secondary {
    @apply bg-white text-gray-700 px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors text-sm font-medium;
  }
  .btn-danger {
    @apply bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm font-medium;
  }
  .form-input {
    @apply block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-400 focus:ring-primary-400 text-sm;
  }
  .form-label {
    @apply block text-sm font-medium text-gray-700 mb-1;
  }
  .card {
    @apply bg-white rounded-xl border border-gray-200 shadow-sm;
  }
  .badge-active {
    @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800;
  }
  .badge-inactive {
    @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600;
  }
}
```

---

## STEP 4 — Database Migrations

Create these migration files in `database/migrations/`. Name them with timestamps in order:

### Migration 1: Add role to users table
File: `..._add_role_to_users_table.php`

```php
Schema::table('users', function (Blueprint $table) {
    $table->enum('role', ['super_admin', 'editor'])->default('editor')->after('email');
});
```

### Migration 2: adhkar_categories
File: `..._create_adhkar_categories_table.php`

```php
Schema::create('adhkar_categories', function (Blueprint $table) {
    $table->id();
    $table->string('name', 100);
    $table->string('slug', 100)->unique();
    $table->string('icon', 50)->nullable();
    $table->tinyInteger('order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    $table->softDeletes();
});
```

### Migration 3: adhkar
File: `..._create_adhkar_table.php`

```php
Schema::create('adhkar', function (Blueprint $table) {
    $table->id();
    $table->foreignId('category_id')->constrained('adhkar_categories')->cascadeOnDelete();
    $table->text('text');
    $table->string('source', 255)->nullable();
    $table->tinyInteger('count')->default(1);
    $table->smallInteger('order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    $table->softDeletes();
});
```

### Migration 4: motivational_messages
File: `..._create_motivational_messages_table.php`

```php
Schema::create('motivational_messages', function (Blueprint $table) {
    $table->id();
    $table->text('text');
    $table->enum('prayer_time', ['any', 'fajr', 'dhuhr', 'asr', 'maghrib', 'isha'])->default('any');
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    $table->softDeletes();
});
```

### Migration 5: ruling_topics
File: `..._create_ruling_topics_table.php`

```php
Schema::create('ruling_topics', function (Blueprint $table) {
    $table->id();
    $table->string('name', 100);
    $table->string('icon', 50)->nullable();
    $table->tinyInteger('order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    $table->softDeletes();
});
```

### Migration 6: islamic_rulings
File: `..._create_islamic_rulings_table.php`

```php
Schema::create('islamic_rulings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('topic_id')->constrained('ruling_topics')->cascadeOnDelete();
    $table->string('question', 500);
    $table->text('answer');
    $table->text('evidence')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    $table->softDeletes();
});
```

### Migration 7: daily_notifications
File: `..._create_daily_notifications_table.php`

```php
Schema::create('daily_notifications', function (Blueprint $table) {
    $table->id();
    $table->string('title', 255);
    $table->text('body');
    $table->enum('type', ['khulq', 'nafl', 'dua', 'reminder'])->default('reminder');
    $table->date('scheduled_date')->nullable();
    $table->time('send_time')->default('07:00:00');
    $table->boolean('is_sent')->default(false);
    $table->timestamp('sent_at')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    $table->softDeletes();
});
```

### Migration 8: app_devices
File: `..._create_app_devices_table.php`

```php
Schema::create('app_devices', function (Blueprint $table) {
    $table->id();
    $table->string('device_id', 255)->unique();
    $table->text('fcm_token');
    $table->enum('platform', ['android', 'ios'])->default('android');
    $table->string('app_version', 20)->nullable();
    $table->timestamp('last_seen_at')->nullable();
    $table->timestamps();
});
```

### Migration 9: notification_logs
File: `..._create_notification_logs_table.php`

```php
Schema::create('notification_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('notification_id')->constrained('daily_notifications')->cascadeOnDelete();
    $table->integer('devices_count')->default(0);
    $table->integer('success_count')->default(0);
    $table->integer('failure_count')->default(0);
    $table->timestamp('sent_at');
    $table->timestamps();
});
```

After creating all migrations, run:
```bash
php artisan migrate
```

---

## STEP 5 — Models

Create all models with correct relationships and soft deletes:

### `app/Models/AdhkarCategory.php`
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdhkarCategory extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'icon', 'order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function adhkar(): HasMany
    {
        return $this->hasMany(Dhikr::class, 'category_id');
    }

    public function activeAdhkar(): HasMany
    {
        return $this->hasMany(Dhikr::class, 'category_id')->where('is_active', true)->orderBy('order');
    }
}
```

### `app/Models/Dhikr.php`
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dhikr extends Model
{
    use SoftDeletes;

    protected $table = 'adhkar';
    protected $fillable = ['category_id', 'text', 'source', 'count', 'order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(AdhkarCategory::class, 'category_id');
    }
}
```

### `app/Models/MotivationalMessage.php`
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MotivationalMessage extends Model
{
    use SoftDeletes;

    protected $fillable = ['text', 'prayer_time', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function getPrayerTimeLabelAttribute(): string
    {
        return match($this->prayer_time) {
            'fajr'    => 'الفجر',
            'dhuhr'   => 'الظهر',
            'asr'     => 'العصر',
            'maghrib' => 'المغرب',
            'isha'    => 'العشاء',
            default   => 'الكل',
        };
    }
}
```

### `app/Models/RulingTopic.php`
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RulingTopic extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'icon', 'order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function rulings(): HasMany
    {
        return $this->hasMany(IslamicRuling::class, 'topic_id');
    }
}
```

### `app/Models/IslamicRuling.php`
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IslamicRuling extends Model
{
    use SoftDeletes;

    protected $fillable = ['topic_id', 'question', 'answer', 'evidence', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(RulingTopic::class, 'topic_id');
    }
}
```

### `app/Models/DailyNotification.php`
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyNotification extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'body', 'type', 'scheduled_date', 'send_time', 'is_sent', 'sent_at', 'is_active'];
    protected $casts = [
        'is_sent'        => 'boolean',
        'is_active'      => 'boolean',
        'scheduled_date' => 'date',
        'sent_at'        => 'datetime',
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(NotificationLog::class, 'notification_id');
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'khulq'    => 'خلق',
            'nafl'     => 'نافلة',
            'dua'      => 'دعاء',
            default    => 'تذكير',
        };
    }
}
```

### `app/Models/AppDevice.php`
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppDevice extends Model
{
    protected $fillable = ['device_id', 'fcm_token', 'platform', 'app_version', 'last_seen_at'];
    protected $casts = ['last_seen_at' => 'datetime'];
}
```

### `app/Models/NotificationLog.php`
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationLog extends Model
{
    protected $fillable = ['notification_id', 'devices_count', 'success_count', 'failure_count', 'sent_at'];
    protected $casts = ['sent_at' => 'datetime'];

    public function notification(): BelongsTo
    {
        return $this->belongsTo(DailyNotification::class, 'notification_id');
    }
}
```

Also update `app/Models/User.php` — add role field:
```php
protected $fillable = ['name', 'email', 'password', 'role'];
```

---

## STEP 6 — Database Seeders

Create `database/seeders/ArkaniSeeder.php` with ALL of this real seed data:

```php
<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\AdhkarCategory;
use App\Models\Dhikr;
use App\Models\MotivationalMessage;
use App\Models\RulingTopic;
use App\Models\IslamicRuling;
use App\Models\DailyNotification;

class ArkaniSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::firstOrCreate(
            ['email' => 'admin@arkani.app'],
            [
                'name'     => 'مدير النظام',
                'password' => Hash::make('Admin@1234'),
                'role'     => 'super_admin',
            ]
        );

        // Adhkar Categories
        $categories = [
            ['name' => 'أذكار الصباح',    'slug' => 'morning',       'icon' => '☀️', 'order' => 1],
            ['name' => 'أذكار المساء',     'slug' => 'evening',       'icon' => '🌙', 'order' => 2],
            ['name' => 'أذكار بعد الصلاة', 'slug' => 'after-prayer', 'icon' => '🤲', 'order' => 3],
            ['name' => 'أذكار الأذان',     'slug' => 'adhan',         'icon' => '📣', 'order' => 4],
        ];

        foreach ($categories as $cat) {
            AdhkarCategory::firstOrCreate(['slug' => $cat['slug']], array_merge($cat, ['is_active' => true]));
        }

        $morning = AdhkarCategory::where('slug', 'morning')->first();
        $evening = AdhkarCategory::where('slug', 'evening')->first();
        $afterPrayer = AdhkarCategory::where('slug', 'after-prayer')->first();
        $adhan = AdhkarCategory::where('slug', 'adhan')->first();

        // Morning adhkar
        $morningAdhkar = [
            ['text' => 'أَصْبَحْنَا وَأَصْبَحَ الْمُلْكُ لِلَّهِ، وَالْحَمْدُ لِلَّهِ، لَا إِلَهَ إِلَّا اللَّهُ وَحْدَهُ لَا شَرِيكَ لَهُ، لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ وَهُوَ عَلَى كُلِّ شَيْءٍ قَدِيرٌ', 'source' => 'رواه مسلم', 'count' => 1],
            ['text' => 'اللَّهُمَّ بِكَ أَصْبَحْنَا وَبِكَ أَمْسَيْنَا، وَبِكَ نَحْيَا وَبِكَ نَمُوتُ وَإِلَيْكَ النُّشُورُ', 'source' => 'رواه أبو داود والترمذي', 'count' => 1],
            ['text' => 'اللَّهُمَّ أَنْتَ رَبِّي لَا إِلَهَ إِلَّا أَنْتَ، خَلَقْتَنِي وَأَنَا عَبْدُكَ، وَأَنَا عَلَى عَهْدِكَ وَوَعْدِكَ مَا اسْتَطَعْتُ، أَعُوذُ بِكَ مِنْ شَرِّ مَا صَنَعْتُ', 'source' => 'رواه البخاري', 'count' => 1],
            ['text' => 'سُبْحَانَ اللَّهِ وَبِحَمْدِهِ', 'source' => 'رواه مسلم', 'count' => 100],
            ['text' => 'بِسْمِ اللَّهِ الَّذِي لَا يَضُرُّ مَعَ اسْمِهِ شَيْءٌ فِي الأَرْضِ وَلَا فِي السَّمَاءِ وَهُوَ السَّمِيعُ الْعَلِيمُ', 'source' => 'رواه أبو داود والترمذي', 'count' => 3],
        ];

        foreach ($morningAdhkar as $i => $dhikr) {
            Dhikr::firstOrCreate(
                ['category_id' => $morning->id, 'order' => $i + 1],
                array_merge($dhikr, ['category_id' => $morning->id, 'order' => $i + 1, 'is_active' => true])
            );
        }

        // Evening adhkar
        $eveningAdhkar = [
            ['text' => 'أَمْسَيْنَا وَأَمْسَى الْمُلْكُ لِلَّهِ، وَالْحَمْدُ لِلَّهِ، لَا إِلَهَ إِلَّا اللَّهُ وَحْدَهُ لَا شَرِيكَ لَهُ، لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ وَهُوَ عَلَى كُلِّ شَيْءٍ قَدِيرٌ', 'source' => 'رواه مسلم', 'count' => 1],
            ['text' => 'اللَّهُمَّ بِكَ أَمْسَيْنَا وَبِكَ أَصْبَحْنَا، وَبِكَ نَحْيَا وَبِكَ نَمُوتُ وَإِلَيْكَ الْمَصِيرُ', 'source' => 'رواه أبو داود والترمذي', 'count' => 1],
            ['text' => 'اللَّهُمَّ أَنْتَ رَبِّي لَا إِلَهَ إِلَّا أَنْتَ، عَلَيْكَ تَوَكَّلْتُ وَأَنْتَ رَبُّ الْعَرْشِ الْعَظِيمِ', 'source' => 'رواه أبو داود', 'count' => 1],
        ];

        foreach ($eveningAdhkar as $i => $dhikr) {
            Dhikr::firstOrCreate(
                ['category_id' => $evening->id, 'order' => $i + 1],
                array_merge($dhikr, ['category_id' => $evening->id, 'order' => $i + 1, 'is_active' => true])
            );
        }

        // After prayer adhkar
        $afterPrayerAdhkar = [
            ['text' => 'أَسْتَغْفِرُ اللَّهَ', 'source' => 'رواه مسلم', 'count' => 3],
            ['text' => 'اللَّهُمَّ أَنْتَ السَّلَامُ وَمِنْكَ السَّلَامُ، تَبَارَكْتَ يَا ذَا الْجَلَالِ وَالْإِكْرَامِ', 'source' => 'رواه مسلم', 'count' => 1],
            ['text' => 'لَا إِلَهَ إِلَّا اللَّهُ وَحْدَهُ لَا شَرِيكَ لَهُ، لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ وَهُوَ عَلَى كُلِّ شَيْءٍ قَدِيرٌ', 'source' => 'رواه مسلم', 'count' => 1],
            ['text' => 'سُبْحَانَ اللَّهِ', 'source' => 'رواه مسلم', 'count' => 33],
            ['text' => 'الْحَمْدُ لِلَّهِ', 'source' => 'رواه مسلم', 'count' => 33],
            ['text' => 'اللَّهُ أَكْبَرُ', 'source' => 'رواه مسلم', 'count' => 34],
        ];

        foreach ($afterPrayerAdhkar as $i => $dhikr) {
            Dhikr::firstOrCreate(
                ['category_id' => $afterPrayer->id, 'order' => $i + 1],
                array_merge($dhikr, ['category_id' => $afterPrayer->id, 'order' => $i + 1, 'is_active' => true])
            );
        }

        // Adhan adhkar
        $adhanAdhkar = [
            ['text' => 'اللَّهُمَّ رَبَّ هَذِهِ الدَّعْوَةِ التَّامَّةِ، وَالصَّلَاةِ الْقَائِمَةِ، آتِ مُحَمَّداً الْوَسِيلَةَ وَالْفَضِيلَةَ، وَابْعَثْهُ مَقَاماً مَحْمُوداً الَّذِي وَعَدْتَهُ', 'source' => 'رواه البخاري', 'count' => 1],
        ];

        foreach ($adhanAdhkar as $i => $dhikr) {
            Dhikr::firstOrCreate(
                ['category_id' => $adhan->id, 'order' => $i + 1],
                array_merge($dhikr, ['category_id' => $adhan->id, 'order' => $i + 1, 'is_active' => true])
            );
        }

        // Motivational Messages
        $messages = [
            ['text' => 'أذّنت؟ أحسن تصلي ركعتين قبل الفريضة إن استطعت 🤍', 'prayer_time' => 'fajr'],
            ['text' => 'الفجر فرصة ربحان، لا تفوّتها 🌅', 'prayer_time' => 'fajr'],
            ['text' => 'من صلى الفجر في جماعة فكأنما صلى الليل كله — ابدأ يومك بالله ☀️', 'prayer_time' => 'fajr'],
            ['text' => 'أذان الظهر، خذ استراحتك مع الله 🤲', 'prayer_time' => 'dhuhr'],
            ['text' => 'لحظات بين الأذان والإقامة من أفضل أوقات الدعاء 💚', 'prayer_time' => 'dhuhr'],
            ['text' => 'وسط يومك المشغول، خذ دقائق مع ربك 🕌', 'prayer_time' => 'asr'],
            ['text' => 'صلاة العصر محافظ عليها؟ حافظ عليها ترى هي الوسطى 🌿', 'prayer_time' => 'asr'],
            ['text' => 'أذان المغرب، انتهى النهار بخير — الحمد لله 🌇', 'prayer_time' => 'maghrib'],
            ['text' => 'بين المغرب والعشاء وقت ذهبي للقرآن 📖', 'prayer_time' => 'maghrib'],
            ['text' => 'العشاء آخر صلاة اليوم، اختمه مع الله 🌙', 'prayer_time' => 'isha'],
            ['text' => 'من صلى العشاء في جماعة فكأنما قام نصف الليل ⭐', 'prayer_time' => 'isha'],
            ['text' => 'الأذان = فرصة نفل، لا تضيّعها 🌟', 'prayer_time' => 'any'],
            ['text' => 'أجب نداء ربك، كل صلاة ترفعك درجة 💫', 'prayer_time' => 'any'],
            ['text' => 'الصلاة نور، كل ما صليت كلما نوّر الله قلبك ✨', 'prayer_time' => 'any'],
            ['text' => 'استثمر الفرصة: أذّن في قلبك وقل «لا حول ولا قوة إلا بالله» 🤍', 'prayer_time' => 'any'],
        ];

        foreach ($messages as $msg) {
            MotivationalMessage::firstOrCreate(
                ['text' => $msg['text']],
                array_merge($msg, ['is_active' => true])
            );
        }

        // Ruling Topics
        $topics = [
            ['name' => 'الطهارة',  'icon' => '💧', 'order' => 1],
            ['name' => 'الصلاة',   'icon' => '🕌', 'order' => 2],
            ['name' => 'الصيام',   'icon' => '🌙', 'order' => 3],
            ['name' => 'الزكاة',   'icon' => '💚', 'order' => 4],
            ['name' => 'أحكام عامة', 'icon' => '📖', 'order' => 5],
        ];

        foreach ($topics as $topic) {
            RulingTopic::firstOrCreate(['name' => $topic['name']], array_merge($topic, ['is_active' => true]));
        }

        $topicModels = RulingTopic::all()->keyBy('name');

        $rulings = [
            [
                'topic' => 'الطهارة',
                'question' => 'هل يجوز الصلاة بدون وضوء ناسياً؟',
                'answer' => 'لا تصح الصلاة بدون وضوء ناسياً كان أم متعمداً، ويجب إعادتها بعد التطهر.',
                'evidence' => 'قال رسول الله ﷺ: «لا تُقبل صلاة بغير طهور» رواه مسلم',
            ],
            [
                'topic' => 'الصلاة',
                'question' => 'ما حكم الصلاة في وقت النهي؟',
                'answer' => 'أوقات النهي هي بعد صلاة الفجر حتى ترتفع الشمس، وعند الاستواء حتى تزول، وبعد صلاة العصر حتى تغرب. لا يجوز التطوع فيها إلا ذوات الأسباب كتحية المسجد.',
                'evidence' => 'حديث عقبة بن عامر: «ثلاث ساعات كان رسول الله ﷺ ينهانا أن نصلي فيهن» رواه مسلم',
            ],
            [
                'topic' => 'الصلاة',
                'question' => 'هل يجوز قصر الصلاة في السفر؟',
                'answer' => 'نعم، قصر الصلاة الرباعية إلى ركعتين جائز بل مستحب في السفر الذي تبلغ مسافته 80 كم فأكثر.',
                'evidence' => 'قال الله تعالى: «وَإِذَا ضَرَبْتُمْ فِي الأَرْضِ فَلَيْسَ عَلَيْكُمْ جُنَاحٌ أَن تَقْصُرُوا مِنَ الصَّلاةِ» سورة النساء 101',
            ],
            [
                'topic' => 'الصيام',
                'question' => 'هل يبطل الصيام بالأكل ناسياً؟',
                'answer' => 'لا يبطل الصيام بالأكل أو الشرب ناسياً، ويجب على من تذكر أن يكفّ فوراً ويكمل صومه.',
                'evidence' => 'قال رسول الله ﷺ: «من نسي وهو صائم فأكل أو شرب فليتم صومه، فإنما أطعمه الله وسقاه» رواه البخاري ومسلم',
            ],
            [
                'topic' => 'أحكام عامة',
                'question' => 'ما حكم الغيبة؟',
                'answer' => 'الغيبة محرمة وهي ذكر أخاك بما يكره ولو كان فيه. وهي من الكبائر التي حرمها الله في القرآن الكريم.',
                'evidence' => 'قال الله تعالى: «وَلَا يَغْتَب بَّعْضُكُم بَعْضاً» سورة الحجرات 12',
            ],
        ];

        foreach ($rulings as $ruling) {
            $topic = $topicModels[$ruling['topic']] ?? null;
            if ($topic) {
                IslamicRuling::firstOrCreate(
                    ['question' => $ruling['question']],
                    [
                        'topic_id' => $topic->id,
                        'question' => $ruling['question'],
                        'answer'   => $ruling['answer'],
                        'evidence' => $ruling['evidence'],
                        'is_active' => true,
                    ]
                );
            }
        }

        // Daily Notifications (one per day of week)
        $notifications = [
            ['title' => 'خلق اليوم 🌿', 'body' => 'الصدق: قال النبي ﷺ «عليكم بالصدق فإن الصدق يهدي إلى البر وإن البر يهدي إلى الجنة»', 'type' => 'khulq'],
            ['title' => 'نافلة اليوم 🤲', 'body' => 'صلاة الضحى: ركعتان أو أربع بعد ارتفاع الشمس — قال ﷺ «من صلى الضحى ركعتين لم يُكتب من الغافلين»', 'type' => 'nafl'],
            ['title' => 'دعاء اليوم 💚', 'body' => '«اللَّهُمَّ اجعل في قلبي نوراً، وفي لساني نوراً، وفي سمعي نوراً، وفي بصري نوراً»', 'type' => 'dua'],
            ['title' => 'تذكير اليوم ⭐', 'body' => 'لا تنسَ أذكار الصباح، هي درعك ليومك بإذن الله 🌅', 'type' => 'reminder'],
            ['title' => 'خلق اليوم 🌸', 'body' => 'الرفق: قال رسول الله ﷺ «إن الله رفيق يحب الرفق في الأمر كله»', 'type' => 'khulq'],
            ['title' => 'نافلة اليوم 🕌', 'body' => 'ركعتا الفجر: قال ﷺ «ركعتا الفجر خير من الدنيا وما فيها» — لا تفوّتهما', 'type' => 'nafl'],
            ['title' => 'دعاء اليوم 🤍', 'body' => '«رَبَّنَا آتِنَا فِي الدُّنْيَا حَسَنَةً وَفِي الآخِرَةِ حَسَنَةً وَقِنَا عَذَابَ النَّارِ»', 'type' => 'dua'],
        ];

        foreach ($notifications as $notif) {
            DailyNotification::firstOrCreate(
                ['title' => $notif['title']],
                array_merge($notif, ['send_time' => '07:00:00', 'is_active' => true, 'is_sent' => false])
            );
        }

        $this->command->info('✅ Arkani seed data created successfully!');
    }
}
```

Register seeder in `DatabaseSeeder.php`:
```php
public function run(): void
{
    $this->call(ArkaniSeeder::class);
}
```

Run seeder:
```bash
php artisan db:seed
```

---

## STEP 7 — Authentication Setup

Run:
```bash
php artisan make:auth
```
Or if using Laravel 12 built-in auth, ensure login routes exist at `/login`.

Add admin middleware. Create `app/Http/Middleware/AdminMiddleware.php`:

```php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
```

Register in `bootstrap/app.php` (Laravel 12):
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ]);
})
```

---

## STEP 8 — Services

### `app/Services/FcmService.php`

```php
<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    private string $serverKey;
    private string $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

    public function __construct()
    {
        $this->serverKey = config('services.fcm.server_key');
    }

    public function sendToAll(string $title, string $body, array $data = []): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'key=' . $this->serverKey,
            'Content-Type'  => 'application/json',
        ])->post($this->fcmUrl, [
            'to'           => '/topics/all',
            'notification' => ['title' => $title, 'body' => $body, 'sound' => 'default'],
            'data'         => $data,
        ]);

        if ($response->failed()) {
            Log::error('FCM send failed', ['response' => $response->body()]);
            return ['success' => false, 'error' => $response->body()];
        }

        $result = $response->json();
        return [
            'success'       => true,
            'success_count' => $result['success'] ?? 0,
            'failure_count' => $result['failure'] ?? 0,
        ];
    }

    public function sendToToken(string $token, string $title, string $body): bool
    {
        $response = Http::withHeaders([
            'Authorization' => 'key=' . $this->serverKey,
            'Content-Type'  => 'application/json',
        ])->post($this->fcmUrl, [
            'to'           => $token,
            'notification' => ['title' => $title, 'body' => $body, 'sound' => 'default'],
        ]);

        return $response->successful();
    }
}
```

Add to `config/services.php`:
```php
'fcm' => [
    'server_key' => env('FCM_SERVER_KEY'),
    'project_id' => env('FCM_PROJECT_ID'),
],
'google_places' => [
    'key' => env('GOOGLE_PLACES_API_KEY'),
],
'aladhan' => [
    'url' => env('ALADHAN_API_URL', 'https://api.aladhan.com/v1'),
],
```

### `app/Services/PrayerTimesService.php`

```php
<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PrayerTimesService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.aladhan.url');
    }

    public function getTimes(float $lat, float $lng, string $date = null, int $method = 4): array
    {
        $date = $date ?? now()->format('d-m-Y');
        $cacheKey = "prayer_times_{$lat}_{$lng}_{$date}_{$method}";

        return Cache::remember($cacheKey, 86400, function () use ($lat, $lng, $date, $method) {
            $response = Http::get("{$this->baseUrl}/timings/{$date}", [
                'latitude'  => $lat,
                'longitude' => $lng,
                'method'    => $method,
            ]);

            if ($response->failed()) {
                return [];
            }

            $data = $response->json('data');
            return [
                'timings'  => $data['timings'] ?? [],
                'date'     => $data['date']['readable'] ?? $date,
                'location' => [
                    'latitude'  => $lat,
                    'longitude' => $lng,
                    'timezone'  => $data['meta']['timezone'] ?? 'UTC',
                ],
            ];
        });
    }
}
```

---

## STEP 9 — Build Frontend Assets

```bash
npm run build
```

Verify no errors. If errors occur, fix them before proceeding.

---

## STEP 10 — Final Verification

Run these checks:
```bash
php artisan route:list | grep api      # Should show no routes yet — that's fine
php artisan migrate:status             # All migrations should show "Ran"
php artisan tinker --execute="echo App\Models\User::count() . ' users';"
php artisan tinker --execute="echo App\Models\AdhkarCategory::count() . ' categories';"
php artisan tinker --execute="echo App\Models\MotivationalMessage::count() . ' messages';"
```

Expected output:
```
1 users
4 categories
15 messages
```

---

## ✅ DONE — Prompt 1 Complete

After this prompt is complete, the project should have:
- All packages installed
- All migrations run
- All models created with relationships
- Real seed data inserted
- FCM and Prayer Times services ready
- Frontend assets built

**Proceed to Prompt 2 for the full admin dashboard UI and API endpoints.**
