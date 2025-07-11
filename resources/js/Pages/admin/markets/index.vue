<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import "@fortawesome/fontawesome-free/css/all.css";
import axios from 'axios';
import ShowMarketModal from "@/Pages/admin/markets/ShowMarketModal.vue";
import AddMarketModal from "@/Pages/admin/markets/AddMarketModal.vue";
import EditMarketModal from "@/Pages/admin/markets/EditMarketModal.vue";
import DeleteMarketModal from "@/Pages/admin/markets/DeleteMarketModal.vue";

export default {
    components: {
        AuthenticatedLayout,
        Head,
        ShowMarketModal,
        AddMarketModal,
        EditMarketModal,
        DeleteMarketModal,
    },

    props: {
        markets: Array
    },

    data() {
        return {
            selectedMarket: {},
            isModalOpen: false,
            isAddMarketOpen: false,
            isEditMarketOpen: false,
            isDeleteMarketOpen: false,
            message: '',
            localMarkets: [],
        };
    },

    mounted() {
        this.getMarkets();
    },

    methods: {
        showMarketModal(market) {
            this.selectedMarket = market;
            this.isModalOpen = true;
        },

        editMarketModal(market) {
            this.selectedMarket = market;
            this.isEditMarketOpen = true;
        },

        deleteMarketModal(market) {
            this.selectedMarket = market;
            this.isDeleteMarketOpen = true;
        },

        closeModal() {
            this.isModalOpen = false;
            this.isEditMarketOpen = false;
            this.isAddMarketOpen = false;
            this.isDeleteMarketOpen = false;
            this.selectedMarket = {};
            this.message = '';
        },

        addMarketModal() {
            this.closeModal();
            this.isAddMarketOpen = true;
        },

        getMarkets(){
            axios.get('/admin/markets/get-markets')
                .then(response => {
                    this.localMarkets = response.data.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },

        handleMarketCreated() {
            try {
                this.getMarkets();
                this.message = 'تمت إضافة المعرض بنجاح';
                this.isAddMarketOpen = false;
                setTimeout(() => {
                    this.message = '';
                }, 3000);
            } catch (error) {
                this.message = 'حدث خطأ أثناء التحديث';
                console.error(error);
            }
        },

        handleMarketUpdated(updatedMarket) {
            try {
                this.getMarkets();
                this.message = 'تم تحديث المعرض بنجاح';
                this.isEditMarketOpen = false;
                setTimeout(() => { this.message = ''; }, 3000);
            } catch (error) {
                this.message = 'حدث خطأ أثناء التحديث';
                console.error(error);
            }
        },

        handleMarketDeleted(marketId) {
            try {
                this.getMarkets();
                this.message = 'تم حذف المعرض بنجاح';
                this.isDeleteMarketOpen = false;
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
    <Head title="المعرض" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 text-right">
                قائمة المعرض
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
                        @click="addMarketModal()"
                        class="pl-3 text-blue-700 text-lg hover:text-gray-500"
                        style="font-size: 22px"
                    >
                        اضافة معرض
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
                    <tr v-for="market in localMarkets" :key="market.id">
                        <td>{{ market.id }}</td>
                        <td style="justify-items: center;">
                            <img
                                :src="market.image"
                                alt="Market Image"
                                class="img-fluid"
                                style="height: 100px; width: 120px; object-fit: contain"
                            />
                        </td>
                        <td>
                            <span :class="market.show ? 'text-green-600' : 'text-red-600'">
                                {{ market.show ? 'مفعل' : 'معطل' }}
                            </span>
                        </td>
                        <td>
                            <button
                                type="button"
                                @click="showMarketModal(market)"
                                class="pl-3 text-green-500 text-lg hover:text-gray-500"
                            >
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button
                                type="button"
                                @click="editMarketModal(market)"
                                class="pl-3 text-blue-500 text-lg hover:text-gray-500"
                            >
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                            <button
                                type="button"
                                @click="deleteMarketModal(market)"
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

        <!-- Show Market Modal -->
        <ShowMarketModal
            :show="isModalOpen"
            :market="selectedMarket"
            @close="closeModal"
        />

        <!-- Add Market Modal -->
        <AddMarketModal
            :show="isAddMarketOpen"
            @close="closeModal"
            @created="handleMarketCreated"
        />

        <!-- Edit Market Modal -->
        <EditMarketModal
            :show="isEditMarketOpen"
            :market="selectedMarket"
            @close="closeModal"
            @updated="handleMarketUpdated"
        />

        <!-- Delete Market Modal -->
        <DeleteMarketModal
            :show="isDeleteMarketOpen"
            :market="selectedMarket"
            @close="closeModal"
            @deleted="handleMarketDeleted"
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
