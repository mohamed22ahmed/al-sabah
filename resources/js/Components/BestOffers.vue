<script>
import { Link } from '@inertiajs/vue3';
import { useCartStore } from '@/Stores/cart';
import { useNotificationStore } from '@/Stores/notificationStore';

export default {
    components: {
        Link
    },

    data() {
        return {
            bestOffers: [],
            loading: true,
            error: null,
            cartStore: useCartStore(),
            notificationStore: useNotificationStore(),
            showQuantityModal: false,
            selectedProduct: null,
            selectedQuantity: 1
        };
    },

    mounted() {
        this.fetchBestOffers();
    },

    methods: {
        async fetchBestOffers() {
            try {
                this.loading = true;
                this.error = null;
                const response = await fetch('/api/best-offers/8');
                const data = await response.json();

                if (data.success) {
                    this.bestOffers = data.data.map(product => ({
                        ...product,
                        discount_amount: product.price - product.discount_price,
                        discount_percentage: Math.round(((product.price - product.discount_price) / product.price) * 100)
                    }));
                } else {
                    this.error = data.message || 'حدث خطأ في تحميل العروض';
                }
            } catch (error) {
                console.error('Error fetching best offers:', error);
                this.error = 'حدث خطأ في تحميل العروض';
            } finally {
                this.loading = false;
            }
        },

        formatPrice(price) {
            return new Intl.NumberFormat('ar-SA', {
                style: 'currency',
                currency: 'SAR'
            }).format(price);
        },
        openQuantityModal(product) {
            if (product.quantity <= 0) return;
            this.selectedProduct = product;
            this.selectedQuantity = 1;
            this.showQuantityModal = true;
        },
        closeQuantityModal() {
            this.showQuantityModal = false;
            this.selectedProduct = null;
            this.selectedQuantity = 1;
        },
        getDiscountPercentage(originalPrice, discountPrice) {
            if (!discountPrice) return 0;
            return Math.round(((originalPrice - discountPrice) / originalPrice) * 100);
        },

        async addToCart(product) {
            if (product.quantity <= 0) return;

            try {
                await this.cartStore.addToCart(product.id, this.selectedQuantity);
                this.closeQuantityModal();
                this.$emit('cart-updated');
            } catch (error) {
                console.error('Error adding to cart:', error);
            }
        },

        async confirmAddToCart() {
            if (!this.selectedProduct || this.selectedQuantity <= 0) return;

            try {
                await this.cartStore.addToCart(this.selectedProduct.id, this.selectedQuantity);
                this.closeQuantityModal();
                // Show success notification
                this.notificationStore.show('تم إضافة المنتج إلى السلة بنجاح', 'success');
            } catch (error) {
                this.notificationStore.show('حدث خطأ في إضافة المنتج إلى السلة', 'error');
            }
        },
        isInCart(productId) {
            return this.cartStore?.getItemById?.(productId) || false;
        }
    }
};
</script>

<template>
    <section class="py-16 bg-white" style="direction: rtl;">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">أفضل العروض</h2>
                <hr class="w-24 h-1 bg-red-600 mx-auto mb-4">
                <p class="text-gray-600 text-lg">اكتشف أكبر الخصومات وأفضل الأسعار</p>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-red-600"></div>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="text-center py-12">
                <p class="text-red-500 text-lg">{{ error }}</p>
            </div>

            <!-- No Offers State -->
            <div v-else-if="bestOffers.length === 0" class="text-center py-12">
                <p class="text-gray-500 text-lg">لا توجد عروض متاحة حالياً</p>
            </div>

            <!-- Offers Grid -->
            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <Link
                    v-for="product in bestOffers"
                    :key="product.id"
                    :href="`/product/${product.id}`"
                    class="group bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 border border-gray-100 block"
                >
                    <!-- Product Image -->
                    <div class="relative w-full aspect-[4/3] sm:aspect-[3/2] bg-gray-50 overflow-hidden">
                        <img :src="product.image" :alt="product.name" loading="lazy" decoding="async" class="absolute inset-0 w-full h-full object-contain group-hover:scale-105 transition-transform duration-300" />
                        <!-- Discount Badge -->
                        <div v-if="product.discount_price != product.price" class="absolute top-3 right-3 rtl:left-3 rtl:right-auto bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs font-bold border border-yellow-300 flex items-center gap-1">
                            <span>كمية محدودة</span>
                        </div>
                    </div>
                    <!-- Product Info -->
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ product.name }}</h3>
                        <p class="text-sm text-gray-600 mb-3">{{ product.description.length > 50 ? product.description.substring(0, 50) + '...' : product.description }}</p>

                        <!-- Price -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="space-y-1">
                                <div v-if="product.discount_price != product.price" class="text-sm text-gray-400 line-through">
                                    {{ formatPrice(product.price) }}
                                </div>
                                <div class="text-lg font-bold text-red-600">
                                    {{ formatPrice(product.discount_price) }}
                                </div>
                            </div>
                            <div class="text-xs text-gray-500">
                                الكود: {{ product.code }}
                            </div>
                        </div>

                        <!-- Add to Cart Button -->
                        <button
                            @click.prevent="openQuantityModal(product)"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200"
                            :disabled="product.quantity <= 0"
                        >
                            {{ product.quantity > 0 ? 'أضف إلى السلة' : 'نفذت الكمية' }}
                        </button>
                    </div>
                </Link>
            </div>
        </div>

        <!-- Quantity Modal -->
        <div v-if="showQuantityModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-96 max-w-md mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">اختر الكمية</h3>
                    <button @click="closeQuantityModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div v-if="selectedProduct" class="mb-4">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <img :src="selectedProduct.image" :alt="selectedProduct.name" loading="lazy" decoding="async" class="w-16 h-16 object-contain rounded bg-gray-50">
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ selectedProduct.name }}</h4>
                            <p class="text-sm text-gray-600">{{ selectedProduct.discount_price || selectedProduct.price }} د.ك</p>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">الكمية:</label>
                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                        <button
                            @click="selectedQuantity = Math.max(1, selectedQuantity - 1)"
                            class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                        </button>
                        <input
                            v-model.number="selectedQuantity"
                            type="number"
                            min="1"
                            :max="selectedProduct ? selectedProduct.quantity : 1"
                            class="w-16 text-center border border-gray-300 rounded px-2 py-1"
                        >
                        <button
                            @click="selectedQuantity = Math.min(selectedProduct ? selectedProduct.quantity : 1, selectedQuantity + 1)"
                            class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">المتوفر: {{ selectedProduct ? selectedProduct.quantity : 0 }} قطعة</p>
                </div>

                <div class="flex space-x-2 rtl:space-x-reverse">
                    <button
                        @click="closeQuantityModal"
                        class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition-colors"
                    >
                        إلغاء
                    </button>
                    <button
                        @click="confirmAddToCart"
                        :disabled="selectedQuantity <= 0 || selectedQuantity > (selectedProduct ? selectedProduct.quantity : 0)"
                        class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors"
                    >
                        أضف إلى السلة
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.line-clamp-2 {
    overflow: hidden;
    text-overflow: ellipsis;
    max-height: 3em;
    line-height: 1.5;
    position: relative;
}

.line-clamp-2::after {
    content: '';
    position: absolute;
    bottom: 0;
    right: 0;
    width: 40%;
    height: 1.5em;
    background: linear-gradient(to right, rgba(255, 255, 255, 0), rgba(255, 255, 255, 1) 50%);
    pointer-events: none;
}

.product-img-fit {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
</style>
