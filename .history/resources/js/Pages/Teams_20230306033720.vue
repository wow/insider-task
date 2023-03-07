<script setup>
import Layout from "../Shared/Layout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
  <Layout>
    <Head title="Teams" />
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
                  <th scope="col" class="px-6 py-4">First</th>
                  <th scope="col" class="px-6 py-4">Last</th>
                  <th scope="col" class="px-6 py-4">Handle</th>
                </tr>
              </thead>
              <tbody>
                <tr class="border-b dark:border-neutral-500">
                  <td class="whitespace-nowrap px-6 py-4 font-medium">1</td>
                  <td class="whitespace-nowrap px-6 py-4">Mark</td>
                  <td class="whitespace-nowrap px-6 py-4">Otto</td>
                  <td class="whitespace-nowrap px-6 py-4">@mdo</td>
                </tr>
                <tr class="border-b dark:border-neutral-500">
                  <td class="whitespace-nowrap px-6 py-4 font-medium">2</td>
                  <td class="whitespace-nowrap px-6 py-4">Jacob</td>
                  <td class="whitespace-nowrap px-6 py-4">Thornton</td>
                  <td class="whitespace-nowrap px-6 py-4">@fat</td>
                </tr>
                <tr class="border-b dark:border-neutral-500">
                  <td class="whitespace-nowrap px-6 py-4 font-medium">3</td>
                  <td colspan="2" class="whitespace-nowrap px-6 py-4">
                    Larry the Bird
                  </td>
                  <td class="whitespace-nowrap px-6 py-4">@twitter</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>
