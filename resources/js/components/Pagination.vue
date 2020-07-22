<template>
  <nav
    v-if="totalPages"
    class="pagination"
    aria-label="Search results pagination"
  >
    <ul class="pagination__list">
      <li
        class="pagination__list-item"
      >
        <template v-if="prevPageNumber">
          <RouterLink
            class="link link--text"
            :aria-label="`Go to previous page of results`"
            :to="{
              name: 'search',
              query: {
                ...$route.query,
                ...{ page: prevPageNumber }
              }
            }"
          >
            Previous
          </RouterLink>
        </template>
        <template v-else>
          <span
            aria-hidden="true"
            class="link link--text link--disabled"
          >Previous</span>
        </template>
      </li>

      <li
        v-for="i in totalPages"
        :key="i"
        :class="['pagination__list-item', {['pagination__list-item--current link--disabled']: i === currentPage}]"
      >
        <RouterLink
          class="link link--text"
          :aria-label="i === currentPage ? `Current page. Page ${i}` : `Go to page ${i}`"
          :aria-current="i === currentPage ? true : false"
          :to="{
            name: 'search',
            query: {
              ...$route.query,
              ...{ page: i }
            }
          }"
        >
          {{ i }}
        </RouterLink>
      </li>

      <li
        class="pagination__list-item"
      >
        <template v-if="nextPageNumber">
          <RouterLink
            class="link link--text"
            :aria-label="`Go to next page of results`"
            :to="{
              name: 'search',
              query: {
                ...$route.query,
                ...{ page: nextPageNumber }
              }
            }"
          >
            Next
          </RouterLink>
        </template>
        <template v-else>
          <span
            aria-hidden="true"
            class="link link--text link--disabled"
          >Next</span>
        </template>
      </li>
    </ul>
  </nav>
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
    totalPages: {
      type: Number,
      default: 0,
    },
    currentPage: {
      type: Number,
      default: 0,
    },
    pagerQuery: {
      type: Number,
      default: 0,
    },
  },
  computed: {
    prevPageNumber() {
      if ((this.currentPage - 1) > 0) {
        return this.currentPage - 1;
      }
      return false;
    },
    nextPageNumber() {
      if ((this.currentPage + 1) <= this.totalPages) {
        return this.currentPage + 1;
      }
      return false;
    },
  },
};
</script>
