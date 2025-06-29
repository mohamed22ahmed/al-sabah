<script setup>
import { Link } from '@inertiajs/vue3';
defineProps({
    logo: String,
});
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0"
    >
        <div>
            <Link href="/">
                <img :src="logo" alt="al-sabah" class="navbar__logo" />
            </Link>
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
<style>
.navbar__logo {
    height: 90px;
}
</style>
