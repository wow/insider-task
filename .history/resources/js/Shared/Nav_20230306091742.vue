<template>
    <nav>
      <ul class="flex space-x-6">
        <li>
          <NavLink href="/" :active="$page.component === 'Home'">
            Home
          </NavLink>
        </li>

        <li>
          <NavLink href="/teams" :active="$page.component === 'Teams'">
            Teams
          </NavLink>
        </li>

        <li>
          <NavLink href="/fixtures" :active="$page.component === 'Fixtures'">
            Fixtures
          </NavLink>
        </li>

        <li>
          <NavLink href="/simulations" :active="$page.component === 'Simulations'">
            Fixtures
          </NavLink>
        </li>

        <!-- <li>
          <NavLink href="/logout" method="post" as="button">
            Log Out
          </NavLink>
        </li> -->
      </ul>
    </nav>
</template>

<script>
  import NavLink from "./NavLink.vue";

  export default {
    components: { NavLink },
  };
</script>
