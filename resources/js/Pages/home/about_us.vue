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
    <Head title="About Us" />
    <Welcome :links="links">
        <h1 class="text-3xl font-bold text-center mb-8 mt-5 text-[#a31f10]">من نحن</h1>
        <section class="bg-white rounded-lg shadow p-6 mb-8 text-right" style="direction: rtl;">
        <h2 class="text-2xl font-semibold mb-4 text-[#a31f10]">عن متجر الصباح</h2>
        <p class="text-gray-700 leading-relaxed text-lg">
            متجر الصباح متخصصون في توفير أفضل معدات القهوة والمقاهي بالإضافة إلى المنتجات القوية والمعدات الخاصة بالفنادق والمطاعم، بالإضافة إلى المنتجات المخصصة لمنتجات الزين المميزة لعملائنا في جميع أنحاء المملكة العربية السعودية والخليج العربي.
        </p>
        <p class="mt-4 text-gray-800 text-base">
            نحن نؤمن بأهمية الجودة والابتكار في كل ما نقدمه، ونسعى دائمًا لتلبية احتياجات عملائنا وتقديم أفضل الحلول والخدمات.
        </p>
        </section>

        <section class="bg-gray-50 rounded-lg shadow p-6 mb-8 text-right" style="direction: rtl;">
        <h2 class="text-xl font-semibold mb-4 text-[#a31f10]">رؤيتنا ورسالتنا وقيمنا</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="font-bold text-lg mb-2">رؤيتنا</h3>
                <p class="text-gray-700 text-base">أن نكون الخيار الأول في مجال معدات القهوة والمقاهي والفنادق في المملكة والخليج</p>
            </div>
            <div>
                <h3 class="font-bold text-lg mb-2">رسالتنا</h3>
                <p class="text-gray-700 text-base">تقديم منتجات عالية الجودة وخدمة عملاء متميزة تلبي تطلعات عملائنا وتفوق توقعاتهم</p>
            </div>
            <div>
            <h3 class="font-bold text-lg mb-2">قيمنا</h3>
            <ul class="list-disc pr-5 text-gray-700 text-base">
                <li>الجودة</li>
                <li>الابتكار</li>
                <li>الشفافية</li>
                <li>الالتزام</li>
                <li>رضا العملاء</li>
            </ul>
            </div>
        </div>
        </section>

        <section class="bg-white rounded-lg shadow p-6 text-right" style="direction: rtl;">
        <h2 class="text-xl font-semibold mb-4 text-[#a31f10]">معلومات التواصل</h2>
        <div class="flex flex-col md:flex-row md:justify-between gap-6">
            <div class="text-right">
                <span class="font-bold">البريد الإلكتروني: </span>
                <a href="mailto:info@horeka.com" class="text-blue-700 hover:underline ml-2">info@horeka.com</a>
            </div>
            <div class="text-right">
            <span class="font-bold">رقم الهاتف: </span>
            <a href="tel:0532872123" class="text-blue-700 hover:underline ml-2">0532872123</a>
            </div>
            <div class="text-right">
            <span class="font-bold">العنوان: </span>
            <span class="ml-2">السعودية، جدة، حي العزيزية، شارع التحلية</span>
            </div>
        </div>
        </section>
    </Welcome>
</template>
