<template>
  <div
    class="search-page__current-search"
  >
    <div
      v-for="item in currentFacets"
      :key="item.value"
      :class="{'search-facet__item': true, 'search-facet__item--active': true}"
    >
      <a
        :href="`/search?${query(item.key, item.value)}`"
        class="link link--text link--text-and-button"
        @click.prevent="handleClick($event)"
      >
        <span
          :title="`Remove ${item.value} filter from search`"
        ><strong>{{ item.value }}</strong>
          <button
            class="button button--icon search-facet__item-remove"
          >
            <svg class="icon icon--close-pink">
              <use xlink:href="/images/sprite.svg#sprite-close-pink" />
            </svg>
          </button>
        </span>
      </a>
    </div>
  </div>
</template>

<script>
import stringifyQuery from '../mixins/stringifyQuery';

export default {
  name: 'CurrentSearch',
  computed: {
    filteredQuery() {
      const query = this.$route.query;
      const allowed = ['topics', 'tags', 'speakers', 'dates', 'playlists'];
      return Object.keys(query)
        .filter((key) => allowed.includes(key))
        .reduce((obj, key) => {
          obj[key] = query[key];
          return obj;
        }, {});
    },
    currentFacets() {
      const active = [];
      const query = this.filteredQuery;
      Object.keys(query).forEach((key) => {
        const value = query[key];
        if (value instanceof Array) {
          value.forEach((item) => {
            active.push({
              key,
              value: item,
            });
          });
        } else {
          active.push({
            key,
            value,
          });
        }
      });

      return [].concat(...active);
    },
  },
  methods: {
    handleClick(e) {
      const target = e.currentTarget;
      this.$router.push(`${target.pathname}${target.search}`);
    },
    query(key, value) {
      const param = `${key}=${encodeURIComponent(value)}`;
      let qs = stringifyQuery(this.$route.query);
      const r = this.$route.query;

      // If the query string contains pagination info, remove it
      if (r.page) {
        qs = qs.replace(`page=${r.page}`, '');
      }
      // If the querystring contains the current facet, genearate a new one without it.
      if (r[key] && (r[key] === value || r[key].includes(value))) {
        let processed = qs.replace(param, '');
        if (processed.slice(-1) === '&') {
          processed = processed.slice(0, -1);
        }
        return processed;
      }
      return qs === '' ? `${qs}${param}` : `${qs}&${param}`;
    },
    getValue(item, type) {
      return type === 'date' ? new Date(item.key_as_string).getFullYear() : item.key;
    },
  },
};
</script>
