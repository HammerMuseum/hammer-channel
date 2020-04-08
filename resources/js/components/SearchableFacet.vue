<template>
  <div class="searchable-facet">
    <div class="searchable-facet__search-input">
      <label
        class="visually-hidden"
        for="facet-search"
      >Type to filter list...</label>
      <input
        v-model="facetSearch"
        type="text"
        name="facet-search"
        class="search__input"
        aria-label="Search to filter list"
        placeholder="Search to filter list"
      >
    </div>
    <div
      v-if="noResults"
      class="no-results"
    >
      No results found
    </div>

    <div
      v-for="facet in filteredItems"
      v-else
      :key="facet.label"
      class="search-facet__list"
    >
      <h4 class="visually-hidden search-facet__list-label">
        {{ facet.label }}
      </h4>
      <div
        v-for="item in facet.items"
        :key="item.key"
        :class="{'search-facet__item': true, 'search-facet__item--active': isActive(getValue(item, facet.type))}"
      >
        <a
          :href="`/search?${query(facet.id, getValue(item, facet.type))}`"
          class="search-facet__item-link"
          @click.prevent="handleClick($event, facet.id, getValue(item, facet.type))"
        >
          <template v-if="!isActive(getValue(item, facet.type))">
            <span
              class="search-facet__item-text"
            >{{ getValue(item, facet.type) }}</span>
          </template>
          <template v-else>
            <span
              class="search-facet__item-text"
            >{{ getValue(item, facet.type) }}</span>
            <button
              class="button button--icon search-facet__item-remove"
              aria-label="Remove selection"
            >
              <svg class="icon icon--close">
                <use xlink:href="/images/sprite.svg#sprite-close" />
              </svg>
            </button>
          </template>
        </a>
      </div>
    </div>
  </div>
  </div>
</template>

<script>
import stringifyQuery from '../mixins/stringifyQuery';

export default {
  props: {
    facetList: Array,
    panelName: String,
    activeFacets: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      facetSearch: '',
      noResults: false,
    };
  },
  computed: {
    filteredItems() {
      const filteredOptions = [];
      const self = this;
      if (this.facetSearch !== '') {
        for (let i = 0; i < this.facetList.length; i += 1) {
          const filteredItemsArray = self.facetList[i].items.filter(function (itemValue) {
            return itemValue.key.toLowerCase().includes(self.facetSearch.toLowerCase());
          });
          const newFacet = {
            id: self.facetList[i].id,
            items: filteredItemsArray,
            label: self.facetList[i].label,
            type: self.facetList[i].type,
          };
          filteredOptions.push(newFacet);
        }
        return filteredOptions;
      }
      return this.facetList;
    },
  },
  watch: {
    filteredItems(value) {
      for (let i = 0; i < value.length; i += 1) {
        if (value[i].items.length < 1) {
          this.noResults = true;
        } else {
          this.noResults = false;
        }
      }
    },
  },
  methods: {
    handleClick(e, key, value) {
      const target = e.currentTarget;
      this.$router.push(`${target.pathname}${target.search}`);
      this.$emit('change', key, value);
      this.$parent.toggleFacetPanel(this.panelName);
    },
    query(key, value) {
      const param = `${key}=${encodeURIComponent(value)}`;
      let qs = stringifyQuery(this.$route.query);
      const r = this.$route.query;
      // If the query string contains pagination info, remove it
      if (r.page) {
        qs = qs.replace(`page=${r.page}`, '');
      }
      // If the querystring contains the current facet, generate a new one without it.
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
