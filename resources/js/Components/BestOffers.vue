<script>
export default {
    data() {
        return {
            offers: [
                {
                    id: 1,
                    title: "مجموعة الالات القهوة 1",
                    image: "https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=400&h=300&fit=crop",
                    oldPrice: 24650.00,
                    newPrice: 22450.00,
                    discount: 9,
                    inStock: true
                },
                {
                    id: 2,
                    title: "بكج سهلنا لك مخبوزاتك",
                    image: "https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=300&fit=crop",
                    oldPrice: 9800.00,
                    newPrice: 8450.00,
                    discount: 14,
                    inStock: true
                },
                {
                    id: 3,
                    title: "بكج معدات المقاهي المتكامل",
                    image: "https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=400&h=300&fit=crop",
                    oldPrice: null,
                    newPrice: 24500.00,
                    discount: 0,
                    inStock: true
                }
            ]
        };
    },
    methods: {
        formatPrice(price) {
            return new Intl.NumberFormat('ar-SA', {
                style: 'currency',
                currency: 'SAR'
            }).format(price);
        },
        addToCart(productId) {
            // Dummy function - would integrate with cart store
            console.log('Adding to cart:', productId);
        }
    }
};
</script>

<template>
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Section Header -->
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">افضل العروض</h2>
                <div class="w-24 h-1 bg-cyan-600 mx-auto"></div>
            </div>

            <!-- Offers Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="offer in offers" :key="offer.id" class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <!-- Product Image -->
                    <div class="relative h-64 overflow-hidden">
                        <img :src="offer.image" :alt="offer.title" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" />
                        <!-- Heart Icon -->
                        <button class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Product Info -->
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 text-right">{{ offer.title }}</h3>

                        <!-- Pricing -->
                        <div class="mb-4">
                            <div class="flex items-center justify-end gap-2 mb-1">
                                <span v-if="offer.oldPrice" class="text-gray-400 line-through text-sm">
                                    {{ formatPrice(offer.oldPrice) }}
                                </span>
                                <span class="text-xl font-bold text-cyan-600">
                                    {{ formatPrice(offer.newPrice) }}
                                </span>
                            </div>
                            <div v-if="offer.discount > 0" class="text-right">
                                <span class="text-sm text-green-600 font-semibold">
                                    وفر {{ offer.discount }}%
                                </span>
                            </div>
                        </div>

                        <!-- Add to Cart Button -->
                        <button
                            @click="addToCart(offer.id)"
                            class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200"
                        >
                            اضف للسلة
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.text-right {
    text-align: right;
}
</style>
