<script>
import { Head, Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import navbar from "@/Components/navbar.vue";
import Footer from "@/Components/footer.vue";
import Toast from '@/Components/Toast.vue';
import WhatsAppButton from '@/Components/WhatsAppButton.vue';
import { useNotificationStore } from '@/Stores/notificationStore';
import { useCartStore } from '@/Stores/cart';

export default {
    components: {
        Head,
        Link,
        navbar,
        Footer,
        Toast,
        WhatsAppButton
    },
    props: {
        links: Array,
        product: Object
    },
    setup() {
        const notificationStore = useNotificationStore();
        const cartStore = useCartStore();
        const page = usePage();
        return {
            notificationStore,
            cartStore,
            page
        };
    },
    data() {
        return {
            selectedProduct: null,
            quantity: 1,
            showQuantityModal: false,
            showFullDescription: false
        };
    },
    mounted() {
        this.cartStore.fetchCart();
        this.selectedProduct = this.product.data;
        console.log(this.selectedProduct);
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
        openQuantityModal() {
            if (this.selectedProduct.quantity <= 0) return;
            this.quantity = 1;
            this.showQuantityModal = true;
        },
        closeQuantityModal() {
            this.showQuantityModal = false;
            this.quantity = 1;
        },
        async addToCart() {
            if (this.selectedProduct.quantity <= 0) return;

            try {
                await this.cartStore.addToCart(this.selectedProduct.id, this.quantity);
                this.closeQuantityModal();
                this.notificationStore.show('تم إضافة المنتج إلى السلة بنجاح', 'success');
            } catch (error) {
                this.notificationStore.show('حدث خطأ في إضافة المنتج إلى السلة', 'error');
            }
        },
        isInCart() {
            return this.cartStore?.getItemById?.(this.selectedProduct.id) || false;
        },
        toggleDescription() {
            this.showFullDescription = !this.showFullDescription;
        }
    }
}
</script>

<template>
    <Head :title="selectedProduct?.name" />
    <div class="bg-gray-50 min-h-screen flex flex-col">
        <header>
            <navbar :links="links" />
        </header>

        <div class="flex-1 w-[90%] mx-auto py-8">
            <!-- Loading State -->
            <div v-if="!selectedProduct" class="flex items-center justify-center h-64">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-red-600"></div>
            </div>

            <!-- Product Content -->
            <div v-else>
                <!-- Breadcrumb -->
                <nav class="mb-8" style="direction: rtl;">
                    <ol class="flex items-center space-x-2 space-x-reverse text-sm text-gray-600">
                        <li>
                            <Link href="/" class="hover:text-red-600 transition-colors">الرئيسية</Link>
                        </li>
                        <li class="text-gray-400">/</li>
                        <li>
                            <Link :href="selectedProduct.category?.url" class="hover:text-red-600 transition-colors">
                                {{ selectedProduct.category?.name }}
                            </Link>
                        </li>
                        <li class="text-gray-400">/</li>
                        <li class="text-gray-900 font-medium">{{ selectedProduct.name }}</li>
                    </ol>
                </nav>

                <!-- Product Details -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden" style="direction: rtl;">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-8">
                        <!-- Product Image -->
                        <div class="relative">
                            <div class="relative w-full bg-gray-50 rounded-lg overflow-hidden">
                                <div class="w-full aspect-square md:aspect-[4/3]"></div>
                                <img
                                    :src="selectedProduct.image"
                                    :alt="selectedProduct.name"
                                    loading="lazy"
                                    decoding="async"
                                    class="absolute inset-0 w-full h-full object-contain"
                                />
                            </div>

                            <!-- Discount Badge -->
                            <div v-if="selectedProduct.discount_price != selectedProduct.price"
                                 class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                خصم {{ getDiscountPercentage(selectedProduct.price, selectedProduct.discount_price) }}%
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="space-y-6">
                            <!-- Product Name -->
                            <h1 class="text-3xl font-bold text-gray-900">{{ selectedProduct.name }}</h1>

                            <!-- Product Code -->
                            <div class="text-gray-600">
                                <span class="font-semibold">الكود:</span> {{ selectedProduct.code }}
                            </div>

                            <!-- Price -->
                            <div class="space-y-2">
                                <div v-if="selectedProduct.discount_price != selectedProduct.price" class="text-2xl text-gray-400 line-through">
                                    {{ formatPrice(selectedProduct.price) }}
                                </div>
                                <div class="text-3xl font-bold text-red-600">
                                    {{ formatPrice(selectedProduct.discount_price) }}
                                </div>

                                <!-- Quantity Available -->
                                <div class="text-gray-600 mt-4">
                                    <span class="font-semibold">الكمية المتوفرة:</span>
                                    <span :class="selectedProduct.quantity > 0 ? 'text-green-600' : 'text-red-600'">
                                        {{ selectedProduct.quantity > 0 ? selectedProduct.quantity : 'نفذت الكمية' }}
                                    </span>
                                </div>

                                <!-- Sold Count -->
                                <div class="text-gray-600">
                                    <span class="font-semibold">المباع:</span> {{ selectedProduct.sold }}
                                </div>
                            </div>

                            <!-- Basic Info Grid -->
                            <div class="grid grid-cols-1 gap-4">
                                <!-- Weight -->
                                <div class="text-gray-600">
                                    <span class="font-semibold">الوزن:</span> {{ selectedProduct.weight }} كجم
                                </div>
                            </div>

                            <!-- Description -->
                                    <div v-if="selectedProduct.description" class="space-y-2">
                                <h3 class="text-lg font-semibold text-gray-900">الوصف:</h3>
                                <div class="text-gray-600 leading-relaxed">
                                    <div v-if="!showFullDescription" v-html="selectedProduct.description.substring(0, 50) + '...'"></div>
                                    <div v-else v-html="selectedProduct.description"></div>
                                </div>
                                <button
                                    v-if="selectedProduct.description.length > 50"
                                    @click="toggleDescription"
                                    class="text-red-600 hover:text-red-700 font-semibold text-sm transition-colors"
                                >
                                    {{ showFullDescription ? 'عرض أقل' : 'عرض الكل' }}
                                </button>
                            </div>

                            <!-- Add to Cart Section -->
                            <div class="border-t pt-6 space-y-4">
                                <div v-if="selectedProduct.quantity > 0" class="flex items-center space-x-4 space-x-reverse">
                                    <button
                                        @click="openQuantityModal"
                                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-lg transition-colors duration-200 flex items-center space-x-2 space-x-reverse"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"/>
                                        </svg>
                                        <span>أضف إلى السلة</span>
                                    </button>

                                    <div v-if="isInCart()" class="text-green-600 font-semibold">
                                        ✓ تمت الإضافة إلى السلة
                                    </div>
                                </div>

                                <div v-else class="text-red-600 font-semibold">
                                    نفذت الكمية
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Footer />
        <Toast />
        <WhatsAppButton />

        <!-- Quantity Modal -->
        <div v-if="showQuantityModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-96 max-w-sm mx-4" style="direction: rtl;">
                <h3 class="text-lg font-bold mb-4">اختر الكمية</h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-center space-x-4 space-x-reverse">
                        <button
                            @click="quantity > 1 ? quantity-- : null"
                            class="w-10 h-10 bg-gray-200 hover:bg-gray-300 rounded-full flex items-center justify-center transition-colors"
                            :disabled="quantity <= 1"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                        </button>

                        <span class="text-2xl font-bold w-16 text-center">{{ quantity }}</span>

                        <button
                            @click="quantity < selectedProduct.quantity ? quantity++ : null"
                            class="w-10 h-10 bg-gray-200 hover:bg-gray-300 rounded-full flex items-center justify-center transition-colors"
                            :disabled="quantity >= selectedProduct.quantity"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    </div>

                    <div class="text-center text-gray-600">
                        السعر الإجمالي: {{ formatPrice(selectedProduct.discount_price * quantity) }}
                    </div>
                </div>

                <div class="flex space-x-3 space-x-reverse mt-6">
                    <button
                        @click="closeQuantityModal"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition-colors"
                    >
                        إلغاء
                    </button>
                    <button
                        @click="addToCart"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition-colors"
                    >
                        إضافة إلى السلة
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
