import { defineStore } from 'pinia'
import axios from 'axios'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [],
    total: 0,
    subtotal: 0,
    itemsCount: 0,
    loading: false,
    error: null,
    showPaymentModal: false,
    paymentData: {
      publishableKey: null,
      amount: 0,
      orderData: null
    }
  }),

  getters: {
    isEmpty: (state) => state.items.length === 0,
    getItemById: (state) => (productId) => {
      return state.items.find(item => item.product_id === productId)
    },
    calculatedSubtotal: (state) => {
      return state.items.reduce((total, item) => total + (item.subtotal || 0), 0)
    },
    calculatedTotal: (state) => {
      return state.items.reduce((total, item) => total + (item.subtotal || 0), 0)
    },
    calculatedItemsCount: (state) => {
      return state.items.length // Number of unique products
    }
  },

  actions: {
    async fetchCart() {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get('/api/cart')
        this.items = response.data.items || []
        this.total = response.data.total || 0
        this.subtotal = response.data.subtotal || 0
        this.itemsCount = response.data.items_count || 0
      } catch (error) {
        this.error = error.response?.data?.message || 'حدث خطأ في تحميل السلة'
        console.error('Error fetching cart:', error)
      } finally {
        this.loading = false
      }
    },

    async addToCart(productId, quantity = 1) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.post('/api/cart/add', {
          product_id: productId,
          quantity: quantity
        })

        if (response.data.success) {
          this.items = response.data.cart.items || []
          this.total = response.data.cart.total || 0
          this.subtotal = response.data.cart.subtotal || 0
          this.itemsCount = response.data.items_count || 0
          return { success: true, message: response.data.message }
        }
      } catch (error) {
        this.error = error.response?.data?.message || 'حدث خطأ في إضافة المنتج'
        console.error('Error adding to cart:', error)
        return { success: false, message: this.error }
      } finally {
        this.loading = false
      }
    },

    async updateQuantity(itemId, quantity) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.put(`/api/cart/update/${itemId}`, {
          quantity: quantity
        })

        if (response.data.success) {
          this.items = response.data.cart.items || []
          this.total = response.data.cart.total || 0
          this.subtotal = response.data.cart.subtotal || 0
          this.itemsCount = response.data.items_count || 0
          return { success: true, message: response.data.message }
        }
      } catch (error) {
        this.error = error.response?.data?.message || 'حدث خطأ في تحديث الكمية'
        console.error('Error updating quantity:', error)
        return { success: false, message: this.error }
      } finally {
        this.loading = false
      }
    },

    async removeFromCart(itemId) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.delete(`/api/cart/remove/${itemId}`)

        if (response.data.success) {
          this.items = response.data.cart.items || []
          this.total = response.data.cart.total || 0
          this.subtotal = response.data.cart.subtotal || 0
          this.itemsCount = response.data.items_count || 0
          return { success: true, message: response.data.message }
        }
      } catch (error) {
        this.error = error.response?.data?.message || 'حدث خطأ في حذف المنتج'
        console.error('Error removing from cart:', error)
        return { success: false, message: this.error }
      } finally {
        this.loading = false
      }
    },

    async clearCart() {
      this.loading = true
      this.error = null
      try {
        const response = await axios.delete('/api/cart/clear')

        if (response.data.success) {
          this.items = response.data.items || []
          this.total = response.data.total || 0
          this.subtotal = response.data.cart.subtotal || 0
          this.itemsCount = response.data.items_count || 0
          return { success: true, message: response.data.message }
        }
      } catch (error) {
        this.error = error.response?.data?.message || 'حدث خطأ في تفريغ السلة'
        console.error('Error clearing cart:', error)
        return { success: false, message: this.error }
      } finally {
        this.loading = false
      }
    },

    async completeOrder({ name, phone, address, zone, delivery_cost}) {
        console.log('Completing order with:', { name, phone, address, zone, delivery_cost });
      this.loading = true;
      this.error = null;
      try {
        const products = this.items.map(item => ({
          id: item.product_id,
          name: item.name,
          quantity: item.quantity,
          price: item.price,
        }));
        const subtotal = this.subtotal || this.calculatedSubtotal || 0;
        const orderData = {
          name,
          phone,
          address,
          zone,
          delivery_cost,
          products,
          subtotal,
        };

        return { success: true, message: 'تم إعداد الطلب للدفع', orderData };
      } catch (error) {
        this.error = error.response?.data?.message || 'حدث خطأ أثناء إتمام الطلب';
        console.error('Error completing order:', error);
        return { success: false, message: this.error };
      } finally {
        this.loading = false;
      }
    },

    clearError() {
      this.error = null
    },

    showPayment(orderData = null) {
      this.paymentData.orderData = orderData
      this.showPaymentModal = true
    },

    async clearCartAfterPayment() {
      await this.clearCart()
    },

    hidePayment() {
      this.showPaymentModal = false
      this.paymentData = {
        publishableKey: null,
        amount: 0,
        orderData: null
      }
    },

    async fetchPaymentConfig() {
      try {
        const response = await axios.get('/checkout', {
          params: {
            amount: this.paymentData.amount,
            order_data: JSON.stringify(this.paymentData.orderData)
          }
        })
        this.paymentData.publishableKey = response.data.publishable_key
        return { success: true }
      } catch (error) {
        this.error = error.response?.data?.message || 'فشل في تحميل إعدادات الدفع'
        return { success: false, message: this.error }
      }
    }
  }
})
