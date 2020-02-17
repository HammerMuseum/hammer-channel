<template>
  <div class="video-player-container vjs-hd">
    <video
      ref="videoPlayer"
      class="video-js video-player vjs-default-skin"
    >
      <p class="vjs-no-js">
        To view this video please enable JavaScript, and consider upgrading to a
        web browser that
        <a
          href="https://videojs.com/html5-video-support/"
          target="_blank"
        >
          supports HTML5 video
        </a>
      </p>
    </video>
  </div>
</template>

<script>
import videojs from 'video.js';

export default {
  name: 'VideoPlayer',
  props: {
    title: {
      type: String,
      default() {
        return '';
      },
    },
    videoUrl: {
      type: String,
      default() {
        return '';
      },
    },
    options: {
      type: Object,
      default() {
        return {};
      },
    },
    timecode: {
      type: Number,
      default() {
        return 0;
      },
    },
    track: {
      type: Object,
      default() {
        return {};
      },
    },
  },
  data() {
    return {
      player: null,
    };
  },
  watch: {
    options() {
      this.initVideoPlayer();
    },
    timecode(val) {
      this.player.currentTime(val);
      this.player.play();
    },
    videoUrl(val) {
      this.updatePlayerSrc(val);
    },
  },
  beforeDestroy() {
    if (this.player) {
      this.player.dispose();
    }
  },
  methods: {
    initVideoPlayer() {
      const DEFAULT_EVENTS = [
        'loadeddata',
        'canplay',
        'canplaythrough',
        'play',
        'pause',
        'waiting',
        'playing',
        'ended',
        'error',
      ];

      const self = this;

      this.player = videojs(this.$refs.videoPlayer, this.options, function () {
        // events
        const events = DEFAULT_EVENTS;
        // watch events
        const onEdEvents = {};
        for (let i = 0; i < events.length; i += 1) {
          if (typeof events[i] === 'string' && onEdEvents[events[i]] === undefined) {
            ((event) => {
              onEdEvents[event] = null;
              this.on(event, () => {
                self.$emit(event, self.player);
              });
            })(events[i]);
          }
        }

        this.on('error', function () {
          if (this.error().code === 2) {
            self.$emit('playbackerror');
          }
        });

        this.on('timeupdate', function () {
          self.$emit('timeupdate', this.currentTime());
        });

        self.$emit('ready', this);
      });

      this.player.ready(function () {
        self.player.addRemoteTextTrack(self.track);
      });

      // Setup overlay content. Move up to parent?
      const overlayContent = `<p>${this.title}</p>`;
      this.player.overlay({
        overlays: [{
          start: 'pause',
          class: 'hammer-video-overlay',
          content: overlayContent,
          end() {
            if (self.player.controlBar.hasClass('vjs-user-inactive')) {
              $('.vjs-overlay').removeClass('vjs-user-inactive');
            }
          },
          align: 'top',
        }],
      });
      $('.vjs-overlay').addClass('vjs-control-bar');
    },
    updatePlayerSrc(val) {
      const time = this.player.currentTime();
      const self = this;
      let initdone = false;

      this.player.off('ready');
      this.player.src({
        type: 'video/mp4',
        src: val,
      });

      // wait for video metadata to load, then set time.
      this.player.on('loadedmetadata', () => {
        self.player.currentTime(time);
      });

      // iPhone/iPad need to play first, then set the time
      // events: https://www.w3.org/TR/html5/embedded-content-0.html#mediaevents
      this.player.on('canplaythrough', () => {
        if (!initdone) {
          self.player.currentTime(time);
          initdone = true;
        }
      });
    },
  },
};
</script>

