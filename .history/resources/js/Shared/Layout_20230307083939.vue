<template>
  <Head>
    <meta
      type="description"
      content="Information about my app"
      head-key="description"
    />
  </Head>

  <section class="p-6 bg-gray-200">
    <header class="flex justify-between">
      <div class="flex items-center">
        <h1 class="font-bold text-lg">Championship Prediction</h1>
      </div>
      <Nav />
    </header>
  </section>

  <section class="p-6">
    <div class="max-w-5xl mx-auto">
      <slot />
    </div>
  </section>
</template>

<script>
import Nav from "./Nav.vue";

export default {
  components: { Nav },
};
</script>
