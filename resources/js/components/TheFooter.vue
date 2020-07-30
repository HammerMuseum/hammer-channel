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
            <BaseIcon
              width="36"
              height="36"
              view-box="0 0 48 36"
              icon-name="footer-signup"
              title="Sign up to our newsletters"
            >
              <EnvelopeIcon />
            </BaseIcon>
          </a>
          <a
            href="https://hammer.ucla.edu/programs-events"
            target="_blank"
            class="link"
          >
            Talks happening soon
            <BaseIcon
              width="39"
              height="32"
              view-box="0 0 39 32"
              icon-name="events-calendar"
              title="Find talks happening soon"
            >
              <CalendarIcon />
            </BaseIcon>
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
export default {
  name: 'TheFooter',
  methods: {
    close() {
      this.$emit('close');
    },
  },
};
</script>
