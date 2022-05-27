<template>
    <admin-layout title="Dashboard">

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Movie Attach
            </h2>
        </template>

        <div class="py-2">
            <div class="max-w-7xl mx-auto">
                <section class="container mx-auto p-6 font-mono">
                    <div class="w-full flex mb-4 p-2">

                        <Link :href="route('admin.movies.index')" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 mx-3 rounded-lg">View Movies</Link>

                    </div>

                    <div class="w-full mb-8 sm:max-w-md overflow-hidden bg-white rounded-lg shadow-lg">

                        <div class="flex justify-between">
                            <div class="flex-1">

                            </div>
                            <!--                                <div class="flex">-->
                            <!--                                    <select v-model="perPage"-->
                            <!--                                            @onchange="getTags"-->
                            <!--                                            class="px-4 py-3 w-full rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0 text-sm">-->
                            <!--                                        <option value="5">5 Per Page</option>-->
                            <!--                                        <option value="10">10 Per Page</option>-->
                            <!--                                        <option value="15">15 Per Page</option>-->
                            <!--                                    </select>-->
                            <!--                                </div>-->
                        </div>
                    </div>

                    <div class="w-full mb-8 p-6 overflow-hidden bg-white rounded-lg shadow-lg">

                    Trailer Form
                    </div>

                    <div class="w-full mb-8 p-6 overflow-hidden bg-white rounded-lg shadow-lg">

                        Cast  Form
                    </div>

                    <div class="w-full mb-8 p-6 overflow-hidden bg-white rounded-lg shadow-lg">

                        Tags Form
                    </div>

                </section>
            </div>
        </div>
    </admin-layout>

</template>

<script setup>

import AdminLayout from '../../Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { ref, watch, defineProps } from 'vue';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetCheckbox from "@/Jetstream/Checkbox.vue";



const props = defineProps(
    {
        movie:Object,
    });

const form = useForm({
    title: props.movie.title,
    runtime: props.movie.runtime,
    lang: props.movie.lang,
    video_format: props.movie.video_format,
    rating: props.movie.rating,
    poster_path: props.movie.poster_path,
    backdrop_path: props.movie.backdrop_path,
    overview: props.movie.overview,
    is_public:props.movie.is_public


})

function submitMovie()
{
    form.put('/admin/movies/' + props.movie.id)
}


</script>

<style scoped>

</style>
