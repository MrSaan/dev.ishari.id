<script lang="ts" setup>

withDefaults(
    defineProps<{
        modelValue?: boolean,
    }>(), {
        modelValue: false
    },
);

const emit = defineEmits(['update:modelValue'])
const close = () => {
    console.log('close')
    emit('update:modelValue', false)
}

</script>

<template>
    <section
        v-if="modelValue"
        class="fixed flex items-center justify-between max-w-4xl p-4 mx-auto bg-white border border-gray-200 shadow-md dark:bg-gray-900 left-12 bottom-16 dark:shadow-gray-900 shadow-gray-100 md:gap-x-4 dark:border-gray-700 rounded-2xl">
        <div class="flex justify-center">
            <svg class="animate-spin w-5" fill="none" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75"
                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                      fill="currentColor">
                </path>
            </svg>
        </div>
        <p class="text-base text-gray-600 dark:text-gray-300">Loading... Tunggu Sebentar. </p>

        <button
            class="flex items-center justify-center text-gray-700 transition-colors duration-300 rounded-full shrink-0 dark:text-gray-200 dark:hover:bg-gray-700 w-7 h-7 focus:outline-none hover:bg-gray-100"
            @click="close">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
            </svg>
        </button>
    </section>
</template>
