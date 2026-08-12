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
        district: Object
    },
    emits: ['close', 'updated'],
    data() {
        return {
            form: {
                id: null,
                name: '',
                name_ar: '',
                price: '',
                zone_name: '',
            },
            zones: [],
            errors: {}
        };
    },
    watch: {
        district: {
            handler(newDistrict) {
                if (newDistrict) {
                    this.form.id = newDistrict.id;
                    this.form.name = newDistrict.name;
                    this.form.name_ar = newDistrict.name_ar;
                    this.form.price = newDistrict.price;
                    this.form.zone_name = newDistrict.zone_name;
                }
            },
            immediate: true,
            deep: true
        }
    },

    mounted() {
        this.getZones();
    },

    methods: {
        getZones() {
            axios.get('/admin/zones/get-zones')
                .then(response => {
                    this.zones = response.data.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },

        async submit() {
            this.errors = {};

            try {
                const selectedZone = this.zones.find(zone => zone.name_ar === this.form.zone_name);
                const zoneId = selectedZone ? selectedZone.id : null;

                const formData = {
                    ...this.form,
                    zone_id: zoneId
                };

                await axios.post(`/admin/districts/update/${this.district.id}`, formData);
                this.$emit('updated');
                this.resetForm();
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                }
            }
        },

        resetForm() {
            this.form = {
                name: '',
                name_ar: '',
                price: 0,
                zone_name: '',
            };
            this.errors = {};
        }
    }
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6" style="direction: rtl;">
            <div class="flex justify-between items-center mb-4">
                <h5 class="text-lg font-bold">تعديل الحي</h5>
                <button type="button" class="text-gray-500 hover:text-red-600 text-2xl" @click="$emit('close')">&times;</button>
            </div>
            <hr class="my-4">

            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <InputLabel for="name" value="الاسم" />
                        <TextInput
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="mt-1 block w-full"
                            :class="{ 'border-red-500': errors.name }"
                        />
                        <InputError :message="errors.name" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="name_ar" value="الاسم بالعربى" />
                        <TextInput
                            id="name_ar"
                            v-model="form.name_ar"
                            type="text"
                            class="mt-1 block w-full"
                            :class="{ 'border-red-500': errors.name_ar }"
                        />
                        <InputError :message="errors.name_ar" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <InputLabel for="zone_name" value="المنطقة" />
                        <select
                            id="zone_name"
                            v-model="form.zone_name"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                            :class="{ 'border-red-500': errors.zone_name }"
                        >
                            <option value="">اختر القسم</option>
                            <option v-for="zone in zones" :key="zone.id" :value="zone.name_ar">
                                {{ zone.name_ar }}
                            </option>
                        </select>
                        <InputError :message="errors.zone_name" class="mt-2" />
                    </div>
                    <div>
                        <InputLabel for="price" value="سعر التوصيل" />
                        <TextInput
                            id="price"
                            v-model="form.price"
                            type="text"
                            class="mt-1 block w-full"
                            :class="{ 'border-red-500': errors.price }"
                        />
                        <InputError :message="errors.price" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3 space-x-reverse">
                    <button
                        type="button"
                        @click="$emit('close')"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                    >
                        إلغاء
                    </button>

                    <PrimaryButton class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تحديث الحي</PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
