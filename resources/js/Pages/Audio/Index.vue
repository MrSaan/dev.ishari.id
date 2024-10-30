<script setup lang="ts">

import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {ChevronRightIcon, ChevronUpIcon, PlayIcon, StopIcon} from "@heroicons/vue/24/solid";
import Dropdown from "@/Components/Dropdown.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {onMounted, ref} from "vue";
import axios from "axios";
import ResponsePopup from "@/Components/ResponsePopup.vue";
import LoadingBanner from "@/Components/LoadingBanner.vue";
import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Form from "@/Pages/Audio/Form.vue";
import {PencilSquareIcon, TrashIcon} from "@heroicons/vue/20/solid";


// variable
const loading = ref(false)
const showModalAddAudio = ref(false)
const messageResponse = ref<ResponseServer | null>(null)
const showResponse = ref(false)
const muhudList = ref<MuhudList[]>([])
const personList = ref<Person[]>([])
const textShalawat = ref<ShalawatList[]>([])
const audios = ref<Audio[]>([])
const selectedMuhud = ref<MuhudList>({
    arabic: "", id: 0, name: "Pilih Muhud", position: 0, translateId: "", transliteration: ""
})
const selectedShalawat = ref<ShalawatList|null>(null)
const audioPath = ref<String|null>(null)
const audio = ref<any>(null); // Menyimpan referensi ke elemen audio
const audioPlayer = new Audio()

// logic
onMounted(() => {
    getData()
})

const getData = async () => {
    try {
        loading.value = true
        const response = await axios.get('/muhud/get')
        const persons = await axios.get('/pimpinan-shalawat/get')
        personList.value = persons.data.data
        muhudList.value = response.data.data
        selectedMuhud.value = muhudList.value[0]
        await getShalawat(selectedMuhud.value.name)
    } catch (e) {
        messageResponse.value = e as ResponseServer
        showResponse.value = true
    } finally {
        loading.value = false
    }
}

const getAudio = async (idShalawat: number|null) => {

    close() // close disclosure

    try {
        if (idShalawat) {
            loading.value = true
            const response = await axios.get(`/audio/${idShalawat}/get`)
            audios.value[idShalawat] = response.data.data
        }
    } catch (e) {
        messageResponse.value = e as ResponseServer
        showResponse.value = true
    } finally {
        loading.value = false
    }
}

const getShalawat = async (name: string) => {
    try {
        loading.value = true
        const response = await axios.get(`/shalawat/${name}/get`)
        textShalawat.value = response.data.data
    } catch (e) {
        messageResponse.value = e as ResponseServer
        showResponse.value = true
    } finally {
        loading.value = false
    }
}

const selectMuhud = (item: MuhudList) => {
    selectedMuhud.value = item
    getShalawat(item.name)
}

const destroy = async (id: number|null) => {
    try {
        loading.value = true
        const response = await axios.delete(`/audio/${id}`)
        messageResponse.value = {
            status: response.data.status,
            message: response.data.message
        }
        showResponse.value = true

        if (selectedShalawat.value)
            await getAudio(selectedShalawat.value.id)

    } catch (e) {
        messageResponse.value = e as ResponseServer
        showResponse.value = true
    } finally {
        loading.value = false
    }
}

const playAudio = (path: string) => {

    if (!path) {
        console.error("Path audio tidak tersedia.");
        return;
    }

    audioPlayer.src = path
    audioPlayer.play()
}

const stopAudio = () => {
    audioPlayer.pause()
    audioPlayer.currentTime = 0
}
</script>

