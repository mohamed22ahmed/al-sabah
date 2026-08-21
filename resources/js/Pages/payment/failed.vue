<script>
export default {
    props: {
        error: String
    }
}
</script>

<template>
    <div class="max-w-md mx-auto p-4 text-center">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <svg class="w-16 h-16 mx-auto mb-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h2 class="text-2xl font-bold mb-2">Payment Failed</h2>
            <p class="mb-4">{{ error || 'Your payment could not be processed. Please try again.' }}</p>
        </div>
        <div class="flex gap-4 justify-center">
            <button @click="$inertia.visit('/checkout')" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Try Again
            </button>
            <button @click="$inertia.visit('/')" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Go to Home
            </button>
        </div>
    </div>
</template>
