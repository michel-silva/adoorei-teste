<template>
    <app-layout title="RASTREAMENTO DE ENCOMENDAS">
        <div class="flex min-h-screen">
            <div class="w-1/4 py-8 bg-slate-200">
                <img class="object-fill mx-auto px-4" src="/images/app/logo-header.svg" />
            </div>

            <div class="w-3/4 bg-slate-50 p-4">
                <div class="flex justify-between">
                    <div class="px-4 py-3">
                        <div class="text-xl">RASTREAMENTO DE ENCOMENDAS</div>
                        <div class="text-sm">
                            Acompanhe seus pacotes enviados em tempo real
                        </div>
                    </div>
                    <div class="py-3">
                        <button @click="openModalTracking()" type="button" class="py-2.5 px-5 mr-2 mb-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            Cadastrar Código
                        </button>
                        <button @click="updateTrackingCodes()" type="button" class="py-2.5 px-5 mr-2 mb-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            Atualizar Códigos
                        </button>
                    </div>

                </div>

                <div class="py-6">
                    <span>Filtrar</span>
                    <select name="statusTracks" id="statusTracks" @change="changeStatus">
                        <option value="Todos" selected>Todos</option>
                        <option v-bind:value="status" v-for="(status, index) in statusTypes" :key="index">{{status}}</option>
                    </select>
                </div>


                <div v-if="sortedFilterTracks.length <= 0" class="text-center">
                    Sem códigos para mostrar
                </div>
                <table class="table-auto w-full" v-if="sortedFilterTracks.length > 0">
                    <thead>
                        <tr>
                            <th class="w-1/6" @click="sort('tracking_number')">
                                CÓDIGO
                                <span v-if="'tracking_number' === currentSort">
                                    <i :class="[currentSortDir === 'asc' ? 'fas fa-arrow-up' : 'fas fa-arrow-down' ]">
                                    </i>
                                </span>
                            </th>
                            <th class="w-1/6" @click="sort('status')">STATUS</th>
                            <th class="w-3/6">ÚLTIMA ATUALIZAÇÃO</th>
                            <th class="w-1/6">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(track, index) in sortedFilterTracks" :key="index">
                            <td class="border px-4 py-2 font-medium text-center">
                                {{ track.tracking_number }}
                            </td>
                            <td class="border px-4 py-2 font-medium text-center">
                                <div class="text-xs px-2 py-0.5 font-bold bg-gray-300 text-gray-600 rounded-lg">
                                    {{ track.status }}
                                </div>
                            </td>
                            <td class="border px-4 py-2 font-medium">
                                {{ track.last_update }}
                            </td>
                            <td class="border px-4 py-2 font-medium">
                                <div class="flex place-content-around">
                                    <button @click="deleteTrackingCode(track.id, track.tracking_number)">X</button>
                                    <svg @click="openModalHistoryTracking(track.history)" class="h-8 w-8 text-black-500 cursor-pointer"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <circle cx="7" cy="17" r="2" />  <circle cx="17" cy="17" r="2" />  <path d="M5 17h-2v-4m-1 -8h11v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" />  <line x1="3" y1="9" x2="7" y2="9" /></svg>

                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </app-layout>

    <!-- Modal add tracking number-->
    <div class="fixed inset-0 overflow-y-auto" v-if="isTrackingModalOpen">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">'
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>'

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">

                <form>
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="">
                                <div class="mb-4">
                                    <h3 for="tracking_number" class="block text-gray-700 text-md font-bold mb-2">CADASTRAR CÓDIGO</h3>
                                    <h4 for="tracking_number" class="text-sm"> Informe o código de rastreio que deseja monitorar.</h4>
                                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="tracking_number" placeholder="Digite o Código" v-model="form.tracking_number">
                                </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                            <button @click="saveTrackingCode()" wire:click.prevent="store()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                            Monitorar
                            </button>
                        </span>
                        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                            <button @click="closeModalTracking()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Fechar
                            </button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal tracking number-->


    <!-- Modal history tracking number-->
    <div class="fixed inset-0 overflow-y-auto" v-if="isHistoryModalOpen">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">'
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>'

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
            <div class="inline-block align-bottom bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">

                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                        <button @click="closeModalHistory()" type="button" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                        X
                        </button>
                    </span>
                </div>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 h-96 overflow-y-auto">
                    <div class="">
                            <div class="mb-4 " v-for="(history, index) in selectedHistory" :key="index">
                                {{history.dtHrCriado}} - {{history.unidade.endereco.cidade}} - {{history.descricao}}
                            </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- end modal history tracking number-->

</template>


<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import JetApplicationLogo from "@/Jetstream/ApplicationLogo.vue";
import JetModal from "@/Jetstream/Modal.vue";

export default defineComponent({
    components: {
        AppLayout,
        JetApplicationLogo,
        JetModal,
    },
    props: ['tracks', 'statusTypes'],
    data() {
        return {
            isTrackingModalOpen: false,
            isHistoryModalOpen: false,
            isOpen: false,
            form: {
                tracking_number: null,
            },
            selectedHistory: [],
            currentSort: 'status',
            currentSortDir: 'asc',
            filterType: 'Todos'

        }
    },
    methods: {
        updateTrackingCodes() {
            this.$inertia.post('/tracking/updateTracks');
        },
        sort(s) {
            if(s === this.currentSort) {
            this.currentSortDir = this.currentSortDir==='asc'?'desc':'asc';
            }
            this.currentSort = s;
        },
        openModalTracking() {
            this.isTrackingModalOpen = true;
        },
        openModalHistoryTracking(history) {
            this.selectedHistory = JSON.parse(history);
            this.isHistoryModalOpen = true;
        },
        closeModalHistory() {
            this.isHistoryModalOpen = false;
        },
        closeModalTracking() {
            this.isTrackingModalOpen = false;
            this.resetForm();
        },
        saveTrackingCode() {
            this.$inertia.post('/tracking', this.form);
            this.resetForm();
            this.closeModalTracking();
        },
        resetForm() {
            this.form = {
                tracking_number: null
            }
        },
        deleteTrackingCode(id, tracking_number) {
            if (!confirm(`Deseja realmente apagar o código ${tracking_number}?`)) return;

                this.$inertia.delete(`/tracking/${id}`);
                this.resetForm();
                this.closeModalTracking();
        },
        changeStatus(e) {
            if(e.target.options.selectedIndex > -1) {
                let value = e.target.options[e.target.options.selectedIndex].value;
                this.filterType = value;
            }
        }
    },
    computed:{
        sortedFilterTracks() {

            let tracksFiltered =  [...this.tracks];

            tracksFiltered = tracksFiltered.filter(
                track => {
                    return ( this.filterType == 'Todos' ) ? track : (track.status == this.filterType) ? track : null;
                }
            );

            return tracksFiltered.sort((a,b) => {
            let modifier = 1;
            if(this.currentSortDir === 'desc') modifier = -1;
            if(a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
            if(a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
            return 0;
            });
        }
    }
});
</script>
