<script lang="ts" setup>

import {onMounted, ref} from "vue";
import {Head} from '@inertiajs/vue3'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Form from "@/Pages/Shalawat/Form.vue";
import axios from "axios";
import ResponsePopup from "@/Components/ResponsePopup.vue";
import LoadingBanner from "@/Components/LoadingBanner.vue";
import Dropdown from "@/Components/Dropdown.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {PencilSquareIcon, TrashIcon} from "@heroicons/vue/20/solid";
import {Tab, TabGroup, TabList, TabPanel, TabPanels} from "@headlessui/vue";

// variable
const loading = ref(false)
const messageResponse = ref<ResponseServer | null>(null)
const showResponse = ref(false)
const muhudList = ref<MuhudList[]>([])
const textShalawat = ref<ShalawatList[]>([])
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
const selectedMuhud = ref<MuhudList>({
    arabic: "", id: 0, name: "Pilih Muhud", position: 0, translateId: "", transliteration: ""
})

// logic
onMounted(() => {
    getMuhud()
})

const getShalawat = async () => {
    try {
        loading.value = true
        const name = selectedMuhud.value.name
        const response = await axios.get(`/shalawat/${name}/get`)
        textShalawat.value = response.data.data
    } catch (e) {
        messageResponse.value = e as ResponseServer
        showResponse.value = true
    } finally {
        loading.value = false
    }
}

const getMuhud = async () => {
    try {
        loading.value = true
        const response = await axios.get('/muhud/get')
        muhudList.value = response.data.data
        selectedMuhud.value = muhudList.value[0]
        await getShalawat()
    } catch (e) {
        messageResponse.value = e as ResponseServer
        showResponse.value = true
    } finally {
        loading.value = false
    }
}

