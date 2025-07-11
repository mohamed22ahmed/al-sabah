<script>
import Modal from '@/Components/Modal.vue';
import axios from 'axios';

export default {
    components: { Modal },
    props: {
        show: Boolean,
        user: Object
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
                await axios.delete(`/admin/users/delete/${this.user.id}`);
                this.$emit('deleted', this.user.id);
                this.reset();
            } catch (error) {
                console.error('Error deleting user:', error);
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
    <Modal :show="show" @close="$emit('close')">
        <div class="p-4 max-h-[80vh] overflow-auto mx-4 my-6" style="direction: rtl;">
            <div class="flex justify-between items-center mb-3">
                <h5 class="text-lg font-bold">حذف المستخدم</h5>
                <button type="button" class="text-gray-500 hover:text-red-600 text-2xl" @click="$emit('close')">&times;</button>
            </div>
            <hr class="my-3">

            <div class="text-center">
                <p class="text-gray-700 mb-4 text-sm">
                    هل أنت متأكد من حذف المستخدم
                    <strong>{{ user.name }}</strong>؟
                </p>
                <p class="text-red-600 text-sm mb-4">
                    هذا الإجراء لا يمكن التراجع عنه.
                </p>
            </div>

            <div class="flex justify-end space-x-3 space-x-reverse">
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
                    {{ isDeleting ? 'جاري الحذف...' : 'حذف المستخدم' }}
                </button>
            </div>
        </div>
    </Modal>
</template>
