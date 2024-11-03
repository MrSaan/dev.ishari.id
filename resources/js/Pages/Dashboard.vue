<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import {onMounted, ref} from "vue";
import LoadingBanner from "@/Components/LoadingBanner.vue";
import ResponsePopup from "@/Components/ResponsePopup.vue";
import axios from "axios";

// variable
const messageResponse = ref<ResponseServer | null>(null)
const showResponse = ref(false)
const loading = ref(false)
const information = ref(null)

// logic
onMounted(() => {
    getData()
})

const getData = async () => {
    try {
        loading.value = true
        const response = await axios.get('/dashboard/get-info')
        information.value = response.data.data
    } catch (e) {
        messageResponse.value = e as ResponseServer
        showResponse.value = true
    } finally {
        loading.value = false
    }
}

const title = (text: any) => {
    switch (text) {
        case 'sumMuhud':
            return 'Jumlah Muhud';
        case 'sumAudio':
            return 'Jumlah Audio';
        case 'sumPimpinan':
            return 'Jumlah Pimpinan';
        case 'sumTextShalawat':
            return 'Jumlah Teks Shalawat';
        default:
            return '';
    }
}

const toPage = (text: any) => {
    switch (text) {
        case 'sumMuhud':
            router.get(route('muhud.index'));
            break;

        case 'sumAudio':
            router.get(route('audio.index'));
            break;

        case 'sumPimpinan':
            router.get(route('figure.index'));
            break;

        case 'sumTextShalawat':
            router.get(route('shalawat.index'));
            break;

        default:
            return '';
    }
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Ishari App - Dev
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="grid grid-cols-2 px-4 md:px-0 md:grid-cols-4 gap-4"
                >
                    <div
                        v-for="(info, index) in information" class="p-6 bg-white shadow-sm rounded-md text-gray-900">
                        <div @click="toPage(index)" class="w-full cursor-pointer flex flex-col gap-4 items-start justify-between">
                            <div class="text-lg">{{title(index)}}</div>
                            <div class="text-4xl font-medium">{{info}}</div>
                        </div>
                    </div>
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
