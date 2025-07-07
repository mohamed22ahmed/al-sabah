<script>
export default {
    props: {
        logo: String,
    },
};
</script>

<template>
    <img :src="logo" alt="al-sabah" class="navbar__logo" />
</template>
