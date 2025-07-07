<script>
import Modal from '@/Components/Modal.vue';

export default {
    components: { Modal },
    props: {
        category: Object,
        show: Boolean
    },
    emits: ['close'],
    mounted() {
        console.log(this.category);
    }
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')" maxWidth="md">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <button type="button" class="text-gray-500 hover:text-red-600 text-2xl" @click="$emit('close')">&times;</button>
                <h5 class="text-lg font-bold">تفاصيل القسم</h5>
            </div>
            <hr class="my-4">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex-1 space-y-2">
                    <div><span class="font-bold">ID:</span> {{ category.id }}</div>
                    <div><span class="font-bold">Name:</span> {{ category.name }}</div>
                    <div><span class="font-bold">URL:</span> {{ category.url }}</div>
                </div>
                <div class="flex-1 flex items-center justify-center">
                    <img :src="category.image" alt="Category Image" class="max-h-40 max-w-full object-contain rounded shadow" />
                </div>
            </div>
            <hr class="my-4">
            <div class="mt-5 flex justify-center">
                <button type="button" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" @click="$emit('close')">إغلاق</button>
            </div>
        </div>
    </Modal>
</template>

<style scoped>
.modal-body {
    height: 100%;
    display: flex;
    flex: 1;
    overflow-y: auto;
}
.modal-dialog {
    max-height: 300vh;
}

.modal-content {
    height: 100%;
}

.row {
    flex: 1;
    height: 100%;
}
.col-md-6 {
    display: flex;
    flex-direction: column;
    justify-content: center;
    height: 100%;
}
</style>
