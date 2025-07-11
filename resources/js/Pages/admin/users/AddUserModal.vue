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
        show: Boolean
    },
    emits: ['close', 'created'],
    data() {
        return {
            form: {
                name: '',
                email: '',
                phone: '',
                password: ''
            },
            errors: {},
            loading: false
        };
    },
    methods: {
        async submit() {
            this.loading = true;
            this.errors = {};

            // Validation
            if (!this.form.name) this.errors.name = 'الاسم مطلوب';
            if (!this.form.email) this.errors.email = 'البريد الإلكتروني مطلوب';
            if (!this.form.phone) this.errors.phone = 'رقم الهاتف مطلوب';
            if (!this.form.password) this.errors.password = 'كلمة المرور مطلوبة';

            if (Object.keys(this.errors).length > 0) {
                this.loading = false;
                return;
            }

            try {
                await axios.post('/admin/users/store', this.form);
                this.$emit('created');
                this.reset();
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                }
            } finally {
                this.loading = false;
            }
        },

        close() {
            this.$emit('close');
            this.reset();
        },

        reset() {
            this.form = {
                name: '',
                email: '',
                phone: '',
                password: ''
            };
            this.errors = {};
            this.loading = false;
        }
    }
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6" style="direction: rtl;">
            <div class="flex justify-between items-center mb-4">
                <h5 class="text-lg font-bold">إضافة مستخدم جديد</h5>
                <button type="button" class="text-gray-500 hover:text-red-600 text-2xl" @click="$emit('close')">&times;</button>
            </div>
            <hr class="my-4">

            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Name -->
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

                    <!-- Email -->
                    <div>
                        <InputLabel for="email" value="البريد الإلكتروني" />
                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="mt-1 block w-full"
                            :class="{ 'border-red-500': errors.email }"
                        />
                        <InputError :message="errors.email" class="mt-2" />
                    </div>

                    <!-- Phone -->
                    <div>
                        <InputLabel for="phone" value="رقم الهاتف" />
                        <TextInput
                            id="phone"
                            v-model="form.phone"
                            type="tel"
                            class="mt-1 block w-full"
                            :class="{ 'border-red-500': errors.phone }"
                        />
                        <InputError :message="errors.phone" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <InputLabel for="password" value="كلمة المرور" />
                        <TextInput
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-full"
                            :class="{ 'border-red-500': errors.password }"
                        />
                        <InputError :message="errors.password" class="mt-2" />
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
                        {{ loading ? 'جاري الحفظ...' : 'حفظ المستخدم' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
