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
        banner: {
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
        async deleteBanner() {
            this.loading = true;

            try {
                const response = await axios.delete(`/admin/banners/delete/${this.banner.id}`);
                this.$emit('deleted', this.banner.id);
            } catch (error) {
                console.error('Error deleting banner:', error);
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
                <h5 class="text-lg font-bold">حذف البانر</h5>
                <button type="button" class="text-gray-500 hover:text-red-600 text-2xl" @click="$emit('close')">&times;</button>
            </div>
            <hr class="my-4">

            <div class="mb-6">
                <p class="text-gray-700 mb-4">
                    هل أنت متأكد من حذف البانر التالي؟
                </p>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center space-x-4 space-x-reverse">
                        <img
                            :src="banner.image"
                            :alt="'Banner ' + banner.id"
                            class="w-20 h-20 object-cover rounded"
                        />
                        <div>
                            <h3 class="font-semibold text-gray-900">البانر رقم: {{ banner.id }}</h3>
                            <p class="text-gray-600 text-sm">
                                الحالة:
                                <span :class="banner.show ? 'text-green-600' : 'text-red-600'">
                                    {{ banner.show ? 'مفعل' : 'معطل' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
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
                    @click="deleteBanner"
                    :disabled="loading"
                    class="bg-red-600 hover:bg-red-700"
                >
                    {{ loading ? 'جاري الحذف...' : 'حذف البانر' }}
                </PrimaryButton>
            </div>
        </div>
    </Modal>
</template>