const destroy = async (id: number | null) => {
    try {
        loading.value = true
        const response = await axios.delete(`/shalawat/${id}`)
        messageResponse.value = response.data
        showResponse.value = true
        await getShalawat()
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

const selectMuhud = (item: MuhudList) => {
    selectedMuhud.value = item
    getShalawat()
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
                    :muhudList="muhudList"
                    :shalawatEdit="shalawatEdit"
                    @load="getShalawat"/>

                <!-- muhud list -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="mt-10 px-4 md:px-8 md:w-96 space-y-1">
                        <InputLabel value="Muhud"/>
                        <Dropdown>
                            <template #trigger>
                                <div
                                    class="bg-white inline-flex items-center w-full border border-gray-300 rounded-md py-2.5 px-4">
                                    <div class="w-full">
                                        {{ selectedMuhud.name }}
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
                                        v-for="item in muhudList"
                                        class="py-2 px-4 hover:bg-gray-100 rounded-md"
                                        @click="selectMuhud(item)">
                                        {{ item.name }}
                                    </div>
                                </div>
                            </template>
                        </Dropdown>
                    </div>
                </div>

                <!-- tab option -->
                <TabGroup class="w-full mt-10" as="div">
                    <TabList class="flex space-x-1 bg-slate-300 p-1">
                        <Tab v-slot="{ selected }" class="w-full">
                            <button
                                :class="[
                                  'w-full py-2.5 tracking-wider font-medium leading-5',
                                  selected
                                    ? 'bg-slate-800 text-white shadow rounded-md'
                                    : 'text-slate-800 font-semibold hover:bg-white/[0.12] hover:text-white',
                                ]"
                            >
                                Diwan
                            </button>
                        </Tab>
                        <Tab v-slot="{ selected }" class="w-full">
                            <button
                                :class="[
                                  'w-full py-2.5 tracking-wider font-medium leading-5',
                                  selected
                                    ? 'bg-slate-800 text-white shadow rounded-md'
                                    : 'text-slate-800 font-semibold hover:bg-white/[0.12] hover:text-white',
                                ]"
                            >
                                Syaraful Anam
                            </button>
                        </Tab>
                    </TabList>
                    <TabPanels>
                        <TabPanel>
                            <div class="overflow-auto mt-3 p-4 bg-white shadow-sm sm:rounded-lg">
                                <table class="min-w-full table-auto">
                                    <thead>
                                    <tr class="border-b-2">
                                        <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Urutan</th>
                                        <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Muhud</th>
                                        <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Teks</th>
                                        <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Kitab</th>
                                        <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Terjemahan</th>
                                        <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Transliterasi</th>
                                        <th class="py-2 border-b font-medium text-center px-2 sm:px-0">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="textShalawat && textShalawat.length > 0">
                                    <tr v-for="item in textShalawat.filter(i => i.isDiwan)" class="border-b-2 hover:bg-slate-200">
                                        <td class="py-2 px-2 text-left">{{ item.position }}</td>
                                        <td class="py-2 px-2 text-left">{{ item.muhud.name }}</td>
                                        <td class="py-2 px-2 text-left">{{ item.text }}</td>
                                        <td class="py-2 px-2 text-left">{{ item.isDiwan ? 'Diwan' : 'Diba' }}</td>
                                        <td class="py-2 px-2 text-left">{{ item.translateId }}</td>
                                        <td class="py-2 px-2 text-left">{{ item.transliteration }}</td>
                                        <td class="py-2 text-center">
                                            <div class="inline-flex gap-2">
                                                <div class="p-2 border w-fit rounded-md cursor-pointer bg-red-500 hover:bg-red-700"
                                                     @click="destroy(item.id)">
                                                    <TrashIcon class="w-6 text-white"/>
                                                </div>
                                                <div
                                                    class="p-2 border w-fit rounded-md cursor-pointer bg-slate-500 hover:bg-slate-700"
                                                    @click="edit(item)">
                                                    <PencilSquareIcon class="w-6 text-white"/>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </TabPanel>
                        <TabPanel>
                            <div class="overflow-auto mt-3 p-4 bg-white shadow-sm sm:rounded-lg">
                                <table class="min-w-full table-auto">
                                    <thead>
                                    <tr class="border-b-2">
                                        <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Urutan</th>
                                        <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Muhud</th>
                                        <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Teks</th>
                                        <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Kitab</th>
                                        <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Terjemahan</th>
                                        <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Transliterasi</th>
                                        <th class="py-2 border-b font-medium text-center px-2 sm:px-0">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="textShalawat && textShalawat.length > 0">
                                    <tr v-for="item in textShalawat.filter(i => !i.isDiwan)" class="border-b-2 hover:bg-slate-200">
                                        <td class="py-2 px-2 text-left">{{ item.position }}</td>
                                        <td class="py-2 px-2 text-left">{{ item.muhud.name }}</td>
                                        <td class="py-2 px-2 text-left">{{ item.text }}</td>
                                        <td class="py-2 px-2 text-left">{{ item.isDiwan ? 'Diwan' : 'Diba' }}</td>
                                        <td class="py-2 px-2 text-left">{{ item.translateId }}</td>
                                        <td class="py-2 px-2 text-left">{{ item.transliteration }}</td>
                                        <td class="py-2 text-center">
                                            <div class="inline-flex gap-2">
                                                <div class="p-2 border w-fit rounded-md cursor-pointer bg-red-500 hover:bg-red-700"
                                                     @click="destroy(item.id)">
                                                    <TrashIcon class="w-6 text-white"/>
                                                </div>
                                                <div
                                                    class="p-2 border w-fit rounded-md cursor-pointer bg-slate-500 hover:bg-slate-700"
                                                    @click="edit(item)">
                                                    <PencilSquareIcon class="w-6 text-white"/>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </div>

        <!-- modal -->
        <LoadingBanner v-model="loading"/>
        <ResponsePopup
            v-model="showResponse"
            :message="messageResponse"/>
    </AuthenticatedLayout>
</template>
