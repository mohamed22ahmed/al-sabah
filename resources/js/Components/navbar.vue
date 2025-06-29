<script>
import { ref, onMounted, onUnmounted } from 'vue';
import NavLink from "@/Components/NavLink.vue";

export default {
    name: 'Navbar',
    components: {NavLink},
    props: {
        logo: String,
        links: Array,
    },
    data() {
        return {
            scrolled: false,
            mobileMenuOpen: false,
        };
    },
    methods: {
        onScroll() {
            this.scrolled = window.scrollY > 80;
        },
        toggleMobileMenu() {
            this.mobileMenuOpen = !this.mobileMenuOpen;
        },
        closeMobileMenu() {
            this.mobileMenuOpen = false;
        }
    },
    mounted() {
        window.addEventListener('scroll', this.onScroll);
        window.addEventListener('resize', this.closeMobileMenu);
    },
    unmounted() {
        window.removeEventListener('scroll', this.onScroll);
        window.removeEventListener('resize', this.closeMobileMenu);
    },
};
</script>

<template>
    <nav :class="['navbar', { 'navbar--scrolled': scrolled }]">
        <div class="navbar__middle">
            <NavLink href="/cart" class="navbar__cart">🛒</NavLink>
            <input class="navbar__search" type="text" placeholder="ابحث عن المنتجات" />
            <img :src="logo" alt="al-sabah" class="navbar__logo" />

            <button class="navbar__burger" @click="toggleMobileMenu" aria-label="Open menu">
                <span :class="{'navbar__burger-bar': true, 'navbar__burger-bar--open': mobileMenuOpen}"></span>
                <span :class="{'navbar__burger-bar': true, 'navbar__burger-bar--open': mobileMenuOpen}"></span>
                <span :class="{'navbar__burger-bar': true, 'navbar__burger-bar--open': mobileMenuOpen}"></span>
            </button>
        </div>

        <div class="navbar__main navbar__main--desktop">
            <NavLink v-for="link in links" :key="link.url" :href="link.url" :active="link.active">
                {{ link.name }}
            </NavLink>
        </div>

        <transition name="slide">
            <div v-if="mobileMenuOpen" class="navbar__main navbar__main--mobile">
                <button class="navbar__close" @click="closeMobileMenu">✕</button>
                <NavLink v-for="link in links" :key="link.name" :href="link.url" :active="link.active" @click.native="closeMobileMenu">
                    {{ link.name }}
                </NavLink>
            </div>
        </transition>
    </nav>
</template>

<style scoped>
.navbar {
    position: relative;
    top: 0;
    width: 100%;
    background: #cfd4cf;
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
    background: #cfd4cf;
    position: relative;
}
.navbar__cart {
    font-size: 30px;
}
.navbar__search {
    flex: 1;
    margin: 0 30px;
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
    background: white;
    border-top: 1px solid #111;
    font-size: 18px;
    font-family: inherit;
    padding: 10px 0;
    direction: rtl;
}
.navbar__main a {
    color: #222;
    text-decoration: none;
    font-weight: 600;
}
.navbar__main--mobile {
    flex-direction: column;
    align-items: flex-end;
    position: absolute;
    top: 100%;
    right: 0;
    left: 0;
    background: #fff;
    z-index: 200;
    padding: 20px 16px 16px 16px;
    gap: 24px;
    border-bottom: 1px solid #bbb;
    box-shadow: 0 8px 16px 0 rgba(0,0,0,0.04);
}
.navbar__close {
    background: none;
    border: none;
    font-size: 32px;
    color: #222;
    position: absolute;
    top: 8px;
    left: 12px;
    cursor: pointer;
    z-index: 10;
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

/* Transition for mobile menu */
.slide-enter-active, .slide-leave-active {
    transition: all 0.3s cubic-bezier(.25,.8,.25,1);
}
.slide-enter-from, .slide-leave-to {
    opacity: 0;
    transform: translateY(-16px);
}
.slide-enter-to, .slide-leave-from {
    opacity: 1;
    transform: translateY(0);
}
</style>
