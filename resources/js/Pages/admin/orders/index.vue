<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import "@fortawesome/fontawesome-free/css/all.css";
import axios from 'axios';
import ShowOrderModal from "@/Pages/admin/orders/ShowOrderModal.vue";
import AddOrderModal from "@/Pages/admin/orders/AddOrderModal.vue";
import EditOrderModal from "@/Pages/admin/orders/EditOrderModal.vue";
import DeleteOrderModal from "@/Pages/admin/orders/DeleteOrderModal.vue";

export default {
    components: {
        AuthenticatedLayout,
        Head,
        ShowOrderModal,
        AddOrderModal,
        EditOrderModal,
        DeleteOrderModal,
    },

    props: {
        orders: Array
    },

    data() {
        return {
            selectedOrder: {},
            isModalOpen: false,
            isAddOrderOpen: false,
            isEditOrderOpen: false,
            isDeleteOrderOpen: false,
            message: '',
            localOrders: [],
            toast: {
                show: false,
                message: '',
            },
            pollingInterval: null,
            lastOrderIds: [],
            // Filters
            statusFilter: 'all',
            dateFrom: '',
            dateTo: '',
        };
    },

    computed: {
        filteredOrders() {
            let orders = this.localOrders;
            if (this.statusFilter !== 'all') {
                orders = orders.filter(order => order.status === this.statusFilter);
            }
            if (this.dateFrom) {
                const filterDate = this.formatDate(this.dateFrom).split(' ')[0]; // Only date part
                orders = orders.filter(order => {
                    const orderDate = this.formatDate(order.created_at).split(' ')[0];
                    return orderDate === filterDate;
                });
            }
            return orders;
        },
    },

    mounted() {
        this.getOrders();
        this.startPolling();
    },
    beforeUnmount() {
        this.stopPolling();
    },

    methods: {
        showOrderModal(order) {
            this.selectedOrder = order;
            this.isModalOpen = true;
        },

        editOrderModal(order) {
            this.selectedOrder = order;
            this.isEditOrderOpen = true;
        },

        deleteOrderModal(order) {
            this.selectedOrder = order;
            this.isDeleteOrderOpen = true;
        },

        closeModal() {
            this.isModalOpen = false;
            this.isEditOrderOpen = false;
            this.isAddOrderOpen = false;
            this.isDeleteOrderOpen = false;
            this.selectedOrder = {};
            this.message = '';
        },

        addOrderModal() {
            this.closeModal();
            this.isAddOrderOpen = true;
        },

        getOrders(){
            axios.get('/admin/orders/get-orders')
                .then(response => {
                    const newOrders = response.data;
                    // Detect new order
                    const newIds = newOrders.map(o => o.id);
                    if (this.lastOrderIds.length && newIds.length > this.lastOrderIds.length) {
                        // New order arrived
                        this.showToast('يوجد طلب جديد');
                    } else if (this.lastOrderIds.length && newIds[0] !== this.lastOrderIds[0]) {
                        // New order at the top
                        this.showToast('يوجد طلب جديد');
                    }
                    this.lastOrderIds = newIds;
                    this.localOrders = newOrders;
                })
                .catch(error => {
                    console.error(error);
                });
        },

        handleOrderCreated() {
            try {
                this.getOrders();
                this.message = 'تمت إضافة الطلب بنجاح';
                this.isAddOrderOpen = false;
                setTimeout(() => {
                    this.message = '';
                }, 3000);
            } catch (error) {
                this.message = 'حدث خطأ أثناء التحديث';
                console.error(error);
            }
        },

        handleOrderUpdated() {
            try {
                this.getOrders();
                this.message = 'تم تعديل الطلب بنجاح';
                this.isEditOrderOpen = false;
                setTimeout(() => {
                    this.message = '';
                }, 3000);
            } catch (error) {
                this.message = 'حدث خطأ أثناء التحديث';
                console.error(error);
            }
        },

        handleOrderDeleted() {
            try {
                this.getOrders();
                this.message = 'تم حذف الطلب بنجاح';
                this.isDeleteOrderOpen = false;
                setTimeout(() => {
                    this.message = '';
                }, 3000);
            } catch (error) {
                this.message = 'حدث خطأ أثناء التحديث';
                console.error(error);
            }
        },

        getStatusColor(status) {
            switch(status) {
                case 'pending': return 'color: #ffc107;';
                case 'processing': return 'color: #007bff;';
                case 'completed': return 'color: #28a745;';
                case 'cancelled': return 'color: #dc3545;';
                default: return 'color: #6c757d;';
            }
        },

        getStatusText(status) {
            switch(status) {
                case 'pending': return 'معلق';
                case 'processing': return 'قيد المعالجة';
                case 'completed': return 'مكتمل';
                case 'cancelled': return 'ملغي';
                default: return status;
            }
        },
        startPolling() {
            this.pollingInterval = setInterval(() => {
                this.getOrders();
            }, 10000); // 10 seconds
        },
        stopPolling() {
            if (this.pollingInterval) {
                clearInterval(this.pollingInterval);
                this.pollingInterval = null;
            }
        },
        showToast(msg) {
            this.toast.message = msg;
            this.toast.show = true;
            setTimeout(() => {
                this.toast.show = false;
            }, 4000);
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
    <Head title="الطلبات" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 text-right">
                قائمة الطلبات
            </h2>
        </template>

        <div v-if="toast.show" class="fixed top-6 right-6 z-50 bg-green-600 text-white px-6 py-3 rounded shadow-lg animate-bounce text-lg">
            {{ toast.message }}
        </div>
        <div v-if="message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
            {{ message }}
        </div>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <!-- Filters and Add Button Row -->
                <div class="flex flex-wrap items-center justify-between mb-6 gap-4">
                    <!-- Add New Order Button -->
                    <div class="float-right mb-2">
                        <button
                            type="button"
                            @click="addOrderModal()"
                            class="pl-3 text-blue-700 text-lg hover:text-gray-500"
                            style="font-size: 22px"
                        >
                            اضافة طلب
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                    <!-- Filters -->
                    <div class="flex flex-wrap gap-4 items-center">
                        <div>
                            <label class="block mb-1 text-gray-700">الحالة:</label>
                            <select v-model="statusFilter" class="border rounded px-3 py-2 text-right" style="direction: rtl; appearance: none; background-position: left 0.5rem center; background-repeat: no-repeat; background-image: url('data:image/svg+xml;utf8,<svg fill=\'none\' stroke=\'%23333\' stroke-width=\'2\' viewBox=\'0 0 24 24\' xmlns=\'http://www.w3.org/2000/svg\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M19 9l-7 7-7-7\'></path></svg>');">
                                <option value="all">الكل</option>
                                <option value="pending">معلق</option>
                                <option value="processing">قيد المعالجة</option>
                                <option value="completed">مكتمل</option>
                                <option value="cancelled">ملغي</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 text-gray-700">تاريخ الطلب:</label>
                            <input type="date" v-model="dateFrom" class="border rounded px-3 py-2" />
                        </div>
                    </div>
                </div>
                <table style="direction: rtl">
                    <thead>
                        <tr class="text-right">
                            <th>#</th>
                            <th>الاسم</th>
                            <th>رقم الهاتف</th>
                            <th>العنوان</th>
                            <th>المجموع</th>
                            <th>الحالة</th>
                            <th>تاريخ الطلب</th>
                            <th>الاجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="order in filteredOrders" :key="order.id" class="text-right">
                            <td>{{ order.id }}</td>
                            <td>{{ order.name }}</td>
                            <td>{{ order.phone }}</td>
                            <td>{{ order.address }}</td>
                            <td>{{ order.total }} ريال</td>
                            <td :style="getStatusColor(order.status)">
                                {{ getStatusText(order.status) }}
                            </td>
                            <td>{{ order.created_at ? formatDate(order.created_at) : '' }}</td>
                            <td>
                                <button type="button" @click="showOrderModal(order)" class="pl-3 text-green-500 text-lg hover:text-gray-500">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button type="button" @click="editOrderModal(order)" class="pl-3 text-blue-500 text-lg hover:text-gray-500">
                                    <i class="fa-solid fa-pencil"></i>
                                </button>
                                <button type="button" @click="deleteOrderModal(order)" class="pl-3 text-red-500 text-lg hover:text-gray-500">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>

    <ShowOrderModal
        :show="isModalOpen"
        :order="selectedOrder"
        @close="closeModal"
    />
    <AddOrderModal
        :show="isAddOrderOpen"
        @close="closeModal"
        @created="handleOrderCreated"
    />
    <EditOrderModal
        :show="isEditOrderOpen"
        :order="selectedOrder"
        @close="closeModal"
        @updated="handleOrderUpdated"
    />
    <DeleteOrderModal
        :show="isDeleteOrderOpen"
        :order="selectedOrder"
        @close="closeModal"
        @deleted="handleOrderDeleted"
    />
</template>

<style scoped>
table {
    width: 100%;
    border-collapse: collapse;
}

.alert {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 0.375rem;
    text-align: center;
}

.alert-success {
    background-color: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
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
