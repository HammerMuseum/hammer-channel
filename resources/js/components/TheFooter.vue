<template>
  <footer
    class="footer"
  >
    <div class="footer__inner">
      <span class="visually-hidden">Information about the archive</span>
      <h2 class="heading heading--footer">
        Welcome to Hammer ON
      </h2>
      <div class="footer__body">
        <div class="footer__actions">
          <a
            href="https://hammer.ucla.edu/sign-up-for-email-from-the-hammer"
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
              <use xlink:href="/images/sprite.svg#sprite-calendar" />
            </svg>
          </a>
        </div>
        <div class="footer__info">
          <p>Hammer ON is the video archive of the Hammer Museum. The archive was made possible thanks to a grant from the Mellon Foundation.</p>
          <p>If you would like to use any of the footage for broadcast, please contact example@example.org</p>
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
</template>

<script>
import SvgIcon from './SvgIcon.vue';

export default {
  name: 'TheFooter',
  components: {
    SvgIcon,
  },
  methods: {
    close() {
      this.$emit('close');
    },
  },
};
</script>
