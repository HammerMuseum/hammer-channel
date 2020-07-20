<template>
  <div class="video-player__wrapper">
    <div class="video-player__wrapper-inner">
      <div
        ref="videoPlayerContainer"
        class="video-player__container vjs-hd"
      >
        <video
          ref="videoPlayer"
          class="video-js video-player vjs-default-skin"
          :poster="poster"
          playsinline
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
    <ClipDisplay
      v-if="isClip"
    >
      {{ clipString }}
      <div
        v-if="isEndOfClip"
      >
        This clip has ended.
        <button
          class="button button--action"
          @click="destroyClipMarkers"
        >
          Continue playing
        </button>
        <button
          class="button button--action"
          @click="replayClip"
        >
          Replay clip
        </button>
      </div>
    </ClipDisplay>
  </div>
</template>

<script>
import prettyms from 'humanize-duration';
import videojs from 'video.js';
import 'videojs-markers';
import ClipDisplay from './ClipDisplay.vue';

window.VIDEOJS_NO_BASE_THEME = true;

export default {
  name: 'VideoPlayer',
  components: {
    ClipDisplay,
  },
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
    options: {
      type: Object,
      default() {
        return {};
      },
    },
    retrySources: {
      type: Array,
      default() {
        return [];
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
      isClipSet: false,
      isEndOfClip: false,
      defaultOptions: {
        autoplay: false,
        controlBar: {
          muteToggle: false,
          pictureInPictureToggle: false,
          progressControl: {
            keepTooltipsInside: true,
          },
        },
        controls: true,
        fill: true,
        sources: null,
        textTrackSettings: false,
        preload: 'auto',
      },
    };
  },
  computed: {
    clipDuration() {
      if (this.clipStart) {
        if (this.clipEnd && this.clipEnd > this.clipStart) {
          return this.clipEnd - this.clipStart;
        }
        return this.player.duration() - this.clipStart;
      }
      return 0;
    },
    clipEnd() {
      const query = this.$route.query;
      return Object.prototype.hasOwnProperty.call(query, 'end') ? query.end : false;
    },
    clipStart() {
      const query = this.$route.query;
      return Object.prototype.hasOwnProperty.call(query, 'start') ? query.start : false;
    },
    clipStartTime() {
      return this.convertSecondsToTimecode(this.clipStart);
    },
    clipEndTime() {
      return this.convertSecondsToTimecode(this.clipEnd);
    },
    clipString() {
      let message = 'Currently viewing: ';
      const duration = prettyms(this.clipDuration * 1000);
      if (this.clipEnd) {
        message += `${duration} clip from ${this.clipStartTime}`;
      } else {
        message += `clip from ${this.clipStart} until the end of the video.`;
      }
      return message;
    },
    isClip() {
      return this.clipStart > 0;
    },
    markerDefaults() {
      return {
        markerStyle: {
          'background-color': 'yellow',
        },
        markerTip: {
          display: true,
          text: (marker) => marker.text,
          time: (marker) => marker.time,
        },
      };
    },
    playerOptions() {
      return { ...this.defaultOptions, ...this.options };
    },
  },
  watch: {
    timecode(val) {
      if (val > 0) {
        this.player.currentTime(val);
        this.player.play();
        this.$emit('timecode-reset');
      }
    },
    retrySources(sources) {
      if (sources !== null) {
        this.updatePlayerSrc(sources);
      }
    },
    playerOptions(newOptions) {
      this.player.pause();
      this.reInit(newOptions.sources);
    },
  },
  beforeDestroy() {
    if (this.player) {
      this.player.dispose();
    }
  },
  mounted() {
    this.initVideoPlayer();
  },
  methods: {
    convertSecondsToTimecode(timeStr) {
      return (new Date(timeStr * 1000)).toUTCString().match(/(\d\d:\d\d:\d\d)/)[0];
    },
    initVideoPlayer() {
      const DEFAULT_EVENTS = [
        'canplay',
        'canplaythrough',
        'dispose',
        'durationchange',
        'ended',
        'enterFullWindow',
        'enterpictureinpicture',
        'error',
        'exitFullWindow',
        'fullscreenchange',
        'leavepictureinpicture',
        'loadeddata',
        'loadedmetadata',
        'loadstart',
        'pause',
        'play',
        'playerresize',
        'playing',
        'ready',
        'seeked',
        'seeking',
        'texttrackchange',
        'useractive',
        'userinactive',
        'volumechange',
        'waiting',
      ];

      const self = this;
      const options = this.playerOptions;
      this.player = videojs(this.$refs.videoPlayer, options, function () {
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
          if (self.isClip && this.currentTime() >= self.clipEnd) {
            self.isEndOfClip = true;
            self.player.pause();
          }
        });


        self.$emit('ready', this);
      });
      this.initClipMarkers();
      this.initOverlays();
      this.player.ready(function () {
        self.player.addRemoteTextTrack(self.track, true);
      });
    },
    destroyClipMarkers() {
      this.player.markers.removeAll();
      this.player.play();
      this.isClipSet = false;
      this.isEndClip = false;
      this.$router.push({ path: this.$route.path });
    },
    replayClip() {
      this.player.currentTime(this.clipStart);
      this.player.play();
      this.isEndClip = false;
    },
    initClipMarkers() {
      if ((this.clipStart || this.clipEnd) && !this.isClipSet) {
        const markerOptions = {
          ...this.markerDefaults,
          markers: [
            {
              time: this.clipStart,
              duration: this.clipDuration,
              text: 'Clipped section',
            },
          ],
        };
        this.player.markers(markerOptions);
        this.isClipSet = true;
      }
    },
    initOverlays() {
      // @todo this could be abstracted out into a child component
      // if multiple overlays were ever needed.
      const el = videojs.dom.createEl('div', {
        className: 'vjs-title-overlay',
      });
      el.innerHTML = `<span>${this.title}</span>`;
      this.player.el().appendChild(el);
    },
    reInit(val) {
      this.player.poster(this.poster);
      const textTracks = this.player.textTracks();
      for (let i = 0; i < textTracks.length; i += 1) {
        this.player.removeRemoteTextTrack(textTracks[i]);
      }
      this.player.addRemoteTextTrack(this.track, true);
      this.player.off('ready');
      this.player.src(val);
    },
    updatePlayerSrc(val) {
      const time = this.player.currentTime();
      let initdone = false;

      this.player.off('ready');
      this.player.src(val);

      // wait for video metadata to load, then set time.
      this.player.one('loadedmetadata', () => {
        if (this.isClip) {
          this.setClip();
        } else {
          this.player.currentTime(time);
          this.player.play();
        }
      });

      // iPhone/iPad need to play first, then set the time
      // events: https://www.w3.org/TR/html5/embedded-content-0.html#mediaevents
      this.player.one('canplaythrough', () => {
        if (!initdone) {
          if (!this.isClip) {
            this.player.currentTime(time);
            this.player.play();
          }
          initdone = true;
        }
      });
    },
    setClip() {
      if (this.clipStart) {
        this.player.currentTime(this.clipStart);
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
