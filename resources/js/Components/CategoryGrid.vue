<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        Link
    },

    props: {
        categories: {
            type: Array,
            default: () => []
        }
    },

    data() {
        return {
            loading: false,
            error: null,
            filteredCategories: []
        };
    },

    mounted() {
        this.fetchCategories();
    },

    methods: {
        async fetchCategories() {
            try {
                this.loading = true;
                this.error = null;
                const response = await fetch('/api/categories');
                const data = await response.json();

                if (data) {
                    this.filteredCategories = data;
                } else {
                    this.error = data.message || 'حدث خطأ في تحميل العروض';
                }
            } catch (error) {
                this.error = 'حدث خطأ في تحميل الفئات';
            } finally {
                this.loading = false;
            }
        },
    }
};
</script>

<template>
    <section class="py-16 bg-gray-50" style="direction: rtl;">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">تسوق حسب نشاطك</h2>
                <hr class="w-24 h-1 bg-red-600 mx-auto mb-4">
                <p class="text-gray-600 text-lg">اختر من بين فئاتنا المتنوعة</p>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-red-600"></div>
            </div>

            <!-- No Categories State -->
            <div v-else-if="filteredCategories.length === 0" class="text-center py-12">
                <p class="text-gray-500 text-lg">لا توجد فئات متاحة حالياً</p>
            </div>

            <!-- Categories Grid -->
            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <Link
                    v-for="category in filteredCategories"
                    :key="category.id"
                    :href="category.url"
                    class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 border border-gray-100 group block"
                >
                    <!-- Category Image -->
                    <div class="relative w-full aspect-[4/3] sm:aspect-[3/2] bg-gray-50 overflow-hidden">
                        <img
                            :src="category.image"
                            :alt="category.name"
                            loading="lazy"
                            decoding="async"
                            class="absolute inset-0 w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                        />
                        <div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-30 transition-all duration-300"></div>
                    </div>
                    <!-- Category Info -->
                    <div class="flex-1 flex flex-col p-4" style="height: 120px;">
                        <h3 class="text-lg font-bold text-gray-900 mb-2 text-center">{{ category.name }}</h3>
                        <p v-if="category.description" class="text-sm text-gray-600 text-center mb-4 line-clamp-2">
                            {{ category.description }}
                        </p>
                        <div class="w-full mt-auto bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors duration-200 font-semibold text-center">
                            تصفح الفئة
                        </div>
                    </div>
                </Link>
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

.category-img-fit {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
</style>
