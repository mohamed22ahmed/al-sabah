<script>
import { Head, Link } from '@inertiajs/vue3';
import navbar from "@/Components/navbar.vue";
import Footer from "@/Components/footer.vue";
import Welcome from "./Welcome.vue";
import BannerSlideshow from "@/Components/BannerSlideshow.vue";
import BestOffers from "@/Components/BestOffers.vue";
import CategoryGrid from "@/Components/CategoryGrid.vue";
import { useLogoStore } from '@/Stores/logoStore';

export default {
    components: {
        Head,
        Link,
        navbar,
        Footer,
        Welcome,
        BannerSlideshow,
        BestOffers,
        CategoryGrid
    },
    props: {
        links: Array,
        cartImage: String,
        bestOffers: Array
    },
    setup() {
        const logoStore = useLogoStore();
        return {
            logoStore
        };
    },
};
</script>

<template>
    <Head title="الصفحة الرئيسية" />
    <Welcome :links="links">
        <div class="bg-gray-50 min-h-screen flex flex-col">
            <div class="w-[90%] mx-auto">
                <section class="py-8">
                    <BannerSlideshow />
                </section>

                <BestOffers :bestOffers="bestOffers" />

                <CategoryGrid/>

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
