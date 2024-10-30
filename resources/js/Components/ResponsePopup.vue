<script setup>

import {XCircleIcon} from "@heroicons/vue/24/solid/index.js";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";

// const props = defineProps({
//     modelValue: {
//         type: Boolean,
//         default: false
//     },
//     message: {
//         default: null
//     }
// })

const props = defineProps(['modelValue', 'message'])

const emit = defineEmits(['update:modelValue'])

const close = () => {
    emit('update:modelValue', false)
}

</script>

<template>
    <Modal :show="modelValue" maxWidth="lg">
        <div class="flex flex-col bg-white w-full gap-3 p-6 rounded-xl">
            <div class="flex justify-center w-full">
                <svg v-if="message.status === 'success'" xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                    <g clip-path="url(#clip0_4516_30676)">
                        <path
                            d="M12.5 2C6.98 2 2.5 6.48 2.5 12C2.5 17.52 6.98 22 12.5 22C18.02 22 22.5 17.52 22.5 12C22.5 6.48 18.02 2 12.5 2ZM10.5 17L5.5 12L6.91 10.59L10.5 14.17L18.09 6.58L19.5 8L10.5 17Z"
                            fill="#3BA54E" />
                    </g>
                    <defs>
                        <clipPath id="clip0_4516_30676">
                            <rect width="24" height="24" fill="white" transform="translate(0.5)" />
                        </clipPath>
                    </defs>
                </svg>
                <XCircleIcon v-else class="w-7 text-primary-red rounded-full"/>
            </div>
            <div class="text-base font-medium text-center">
                {{message.status}}
            </div>
            <div class="text-sm text-center tracking-wider">
                {{message.message}}
            </div>
            <div class="flex justify-center gap-2 mt-4">
                <SecondaryButton
                    class="text-black font-medium text-m border-none px-4 py-2 rounded-md"
                    @click="close">
                    OK
                </SecondaryButton>
            </div>
        </div>
    </Modal>
</template>
