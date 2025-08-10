<script>
import { Head } from '@inertiajs/vue3';
import navbar from "@/Components/navbar.vue";
import Footer from "@/Components/footer.vue";
import Toast from '@/Components/Toast.vue';
import WhatsAppButton from '@/Components/WhatsAppButton.vue';
import { useNotificationStore } from '@/Stores/notificationStore';

export default {
    components: {
        Head,
        navbar,
        Footer,
        Toast,
        WhatsAppButton
    },
    props: {
        links: Array
    },
    setup() {
        const notificationStore = useNotificationStore();
        return { notificationStore };
    },
};
</script>

<template>
    <Head />
    <div class="bg-gray-50 min-h-screen flex flex-col">
        <header>
            <navbar class="navbar" :links="links"/>
        </header>
        <!-- Banner will be rendered here for full-width display -->
        <div class="banner-full-width">
            <slot name="banner" />
        </div>
        <div class="flex-1 w-[80%] mx-auto">
            <slot />
        </div>
        <footer>
            <Footer />
        </footer>
        <Toast v-if="notificationStore.show" :message="notificationStore.message" :type="notificationStore.type" :duration="notificationStore.duration" @close="notificationStore.close()" />
        <WhatsAppButton />
    </div>
</template>
