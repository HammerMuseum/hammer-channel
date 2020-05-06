<template>
  <div class="video-player__wrapper">
    <div class="video-player__wrapper-inner">
      <div class="video-player__container vjs-hd">
        <video
          ref="videoPlayer"
          class="video-js video-player vjs-default-skin"
          :poster="poster"
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
    </div>
  </div>
</template>

<script>
import videojs from 'video.js';
import overlay from 'videojs-overlay';

window.VIDEOJS_NO_BASE_THEME = true;

export default {
  name: 'VideoPlayer',
  props: {
    title: {
      type: String,
      default() {
        return '';
      },
    },
    poster: {
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
    duration: {
      type: String,
      default() {
        return '';
      },
    },
  },
  data() {
    return {
      player: null,
      endtime: 0,
      starttime: 0,
      clipSliderSet: false,
    };
  },
  computed: {
    isClip() {
      return !!((this.$route.query.start || this.$route.query.end));
    },
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
          if (self.endtime > 0 && this.currentTime() >= self.endtime) {
            self.player.pause();
          }
        });

        self.$emit('ready', this);
      });

      this.player.ready(function () {
        self.player.addRemoteTextTrack(self.track, true);
      });

      // Setup title overlay content.
      this.player.overlay({
        overlays: [{
          start: 'fullscreenchange',
          end: 'fullscreenchange',
          class: 'hammer-video-overlay',
          content: `<p>${this.title}</p>`,
          align: 'top-left',
        }],
      });
    },
    setSliderAppearance() {
      // const sliderBar = document.querySelector('.vjs-play-progress');
      // const sliderWidth = sliderBar.style.width;
      const controlBar = this.player.getChild('ControlBar');
      const sliderWidth = controlBar.getChild('ProgressControl').currentWidth();

      const progressHolder = document.querySelector('.vjs-progress-holder');
      const clipDuration = this.getClipDuration();
      let clipPercentage = 100;
      if (clipDuration !== this.player.duration()) {
        clipPercentage = (clipDuration / this.player.duration()) * 100;
      }

      // If the custom progress bar already exists, remove it
      const hammerProgressBar = document.querySelector('.hammer-progress-bar');
      if (hammerProgressBar != null) {
        hammerProgressBar.parentNode.removeChild(hammerProgressBar);
      }

      // Insert the custom progress bar
      const newProgressBar = document.createElement('div');
      newProgressBar.classList.add('hammer-progress-bar');
      newProgressBar.style.width = `${clipPercentage}%`;
      newProgressBar.style.left = sliderWidth;
      progressHolder.appendChild(newProgressBar);
      this.clipSliderSet = true;
    },
    getClipDuration() {
      if (this.endtime === 0) {
        return this.player.duration() - this.starttime;
      }
      return this.endtime - this.starttime;
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

      this.queryParams = this.$route.query;
      // wait for video metadata to load, then set time.
      this.player.on('loadedmetadata', () => {
        if (this.isClip) {
          self.setClip(self.queryParams);
        } else {
          self.player.currentTime(time);
        }
      });

      this.player.on('seeked', () => {
        if ((self.queryParams.start || self.queryParams.end) && !self.clipSliderSet) {
          self.setSliderAppearance();
        }
      });

      // iPhone/iPad need to play first, then set the time
      // events: https://www.w3.org/TR/html5/embedded-content-0.html#mediaevents
      this.player.on('canplaythrough', () => {
        if (!initdone) {
          if (!this.isClip) {
            self.player.currentTime(time);
          }
          initdone = true;
        }
      });
    },
    setClip(queryParams) {
      if (queryParams.start) {
        this.starttime = queryParams.start;
        this.player.currentTime(queryParams.start);
      }
      if (queryParams.end) {
        this.endtime = queryParams.end;
      }
    },
  },
};
</script>

<style>
.video-player__wrapper {
  min-width: 100%;
  margin: 0 -16px;
}

.video-player__wrapper-inner {
  position: relative;
  padding-top: calc((9 / 16) * 100%);
}

.video-player__container {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}

.video-player {
  width: 100%;
  height: 100%;
}

@media (min-width: 52.5em) {
  .video-player__wrapper {
    margin: 0;
  }
}
</style>
