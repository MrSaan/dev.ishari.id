<script lang="ts" setup>

import {onMounted, ref} from "vue";
import {Head} from '@inertiajs/vue3'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Form from "@/Pages/Shalawat/Form.vue";
import axios from "axios";
import ResponsePopup from "@/Components/ResponsePopup.vue";
import LoadingBanner from "@/Components/LoadingBanner.vue";
import {PencilSquareIcon, TrashIcon} from "@heroicons/vue/20/solid";

// variable
const loading = ref(false)
const messageResponse = ref<ResponseServer | null>(null)
const showResponse = ref(false)
const shalawatList = ref<ShalawatList[]>([])
const muhudList = ref<MuhudList[]>([])
const shalawatEdit = ref<FormShalawat>({
    id: null,
    muhudId: 0,
    muhudName: '',
    position: 0,
    text: '',
    isDiwan: false,
    isDiba: false,
    translateId: '',
    transliteration: '',
    createdBy: '',
})


// logic
onMounted(() => {
    getMuhud()
    getData()
})

const getData = async () => {
    try {
        const response = await axios.get('/shalawat/get')
        shalawatList.value = response.data.data
    } catch (e) {
        messageResponse.value = e as ResponseServer
        showResponse.value = true
    }
}

const getMuhud = async () => {
    try {
        const response = await axios.get('/muhud/get')
        muhudList.value = response.data.data
    } catch (e) {
        messageResponse.value = e as ResponseServer
        showResponse.value = true
    }
}

const destroy = async (id: number | null) => {
    try {
        loading.value = true
        const response = await axios.delete(`/shalawat/${id}`)
        messageResponse.value = response.data
        showResponse.value = true
        await getData()
    } catch (e) {
        messageResponse.value = e as ResponseServer
        showResponse.value = true
    } finally {
        loading.value = false
    }
}

const edit = (item: ShalawatList) => {
    window.scrollTo({top: 0, behavior: 'smooth'}); // top

    shalawatEdit.value.id = item.id
    shalawatEdit.value.muhudId = item.muhud.id
    shalawatEdit.value.muhudName = item.muhud.name
    shalawatEdit.value.position = item.position
    shalawatEdit.value.text = item.text
    shalawatEdit.value.isDiwan = item.isDiwan
    shalawatEdit.value.isDiba = item.isDiba
    shalawatEdit.value.translateId = item.translateId
    shalawatEdit.value.transliteration = item.transliteration
}
</script>

<template>
    <Head title="Shalawat"/>

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Ishari App - Dev
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                <div class="font-medium text-xl px-4 sm:px-1">
                    {{ shalawatEdit.id ? 'Edit Shalawat' : 'Tambah Shalawat' }}
                </div>

                <Form
                    :shalawatEdit="shalawatEdit"
                    :muhudList="muhudList"
                    @load="getData"/>

                <div class="overflow-auto p-4 mt-10 bg-white shadow-sm sm:rounded-lg">
                    <table class="min-w-full table-auto">
                        <thead>
                        <tr class="border-b-2">
                            <th class="py-2 border-b font-medium text-left px-2 sm:px-0" >Urutan</th>
                            <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Muhud</th>
                            <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Teks</th>
                            <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Kitab</th>
                            <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Terjemahan</th>
                            <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Transliterasi</th>
                            <th class="py-2 border-b font-medium text-center px-2 sm:px-0">Action</th>
                        </tr>
                        </thead>
                        <tbody v-if="shalawatList && shalawatList.length > 0">
                        <tr v-for="item in shalawatList" class="border-b-2 hover:bg-slate-200">
                            <td class="py-2 px-2 text-left">{{item.position}}</td>
                            <td class="py-2 px-2 text-left">{{item.muhud.name}}</td>
                            <td class="py-2 px-2 text-left">{{item.text}}</td>
                            <td class="py-2 px-2 text-left">{{item.isDiwan ? 'Diwan' : 'Diba' }}</td>
                            <td class="py-2 px-2 text-left">{{item.translateId}}</td>
                            <td class="py-2 px-2 text-left">{{item.transliteration}}</td>
                            <td class="py-2 text-center">
                                <div class="inline-flex gap-2">
                                    <div @click="destroy(item.id)" class="p-2 border w-fit rounded-md cursor-pointer bg-red-500 hover:bg-red-700">
                                        <TrashIcon class="w-6 text-white" />
                                    </div>
                                    <div @click="edit(item)" class="p-2 border w-fit rounded-md cursor-pointer bg-slate-500 hover:bg-slate-700">
                                        <PencilSquareIcon class="w-6 text-white" />
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- modal -->
        <LoadingBanner v-model="loading"/>
        <ResponsePopup
            v-model="showResponse"
            :message="messageResponse"/>
    </AuthenticatedLayout>
</template>
