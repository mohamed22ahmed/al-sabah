<script>
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from 'axios';

export default {
    components: {
        Modal,
        InputLabel,
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
                show: true,
                image: null,
            },
            errors: {},
            loading: false,
            imagePreview: null,
        };
    },

    methods: {
        handleImageChange(event) {
            const file = event.target.files[0];
            this.form.image = file;
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                this.imagePreview = null;
            }
        },

        async submit() {
            this.loading = true;
            this.errors = {};

            const formData = new FormData();
            formData.append('show', this.form.show ? 1 : 0);

            if (this.form.image) {
                formData.append('image', this.form.image);
            }

            try {
                const response = await axios.post('/admin/banners/store', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });

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
                show: true,
                image: null,
            };
            this.errors = {};
            this.imagePreview = null;
        },
    },

    emits: ["close", "created"],
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6" style="direction: rtl;">
            <div class="flex justify-between items-center mb-4">
                <h5 class="text-lg font-bold">إضافة بانر جديد</h5>
                <button type="button" class="text-gray-500 hover:text-red-600 text-2xl" @click="$emit('close')">&times;</button>
            </div>
            <hr class="my-4">

            <form @submit.prevent="submit">
                <!-- Show Status -->
                <div class="mb-4">
                    <InputLabel for="show" value="الحالة" />
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input
                                type="checkbox"
                                v-model="form.show"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            />
                            <span class="mr-2 text-gray-700">تفعيل البانر</span>
                        </label>
                    </div>
                    <InputError :message="errors.show" class="mt-2" />
                </div>

                <!-- Image -->
                <div class="mb-4 text-right">
                    <InputLabel for="image" value="صورة البانر" />
                    <input
                        id="image"
                        type="file"
                        @change="handleImageChange"
                        accept="image/*"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        :class="{ 'border-red-500': errors.image }"
                    />
                    <InputError :message="errors.image" class="mt-2" />
                    <div v-if="imagePreview" class="mt-2 flex justify-start">
                        <img :src="imagePreview" alt="صورة البانر" class="max-h-32 rounded shadow" />
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
                        {{ loading ? 'جاري الحفظ...' : 'حفظ البانر' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
