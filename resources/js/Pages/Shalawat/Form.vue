<script setup lang="ts">

import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {useForm} from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {ref, watch} from "vue";
import ResponsePopup from "@/Components/ResponsePopup.vue";
import LoadingBanner from "@/Components/LoadingBanner.vue";

const props = defineProps<{
    muhudList: MuhudList[],
    shalawatEdit: FormShalawat,
}>();

const emit = defineEmits(['load', 'update:shalawatEdit'])

// variable
const loading = ref(false)
const messageResponse = ref<ResponseServer | null>(null)
const showResponse = ref(false)
const formEdit = ref(props.shalawatEdit)
const form = useForm<FormShalawat>({
    id: null,
    muhudId: 0,
    muhudName: '',
    position: 0,
    text: '',
    isDiwan: true,
    isDiba: false,
    translateId: '',
    transliteration: '',
    createdBy: '',
})

watch(() => formEdit.value, (newVal) => {
    form.id = newVal.id
    form.muhudId = newVal.muhudId
    form.muhudName = newVal.muhudName
    form.position = newVal.position
    form.text = newVal.text
    form.isDiwan = newVal.isDiwan
    form.isDiba = newVal.isDiba
    form.translateId = newVal.translateId
    form.transliteration = newVal.transliteration
}, {deep: true})

// logic
const submit = () => {

    if (form.muhudId === 0) return alert('Urutan tidak boleh null')

    form.post(route('shalawat.store'), {
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

            emit('load', true)
        },
        onError: params => {
            messageResponse.value = {
                status: 'gagal',
                message: `Gagal menambahkan muhud. Hubungi Amar! onk error.. aplikasine. ${params.message}`
            }
            showResponse.value = true
            loading.value = false
        }
    })
}

const update = () => {
    form.patch(route('shalawat.update', {shalawat: form.id}), {
        onStart: () => {
            loading.value = true
        },
        onSuccess: () => {
            messageResponse.value = {
                status: 'success',
                message: 'Edit shalawat berhasil.'
            }
            showResponse.value = true

            loading.value = false
            form.reset()

            emit('load', true)
            deleteDataEdit()
        },
        onError: params => {
            messageResponse.value = {
                status: 'gagal',
                message: `Gagal memperbarui muhud. Hubungi Amar! onk error.. aplikasine. ${params.message}`
            }
            showResponse.value = true
            loading.value = false

            deleteDataEdit()
        }
    })
}

const deleteDataEdit = () => {
    formEdit.value.id = null
    formEdit.value.muhudId = 0
    formEdit.value.muhudName = ''
    formEdit.value.position = 0
    formEdit.value.text = ''
    formEdit.value.isDiwan = false
    formEdit.value.isDiba = false
    formEdit.value.translateId = ''
    formEdit.value.transliteration = ''
}
</script>

<template>
    <div
        class="mt-4 bg-white shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 flex flex-col">
            <form class="flex flex-col gap-5" @submit.prevent="formEdit.id ? update() : submit()">
                <div class="grid grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <InputLabel for="text" value="Text Shalawat"/>

                        <TextInput
                            id="text"
                            v-model="form.text"
                            autocomplete="Text Shalawat"
                            autofocus
                            class="mt-1 block w-full"
                            type="text"
                        />

                        <InputError :message="form.errors.text" class="mt-0.5"/>
                    </div>
                    <div class="w-full">
                        <InputLabel class="pb-1" value="Muhud"/>

                        <Dropdown align="left" width="48">
                            <template #trigger>
                                <div class="inline-flex items-center w-full border border-gray-300 rounded-md py-2.5 px-4">
                                    <div class="w-full">
                                        {{form.muhudName === '' ? 'Pilih Muhud' : form.muhudName}}
                                    </div>
                                    <svg
                                        class="-me-0.5 ms-2 h-4 w-4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                            </template>
                            <template #content>
                                <div class="w-full flex flex-col gap-2">
                                    <div
                                        v-for="muhud in muhudList"
                                        class="py-2 px-4 hover:bg-gray-100 rounded-md"
                                        @click="[form.muhudId = muhud.id, form.muhudName = muhud.name]">
                                        {{muhud.name}}
                                    </div>
                                </div>
                            </template>
                        </Dropdown>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Dari Kitab" class="pb-0.5"/>

                            <Dropdown align="left" width="48">
                                <template #trigger>
                                    <div class="inline-flex items-center w-full border border-gray-300 rounded-md py-2.5 px-4">
                                        <div class="w-full">
                                            {{form.isDiwan ? 'Diwan' : 'Diba'}}
                                        </div>
                                        <svg
                                            class="-me-0.5 ms-2 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                </template>
                                <template #content>
                                    <div class="w-full flex flex-col gap-2">
                                        <div @click="[form.isDiwan = true, form.isDiba = false]" class="py-2 px-4 hover:bg-gray-100 rounded-md">
                                            Diwan
                                        </div>
                                        <div @click="[form.isDiba = true, form.isDiwan = false]" class="py-2 px-4 hover:bg-gray-100 rounded-md">
                                            Diba
                                        </div>
                                    </div>
                                </template>
                            </Dropdown>
                        </div>
                        <div>
                            <InputLabel for="position" value="Urutan"/>

                            <TextInput
                                id="position"
                                v-model="form.position"
                                autocomplete="Position"
                                autofocus
                                class="mt-1 block w-full"
                                type="number"
                            />

                            <InputError :message="form.errors.position" class="mt-2"/>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <InputLabel for="translate" value="Terjemahan Indonesia"/>

                        <TextInput
                            id="translate"
                            v-model="form.translateId"
                            autocomplete="Translate"
                            autofocus
                            class="mt-1 block w-full"
                            type="text"
                        />

                        <InputError :message="form.errors.translateId" class="mt-2"/>
                    </div>
                    <div class="col-span-2">
                        <InputLabel for="transliteration" value="Transliterasi"/>

                        <TextInput
                            id="transliteration"
                            v-model="form.transliteration"
                            autocomplete="Transliteration"
                            autofocus
                            class="mt-1 block w-full"
                            type="text"
                        />

                        <InputError :message="form.errors.translateId" class="mt-2"/>
                    </div>
                </div>
                <div class="inline-flex gap-3">
                    <PrimaryButton
                        type="submit"
                        :class="{ 'opacity-25': form.processing }"
                        class="mt-4 px-10"
                    >
                        {{form.id ? 'Edit Data' : 'Tambah Data'}}
                    </PrimaryButton>
                    <SecondaryButton
                        v-if="form.id"
                        type="button"
                        :class="{ 'opacity-25': form.processing }"
                        class="mt-4 px-10"
                        @click="deleteDataEdit"
                    >
                        Hapus data edit
                    </SecondaryButton>
                </div>
            </form>
        </div>

        <!-- modal -->
        <LoadingBanner v-model="loading"/>
        <ResponsePopup
            v-model="showResponse"
            :message="messageResponse"/>
    </div>
</template>
