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
        product: {
            type: Object,
            default: () => ({}),
        },
    },

    data() {
        return {
            form: {
                category_id: '',
                name: '',
                description: '',
                sold: 5,
                price: '',
                weight: '',
                quantity: '',
                image: null,
            },
            categories: [],
            errors: {},
            loading: false,
            imagePreview: null,
        };
    },

    watch: {
        product: {
            handler(newProduct) {
                if (newProduct && Object.keys(newProduct).length > 0) {
                    this.populateForm();
                }
            },
            immediate: true,
        },
    },

    mounted() {
        this.getCategories();
    },

    methods: {
        getCategories() {
            axios.get('/admin/categories/get-categories')
                .then(response => {
                    this.categories = response.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },

        populateForm() {
            this.form = {
                category_id: this.product.category.id || '',
                name: this.product.name || '',
                description: this.product.description || '',
                sold: this.product.sold || 5,
                price: this.product.price || '',
                weight: this.product.weight || '',
                quantity: this.product.quantity || '',
                image: null,
            };
            this.imagePreview = this.product.image;
        },

        handleImageChange(event) {
            this.form.image = event.target.files[0];
            if (this.form.image) {
                this.imagePreview = URL.createObjectURL(this.form.image);
            }
        },

        async submit() {
            this.loading = true;
            this.errors = {};

            const formData = new FormData();
            formData.append('category_id', this.form.category_id);
            formData.append('name', this.form.name);
            formData.append('description', this.form.description);
            formData.append('sold', this.form.sold);
            formData.append('price', this.form.price);
            formData.append('weight', this.form.weight);
            formData.append('quantity', this.form.quantity);

            if (this.form.image) {
                formData.append('image', this.form.image);
            }

            try {
                const response = await axios.post(`/admin/products/update/${this.product.id}`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });

                this.$emit('updated', this.product);
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                }
            } finally {
                this.loading = false;
            }
        },
    },

    emits: ["close", "updated"],
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6" style="direction: rtl;">
            <div class="flex justify-between items-center mb-4">
                <h5 class="text-lg font-bold">تعديل المنتج</h5>
                <button type="button" class="text-gray-500 hover:text-red-600 text-2xl" @click="$emit('close')">&times;</button>
            </div>
            <hr class="my-4">

            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Category -->
                    <div>
                        <InputLabel for="category_id" value="القسم" />
                        <select
                            id="category_id"
                            v-model="form.category_id"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                            :class="{ 'border-red-500': errors.category_id }"
                        >
                            <option value="">اختر القسم</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                        <InputError :message="errors.category_id" class="mt-2" />
                    </div>

                    <!-- Name -->
                    <div>
                        <InputLabel for="name" value="اسم المنتج" />
                        <TextInput
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="mt-1 block w-full"
                            :class="{ 'border-red-500': errors.name }"
                        />
                        <InputError :message="errors.name" class="mt-2" />
                    </div>

                    <!-- Price -->
                    <div>
                        <InputLabel for="price" value="السعر" />
                        <TextInput
                            id="price"
                            v-model="form.price"
                            type="number"
                            step="0.01"
                            class="mt-1 block w-full"
                            :class="{ 'border-red-500': errors.price }"
                        />
                        <InputError :message="errors.price" class="mt-2" />
                    </div>

                    <!-- Weight -->
                    <div>
                        <InputLabel for="weight" value="الوزن (كجم)" />
                        <TextInput
                            id="weight"
                            v-model="form.weight"
                            type="number"
                            step="0.01"
                            class="mt-1 block w-full"
                            :class="{ 'border-red-500': errors.weight }"
                        />
                        <InputError :message="errors.weight" class="mt-2" />
                    </div>

                    <!-- Quantity -->
                    <div>
                        <InputLabel for="quantity" value="الكمية" />
                        <TextInput
                            id="quantity"
                            v-model="form.quantity"
                            type="number"
                            class="mt-1 block w-full"
                            :class="{ 'border-red-500': errors.quantity }"
                        />
                        <InputError :message="errors.quantity" class="mt-2" />
                    </div>

                    <!-- Sold -->
                    <div>
                        <InputLabel for="sold" value="المباع" />
                        <TextInput
                            id="sold"
                            v-model="form.sold"
                            type="number"
                            class="mt-1 block w-full"
                            :class="{ 'border-red-500': errors.sold }"
                        />
                        <InputError :message="errors.sold" class="mt-2" />
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-4">
                    <InputLabel for="description" value="الوصف" />
                    <textarea
                        id="description"
                        v-model="form.description"
                        rows="4"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                        :class="{ 'border-red-500': errors.description }"
                    ></textarea>
                    <InputError :message="errors.description" class="mt-2" />
                </div>

                <!-- Image -->
                <div class="mt-4">
                    <InputLabel for="image" value="صورة المنتج" />
                    <input
                        id="image"
                        type="file"
                        @change="handleImageChange"
                        accept="image/*"
                        class="mt-1 block w-full"
                        :class="{ 'border-red-500': errors.image }"
                    />
                    <InputError :message="errors.image" class="mt-2" />

                    <!-- Image Preview -->
                    <div v-if="imagePreview" class="mt-2">
                        <img
                            :src="imagePreview"
                            alt="Product Preview"
                            class="max-w-xs h-auto rounded-lg shadow-md"
                        />
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

                    <PrimaryButton :disabled="loading" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        {{ loading ? 'جاري التحديث...' : 'تحديث المنتج' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
