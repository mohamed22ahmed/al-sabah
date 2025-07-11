<script>
import Modal from '@/Components/Modal.vue';
import axios from 'axios';

export default {
    components: { Modal },
    props: {
        show: Boolean,
        order: Object
    },
    emits: ['close', 'deleted'],
    data() {
        return {
            isDeleting: false
        };
    },
    methods: {
        async confirmDelete() {
            this.isDeleting = true;
            try {
                await axios.delete(`/admin/orders/delete/${this.order.id}`);
                this.$emit('deleted', this.order.id);
                this.reset();
            } catch (error) {
                console.error('Error deleting order:', error);
            } finally {
                this.isDeleting = false;
            }
        },
        close() {
            this.$emit('close');
            this.reset();
        },
        reset() {
            this.isDeleting = false;
        }
    }
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')" max-width="md">
        <div class="max-h-[90vh] overflow-hidden flex flex-col" style="direction: rtl;">
            <!-- Header -->
            <div class="flex justify-between items-center p-4 border-b bg-gray-50">
                <h5 class="text-lg font-bold">حذف الطلب</h5>
                <button type="button" class="text-gray-500 hover:text-red-600 text-2xl" @click="$emit('close')">&times;</button>
            </div>

            <!-- Content -->
            <div class="flex-1 p-4">
                <div class="text-center">
                    <p class="text-gray-700 mb-4 text-sm">
                        هل أنت متأكد من حذف الطلب رقم
                        <strong>#{{ order.id }}</strong> للعميل
                        <strong>{{ order.name }}</strong>؟
                    </p>
                    <p class="text-red-600 text-sm mb-4">
                        هذا الإجراء لا يمكن التراجع عنه.
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-4 border-t bg-gray-50 flex justify-end space-x-3 space-x-reverse">
                <button
                    type="button"
                    @click="$emit('close')"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm"
                >
                    إلغاء
                </button>
                <button
                    type="button"
                    @click="confirmDelete"
                    :disabled="isDeleting"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm disabled:opacity-50"
                >
                    {{ isDeleting ? 'جاري الحذف...' : 'حذف الطلب' }}
                </button>
            </div>
        </div>
    </Modal>
</template>
