<template>
    <ul :class="{ 'hidden' : !show }" class="relative mt-1 space-y-1 before:content-[''] before:block before:border-l before:border-dotted before:border-l-gray-400 before:absolute before:bottom-0 before:top-0 before:left-2">
        <li :key="index" v-for="item, index in data" class="pl-4 relative before:last:bg-white before:last:h-auto before:last:bottom-0 before:content-[''] before:block before:border-t before:border-dotted before:border-t-gray-400 before:absolute before:bottom-0 before:top-2.5 before:left-2 before:w-5">
            <span class="caret flex items-center relative z-5">
                <input type="checkbox" v-if="checkbox" value="1" :id="item.id" :disabled="item.disabled" @change="$emit('check', item.id)" :checked="selectedIds.includes(item.id)" class="form-check-input appearance-none rounded-sm h-4 w-4 border border-gray-300 bg-white text-purple-800 hover:text-purple-600 hover:border-purple-600 focus:bg-purple-600 focus:border-purple-600 checked:bg-purple-600 checked:border-purple-600 focus:outline-none transition duration-200 align-top bg-no-repeat bg-center bg-contain float-left mr-3 cursor-pointer">
                <input type="radio" v-else :id="item.id" :disabled="item.disabled" @change="$emit('check', item.id)" :checked="selectedIds.includes(item.id)" name="flexRadioDefault" class="form-check-input appearance-none rounded-full h-4 w-4 border border-gray-300 bg-white text-purple-800 hover:text-purple-600 hover:border-purple-600 focus:bg-purple-600 focus:border-purple-600 checked:bg-purple-600 checked:border-purple-600 focus:outline-none transition duration-200 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer">
                <label :for="item.id" :class="{ 'opacity-50': item.disabled }" class="form-check-label inline-block text-gray-600 text-sm font-normal">{{ item.text }}</label>
                <a href="#" v-if="hasChildren(item)" @click.prevent="toggle(item)">
                    <svg xmlns="http://www.w3.org/2000/svg" :class="{ 'rotate-90' : item.open }" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </span>
            <tree-item v-if="hasChildren(item)" :data="item.children" :selected-ids="selectedIds" :checkbox="checkbox" @check="check" :show="item.open"></tree-item>
        </li>
    </ul>
</template>

<script>
export default {  
    name: "tree-item",

    props: {
        data: Object,
        selectedIds: Array,
        show: {
            type: Boolean,
            default: true
        },
        checkbox: Boolean
    },

    methods: {
        check(id) {
            this.$emit('check', id);
        },
        
        toggle: function(item) {
            item.open = !item.open;
        },

        hasChildren: function(item) {
            return item.hasOwnProperty('children') && item.children!==null && item.children.length;
        }
    }
}
</script>