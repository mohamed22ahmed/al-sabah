<script>
import { Head } from "@inertiajs/vue3";
import { useCartStore } from "@/Stores/cart";
import Welcome from "./Welcome.vue";
import Modal from "@/Components/Modal.vue";
import Toast from '@/Components/Toast.vue';
import PaymentModal from '@/Components/PaymentModal.vue';
import L from 'leaflet';
import axios from "axios";

export default {
    components: {
        Head,
        Welcome,
        Modal,
        Toast,
        PaymentModal,
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
            selectedZone: '',
            deliveryCost: 0
          },
          orderError: '',
          toast: {
            show: false,
            message: '',
            type: 'success',
          },
          map: null,
          marker: null,
          zones: [],
        };
    },

    async mounted() {
        this.cartStore = useCartStore();
        await this.getZones();
        await this.cartStore.fetchCart();
    },

    computed: {
        orderSummary() {
      if (!this.cartStore) return {
        itemsCount: 0,
        totalItems: 0,
        subtotal: 0,
        deliveryCost: 0,
        total: 0
      };

      // Use calculated values as fallback to ensure accuracy
      const subtotal = this.cartStore.subtotal || this.cartStore.calculatedSubtotal || 0;
      const deliveryCost = this.orderForm.deliveryCost || 0;
      const total = subtotal + deliveryCost; // Total = Subtotal + Delivery Cost
      const itemsCount = this.cartStore.items?.length || 0; // Number of unique products
      const totalItems = this.cartStore.items?.reduce((total, item) => total + item.quantity, 0) || 0;

      return {
        itemsCount: itemsCount,
        totalItems: totalItems,
        subtotal: subtotal,
        deliveryCost: deliveryCost,
        total: total
      };
    }
    },

    methods: {
        async getZones() {
          axios.get('/admin/zones/get-zones')
              .then(response => {
                  this.zones = response.data.data;
                  console.log(this.zones);
              })
              .catch(error => {
                  console.error(error);
              });
      },

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
          this.$nextTick(() => {
            this.initMap();
          });
        },

        async submitOrder() {
          this.orderError = '';
          if (!this.orderForm.name || !this.orderForm.phone || !this.orderForm.address) {
            this.orderError = 'يرجى إدخال جميع البيانات المطلوبة';
            return;
          }
          if (!this.orderForm.lat || !this.orderForm.lng) {
            this.orderError = 'يرجى تحديد الموقع على الخريطة';
            return;
          }
          console.log(this.orderForm);
          const result = await this.cartStore.completeOrder({
            name: this.orderForm.name,
            phone: this.orderForm.phone,
            address: this.orderForm.address,
            zone: this.orderForm.selectedZone,
            delivery_cost: this.orderForm.deliveryCost
          });
          if (result.success) {
            // Clean up map before closing modal
            if (this.map) {
              this.map.remove();
              this.map = null;
            }
            if (this.marker) {
              this.marker = null;
            }

            this.showOrderModal = false;
            this.showOrderSuccess = true;
            this.orderForm = {
              name: '',
              phone: '',
              address: '',
              selectedZone: '',
              deliveryCost: 0,
              lat: null,
              lng: null
            };
            this.toast.message = result.message;
            this.toast.type = 'success';
            this.toast.show = true;

            // Show payment modal with order ID
            await this.cartStore.fetchPaymentConfig();
            this.cartStore.showPayment(result.orderId);
          } else {
            this.orderError = result.message;
          }
        },

        closeOrderModal() {
          this.showOrderModal = false;
          // Clean up map when modal is closed
          if (this.map) {
            this.map.remove();
            this.map = null;
          }
          if (this.marker) {
            this.marker = null;
          }
        },

        closeOrderSuccess() {
          this.showOrderSuccess = false;
        },

        closePaymentModal() {
          this.cartStore.hidePayment();
        },

        initMap() {
          try {
            // Initialize Leaflet map centered on Riyadh
            this.map = L.map('map').setView([24.7136, 46.6753], 12);

            // Add OpenStreetMap tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              attribution: '&copy; OpenStreetMap contributors'
            }).addTo(this.map);

            // Add click event to map
            this.map.on('click', (event) => {
              this.handleMapClick(event.latlng);
            });

            // Try to get user's location
            if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(
                (position) => {
                  const userLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                  };
                  this.map.setView(userLocation, 13);
                  this.handleMapClick(L.latLng(userLocation.lat, userLocation.lng));
                },
                (error) => {
                  console.log("Geolocation error:", error);
                }
              );
            }
          } catch (error) {
            console.error("Error loading Leaflet map:", error);
            this.orderError = "فشل في تحميل الخريطة. يرجى المحاولة مرة أخرى.";
          }
        },

        handleMapClick(latlng) {
          const lat = latlng.lat;
          const lng = latlng.lng;

          if (this.marker) {
            this.marker.setLatLng(latlng);
          } else {
            this.marker = L.marker(latlng, {
              draggable: true
            }).addTo(this.map);

            this.marker.on('dragend', (event) => {
              this.handleMapClick(event.target.getLatLng());
            });
          }

          this.orderForm.lat = lat;
          this.orderForm.lng = lng;

          this.reverseGeocode(lat, lng).then(() => {
            this.detectZone(this.orderForm.address);
          });
        },

        detectZone(address) {
        const regionPart = address
            .split(',')
            .find(part => part.includes('منطقة'));

        const regionName = regionPart
            ?.replace(/.*?ال?منطقة\s*/u, '')
            .trim();

        this.orderForm.selectedZone = regionName === 'الشرقية' ? 'المنطقة الشرقية' : regionName;
        this.getZonePrice(regionName);
    },

        async getZonePrice(regionName) {
          regionName = regionName.replace("منطقة ", "").trim();
          var deliveryPrice = 0;

          for(const zone of this.zones) {
              if(zone.name === regionName || zone.name_ar === regionName) {
                  deliveryPrice = zone.price;
                  break;
              }
          }

          var totalWeight = 0;
          for(const item of this.cartStore?.items) {
              totalWeight += item.product.weight * item.quantity;
          }

          if(totalWeight <= 25) {
              this.orderForm.deliveryCost = deliveryPrice;
          } else if(totalWeight > 25 && totalWeight <= 100) {
              this.orderForm.deliveryCost = deliveryPrice * 2;
          } else if(totalWeight > 100) {
              this.orderForm.deliveryCost = deliveryPrice * 3;
          }
        },

        async reverseGeocode(lat, lng) {
          try {
            const response = await fetch(
              `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=ar`
            );
            const data = await response.json();

            if (data.display_name) {
              this.orderForm.address = data.display_name;
            }
            return Promise.resolve();
          } catch (error) {
              console.error("Error in reverse geocoding:", error);
              return Promise.reject(error);
          }
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
            class="font-medium"
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
            class="inline-block bg-[#a31f10] hover:bg-[#8a1a0e] text-white px-6 py-3 rounded-lg transition-colors"
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
                    loading="lazy"
                    decoding="async"
                    class="w-20 h-20 object-contain rounded-lg bg-gray-50"
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
                  <span class="text-gray-600">رسوم التوصيل:</span>
                  <span class="font-semibold">{{ formatPrice(orderSummary.deliveryCost) }}</span>
                </div>
                <div class="border-t pt-3">
                  <div class="flex justify-between">
                    <span class="text-lg font-bold text-gray-900">المجموع الكلي:</span>
                    <span class="text-lg font-bold text-cyan-600">{{ formatPrice(orderSummary.total) }}</span>
                  </div>
                </div>
              </div>

              <button
                @click="checkout"
                :disabled="cartStore?.loading || cartStore?.isEmpty"
                class="w-full text-white py-3 px-4 rounded-lg font-semibold bg-[#a31f10] hover:bg-[#8a1a0e] disabled:opacity-50 disabled:cursor-not-allowed"
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
    <Modal :show="showOrderModal" @close="closeOrderModal" maxWidth="lg">
      <div class="p-6">
        <h2 class="text-xl font-bold mb-4 text-center">إتمام الطلب</h2>
        <div class="mb-4">
          <input v-model="orderForm.name" type="text" placeholder="الاسم" class="w-full mb-2 px-4 py-2 border rounded" />
          <input v-model="orderForm.phone" type="text" placeholder="رقم الجوال" class="w-full mb-2 px-4 py-2 border rounded" />
          <input v-model="orderForm.address" type="text" placeholder="العنوان" class="w-full mb-2 px-4 py-2 border rounded" />
        </div>

        <!-- Google Maps Section -->
        <div class="mb-4">
          <h3 class="font-semibold mb-2">تحديد الموقع على الخريطة</h3>
          <div id="map" class="w-full h-72 rounded-lg border border-gray-300 mb-2"></div>
          <p class="text-sm text-gray-600">انقر على الخريطة لتحديد موقع التوصيل</p>
        </div>

        <!-- Zone and Delivery Cost Display -->
        <div v-if="orderForm.selectedZone" class="mb-4 p-3 bg-gray-50 rounded-lg">
          <div class="flex justify-between items-center">
            <span class="font-semibold">المنطقة المحددة:</span>
            <span class="text-cyan-600">{{ orderForm.selectedZone }}</span>
          </div>
          <div class="flex justify-between items-center mt-1">
            <span class="font-semibold">رسوم التوصيل:</span>
            <span class="text-cyan-600 font-bold">{{ formatPrice(orderForm.deliveryCost) }}</span>
          </div>
        </div>

        <div v-if="orderError" class="text-red-600 mb-2 text-center">{{ orderError }}</div>
        <div class="flex justify-center gap-4 mt-4">
          <button @click="submitOrder" :disabled="cartStore?.loading" class="text-white px-6 py-2 rounded-lg font-bold bg-[#a31f10] hover:bg-[#8a1a0e] disabled:opacity-50">
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
        <button @click="closeOrderSuccess" class="text-white px-8 py-2 rounded-lg font-bold bg-[#a31f10] hover:bg-[#8a1a0e]">حسناً</button>
      </div>
    </Modal>

    <PaymentModal
      :show="cartStore?.showPaymentModal"
      :publishableKey="cartStore?.paymentData?.publishableKey"
      :amount="cartStore?.paymentData?.amount"
      :orderId="cartStore?.paymentData?.orderId"
      @close="closePaymentModal"
    />

    <Toast v-if="toast.show" :message="toast.message" :type="toast.type" @close="toast.show = false" />
  </Welcome>
</template>
