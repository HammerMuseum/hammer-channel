<template>
  <div
    v-on-clickaway="away"
    class="footer-wrapper"
  >
    <div class="toggle-footer">
      <button
        class="toggle-footer__button"
        @click="toggleFooter()"
      >
        ?
      </button>
    </div>
    <div
      v-show="showFooter"
      class="footer"
    >
      <VTabs class="styled">
        <template slot="About">
          <h2>About</h2>
          <div class="about">
            <p class="footer__text">
              In the Hammer’s video archive, people will discover ideas that will illuminate their lives in new ways.
            </p>
            <p class="footer__text">
              From the Hammer’s robust archive of conversations and programs featuring some of the greatest artists and activists of our time, visitors will be empowered to share those ideas, build knowledge, and open new lines of dialogue in the pursuit of a more just world.
            </p>
          </div>
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
          <div class="email-signup">
            <VInput
              v-model="email"
              type="email"
              name="email"
              label="Enter your email address:"
              :class="`email-signup__item`"
            />
            <button
              class="email-signup__button email-signup__item"
              @click="submitNewsletterForm()"
            >
              Submit
            </button>
            <span class="email-signup__result email-signup__item" />
            <div class="email-signup__info">
              Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            </div>
          </div>
        </template>

        <template slot="Visit the main Hammer site">
          <a href="https://hammer.ucla.edu">https://hammer.ucla.edu</a>
        </template>
      </VTabs>
      <div class="footer-logo">
        <img
          class="footer-logo__hammer"
          src="/images/logo-hammer.png"
        >
        <img
          class="footer-logo__mellon"
          src="/images/logo-header.svg"
        >
      </div>
    </div>
  </div>
</template>

<script>
import { VTabs, VInput } from 'vuetensils';

import { directive as onClickaway } from 'vue-clickaway';

export default {
  directives: {
    onClickaway,
  },
  components: {
    VTabs,
    VInput,
  },
  data() {
    return {
      showFooter: false,
    };
  },
  methods: {
    toggleFooter() {
      if (!this.showFooter) {
        this.showFooter = true;
        return true;
      }
      this.showFooter = false;
      return false;
    },
    away() {
      if (this.showFooter) {
        this.showFooter = false;
      }
    },
    submitNewsletterForm() {
      const emailAddress = document.querySelector('[name=email]');
      const result = document.querySelector('.email-signup__result');
      if (emailAddress.value === '') {
        result.innerHTML = 'Please enter a valid email address.';
        return false;
      }
      axios
        .get(`/submit?email=${emailAddress.value}`)
        .then((response) => {
          result.innerHTML = response.data.message;
          if (response.data.success) {
            emailAddress.value = '';
          }
        });
    },
  },
};
</script>
