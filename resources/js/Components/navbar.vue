<script>
import { ref, onMounted, onUnmounted } from 'vue';
import NavLink from "@/Components/NavLink.vue";
import { useLogoStore } from '@/Stores/logoStore';
import { useCartStore } from '@/Stores/cart';
import { router } from '@inertiajs/vue3';

export default {
    name: 'Navbar',
    components: {NavLink},
    props: {
        links: Array
    },
    setup() {
        const logoStore = useLogoStore();
        const cartStore = useCartStore();
        return {
            logoStore,
            cartStore
        };
    },
    data() {
        return {
            scrolled: false,
            mobileMenuOpen: false,
            searchQuery: '',
            searchResults: [],
            showSearchResults: false,
            loading: false
        };
    },
    methods: {
        onScroll() {
            this.scrolled = window.scrollY > 80;
        },
        toggleMobileMenu() {
            this.mobileMenuOpen = !this.mobileMenuOpen;
        },
        openMobileMenu() {
            this.mobileMenuOpen = true;
            // Prevent body scroll when menu is open
            document.body.style.overflow = 'hidden';
        },
        closeMobileMenu() {
            this.mobileMenuOpen = false;
            // Prevent body scroll when menu is closed
            document.body.style.overflow = '';
        },
        async searchProducts() {
            if (this.searchQuery.trim().length < 2) {
                this.searchResults = [];
                this.showSearchResults = false;
                return;
            }

            try {
                this.loading = true;
                const response = await fetch(`/api/search?q=${encodeURIComponent(this.searchQuery)}`);
                const data = await response.json();

                if (data.success) {
                    this.searchResults = data.data;
                    this.showSearchResults = data.data.length > 0;
                } else {
                    this.searchResults = [];
                    this.showSearchResults = false;
                }
            } catch (error) {
                console.error('Search error:', error);
                this.searchResults = [];
                this.showSearchResults = false;
            } finally {
                this.loading = false;
            }
        },
        selectSearchResult(result) {
            // Navigate to the category page that contains this product
            router.visit(result.category.url);
            this.searchQuery = '';
            this.showSearchResults = false;
            this.searchResults = [];
        },
        clearSearch() {
            this.searchQuery = '';
            this.showSearchResults = false;
            this.searchResults = [];
        },
        handleClickOutside(event) {
            if (!this.$el.contains(event.target)) {
                this.showSearchResults = false;
            }
        },
        navigateTo(url) {
            router.visit(url);
        }
    },
    async mounted() {
        window.addEventListener('scroll', this.onScroll);
        window.addEventListener('resize', this.closeMobileMenu);
        document.addEventListener('click', this.handleClickOutside);
        await this.cartStore.fetchCart();
    },
    unmounted() {
        window.removeEventListener('scroll', this.onScroll);
        window.removeEventListener('resize', this.closeMobileMenu);
        document.removeEventListener('click', this.handleClickOutside);
    },
    watch: {
        searchQuery: {
            handler() {
                // Debounce search
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => {
                    this.searchProducts();
                }, 300);
            }
        }
    }
};
</script>

