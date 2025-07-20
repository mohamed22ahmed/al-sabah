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
            imagePreview: null,
            errors: {},
            loading: false,
        };
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
                const response = await axios.post('/admin/products/store', formData, {
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
                category_id: '',
                name: '',
                description: '',
                sold: 5,
                price: '',
                discount_price: '',
                weight: '',
                quantity: '',
                image: null,
            };
            this.errors = {};
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

    emits: ["close", "created"],
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6" style="direction: rtl;">
            <div class="flex justify-between items-center mb-4">
                <h5 class="text-lg font-bold">إضافة منتج جديد</h5>
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

                    <!-- Discount Price -->
                    <div>
                        <InputLabel for="discount_price" value="السعر بعد الخصم" />
                        <TextInput
                            id="discount_price"
                            v-model="form.discount_price"
                            type="number"
                            step="0.01"
                            class="mt-1 block w-full"
                            :class="{ 'border-red-500': errors.discount_price }"
                        />
                        <InputError :message="errors.discount_price" class="mt-2" />
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

                    <!-- HTML Formatting Tools -->
                    <div class="mb-2 flex flex-wrap gap-2">
                        <button
                            type="button"
                            @click="insertHTML('<strong>', '</strong>')"
                            class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm font-bold"
                            title="Bold"
                        >
                            B
                        </button>
                        <button
                            type="button"
                            @click="insertHTML('<em>', '</em>')"
                            class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm italic"
                            title="Italic"
                        >
                            I
                        </button>
                        <button
                            type="button"
                            @click="insertHTML('<ul><li>', '</li></ul>')"
                            class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm"
                            title="Unordered List"
                        >
                            • List
                        </button>
                        <button
                            type="button"
                            @click="insertHTML('<ol><li>', '</li></ol>')"
                            class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm"
                            title="Ordered List"
                        >
                            1. List
                        </button>
                        <button
                            type="button"
                            @click="insertHTML('<li>', '</li>')"
                            class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm"
                            title="List Item"
                        >
                            • Item
                        </button>
                        <button
                            type="button"
                            @click="insertHTML('<br>', '')"
                            class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm"
                            title="Line Break"
                        >
                            ↵
                        </button>
                    </div>

                    <textarea
                        id="description"
                        v-model="form.description"
                        rows="6"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                        :class="{ 'border-red-500': errors.description }"
                        placeholder="اكتب وصف المنتج هنا... يمكنك استخدام HTML للتنسيق"
                        @paste="handlePaste"
                    ></textarea>

                    <!-- HTML Preview -->
                    <div v-if="form.description" class="mt-2 p-3 bg-gray-50 rounded border">
                        <div class="text-sm text-gray-600 mb-1">معاينة:</div>
                        <div class="text-sm" v-html="form.description"></div>
                    </div>

                    <InputError :message="errors.description" class="mt-2" />
                </div>

                <!-- Image -->
                <div class="mt-4 text-right">
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
                    <div v-if="imagePreview" class="mt-2 flex justify-start">
                        <img :src="imagePreview" alt="صورة القسم" class="max-h-32 rounded shadow" />
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
                        {{ loading ? 'جاري الحفظ...' : 'حفظ المنتج' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
