<script lang="ts" setup>

import {TrashIcon, PencilSquareIcon} from "@heroicons/vue/20/solid/index.js";
import {Head, useForm} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {onMounted, ref} from "vue";
import LoadingBanner from "@/Components/LoadingBanner.vue";
import ResponsePopup from "@/Components/ResponsePopup.vue";
import axios from "axios";
import Create from "@/Pages/Muhud/Create.vue";

// variable
const loading = ref(false)
const messageResponse = ref<ResponseServer | null>(null)
const showResponse = ref(false)
const dataList = ref<MuhudList[]>([])
const dataEdit = ref<FormMuhud>({
    id: null,
    name: '',
    position: 0,
    nameArabic: '',
    translateId: '',
    transliteration: '',
})

// logic
onMounted(() => {
    getData()
})

const getData = async () => {
    try {
        loading.value = true
        const response = await axios.get('/muhud/get')
        dataList.value = response.data.data
    } catch (e) {
        messageResponse.value = {
            status: 'gagal',
            message: `Gagal menambahkan muhud. Hubungi Amar! onk error.. aplikasine.`
        }
        showResponse.value = true
    } finally {
        loading.value = false
    }
}

const edit = (item: MuhudList) => {
    window.scrollTo({top: 0, behavior: 'smooth'}); // top

    dataEdit.value.id = item.id
    dataEdit.value.name = item.name
    dataEdit.value.position = item.position
    dataEdit.value.nameArabic = item.arabic
    dataEdit.value.translateId = item.translateId
    dataEdit.value.transliteration = item.transliteration
}

const destroy = async (id: number) => {
    try {
        loading.value = true
        const response = await axios.delete(`/muhud/${id}`)
        messageResponse.value = {
            status: response.data.status,
            message: response.data.message
        }
        showResponse.value = true
        await getData()
    } catch (e) {
        messageResponse.value = e as ResponseServer
        showResponse.value = true
    } finally {
        loading.value = false
    }
}

</script>

<template>
    <Head title="Muhud"/>

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
                    {{dataEdit.id ? 'Edit Muhud :' : 'Tambah Muhud :'}}
                </div>

                <Create
                    :dataEdit="dataEdit"
                    @load="getData"/>

                <div class="overflow-auto p-4 mt-10 bg-white shadow-sm sm:rounded-lg">
                    <table class="min-w-full table-auto overflow-auto">
                        <thead>
                        <tr class="border-b-2 align-top">
                            <th class="py-2 border-b font-medium text-left px-2 sm:px-0" >Urutan</th>
                            <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Nama Muhud</th>
                            <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Nama Arab</th>
                            <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Terjemahan Id</th>
                            <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Transliterasi</th>
                            <th class="py-2 border-b font-medium text-center px-2 sm:px-0">Action</th>
                        </tr>
                        </thead>
                        <tbody v-if="dataList && dataList.length > 0">
                        <tr v-for="item in dataList" :key="item.id" class="border-b-2 hover:bg-slate-200">
                            <td class="py-2 px-2 text-left">{{item.position}}</td>
                            <td class="py-2 text-left">{{item.name}}</td>
                            <td class="py-2">{{item.arabic}}</td>
                            <td class="py-2">{{item.translateId}}</td>
                            <td class="py-2">{{item.transliteration}}</td>
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
