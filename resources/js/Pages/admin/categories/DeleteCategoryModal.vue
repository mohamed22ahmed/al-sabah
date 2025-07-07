<script>
import Modal from '@/Components/Modal.vue';
import axios from 'axios';

export default {
    components: { Modal },
    props: {
        show: Boolean,
        category: Object
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
                await axios.delete(`categories/delete/${this.category.id}`);
                this.$emit('deleted', this.category.id);
                this.reset();
            } catch (error) {
                console.error('Error deleting category:', error);
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
    <Modal :show="show" @close="close" maxWidth="md">
        <div class="p-6 rtl text-right">
            <div class="flex justify-between items-center mb-4">
                <button type="button" class="text-gray-500 hover:text-red-600 text-2xl" @click="close">&times;</button>
                <h5 class="text-lg font-bold text-red-600">حذف القسم</h5>
            </div>
            <hr class="my-4">
            <div class="mb-6">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                </div>
                <h6 class="text-lg font-semibold text-center mb-2">هل أنت متأكد من حذف هذا القسم؟</h6>
                <p class="text-gray-600 text-center mb-4">لا يمكن التراجع عن هذا الإجراء بعد الحذف.</p>
            </div>
            <hr class="my-4">
            <div class="flex justify-center gap-3">
                <button
                    type="button"
                    class="bg-gray-300 text-gray-800 px-6 py-2 rounded hover:bg-gray-400 transition-colors"
                    @click="close"
                    :disabled="isDeleting"
                >
                    إلغاء
                </button>
                <button
                    type="button"
                    class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 transition-colors flex items-center gap-2"
                    @click="confirmDelete"
                    :disabled="isDeleting"
                >
                    <i v-if="isDeleting" class="fa-solid fa-spinner fa-spin"></i>
                    {{ isDeleting ? 'جاري الحذف...' : 'حذف القسم' }}
                </button>
            </div>
        </div>
    </Modal>
</template>
