<template>
  <FocusTrap
    :active="true"
    :escape-deactivates="false"
  >
    <div
      class="search-facet__list"
    >
      <div
        v-for="option in facet.items"
        ref="listChildren"
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
              :aria-label="`Remove ${getValue(option, facet.type)} filter from selection`"
            >
              <svg class="icon icon--close">
                <use xlink:href="/images/sprite.svg#sprite-close" />
              </svg>
            </button>
          </span>
        </a>
      </div>
    </div>
  </FocusTrap>
</template>

<script>
import { FocusTrap } from 'focus-trap-vue';
import stringifyQuery from '../mixins/stringifyQuery';

export default {
  name: 'SearchFacet',
  components: {
    FocusTrap,
  },
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
  mounted() {
    this.$refs.listChildren[0].firstChild.focus();
  },
  methods: {
    handleClick(e, key, value) {
      const target = e.currentTarget;
      this.$router.push(`${target.pathname}${target.search}`);
      this.$emit('change', key, value);
    },
    query(key, value) {
      const param = `${key}=${encodeURIComponent(value)}`;
      let queryStr = stringifyQuery(this.$route.query);
      const routeQuery = this.$route.query;

      // If the query string contains pagination info, remove it
      if (routeQuery.page) {
        queryStr = queryStr.replace(`page=${routeQuery.page}`, '');
      }
      // If the querystring contains the current facet, genearate a new one without it.
      if (routeQuery[key] && (routeQuery[key] === value || routeQuery[key].includes(value))) {
        const processed = queryStr.replace(param, '');
        return processed;
      }
      return queryStr === '' ? `${queryStr}${param}` : `${queryStr}&${param}`;
    },
    isActive(value) {
      return this.activeFacets && this.activeFacets.includes(String(value));
    },
    getValue(item, type) {
      return type === 'date' ? new Date(item.key_as_string).getFullYear() : item.key;
    },
  },
};
</script>
