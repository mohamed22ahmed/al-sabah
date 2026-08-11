<script>
import Modal from '@/Components/Modal.vue';
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
        show: Boolean,
        zone: Object
    },
    emits: ['close', 'updated'],
    data() {
        return {
            form: {
                id: null,
                name: '',
                name_ar: '',
            },
            errors: {}
        };
    },
    watch: {
        zone: {
            handler(newZone) {
                if (newZone) {
                    this.form.id = newZone.id;
                    this.form.name = newZone.name;
                    this.form.name_ar = newZone.name_ar;
                }
            },
            immediate: true,
            deep: true
        }
    },

    methods: {
        async submit() {
            this.errors = {};

            try {
                await axios.post(`/admin/zones/update/${this.zone.id}`, this.form);
                this.$emit('updated');
                this.resetForm();
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                }
            }
        },

        resetForm() {
            this.form = {
                name: '',
                name_ar: ''
            };
            this.errors = {};
        }
    }
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6" style="direction: rtl;">
            <div class="flex justify-between items-center mb-4">
                <h5 class="text-lg font-bold">تعديل المنطقة</h5>
                <button type="button" class="text-gray-500 hover:text-red-600 text-2xl" @click="$emit('close')">&times;</button>
            </div>
            <hr class="my-4">

            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <InputLabel for="name" value="الاسم" />
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
                        <InputLabel for="name_ar" value="الاسم بالعربى" />
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

                    <PrimaryButton class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تحديث المنطقة</PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
