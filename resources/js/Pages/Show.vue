<script>
import { Head, Link } from '@inertiajs/vue3';
import navbar from "@/Components/navbar.vue";

export default {
    components: {
        Head,
        Link,
        navbar,
    },

    props: {
        links: Array,
        logo: String,
        slug: String,
        laravelVersion: String,
        phpVersion: String
    },
};
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-gray-50 ">
        <div class="relative flex min-h-screen flex-col selection:bg-[#FF2D20] selection:text-white">
            <div class="w-full px-6">
                <header>
                    <navbar class="navbar" :logo="logo" :links="links" />
                </header>

                <main>
                    {{ slug }}
                </main>
                <footer
                    class="py-16 text-center text-sm text-black dark:text-white/70"
                >
                    Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
                </footer>
            </div>
        </div>
    </div>
</template>
