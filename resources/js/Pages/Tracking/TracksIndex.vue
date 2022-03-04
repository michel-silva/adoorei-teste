<template>
  <app-layout title="RASTREAMENTO DE ENCOMENDAS">
    <template #header>
      <div class="flex justify-between">
        <div class="px-4 py-3">
          <div class="text-xl">RASTREAMENTO DE ENCOMENDAS</div>
          <div class="text-sm">
            Acompanhe seus pacotes enviados em tempo real
          </div>
        </div>
        <div class="py-3">
          <jet-button @click="openModalTracking()">
            Cadastrar Código
          </jet-button>
          <jet-input-error :message="errors.tracking_number" class="mt-2" />

          <jet-button
            class="lg:ml-2 md:mt-2 sm:mt-2"
            @click="updateTrackingCodes()"
          >
            Atualizar Códigos
          </jet-button>
        </div>
      </div>
    </template>

    <div class="py-2">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl sm:rounded-lg">
          <div class="bg-slate-50 p-4">
            <tracks-list :tracks="tracks" :statusTypes="statusTypes" />
          </div>
        </div>
      </div>
    </div>
  </app-layout>

  <!-- Modal add tracking number-->
  <jet-dialog-modal :show="isTrackingModalOpen" @close="closeModalTracking">
    <template #title>
      <h3 class="text-gray-700 text-md font-bold">CADASTRAR CÓDIGO</h3>
      <h4 class="text-sm">
        Informe o código de rastreio que deseja monitorar.
      </h4>
    </template>

    <template #content>
      <input
        ref="inputTrack"
        type="text"
        class="
          shadow
          appearance-none
          border
          rounded
          w-full
          py-2
          px-3
          text-gray-700
          leading-tight
          focus:outline-none focus:shadow-outline
        "
        id="tracking_number"
        placeholder="Digite o Código"
        v-model="form.tracking_number"
      />
    </template>

    <template #footer>
      <jet-secondary-button @click="closeModalTracking">
        Cancelar
      </jet-secondary-button>

      <jet-button
        class="ml-3"
        @click="saveTrackingCode"
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        Monitorar
      </jet-button>
    </template>
  </jet-dialog-modal>
</template>


<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import JetDialogModal from "@/Jetstream/DialogModal.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";

import TracksList from "@/Pages/Tracking/TracksList.vue";

export default defineComponent({
  components: {
    AppLayout,
    JetDialogModal,
    JetSecondaryButton,
    JetButton,
    JetInput,
    JetInputError,

    TracksList,
  },
  props: ["tracks", "statusTypes", "errors"],
  data() {
    return {
      isTrackingModalOpen: false,
      form: this.$inertia.form({
        tracking_number: "",
      }),
    };
  },
  methods: {
    updateTrackingCodes() {
      this.$inertia.post("/tracking/updateTracks");
    },
    openModalTracking() {
      this.isTrackingModalOpen = true;
      this.$nextTick(function () {
        this.$refs.inputTrack.focus();
      });
    },
    closeModalTracking() {
      this.isTrackingModalOpen = false;
      this.resetForm();
    },
    saveTrackingCode() {
      this.$inertia.post("/tracking", this.form);
      this.resetForm();
      this.closeModalTracking();
    },
    resetForm() {
      this.form = {
        tracking_number: null,
      };
    },
  },
});
</script>
