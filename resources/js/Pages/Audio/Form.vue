<script lang="ts" setup>

import {ref, watch} from "vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {XMarkIcon} from "@heroicons/vue/24/solid";
import {useForm} from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import Dropdown from "@/Components/Dropdown.vue";
import InputError from "@/Components/InputError.vue";
import LoadingBanner from "@/Components/LoadingBanner.vue";
import ResponsePopup from "@/Components/ResponsePopup.vue";

const props = defineProps<{
    show?: boolean,
    persons?: Person[],
    shalawat?: ShalawatList|null,
}>()

const emit = defineEmits(['close', 'load'])

// variable
const loading = ref(false)
const messageResponse = ref<ResponseServer | null>(null)
const showResponse = ref(false)
const isOpen = ref(props.show)
const personList = ref(props.persons)
const form = useForm<FormAudio>({
    personName: "",
    personId: 0,
    audio: null,
    description: "",
    textId: 0,
})

watch(() => props.show, (newVal) => {
    isOpen.value = newVal
})

watch(() => props.persons, (newVal) => {
    personList.value = newVal
})

watch(() => props.shalawat, (newVal) => {
    if (newVal) {
        form.textId = newVal.id
    }
}, {deep: true})

// logic
const setIsOpen = (value: boolean) => {
    emit('close', value)
    isOpen.value = value
}

const handleFileUpload = (event: Event) => {
    const target = event.target as HTMLInputElement
    let file = target.files?.[0] || null

    // Mengecek ekstensi file
    const validExtensions = ["audio/mpeg", "audio/wav", "audio/ogg", "audio/mp3"];
    if (file) {
        if (!validExtensions.includes(file.type)) {
            file = null
            return alert("Format file tidak valid. Harap unggah file audio (MP3, WAV, atau OGG).");
        } else {
            form.audio = file
        }
    } else {
        form.errors.audio = 'File error. Coba ulangi input file.'
    }
}

const submit = () => {
    form.post(route('audio.store'), {
        onStart: () => {
            loading.value = true
        },
        onSuccess: () => {
            messageResponse.value = {
                status: 'success',
                message: 'Berhasil menambahkan muhud.'
            }
            showResponse.value = true

            loading.value = false
            form.reset()

            setIsOpen(false)

            emit('load', true)
        },
        onError: params => {
            messageResponse.value = {
                status: 'gagal',
                message: `Gagal menambahkan audio. Hubungi Amar! onk error.. aplikasine. ${params.message}`
            }
            showResponse.value = true
            loading.value = false
        }
    })
}

</script>

<template>
    <div>
        <Modal :show="isOpen" maxWidth="2xl" @close="setIsOpen(false)">
            <div class="bg-white p-6 flex flex-col gap-4">
                <div class="w-full border-b pb-4 inline-flex justify-between items-center">
                    <div>Upload File Audio</div>
                    <XMarkIcon class="w-5 h-5 cursor-pointer" @click="setIsOpen(false)"/>
                </div>
                <form @submit.prevent="submit">
                    <div class="flex flex-col gap-4">
                        <div class="w-full">
                            <InputLabel class="pb-1" value="Pimpinan Shalawat"/>

                            <Dropdown align="left">
                                <template #trigger>
                                    <div
                                        class="inline-flex items-center w-full border border-gray-300 rounded-md py-2.5 px-4">
                                        <div class="w-full">
                                            {{ form.personName === '' ? 'Pilih Pimpinan' : form.personName }}
                                        </div>
                                        <svg
                                            class="-me-0.5 ms-2 h-4 w-4"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                clip-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                fill-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                </template>
                                <template #content>
                                    <div class="w-full flex flex-col gap-2">
                                        <div
                                            v-for="item in personList"
                                            class="py-2 px-4 hover:bg-gray-100 rounded-md"
                                            @click="[form.personId = item.id ?? 0, form.personName = item.name]">
                                            {{ item.name }}
                                        </div>
                                    </div>
                                </template>
                            </Dropdown>
                        </div>
                        <div class="col-span-2">
                            <InputLabel for="desc" value="Deskripsi (tambahan)"/>

                            <textarea
                                id="desc"
                                v-model="form.description"
                                autofocus
                                class="mt-1 block w-full rounded-md border-gray-300"
                                rows="3"
                                type="text"
                            />

                            <InputError :message="form.errors.description" class="mt-2"/>
                        </div>
                        <div class="flex flex-col">
                            <div>
                                <InputLabel for="ava" value="Audio"/>

                                <input
                                    id="ava"
                                    class="mt-1.5"
                                    name="ava"
                                    type="file"
                                    @input="handleFileUpload"/>

                                <InputError :message="form.errors.audio" class="mt-2"/>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end mt-6">
                        <div class="flex justify-between gap-4">
                            <PrimaryButton>
                                Upload
                            </PrimaryButton>
                            <SecondaryButton @click="setIsOpen(false)">
                                Close
                            </SecondaryButton>
                        </div>
                    </div>
                </form>
            </div>
            <!-- modal -->
            <LoadingBanner v-model="loading"/>
            <ResponsePopup
                v-model="showResponse"
                :message="messageResponse"/>
        </Modal>
    </div>
</template>
