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
        order: {
            type: Object,
            default: () => ({}),
        },
    },

    emits: ["close"],

    methods: {
        getStatusText(status) {
            switch(status) {
                case 'pending': return 'معلق';
                case 'processing': return 'قيد المعالجة';
                case 'completed': return 'مكتمل';
                case 'cancelled': return 'ملغي';
                default: return status;
            }
        },

        getStatusColor(status) {
            switch(status) {
                case 'pending': return 'text-yellow-600';
                case 'processing': return 'text-blue-600';
                case 'completed': return 'text-green-600';
                case 'cancelled': return 'text-red-600';
                default: return 'text-gray-600';
            }
        },
    },
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')" max-width="4xl">
        <div class="flex flex-col max-h-[90vh] h-[90vh] overflow-hidden" style="direction: rtl;">
            <!-- Header -->
            <div class="flex justify-between items-center p-4 border-b bg-gray-50">
                <h5 class="text-lg font-bold">تفاصيل الطلب</h5>
                <button type="button" class="text-gray-500 hover:text-red-600 text-2xl" @click="$emit('close')">&times;</button>
            </div>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col min-h-0 p-4">
                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
                    <div>
                        <span class="block text-gray-500 text-xs mb-1">اسم العميل</span>
                        <div class="bg-gray-100 rounded px-2 py-1 text-sm">{{ order.name }}</div>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-xs mb-1">رقم الهاتف</span>
                        <div class="bg-gray-100 rounded px-2 py-1 text-sm">{{ order.phone }}</div>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-xs mb-1">الحالة</span>
                        <div class="bg-gray-100 rounded px-2 py-1 text-sm" :class="getStatusColor(order.status)">
                            {{ getStatusText(order.status) }}
                        </div>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-xs mb-1">الضرائب</span>
                        <div class="bg-gray-100 rounded px-2 py-1 text-sm">{{ order.taxes }}</div>
                    </div>
                </div>

                <!-- Address -->
                <div class="mb-4">
                    <span class="block text-gray-500 text-xs mb-1">العنوان</span>
                    <div class="bg-gray-100 rounded px-2 py-1 text-sm min-h-[40px]">{{ order.address }}</div>
                </div>

                <!-- Products Section -->
                <div class="flex-1 flex flex-col min-h-0 mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold">المنتجات</span>
                    </div>
                    <div class="flex-1 overflow-y-auto border rounded bg-gray-50 p-2">
                        <div class="space-y-2">
                            <div v-if="order.products && Array.isArray(order.products) && order.products.length">
                                <div v-for="(product, index) in order.products" :key="index" class="grid grid-cols-12 gap-2 p-2 border rounded bg-white">
                                    <div class="col-span-4 flex items-center">
                                        <span class="text-xs">{{ product.name || product }}</span>
                                    </div>
                                    <div class="col-span-2 flex items-center">
                                        <span class="text-xs">{{ product.quantity || 1 }}</span>
                                    </div>
                                    <div class="col-span-2 flex items-center">
                                        <span class="text-xs">{{ product.price || 0 }}</span>
                                    </div>
                                    <div class="col-span-2 flex items-center justify-center">
                                        <span class="text-xs font-semibold">{{ (product.price || 0) * (product.quantity || 1) }} ريال</span>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-gray-400 text-xs text-center py-4">لا توجد منتجات</div>
                        </div>
                    </div>
                </div>

                <!-- Total -->
                <div class="flex-shrink-0 mt-4 text-right mb-4">
                    <span class="font-bold text-lg">المجموع: {{ order.total }} ريال</span>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-4 border-t bg-gray-50 flex justify-end space-x-3 space-x-reverse">
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
