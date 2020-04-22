<template>
  <div class="pagination">
    <ul class="pagination__list">
      <li
        v-for="(item, label) in paginationLinks"
        :key="label"
        class="pagination__list-item"
      >
        <RouterLink
          class="link link--text"
          :to="{ name: 'search', query: { ...$route.query, ...{ page: item.split('=').pop() } } }"
        >
          {{ label | capitalize }}
        </RouterLink>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'Pagination',
  filters: {
    capitalize(value) {
      if (!value) return '';
      return value.toString().charAt(0).toUpperCase() + value.slice(1);
    },
  },
  props: {
    paginationLinks: {
      type: Object,
      default() {
        return {};
      },
    },
  },
};
</script>

<style>
.pagination {
  margin: 40px 0;
}

.pagination__list {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  width: 100%;
  padding-top: 40px;
}
</style>
