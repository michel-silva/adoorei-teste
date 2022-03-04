<template>
  <div class="flex flex-wrap mb-2">
    <div class="w-full md:w-1/2 xl:w-1/3 pt-3 px-3 md:pl-2">
      <div class="bg-blue-600 border rounded shadow p-2">
        <div class="flex flex-row items-center">
          <div class="flex-shrink pl-1 pr-4">
            <i class="fa-solid fa-truck-fast fa-2x fa-fw fa-inverse"></i>
          </div>
          <div class="flex-1 text-right">
            <h5 class="text-white">Total</h5>
            <h3 class="text-white text-3xl">
              {{totalTracks}}
            </h3>
          </div>
        </div>
      </div>
    </div>
    <div class="w-full md:w-1/2 xl:w-1/3 pt-3 px-3 md:pl-2" v-for="(group, index) in countTracksGroup" :key="index">
      <div class="bg-blue-600 border rounded shadow p-2">
        <div class="flex flex-row items-center">
          <div class="flex-shrink pl-1 pr-4">
            <i class="fa-solid fa-truck-fast fa-2x fa-fw fa-inverse"></i>
          </div>
          <div class="flex-1 text-right">
            <h5 class="text-white">{{group.status}}</h5>
            <h3 class="text-white text-3xl">
              {{group.total}}
            </h3>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import { defineComponent } from "vue"

export default defineComponent({
    component: {

    },
    props: ['countTracksGroup', 'totalTracks', 'statusTypes'],
});
</script>

<style>
</style>
