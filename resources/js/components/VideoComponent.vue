<template>
    <div class="video">
        <video id="hammer-video-player" class="video-js hammer-video vjs-default-skin" controls type="video/mp4">
            <source :src="video_url" />
        </video>
    </div>
</template>

<script>
    import vjs from 'video.js';
    export default {
        props: ['video_url'],
        mounted() {
            (function(window, videojs) {
                var player = vjs('hammer-video-player');
                var overlay_content = '<p>' + $('.title').text() + '</p>';
                player.overlay({
                    overlays: [{
                        start: 'loadedmetadata',
                        'class': 'hammer-video-overlay',
                        content: overlay_content,
                        end: function() {
                            if (player.controlBar.hasClass('vjs-user-inactive')) {
                                $('.vjs-overlay').addClass('vjs-user-inactive');
                            }
                        },
                        align: 'top'
                    }]
                });
                $('.vjs-overlay').addClass('vjs-control-bar');
            }(window, window.videojs));
        }
    }
</script>
