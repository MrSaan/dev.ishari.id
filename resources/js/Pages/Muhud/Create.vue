<script setup lang="ts">

import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import {useForm} from "@inertiajs/vue3";
import {ref, watch} from "vue";
import LoadingBanner from "@/Components/LoadingBanner.vue";
import ResponsePopup from "@/Components/ResponsePopup.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps<{
    dataEdit: FormMuhud,
}>()
const emit = defineEmits(['load', 'update:dataEdit'])

// varibale
const loading = ref(false)
const messageResponse = ref<ResponseServer | null>(null)
const showResponse = ref(false)
const formEdit = ref(props.dataEdit)
const form = useForm<FormMuhud>({
    id: null,
    position: 0,
    name: '',
    nameArabic: '',
    translateId: '',
    transliteration: '',
})

watch(() => formEdit.value, (newVal) => {
    form.id = newVal.id
    form.name = newVal.name
    form.nameArabic = newVal.nameArabic
    form.position = newVal.position
    form.translateId = newVal.translateId
    form.transliteration = newVal.transliteration
}, {deep: true})

// logic
const submit = () => {
    form.post(route('muhud.store'), {
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
    },)
}

const update = () => {
    form.patch(route('muhud.update', {muhud: form.id}), {
        onStart: () => {
            loading.value = true
        },
        onSuccess: () => {
            messageResponse.value = {
                status: 'success',
                message: 'Edit muhud berhasil.'
            }
            showResponse.value = true

            loading.value = false
            form.reset()

            deleteDataEdit()
            emit('load', true)
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
    formEdit.value.name = ''
    formEdit.value.position = 0
    formEdit.value.nameArabic = ''
    formEdit.value.translateId = ''
    formEdit.value.transliteration = ''

    emit('update:dataEdit', formEdit.value)
}
</script>

<template>
    <div
        class="overflow-hidden mt-4 bg-white shadow-sm sm:rounded-lg"
    >
        <div class="p-6 text-gray-900 flex flex-col">
            <!-- form -->
            <form class="flex flex-col gap-5" @submit.prevent="formEdit.id ? update() : submit()" >
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <InputLabel for="name">
                            Nama Muhud <span class="text-rose-600">*</span>
                        </InputLabel>

                        <TextInput
                            id="name"
                            v-model="form.name"
                            autocomplete="Name"
                            autofocus
                            class="mt-1 block w-full"
                            type="text"
                        />

                        <InputError :message="form.errors.name" class="mt-2"/>
                    </div>
                    <div>
                        <InputLabel for="nameArab" value="Name Arab (teks arab)"/>

                        <TextInput
                            id="nameArab"
                            v-model="form.nameArabic"
                            autocomplete="Name Arab"
                            autofocus
                            class="mt-1 block w-full"
                            type="text"
                        />

                        <InputError :message="form.errors.nameArabic" class="mt-2"/>
                    </div>
                </div>
                <div class="">
                    <InputLabel for="position">
                        Urutan Muhud <span class="text-rose-600">*</span>
                    </InputLabel>

                    <TextInput
                        id="position"
                        v-model="form.position"
                        autofocus
                        class="mt-1 block w-full"
                        type="number"
                    />

                    <InputError :message="form.errors.position" class="mt-2"/>
                </div>
                <div>
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
                <div>
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

                <div class="inline-flex gap-3">
                    <PrimaryButton
                        type="submit"
                        :class="{ 'opacity-25': form.processing }"
                        class="mt-4 px-10"
                    >
                        {{formEdit.id ? 'Edit Data' : 'Tambah Data'}}
                    </PrimaryButton>
                    <SecondaryButton
                        v-if="formEdit.id"
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
