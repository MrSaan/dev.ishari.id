<script lang="ts" setup>

import {useForm} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {ref, watch} from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps<{
    dataEdit: Person
}>()
const emit = defineEmits(['load', 'update:dataEdit'])

// variable
const loading = ref(false)
const messageResponse = ref<ResponseServer | null>(null)
const showResponse = ref(false)
const previewUrl = ref<string | null>(null)
const formEdit = ref(props.dataEdit)
const form = useForm<Person>({
    id: null,
    name: '',
    description: '',
    avatar: null
})

watch(() => formEdit.value, (newVal) => {
    form.id = newVal.id
    form.name = newVal.name
    form.description = newVal.description
    form.avatar = newVal.avatar
}, {deep: true})

// logic
const handleFileUpload = (event: Event) => {
    const target = event.target as HTMLInputElement
    const file = target.files?.[0] || null

    // reset
    previewUrl.value = null

    if (file) {
        if (file.type.startsWith('image/')) {
            form.avatar = file
            previewUrl.value = URL.createObjectURL(file)
        } else {
            form.errors.avatar = 'Hanya file foto yang diizinkan (JPEG, PNG, dll.).'
        }
    }
}

const deleteDataEdit = () => {
    formEdit.value.id = null
    formEdit.value.name = ''
    formEdit.value.description = ''
    formEdit.value.avatar = null
}

const submit = () => {
    form.post(route('figure.store'), {
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
    form.patch(route('figure.update', {person: form.id}), {
        onStart: () => {
            loading.value = true
        },
        onSuccess: () => {
            messageResponse.value = {
                status: 'success',
                message: 'Edit pimpinan berhasil.'
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
                message: `Gagal memperbarui pimpinan. Hubungi Amar! onk error.. aplikasine. ${params.message}`
            }
            showResponse.value = true
            loading.value = false
            deleteDataEdit()
        }
    })
}

</script>

<template>
    <div
        class="overflow-hidden mt-4 bg-white shadow-sm sm:rounded-lg"
    >
        <div class="p-6 text-gray-900 flex flex-col">
            <!-- form -->
            <form class="flex flex-col gap-5" @submit.prevent="formEdit.id ? update() : submit()">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <InputLabel for="name">
                            Nama <span class="text-rose-600">*</span>
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
                            <InputLabel for="ava" value="Foto"/>

                            <input
                                type="file"
                                name="ava"
                                id="ava"
                                class="mt-1.5"
                                @input="handleFileUpload" />

                            <InputError :message="form.errors.avatar" class="mt-2"/>
                        </div>
                        <div v-if="previewUrl" class="mt-4">
                            <img :src="previewUrl" alt="Pratinjau Gambar" class="w-32 h-32 object-cover rounded-md" />
                        </div>
                    </div>
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
    </div>
</template>
