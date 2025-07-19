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
        CategoryProductGrid
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
            </div>
        </div>
    </Welcome>
</template>
