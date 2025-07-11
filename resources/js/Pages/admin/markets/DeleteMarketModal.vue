<script>
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from 'axios';

export default {
    components: {
        Modal,
        PrimaryButton,
    },

    props: {
        show: {
            type: Boolean,
            default: false,
        },
        market: {
            type: Object,
            default: () => ({}),
        },
    },

    data() {
        return {
            loading: false,
        };
    },

    methods: {
        async deleteMarket() {
            this.loading = true;

            try {
                const response = await axios.delete(`/admin/markets/delete/${this.market.id}`);
                this.$emit('deleted', this.market.id);
            } catch (error) {
                console.error('Error deleting market:', error);
            } finally {
                this.loading = false;
            }
        },
    },

    emits: ["close", "deleted"],
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6" style="direction: rtl;">
            <div class="flex justify-between items-center mb-4">
                <h5 class="text-lg font-bold">حذف المعرض</h5>
                <button type="button" class="text-gray-500 hover:text-red-600 text-2xl" @click="$emit('close')">&times;</button>
            </div>
            <hr class="my-4">

            <div class="mb-6">
                <p class="text-gray-700 mb-4">
                    هل أنت متأكد من حذف المعرض التالي؟
                </p>
            </div>

            <div class="flex justify-end space-x-3 space-x-reverse">
                <button
                    type="button"
                    @click="$emit('close')"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                    :disabled="loading"
                >
                    إلغاء
                </button>
                <PrimaryButton
                    @click="deleteMarket"
                    :disabled="loading"
                    class="bg-red-600 hover:bg-red-700"
                >
                    {{ loading ? 'جاري الحذف...' : 'حذف المعرض' }}
                </PrimaryButton>
            </div>
        </div>
    </Modal>
</template>