<template>
    <Head title="Audio"/>

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Ishari App - Dev
            </h2>
        </template>

        <!-- Audio play -->
        <div class="hidden">
            <audio id="audio" controls></audio>
        </div>

        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="bg-gray-200 shadow-md flex flex-col items-center md:flex-row md:justify-between w-full gap-4 p-2 rounded-lg font-medium text-base sm:p-2 lg:p-6">
                    <div>Step tambah Audio Shalawat</div>
                    <ChevronRightIcon class="w-6"/>
                    <div>Pilih Muhud</div>
                    <ChevronRightIcon class="w-6"/>
                    <div>Pilih Shalawat</div>
                    <ChevronRightIcon class="w-6"/>
                    <div>Tambah Audio Shalawat</div>
                </div>
            </div>

            <!-- muhud list -->
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mt-10 px-4 md:px-8 md:w-96 space-y-1">
                    <InputLabel value="Muhud" />
                    <Dropdown >
                        <template #trigger>
                            <div class="bg-white inline-flex items-center w-full border border-gray-300 rounded-md py-2.5 px-4">
                                <div class="w-full">
                                    {{selectedMuhud.name}}
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
                                    v-for="item in muhudList"
                                    class="py-2 px-4 hover:bg-gray-100 rounded-md"
                                    @click="selectMuhud(item)">
                                    {{item.name}}
                                </div>
                            </div>
                        </template>
                    </Dropdown>
                </div>
            </div>

            <!-- teks shalawat list -->
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-6">
                <Disclosure v-for="item in textShalawat" v-slot="{open}">
                    <DisclosureButton
                        @click="getAudio(item.id)"
                        class="py-4 px-6 w-full bg-white flex justify-between border-b ">
                        <div class="text-left inline-flex gap-4">
                            <div>{{item.id}}</div>
                            <div class="font-amiri text-xl">{{item.text}}</div>
                        </div>
                        <ChevronUpIcon
                            :class="open ? 'rotate-180 transform' : ''"
                            class="h-5 w-5"
                        />
                    </DisclosureButton>
                    <DisclosurePanel class="bg-white border-b w-full">
                        <div class="w-full px-1 py-6">
                            <table class="table-auto w-full">
                                <thead>
                                <tr class="border-b">
                                    <th class="py-2 text-center">No.</th>
                                    <th class="py-2 text-center">Pimpinan</th>
                                    <th class="py-2 text-center">Deskripsi</th>
                                    <th class="py-2 text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(audio, index) in audios[item.id ?? 0]" class="border-b">
                                    <td class="py-2 text-center">{{index + 1}}</td>
                                    <td class="py-2 text-center">{{audio.pimpinan.name}}</td>
                                    <td class="py-2 text-center">{{audio.description}}</td>
                                    <td class="py-2 text-center">
                                        <div class="inline-flex gap-2">
                                            <div @click="playAudio(audio.path)" class="p-2 border w-fit rounded-md cursor-pointer bg-slate-900 hover:bg-red-700">
                                                <PlayIcon class="w-6 text-white" />
                                            </div>
                                            <div @click="stopAudio()" class="p-2 border w-fit rounded-md cursor-pointer bg-slate-900 hover:bg-red-700">
                                                <StopIcon class="w-6 text-white" />
                                            </div>
                                            <div @click="destroy(audio.id)" class="p-2 border w-fit rounded-md cursor-pointer bg-red-500 hover:bg-red-700">
                                                <TrashIcon class="w-6 text-white" />
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="mt-6 w-full inline-flex justify-center md:justify-end pr-4 mx-auto">
                                <PrimaryButton @click="[showModalAddAudio = true, selectedShalawat = item]">
                                    Tambah Audio
                                </PrimaryButton>
                            </div>
                        </div>
                    </DisclosurePanel>
                </Disclosure>
            </div>

        </div>

        <!-- modal -->
        <LoadingBanner v-model="loading"/>
        <Form
            :show="showModalAddAudio"
            :persons="personList"
            :shalawat="selectedShalawat"
            @load="getAudio(selectedShalawat!.id)"
            @close="showModalAddAudio = false"/>
        <ResponsePopup
            v-model="showResponse"
            :message="messageResponse"/>
    </AuthenticatedLayout>
</template>
