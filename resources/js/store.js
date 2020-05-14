import Vue from 'vue';

export const store = Vue.observable({
  searchActive: false,
  footerActive: false,
  searchTerm: '',
  transcriptInit: false,
});

export const mutations = {
  toggleFooterActive() {
    store.footerActive = !store.footerActive;
  },
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
