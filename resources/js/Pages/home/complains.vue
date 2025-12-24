<script>
import { Head, Link } from '@inertiajs/vue3';
import navbar from "@/Components/navbar.vue";
import Footer from "@/Components/footer.vue";
import Welcome from "./../Welcome.vue"

export default {
    components: {
        Head,
        Link,
        navbar,
        Footer,
        Welcome
    },
    props: {
        links: Array
    }
};
</script>

<template>
  <Head title="الشكاوي والاقتراحات" />
    <Welcome :links="links">
    <div class="max-w-4xl mx-auto py-12 px-4" style="direction: rtl;">
      <h1 class="text-3xl font-bold text-center mt-5 mb-10 text-[#a31f10]">الشكاوي والاقتراحات</h1>
        <section class="bg-white rounded-lg shadow p-6 mb-8" style="direction:rtl">
        <p class="mb-4 text-lg font-bold" style="font-size: 30px">لإرسال الشكاوى أو الاقتراحات</p>
        <br><p class="mb-4 text-lg font-bold" style="font-size: 26px">ارسال بريد إلكتروني على البريد الإلكتروني:  <a href="mailto:sales@alsabhest.com" class="text-[#a31f10] font-bold">sales@alsabhest.com </a></p>
        <br><p class="mb-4 text-lg font-bold" style="font-size: 26px">أو ارسال رسالة على الواتساب: <a href="https://wa.me/966549190250" class="text-[#a31f10] font-bold">+966549190250</a></p>
        </section>
    </div>
    </Welcome>
</template>


