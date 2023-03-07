<script setup>
import Layout from "../Shared/Layout.vue";
import { Head } from "@inertiajs/vue3";

let props = defineProps({
  fixtures: Array,
});
</script>

<template>
  <Layout>
    <Head title="Fixtures" />
    <div class="flex flex-col">
      <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
          <div class="overflow-hidden">
            <table class="min-w-full text-center text-sm font-light">
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
                  v-for="fixture in fixtures"
                  class="border-b dark:border-neutral-500"
                >
                  <td v-for="f in fixture" class="whitespace-nowrap px-6 py-4">
                    {{ f.week }}
                  </td>
                </tr>
              </tbody>
            </table>

          </div>

        </div>
      </div>

    </div>
  </Layout>
</template>
