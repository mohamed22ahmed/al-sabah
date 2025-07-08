<script>
import Modal from "@/Components/Modal.vue";

export default {
    components: {
        Modal,
    },

    props: {
        show: {
            type: Boolean,
            default: false,
        },
        product: {
            type: Object,
            default: () => ({}),
        },
    },

    emits: ["close"],
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6" style="direction: rtl;">
            <div class="flex justify-between items-center mb-4">
                <h5 class="text-lg font-bold">تفاصيل المنتج</h5>
                <button type="button" class="text-gray-500 hover:text-red-600 text-2xl" @click="$emit('close')">&times;</button>
            </div>
            <hr class="my-4">

            <div class="space-y-4">
                <!-- Product Name -->
                <div class="flex items-center border-b border-gray-200 pb-3">
                    <span class="font-semibold text-gray-700 min-w-[120px]">الاسم:</span>
                    <span class="text-gray-900 flex-1">{{ product.name }}</span>
                </div>

                <!-- Category -->
                <div class="flex items-center border-b border-gray-200 pb-3">
                    <span class="font-semibold text-gray-700 min-w-[120px]">القسم:</span>
                    <span class="text-gray-900 flex-1">{{ product.category?.name || 'غير محدد' }}</span>
                </div>

                <!-- Code -->
                <div class="flex items-center border-b border-gray-200 pb-3">
                    <span class="font-semibold text-gray-700 min-w-[120px]">الكود:</span>
                    <span class="text-gray-900 flex-1 font-mono bg-gray-100 px-2 py-1 rounded">{{ product.code }}</span>
                </div>

                <!-- Price -->
                <div class="flex items-center border-b border-gray-200 pb-3">
                    <span class="font-semibold text-gray-700 min-w-[120px]">السعر:</span>
                    <span class="text-gray-900 flex-1 font-bold text-green-600">{{ product.price }} ريال</span>
                </div>

                <!-- Quantity -->
                <div class="flex items-center border-b border-gray-200 pb-3">
                    <span class="font-semibold text-gray-700 min-w-[120px]">الكمية:</span>
                    <span class="text-gray-900 flex-1">{{ product.quantity }}</span>
                </div>

                <!-- Weight -->
                <div class="flex items-center border-b border-gray-200 pb-3">
                    <span class="font-semibold text-gray-700 min-w-[120px]">الوزن:</span>
                    <span class="text-gray-900 flex-1">{{ product.weight }} كجم</span>
                </div>

                <!-- Sold -->
                <div class="flex items-center border-b border-gray-200 pb-3">
                    <span class="font-semibold text-gray-700 min-w-[120px]">المباع:</span>
                    <span class="text-gray-900 flex-1">{{ product.sold }}</span>
                </div>

                <!-- Description -->
                <div class="flex items-start border-b border-gray-200 pb-3">
                    <span class="font-semibold text-gray-700 min-w-[120px] mt-1">الوصف:</span>
                    <div class="text-gray-900 flex-1 bg-gray-50 p-3 rounded-lg">
                        {{ product.description }}
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="font-semibold text-gray-700 mb-2">الصورة:</h3>
                <img
                    :src="product.image"
                    :alt="product.name"
                    class="max-w-xs h-auto rounded-lg shadow-md"
                />
            </div>

            <div class="mt-6 flex justify-end">
                <button
                        type="button"
                        @click="$emit('close')"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                    >
                        اغلاق
                    </button>
            </div>
        </div>
    </Modal>
</template>
