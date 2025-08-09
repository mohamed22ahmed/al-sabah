<script>
import axios from 'axios';

export default {
    data() {
        return {
            banners: [],
            currentIndex: 0,
            loading: true,
            error: null
        };
    },

    mounted() {
        this.fetchBanners();
        this.startAutoSlide();
    },

    beforeUnmount() {
        this.stopAutoSlide();
    },

    methods: {
        async fetchBanners() {
            try {
                this.loading = true;
                const response = await axios.get('/api/home/banners');
                this.banners = response.data.data.filter(banner => banner.show);
                this.loading = false;
            } catch (error) {
                console.error('Error fetching banners:', error);
                this.error = 'حدث خطأ في تحميل البانرات';
                this.loading = false;
            }
        },

        nextSlide() {
            if (this.banners.length > 0) {
                this.currentIndex = (this.currentIndex + 1) % this.banners.length;
            }
        },

        prevSlide() {
            if (this.banners.length > 0) {
                this.currentIndex = this.currentIndex === 0
                    ? this.banners.length - 1
                    : this.currentIndex - 1;
            }
        },

        goToSlide(index) {
            this.currentIndex = index;
        },

        startAutoSlide() {
            this.autoSlideInterval = setInterval(() => {
                if (this.banners.length > 1) {
                    this.nextSlide();
                }
            }, 5000); // Change slide every 5 seconds
        },

        stopAutoSlide() {
            if (this.autoSlideInterval) {
                clearInterval(this.autoSlideInterval);
            }
        }
    }
};
</script>

<template>
    <div class="relative w-full h-56 sm:h-72 md:h-96 lg:h-[520px] overflow-hidden rounded-lg shadow-lg">
        <!-- Loading State -->
        <div v-if="loading" class="flex items-center justify-center h-full bg-gray-200">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="flex items-center justify-center h-full bg-gray-200 text-red-600">
            {{ error }}
        </div>

        <!-- Banners Slideshow -->
        <div v-else-if="banners.length > 0" class="relative h-full">
            <!-- Slides Container -->
            <div class="relative h-full">
                <div
                    v-for="(banner, index) in banners"
                    :key="banner.id"
                    class="absolute inset-0 transition-opacity duration-500 ease-in-out"
                    :class="{ 'opacity-100': index === currentIndex, 'opacity-0': index !== currentIndex }"
                >
                    <img
                        :src="banner.image"
                        :alt="`Banner ${banner.id}`"
                        :loading="index === currentIndex ? 'eager' : 'lazy'"
                        decoding="async"
                        class="w-full h-full object-cover"
                    />
                </div>
            </div>

            <!-- Navigation Arrows -->
            <button
                v-if="banners.length > 1"
                @click="prevSlide"
                class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-all duration-200 z-10"
                aria-label="Previous slide"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button
                v-if="banners.length > 1"
                @click="nextSlide"
                class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-all duration-200 z-10"
                aria-label="Next slide"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Dots Navigation -->
            <div v-if="banners.length > 1" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10">
                <button
                    v-for="(banner, index) in banners"
                    :key="`dot-${banner.id}`"
                    @click="goToSlide(index)"
                    class="w-3 h-3 rounded-full transition-all duration-200"
                    :class="index === currentIndex ? 'bg-white' : 'bg-white bg-opacity-50 hover:bg-opacity-75'"
                    :aria-label="`Go to slide ${index + 1}`"
                ></button>
            </div>
        </div>

        <!-- No Banners State -->
        <div v-else class="flex items-center justify-center h-full bg-gray-200 text-gray-500">
            لا توجد بانرات متاحة
        </div>
    </div>
</template>

<style scoped>
.banner-img-fit {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
/* Custom scrollbar for webkit browsers */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
