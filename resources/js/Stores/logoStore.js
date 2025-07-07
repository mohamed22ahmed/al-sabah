import { defineStore } from 'pinia';

export const useLogoStore = defineStore('logo', {
    state: () => ({
        logoUrl: '/images/elsabah.png', // Default logo
        logoAlt: 'Al Sabah Logo',
        logoHeight: '48px',
        logoWidth: 'auto'
    }),

    getters: {
        getLogo: (state) => state.logoUrl,
        getLogoAlt: (state) => state.logoAlt,
        getLogoSize: (state) => ({
            height: state.logoHeight,
            width: state.logoWidth
        })
    },

    actions: {
        setLogo(url, alt = 'Logo') {
            this.logoUrl = url;
            this.logoAlt = alt;
        },

        setLogoSize(height, width = 'auto') {
            this.logoHeight = height;
            this.logoWidth = width;
        },

        resetLogo() {
            this.logoUrl = '/images/elsabah.png';
            this.logoAlt = 'Al Sabah Logo';
            this.logoHeight = '48px';
            this.logoWidth = 'auto';
        }
    }
});
