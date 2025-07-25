<script>
import { Head } from "@inertiajs/vue3";
import { useCartStore } from "@/Stores/cart";
import Welcome from "./Welcome.vue";
import Modal from "@/Components/Modal.vue";
import Toast from '@/Components/Toast.vue';

export default {
  components: {
    Head,
    Welcome,
    Modal,
    Toast,
  },
  props: {
    links: Array,
  },
  data() {
    return {
      cartStore: null,
      showClearModal: false,
      showOrderModal: false,
      showOrderSuccess: false,
      orderForm: {
        name: '',
        phone: '',
        address: '',
      },
      orderError: '',
      toast: {
        show: false,
        message: '',
        type: 'success',
      },
    };
  },
  async mounted() {
    this.cartStore = useCartStore();
    await this.cartStore.fetchCart();
  },

  computed: {
    orderSummary() {
      if (!this.cartStore) return {
        itemsCount: 0,
        totalItems: 0,
        subtotal: 0,
        tax: 0,
        total: 0
      };

      // Use calculated values as fallback to ensure accuracy
      const subtotal = this.cartStore.subtotal || this.cartStore.calculatedSubtotal || 0;
      const tax = subtotal * 0.15; // 15% tax
      const total = subtotal + tax; // Total = Subtotal + Tax (calculated locally only)
      const itemsCount = this.cartStore.items?.length || 0; // Number of unique products
      const totalItems = this.cartStore.items?.reduce((total, item) => total + item.quantity, 0) || 0;

      return {
        itemsCount: itemsCount,
        totalItems: totalItems,
        subtotal: subtotal,
        tax: tax,
        total: total
      };
    }
  },

  methods: {
    formatPrice(price) {
      return new Intl.NumberFormat("ar-SA", {
        style: "currency",
        currency: "SAR",
      }).format(price);
    },
    async updateQuantity(itemId, quantity) {
      const result = await this.cartStore.updateQuantity(itemId, quantity);
      if (result.success) {
        // Force refresh cart data to ensure order summary is updated
        await this.cartStore.fetchCart();
        this.toast.message = result.message;
        this.toast.type = 'success';
        this.toast.show = true;
      } else {
        this.toast.message = result.message;
        this.toast.type = 'error';
        this.toast.show = true;
      }
    },
    async removeItem(itemId) {
      if (confirm("هل أنت متأكد من حذف هذا المنتج من السلة؟")) {
        const result = await this.cartStore.removeFromCart(itemId);
        if (result.success) {
          this.toast.message = result.message;
          this.toast.type = 'success';
          this.toast.show = true;
        } else {
          this.toast.message = result.message;
          this.toast.type = 'error';
          this.toast.show = true;
        }
      }
    },
    async clearCart() {
      this.showClearModal = true;
    },
    async confirmClearCart() {
      const result = await this.cartStore.clearCart();
      this.showClearModal = false;
      if (result.success) {
        this.toast.message = result.message;
        this.toast.type = 'success';
        this.toast.show = true;
      } else {
        this.toast.message = result.message;
        this.toast.type = 'error';
        this.toast.show = true;
      }
    },
    cancelClearCart() {
      this.showClearModal = false;
    },
    checkout() {
      this.orderError = '';
      this.showOrderModal = true;
    },
    async submitOrder() {
      this.orderError = '';
      if (!this.orderForm.name || !this.orderForm.phone || !this.orderForm.address) {
        this.orderError = 'يرجى إدخال جميع البيانات المطلوبة';
        return;
      }
      const result = await this.cartStore.completeOrder({
        name: this.orderForm.name,
        phone: this.orderForm.phone,
        address: this.orderForm.address,
      });
      if (result.success) {
        this.showOrderModal = false;
        this.showOrderSuccess = true;
        this.orderForm = { name: '', phone: '', address: '' };
      } else {
        this.orderError = result.message;
      }
    },
    closeOrderModal() {
      this.showOrderModal = false;
    },
    closeOrderSuccess() {
      this.showOrderSuccess = false;
    },
  },
};
</script>

