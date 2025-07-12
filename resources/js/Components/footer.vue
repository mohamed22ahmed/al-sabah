<script>
import NavLink from "@/Components/NavLink.vue";

export default {
    name: 'Footer',
    components: {NavLink},
    data() {
        return {
            mobileMenuOpen: false,
        };
    },
    methods: {
        toggleMobileMenu() {
            this.mobileMenuOpen = !this.mobileMenuOpen;
        },
        closeMobileMenu() {
            this.mobileMenuOpen = false;
        }
    }
};
</script>

<template>
  <footer class="bg-gradient-to-b from-white to-[#a31f10] pt-12 pb-2 mt-12 w-full">
    <div class="max-w-5xl mx-auto px-4">

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-right">
        <div>
          <h3 class="font-bold text-xl mb-2">موقعنا</h3>
          <a href="https://maps.app.goo.gl/oRPohHpePNMGMQd48" target="_blank" class="text-blue-700 text-sm hover:underline break-all">https://maps.app.goo.gl/oRPohHpePNMGMQd48</a>
          <h3 class="font-bold text-xl mt-4 mb-2">العنوان</h3>
          <div class="text-sm text-gray-800">شارع التحلية<br>السعودية، جدة، حي العزيزية، شارع التحلية <span class="inline-block align-middle"><svg xmlns='http://www.w3.org/2000/svg' class='inline w-4 h-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z'/><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 11a3 3 0 11-6 0 3 3 0 016 0z'/></svg></span></div>
          <h3 class="font-bold text-xl mt-4 mb-2">وسائل التواصل</h3>
          <div class="flex justify-center md:justify-start gap-4 mt-2">
            <a href="mailto:info@horeka.com" class="text-gray-700 hover:text-cyan-600"><svg xmlns='http://www.w3.org/2000/svg' class='w-6 h-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M16 12H8m8 0a4 4 0 11-8 0 4 4 0 018 0zm0 0v1a4 4 0 01-8 0v-1m8 0H8'/></svg></a>
            <a href="tel:0532872123" class="text-gray-700 hover:text-cyan-600"><svg xmlns='http://www.w3.org/2000/svg' class='w-6 h-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm0 10a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2zm10-10a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zm0 10a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'/></svg></a>
          </div>
        </div>

        <div>
          <h3 class="font-bold text-xl mb-2 underline">لينكات سريعة</h3>
          <ul class="space-y-1 text-base">
            <li><a href="/about-us" class="hover:underline">من نحن</a></li>
            <li><a href="/privacy" class="hover:underline">سياسة الخصوصية</a></li>
            <li><a href="/terms" class="hover:underline">الشروط والأحكام</a></li>
            <li><a href="/terms" class="hover:underline">سياسة الشحن والتوصيل</a></li>
            <li><a href="/about-us" class="hover:underline">المدونة</a></li>
            <li><a href="/privacy" class="hover:underline">سياسة الاستبدال والاسترجاع</a></li>
          </ul>
        </div>

        <div>
          <h3 class="font-bold text-xl mb-2">من نحن</h3>
          <p class="text-sm text-gray-700 leading-relaxed">
            متجر Horeka متخصصون في توفير أفضل معدات القهوة والمقاهي بالإضافة إلى المنتجات القوية والمعدات الخاصة بالفنادق والمطاعم، بالإضافة إلى المنتجات المخصصة لمنتجات الزين المميزة لعملائنا في جميع أنحاء المملكة العربية السعودية والخليج العربي.
          </p>
          <p class="mt-4 text-sm text-gray-800">لعملائنا في جميع أنحاء المملكة العربية السعودية</p>
        </div>
      </div>

      <div class="flex flex-wrap justify-center items-center gap-4 mt-8 mb-2">
        <img src="https://cdn.salla.sa/payments/tap.png" alt="tabby" class="h-7" />
        <img src="https://cdn.salla.sa/payments/visa.png" alt="visa" class="h-7" />
        <img src="https://cdn.salla.sa/payments/applepay.png" alt="applepay" class="h-7" />
        <img src="https://cdn.salla.sa/payments/mada.png" alt="mada" class="h-7" />
        <img src="https://cdn.salla.sa/payments/mastercard.png" alt="mastercard" class="h-7" />
        <img src="https://cdn.salla.sa/payments/stcpay.png" alt="stcpay" class="h-7" />
      </div>
      <div class="flex flex-wrap justify-center items-center gap-4 mt-2 mb-2">
        <img src="https://cdn.salla.sa/licenses/cr.png" alt="cr" class="h-7" />
        <img src="https://cdn.salla.sa/licenses/ma.png" alt="ma" class="h-7" />
        <span class="text-xs text-black-600">4030145436 :الرقم الضريبي</span>
      </div>
      <hr class="my-2 border-t border-gray-300" />
      <div class="text-center text-xs text-white pb-2">جميع الحقوق محفوظة ومملوكة لمؤسسة الصباح التجارية</div>
    </div>
  </footer>
</template>

<style scoped>
.footer {
    width: 100%;
    background: #a31f10;
    border-top: 1px solid #222;
    z-index: 100;
    position: relative;
    bottom: 0;
    left: 0;
    direction: rtl;
}
.footer__content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px 8px 24px;
    background: #a31f10;
    position: relative;
}
.footer__logo {
    height: 48px;
}
.footer__links {
    display: flex;
    gap: 40px;
    background: none;
    font-size: 18px;
    font-family: inherit;
    padding: 0;
}
.footer__links a {
    color:#fff;
    text-decoration: none;
    font-weight: 600;
}
.footer__burger {
    display: none;
    background: none;
    border: none;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 3px;
    width: 36px;
    height: 36px;
    cursor: pointer;
    margin-right: 10px;
}
.footer__burger-bar {
    width: 24px;
    height: 3px;
    background: #222;
    border-radius: 2px;
    display: block;
    transition: all 0.2s;
}
.footer__links--mobile {
    flex-direction: column;
    align-items: flex-end;
    position: absolute;
    bottom: 100%;
    right: 0;
    left: 0;
    background: #a31f10;
    z-index: 200;
    padding: 20px 16px 16px 16px;
    gap: 24px;
    border-top: 1px solid #bbb;
    box-shadow: 0 -8px 16px 0 rgba(0,0,0,0.04);
}
.footer__close {
    background: none;
    border: none;
    font-size: 32px;
    color: #222;
    position: absolute;
    top: 8px;
    left: 12px;
    cursor: pointer;
    z-index: 10;
}
.footer__bottom {
    text-align: center;
    font-size: 14px;
    color: #fff;
    padding: 12px 0 8px 0;
    background: #a31f10;
    border-top: 1px solid #222;
}

@media (max-width: 900px) {
    .footer__links--desktop {
        display: none !important;
    }
    .footer__links--mobile {
        display: flex !important;
    }
    .footer__logo {
        height: 36px;
    }
    .footer__burger {
        display: flex;
    }
}
@media (min-width: 901px) {
    .footer__links--mobile {
        display: none !important;
    }
    .footer__links--desktop {
        display: flex !important;
    }
    .footer__burger {
        display: none !important;
    }
}

/* Slide up transition for mobile menu */
.slide-up-enter-active, .slide-up-leave-active {
    transition: all 0.3s cubic-bezier(.25,.8,.25,1);
}
.slide-up-enter-from, .slide-up-leave-to {
    opacity: 0;
    transform: translateY(16px);
}
.slide-up-enter-to, .slide-up-leave-from {
    opacity: 1;
    transform: translateY(0);
}
</style>
