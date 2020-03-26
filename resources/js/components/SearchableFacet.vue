<template>
  <div class="facet">
    <div class="searchable-facet__filter">
      <label for="facet-search">Filter by: </label>
      <input type="search" name="facet-search" id="facet-search" v-model="facetSearch" />
    </div>
    <div v-if="noResults" class="no-results">No results found</div>
    <div v-if="!noResults" v-for="facet in filteredFacetList" class="searchable-facet">
      <span class="searchable-facet__label">
        {{ facet.label }}
      </span>
      <div class="searchable-facet__items">
        <div class="searchable-facet__item"
          v-for="item in facet.items"
         :class="{facets__item: true, 'facets__item--active': isActive(getValue(item, facet.type))}"
        >
          <a
            :href="`/search?${query(facet.id, getValue(item, facet.type))}`"
            class="facets__item-link"
            @click.prevent="handleClick($event, facet.id, getValue(item, facet.type))"
          >
            <span
              v-if="!isActive(getValue(item, facet.type))"
              class="facets__item-link-text"
            >{{ getValue(item, facet.type) }}</span>
            <span
              v-else
              class="facets__item-link-text"
            >{{ getValue(item, facet.type) }} (Remove selection)</span>
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
      }
    },
    watch: {
      filteredFacetList(value) {
        for (let i = 0; i < value.length; i++) {
          if (value[i].items.length < 1) {
            this.noResults = true;
          } else {
            this.noResults = false;
          }
        }
      }
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
        if (r['page']) {
          qs = qs.replace(`page=${r['page']}`, '');
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
    computed: {
      filteredFacetList: function() {
        let filteredOptions = [];
        let self = this;
        if (this.facetSearch !== '') {
          for (let i = 0; i < this.facetList.length; i++) {
            let filteredItemsArray = self.facetList[i].items.filter(function (itemValue) {
              return itemValue.key.toLowerCase().includes(self.facetSearch.toLowerCase());
            });
            let newFacet = {
              id: self.facetList[i].id,
              items: filteredItemsArray,
              label: self.facetList[i].label,
              type: self.facetList[i].type
            };
            filteredOptions.push(newFacet);
          }
          return filteredOptions;
        }
        return this.facetList;
      }
    },
  };
</script>
