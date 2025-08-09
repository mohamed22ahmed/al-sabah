<script>
export default {
    props: {
        logo: String,
    },
};
</script>

<template>
    <img :src="logo" alt="al-sabah" loading="lazy" decoding="async" class="navbar__logo max-h-12 h-auto w-auto" />
</template>
