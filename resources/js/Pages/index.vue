<script>
import { Head, Link } from '@inertiajs/vue3';
import navbar from "@/Components/navbar.vue";
import Footer from "@/Components/footer.vue";
import Welcome from "./Welcome.vue";
import { useLogoStore } from '@/Stores/logoStore';

export default {
    components: {
        Head,
        Link,
        navbar,
        Footer,
        Welcome
    },
    props: {
        links: Array,
        cartImage: String,
        laravelVersion: String,
        phpVersion: String
    },
    setup() {
        const logoStore = useLogoStore();
        return {
            logoStore
        };
    },
    data() {
        return {
            currentSlide: 0,
            slides: [
                {
                    bg: 'bg-gradient-to-br from-cyan-500 to-teal-400',
                    headline: 'معنا ما تشيل هم!',
                    subheadline: 'قسّم سعر المنتج العالي على دفعات مريحة تناسبك',
                    cta: 'تسوق الآن',
                    phone: '0532872123',
                    img: '/images/elsabah.png',
                },
                {
                    bg: 'bg-gradient-to-br from-blue-500 to-indigo-400',
                    headline: 'عروض حصرية على كل المنتجات',
                    subheadline: 'استفد من التخفيضات الكبيرة لفترة محدودة',
                    cta: 'اكتشف العروض',
                    phone: '0532872123',
                    img: 'https://via.placeholder.com/350x300?text=Offer',
                },
                {
                    bg: 'bg-gradient-to-br from-green-500 to-emerald-400',
                    headline: 'خدمة عملاء 24/7',
                    subheadline: 'دعم فوري لجميع استفساراتك وطلباتك',
                    cta: 'تواصل معنا',
                    phone: '0532872123',
                    img: 'https://via.placeholder.com/350x300?text=Support',
                },
            ],
            slideInterval: null,
        };
    },
    mounted() {
        this.startAutoSlide();
    },
    beforeUnmount() {
        clearInterval(this.slideInterval);
    },
    methods: {
        goToSlide(idx) {
            this.currentSlide = idx;
        },
        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.slides.length;
        },
        startAutoSlide() {
            this.slideInterval = setInterval(() => {
                this.nextSlide();
            }, 6000);
        },
    },
};
</script>

