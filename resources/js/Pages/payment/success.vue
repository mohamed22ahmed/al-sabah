<script>
import { useCartStore } from "@/Stores/cart";

export default {
    props: {
        message: String
    },
    data() {
        return {
            cartStore: null
        }
    },
    async mounted() {
        this.cartStore = useCartStore();
        // Clear cart after successful payment
        await this.cartStore.clearCartAfterPayment();

        // Redirect to home after 3 seconds
        setTimeout(() => {
            this.$inertia.visit('/');
        }, 3000);
    }
}
</script>

<template>
    <div class="max-w-md mx-auto p-4 text-center">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <svg class="w-16 h-16 mx-auto mb-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h2 class="text-2xl font-bold mb-2">Payment Successful!</h2>
            <p class="mb-4">{{ message || 'Your payment has been processed successfully.' }}</p>
            <p class="text-sm text-green-600">You will be redirected to the home page in 3 seconds...</p>
        </div>
        <button @click="$inertia.visit('/')" class="mt-4 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
            Go to Home
        </button>
    </div>
</template>
