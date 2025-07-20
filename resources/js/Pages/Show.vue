<script>
import { Head } from '@inertiajs/vue3';
import { useCartStore } from '@/Stores/cart';
import Welcome from "./Welcome.vue"
import Toast from '@/Components/Toast.vue';
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        Head,
        Welcome,
        Toast,
        Link
    },
    props: {
        links: Array,
        category: Object,
        products: Array,
    },
    data() {
        return {
            sort: 'latest',
            filter: 'all',
            favoriteIds: [],
            showQuantityModal: false,
            selectedProduct: null,
            selectedQuantity: 1,
            cartStore: useCartStore(),
            toast: {
                show: false,
                message: '',
                type: 'success',
            },
        };
    },
    computed: {
        filteredProducts() {
            let prods = [...this.products.data];
            // Filter by availability
            if (this.filter === 'available') {
                prods = prods.filter(p => p.quantity > 0);
            } else if (this.filter === 'out') {
                prods = prods.filter(p => p.quantity === 0);
            }
            // Sort
            if (this.sort === 'latest') {
                prods = prods.sort((a, b) => b.id - a.id);
            } else if (this.sort === 'price_asc') {
                prods = prods.sort((a, b) => (a.discount_price || a.price) - (b.discount_price || b.price));
            } else if (this.sort === 'price_desc') {
                prods = prods.sort((a, b) => (b.discount_price || b.price) - (a.discount_price || a.price));
            }
            return prods;
        }
    },

    async mounted() {
        await this.cartStore.fetchCart();
    },
    methods: {
        formatPrice(price) {
            return new Intl.NumberFormat('ar-SA', {
                style: 'currency',
                currency: 'SAR'
            }).format(price);
        },
        getDiscountPercentage(originalPrice, discountPrice) {
            if (!discountPrice) return 0;
            return Math.round(((originalPrice - discountPrice) / originalPrice) * 100);
        },
        toggleFavorite(id) {
            if (this.favoriteIds.includes(id)) {
                this.favoriteIds = this.favoriteIds.filter(fid => fid !== id);
            } else {
                this.favoriteIds.push(id);
            }
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
        async addToCart() {
            if (!this.selectedProduct) return;

            const result = await this.cartStore.addToCart(this.selectedProduct.id, this.selectedQuantity);

            if (result.success) {
                this.selectedProduct.quantity -= this.selectedQuantity;
                this.closeQuantityModal();
                this.toast.message = result.message;
                this.toast.type = 'success';
                this.toast.show = true;
            } else {
                this.toast.message = result.message;
                this.toast.type = 'error';
                this.toast.show = true;
            }
        },
        isInCart(productId) {
            return this.cartStore?.getItemById?.(productId) || false;
        }
    },
};
</script>

