<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import "@fortawesome/fontawesome-free/css/all.css";
import axios from 'axios';
import ShowBannerModal from "@/Pages/admin/banners/ShowBannerModal.vue";
import AddBannerModal from "@/Pages/admin/banners/AddBannerModal.vue";
import EditBannerModal from "@/Pages/admin/banners/EditBannerModal.vue";
import DeleteBannerModal from "@/Pages/admin/banners/DeleteBannerModal.vue";

export default {
    components: {
        AuthenticatedLayout,
        Head,
        ShowBannerModal,
        AddBannerModal,
        EditBannerModal,
        DeleteBannerModal,
    },

    props: {
        banners: Array
    },

    data() {
        return {
            selectedBanner: {},
            isModalOpen: false,
            isAddBannerOpen: false,
            isEditBannerOpen: false,
            isDeleteBannerOpen: false,
            message: '',
            localBanners: [],
        };
    },

    mounted() {
        this.getBanners();
    },

    methods: {
        showBannerModal(banner) {
            this.selectedBanner = banner;
            this.isModalOpen = true;
        },

        editBannerModal(banner) {
            this.selectedBanner = banner;
            this.isEditBannerOpen = true;
        },

        deleteBannerModal(banner) {
            this.selectedBanner = banner;
            this.isDeleteBannerOpen = true;
        },

        closeModal() {
            this.isModalOpen = false;
            this.isEditBannerOpen = false;
            this.isAddBannerOpen = false;
            this.isDeleteBannerOpen = false;
            this.selectedBanner = {};
            this.message = '';
        },

        addBannerModal() {
            this.closeModal();
            this.isAddBannerOpen = true;
        },

        getBanners(){
            axios.get('/admin/banners/get-banners')
                .then(response => {
                    this.localBanners = response.data.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },

        handleBannerCreated() {
            try {
                this.getBanners();
                this.message = 'تمت إضافة البانر بنجاح';
                this.isAddBannerOpen = false;
                setTimeout(() => {
                    this.message = '';
                }, 3000);
            } catch (error) {
                this.message = 'حدث خطأ أثناء التحديث';
                console.error(error);
            }
        },

        handleBannerUpdated(updatedBanner) {
            try {
                this.getBanners();
                this.message = 'تم تحديث البانر بنجاح';
                this.isEditBannerOpen = false;
                setTimeout(() => { this.message = ''; }, 3000);
            } catch (error) {
                this.message = 'حدث خطأ أثناء التحديث';
                console.error(error);
            }
        },

        handleBannerDeleted(bannerId) {
            try {
                this.getBanners();
                this.message = 'تم حذف البانر بنجاح';
                this.isDeleteBannerOpen = false;
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
    <Head title="البانرات" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 text-right">
                قائمة البانرات
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
                        @click="addBannerModal()"
                        class="pl-3 text-blue-700 text-lg hover:text-gray-500"
                        style="font-size: 22px"
                    >
                        اضافة بانر
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
                <table style="direction: rtl">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الصورة</th>
                        <th>الحالة</th>
                        <th>الاجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="banner in localBanners" :key="banner.id">
                        <td>{{ banner.id }}</td>
                        <td style="justify-items: center;">
                            <img
                                :src="banner.image"
                                alt="Banner Image"
                                class="img-fluid"
                                style="height: 100px; width: 120px; object-fit: contain"
                            />
                        </td>
                        <td>
                            <span :class="banner.show ? 'text-green-600' : 'text-red-600'">
                                {{ banner.show ? 'مفعل' : 'معطل' }}
                            </span>
                        </td>
                        <td>
                            <button
                                type="button"
                                @click="showBannerModal(banner)"
                                class="pl-3 text-green-500 text-lg hover:text-gray-500"
                            >
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button
                                type="button"
                                @click="editBannerModal(banner)"
                                class="pl-3 text-blue-500 text-lg hover:text-gray-500"
                            >
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                            <button
                                type="button"
                                @click="deleteBannerModal(banner)"
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

        <!-- Show Banner Modal -->
        <ShowBannerModal
            :show="isModalOpen"
            :banner="selectedBanner"
            @close="closeModal"
        />

        <!-- Add Banner Modal -->
        <AddBannerModal
            :show="isAddBannerOpen"
            @close="closeModal"
            @created="handleBannerCreated"
        />

        <!-- Edit Banner Modal -->
        <EditBannerModal
            :show="isEditBannerOpen"
            :banner="selectedBanner"
            @close="closeModal"
            @updated="handleBannerUpdated"
        />

        <!-- Delete Banner Modal -->
        <DeleteBannerModal
            :show="isDeleteBannerOpen"
            :banner="selectedBanner"
            @close="closeModal"
            @deleted="handleBannerDeleted"
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