<template>
    <nav :class="['navbar', { 'navbar--scrolled': scrolled }]">
        <div class="navbar__middle">
            <NavLink href="/cart" class="navbar__cart" style="color:#fff; position: relative;">
                <i class="fas fa-shopping-cart text-2xl"></i>
                <!-- Cart Count Badge -->
                <span v-if="cartStore?.itemsCount > 0" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                    {{ cartStore.itemsCount > 99 ? '99+' : cartStore.itemsCount }}
                </span>
            </NavLink>
            <div class="relative flex-1 mx-8">
                <input
                    v-model="searchQuery"
                    class="navbar__search"
                    type="text"
                    placeholder="ابحث عن المنتجات"
                    @focus="showSearchResults = searchResults.length > 0"
                />

                <!-- Search Results Dropdown -->
                <div v-if="showSearchResults" class="absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-lg shadow-lg z-50 mt-1">
                    <div v-if="loading" class="p-4 text-center text-gray-500">
                        جاري البحث...
                    </div>
                    <div v-else-if="searchResults.length === 0" class="p-4 text-center text-gray-500">
                        لا توجد نتائج
                    </div>
                    <div v-else class="max-h-60 overflow-y-auto">
                        <div
                            v-for="result in searchResults"
                            :key="result.id"
                            @click="selectSearchResult(result)"
                            class="p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-200 last:border-b-0"
                        >
                            <div class="font-semibold text-gray-800">{{ result.name }}</div>
                            <div class="text-sm text-gray-600">الكود: {{ result.code }}</div>
                            <div class="text-xs text-blue-600">الفئة: {{ result.category.name }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <img :src="logoStore.getLogo" :alt="logoStore.getLogoAlt" loading="lazy" decoding="async" class="navbar__logo max-h-12 h-auto w-auto" />

            <button class="navbar__burger" @click="openMobileMenu">
                <span class="navbar__burger-bar"></span>
                <span class="navbar__burger-bar"></span>
                <span class="navbar__burger-bar"></span>
            </button>
        </div>

        <div class="navbar__main navbar__main--desktop">
            <NavLink v-for="link in links.data" :key="link.url" :href="link.url" :active="link.active">
                {{ link.name }}
            </NavLink>
        </div>

        <!-- Mobile Menu Backdrop -->
        <transition name="fade" appear>
            <div v-if="mobileMenuOpen" class="mobile-menu-backdrop" @click="closeMobileMenu"></div>
        </transition>

        <!-- Mobile Menu -->
        <transition name="slide" appear>
            <div v-if="mobileMenuOpen" class="navbar__main navbar__main--mobile">
                <button class="navbar__close" @click="closeMobileMenu">✕</button>

                <!-- Logo and Brand Section -->
                <div class="mobile-menu-header">
                    <div class="mobile-logo">
                        <img :src="logoStore.getLogo" :alt="logoStore.getLogoAlt" class="mobile-logo-img" />
                    </div>
                    <h2 class="mobile-brand-name">الصباح</h2>
                    <p class="mobile-brand-subtitle">ALSABAH</p>
                </div>

                <!-- Categories Section - Main Focus -->
                <div v-if="links.data && links.data.length > 0" class="mobile-menu-section categories-section">
                    <div
                        v-for="category in links.data.filter(cat => cat.url !== '/')"
                        :key="category.id"
                        @click="navigateTo(category.url); closeMobileMenu()"
                        class="mobile-menu-item mobile-category-item"
                        :class="{ 'active': category.active }"
                    >
                        <span class="category-icon">☕</span>
                        <span class="category-text">{{ category.name }}</span>
                        <span v-if="category.hasSubmenu" class="submenu-arrow">‹</span>
                    </div>
                </div>

                <!-- Main Navigation Links - Compact -->
                <div class="mobile-menu-section main-nav-section">
                    <div
                        v-for="link in links"
                        :key="link.name"
                        @click="navigateTo(link.url); closeMobileMenu()"
                        class="mobile-menu-item mobile-nav-item"
                        :class="{ 'active': link.active }"
                    >
                    </div>
                </div>
            </div>
        </transition>
    </nav>
</template>

<style scoped>
.navbar {
    position: relative;
    top: 0;
    width: 100%;
    background: #a31f10;
    transition: box-shadow 0.2s, position 0.2s;
    z-index: 100;
    border-bottom: 1px solid #222;
}
.navbar--scrolled {
    position: fixed;
    box-shadow: 0 2px 8px rgba(0,0,0,0.09);
    top: 0;
    left: 0;
}
.navbar__top {
    display: flex;
    justify-content: flex-start;
    gap: 15px;
    margin-left: 70px;
    padding: 8px 24px;
}
.navbar__login {
    border: 1px solid #222;
    background: transparent;
    padding: 4px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}
.navbar__middle {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px;
    background: #a31f10;
    position: relative;
}
.navbar__cart {
    font-size: 30px;
}
.navbar__search {
    width: 100%;
    padding: 8px 16px;
    border-radius: 5px;
    border: 1px solid #bbb;
    direction: rtl;
    max-width: unset;
}
.navbar__logo {
    height: 48px;
}
.navbar__burger {
    display: none;
    background: none;
    border: none;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 3px;
    width: 36px;
    height: 36px;
    cursor: pointer;
    margin-right: 10px;
}
.navbar__burger-bar {
    width: 24px;
    height: 3px;
    background: #222;
    border-radius: 2px;
    display: block;
    transition: all 0.2s;
}
.navbar__main {
    display: flex;
    justify-content: center;
    gap: 40px;
    background: #a31f10;
    border-top: 1px solid #111;
    font-size: 18px;
    font-family: inherit;
    padding: 10px 0;
    direction: rtl;
}
.navbar__main a {
    color:#fff;
    text-decoration: none;
    font-weight: 600;
}
.navbar__main--mobile {
    flex-direction: column;
    align-items: flex-start;
    position: fixed;
    top: 0;
    right: 0;
    width: 80%;
    height: 100vh;
    background: #fff;
    z-index: 200;
    padding: 0;
    box-shadow: -4px 0 20px rgba(0,0,0,0.15);
    overflow-y: auto;
}

/* Mobile Menu Backdrop */
.mobile-menu-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 150;
    backdrop-filter: blur(2px);
}

/* Fade Transition for Backdrop */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.fade-enter-to,
.fade-leave-from {
    opacity: 1;
}

