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
        district: {
            type: Object,
            default: () => ({}),
        },
    },

    emits: ["close"],
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-4 max-h-[80vh] overflow-auto mx-4 my-6" style="direction: rtl;">
            <div class="flex justify-between items-center mb-3">
                <h5 class="text-lg font-bold">تفاصيل الحي</h5>
                <button type="button" class="text-gray-500 hover:text-red-600 text-2xl" @click="$emit('close')">&times;</button>
            </div>
            <hr class="my-3">

            <div class="space-y-3">
                <!-- Product Name -->
                <div class="flex items-center border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-700 min-w-[120px] text-sm">الاسم:</span>
                    <span class="text-gray-900 flex-1 text-sm">{{ district.name }}</span>
                </div>

                <!-- Category -->
                <div class="flex items-center border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-700 min-w-[120px] text-sm">الاسم بالعربى:</span>
                    <span class="text-gray-900 flex-1 text-sm">{{ district.name_ar}}</span>
                </div>

                <div class="flex items-center border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-700 min-w-[120px] text-sm">اسم المنطقة:</span>
                    <span class="text-gray-900 flex-1 text-sm">{{ district.zone_name }}</span>
                </div>

                <!-- Price -->
                <div class="flex items-center border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-700 min-w-[120px] text-sm">السعر:</span>
                    <span class="text-gray-900 flex-1 text-sm">{{ district.price }} ريال</span>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <button
                        type="button"
                        @click="$emit('close')"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm"
                    >
                        اغلاق
                    </button>
            </div>
        </div>
    </Modal>
</template>
