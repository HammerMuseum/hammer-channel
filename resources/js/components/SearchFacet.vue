<template>
  <div class="search-facet__list">
    <div
      v-for="option in facet.items"
      :key="option.key"
      :class="{'search-facet__item': true, 'search-facet__item--active': isActive(getValue(option, facet.type))}"
    >
      <a
        :href="`/search?${query(facet.id, getValue(option, facet.type))}`"
        class="search-facet__item-link"
        @click.prevent="handleClick($event, facet.id, getValue(option, facet.type))"
      >
        <span
          v-if="!isActive(getValue(option, facet.type))"
          class="search-facet__item-text"
        >{{ getValue(option, facet.type) }}</span>
        <span
          v-else
          class="search-facet__item-text"
        >{{ getValue(option, facet.type) }}
          <button
            class="button button--icon search-facet__item-remove"
            aria-label="Remove selection"
          >
            <svg class="icon icon--close">
              <use xlink:href="/images/sprite.svg#sprite-close" />
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
  name: 'SearchFacet',
  props: {
    facet: {
      type: Object,
      required: true,
    },
    activeFacets: {
      type: Array,
      required: true,
    },
  },
  methods: {
    handleClick(e, key, value) {
      const target = e.currentTarget;
      this.$router.push(`${target.pathname}${target.search}`);
      this.$emit('change', key, value);
    },
    query(key, value) {
      const param = `${key}=${encodeURIComponent(value)}`;
      let qs = stringifyQuery(this.$route.query);
      const r = this.$route.query;
      // If the query string contains pagination info, remove it
      if (r['page']) {
        qs = qs.replace(`page=${r['page']}`, '');
      }
      // If the querystring contains the current facet, genearate a new one without it.
      if (r[key] && (r[key] === value || r[key].includes(value))) {
        const processed = qs.replace(param, '');
        return processed;
      }
      return qs === '' ? `${qs}${param}` : `${qs}&${param}`;
    },
    isActive(value) {
      return this.activeFacets && this.activeFacets.includes(value);
    },
    getValue(item, type) {
      return type === 'date' ? new Date(item.key_as_string).getFullYear() : item.key;
    },
  },
};
</script>
