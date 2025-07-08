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
        banner: {
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
                <h5 class="text-lg font-bold">تفاصيل البانر</h5>
                <button type="button" class="text-gray-500 hover:text-red-600 text-2xl" @click="$emit('close')">&times;</button>
            </div>
            <hr class="my-4">

            <div class="space-y-4">
                <!-- Banner ID -->
                <div class="flex items-center border-b border-gray-200 pb-3">
                    <span class="font-semibold text-gray-700 min-w-[120px]">الرقم:</span>
                    <span class="text-gray-900 flex-1">{{ banner.id }}</span>
                </div>

                <!-- Status -->
                <div class="flex items-center border-b border-gray-200 pb-3">
                    <span class="font-semibold text-gray-700 min-w-[120px]">الحالة:</span>
                    <span :class="banner.show ? 'text-green-600 font-bold' : 'text-red-600 font-bold'" class="flex-1">
                        {{ banner.show ? 'مفعل' : 'معطل' }}
                    </span>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="font-semibold text-gray-700 mb-2">الصورة:</h3>
                <img
                    :src="banner.image"
                    :alt="'Banner ' + banner.id"
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
