<script setup>
import Layout from "../Shared/Layout.vue";
import { Head } from "@inertiajs/vue3";

let props = defineProps({
  fixtures: Object,
});
</script>

<template>
  <Layout>
    <Head title="Fixtures" />
    <div class="flex flex-col">
      <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
          <div class="overflow-hidden" style="display: grid; grid-template-columns: repeat(2, 1fr); grid-auto-rows: 1fr;">
            <table v-for="(fixture, index) in fixtures" :key="index" class="text-center text-sm font-light m-2">
              <thead
                class="border-b bg-neutral-800 font-medium text-white dark:border-neutral-500 dark:bg-neutral-900"
              >
                <tr>
                  <th scope="col" class="px-6 py-4">Week {{ index }}</th>
                  <th scope="col" class="px-6 py-4">Home/Away</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="f in fixture"
                  class="border dark:border-neutral-500"
                  :key="f.id"
                >
                  <td class="whitespace-nowrap px-6 py-4">{{ f.home_team.name }}</td>
                  <td class="whitespace-nowrap px-6 py-4">{{ f.away_team.name }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

