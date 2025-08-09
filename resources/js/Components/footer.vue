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
          <div class="my-2 w-full flex justify-center">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3683.502964479836!2d39.2084482!3d21.5632452!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x15c3cefd583e0613%3A0x9e4d626ed72029b5!2z2KfZhNmF2K3Yp9mB2YrYqSDYp9mE2KfZhNin2YXYp9mK2KkgQWxzYWJhaCBUcmFkaW5nIEVzdA!5e0!3m2!1sar!2ssa!4v1688570000000!5m2!1sar!2ssa"
              width="100%"
              height="180"
              style="border:0; border-radius: 8px;"
              allowfullscreen=""
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
              title="خريطة الصباح لمعدات المطاعم"
            ></iframe>
          </div>
          <h3 class="font-bold text-xl mt-4 mb-2">العنوان</h3>
          <div class="text-sm text-gray-800">
            شارع الأمير محمد بن عبدالعزيز، حى العزيزية، جدة
            <span class="inline-block align-middle">
              <svg xmlns='http://www.w3.org/2000/svg' class='inline w-4 h-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z'/><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 11a3 3 0 11-6 0 3 3 0 016 0z'/></svg>
            </span>
          </div>
          <h3 class="font-bold text-xl mt-4 mb-2">وسائل التواصل</h3>
          <div class="flex justify-center md:justify-start gap-4 mt-2" style="direction: rtl;">
            <!-- WhatsApp -->
            <a href="https://wa.me/966549190250" target="_blank" class="text-gray-700 hover:text-green-600 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
              </svg>
            </a>
            <!-- Email -->
            <a href="mailto:ejehani@alsabahest.com" class="text-gray-700 hover:text-blue-600 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
            </a>
            <!-- Phone -->
            <a href="tel:012-6444468" class="text-gray-700 hover:text-green-600 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
            </a>
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
            متجر الصباح متخصصون في توفير أفضل معدات القهوة والمقاهي بالإضافة إلى المنتجات القوية والمعدات الخاصة بالفنادق والمطاعم، بالإضافة إلى المنتجات المخصصة لمنتجات الزين المميزة لعملائنا في جميع أنحاء المملكة العربية السعودية والخليج العربي.
          </p>
          <p class="mt-4 text-sm text-gray-800">لعملائنا في جميع أنحاء المملكة العربية السعودية</p>
        </div>
      </div>

      <div class="flex flex-wrap justify-center items-center gap-4 mt-8 mb-2">
        <img src="/images/payments/visa.png" alt="visa" loading="lazy" decoding="async" class="h-7 w-auto" />
        <img src="/images/payments/applepay.png" alt="applepay" loading="lazy" decoding="async" class="h-7 w-auto" />
        <img src="/images/payments/mada.png" alt="mada" loading="lazy" decoding="async" class="h-7 w-auto" />
        <img src="/images/payments/mastercard.png" alt="mastercard" loading="lazy" decoding="async" class="h-7 w-auto" />
      </div>

      <hr class="my-4  border-t border-gray-300" />
      <div class="flex flex-wrap justify-center items-center gap-4 mt-2 mb-2">
        <span class="text-lg text-black-600"><span class="text-white">4030145436</span> :السجل التجارى</span>
        <span class="text-lg text-black-600"><span class="text-white">300229274300003</span> :الرقم الضريبي</span>
      </div>
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
