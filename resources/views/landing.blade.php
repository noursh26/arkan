<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أركاني - تطبيقك الإسلامي الشامل</title>
    <meta name="description" content="تطبيق أركاني - أذكار، أحكام شرعية، أوقات الصلاة، وإشعارات يومية">
    
    <!-- Google Fonts: Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #0F6E56 0%, #0a5240 50%, #065f46 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .floating {
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-delayed {
            animation: float 6s ease-in-out infinite;
            animation-delay: 2s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(15, 110, 86, 0.4); }
            50% { box-shadow: 0 0 40px rgba(15, 110, 86, 0.8); }
        }
        
        .feature-card {
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .scroll-reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }
        
        .scroll-reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .pattern-bg {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased overflow-x-hidden">
    
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#0F6E56] to-[#0a5240] rounded-full flex items-center justify-center">
                        <span class="text-xl font-bold text-white">أ</span>
                    </div>
                    <span class="text-xl font-bold text-[#0F6E56]">أركاني</span>
                </div>
                
                <div class="hidden md:flex items-center gap-6">
                    <a href="#features" class="text-gray-600 hover:text-[#0F6E56] transition-colors font-medium">المميزات</a>
                    <a href="#about" class="text-gray-600 hover:text-[#0F6E56] transition-colors font-medium">عن التطبيق</a>
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-[#0F6E56] transition-colors font-medium">تسجيل الدخول</a>
                    <a href="#download" class="px-5 py-2 bg-[#0F6E56] text-white rounded-full hover:bg-[#0a5240] transition-all font-medium">
                        تحميل التطبيق
                    </a>
                </div>
                
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100">
            <div class="px-4 py-4 space-y-3">
                <a href="#features" class="block py-2 text-gray-600 hover:text-[#0F6E56]">المميزات</a>
                <a href="#about" class="block py-2 text-gray-600 hover:text-[#0F6E56]">عن التطبيق</a>
                <a href="{{ route('login') }}" class="block py-2 text-gray-600 hover:text-[#0F6E56]">تسجيل الدخول</a>
                <a href="#download" class="block py-2 text-[#0F6E56] font-medium">تحميل التطبيق</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center pt-16" style="background: linear-gradient(135deg, #0F6E56 0%, #0a5240 50%, #065f46 100%);">
        <!-- Pattern Overlay -->
        <div class="absolute inset-0 opacity-30" style="background-image: url(&quot;data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E&quot;);"></div>
        <!-- Bottom Gradient Overlay -->
        <div class="absolute inset-0" style="background: linear-gradient(to bottom, transparent, transparent, rgba(0,0,0,0.2));"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-right">
                    <div class="inline-block px-4 py-2 bg-white/10 rounded-full mb-6 backdrop-blur-sm">
                        <span class="text-white/90 text-sm font-medium">✨ تطبيق إسلامي شامل</span>
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                        أركاني<br>
                        <span class="text-emerald-200">رفيقك الروحاني</span>
                    </h1>
                    
                    <p class="text-lg md:text-xl text-white/90 mb-8 leading-relaxed max-w-xl">
                        تطبيق أركاني يقدم لك الأذكار، الأحكام الشرعية، أوقات الصلاة، وإشعارات يومية لتذكيرك بالأعمال الصالحة في كل وقت
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="#download" class="px-8 py-4 bg-white text-[#0F6E56] rounded-full font-bold text-lg hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.707 10.708L16.293 9.294 13 12.586V3h-2v9.586l-3.293-3.292-1.414 1.414L12 16.414l5.707-5.706zM5 16v4h14v-4h2v4c0 1.103-.897 2-2 2H5c-1.103 0-2-.897-2-2v-4h2z"/>
                            </svg>
                            تحميل التطبيق
                        </a>
                        <a href="#features" class="px-8 py-4 glass-effect text-white rounded-full font-bold text-lg hover:bg-white/20 transition-all flex items-center justify-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            اكتشف المزيد
                        </a>
                    </div>
                    
                    <div class="mt-12 flex items-center gap-6 justify-center lg:justify-start">
                        <div class="flex -space-x-3 space-x-reverse">
                            <div class="w-10 h-10 rounded-full bg-emerald-400 flex items-center justify-center text-white text-sm font-bold border-2 border-white">أ</div>
                            <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center text-white text-sm font-bold border-2 border-white">ر</div>
                            <div class="w-10 h-10 rounded-full bg-emerald-600 flex items-center justify-center text-white text-sm font-bold border-2 border-white">ك</div>
                        </div>
                        <div class="text-white/80 text-sm">
                            <span class="font-bold text-white">+10,000</span> مستخدم نشط
                        </div>
                    </div>
                </div>
                
                <div class="relative hidden lg:block">
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
                    
                    <div class="relative floating">
                        <div class="w-80 h-96 mx-auto bg-gradient-to-br from-white to-gray-100 rounded-3xl shadow-2xl p-6 transform rotate-3 hover:rotate-0 transition-transform duration-500">
                            <div class="w-full h-full bg-gradient-to-br from-[#0F6E56] to-[#0a5240] rounded-2xl flex flex-col items-center justify-center text-white p-6">
                                <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mb-4">
                                    <span class="text-5xl">🕌</span>
                                </div>
                                <h3 class="text-2xl font-bold mb-2">أركاني</h3>
                                <p class="text-center text-white/80 text-sm">رفيقك في طاعة الله</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="absolute -bottom-4 -left-4 floating-delayed">
                        <div class="bg-white rounded-2xl shadow-xl p-4 flex items-center gap-3">
                            <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center text-2xl">
                                ☀️
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">أذكار الصباح</p>
                                <p class="text-sm text-gray-500">متاحة الآن</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="absolute -top-4 -right-4 floating">
                        <div class="bg-white rounded-2xl shadow-xl p-4 flex items-center gap-3">
                            <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center text-2xl">
                                🌙
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">أذكار المساء</p>
                                <p class="text-sm text-gray-500">لحمايتك</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 scroll-reveal">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">مميزات تطبيق أركاني</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">تطبيق شامل يوفر لك كل ما تحتاجه في حياتك اليومية لتعزيز علاقتك بالله</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card bg-gray-50 rounded-2xl p-8 scroll-reveal">
                    <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center text-3xl mb-6">
                        📿
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">الأذكار اليومية</h3>
                    <p class="text-gray-600 leading-relaxed">مجموعة شاملة من أذكار الصباح والمساء وأذكار بعد الصلاة والأذان مع ذكر المصدر</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card bg-gray-50 rounded-2xl p-8 scroll-reveal" style="transition-delay: 100ms;">
                    <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center text-3xl mb-6">
                        ⚖️
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">الأحكام الشرعية</h3>
                    <p class="text-gray-600 leading-relaxed">استفتاءات شرعية في مواضيع متنوعة مع الأدلة من القرآن والسنة النبوية</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card bg-gray-50 rounded-2xl p-8 scroll-reveal" style="transition-delay: 200ms;">
                    <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center text-3xl mb-6">
                        🔔
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">إشعارات يومية</h3>
                    <p class="text-gray-600 leading-relaxed">تذكيرات يومية بالأعمال الصالحة والنوافل والأدعية في أوقات مختلفة</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="feature-card bg-gray-50 rounded-2xl p-8 scroll-reveal" style="transition-delay: 300ms;">
                    <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center text-3xl mb-6">
                        🕐
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">أوقات الصلاة</h3>
                    <p class="text-gray-600 leading-relaxed">معرفة أوقات الصلاة بدقة حسب موقعك الجغرافي مع عدة طرق حساب</p>
                </div>
                
                <!-- Feature 5 -->
                <div class="feature-card bg-gray-50 rounded-2xl p-8 scroll-reveal" style="transition-delay: 400ms;">
                    <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center text-3xl mb-6">
                        🗺️
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">البحث عن المساجد</h3>
                    <p class="text-gray-600 leading-relaxed">ابحث عن أقرب المساجد إليك باستخدام خرائط Google Maps</p>
                </div>
                
                <!-- Feature 6 -->
                <div class="feature-card bg-gray-50 rounded-2xl p-8 scroll-reveal" style="transition-delay: 500ms;">
                    <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center text-3xl mb-6">
                        💬
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">رسائل تحفيزية</h3>
                    <p class="text-gray-600 leading-relaxed">رسائل أذكار وأدعية خاصة بأوقات الصلاة المختلفة لتحفيزك على الخير</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-24 bg-gradient-to-br from-gray-50 to-emerald-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="scroll-reveal">
                    <div class="relative">
                        <div class="absolute -top-4 -right-4 w-72 h-72 bg-[#0F6E56]/10 rounded-full blur-3xl"></div>
                        <div class="relative bg-white rounded-3xl shadow-2xl p-8">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-emerald-50 rounded-2xl p-6 text-center">
                                    <div class="text-4xl font-bold text-[#0F6E56] mb-2">50+</div>
                                    <div class="text-sm text-gray-600">ذكر وأذكار</div>
                                </div>
                                <div class="bg-emerald-50 rounded-2xl p-6 text-center">
                                    <div class="text-4xl font-bold text-[#0F6E56] mb-2">100+</div>
                                    <div class="text-sm text-gray-600">حكم شرعي</div>
                                </div>
                                <div class="bg-emerald-50 rounded-2xl p-6 text-center">
                                    <div class="text-4xl font-bold text-[#0F6E56] mb-2">4</div>
                                    <div class="text-sm text-gray-600">تصنيفات أذكار</div>
                                </div>
                                <div class="bg-emerald-50 rounded-2xl p-6 text-center">
                                    <div class="text-4xl font-bold text-[#0F6E56] mb-2">∞</div>
                                    <div class="text-sm text-gray-600">أجر مستمر</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="scroll-reveal">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">لماذا تطبيق أركاني؟</h2>
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="w-12 h-12 bg-[#0F6E56] rounded-full flex items-center justify-center text-white flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">محتوى موثوق</h3>
                                <p class="text-gray-600">جميع الأذكار والأحكام مأخوذة من مصادر موثوقة مع ذكر الدليل من القرآن والسنة</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-4">
                            <div class="w-12 h-12 bg-[#0F6E56] rounded-full flex items-center justify-center text-white flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">سهل الاستخدام</h3>
                                <p class="text-gray-600">واجهة بسيطة وسلسة تناسب جميع الفئات العمرية مع دعم كامل للغة العربية</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-4">
                            <div class="w-12 h-12 bg-[#0F6E56] rounded-full flex items-center justify-center text-white flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">سريع وخفيف</h3>
                                <p class="text-gray-600">يعمل بسلاسة على جميع الأجهزة حتى مع إنترنت ضعيف</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Download Section -->
    <section id="download" class="py-24 relative" style="background: linear-gradient(135deg, #0F6E56 0%, #0a5240 50%, #065f46 100%);">
        <!-- Pattern Overlay -->
        <div class="absolute inset-0 opacity-30" style="background-image: url(&quot;data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E&quot;);"></div>
        <!-- Bottom Gradient Overlay -->
        <div class="absolute inset-0" style="background: linear-gradient(to bottom, transparent, transparent, rgba(0,0,0,0.2));"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="scroll-reveal">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">حمل التطبيق الآن</h2>
                <p class="text-lg text-white/80 mb-12 max-w-2xl mx-auto">ابدأ رحلتك الروحانية مع أركاني وكن أقرب إلى الله في كل وقت ومكان</p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <!-- App Store Button -->
                    <a href="#" class="inline-flex items-center gap-3 px-8 py-4 bg-white rounded-xl hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl">
                        <svg class="w-8 h-8 text-gray-900" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                        </svg>
                        <div class="text-right">
                            <div class="text-xs text-gray-600">حمل من</div>
                            <div class="text-lg font-bold text-gray-900">App Store</div>
                        </div>
                    </a>
                    
                    <!-- Play Store Button -->
                    <a href="#" class="inline-flex items-center gap-3 px-8 py-4 bg-white rounded-xl hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.5,12.92 20.16,13.19L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z" style="fill:#4CAF50"/>
                            <path d="M3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15M6.05,2.66L14.54,11.15L16.81,8.88L6.05,2.66M6.05,21.34L16.81,15.12L14.54,12.85L6.05,21.34M20.16,10.81L17.89,9.5L15.39,12L17.89,14.5L20.16,13.19C20.5,12.92 20.75,12.5 20.75,12C20.75,11.5 20.5,11.08 20.16,10.81Z" style="fill:#FFC107"/>
                        </svg>
                        <div class="text-right">
                            <div class="text-xs text-gray-600">حمل من</div>
                            <div class="text-lg font-bold text-gray-900">Google Play</div>
                        </div>
                    </a>
                </div>
                
                <div class="mt-12 text-white/60 text-sm">
                    <p>سيتوفر التطبيق قريباً على متاجر التطبيقات</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8 mb-8">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-[#0F6E56] rounded-full flex items-center justify-center">
                            <span class="text-xl font-bold">أ</span>
                        </div>
                        <span class="text-xl font-bold">أركاني</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        تطبيق إسلامي شامل يهدف إلى تعزيز علاقتك بالله من خلال الأذكار والأحكام الشرعية والتذكير اليومي بالأعمال الصالحة.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">روابط سريعة</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#features" class="hover:text-white transition-colors">المميزات</a></li>
                        <li><a href="#about" class="hover:text-white transition-colors">عن التطبيق</a></li>
                        <li><a href="#download" class="hover:text-white transition-colors">تحميل التطبيق</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">لوحة التحكم</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">تواصل معنا</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            support@arkani.app
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                            </svg>
                            موقع الويب
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-sm">© 2025 أركاني. جميع الحقوق محفوظة.</p>
                <p class="text-gray-600 text-sm">صنع بـ ❤️ لله</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
        
        // Scroll Reveal Animation
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };
        
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.scroll-reveal').forEach(el => {
            observer.observe(el);
        });
        
        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    mobileMenu.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>
