<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import "@fortawesome/fontawesome-free/css/all.css";
import axios from 'axios';
import ShowUserModal from "@/Pages/admin/users/ShowUserModal.vue";
import AddUserModal from "@/Pages/admin/users/AddUserModal.vue";
import EditUserModal from "@/Pages/admin/users/EditUserModal.vue";
import DeleteUserModal from "@/Pages/admin/users/DeleteUserModal.vue";

export default {
    components: {
        AuthenticatedLayout,
        Head,
        ShowUserModal,
        AddUserModal,
        EditUserModal,
        DeleteUserModal,
    },

    props: {
        users: Array
    },

    data() {
        return {
            selectedUser: {},
            isModalOpen: false,
            isAddUserOpen: false,
            isEditUserOpen: false,
            isDeleteUserOpen: false,
            message: '',
            localUsers: [],
        };
    },

    mounted() {
        this.getUsers();
    },

    methods: {
        showUserModal(user) {
            this.selectedUser = user;
            this.isModalOpen = true;
        },

        editUserModal(user) {
            this.selectedUser = user;
            this.isEditUserOpen = true;
        },

        deleteUserModal(user) {
            this.selectedUser = user;
            this.isDeleteUserOpen = true;
        },

        closeModal() {
            this.isModalOpen = false;
            this.isEditUserOpen = false;
            this.isAddUserOpen = false;
            this.isDeleteUserOpen = false;
            this.selectedUser = {};
            this.message = '';
        },

        addUserModal() {
            this.closeModal();
            this.isAddUserOpen = true;
        },

        getUsers(){
            axios.get('/admin/users/get-users')
                .then(response => {
                    this.localUsers = response.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },

        handleUserCreated() {
            try {
                this.getUsers();
                this.message = 'تمت إضافة المستخدم بنجاح';
                this.isAddUserOpen = false;
                setTimeout(() => {
                    this.message = '';
                }, 3000);
            } catch (error) {
                this.message = 'حدث خطأ أثناء التحديث';
                console.error(error);
            }
        },

        handleUserUpdated() {
            try {
                this.getUsers();
                this.message = 'تم تعديل المستخدم بنجاح';
                this.isEditUserOpen = false;
                setTimeout(() => {
                    this.message = '';
                }, 3000);
            } catch (error) {
                this.message = 'حدث خطأ أثناء التحديث';
                console.error(error);
            }
        },

        handleUserDeleted() {
            try {
                this.getUsers();
                this.message = 'تم حذف المستخدم بنجاح';
                this.isDeleteUserOpen = false;
                setTimeout(() => {
                    this.message = '';
                }, 3000);
            } catch (error) {
                this.message = 'حدث خطأ أثناء التحديث';
                console.error(error);
            }
        },
    },
};
</script>

<template>
    <Head title="المستخدمين" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 text-right">
                قائمة المستخدمين
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
                        @click="addUserModal()"
                        class="pl-3 text-blue-700 text-lg hover:text-gray-500"
                        style="font-size: 22px"
                    >
                        اضافة مستخدم
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
                <table style="direction: rtl">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>رقم الهاتف</th>
                        <th>الاجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in localUsers" :key="user.id">
                            <td>{{ user.id }}</td>
                            <td>{{ user.name }}</td>
                            <td>{{ user.email }}</td>
                            <td>{{ user.phone }}</td>
                            <td>
                                <button
                                    type="button"
                                    @click="showUserModal(user)"
                                    class="pl-3 text-green-500 text-lg hover:text-gray-500"
                                >
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button
                                    type="button"
                                    @click="editUserModal(user)"
                                    class="pl-3 text-blue-500 text-lg hover:text-gray-500"
                                >
                                    <i class="fa-solid fa-pencil"></i>
                                </button>
                                <button
                                    type="button"
                                    @click="deleteUserModal(user)"
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

    <ShowUserModal
        :show="isModalOpen"
        :user="selectedUser"
        @close="closeModal"
    />
    <AddUserModal
        :show="isAddUserOpen"
        @close="closeModal"
        @created="handleUserCreated"
    />
    <EditUserModal
        :show="isEditUserOpen"
        :user="selectedUser"
        @close="closeModal"
        @updated="handleUserUpdated"
    />
    <DeleteUserModal
        :show="isDeleteUserOpen"
        :user="selectedUser"
        @close="closeModal"
        @deleted="handleUserDeleted"
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

.alert {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 0.375rem;
    text-align: center;
}

.alert-success {
    background-color: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}
</style>
