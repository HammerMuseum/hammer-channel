<template>
  <div class="video-js-responsive-container vjs-hd">
    <video
      ref="videoPlayer"
      class="video-js hammer-video-player vjs-default-skin"
    />
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

        // watch timeupdate
        this.on('timeupdate', function () {
          self.$emit('timeupdate', this.currentTime());
        });

        // player readied
        self.$emit('ready', this);
      });

      this.player.ready(function () {
        self.player.addRemoteTextTrack(self.track);
      });

      // Setup overlay content. Move up to parent?
      const overlayContent = `<p>${this.title}</p>`;
      this.player.overlay({
        overlays: [{
          start: 'loadedmetadata',
          class: 'hammer-video-overlay',
          content: overlayContent,
          end() {
            if (self.player.controlBar.hasClass('vjs-user-inactive')) {
              $('.vjs-overlay').addClass('vjs-user-inactive');
            }
          },
          align: 'top',
        }],
      });
      $('.vjs-overlay').addClass('vjs-control-bar');
    },
  },
};
</script>
