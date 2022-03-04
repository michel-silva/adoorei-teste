<template>
  <div>
    <span class="mr-2">Filtrar</span>
    <select
      class="
        inline-flex
        items-center
        px-4
        py-2
        bg-gray-800
        border border-transparent
        rounded-md
        font-semibold
        text-xs text-white
        uppercase
        tracking-widest
        hover:bg-gray-700
        active:bg-gray-900
        focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300
        disabled:opacity-25
        transition
      "
      name="statusTracks"
      id="statusTracks"
      @change="changeStatusFilter"
    >
      <option value="Todos" selected>Todos</option>
      <option
        v-bind:value="status.short_status"
        v-for="(status, index) in statusTypes"
        :key="index"
      >
        {{ status.short_status }}
      </option>
    </select>
  </div>

  <div class="overflow-x-auto pt-4">
    <div v-if="sortedFilterTracks.length <= 0" class="text-center">
      Sem códigos para mostrar
    </div>
    <table
      class="table-auto w-11/12 mx-auto">
      <thead>
        <tr>
          <th class="w-1/6 border font-medium cursor-pointer" @click="sort('tracking_number')">
            CÓDIGO
            <span>
              <i
                class="fa fa-arrow-up"
                :class="[
                  'tracking_number' === currentSort
                    ? currentSortDir === 'asc'
                      ? 'text-slate-500'
                      : ''
                    : 'text-slate-500',
                ]"
              >
              </i>
              <i
                class="fa fa-arrow-down"
                :class="[
                  'tracking_number' === currentSort
                    ? currentSortDir === 'desc'
                      ? 'text-slate-500'
                      : ''
                    : 'text-slate-500',
                ]"
              >
              </i>
            </span>
          </th>
          <th class="w-1/6 border font-medium cursor-pointer" @click="sort('status')">
            STATUS
            <span>
              <i
                class="fa fa-arrow-up"
                :class="[
                  'status' === currentSort
                    ? currentSortDir === 'asc'
                      ? 'text-slate-500'
                      : ''
                    : 'text-slate-500',
                ]"
              >
              </i>
              <i
                class="fa fa-arrow-down"
                :class="[
                  'status' === currentSort
                    ? currentSortDir === 'desc'
                      ? 'text-slate-500'
                      : ''
                    : 'text-slate-500',
                ]"
              >
              </i>
            </span>
          </th>
          <th class="w-3/6 border font-medium">ÚLTIMA ATUALIZAÇÃO</th>
          <th class="w-1/6 border font-medium">AÇÕES</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(track, index) in sortedFilterTracks" :key="index">
          <td class="border px-4 py-2 font-medium text-center">
            {{ track.tracking_number }}
          </td>
          <td class="border px-4 py-2 font-medium text-center">
            <div
              class="
                text-xs
                px-2
                py-0.5
                font-bold
                bg-gray-300
                text-gray-600
                rounded-lg
              "
            >
              {{ track.status }}
            </div>
          </td>
          <td class="border px-4 py-2 font-medium">
            {{ track.last_update }}
          </td>
          <td class="border">
            <div class="flex place-content-around">

              <i class="fa-solid fa-trash-can fa-lg  cursor-pointer" @click="openRemoveTrackModal(track)"></i>
              <i class="fa-solid fa-truck-fast fa-lg cursor-pointer" @click="openModalHistoryTracking(track)"></i>

            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Modal events tracking number-->
  <jet-dialog-modal :show="isHistoryModalOpen" @close="closeModalHistory">
    <template #title>
      RASTREAMENTO - {{ selectedTrack.tracking_number }}
    </template>
    <template #content>
      <div class="bg-white  h-96 w-full overflow-y-auto overflow-x-auto">
        <div class="mx-auto" v-if="selectedTrack.events.length <= 0">
            Aguardando Postagem
        </div>

        <table v-if="selectedTrack.events.length > 0" class="table-auto order-collapse border border-slate-400 w-full">
          <thead>
            <tr>
              <td class="border border-slate-300 text-center">Data</td>
              <td class="border border-slate-300 text-center">Local</td>
              <td class="border border-slate-300 text-center">Status</td>
            </tr>
          </thead>
          <tbody>
            <tr
              v-bind:class="{ 'bg-gray-100': index % 2 == 0 }"
              v-for="(event, index) in selectedTrack.events"
              :key="index"
            >
              <td class="border border-slate-300 px-2 text-center">{{ event.date_event }}</td>
              <td class="border border-slate-300 px-2 text-center">
                {{
                  JSON.parse(event.unit).nome
                    ? JSON.parse(event.unit).nome
                    : JSON.parse(event.unit).endereco.cidade +
                      "-" +
                      JSON.parse(event.unit).endereco.uf
                }}
              </td>
              <td class="border border-slate-300 px-2 text-center">{{ event.event }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </template>

    <template #footer>
      <jet-secondary-button @click="closeModalHistory">
        Fechar
      </jet-secondary-button>
    </template>
  </jet-dialog-modal>

  <!-- Modal confirm remove track-->
  <jet-confirmation-modal
    :show="isRemoveTrackModalOpen"
    @close="closeRemoveTrackModal"
  >
    <template #title>
      Excluir Monitoramento {{ trackRemove.tracking_number }}</template
    >

    <template #content>
      Essa operação é irreversivel. O código será excluído e deixará de ser
      monitorado. Clique em excluir para confirmar a operação!
    </template>

    <template #footer>
      <jet-secondary-button @click="closeRemoveTrackModal()"
        >Fechar</jet-secondary-button
      >
      <jet-danger-button
        class="ml-3"
        @click="deleteTrackingCode(trackRemove.id)"
        >Excluir</jet-danger-button
      >
    </template>
  </jet-confirmation-modal>
</template>

<script>
import { defineComponent } from "vue";
import JetButton from "@/Jetstream/Button.vue";
import JetDangerButton from "@/Jetstream/DangerButton.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetDialogModal from "@/Jetstream/DialogModal.vue";
import JetConfirmationModal from "@/Jetstream/ConfirmationModal.vue";

export default defineComponent({
  components: {
    JetButton,
    JetDangerButton,
    JetSecondaryButton,
    JetDialogModal,
    JetConfirmationModal,
  },
  props: ['tracks', 'statusTypes'],
  data() {
    return {
      trackRemove: {},
      isRemoveTrackModalOpen: false,
      isHistoryModalOpen: false,
      selectedTrack: {},
      currentSort: "status",
      currentSortDir: "asc",
      filterType: "Todos",
    };
  },
  methods: {
    openRemoveTrackModal(track) {
      this.trackRemove = track;
      this.isRemoveTrackModalOpen = true;
    },
    closeRemoveTrackModal() {
      this.trackRemove = {};
      this.isRemoveTrackModalOpen = false;
    },
    openModalHistoryTracking(track) {
      this.selectedTrack = track;
      this.isHistoryModalOpen = true;
    },
    closeModalHistory() {
      this.isHistoryModalOpen = false;
    },
    deleteTrackingCode(id) {
      this.$inertia.delete(`/tracking/${id}`);
      this.closeRemoveTrackModal();
    },
    changeStatusFilter(e) {
      if (e.target.options.selectedIndex > -1) {
        let value = e.target.options[e.target.options.selectedIndex].value;
        this.filterType = value;
      }
    },

    sort(s) {
      if (s === this.currentSort) {
        this.currentSortDir = this.currentSortDir === "asc" ? "desc" : "asc";
      }
      this.currentSort = s;
    },
  },
  computed: {
    sortedFilterTracks() {
      let tracksFiltered = [...this.tracks];

      tracksFiltered = tracksFiltered.filter((track) => {
        return this.filterType == "Todos"
          ? track
          : track.status == this.filterType
          ? track
          : null;
      });

      return tracksFiltered.sort((a, b) => {
        let modifier = 1;
        if (this.currentSortDir === "desc") modifier = -1;
        if (a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
        if (a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
        return 0;
      });
    },
  },
});
</script>

<style>
</style>
