import Vue from 'vue';

export const store = Vue.observable({
  searchActive: false,
  searchTerm: '',
});

export const mutations = {
  toggleSearchActive() {
    store.searchActive = !store.searchActive;
  },
  setSearchTerm(term) {
    store.searchTerm = term;
  },
};
