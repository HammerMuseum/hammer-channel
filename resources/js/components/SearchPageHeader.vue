<template>
  <div class="search-page__header">
    <h1 class="page-title">
      Search results
    </h1>
    <slot />
    <div class="search-page__sorting">
      <label>Order by</label>
      <RouterLink
        :to="{
          name: 'search',
          query: {
            ...$route.query,
            ...{ sort: 'date_recorded', order: 'asc' }
          }
        }"
      >
        Date (ASC)
      </RouterLink>
      <RouterLink
        :to="{
          name: 'search',
          query: {
            ...$route.query,
            ...{ sort: 'date_recorded', order: 'desc' }
          }
        }"
      >
        Date (DESC)
      </RouterLink>
    </div>
  </div>
</template>
