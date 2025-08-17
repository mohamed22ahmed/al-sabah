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
        order: Object
    },
    emits: ['close', 'updated'],
    data() {
        return {
            form: {
                name: '',
                phone: '',
                address: '',
                products: [],
                total: 0,
                status: 'pending'
            },
            errors: {},
            loading: false,
            products: []
        };
    },
    watch: {
        order: {
            handler(newOrder) {
                if (newOrder && Object.keys(newOrder).length > 0) {
                    this.populateForm();
                }
            },
            immediate: true,
        },
    },
    mounted() {
        this.getProducts();
    },
    methods: {
        getProducts() {
            axios.get('/admin/products/get-products')
                .then(response => {
                    this.products = response.data.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },

        populateForm() {
            this.form = {
                name: this.order.name || '',
                phone: this.order.phone || '',
                address: this.order.address || '',
                products: this.order.products ? JSON.parse(JSON.stringify(this.order.products)) : [],
                total: this.order.total || 0,
                status: this.order.status || 'pending'
            };

            // Set available quantities for existing products
            this.form.products.forEach((product, index) => {
                const prod = this.products.find(p => p.id == product.id);
                if (prod) {
                    this.form.products[index].name = product.name;
                }
            });
        },

        addProduct() {
            this.form.products.push({
                id: '',
                name: '',
                quantity: 1,
                price: 0
            });
        },

        removeProduct(index) {
            this.form.products.splice(index, 1);
            this.calculateTotal();
        },

        onProductChange(index) {
            const product = this.products.find(p => p.id == this.form.products[index].id);
            if (product) {
                this.form.products[index].name = product.name;
                this.form.products[index].price = product.price;
                this.form.products[index].availableQuantity = product.quantity;
                this.calculateTotal();
            }
        },

        calculateTotal() {
            let subtotal = 0;
            this.form.products.forEach(product => {
                subtotal += (product.price || 0) * (product.quantity || 1);
            });
            this.form.total = subtotal;
        },

        validateQuantities() {
            let isValid = true;
            this.form.products.forEach((product, index) => {
                if (product.quantity > product.availableQuantity) {
                    this.errors[`products.${index}.quantity`] = `الكمية المطلوبة (${product.quantity}) تتجاوز الكمية المتوفرة (${product.availableQuantity})`;
                    isValid = false;
                }
            });
            return isValid;
        },

        async submit() {
            this.loading = true;
            this.errors = {};

            // Validation
            if (!this.form.name) this.errors.name = 'الاسم مطلوب';
            if (!this.form.phone) this.errors.phone = 'رقم الهاتف مطلوب';
            if (!this.form.address) this.errors.address = 'العنوان مطلوب';
            if (!this.form.products.length) this.errors.products = 'يجب إضافة منتج واحد على الأقل';

            // Validate quantities
            if (!this.validateQuantities()) {
                this.loading = false;
                return;
            }

            if (Object.keys(this.errors).length > 0) {
                this.loading = false;
                return;
            }

            try {
                const formData = new FormData();
                formData.append('name', this.form.name);
                formData.append('phone', this.form.phone);
                formData.append('address', this.form.address);
                formData.append('products', JSON.stringify(this.form.products));
                formData.append('total', this.form.total);
                formData.append('status', this.form.status);

                await axios.post(`/admin/orders/update/${this.order.id}`, formData);
                this.$emit('updated');
                this.reset();
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                } else if (error.response && error.response.data.message) {
                    this.errors.general = error.response.data.message;
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
                phone: '',
                address: '',
                products: [],
                total: 0,
                status: 'pending'
            };
            this.errors = {};
            this.loading = false;
        }
    }
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')" max-width="4xl">
        <div class="max-h-[90vh] overflow-hidden flex flex-col" style="direction: rtl;">
            <!-- Header -->
            <div class="flex justify-between items-center p-4 border-b bg-gray-50">
                <h5 class="text-lg font-bold">تعديل الطلب</h5>
                <button type="button" class="text-gray-500 hover:text-red-600 text-2xl" @click="$emit('close')">&times;</button>
            </div>

            <!-- Content -->
            <div class="flex-1 flex flex-col p-4">
                <form @submit.prevent="submit" class="flex flex-col h-full">
                    <!-- Fixed Content -->
                    <div class="flex-shrink-0">
                        <!-- Basic Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
                            <!-- Name -->
                            <div>
                                <InputLabel for="name" value="اسم العميل" class="text-sm" />
                                <TextInput
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full text-sm"
                                    :class="{ 'border-red-500': errors.name }"
                                />
                                <InputError :message="errors.name" class="mt-1 text-xs" />
                            </div>

                            <!-- Phone -->
                            <div>
                                <InputLabel for="phone" value="رقم الهاتف" class="text-sm" />
                                <TextInput
                                    id="phone"
                                    v-model="form.phone"
                                    type="tel"
                                    class="mt-1 block w-full text-sm"
                                    :class="{ 'border-red-500': errors.phone }"
                                />
                                <InputError :message="errors.phone" class="mt-1 text-xs" />
                            </div>

                            <!-- Status -->
                            <div>
                                <InputLabel for="status" value="الحالة" class="text-sm" />
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full text-sm mt-1"
                                >
                                    <option value="pending">معلق</option>
                                    <option value="processing">قيد المعالجة</option>
                                    <option value="completed">مكتمل</option>
                                    <option value="cancelled">ملغي</option>
                                </select>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mb-4">
                            <InputLabel for="address" value="العنوان" class="text-sm" />
                            <textarea
                                id="address"
                                v-model="form.address"
                                rows="2"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full text-sm mt-1"
                                :class="{ 'border-red-500': errors.address }"
                            ></textarea>
                            <InputError :message="errors.address" class="mt-1 text-xs" />
                        </div>
                    </div>

                    <!-- Scrollable Products Section -->
                    <div class="flex-1 flex flex-col min-h-0">
                        <div class="flex justify-between items-center mb-2">
                            <InputLabel value="المنتجات" class="text-sm font-semibold" />
                            <button
                                type="button"
                                @click="addProduct"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded text-xs"
                            >
                                إضافة منتج
                            </button>
                        </div>

                        <!-- Products List Container -->
                        <div class="flex-1 overflow-y-auto border rounded bg-gray-50 p-2">
                            <div class="space-y-2">
                                <div v-for="(product, index) in form.products" :key="index" class="grid grid-cols-12 gap-2 p-2 border rounded bg-white">
                                    <!-- Product Selection -->
                                    <div class="col-span-4">
                                        <select
                                            v-model="product.id"
                                            @change="onProductChange(index)"
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full text-xs"
                                        >
                                            <option value="">اختر المنتج</option>
                                            <option v-for="prod in products" :key="prod.id" :value="prod.id">
                                                {{ prod.name }} ({{ prod.quantity }})
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Quantity -->
                                    <div class="col-span-2">
                                        <TextInput
                                            v-model="product.quantity"
                                            type="number"
                                            placeholder="الكمية"
                                            class="w-full text-xs"
                                            :class="{ 'border-red-500': errors[`products.${index}.quantity`] }"
                                            @input="calculateTotal"
                                        />
                                        <div v-if="product.availableQuantity !== undefined" class="text-gray-500 text-xs mt-1">
                                            متوفر: {{ product.availableQuantity }}
                                        </div>
                                    </div>

                                    <!-- Price -->
                                    <div class="col-span-2">
                                        <TextInput
                                            v-model="product.price"
                                            type="number"
                                            step="0.01"
                                            placeholder="السعر"
                                            class="w-full text-xs"
                                            @input="calculateTotal"
                                        />
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="col-span-2 flex items-center justify-center">
                                        <span class="text-xs font-semibold">
                                            {{ (product.price || 0) * (product.quantity || 1) }} ريال
                                        </span>
                                    </div>

                                    <!-- Remove Button -->
                                    <div class="col-span-2 flex items-center justify-center">
                                        <button
                                            type="button"
                                            @click="removeProduct(index)"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs"
                                        >
                                            حذف
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <InputError :message="errors.products" class="mt-2 text-xs" />
                        <InputError :message="errors.general" class="mt-2 text-xs" />
                    </div>

                    <!-- Fixed Footer Content -->
                    <div class="flex-shrink-0 mt-4">
                        <!-- Total -->
                        <div class="text-right mb-4">
                            <span class="font-bold text-lg">المجموع: {{ form.total }} ريال</span>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="p-4 border-t bg-gray-50 flex justify-end space-x-3 space-x-reverse">
                <button
                    type="button"
                    @click="$emit('close')"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm"
                >
                    إلغاء
                </button>

                <PrimaryButton
                    :disabled="loading"
                    @click="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm"
                >
                    {{ loading ? 'جاري التحديث...' : 'تحديث الطلب' }}
                </PrimaryButton>
            </div>
        </div>
    </Modal>
</template>
