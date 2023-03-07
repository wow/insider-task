<script setup>
import Layout from "../Shared/Layout.vue";
import { Head } from "@inertiajs/vue3";
import NavLink from "../Shared/NavLink.vue";

let props = defineProps({
  standingTables: Object,
  fixtures: Object,
  currentWeek: Number,
  predictions: Object,
});
</script>

<template>
  <Layout>
    <Head title="Teams" />
    <div class="flex flex-wrap">
      <div class="w-full lg:w-2/3 p-6">
        <table class="min-w-full text-center text-sm font-light">
          <thead
            class="border-b bg-neutral-800 font-medium text-white dark:border-neutral-500 dark:bg-neutral-900"
          >
            <tr>
              <th scope="col" class="px-6 py-4">Team Name</th>
              <th scope="col" class="px-6 py-4">P</th>
              <th scope="col" class="px-6 py-4">W</th>
              <th scope="col" class="px-6 py-4">D</th>
              <th scope="col" class="px-6 py-4">L</th>
              <th scope="col" class="px-6 py-4">GF</th>
              <th scope="col" class="px-6 py-4">GA</th>
              <th scope="col" class="px-6 py-4">GD</th>
              <th scope="col" class="px-6 py-4">Pts</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="standingTable in standingTables"
              :key="standingTable.id"
              class="border-b dark:border-neutral-500"
            >
              <td class="whitespace-nowrap px-6 py-4">
                {{ standingTable.team.name }}
              </td>
              <td class="whitespace-nowrap px-6 py-4">
                {{ standingTable.played }}
              </td>
              <td class="whitespace-nowrap px-6 py-4">
                {{ standingTable.won }}
              </td>
              <td class="whitespace-nowrap px-6 py-4">
                {{ standingTable.drawn }}
              </td>
              <td class="whitespace-nowrap px-6 py-4">
                {{ standingTable.lost }}
              </td>
              <td class="whitespace-nowrap px-6 py-4">
                {{ standingTable.goals_for }}
              </td>
              <td class="whitespace-nowrap px-6 py-4">
                {{ standingTable.goals_against }}
              </td>
              <td class="whitespace-nowrap px-6 py-4">
                {{ standingTable.goal_difference }}
              </td>
              <td class="whitespace-nowrap px-6 py-4">
                {{ standingTable.points }}
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Add Button -->
        <div class="flex">
          <div class="justify mt-4">
            <NavLink
              href="/simulations/simulate"
              method="post"
              as="button"
              class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue"
            >
              Simulate All Weeks
            </NavLink>
          </div>
          <div class="justify mt-4 ml-2 object-right">
            <NavLink
              href="/simulations/simulate-current"
              method="post"
              as="button"
              class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-yellow-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue"
            >
              Simulate Week ({{ currentWeek }})
            </NavLink>
          </div>
        </div>
      </div>

      <div class="w-full lg:w-1/3 p-6">
        <table class="min-w-full text-center text-sm font-light">
          <thead
            class="border-b bg-neutral-800 font-medium text-white dark:border-neutral-500 dark:bg-neutral-900"
          >
            <tr>
              <th scope="col" class="px-6 py-4">Championship Prediction</th>
              <th scope="col" class="px-6 py-4">%</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="prediction in predictions"
              :key="prediction.id"
              class="border-b dark:border-neutral-500"
            >
              <td class="whitespace-nowrap px-6 py-4">
                {{ prediction.team.name }}
              </td>
              <td class="whitespace-nowrap px-6 py-4">0</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="flex flex-wrap">
      <div
        v-for="(fixture, index) in fixtures"
        :key="index"
        class="w-full lg:w-1/3 p-6"
      >
        <table class="text-center text-sm font-light m-2">
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
              <td class="whitespace-nowrap px-6 py-4 text-right">
                {{ f.home_team.name }} (<strong>{{ f.game.home_team_score }}</strong>)
              </td>
              <td class="whitespace-nowrap px-6 py-4 text-left">
                (<strong>{{ f.game.away_team_score }}</strong>) {{ f.away_team.name }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </Layout>
</template>
