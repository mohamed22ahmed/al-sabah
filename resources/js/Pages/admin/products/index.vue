<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import "@fortawesome/fontawesome-free/css/all.css";
import axios from 'axios';
import ShowProductModal from "@/Pages/admin/products/ShowProductModal.vue";
import AddProductModal from "@/Pages/admin/products/AddProductModal.vue";
import EditProductModal from "@/Pages/admin/products/EditProductModal.vue";
import DeleteProductModal from "@/Pages/admin/products/DeleteProductModal.vue";

export default {
    components: {
        AuthenticatedLayout,
        Head,
        ShowProductModal,
        AddProductModal,
        EditProductModal,
        DeleteProductModal,
    },

    props: {
        products: Array
    },

    data() {
        return {
            selectedProduct: {},
            isModalOpen: false,
            isAddProductOpen: false,
            isEditProductOpen: false,
            isDeleteProductOpen: false,
            message: '',
            localProducts: [],
        };
    },

    mounted() {
        this.getProducts();
    },

    methods: {
        showProductModal(product) {
            this.selectedProduct = product;
            this.isModalOpen = true;
        },

        editProductModal(product) {
            this.selectedProduct = product;
            this.isEditProductOpen = true;
        },

        deleteProductModal(product) {
            this.selectedProduct = product;
            this.isDeleteProductOpen = true;
        },

        closeModal() {
            this.isModalOpen = false;
            this.isEditProductOpen = false;
            this.isAddProductOpen = false;
            this.isDeleteProductOpen = false;
            this.selectedProduct = {};
            this.message = '';
        },

        addProductModal() {
            this.closeModal();
            this.isAddProductOpen = true;
        },

        getProducts(){
            axios.get('/admin/products/get-products')
                .then(response => {
                    this.localProducts = response.data.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },

        handleProductCreated() {
            try {
                this.getProducts();
                this.message = 'تمت إضافة المنتج بنجاح';
                this.isAddProductOpen = false;
                setTimeout(() => {
                    this.message = '';
                }, 3000);
            } catch (error) {
                this.message = 'حدث خطأ أثناء التحديث';
                console.error(error);
            }
        },

        handleProductUpdated(updatedProduct) {
            try {
                this.getProducts();
                this.message = 'تم تحديث المنتج بنجاح';
                this.isEditProductOpen = false;
                setTimeout(() => { this.message = ''; }, 3000);
            } catch (error) {
                this.message = 'حدث خطأ أثناء التحديث';
                console.error(error);
            }
        },

        handleProductDeleted(productId) {
            try {
                this.getProducts();
                this.message = 'تم حذف المنتج بنجاح';
                this.isDeleteProductOpen = false;
                setTimeout(() => { this.message = ''; }, 3000);
            } catch (error) {
                this.message = 'حدث خطأ أثناء الحذف';
                console.error(error);
            }
        },
    },
};
</script>

<template>
    <Head title="المنتجات" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 text-right">
                قائمة المنتجات
            </h2>
        </template>

        <div v-if="message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
            {{ message }}
        </div>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="float-right mb-2">
                    <button
                        type="button"
                        @click="addProductModal()"
                        class="pl-3 text-blue-700 text-lg hover:text-gray-500"
                        style="font-size: 22px"
                    >
                        اضافة منتج
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
                <table style="direction: rtl">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>القسم</th>
                        <th>السعر</th>
                        <th>الكمية</th>
                        <th>الوزن</th>
                        <th>المباع</th>
                        <th>الصورة</th>
                        <th>الاجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="product in localProducts" :key="product.id">
                        <td>{{ product.id }}</td>
                        <td>{{ product.name }}</td>
                        <td>{{ product.category?.name || 'غير محدد' }}</td>
                        <td>{{ product.price }}</td>
                        <td>{{ product.quantity }}</td>
                        <td>{{ product.weight }}</td>
                        <td>{{ product.sold }}</td>
                        <td style="justify-items: center;">
                            <img
                                :src="product.image"
                                alt="Product Image"
                                class="img-fluid"
                                style="height: 100px; width: 120px; object-fit: contain"
                            />
                        </td>
                        <td>
                            <button
                                type="button"
                                @click="showProductModal(product)"
                                class="pl-3 text-green-500 text-lg hover:text-gray-500"
                            >
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button
                                type="button"
                                @click="editProductModal(product)"
                                class="pl-3 text-blue-500 text-lg hover:text-gray-500"
                            >
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                            <button
                                type="button"
                                @click="deleteProductModal(product)"
                                class="pl-3 text-red-500 text-lg hover:text-gray-500"
                            >
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Show Product Modal -->
        <ShowProductModal
            :show="isModalOpen"
            :product="selectedProduct"
            @close="closeModal"
        />

        <!-- Add Product Modal -->
        <AddProductModal
            :show="isAddProductOpen"
            @close="closeModal"
            @created="handleProductCreated"
        />

        <!-- Edit Product Modal -->
        <EditProductModal
            :show="isEditProductOpen"
            :product="selectedProduct"
            @close="closeModal"
            @updated="handleProductUpdated"
        />

        <!-- Delete Product Modal -->
        <DeleteProductModal
            :show="isDeleteProductOpen"
            :product="selectedProduct"
            @close="closeModal"
            @deleted="handleProductDeleted"
        />
    </AuthenticatedLayout>
</template>


<style scoped>
table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;

}

th {
    background-color: #4a5568;
    color: white;
}
</style>
