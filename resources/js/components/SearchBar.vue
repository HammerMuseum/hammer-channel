<template>
  <div class="search-bar-container">
    <span class="search__submit" @click="showSearch"></span>
    <div class="search-bar">
      <input class="search-bar__input" placeholder="Search" type="search" v-model="searchTerm">
      <router-link class="search__submit" tag="button" :to="{name: 'search', query: {term: this.searchTerm}}">Search</router-link>
      <div class="search-bar__options">
        <div class="search-bar__option">
          or show me everything
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        searchTerm: ''
      }
    },
    methods: {
      showSearch() {
        console.log('clicked');
        let searchBar = document.querySelector('.search-bar');
        if (searchBar.classList.contains('search-bar__hidden')) {
          console.log('has class');
          searchBar.classList.remove('search-bar__hidden');
        } else {
          searchBar.classList.add('search-bar__hidden');
        }
      }
    },
    mounted() {
      console.log('Component mounted.');
    }
  };
</script>
