<script>
import { Head, Link } from '@inertiajs/vue3';
import navbar from "@/Components/navbar.vue";
import Footer from "@/Components/footer.vue";
import Welcome from "./Welcome.vue";
import BannerSlideshow from "@/Components/BannerSlideshow.vue";
import BestOffers from "@/Components/BestOffers.vue";
import CategoryGrid from "@/Components/CategoryGrid.vue";
import CategoryProductGrid from "@/Components/CategoryProductGrid.vue";
import { useLogoStore } from '@/Stores/logoStore';
import Markets from "@/Components/markets.vue";

export default {
    components: {
        Head,
        Link,
        navbar,
        Footer,
        Welcome,
        BannerSlideshow,
        BestOffers,
        CategoryGrid,
        CategoryProductGrid,
        Markets
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

                <CategoryProductGrid v-for="category in links.data.filter(cat => cat.url !== '/')" :key="category.id" :category="category" />

                <Markets />

                <section class="py-16 bg-white" style="direction: rtl;">
                    <div class="w-full">
                        <div class="text-center mb-12">
                            <h2 class="text-3xl font-bold text-gray-800 mb-4">شركاءنا</h2>
                            <hr class="w-24 h-1 bg-red-600 mx-auto mb-4">
                            <p class="text-gray-600 text-lg">نفخر بشراكتنا مع أفضل العلامات التجارية</p>
                        </div>

                        <!-- Partners Image -->
                        <div class="w-full h-[800px]">
                            <img
                                src="/images/partner.png"
                                alt="شركاءنا"
                                class="w-full h-full object-cover rounded-lg shadow-lg"
                            />
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </Welcome>
</template>
