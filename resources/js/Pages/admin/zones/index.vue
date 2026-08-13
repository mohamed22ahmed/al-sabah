<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import "@fortawesome/fontawesome-free/css/all.css";
import axios from 'axios';
import AddZoneModal from "@/Pages/admin/zones/AddZoneModal.vue";
import EditZoneModal from "@/Pages/admin/zones/EditZoneModal.vue";
import DeleteZoneModal from "@/Pages/admin/zones/DeleteZoneModal.vue";

export default {
    components: {
        AuthenticatedLayout,
        Head,
        AddZoneModal,
        EditZoneModal,
        DeleteZoneModal,
    },

    props: {
        zones: Array
    },

    data() {
        return {
            selectedZone: {},
            isAddZoneOpen: false,
            isEditZoneOpen: false,
            isDeleteZoneOpen: false,
            message: '',
            localZones: []
        };
    },

    mounted() {
        this.getZones();
    },

    methods: {
        editZoneModal(zone) {
            this.selectedZone = zone;
            console.log(this.selectedZone)
            this.isEditZoneOpen = true;
        },

        deleteZoneModal(zone) {
            this.selectedZone = zone;
            this.isDeleteZoneOpen = true;
        },

        closeModal() {
            this.isEditZoneOpen = false;
            this.isAddZoneOpen = false;
            this.isDeleteZoneOpen = false;
            this.selectedZone = {};
            this.message = '';
        },

        addZoneModal() {
            this.closeModal();
            this.isAddZoneOpen = true;
        },

        getZones(){
            axios.get('/admin/zones/get-zones')
                .then(response => {
                    this.localZones = response.data.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },

        handleZoneCreated() {
            try {
                this.getZones();
                this.message = 'تمت إضافة المنطقة بنجاح';
                this.isAddZoneOpen = false;
                setTimeout(() => {
                    this.message = '';
                }, 3000);
            } catch (error) {
                this.message = 'حدث خطأ أثناء التحديث';
                console.error(error);
            }
        },

        handleZoneUpdated() {
            try {
                this.getZones();
                this.message = 'تم تحديث المنطقة بنجاح';
                this.isEditZoneOpen = false;
                setTimeout(() => { this.message = ''; }, 3000);
            } catch (error) {
                this.message = 'حدث خطأ أثناء التحديث';
                console.error(error);
            }
        },

        handleZoneDeleted() {
            try {
                this.getZones();
                this.message = 'تم حذف المنطقة بنجاح';
                this.isDeleteZoneOpen = false;
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
    <Head title="المنطقة" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 text-right">
                قائمة المناطق
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
                        @click="addZoneModal()"
                        class="pl-3 text-blue-700 text-lg hover:text-gray-500"
                        style="font-size: 22px"
                    >
                        اضافة منطقة
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
                <table style="direction: rtl">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الاسم بالعربى</th>
                        <th>سعر التوصيل</th>
                        <th>الاجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="zone in localZones" :key="zone.id">
                        <td>{{ zone.id }}</td>
                        <td>{{ zone.name }}</td>
                        <td>{{ zone.name_ar }}</td>
                        <td>{{ zone.price }}</td>
                        <td>
                            <button
                                type="button"
                                @click="editZoneModal(zone)"
                                class="pl-3 text-blue-500 text-lg hover:text-gray-500"
                            >
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                            <button
                                type="button"
                                @click="deleteZoneModal(zone)"
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

        <AddZoneModal
            :show="isAddZoneOpen"
            @close="closeModal"
            @created="handleZoneCreated"
        />

        <EditZoneModal
            :show="isEditZoneOpen"
            :zone="selectedZone"
            @close="closeModal"
            @updated="handleZoneUpdated"
        />

        <DeleteZoneModal
            :show="isDeleteZoneOpen"
            :zone="selectedZone"
            @close="closeModal"
            @deleted="handleZoneDeleted"
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
