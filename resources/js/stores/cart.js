import { defineStore } from 'pinia'
import axios from 'axios'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [],
    total: 0,
    itemsCount: 0,
    loading: false,
    error: null
  }),

  getters: {
    isEmpty: (state) => state.items.length === 0,
    getItemById: (state) => (productId) => {
      return state.items.find(item => item.product_id === productId)
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
          this.items = []
          this.total = 0
          this.itemsCount = 0
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

    clearError() {
      this.error = null
    }
  }
})
