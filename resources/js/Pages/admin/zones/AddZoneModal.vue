<script>
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from 'axios';

export default {
    components: {
        Modal,
        InputLabel,
        TextInput,
        InputError,
        PrimaryButton,
    },

    props: {
        show: {
            type: Boolean,
            default: false,
        },
    },

    data() {
        return {
            form: {
                name: '',
                name_ar: ''
            },
            errors: {},
            loading: false,
        };
    },

    methods: {
        async submit() {
            this.loading = true;
            this.errors = {};

            const formData = new FormData();
            formData.append('name', this.form.name);
            formData.append('name_ar', this.form.name_ar);

            try {
                const response = await axios.post('/admin/zones/store', formData);

                this.$emit('created');
                this.resetForm();
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                }
            } finally {
                this.loading = false;
            }
        },

        resetForm() {
            this.form = {
                name: '',
                name_ar: ''
            };
            this.errors = {};
        }
    },

    emits: ["close", "created"],
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6" style="direction: rtl;">
            <div class="flex justify-between items-center mb-4">
                <h5 class="text-lg font-bold">إضافة منطقة جديدة</h5>
                <button type="button" class="text-gray-500 hover:text-red-600 text-2xl" @click="$emit('close')">&times;</button>
            </div>
            <hr class="my-4">

            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <InputLabel for="name" value="اسم المنطقة" />
                        <TextInput
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="mt-1 block w-full"
                            :class="{ 'border-red-500': errors.name }"
                        />
                        <InputError :message="errors.name" class="mt-2" />
                    </div>
                    <div>
                        <InputLabel for="name" value="اسم المنطقة بالعربى" />
                        <TextInput
                            id="name_ar"
                            v-model="form.name_ar"
                            type="text"
                            class="mt-1 block w-full"
                            :class="{ 'border-red-500': errors.name_ar }"
                        />
                        <InputError :message="errors.name_ar" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3 space-x-reverse">
                    <button
                        type="button"
                        @click="$emit('close')"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                    >
                        إلغاء
                    </button>
                    <PrimaryButton :disabled="loading" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ loading ? 'جاري الحفظ...' : 'حفظ المنطقة' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
