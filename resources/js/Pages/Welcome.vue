<script>
import { Head } from '@inertiajs/vue3';
import navbar from "@/Components/navbar.vue";
import Footer from "@/Components/footer.vue";

export default {
    components: {
        Head,
        navbar,
        Footer
    },
    props: {
        links: Array
    },
};
</script>

<template>
    <Head />
    <div class="bg-gray-50 min-h-screen flex flex-col">
        <header>
            <navbar class="navbar" :links="links"/>
        </header>
        <div class="flex-1 w-[80%] mx-auto">
            <slot />
        </div>
        <footer>
            <Footer />
        </footer>
    </div>
</template>
