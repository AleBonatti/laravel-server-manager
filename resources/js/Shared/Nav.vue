<script setup>
    import { ref } from 'vue';
    import { Link } from '@inertiajs/vue3';
    import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
    import { ChevronUpDownIcon } from '@heroicons/vue/20/solid';
</script>

<template>
    <!-- User account dropdown -->
    <div class="hidden bg-cover bg-pic lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col lg:border-r lg:border-gray-200 lg:bg-gray-100">
        <div class="flex flex-shrink-0 items-center px-6 py-6 bg-black/70">
            <a href="/admin/dashboard">
                <img class="h-8 w-auto" src="/assets/loghi/logo.svg" alt="Methlab" />
            </a>
        </div>
        <!-- Sidebar component, swap this element with another sidebar if you like -->
        <div class="flex h-0 flex-1 flex-col overflow-y-auto pt-1 bg-black/70">
            <!-- User account dropdown -->
            <Menu as="div" class="relative inline-block px-3 text-left">
                <div>
                    <MenuButton class="group w-full rounded-md bg-gray-600/80 px-3.5 py-2 text-left text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-gray-700">
                        <span class="flex w-full items-center justify-between">
                        <span class="flex min-w-0 items-center justify-between space-x-3">
                            <!-- <img v-if="store.user.profile_photo_url!==null" :src="store.user.profile_photo_url" :alt="store.user.nome" class="inline-block h-12 w-12 rounded-full"> -->
                            <img class="h-10 w-10 flex-shrink-0 rounded-full bg-gray-300" src="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=3&w=256&h=256&q=80" alt="" />
                            <span class="flex min-w-0 flex-1 flex-col">
                                <span class="truncate text-sm font-medium text-gray-200 group-hover:text-gray-900">{{ $page.props.auth.user.name }}</span>
                            </span>
                        </span>
                        <ChevronUpDownIcon class="h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500" aria-hidden="true" />
                        </span>
                    </MenuButton>
                </div>
                <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                <MenuItems class="absolute right-0 left-0 z-10 mx-3 mt-1 origin-top divide-y divide-gray-200 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div class="py-1">
                    <MenuItem v-slot="{ active, close }">
                        <Link href="/admin/account" @click="close" :class="[active ? 'text-purple-600' : 'text-gray-700', 'block px-4 py-2 text-sm']">Il mio account</Link>
                    </MenuItem>
                    <MenuItem v-slot="{ active, close  }">
                        <Link href="/admin/settings" @click="close" :class="[active ? 'text-purple-600' : 'text-gray-700', 'block px-4 py-2 text-sm']">Impostazioni</Link>
                    </MenuItem>
                    </div>
                    <div class="py-1">
                    <MenuItem>
                        <Link href="/logout" as="button" method="post" class="hover:text-purple-600 text-gray-700 block px-4 py-2 text-sm">Logout</Link>
                    </MenuItem>
                    </div>
                </MenuItems>
                </transition>
            </Menu>
            <!-- Navigation -->
            <nav class="mt-6 px-3">
                <div class="space-y-1">
                    <div  class="border-b border-b-gray-100/20 mt-3 pb-4 first:cursor-auto">
                        <!-- se ha figli -->
                        <div>
                            <span class="-mb-2.5 text-gray-400 uppercase group flex items-center px-2 py-2 text-sm font-medium rounded-md mt-2.5">
                                Pizzetta
                            </span>
                            <ul class="px-7 space-y-1 mt-3">
                                <li>
                                    <!-- text-gray-300, text-purple-400 -->
                                    <Link href="/admin/dashboard" :class="[$page.url==='/admin/dashboard' ? 'text-purple-400': 'text-gray-300', 'text-sm font-medium hover:text-purple-400 focus:text-purple-400 flex items-center']" >
                                        <span class="w-1 h-1 bg-white/30 block rounded-full mr-3"></span>
                                        Dashboard
                                    </Link>
                                </li>
                                <li>
                                    <Link href="/admin/cazzi" :class="[$page.url==='/admin/cazzi' ? 'text-purple-400': 'text-gray-300', 'text-sm font-medium hover:text-purple-400 focus:text-purple-400 flex items-center']" >
                                        <span class="w-1 h-1 bg-white/30 block rounded-full mr-3"></span>
                                        Cazzi
                                    </Link>
                                </li>
                                <li>
                                    <Link href="/logout" as="button" method="post" class="text-sm font-medium text-gray-300 hover:text-purple-400 focus:text-purple-400 flex items-center" >
                                        <span class="w-1 h-1 bg-white/30 block rounded-full mr-3"></span>
                                        Logout
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- log errori è solo su desktop -->
                    <div class="border-b border-b-gray-100/20 mt-3 pb-4">
                        <a href="/admin/log-viewer" target="_blank" class="-mb-2.5 text-gray-400 hover:text-white hover:bg-gray-100/20 uppercase group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            Log errori
                        </a>
                    </div>
                </div>
            </nav>
            <div class="mt-8">
                <img class="h-7 m-auto" src="/assets/loghi/logo-seppia-white.png" alt="Seppia CMS" />
                <p class="text-xs text-gray-300 text-center mt-4">SeppiaCms v6.0 - &copy; 2023 </p>
            </div>
        </div>
    </div>
</template>