<script setup>

defineProps({
    maxheight: {
        require: false,
        default: '80vh'
    },
    overflow: {
        required: false,
        default: true
    },
});
</script>


<template>
    <div class="p-1">
        <div class="justify-start w-full gap-3 md:flex">
            <slot name="section-header"></slot>
        </div>

        <div :class="{ 'overflow-hidden': overflow }" class="mt-3 bg-transparent sm:rounded-lg">
            <div :class="[overflow ? 'overflow-x-auto overflow-y-auto' : '']" class="-mx-2 -my-2 sm:-my-2 lg:-my-3"
                :style="'max-height:' + maxheight">
                <div class="inline-block min-w-full py-2 align-middle lg:px-1">
                    <div class="sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 table-fixed custom-table">
                            <thead
                                class="font-bold text-center text-gray-500 bg-transparent border-b-2 border-blue-200">
                                <slot name="table-header"></slot>
                            </thead>

                            <tbody class="overflow-x-auto text-center text-gray-500 border">
                                <slot name="table-body"></slot>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
