<script>
export default {
    props: {
        publishableKey: String,
        amount: Number
    },
    mounted() {
        Moyasar.init({
            element: '.mysr-form',
            amount: this.amount,
            currency: 'SAR',
            description: 'Order Payment',
            publishable_api_key: this.publishableKey,
            callback_url: window.location.origin + '/payment/callback',
            methods: ['creditcard', 'applepay', 'stcpay']
        });
    }
}
</script>

<template>
    <div class="max-w-md mx-auto p-4">
        <h2 class="text-xl font-bold mb-4">Checkout</h2>
        <div class="mysr-form"></div>
    </div>
</template>
