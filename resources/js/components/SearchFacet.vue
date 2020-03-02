<template>
  <div>
    <p class="facet__label">
      {{ facet.label }}
    </p>
    <div
      v-for="option in facet.items"
      :key="option.key"
      :class="{facets__item: true, 'facets__item--active': isActive(getValue(option, facet.type))}"
    >
      <a
        :href="`/search?${query(facet.id, getValue(option, facet.type))}`"
        class="facets__item-link"
        @click.prevent="handleClick($event, facet.id, getValue(option, facet.type))"
      >
        <span
          v-if="!isActive(getValue(option, facet.type))"
          class="facets__item-link-text"
        >{{ getValue(option, facet.type) }} {{ `(${option.count})` }}</span>
        <span
          v-else
          class="facets__item-link-text"
        >{{ getValue(option, facet.type) }} (Remove selection)</span>
      </a>
    </div>
  </div>
</template>

<script>
import queryString from 'query-string';

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
      const qs = queryString.stringify(this.$route.query);
      const r = this.$route.query;
      // If the querystring contains the current facet, genearate a new one without it.
      if (r[key] && (r[key] === value || r[key].includes(value))) {
        const processed = qs.replace(param, '');
        return processed;
      }
      return qs === '' ? `${qs}${key}=${value}` : `${qs}&${key}=${value}`;
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

<style>
.facets__item {
  margin: 4px 0 6px 0;
}

.facets__item-link {
  display: inline-block;
  text-decoration: none;
  color: var(--body-text-color);
}

.facets__item-link:hover {
  display: inline-block;
  text-decoration: none;
}

.facets__item-link-text {
  border-bottom: 2px solid var(--highlight-color-primary);
}

.facets__item--active .facets__item-link {
  padding: 4px 0;
  color: white;
  background: #484848;
}

.facets__item--active .facets__item-link-text {
  border-bottom: none;
}

</style>
