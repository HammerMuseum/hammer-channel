<template>
  <div class="vjs-hd">
    <video
      ref="videoPlayer"
      class="video-js hammer-video-player vjs-default-skin"
    >
      <track
        v-for="track in trackList"
        :key="track.id"
        :kind="track.kind"
        :label="track.label"
        :src="track.src"
        :srcLang="track.srcLang"
        :default="track.default"
      >
    </video>
    <div class="video-info video-info--title">
      <div class="title">
        <h1>{{ title }}</h1>
      </div>
      <div class="keywords">
        <ul>
          <li
            v-for="item in keywords"
            :key="item.id"
          >
            {{ item }}
          </li>
        </ul>
      </div>
    </div>
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
    keywords: Array,
    options: {
      type: Object,
      default() {
        return {};
      },
    },
    trackList: {
      type: Array,
      default() {
        return [];
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
        for (let i = 0; i < events.length; i++) {
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
