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
      endtime: 0,
      starttime: 0
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
          if (self.endtime > 0 && this.currentTime() >= self.endtime) {
            self.player.pause();
          }
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
    setSliderAppearance() {
      let sliderBar = document.querySelector('.vjs-play-progress');
      let sliderInfo = sliderBar.getBoundingClientRect();
      let sliderWidth = sliderInfo.width;

      let progressHolder = document.querySelector('.vjs-progress-holder');
      let progressInfo = progressHolder.getBoundingClientRect();
      let progressWidth = progressInfo.width;

      let percentageWidth = sliderWidth / progressWidth * 100;
      //
      let clipDuration = this.endtime - this.starttime;
      let clipPercentage = clipDuration / this.player.duration() * 100;
      //
      this.player.controlBar.progressControl.seekBar.playProgressBar.updateDataAttr = function() {
        document.querySelector(".vjs-play-progress").style.width = clipPercentage + '%';
        // template.find(".vjs-play-progress").setAttribute('data-current-time', formatTime(template.currentTime, globalDuration));
      }
      let wrapperHtml = `<div class="hammer-progress-bar"></div>`;
      // progressHolder.innerHTML = progressHolder.innerHTML + wrapperHtml;
      // progressHolder.classList.add('hammer-progress-holder');
      // let hammerBar = document.querySelector('.hammer-progress-bar');
      // hammerBar.style.width = clipPercentage + '%';
      // hammerBar.style.left = sliderWidth + 'px';


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
        self.setClip(self.queryParams);
      });

      this.player.on('seeked', () => {
        if (self.queryParams.start || self.queryParams.end) {
          self.setSliderAppearance();
        }
      });

      // iPhone/iPad need to play first, then set the time
      // events: https://www.w3.org/TR/html5/embedded-content-0.html#mediaevents
      this.player.on('canplaythrough', () => {
        if (!initdone) {
          initdone = true;
        }
      });
    },
    setClip(queryParams) {
      if (queryParams.start) {
        this.player.currentTime(queryParams.start);
        console.log('set the clip time');
      }
      if (queryParams.end) {
        this.endtime = queryParams.end;
      }
    }
  },
};
</script>
