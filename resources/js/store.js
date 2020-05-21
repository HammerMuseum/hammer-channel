import Vue from 'vue';

export const store = Vue.observable({
  searchOverlayActive: false,
  facetOverlayActive: false,
  footerActive: false,
  searchTerm: '',
  transcriptInit: false,
});

export const mutations = {
  toggleFooterActive() {
    store.footerActive = !store.footerActive;
  },
  toggleFacetOverlayActive() {
    store.facetOverlayActive = !store.facetOverlayActive;
  },
  toggleSearchOverlayActive() {
    store.searchOverlayActive = !store.searchOverlayActive;
  },
  setSearchTerm(term) {
    store.searchTerm = term;
  },
  toggleTranscriptInit() {
    store.transcriptInit = !store.transcriptInit;
  },
};
