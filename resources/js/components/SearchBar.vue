<template>
  <div class="search-bar-container">
    <div class="search-bar search-bar__hidden">
      <div class="search-bar__actions">
        <input class="search-bar__input" placeholder="Search" type="search" v-model="searchTerm">
        <router-link class="search__submit" tag="button" :to="{name: 'search', query: {term: this.searchTerm}}">Search</router-link>
      </div>
      <div class="search-bar__options">
        <div class="search-bar__option">
          <span class="tag">or show me everything</span>
        </div>
        <div class="search-bar__option">
          or try <span class="tag">Art</span> <span class="tag">Social justice</span> <span class="tag">Los Angeles</span>
          <span class="tag">Female directors</span> <span class="tag">Hollywood</span> <span class="tag">Poetry</span>
        </div>
      </div>
    </div>
    <span class="search__submit" @click="showSearch"></span>
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
