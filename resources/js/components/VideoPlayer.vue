<template>
    <div class="video-js-responsive-container vjs-hd">
        <video ref="videoPlayer" class="video-js hammer-video-player vjs-default-skin"></video>
    </div>
</template>

<script>
import videojs from 'video.js';

export default {
    name: "VideoPlayer",
    props: {
        title: String,
        options: {
            type: Object,
            default() {
                return {};
            }
        }
    },
    data() {
        return {
            player: null
        }
    },
    mounted() {
      this.player = videojs(this.$refs.videoPlayer, this.options, function onPlayerReady() {
          console.log('onPlayerReady', this);
      })
      const overlay_content = `<p>${this.title}</p>`;
      this.player.overlay({
        overlays: [{
          start: 'loadedmetadata',
          class: 'hammer-video-overlay',
          content: overlay_content,
          end: function() {
              if (player.controlBar.hasClass('vjs-user-inactive')) {
                  $('.vjs-overlay').addClass('vjs-user-inactive');
              }
          },
          align: 'top',
        }]
      });
      $('.vjs-overlay').addClass('vjs-control-bar');
    },
    beforeDestroy() {
        if (this.player) {
            this.player.dispose()
        }
    }
}
</script>