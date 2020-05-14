<template>
  <div
    :class="[
      'container container--fixed',
      'container--bottom',
      'footer-container',
      {'footer--open': isFooterActive}
    ]"
    @click.stop="away($event)"
    @keyup.escape="away($event)"
  >
    <div class="footer-toggle">
      <button
        :class="['button', 'button--icon']"
        @click="toggleFooterActive"
      >
        <svg
          title="Toggle footer menu visibility"
          class="icon"
        >
          <use xlink:href="/images/sprite.svg#sprite-footer-menu" />
        </svg>
        <span class="icon-text visually-hidden">
          {{ isFooterActive ? 'Close' : 'Open' }} the footer menu
        </span>
      </button>
    </div>
    <transition name="slide-up">
      <footer
        v-show="isFooterActive"
        class="footer"
      >
        <button
          @click="layout = !layout"
        >
          Switch layout
        </button>

        <div
          class="footer__inner"
        >
          <button
            :class="['footer__close-button', 'button', 'button--icon']"
            @click.stop="toggleFooterActive"
          >
            <svg
              class="icon icon--close-pink"
            >
              <use xlink:href="/images/sprite.svg#sprite-close-pink" />
            </svg>
            <span class="icon-text visually-hidden">Close the footer menu</span>
          </button>

          <h2 class="heading heading--footer">
            Welcome to Hammer ON
          </h2>
          <div class="footer__body">
            <span class="footer__subheading">Sign Up for Email from the Hammer</span>
            <div :class="['footer__actions', { 'footer__actions--column': layout}]">
              <form
                class="form__input-wrapper form__input-wrapper--footer"
                action="https://hammer.ucla.edu/sign-up-for-email-from-the-hammer"
                method="get"
              >
                <VInput
                  ref="search"
                  label="Enter your email to sign up to our newsletters"
                  :classes="{ text: 'visually-hidden', input: 'form__input form__input--footer' }"
                  placeholder="Email address"
                  autocomplete="new-password"
                />
                <div class="form__submit-wrapper form__submit-wrapper--footer">
                  <button
                    :class="['form__submit', 'form__submit--footer', 'button', 'button--icon']"
                  >
                    <span class="icon-text">Go</span>
                    <svg
                      class="icon"
                    >
                      <use xlink:href="/images/sprite.svg#sprite-envelope" />
                    </svg>
                  </button>
                </div>
              </form>

              <a
                href="https://hammer.ucla.edu/programs-events"
                target="_blank"
                class="link"
              >
                Talks happening soon
                <svg
                  class="icon"
                >
                  <use xlink:href="/images/sprite.svg#sprite-next" />
                </svg>
              </a>
            </div>
            <div class="footer__info">
              <p>Hammer ON is the video archive of the Hammer Museum. The archive was made possible thanks to a grant from the Mellon Foundation.</p>
              <p>If you would like to use any of the footage for broadacst, please contact example@example.org</p>
            </div>
            <div class="footer__links">
              <a
                class="link link--pink"
                href="https://hammer.ucla.edu/privacy-policy"
              >Privacy Policy</a>
              <a
                class="link link--pink"
                href="https://hammer.ucla.edu/terms-of-use"
              >Terms of Use</a>
            </div>
          </div>
        </div>
      </footer>
    </transition>
  </div>
</template>

<script>
import { VInput } from 'vuetensils';
import { store, mutations } from '../store';

export default {
  components: {
    VInput,
  },
  data() {
    return {
      layout: true,
    };
  },
  computed: {
    isFooterActive() {
      return store.footerActive;
    },
  },
  methods: {
    away(event) {
      if (event.target !== event.currentTarget) {
        return;
      }
      this.toggleFooterActive();
    },
    toggleFooterActive: mutations.toggleFooterActive,
  },
};
</script>

<style>
.slide-up-enter-active,
.slide-up-leave-active {
  transition: transform 0.4s cubic-bezier(0.19, 1, 0.22, 1);
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
