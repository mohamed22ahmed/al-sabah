<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import "@fortawesome/fontawesome-free/css/all.css";
import axios from 'axios';
import ShowCategoryModal from "@/Pages/admin/categories/ShowCategoryModal.vue";
import AddCategoryModal from "@/Pages/admin/categories/AddCategoryModal.vue";
import EditCategoryModal from "@/Pages/admin/categories/EditCategoryModal.vue";
import DeleteCategoryModal from "@/Pages/admin/categories/DeleteCategoryModal.vue";
export default {
    components: {
        AuthenticatedLayout,
        Head,
        ShowCategoryModal,
        AddCategoryModal,
        EditCategoryModal,
        DeleteCategoryModal,
    },

    props: {
        categories: Array
    },

    data() {
        return {
            selectedCategory: {},
            isModalOpen: false,
            isAddCategoryOpen: false,
            isEditCategoryOpen: false,
            isDeleteCategoryOpen: false,
            message: '',
            localCategories: [],
        };
    },

    mounted() {
        this.getCategories();
    },

    methods: {
        showCategoryModal(category) {
            this.selectedCategory = category;
            this.isModalOpen = true;
        },

        editCategoryModal(category) {
            this.selectedCategory = category;
            this.isEditCategoryOpen = true;
        },

        deleteCategoryModal(category) {
            this.selectedCategory = category;
            this.isDeleteCategoryOpen = true;
        },

        closeModal() {
            this.isModalOpen = false;
            this.isEditCategoryOpen = false;
            this.isAddCategoryOpen = false;
            this.isDeleteCategoryOpen = false;
            this.selectedCategory = {};
            this.message = '';
        },

        addCategoryModal() {
            this.closeModal();
            this.isAddCategoryOpen = true;
        },

        getCategories(){
            axios.get('/admin/categories/get-categories')
                .then(response => {
                    this.localCategories = response.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },

        handleCategoryCreated() {
            try {
                this.getCategories();
                this.message = 'تمت إضافة القسم بنجاح';
                this.isAddCategoryOpen = false;
                setTimeout(() => {
                    this.message = '';
                }, 3000);
            } catch (error) {
                this.message = 'حدث خطأ أثناء التحديث';
                console.error(error);
            }
        },

        handleCategoryUpdated(updatedCategory) {
            try {
                this.getCategories();
                this.message = 'تم تحديث القسم بنجاح';
                this.isEditCategoryOpen = false;
                setTimeout(() => { this.message = ''; }, 3000);
            } catch (error) {
                this.message = 'حدث خطأ أثناء التحديث';
                console.error(error);
            }
        },

        handleCategoryDeleted(categoryId) {
            try {
                this.getCategories();
                this.message = 'تم حذف القسم بنجاح';
                this.isDeleteCategoryOpen = false;
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
    <Head title="الاقسام" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 text-right">
                قائمة الاقسام
            </h2>
        </template>

        <div v-if="message" class="alert alert-success">
            {{ message }}
        </div>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="float-right mb-2">
                    <button
                        type="button"
                        @click="addCategoryModal()"
                        class="pl-3 text-blue-700 text-lg hover:text-gray-500"
                        style="font-size: 22px"
                    >
                        اضافة قسم
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
                <table style="direction: rtl">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>اللينك</th>
                        <th>الصورة</th>
                        <th>الاجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="category in localCategories" :key="category.id">
                        <td>{{ category.id }}</td>
                        <td>{{ category.name }}</td>
                        <td>{{ category.url }}</td>
                        <td style="justify-items: center;">
                            <img
                                :src="category.image"
                                alt="category Image"
                                class="img-fluid"
                                style="height: 100px; width: 120px; object-fit: contain"
                            />
                        </td>
                        <td>
                            <button
                                type="button"
                                @click="showCategoryModal(category)"
                                class="pl-3 text-green-500 text-lg hover:text-gray-500"
                            >
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button
                                type="button"
                                @click="editCategoryModal(category)"
                                class="pl-3 text-blue-500 text-lg hover:text-gray-500"
                            >
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                            <button
                                type="button"
                                @click="deleteCategoryModal(category)"
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
    </AuthenticatedLayout>

    <ShowCategoryModal
        :show="isModalOpen"
        :category="selectedCategory"
        @close="closeModal"
    />
    <AddCategoryModal
        :show="isAddCategoryOpen"
        @close="closeModal"
        @created="handleCategoryCreated"
    />
    <EditCategoryModal
        :show="isEditCategoryOpen"
        :category="selectedCategory"
        @close="closeModal"
        @updated="handleCategoryUpdated"
    />
    <DeleteCategoryModal
        :show="isDeleteCategoryOpen"
        :category="selectedCategory"
        @close="closeModal"
        @deleted="handleCategoryDeleted"
    />
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
