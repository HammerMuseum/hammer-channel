<template>
  <footer
    class="footer"
  >
    <div class="footer__inner">
      <span class="visually-hidden">Information about the site</span>
      <h2 class="heading heading--footer">
        {{ copy.aboutHeading }}
      </h2>
      <p
        class="footer__intro"
        v-html="copy.aboutStrapline"
      />
      <div class="footer__body">
        <div class="footer__actions">
          <a
            href="https://hammer.ucla.edu/sign-up-for-email-from-the-hammer"
            target="_blank"
            class="link"
          >
            {{ copy.emailSignup }}
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
            {{ copy.calendarLink }}
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
          <p>
            We encourage the use and sharing of these videos for nonprofit educational purposes. Please share via links to this website or embed videos from the <a
              href="https://www.youtube.com/user/hammermuseum"
              target="_blank"
              rel="noopener noreferrer"
            >Hammer’s YouTube channel</a>.
          </p>
          <p>To use selections from these videos for any purpose, or request video files, please contact us at <a href="mailto:info@hammer.ucla.edu">info@hammer.ucla.edu</a>.</p>
          <p>Transcripts provided on this site are generated automatically, and are not 100% accurate. Please send suggestions for corrections to <a href="mailto:info@hammer.ucla.edu">info@hammer.ucla.edu</a>.</p>
          <p>
            This is an open source project. Source code and documentation is available on <a href="https://github.com/HammerMuseum">GitHub</a>. Contact the site’s developers at <a
              href="https://www.cogapp.com"
              target="_blank"
              rel="noopener noreferrer"
            >cogapp</a> for technical queries.
          </p>
          <p>Support for the development of Hammer Channel provided by The Andrew W. Mellon Foundation.</p>
        </div>
        <div class="footer__links">
          <a
            class="link link--pink"
            href="https://hammer.ucla.edu/privacy-policy"
            target="_blank"
            rel="noopener noreferrer"
          >Privacy Policy</a>
          <a
            class="link link--pink"
            href="https://hammer.ucla.edu/terms-of-use"
            target="_blank"
            rel="noopener noreferrer"
          >Terms of Use</a>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
import { store } from '../store';

export default {
  name: 'TheFooter',
  computed: {
    copy() {
      return store.copy;
    },
  },
  methods: {
    close() {
      this.$emit('close');
    },
  },
};
</script>