<template>
    <Head title="Index" />
    <Welcome :links="links">
        <div class="bg-gray-50 min-h-screen flex flex-col">
            <div class="w-[90%] mx-auto">
                <section class="relative w-full flex flex-col items-center justify-center overflow-hidden pt-5" style="min-height: 420px;">
                    <div
                        v-for="(slide, idx) in slides"
                        :key="idx"
                        v-show="currentSlide === idx"
                        :class="[slide.bg, 'transition-all duration-700 ease-in-out w-full flex flex-col md:flex-row items-center justify-between px-8 md:px-20 py-12 md:py-0']"
                        style="min-height: 420px;"
                    >
                        <!-- Left: Text -->
                        <div class="flex-1 flex flex-col justify-center items-start md:items-start rtl:text-right z-10">
                            <img v-if="idx === 0" :src="logoStore.getLogo" alt="horeka logo" class="mb-6 w-40 md:w-48" />
                            <h1 class="text-3xl md:text-5xl font-extrabold mb-4 leading-tight text-white drop-shadow">{{ slide.headline }}</h1>
                            <p class="text-lg md:text-2xl mb-6 text-white/90">{{ slide.subheadline }}</p>
                            <div class="flex items-center gap-4 mb-6">
                                <a href="#" class="flex items-center gap-2 bg-white text-cyan-600 font-bold py-2 px-6 rounded-full shadow hover:bg-gray-100 transition">
                                    <span class="text-xl">←</span>
                                    {{ slide.cta }}
                                </a>
                            </div>
                            <div class="flex items-center gap-2 text-white text-lg">
                                <span class="inline-flex items-center justify-center w-8 h-8 bg-white/20 rounded-full border border-white mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 10c0-3.866-3.582-7-8-7S2 6.134 2 10c0 3.866 3.582 7 8 7s8-3.134 8-7z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 17v2m0 0h-2m2 0h2" /></svg>
                                </span>
                                <span>24</span>
                                <span>{{ slide.phone }}</span>
                            </div>
                        </div>
                        <!-- Right: Image -->
                        <div class="flex-1 flex justify-center items-center relative z-0">
                            <img :src="slide.img" alt="banner image" class="w-72 md:w-96 drop-shadow-xl" />
                            <!-- Money flying effect for first slide -->
                            <template v-if="idx === 0">
                                <img src="#" class="absolute top-8 right-0 w-20 rotate-12 opacity-80 hidden md:block" style="z-index:2;" />
                                <img src="#" class="absolute bottom-8 left-0 w-16 -rotate-12 opacity-70 hidden md:block" style="z-index:2;" />
                            </template>
                        </div>
                    </div>
                    <!-- Navigation Dots -->
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-20">
                        <button
                            v-for="(slide, idx) in slides"
                            :key="'dot-' + idx"
                            @click="goToSlide(idx)"
                            :class="['w-3 h-3 rounded-full', currentSlide === idx ? 'bg-cyan-600' : 'bg-white border border-cyan-600']"
                            aria-label="انتقل إلى الشريحة"
                        ></button>
                    </div>
                </section>
                <!-- Features/Stats Section -->
                <section id="features" class="py-16 bg-white">
                    <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                        <div>
                            <div class="text-5xl font-bold text-red-600 mb-2">1.2m+</div>
                            <div class="text-lg font-semibold">متابع</div>
                        </div>
                        <div>
                            <div class="text-5xl font-bold text-red-600 mb-2">88m+</div>
                            <div class="text-lg font-semibold">مشاهدة</div>
                        </div>
                        <div>
                            <div class="text-5xl font-bold text-red-600 mb-2">1.7k+</div>
                            <div class="text-lg font-semibold">فيديو</div>
                        </div>
                    </div>
                </section>
                <!-- Equipment/Specs Section -->
                <section class="py-16 bg-gray-100">
                    <div class="max-w-5xl mx-auto px-4">
                        <h2 class="text-3xl font-bold text-center mb-10">المعدات والتقنيات</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="bg-white rounded-lg shadow p-6">
                                <h3 class="text-xl font-semibold mb-4">جهاز البث الرئيسي</h3>
                                <ul class="text-gray-700 space-y-2 rtl:text-right">
                                    <li><span class="font-bold">المعالج:</span> Intel Core i9-12900K</li>
                                    <li><span class="font-bold">كرت الشاشة:</span> Geforce RTX 3090 24GB</li>
                                    <li><span class="font-bold">الذاكرة:</span> 64GB DDR4-4400</li>
                                    <li><span class="font-bold">التخزين:</span> 1000GB SSD</li>
                                </ul>
                            </div>
                            <div class="bg-white rounded-lg shadow p-6">
                                <h3 class="text-xl font-semibold mb-4">معدات اللعب</h3>
                                <ul class="text-gray-700 space-y-2 rtl:text-right">
                                    <li><span class="font-bold">اللوحة الأم:</span> ASUS X570-PRO</li>
                                    <li><span class="font-bold">الشاشة:</span> Gigabyte AORUS FI32Q</li>
                                    <li><span class="font-bold">لوحة المفاتيح:</span> HyperX Alloy Origins</li>
                                    <li><span class="font-bold">الفأرة:</span> Logitech G Pro X</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Countdown/Promo Section -->
                <section class="py-16 bg-gradient-to-r from-red-600 to-pink-600 text-white">
                    <div class="max-w-3xl mx-auto px-4 text-center">
                        <h2 class="text-3xl font-bold mb-4">العد التنازلي للعرض القادم</h2>
                        <div class="flex justify-center gap-6 text-3xl font-mono mb-6">
                            <div class="flex flex-col items-center"><span class="font-bold">00</span><span class="text-sm">يوم</span></div>
                            <div class="flex flex-col items-center"><span class="font-bold">00</span><span class="text-sm">ساعة</span></div>
                            <div class="flex flex-col items-center"><span class="font-bold">00</span><span class="text-sm">دقيقة</span></div>
                            <div class="flex flex-col items-center"><span class="font-bold">00</span><span class="text-sm">ثانية</span></div>
                        </div>
                        <p class="mb-4">العرض يبدأ في 1 فبراير الساعة 6 مساءً</p>
                        <a href="#" class="inline-block bg-white text-red-600 font-bold py-2 px-6 rounded-full shadow hover:bg-gray-100 transition">شاهد المزيد</a>
                    </div>
                </section>
                <!-- Latest Posts/News Section -->
                <section class="py-16 bg-white">
                    <div class="max-w-6xl mx-auto px-4">
                        <h2 class="text-3xl font-bold text-center mb-10">آخر الأخبار</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="bg-gray-50 rounded-lg shadow p-6 flex flex-col">
                                <img src="https://via.placeholder.com/400x200" alt="post1" class="rounded mb-4" />
                                <h3 class="text-xl font-semibold mb-2">أين تجد Xur في Destiny 2 هذا الأسبوع</h3>
                                <p class="text-gray-600 flex-1">دليل شامل حول أماكن ظهور Xur وأهم العروض لهذا الأسبوع في لعبة Destiny 2.</p>
                                <a href="#" class="mt-4 text-red-600 font-bold hover:underline">اقرأ المزيد</a>
                            </div>
                            <div class="bg-gray-50 rounded-lg shadow p-6 flex flex-col">
                                <img src="https://via.placeholder.com/400x200" alt="post2" class="rounded mb-4" />
                                <h3 class="text-xl font-semibold mb-2">The Invincible: لعبة خيال علمي مذهلة</h3>
                                <p class="text-gray-600 flex-1">استكشف عالم The Invincible المستوحى من روايات الخيال العلمي الكلاسيكية.</p>
                                <a href="#" class="mt-4 text-red-600 font-bold hover:underline">اقرأ المزيد</a>
                            </div>
                            <div class="bg-gray-50 rounded-lg shadow p-6 flex flex-col">
                                <img src="https://via.placeholder.com/400x200" alt="post3" class="rounded mb-4" />
                                <h3 class="text-xl font-semibold mb-2">Elden Ring: تحديات جديدة</h3>
                                <p class="text-gray-600 flex-1">تعرف على آخر التحديثات والتحديات في لعبة Elden Ring وكيفية التغلب عليها.</p>
                                <a href="#" class="mt-4 text-red-600 font-bold hover:underline">اقرأ المزيد</a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </Welcome>
</template>
