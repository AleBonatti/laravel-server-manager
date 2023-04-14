<template>
    <div class="flex flex-wrap w-28 my-1 content-center overflow-hidden rounded-lg">
        <!-- whitespace-no-wrap text-sm leading-5 text-gray-700 -->
        <img :src="src" :class="{ 'bg-gray-200 px-2 my-2' : isSvg }" class="object-cover rounded-lg">
    </div>
</template>
<script>
    export default {
        props: {
            obj: { type: Object, default: null }
        },
        computed: {
            isSvg: function () {
                return this.obj.hasOwnProperty('mime_type') && this.obj.mime_type.includes('svg');
            },
            src: function () {
                if(this.obj.hasOwnProperty('mime_type') && this.obj.hasOwnProperty('url')) {
                    return this.isSvg ? this.obj.url : this.obj.thumb;
                }
                return '/admin/ico/picture.svg';
            }
        }
    }
</script>