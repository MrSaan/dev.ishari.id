<script setup lang="ts">

import {onMounted, ref} from "vue";
import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Form from "@/Pages/Figure/Form.vue";
import axios from "axios";
import ResponsePopup from "@/Components/ResponsePopup.vue";
import LoadingBanner from "@/Components/LoadingBanner.vue";
import {PencilSquareIcon, TrashIcon} from "@heroicons/vue/20/solid";

// variable
const loading = ref(false)
const messageResponse = ref<ResponseServer | null>(null)
const showResponse = ref<Boolean>(false)
const personList = ref<Person[]>([])
const person = ref<Person>({
    id: null,
    name: '',
    description: '',
    avatar: null
})

// logic
onMounted(() => {
    getData()
})

const getData = async () => {
    try {
        loading.value = true
        const response = await axios.get('/pimpinan-shalawat/get')
        personList.value = response.data.data
    } catch (e) {
        messageResponse.value = e as ResponseServer
        showResponse.value = true
    } finally {
        loading.value = false
    }
}

const destroy = async (id: number|null) => {
    try {
        loading.value = true
        const response = await axios.delete(`/pimpinan-shalawat/${id}`)
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

const edit = (item: Person) => {
    window.scrollTo({top: 0, behavior: 'smooth'})

    person.value.id = item.id
    person.value.name = item.name
    person.value.description = item.description
}

</script>

<template>
    <Head title="Pimpinan Muhud"/>

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
                    Tambah Pimpinan Rodat
                </div>

                <Form
                    :dataEdit="person"
                    @load="getData"/>

                <div class="overflow-auto p-4 mt-10 bg-white shadow-sm sm:rounded-lg">
                    <table class="w-full table-auto overflow-auto">
                        <thead>
                        <tr class="border-b-2 align-top">
                            <th class="py-2 border-b font-medium text-left px-2 sm:px-0" >Nama</th>
                            <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Deskripsi</th>
                            <th class="py-2 border-b font-medium text-left px-2 sm:px-0">Path avatar</th>
                            <th class="py-2 border-b font-medium text-center px-2 sm:px-0">Action</th>
                        </tr>
                        </thead>
                        <tbody v-if="personList && personList.length > 0">
                        <tr v-for="item in personList" class="border-b-2 hover:bg-slate-200">
                            <td class="py-2 text-left">{{item.name}}</td>
                            <td class="py-2">{{item.description}}</td>
                            <td class="py-2">{{item.avatar}}</td>
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
