<script setup>
    const props = defineProps({
        id: { type:Number, required: false, default: null },
        selected: { type: String, default: null },
        tabs: { type: Array, default: [] }
    });
</script>
<template>
    <div class="mt-6 grid grid-cols-1 lg:gap-6 sm:px-6 lg:grid-flow-col-dense lg:grid-cols-3 3xl:max-w-screen-3xl 3xl:mx-auto">
        <div class="space-y-6 lg:col-span-2 lg:col-start-1">
            <div class="sm:hidden mb-3 px-3">
                <label for="tabs" class="sr-only">Select a tab</label>
                <select id="tabs" name="tabs" class="block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                    <option :key="i" v-for="tab,i in tabs" :selected="tab.current">{{ tab.label }}</option>
                </select>
            </div>
            <div class="hidden sm:block">
                <nav class="isolate flex divide-x divide-gray-200 rounded-tl-lg rounded-tr-lg" aria-label="Tabs">
                    <router-link :key="i" v-for="tab,i in tabs" :to="tab.url.replace('{id}', id)" :class="[selected===tab.key?'rounded-t-lg bg-white text-gray-900 hover:text-purple-600':'bg-transparent', { 'pointer-events-none cursor-not-allowed' : id===null }, 'text-gray-500 hover:text-purple-600 group relative min-w-0 overflow-hidden py-4 px-6 text-sm font-medium text-center focus:z-10']">
                        <span>{{ tab.label }}</span>
                        <span v-if="selected===tab.key" aria-hidden="true" class="bg-transparent absolute inset-x-0 bottom-0 h-0.5"></span>
                    </router-link>
                </nav>
            </div>
        </div>
        <div class="lg:col-span-1 lg:col-start-3"></div>
    </div>
</template>