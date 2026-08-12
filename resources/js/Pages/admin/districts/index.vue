<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import "@fortawesome/fontawesome-free/css/all.css";
import axios from 'axios';
import ShowDistrictModal from "@/Pages/admin/districts/ShowDistrictModal.vue";
import AddDistrictModal from "@/Pages/admin/districts/AddDistrictModal.vue";
import EditDistrictModal from "@/Pages/admin/districts/EditDistrictModal.vue";
import DeleteDistrictModal from "@/Pages/admin/districts/DeleteDistrictModal.vue";
import ShowProductModal from "@/Pages/admin/products/ShowProductModal.vue";

export default {
    components: {
        ShowProductModal,
        AuthenticatedLayout,
        Head,
        ShowDistrictModal,
        AddDistrictModal,
        EditDistrictModal,
        DeleteDistrictModal,
    },

    props: {
        districts: Array
    },

    data() {
        return {
            selectedDistrict: {},
            isModalOpen: false,
            isAddDistrictOpen: false,
            isEditDistrictOpen: false,
            isDeleteDistrictOpen: false,
            message: '',
            localDistricts: [],
            districts:[]
        };
    },

    mounted() {
        this.getDistricts();
    },

    methods: {
        searchDistricts(query) {
            this.localDistricts = this.districts.filter(district => {
               return district.name.toLowerCase().includes(query.toLowerCase())
                   || district.name_ar.toLowerCase().includes(query.toLowerCase())
            })
        },

        showDistrictModal(district){
            this.selectedDistrict = district;
            this.isModalOpen = true;
        },

        editDistrictModal(district) {
            this.selectedDistrict = district;
            this.isEditDistrictOpen = true;
        },

        deleteDistrictModal(district) {
            this.selectedDistrict = district;
            this.isDeleteDistrictOpen = true;
        },

        closeModal() {
            this.isModalOpen = false;
            this.isEditDistrictOpen = false;
            this.isAddDistrictOpen = false;
            this.isDeleteDistrictOpen = false;
            this.selectedDistrict = {};
            this.message = '';
        },

        addDistrictModal() {
            this.closeModal();
            this.isAddDistrictOpen = true;
        },

        getDistricts(){
            axios.get('/admin/districts/get-districts')
                .then(response => {
                    this.localDistricts = response.data.data;
                    this.districts = response.data.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },

        handleDistrictCreated() {
            try {
                this.getDistricts();
                this.message = 'تمت إضافة المنطقة بنجاح';
                this.isAddDistrictOpen = false;
                setTimeout(() => {
                    this.message = '';
                }, 3000);
            } catch (error) {
                this.message = 'حدث خطأ أثناء التحديث';
                console.error(error);
            }
        },

        handleDistrictUpdated() {
            try {
                this.getDistricts();
                this.message = 'تم تحديث المنطقة بنجاح';
                this.isEditDistrictOpen = false;
                setTimeout(() => { this.message = ''; }, 3000);
            } catch (error) {
                this.message = 'حدث خطأ أثناء التحديث';
                console.error(error);
            }
        },

        handleDistrictDeleted() {
            try {
                this.getDistricts();
                this.message = 'تم حذف المنطقة بنجاح';
                this.isDeleteDistrictOpen = false;
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
    <Head title="الحى" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 text-right">
                قائمة الأحياء
            </h2>
        </template>

        <div v-if="message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
            {{ message }}
        </div>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="float-left mb-2">
                    <input
                        type="text"
                        @keyup="searchDistricts( $event.target.value)"
                        placeholder="ابحث عن الحي"
                        class="pl-3 text-blue-700 hover:text-gray-500"
                        style="font-size: 16px"
                    >
                </div>
                <div class="float-right mb-2">
                    <button
                        type="button"
                        @click="addDistrictModal()"
                        class="pl-3 text-blue-700 text-lg hover:text-gray-500"
                        style="font-size: 22px"
                    >
                        اضافة حى
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>

                <table style="direction: rtl">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الاسم بالعربى</th>
                        <th>السعر</th>
                        <th>الاجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="district in localDistricts" :key="district.id">
                        <td>{{ district.id }}</td>
                        <td>{{ district.name }}</td>
                        <td>{{ district.name_ar }}</td>
                        <td>{{ district.price }}</td>
                        <td>
                            <button
                                type="button"
                                @click="showDistrictModal(district)"
                                class="pl-3 text-green-500 text-lg hover:text-gray-500"
                            >
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button
                                type="button"
                                @click="editDistrictModal(district)"
                                class="pl-3 text-blue-500 text-lg hover:text-gray-500"
                            >
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                            <button
                                type="button"
                                @click="deleteDistrictModal(district)"
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

        <ShowDistrictModal
            :show="isModalOpen"
            :district="selectedDistrict"
            @close="closeModal"
        />

        <!-- Add Zone Modal -->
        <AddDistrictModal
            :show="isAddDistrictOpen"
            @close="closeModal"
            @created="handleDistrictCreated"
        />

        <!-- Edit Zone Modal -->
        <EditDistrictModal
            :show="isEditDistrictOpen"
            :district="selectedDistrict"
            @close="closeModal"
            @updated="handleDistrictUpdated"
        />

        <!-- Delete Zone Modal -->
        <DeleteDistrictModal
            :show="isDeleteDistrictOpen"
            :district="selectedDistrict"
            @close="closeModal"
            @deleted="handleDistrictDeleted"
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
