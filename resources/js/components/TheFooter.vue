<template>
  <div
    v-on-clickaway="away"
    :class="['footer-wrapper', {'footer--open': footerIsActive} ]"
  >
    <div class="footer-toggle">
      <button
        :class="['button', 'button--icon']"
        @click="toggleFooterIsActive"
      >
        <svg
          title="Toggle footer menu visibility"
          class="icon"
        >
          <use xlink:href="/images/sprite.svg#sprite-footer-menu" />
        </svg>
        <span class="icon-text visually-hidden">Toggle footer menu visibility</span>
      </button>
    </div>
    <transition name="slide-up">
      <footer
        v-show="footerIsActive"
        :class="['footer']"
      >
        <VTabs class="styled">
          <template slot="About">
            <h2>About</h2>
            <div class="about">
              <p class="footer__text">
                Lorem ipsum dolor sit amet
              </p>
              <p class="footer__text">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                nisi ut aliquip ex ea commodo consequat. Duis aute irure
                dolor in reprehenderit in voluptate velit esse cillum
                dolore eu fugiat nulla pariatur. Excepteur sint occaecat
                cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
              </p>
            </div>
          </template>

          <template slot="Terms and conditions">
            <h2>Terms and conditions</h2>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit,
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
            nisi ut aliquip ex ea commodo consequat.
            Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur.
            Excepteur sint occaecat cupidatat non proident, sunt in culpa
            qui officia deserunt mollit anim id est laborum.
          </template>

          <template slot="Privacy policy">
            <h2>Privacy policy</h2>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit,
            sed do eiusmod tempor incididunt ut labore et dolore magna
            aliqua. Ut enim ad minim veniam, quis nostrud exercitation
            ullamco laboris nisi ut aliquip ex ea commodo consequat.
            Duis aute irure dolor in reprehenderit in voluptate velit
            esse cillum dolore eu fugiat nulla pariatur. Excepteur sint
            occaecat cupidatat non proident, sunt in culpa qui officia
            deserunt mollit anim id est laborum.
          </template>

          <template slot="Email sign up">
            <div class="email-signup">
              <VInput
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
                Duis aute irure dolor in reprehenderit in voluptate velit
                esse cillum dolore eu fugiat nulla pariatur.
                Excepteur sint occaecat cupidatat non proident, sunt in
                culpa qui officia deserunt mollit anim id est laborum.
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
            src="/images/logo-mellon.png"
          >
        </div>
      </footer>
    </transition>
  </div>
</template>

<script>
import axios from 'axios';
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
      footerIsActive: false,
    };
  },
  methods: {
    toggleFooterIsActive() {
      this.footerIsActive = !this.footerIsActive;
    },
    away() {
      this.footerIsActive = false;
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

<style>
.slide-up-enter-active,
.slide-up-leave-active {
  transition: transform 1.2s cubic-bezier(.19,1,.22,1);
}

.slide-up-enter,
.slide-up-leave-to {
  transform: translate3d(0, 100%, 0);
}

.slide-up-leave,
.slide-up-enter-to {
  transform: translate3d(0, 0, 0);
}
</style>
