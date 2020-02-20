<template>
  <div class="footer-wrapper">
    <div class="toggle-footer">
      <button class="toggle-footer__button" @click="toggleFooter()">?</button>
    </div>
    <div class="footer" v-show="showFooter">
      <VTabs class="styled">
        <template slot="About">
          <h2>About</h2>
          "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
        </template>

        <template slot="Terms and conditions">
          <h2>Terms and conditions</h2>
          "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
        </template>

        <template slot="Privacy policy">
          <h2>Privacy policy</h2>
          "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
        </template>

        <template slot="Email sign up">
          <h2>Email sign up</h2>
        </template>

        <template slot="Visit the main Hammer site">
          <a href="https://hammer.ucla.edu">https://hammer.ucla.edu</a>
        </template>
      </VTabs>
    </div>
  </div>
</template>

<script>
  import { VTabs } from "vuetensils";
  export default {
    components: {
      VTabs,
    },
    mounted() {
      console.log('Component mounted.');
    },
    data() {
      return {
        showFooter: false
      }
    },
    methods: {
      toggleFooter() {
        if (!this.showFooter) {
          this.showFooter = true;
        } else {
          this.showFooter = false;
        }
      }
    }
  };
</script>
