<script setup>
    import { useNavMenuStore } from '@/stores/SeppiaNavMenu';
    import { HomeIcon, ChevronRightIcon } from '@heroicons/vue/20/solid';

    let store = useNavMenuStore();
</script>
<template>
    <nav class="flex" aria-label="Breadcrumb">
        <ol role="list" class="flex items-center space-x-2">
            <li>
                <div>
                    <router-link to="/admin/dashboard" class="text-gray-400 hover:text-gray-500">
                        <HomeIcon class="h-5 w-5 flex-shrink-0" aria-hidden="true" />
                        <span class="sr-only">Home</span>
                    </router-link>
                </div>
            </li>
            <li v-for="(item, i) in store.breadcrumbs" :key="i">
                <div class="flex items-center">
                    <ChevronRightIcon class="h-5 w-5 flex-shrink-0 text-gray-400" aria-hidden="true" />
                    <router-link v-if="item.hasOwnProperty('to')" :to="item.to" class="ml-2 text-sm font-medium text-gray-500 hover:text-purple-700">{{ item.label }}</router-link>
                    <span v-else class="ml-2 text-sm font-medium text-gray-500">{{ item.label }}</span>
                </div>
            </li>
        </ol>
    </nav>
</template>