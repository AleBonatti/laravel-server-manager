<script setup>
    import { Link } from '@inertiajs/vue3';
    import { HomeIcon, ChevronRightIcon } from '@heroicons/vue/20/solid';

    defineProps({
        title: { type: String },
        breadcrumbs: { type: Array, default: [] }
    })
</script>

<template>
    <div v-if="title!==null" class="px-4 py-4 sm:px-6 lg:px-8 border-b border-gray-200 bg-white">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-medium leading-ato text-gray-900 sm:truncate">{{ title }}</h1>
            </div>
        </div>
        <!-- <Breadcrumbs /> -->
        <nav v-if="breadcrumbs.length" class="flex" aria-label="Breadcrumb">
            <ol role="list" class="flex items-center space-x-2">
                <li>
                    <div>
                        <Link href="/admin/dashboard" class="text-gray-400 hover:text-gray-500">
                            <HomeIcon class="h-5 w-5 flex-shrink-0" aria-hidden="true" />
                            <span class="sr-only">Home</span>
                        </Link>
                    </div>
                </li>
                <li v-for="(item, i) in breadcrumbs" :key="i">
                    <div class="flex items-center">
                        <ChevronRightIcon class="h-5 w-5 flex-shrink-0 text-gray-400" aria-hidden="true" />
                        <Link v-if="item.hasOwnProperty('to')" :href="item.to" class="ml-2 text-sm font-medium text-gray-500 hover:text-purple-700">{{ item.label }}</Link>
                        <span v-else class="ml-2 text-sm font-medium text-gray-500">{{ item.label }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</template>