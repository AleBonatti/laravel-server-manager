

<script>
import { ChevronLeftIcon, ChevronDoubleLeftIcon, ChevronRightIcon, ChevronDoubleRightIcon } from '@heroicons/vue/20/solid';

export default {
    components: { ChevronLeftIcon, ChevronRightIcon, ChevronDoubleLeftIcon, ChevronDoubleRightIcon },
    props: {
        pagination: { type: Object, required: true },
        showCounter: { type: Boolean, default: true }
    },
    computed: {
        pages() {
            if (!this.pagination.to) {
                return null;
            }
            let from = this.pagination.current_page - this.pagination.offset;
            if (from < 1) {
                from = 1;
            }
            let to = from + this.pagination.offset * 2;
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
<div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
    <div class="flex flex-1 justify-between sm:hidden">
        <a href="#" v-if="!onFirstPage" @click.prevent="change(pagination.current_page-1)" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Indietro</a>
        <a href="#" v-if="!onLastPage" @click.prevent="change(pagination.current_page+1)" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Avanti</a>
    </div>
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
        <div class="p-4">
            <p v-show="showCounter" class="text-sm text-gray-500">
                Elementi da <span class="font-medium">{{ pagination.from }}</span> a <span class="font-medium">{{ pagination.to }}</span> di <span class="font-medium">{{ pagination.total }}</span>
            </p>
        </div>
        <div v-if="pagination.last_page > 1">
            <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                <a href="#" v-if="!onFirstPage" @click.prevent="change(pagination.first_page)" class="relative inline-flex items-center rounded-l-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-20">
                    <span class="sr-only">Inizio</span>
                    <ChevronDoubleLeftIcon class="h-5 w-5 text-gray-500" aria-hidden="true" />
                </a>
                <div v-else class="relative inline-flex items-center rounded-l-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500 focus:z-20">
                    <span class="sr-only">Inizio</span>
                    <ChevronDoubleLeftIcon class="h-5 w-5 text-gray-300" aria-hidden="true" />
                </div>
                <a href="#" v-if="!onFirstPage" @click.prevent="change(pagination.current_page-1)" class="relative inline-flex items-center border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-20">
                    <span class="sr-only">Indietro</span>
                    <ChevronLeftIcon class="h-5 w-5 text-gray-500" aria-hidden="true" />
                </a>
                <div v-else class="relative inline-flex items-center border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500 focus:z-20">
                    <span class="sr-only">Indietro</span>
                    <ChevronLeftIcon class="h-5 w-5 text-gray-300" aria-hidden="true" />
                </div>
                <!-- <a href="#" aria-current="page" class="relative z-10 inline-flex items-center border border-indigo-500 bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-600 focus:z-20">1</a>
                <a href="#" class="relative inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-20">2</a>
                <a href="#" class="relative hidden items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-20 md:inline-flex">3</a>
                <span class="relative inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700">...</span>
                <a href="#" class="relative hidden items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-20 md:inline-flex">8</a>
                <a href="#" class="relative inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-20">9</a>
                <a href="#" class="relative inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-20">10</a> -->
                <a href="#" :key="page" v-for="page in pages" @click.prevent="change(page)" :class="[page===pagination.current_page ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50', 'relative inline-flex items-center border px-4 py-2 text-sm font-medium focus:z-20']">{{ page }}</a>

                <a href="#" v-if="!onLastPage" @click.prevent="change(pagination.current_page+1)" class="relative inline-flex items-center border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-20">
                    <span class="sr-only">Avanti</span>
                    <ChevronRightIcon class="h-5 w-5 text-gray-500" aria-hidden="true" />
                </a>
                <div v-else class="relative inline-flex items-center border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500 focus:z-20">
                    <span class="sr-only">Avanti</span>
                    <ChevronRightIcon class="h-5 w-5 text-gray-300" aria-hidden="true" />
                </div>
                <a href="#" v-if="!onLastPage" @click.prevent="change(pagination.last_page)" class="relative inline-flex items-center rounded-r-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-20">
                    <span class="sr-only">Avanti</span>
                    <ChevronDoubleRightIcon class="h-5 w-5 text-gray-500" aria-hidden="true" />
                </a>
                <div v-else class="relative inline-flex items-center rounded-r-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500 focus:z-20">
                    <span class="sr-only">Avanti</span>
                    <ChevronDoubleRightIcon class="h-5 w-5 text-gray-300" aria-hidden="true" />
                </div>
            </nav>
        </div>
    </div>
</div>
</template>