<template>
  <Head title="سلة التسوق" />
  <Welcome :links="links">
    <div class="bg-gray-50 min-h-screen flex flex-col">
      <div class="w-[90%] mx-auto py-8" style="direction: rtl">
        <div class="flex items-center justify-between mb-8">
          <h1 class="text-3xl font-bold text-gray-900">سلة التسوق</h1>
          <button
            v-if="!cartStore?.isEmpty"
            @click="clearCart"
            class="text-red-600 hover:text-red-700 font-medium"
          >
            تفريغ السلة
          </button>
        </div>

        <div v-if="cartStore?.loading" class="text-center py-12">
          <div
            class="animate-spin rounded-full h-12 w-12 border-b-2 border-cyan-600 mx-auto"
          ></div>
          <p class="mt-4 text-gray-600">جاري تحميل السلة...</p>
        </div>

        <div v-else-if="cartStore?.isEmpty" class="text-center py-12">
          <div
            class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4"
          >
            <svg
              class="w-12 h-12 text-gray-400"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"
              />
            </svg>
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">السلة فارغة</h3>
          <p class="text-gray-600 mb-6">لم تقم بإضافة أي منتجات إلى السلة بعد</p>
          <a
            href="/menu"
            class="inline-block bg-cyan-600 text-white px-6 py-3 rounded-lg hover:bg-cyan-700 transition-colors"
          >
            تصفح المنتجات
          </a>
        </div>

        <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm">
              <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">
                  المنتجات ({{ cartStore?.itemsCount }})
                </h2>
              </div>
              <div class="divide-y divide-gray-200">
                <div
                  v-for="item in cartStore?.items"
                  :key="item.id"
                  class="p-6 flex items-center gap-4"
                >
                  <img
                    :src="item.product?.image"
                    :alt="item.product?.name"
                    class="w-20 h-20 object-cover rounded-lg"
                  />

                  <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 mb-1">
                      {{ item.product?.name }}
                    </h3>
                    <p class="text-sm text-gray-600 mb-2">
                      {{ item.product?.description?.length > 50 ? item.product.description.substring(0, 50) + '...' : item.product?.description }}
                    </p>
                    <p class="text-cyan-600 font-bold">{{ formatPrice(item.price) }}</p>
                    <p v-if="item.product?.quantity === 0" class="text-red-500 text-xs font-semibold mt-1">
                      نفذت الكمية
                    </p>
                  </div>

                  <div class="flex items-center gap-2">
                    <button
                      @click="updateQuantity(item.id, item.quantity - 1)"
                      :disabled="item.quantity <= 1 || cartStore?.loading"
                      class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 disabled:opacity-50"
                    >
                      <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M20 12H4"
                        />
                      </svg>
                    </button>
                    <span class="w-12 text-center font-semibold">{{item.quantity}}</span>
                    <button
                      @click="updateQuantity(item.id, item.quantity + 1)"
                      :disabled="
                        item.quantity >= item.product?.quantity || cartStore?.loading || item.product?.quantity === 0
                      "
                      class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 disabled:opacity-50"
                    >
                      <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 4v16m8-8H4"
                        />
                      </svg>
                    </button>
                  </div>

                  <div class="text-right">
                    <p class="font-bold text-gray-900">
                      {{ formatPrice(item.subtotal) }}
                    </p>
                  </div>

                  <button
                    @click="removeItem(item.id)"
                    :disabled="cartStore?.loading"
                    class="text-red-500 hover:text-red-700 p-2"
                  >
                    <svg
                      class="w-5 h-5"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                      />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Order Summary -->
          <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">تفاصيل الطلب</h2>

              <div class="space-y-3 mb-6">
                <div class="flex justify-between">
                  <span class="text-gray-600">عدد المنتجات:</span>
                  <span class="font-semibold">{{ orderSummary.itemsCount }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">إجمالي القطع:</span>
                  <span class="font-semibold">{{ orderSummary.totalItems }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">المجموع الفرعي:</span>
                  <span class="font-semibold">{{ formatPrice(orderSummary.subtotal) }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">الضريبة (15%):</span>
                  <span class="font-semibold">{{ formatPrice(orderSummary.tax) }}</span>
                </div>
                <div class="border-t pt-3">
                  <div class="flex justify-between">
                    <span class="text-lg font-bold text-gray-900">المجموع الكلي:</span>
                    <span class="text-lg font-bold text-cyan-600">{{
                      formatPrice(orderSummary.total)
                    }}</span>
                  </div>
                </div>
              </div>

              <button
                @click="checkout"
                :disabled="cartStore?.loading || cartStore?.isEmpty"
                class="w-full bg-cyan-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-cyan-700 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                إتمام الطلب
              </button>

              <div class="mt-4 text-center">
                <a href="/" class="text-cyan-600 hover:text-cyan-700 font-medium">
                  ← العودة للتسوق
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <Modal :show="showClearModal" @close="cancelClearCart" maxWidth="sm">
      <div class="p-6 text-center">
        <h2 class="text-xl font-bold mb-4">تأكيد تفريغ السلة</h2>
        <p class="mb-6 text-gray-700">
          هل أنت متأكد من أنك تريد تفريغ السلة؟ لا يمكن التراجع عن هذا الإجراء.
        </p>
        <div class="flex justify-center gap-4">
          <button
            @click="confirmClearCart"
            class="bg-red-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-red-700"
          >
            تأكيد
          </button>
          <button
            @click="cancelClearCart"
            class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-bold hover:bg-gray-400"
          >
            إلغاء
          </button>
        </div>
      </div>
    </Modal>
    <Modal :show="showOrderModal" @close="closeOrderModal" maxWidth="sm">
      <div class="p-6 text-center">
        <h2 class="text-xl font-bold mb-4">إتمام الطلب</h2>
        <div class="mb-4">
          <input v-model="orderForm.name" type="text" placeholder="الاسم" class="w-full mb-2 px-4 py-2 border rounded" />
          <input v-model="orderForm.phone" type="text" placeholder="رقم الجوال" class="w-full mb-2 px-4 py-2 border rounded" />
          <input v-model="orderForm.address" type="text" placeholder="العنوان" class="w-full mb-2 px-4 py-2 border rounded" />
        </div>
        <div v-if="orderError" class="text-red-600 mb-2">{{ orderError }}</div>
        <div class="flex justify-center gap-4 mt-4">
          <button @click="submitOrder" :disabled="cartStore?.loading" class="bg-cyan-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-cyan-700 disabled:opacity-50">
            تأكيد الطلب
          </button>
          <button @click="closeOrderModal" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-bold hover:bg-gray-400">
            إلغاء
          </button>
        </div>
      </div>
    </Modal>
    <Modal :show="showOrderSuccess" @close="closeOrderSuccess" maxWidth="sm">
      <div class="p-8 text-center">
        <h2 class="text-2xl font-bold mb-4 text-cyan-700">تم إرسال الطلب بنجاح</h2>
        <p class="mb-6 text-lg">سيتم التواصل معك فى أقرب وقت</p>
        <button @click="closeOrderSuccess" class="bg-cyan-600 text-white px-8 py-2 rounded-lg font-bold hover:bg-cyan-700">حسناً</button>
      </div>
    </Modal>
    <Toast v-if="toast.show" :message="toast.message" :type="toast.type" @close="toast.show = false" />
  </Welcome>
</template>
