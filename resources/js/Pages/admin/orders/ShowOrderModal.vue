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
        formatDate(dateStr) {
            if (!dateStr) return '';
            const d = new Date(dateStr);
            const day = String(d.getDate()).padStart(2, '0');
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const year = d.getFullYear();
            const hours = String(d.getHours()).padStart(2, '0');
            const minutes = String(d.getMinutes()).padStart(2, '0');
            return `${year}/${month}/${day} ${hours}:${minutes}`;
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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-3 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="block text-gray-500 text-base">اسم العميل:</span>
                        <span class="bg-gray-100 rounded px-2 py-1 text-base">{{ order.name }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="block text-gray-500 text-base">رقم الهاتف:</span>
                        <span class="bg-gray-100 rounded px-2 py-1 text-base">{{ order.phone }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="block text-gray-500 text-base">الحالة:</span>
                        <span class="bg-gray-100 rounded px-2 py-1 text-base" :class="getStatusColor(order.status)">{{ getStatusText(order.status) }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="block text-gray-500 text-base">الضرائب:</span>
                        <span class="bg-gray-100 rounded px-2 py-1 text-base">{{ order.taxes }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="block text-gray-500 text-base">تاريخ الإنشاء:</span>
                        <span class="bg-gray-100 rounded px-2 py-1 text-base">{{ formatDate(order.created_at) }}</span>
                    </div>
                </div>
                <!-- Address -->
                <div class="flex items-center gap-2 mb-4">
                    <span class="block text-gray-500 text-base">العنوان:</span>
                    <span class="bg-gray-100 rounded px-2 py-1 text-base min-h-[40px]">{{ order.address }}</span>
                </div>

                <!-- Products Section -->
                <div class="flex-1 flex flex-col min-h-0 mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold">المنتجات</span>
                    </div>
                    <div class="flex-1 overflow-y-auto border rounded bg-gray-50 p-2">
                        <div class="space-y-2">
                            <div v-if="order.products && Array.isArray(order.products) && order.products.length">
                                <table style="direction: rtl">
                                    <thead>
                                        <tr class="text-right">
                                            <th>اسم المنتج</th>
                                            <th>الكمية</th>
                                            <th>السعر</th>
                                            <th>المجموع</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(product, index) in order.products" :key="index" class="text-right">
                                            <td>{{ product.name }}</td>
                                            <td>{{ product.quantity || 1 }}</td>
                                            <td>{{ product.price || 0 }}</td>
                                            <td>{{ (product.price || 0) * (product.quantity || 1) }} ريال</td>
                                        </tr>
                                    </tbody>
                                </table>
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
<style scoped>
table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;

}

th {
    background-color: #4a5568;
    color: white;
}
</style>