/* Slide Transition */
.slide-enter-active,
.slide-leave-active {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.slide-enter-from {
    transform: translateX(100%);
    opacity: 0;
}

.slide-leave-to {
    transform: translateX(100%);
    opacity: 0;
}

.slide-enter-to,
.slide-leave-from {
    transform: translateX(0);
    opacity: 1;
}

/* Mobile Menu Header - Centered */
.mobile-menu-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 35px 15px 20px;
    border-bottom: 1px solid #f0f0f0;
}

.mobile-logo {
    display: flex;
    justify-content: center;
    margin-bottom: 15px;
}

.mobile-logo-img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    border: 3px solid #fff;
}

.mobile-brand-name {
    font-size: 24px;
    font-weight: 800;
    color: #333;
    margin-bottom: 8px;
    text-align: center;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.mobile-brand-subtitle {
    font-size: 16px;
    color: #666;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 2px;
    font-family: 'Arial', sans-serif;
    text-align: center;
}

/* Mobile Menu Sections */
.mobile-menu-section {
    width: 100%;
    padding: 15px 0;
}

.categories-section {
    border-bottom: 1px solid #f0f0f0;
}

.main-nav-section {
    padding-top: 10px;
}

/* Mobile Menu Items */
.mobile-menu-item {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: #333;
    text-decoration: none;
    font-weight: 500;
    font-size: 16px;
    transition: all 0.2s ease;
    cursor: pointer;
    position: relative;
    border-bottom: 1px solid #f8f8f8;
}

.mobile-menu-item:last-child {
    border-bottom: none;
}

.mobile-menu-item:hover {
    background: #f8f9fa;
}

.mobile-menu-item.active {
    background: #e9ecef;
    color: #495057;
    font-weight: 600;
}

/* Category Items */
.mobile-category-item {
    padding: 14px 20px;
}

.category-icon {
    font-size: 18px;
    margin-left: 15px;
    width: 24px;
    text-align: center;
}

.category-text {
    flex: 1;
    text-align: right;
    direction: rtl;
}

.submenu-arrow {
    font-size: 20px;
    color: #999;
    margin-right: 10px;
    transform: rotate(90deg);
}

/* Navigation Items */
.mobile-nav-item {
    padding: 10px 20px;
}

.nav-icon {
    font-size: 16px;
    margin-left: 15px;
    width: 24px;
    text-align: center;
}

.nav-text {
    flex: 1;
    text-align: right;
    direction: rtl;
}

/* Close Button */
.navbar__close {
    position: absolute;
    top: 15px;
    left: 15px;
    background: none;
    border: none;
    font-size: 24px;
    color: #666;
    cursor: pointer;
    z-index: 10;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.navbar__close:hover {
    background: #f0f0f0;
    color: #333;
}

@media (max-width: 900px) {
    .navbar__main--desktop {
        display: none !important;
    }
    .navbar__main--mobile {
        display: flex !important;
    }
    .navbar__middle {
        gap: 8px;
    }
    .navbar__search {
        margin: 0 10px;
        max-width: 120px;
    }
    .navbar__logo {
        height: 36px;
    }
    .navbar__burger {
        display: flex;
    }

    /* Mobile menu responsive adjustments */
    .navbar__main--mobile {
        width: 85%;
    }

    .mobile-menu-header {
        padding: 35px 15px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .mobile-logo-img {
        width: 70px;
        height: 70px;
    }

    .mobile-brand-name {
        font-size: 22px;
        text-align: center;
    }

    .mobile-brand-subtitle {
        font-size: 15px;
        letter-spacing: 1.5px;
        text-align: center;
    }

    .mobile-menu-item {
        font-size: 15px;
        padding: 10px 15px;
    }

    .mobile-category-item {
        padding: 12px 15px;
    }

    .mobile-nav-item {
        padding: 8px 15px;
    }
}

@media (max-width: 480px) {
    .navbar__main--mobile {
        width: 90%;
        padding: 0;
    }

    .mobile-menu-header {
        padding: 30px 12px 15px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .mobile-logo-img {
        width: 65px;
        height: 65px;
    }

    .mobile-brand-name {
        font-size: 20px;
        text-align: center;
    }

    .mobile-brand-subtitle {
        font-size: 14px;
        letter-spacing: 1px;
        text-align: center;
    }

    .mobile-menu-item {
        font-size: 14px;
        padding: 8px 12px;
    }

    .mobile-category-item {
        padding: 10px 12px;
    }

    .mobile-nav-item {
        padding: 6px 12px;
    }

    .category-icon {
        font-size: 16px;
        margin-left: 12px;
    }

    .nav-icon {
        font-size: 14px;
        margin-left: 12px;
    }
}

@media (min-width: 901px) {
    .navbar__main--mobile {
        display: none !important;
    }
    .navbar__main--desktop {
        display: flex !important;
    }
    .navbar__burger {
        display: none !important;
    }
}
</style>
