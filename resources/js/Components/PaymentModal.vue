<script>
export default {
    props: {
        show: {
            type: Boolean,
            default: false
        },
        publishableKey: {
            type: String,
            default: null
        },
        amount: {
            type: Number,
            default: 0
        },
        orderData: {
            type: Object,
            default: null
        }
    },
    data() {
        return {
            moyasarInitialized: false
        }
    },
    computed: {
        displayAmount() {
            return this.amount / 100; // Convert halalas to SAR for display
        }
    },
    watch: {
        show(newVal) {
            if (newVal) {
                this.$nextTick(() => {
                    // Remove existing form if any
                    const existingForm = document.querySelector('.mysr-form')
                    if (existingForm) {
                        existingForm.innerHTML = ''
                    }
                    this.initializeMoyasar()
                })
            }
        }
    },
    methods: {
        initializeMoyasar() {
            if (typeof Moyasar === 'undefined') {
                console.error('Moyasar is not loaded')
                return
            }

            try {
                const callbackUrl = `${window.location.origin}/payment/callback`;
                Moyasar.init({
                    element: '.mysr-form',
                    amount: this.amount, // Amount is already in halalas
                    currency: 'SAR',
                    description: 'Order Payment',
                    publishable_api_key: this.publishableKey,
                    callback_url: callbackUrl,
                    methods: ['creditcard'],
                    skip_3ds: true
                })
                this.moyasarInitialized = true
            } catch (error) {
                console.error('Error initializing Moyasar:', error)
            }
        },
        close() {
            this.$emit('close')
        }
    }
}
</script>

<template>
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">الدفع الإلكتروني</h2>
                    <button @click="close" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">المبلغ الإجمالي:</span>
                        <span class="text-lg font-bold text-cyan-600">{{ new Intl.NumberFormat("ar-SA", {
                            style: "currency",
                            currency: "SAR",
                        }).format(displayAmount) }}</span>
                    </div>
                </div>

                <div class="mysr-form"></div>

                <div class="mt-4 text-center text-sm text-gray-500">
                    <p>الدفع آمن ومحمي بواسطة Moyasar</p>
                </div>
            </div>
        </div>
    </div>
</template>
