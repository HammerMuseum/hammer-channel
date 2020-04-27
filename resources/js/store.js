import Vue from 'vue';

export const store = Vue.observable({
  searchActive: false,
  searchTerm: '',
  transcriptInit: false,
});

export const mutations = {
  toggleSearchActive() {
    store.searchActive = !store.searchActive;
  },
  setSearchTerm(term) {
    store.searchTerm = term;
  },
  toggleTranscriptInit() {
    store.transcriptInit = !store.transcriptInit;
  },
};