<template>
    <Head :title="category?.name || 'المنتجات'" />
    <Welcome :links="links">
        <div class="bg-gray-50 min-h-screen flex flex-col">
            <div class="w-[90%] mx-auto">
                <!-- Filter & Sort Bar -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between py-6 gap-4">
                    <div class="text-gray-600 text-lg font-medium">
                        تم ايجاد <span class="text-cyan-600 font-bold">{{ filteredProducts.length }}</span> منتج
                    </div>
                    <div class="flex flex-wrap gap-3 items-center rtl:flex-row-reverse">
                        <div class="flex gap-2 items-center">
                            <select id="sort" v-model="sort" class="px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 bg-white text-gray-700">
                                <option value="latest">الأحدث</option>
                                <option value="price_asc">الأقل سعراً</option>
                                <option value="price_desc">الأعلى سعراً</option>
                            </select>
                            <label for="sort" class="text-gray-500">ترتيب حسب</label>
                        </div>
                        <div class="flex gap-2 items-center">
                            <select id="filter" v-model="filter" class="px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 bg-white text-gray-700">
                                <option value="all">الكل</option>
                                <option value="available">المتاح</option>
                                <option value="out">غير متوفر</option>
                            </select>
                            <label for="filter" class="text-gray-500">تصنيف النتائج</label>
                        </div>
                    </div>
                </div>
                <!-- Products Grid -->
                <section class="pb-12" style="direction: rtl;">
                    <div v-if="filteredProducts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-7">
                        <Link
                            v-for="product in filteredProducts"
                            :key="product.id"
                            :href="`/product/${product.id}`"
                            class="bg-white rounded-2xl shadow group flex flex-col relative overflow-hidden border border-gray-100 hover:shadow-lg transition-all duration-300 block"
                        >
                            <!-- Product Image -->
                            <div class="relative w-full h-48 flex items-center justify-center bg-gray-50 overflow-hidden">
                                <img :src="product.image" :alt="product.name" class="object-contain w-full h-full group-hover:scale-105 transition-transform duration-300" />
                                <!-- Discount Badge -->
                                <div v-if="product.discount_price != product.price" class="absolute top-3 right-3 rtl:left-3 rtl:right-auto bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs font-bold border border-yellow-300 flex items-center gap-1">
                                    <span>كمية محدودة</span>
                                </div>
                            </div>
                            <!-- Product Info -->
                            <div class="flex-1 flex flex-col p-4">
                                <h3 class="text-base font-bold text-gray-900 mb-2 text-center">{{ product.name }}</h3>
                                <!-- Price Section -->
                                <div class="flex flex-col items-center mb-2">
                                    <div class="flex items-center gap-2">
                                        <span v-if="product.discount_price != product.price" class="text-gray-400 line-through text-lg">{{ formatPrice(product.price) }}</span>
                                        <span class="text-lg font-bold text-cyan-700">{{ formatPrice(product.discount_price || product.price) }}</span>
                                    </div>
                                    <div v-if="product.discount_price != product.price" class="text-xs text-pink-600 font-bold mt-1">
                                        وفر {{ getDiscountPercentage(product.price, product.discount_price) }}%
                                    </div>
                                </div>
                                <!-- Availability -->
                                <div class="flex items-center justify-center gap-2 mb-3">
                                    <span v-if="product.quantity > 0" class="text-green-600 text-xs font-semibold">متوفر</span>
                                    <span v-else class="text-gray-400 text-xs font-semibold flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728"/></svg>
                                        نفذت الكمية
                                    </span>
                                </div>
                                <!-- Add to Cart Button -->
                                <button
                                    @click.prevent="product.quantity > 0 ? openQuantityModal(product) : null"
                                    :disabled="product.quantity === 0 || cartStore?.loading"
                                    class="w-full py-2 rounded-lg font-bold text-white transition-colors duration-200 mt-auto"
                                    :class="product.quantity > 0 ? 'bg-cyan-700 hover:bg-cyan-800' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                                >
                                    <span v-if="product.quantity > 0">
                                        <span v-if="isInCart(product.id)" class="flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            تم الإضافة
                                        </span>
                                        <span v-else>اضف للسلة</span>
                                    </span>
                                    <span v-else>نفذت الكمية</span>
                                </button>
                            </div>
                        </Link>
                    </div>
                    <!-- Empty State -->
                    <div v-else class="text-center py-16">
                        <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">لا توجد منتجات</h3>
                        <p class="text-gray-600">لا توجد منتجات متاحة في هذه الفئة حالياً</p>
                    </div>
                </section>
            </div>
        </div>

        <!-- Quantity Selection Modal -->
        <div v-if="showQuantityModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-96 max-w-md mx-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">اختر الكمية</h3>
                    <button @click="closeQuantityModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="mb-4">
                    <div class="flex items-center gap-3 mb-3">
                        <img :src="selectedProduct?.image" :alt="selectedProduct?.name" class="w-16 h-16 object-cover rounded">
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ selectedProduct?.name }}</h4>
                            <p class="text-cyan-600 font-bold">{{ formatPrice(selectedProduct?.discount_price || selectedProduct?.price) }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="text-gray-700 font-medium">الكمية:</label>
                        <div class="flex items-center gap-2">
                            <button
                                @click="selectedQuantity > 1 ? selectedQuantity-- : null"
                                :disabled="selectedQuantity <= 1"
                                class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 disabled:opacity-50"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/>
                                </svg>
                            </button>
                            <span class="w-12 text-center font-semibold">{{ selectedQuantity }}</span>
                            <button
                                @click="selectedQuantity < selectedProduct?.quantity ? selectedQuantity++ : null"
                                :disabled="selectedQuantity >= selectedProduct?.quantity"
                                class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 disabled:opacity-50"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <p class="text-sm text-gray-500 mt-2">المتوفر: {{ selectedProduct?.quantity }} قطعة</p>
                </div>

                <div class="flex gap-3">
                    <button
                        @click="closeQuantityModal"
                        class="flex-1 py-2 px-4 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50"
                    >
                        إلغاء
                    </button>
                    <button
                        @click="addToCart"
                        :disabled="cartStore?.loading || selectedProduct?.quantity === 0"
                        class="flex-1 py-2 px-4 rounded-lg disabled:opacity-50"
                        :class="selectedProduct?.quantity === 0 ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-cyan-600 text-white hover:bg-cyan-700'"
                    >
                        <span v-if="cartStore?.loading">جاري الإضافة...</span>
                        <span v-else-if="selectedProduct?.quantity === 0">نفذت الكمية</span>
                        <span v-else>إضافة للسلة</span>
                    </button>
                </div>
            </div>
        </div>
        <Toast v-if="toast.show" :message="toast.message" :type="toast.type" @close="toast.show = false" />
    </Welcome>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
