

<script>
import { ChevronLeftIcon, ChevronDoubleLeftIcon, ChevronRightIcon, ChevronDoubleRightIcon } from '@heroicons/vue/20/solid';

export default {
    components: { ChevronLeftIcon, ChevronRightIcon, ChevronDoubleLeftIcon, ChevronDoubleRightIcon },
    props: {
        pagination: { type: Object, required: true },
        offset: { type: Number, default: 4 },
        showCounter: { type: Boolean, default: true }// per ora non è usato
    },
    computed: {
        pages() {
            if (!this.pagination.to) {
                return null;
            }
            let from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }
            let to = from + this.offset * 2;
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }
            let pages = [];
            for (let page = from; page <= to; page++) {
                pages.push(page);
            }
            return pages;
        },
        onFirstPage: function () {
            return this.pagination.current_page===1;
        },
        onLastPage: function () {
            return this.pagination.current_page===this.pagination.last_page;
        }
    },
    methods: {
        change: function (page) {
            this.$emit("paginate", page);
        },
    },
};
</script>
<template>
    <div v-if="pagination.last_page > 1" class="flex justify-end w-full border-t border-gray-300 mt-8">
        <nav class="flex items-center justify-between px-4 sm:px-0 w-5/12 mx-auto">
            <div class="-mt-px flex w-0 flex-1">
                <a href="#" v-if="!onFirstPage" @click.prevent="change(pagination.first_page)" class="inline-flex items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-700 hover:border-gray-400 hover:text-gray-700">
                <ChevronDoubleLeftIcon class="mr-3 h-5 w-5 text-gray-400" aria-hidden="true" />
                </a>
                <a href="#" v-if="!onFirstPage" @click.prevent="change(pagination.current_page-1)" class="inline-flex items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-700 hover:border-gray-400 hover:text-gray-700">
                <ChevronLeftIcon class="mr-3 h-5 w-5 text-gray-400" aria-hidden="true" />
                </a>
            </div>
            <div class="hidden md:-mt-px md:flex">
                <!-- <a href="#" class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500 hover:border-gray-400 hover:text-gray-700">1</a> -->
                <!-- Current: "border-purple-500 text-purple-600", Default: "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-400" -->
                <!-- <a href="#" class="inline-flex items-center border-t-2 border-purple-500 px-4 pt-4 text-sm font-medium text-purple-600" aria-current="page">2</a>
                <a href="#" class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500 hover:border-gray-400 hover:text-gray-700">3</a>
                <span class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500">...</span>
                <a href="#" class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500 hover:border-gray-400 hover:text-gray-700">8</a>
                <a href="#" class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500 hover:border-gray-400 hover:text-gray-700">9</a>
                <a href="#" class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500 hover:border-gray-400 hover:text-gray-700">10</a> -->
                <a href="#" :key="page" v-for="page in pages" @click.prevent="change(page)" :class="[page===pagination.current_page ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-400', 'inline-flex items-center border-t-2 px-4 pt-4 text-sm font-medium']">{{ page }}</a>
            </div>
            <div class="-mt-px flex w-0 flex-1 justify-end">
                <a href="#" v-if="!onLastPage" @click.prevent="change(pagination.current_page+1)" class="inline-flex items-center border-t-2 border-transparent pt-4 pl-1 text-sm font-medium text-gray-500 hover:border-gray-400 hover:text-gray-700">
                <ChevronRightIcon class="ml-3 h-5 w-5 text-gray-400" aria-hidden="true" />
                </a>
                <a href="#" v-if="!onLastPage" @click.prevent="change(pagination.last_page)" class="inline-flex items-center border-t-2 border-transparent pt-4 pl-1 text-sm font-medium text-gray-500 hover:border-gray-400 hover:text-gray-700">
                <ChevronDoubleRightIcon class="ml-3 h-5 w-5 text-gray-400" aria-hidden="true" />
                </a>
            </div>
        </nav>
    </div>

</template>