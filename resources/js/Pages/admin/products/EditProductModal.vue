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
                discount_price: '',
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
                discount_price: this.product.discount_price || '',
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
            formData.append('discount_price', this.form.discount_price);
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

        insertHTML(openTag, closeTag) {
            const textarea = this.$el.querySelector('#description');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const selectedText = this.form.description.substring(start, end);

            const newText = this.form.description.substring(0, start) +
                           openTag + selectedText + closeTag +
                           this.form.description.substring(end);

            this.form.description = newText;

            // Set cursor position after the inserted content
            this.$nextTick(() => {
                const newCursorPos = start + openTag.length + selectedText.length + closeTag.length;
                textarea.setSelectionRange(newCursorPos, newCursorPos);
                textarea.focus();
            });
        },

        handlePaste(event) {
            event.preventDefault();

            // Try to get HTML content first
            let htmlContent = (event.clipboardData || window.clipboardData).getData('text/html');
            let plainText = (event.clipboardData || window.clipboardData).getData('text');

            // If HTML content exists and contains formatting, use it
            if (htmlContent && (htmlContent.includes('<strong>') || htmlContent.includes('<b>') ||
                               htmlContent.includes('<em>') || htmlContent.includes('<i>') ||
                               htmlContent.includes('<ul>') || htmlContent.includes('<ol>') ||
                               htmlContent.includes('<li>'))) {

                // Clean up the HTML (remove unnecessary tags but keep formatting)
                htmlContent = htmlContent
                    .replace(/<div>/g, '<br>')
                    .replace(/<\/div>/g, '')
                    .replace(/<p>/g, '')
                    .replace(/<\/p>/g, '<br>')
                    .replace(/<br\s*\/?>/g, '<br>')
                    .replace(/<b>/g, '<strong>')
                    .replace(/<\/b>/g, '</strong>')
                    .replace(/<i>/g, '<em>')
                    .replace(/<\/i>/g, '</em>')
                    .replace(/\n/g, '<br>');

                // Insert the HTML content
                const start = event.target.selectionStart;
                const end = event.target.selectionEnd;
                const newText = this.form.description.substring(0, start) +
                               htmlContent +
                               this.form.description.substring(end);

                this.form.description = newText;

                // Set cursor position after the pasted content
                this.$nextTick(() => {
                    const newCursorPos = start + htmlContent.length;
                    event.target.setSelectionRange(newCursorPos, newCursorPos);
                    event.target.focus();
                });
            } else {
                // Use plain text if no HTML formatting
                const start = event.target.selectionStart;
                const end = event.target.selectionEnd;
                const newText = this.form.description.substring(0, start) +
                               plainText +
                               this.form.description.substring(end);

                this.form.description = newText;

                // Set cursor position after the pasted content
                this.$nextTick(() => {
                    const newCursorPos = start + plainText.length;
                    event.target.setSelectionRange(newCursorPos, newCursorPos);
                    event.target.focus();
                });
            }
        }
    },

    emits: ["close", "updated"],
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-4 max-h-[80vh] overflow-auto mx-4 my-6" style="direction: rtl;">
            <div class="flex justify-between items-center mb-3">
                <h5 class="text-lg font-bold">تعديل المنتج</h5>
                <button type="button" class="text-gray-500 hover:text-red-600 text-2xl" @click="$emit('close')">&times;</button>
            </div>
            <hr class="my-3">

            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <!-- Category -->
                    <div>
                        <InputLabel for="category_id" value="القسم" class="text-sm" />
                        <select
                            id="category_id"
                            v-model="form.category_id"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full text-sm py-1"
                            :class="{ 'border-red-500': errors.category_id }"
                        >
                            <option value="">اختر القسم</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                        <InputError :message="errors.category_id" class="mt-1" />
                    </div>

                    <!-- Name -->
                    <div>
                        <InputLabel for="name" value="اسم المنتج" class="text-sm" />
                        <TextInput
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="mt-1 block w-full text-sm py-1"
                            :class="{ 'border-red-500': errors.name }"
                        />
                        <InputError :message="errors.name" class="mt-1" />
                    </div>

                    <!-- Price -->
                    <div>
                        <InputLabel for="price" value="السعر" class="text-sm" />
                        <TextInput
                            id="price"
                            v-model="form.price"
                            type="number"
                            step="0.01"
                            class="mt-1 block w-full text-sm py-1"
                            :class="{ 'border-red-500': errors.price }"
                        />
                        <InputError :message="errors.price" class="mt-1" />
                    </div>
                    <div>
                        <InputLabel for="discount_price" value="السعر بعد الخصم" class="text-sm" />
                        <TextInput
                            id="discount_price"
                            v-model="form.discount_price"
                            type="number"
                            step="0.01"
                            class="mt-1 block w-full text-sm py-1"
                            :class="{ 'border-red-500': errors.discount_price }"
                        />
                        <InputError :message="errors.discount_price" class="mt-1" />
                    </div>

                    <!-- Weight -->
                    <div>
                        <InputLabel for="weight" value="الوزن (كجم)" class="text-sm" />
                        <TextInput
                            id="weight"
                            v-model="form.weight"
                            type="number"
                            step="0.01"
                            class="mt-1 block w-full text-sm py-1"
                            :class="{ 'border-red-500': errors.weight }"
                        />
                        <InputError :message="errors.weight" class="mt-1" />
                    </div>

                    <!-- Quantity -->
                    <div>
                        <InputLabel for="quantity" value="الكمية" class="text-sm" />
                        <TextInput
                            id="quantity"
                            v-model="form.quantity"
                            type="number"
                            class="mt-1 block w-full text-sm py-1"
                            :class="{ 'border-red-500': errors.quantity }"
                        />
                        <InputError :message="errors.quantity" class="mt-1" />
                    </div>

                    <!-- Sold -->
                    <div>
                        <InputLabel for="sold" value="المباع" class="text-sm" />
                        <TextInput
                            id="sold"
                            v-model="form.sold"
                            type="number"
                            class="mt-1 block w-full text-sm py-1"
                            :class="{ 'border-red-500': errors.sold }"
                        />
                        <InputError :message="errors.sold" class="mt-1" />
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-3">
                    <InputLabel for="description" value="الوصف" class="text-sm" />

                    <!-- HTML Formatting Tools -->
                    <div class="mb-2 flex flex-wrap gap-1">
                        <button
                            type="button"
                            @click="insertHTML('<strong>', '</strong>')"
                            class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded text-xs font-bold"
                            title="Bold"
                        >
                            B
                        </button>
                        <button
                            type="button"
                            @click="insertHTML('<em>', '</em>')"
                            class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded text-xs italic"
                            title="Italic"
                        >
                            I
                        </button>
                        <button
                            type="button"
                            @click="insertHTML('<ul><li>', '</li></ul>')"
                            class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded text-xs"
                            title="Unordered List"
                        >
                            • List
                        </button>
                        <button
                            type="button"
                            @click="insertHTML('<ol><li>', '</li></ol>')"
                            class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded text-xs"
                            title="Ordered List"
                        >
                            1. List
                        </button>
                        <button
                            type="button"
                            @click="insertHTML('<li>', '</li>')"
                            class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded text-xs"
                            title="List Item"
                        >
                            • Item
                        </button>
                        <button
                            type="button"
                            @click="insertHTML('<br>', '')"
                            class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded text-xs"
                            title="Line Break"
                        >
                            ↵
                        </button>
                    </div>

                    <textarea
                        id="description"
                        v-model="form.description"
                        rows="4"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full text-sm py-1"
                        :class="{ 'border-red-500': errors.description }"
                        placeholder="اكتب وصف المنتج هنا... يمكنك استخدام HTML للتنسيق"
                        @paste="handlePaste"
                    ></textarea>

                    <!-- HTML Preview -->
                    <div v-if="form.description" class="mt-2 p-2 bg-gray-50 rounded border">
                        <div class="text-xs text-gray-600 mb-1">معاينة:</div>
                        <div class="text-xs" v-html="form.description"></div>
                    </div>

                    <InputError :message="errors.description" class="mt-1" />
                </div>

                <!-- Image -->
                <div class="mt-3">
                    <InputLabel for="image" value="صورة المنتج" class="text-sm" />
                    <input
                        id="image"
                        type="file"
                        @change="handleImageChange"
                        accept="image/*"
                        class="mt-1 block w-full text-sm py-1"
                        :class="{ 'border-red-500': errors.image }"
                    />
                    <InputError :message="errors.image" class="mt-1" />

                    <!-- Image Preview -->
                    <div v-if="imagePreview" class="mt-2">
                        <img
                            :src="imagePreview"
                            alt="Product Preview"
                            class="max-w-32 h-auto rounded-lg shadow-md"
                        />
                    </div>
                </div>

                <div class="mt-4 flex justify-end space-x-3 space-x-reverse">
                    <button
                        type="button"
                        @click="$emit('close')"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm"
                    >
                        إلغاء
                    </button>

                    <PrimaryButton :disabled="loading" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                        {{ loading ? 'جاري التحديث...' : 'تحديث المنتج' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
