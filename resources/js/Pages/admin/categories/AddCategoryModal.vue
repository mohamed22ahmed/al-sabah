<script>
import Modal from '@/Components/Modal.vue';
import axios from 'axios';
export default {
    components: { Modal },
    props: {
        show: Boolean
    },
    emits: ['close', 'created'],
    data() {
        return {
            form: {
                name: '',
                url: '',
                image: null
            },
            imagePreview: null,
            errors: {}
        };
    },
    methods: {
        handleFileChange(e) {
            const file = e.target.files[0];
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

        submit() {
            this.errors = {};
            if (!this.form.name) this.errors.name = 'الاسم مطلوب';
            if (!this.form.url) this.errors.url = 'الرابط مطلوب';
            if (!this.form.image) this.errors.image = 'الصورة مطلوبة';
            if (Object.keys(this.errors).length) return;
            const formData = new FormData();
            formData.append('id', this.form.id);
            formData.append('name', this.form.name);
            formData.append('url', this.form.url);
            formData.append('image', this.form.image);
            axios.post('categories/store', formData).then(() => {
                this.$emit('created', { ...this.form });
                this.reset();
            });
        },

        close() {
            this.$emit('close');
            this.reset();
        },

        reset() {
            this.form = { name: '', url: '', image: null };
            this.imagePreview = null;
            this.errors = {};
        }
    }
};
</script>

<template>
    <Modal :show="show" @close="close" maxWidth="md">
        <form @submit.prevent="submit" class="p-6 rtl text-right">
            <div class="flex justify-between items-center mb-4">
                <button type="button" class="text-gray-500 hover:text-red-600 text-2xl" @click="close">&times;</button>
                <h5 class="text-lg font-bold">إضافة قسم جديد</h5>
            </div>
            <hr class="my-4">
            <div class="mb-4 mt-4">
                <label class="block mb-1 font-bold">اسم القسم <span class="text-red-500">*</span></label>
                <input type="text" v-model="form.name" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring text-right" />
                <div v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</div>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-bold">الرابط <span class="text-red-500">*</span></label>
                <input type="text" v-model="form.url" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring text-right" />
                <div v-if="errors.url" class="text-red-500 text-sm mt-1">{{ errors.url }}</div>
            </div>
            <div class="mb-4 text-right">
                <label class="block mb-1 font-bold">الصورة <span class="text-red-500">*</span></label>
                <div class="flex flex-row-reverse items-center gap-2">
                    <input type="file" @change="handleFileChange" accept="image/*" class="w-full file:mr-2 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 text-right" style="direction: rtl;" />
                </div>
                <div v-if="errors.image" class="text-red-500 text-sm mt-1">{{ errors.image }}</div>
                <div v-if="imagePreview" class="mt-2 flex justify-end">
                    <img :src="imagePreview" alt="صورة القسم" class="max-h-32 rounded shadow" />
                </div>
            </div>
            <hr class="my-4">
            <div class="mt-6 flex justify-center gap-2">
                <button type="button" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400" @click="close">إلغاء</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">إضافة</button>
            </div>
        </form>
    </Modal>
</template>
