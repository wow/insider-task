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
            <table v-for="fixture in fixtures" :key="index" class="min-w-full text-center text-sm font-light">
              <thead
                class="border-b bg-neutral-800 font-medium text-white dark:border-neutral-500 dark:bg-neutral-900"
              >
                <tr>
                  <th scope="col" class="px-6 py-4">#</th>
                  <th scope="col" class="px-6 py-4">Team Name</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="f in fixture"
                  class="border-b dark:border-neutral-500"
                  :key="f.id"
                >
                  <td class="whitespace-nowrap px-6 py-4">{{ f.week }}</td>
                  <td class="whitespace-nowrap px-6 py-4">{{ f.home_team.name }} vs {{ f.away_team.name }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Add Button -->
          <div class="flex justify-end mt-4">
            <button
              class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue"
            >
              Generate Fixture
            </button>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

