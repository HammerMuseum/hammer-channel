<template>
  <div
    :class="[
      'container container--fixed',
      'container--bottom',
      'footer-container',
      {'footer--open': isFooterActive}
    ]"
    @click.self="toggleFooterActive"
  >
    <div class="footer-toggle__wrapper">
      <div class="footer-toggle">
        <button
          :class="['button', 'button--icon']"
          @click.stop="toggleFooterActive"
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
    </div>
    <FocusTrap
      :active="isFooterActive"
      :escape-deactivates="false"
    >
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


          <div class="footer__inner">
            <h2 class="heading heading--footer">
              Welcome to Hammer ON
            </h2>
            <div class="footer__body">
              <div :class="['footer__actions', { 'footer__actions--column': layout}]">
                <a
                  href="https://hammer.ucla.edu/programs-events"
                  target="_blank"
                  class="link"
                >
                  Sign up to our newsletters
                  <svg class="icon">
                    <use xlink:href="/images/sprite.svg#sprite-envelope" />
                  </svg>
                </a>
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
            <button
              :class="['footer__close-button', 'button', 'button--icon']"
              @click="toggleFooterActive"
            >
              <svg
                class="icon icon--close-pink"
              >
                <use xlink:href="/images/sprite.svg#sprite-close-pink" />
              </svg>
              <span class="icon-text visually-hidden">Close the footer menu</span>
            </button>
          </div>
        </footer>
      </transition>
    </FocusTrap>
  </div>
</template>

<script>
import { FocusTrap } from 'focus-trap-vue';
import { store, mutations } from '../store';

export default {
  name: 'TheFooter',
  components: {
    FocusTrap,
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
    isBodyOverflowActive() {
      return store.bodyOverflow;
    },
  },
  watch: {
    isFooterActive(active) {
      if (!active) {
        window.removeEventListener('keyup', this.onEscapeKeyUp);
      } else {
        window.addEventListener('keyup', this.onEscapeKeyUp);
      }
    },
  },
  methods: {
    onEscapeKeyUp(event) {
      if (event.which === 27) {
        this.toggleFooterActive();
      }
    },
    toggleFooterActive: mutations.toggleFooterActive,
  },
};
</script>

<style>
.slide-up-enter-active,
.slide-up-leave-active {
  transition: transform 0.8s cubic-bezier(0.19, 1, 0.22, 1);
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
