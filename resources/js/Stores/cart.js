import { defineStore } from 'pinia'
import axios from 'axios'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [],
    total: 0,
    subtotal: 0,
    itemsCount: 0,
    loading: false,
    error: null
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
      const subtotal = state.items.reduce((total, item) => total + (item.subtotal || 0), 0)
      const tax = subtotal * 0.15; // 15% tax
      // Add shipping cost if needed (currently free)
      return subtotal + tax
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

    async completeOrder({ name, phone, address }) {
      this.loading = true;
      this.error = null;
      try {
        // Prepare products array for the order
        const products = this.items.map(item => ({
          id: item.product_id,
          name: item.name,
          quantity: item.quantity,
          price: item.price,
        }));
        const subtotal = this.subtotal || this.calculatedSubtotal || 0;
        const taxes = subtotal * 0.15;
        const total = subtotal + taxes;
        const status = 'pending';
        const response = await axios.post('/admin/orders/store', {
          name,
          phone,
          address,
          products,
          taxes,
          total,
          status,
        });
        // On success, clear the cart
        if (response.data.message === 'تم حفظ الطلب بنجاح') {
          await this.clearCart();
        }
        return { success: true, message: response.data.message };
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
    }
  }
})
