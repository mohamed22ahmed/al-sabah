<script>
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { io } from 'socket.io-client';


export default {
    components: {
        Dropdown,
        DropdownLink,
        NavLink,
        ResponsiveNavLink,
        Link
    },
    data() {
        return {
            showingNavigationDropdown: false,
            showingNotifications: false,
            user_id: 0,
            message: 'New order #1234 received',
            notifications: []
        };
    },
    computed: {
        unreadCount() {
            return this.notifications.filter(n => !n.read).length;
        }
    },

    mounted(){
        this.getNotifications()
        axios.get(route('notifications.getAuthUserId')).then((response) => {
            this.user_id = response.data
            const socket = io('http://localhost:3000');
            socket.emit('register', this.user_id);
            socket.on(`notification:${this.user_id}`, (data) => {
                console.log("Notification received:", data);
                this.notifications.unshift(data);
            });
        })
    },

    methods:{
        getNotifications() {
            axios.get(route('notifications.getNotifications'))
                .then((response) => {
                    this.notifications = response.data.data;
                });
        },

        markAllAsRead(){
            axios.post(route('notifications.MarkAllAsRead'))
                .then((response) => {
                    this.notifications = response.data.data;
                });
        },

        markAsRead(id){
            axios.post(route('notifications.MarkAsRead', id))
                .then((response) => {
                    this.notifications = response.data.data;
                });
        }
    }
};
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="border-b border-gray-100 bg-white">
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('home')">
                                    PharmaBridge
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('dashboard.index')" :active="route().current('dashboard.index')">
                                    Home
                                </NavLink>
                                <NavLink :href="route('medications.index')" :active="route().current('medications.index')">
                                    Medications
                                </NavLink>
                                <NavLink :href="route('offers.index')" :active="route().current('offers.index')">
                                    Offers
                                </NavLink>
                                <NavLink :href="route('orders.index')" :active="route().current('orders.index')">
                                    Orders
                                </NavLink>
                                <NavLink :href="route('ratings.index')" :active="route().current('ratings.index')">
                                    Ratings
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- Notifications Dropdown -->
                            <div class="relative me-3">
                                <Dropdown align="right" width="500" v-model:show="showingNotifications">
                                    <template #trigger>
                                        <button
                                            type="button"
                                            class="relative rounded-full bg-white p-1 text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                        >
                                            <span class="sr-only">View notifications</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            </svg>
                                            <span v-if="unreadCount > 0" class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-medium text-white">
                        {{ unreadCount }}
                      </span>
                                        </button>
                                    </template>

                                    <template #content>
                                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                                <button v-if="unreadCount > 0" class="text-xs font-medium text-blue-600" @click="markAllAsRead"> Mark As Read</button>
                                            </div>
                                        </div>
                                        <div class="max-h-96 overflow-y-auto">
                                            <template v-if="notifications.length > 0">
                                                <div v-for="notification in notifications" :key="notification.id" class="border-b border-gray-100 last:border-0">
                                                    <div class="flex px-4 mt-1 py-3 hover:bg-gray-50 cursor-pointer" :class="{ 'bg-blue-100': !notification.read }" @click="markAsRead(notification.id)">
                                                        <div class="flex-shrink-0 mr-3">
                              <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-purple-100">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                  </svg>
                              </span>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                                {{ notification.message }}
                                                            </p>
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                {{ notification.time_ago }}
                                                            </p>
                                                        </div>
                                                        <div v-if="!notification.read" class="ml-2 flex-shrink-0">
                                                            <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                            <div v-else class="px-4 py-6 text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                </svg>
                                                <h3 class="mt-2 text-sm font-medium text-gray-900">No notifications</h3>
                                                <p class="mt-1 text-xs text-gray-500">We'll notify you when something arrives.</p>
                                            </div>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                    <span class="inline-flex rounded-md">
                        <button
                            type="button"
                            class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            {{ $page.props.auth.user.name }}

                            <svg
                                class="-me-0.5 ms-2 h-4 w-4"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                    </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path :class="{
                          hidden: showingNavigationDropdown,
                          'inline-flex': !showingNavigationDropdown,
                      }"
                                          stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path :class="{
                          hidden: !showingNavigationDropdown,
                          'inline-flex': showingNavigationDropdown,
                      }"
                                          stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div :class="{
                block: showingNavigationDropdown,
                hidden: !showingNavigationDropdown,
            }"
                     class="sm:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            :href="route('dashboard.index')"
                            :active="route().current('dashboard.index')"
                        >
                            Home
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div
                        class="border-t border-gray-200 pb-1 pt-4"
                    >
                        <div class="px-4">
                            <div class="text-base font-medium text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <header
                class="bg-white shadow"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
