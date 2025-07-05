<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import "@fortawesome/fontawesome-free/css/all.css";
// import ShowMedicationModal from "@/Pages/Medications/showMedicationModal.vue";
// import EditMedicationModal from "@/Pages/Medications/editMedicationModal.vue";
// import AddMedicationModal from "@/Pages/Medications/addMedicationModal.vue";
// import DeleteMedicationModal from "@/Pages/Medications/deleteMedicationModal.vue";
export default {
    components: {
        AuthenticatedLayout,
        Head,
        // ShowMedicationModal,
        // EditMedicationModal,
        // AddMedicationModal,
        // DeleteMedicationModal,
    },

    props: {
        categories: Array
    },

    data() {
        return {
            selectedMedication: {},
            isModalOpen: false,
            isEditMedicationOpen: false,
            isAddMedicationOpen: false,
            isDeleteMedicationOpen: false,
            message: ''
        };
    },

    methods: {
        showMedicationModal(medication) {
            this.selectedMedication = medication;
            this.isModalOpen = true;
        },

        closeModal() {
            this.isModalOpen = false;
            this.isEditMedicationOpen = false;
            this.isAddMedicationOpen = false;
            this.isDeleteMedicationOpen = false;
            this.selectedMedication = {};
            this.message = '';
        },

        editMedicationModal(medication) {
            this.closeModal();
            this.selectedMedication = medication;
            this.isEditMedicationOpen = true;
        },

        addMedicationModal() {
            this.closeModal();
            this.selectedMedication = {};
            this.isAddMedicationOpen = true;
        },

        deleteMedicationModal(medication) {
            this.closeModal();
            this.selectedMedication = medication;
            this.isDeleteMedicationOpen = true;
        },

        updatedMedication(){
            this.message = 'Medication updated successfully';
            setTimeout(() => {
                this.message = '';
            }, 3000);
        },

        createdMedication(){
            this.message = 'Medication created successfully';
            setTimeout(() => {
                this.message = '';
            }, 3000);
        },

        deletedMedication(){
            this.message = 'Medication deleted successfully';
            setTimeout(() => {
                this.message = '';
            }, 3000);
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
                        @click="addMedicationModal()"
                        class="pl-3 text-blue-700 text-lg hover:text-gray-500"
                        style="font-size: 22px"
                    >
                        اضافة قسم
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
                <table>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>URL</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="category in categories" :key="category.id">
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
                                @click="showMedicationModal(category)"
                                class="pl-3 text-green-500 text-lg hover:text-gray-500"
                            >
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button
                                type="button"
                                @click="editMedicationModal(category)"
                                class="pl-3 text-blue-500 text-lg hover:text-gray-500"
                            >
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                            <button
                                type="button"
                                @click="deleteMedicationModal(category)"
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

<!--    <ShowMedicationModal-->
<!--        v-if="isModalOpen"-->
<!--        :medication="selectedMedication"-->
<!--        @close="closeModal"-->
<!--    />-->

<!--    <EditMedicationModal-->
<!--        v-if="isEditMedicationOpen"-->
<!--        :medication="selectedMedication"-->
<!--        @updated="updatedMedication"-->
<!--        @close="closeModal"-->
<!--    />-->

<!--    <AddMedicationModal-->
<!--        v-if="isAddMedicationOpen"-->
<!--        :medication="selectedMedication"-->
<!--        @created="createdMedication"-->
<!--        @close="closeModal"-->
<!--    />-->

<!--    <DeleteMedicationModal-->
<!--        v-if="isDeleteMedicationOpen"-->
<!--        :medication="selectedMedication"-->
<!--        @deleted="deletedMedication"-->
<!--        @close="closeModal"-->
<!--    />-->
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
