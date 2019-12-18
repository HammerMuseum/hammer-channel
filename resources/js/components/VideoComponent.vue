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
                var overlay_content = $('.title').text();
                player.overlay({
                    overlays: [{
                        start: 'loadedmetadata',
                        content: overlay_content,
                        end: 'userinactive',
                        align: 'top'
                    }, {
                        start: 'useractive',
                        content: overlay_content,
                        align: 'top',
                        end: 'userinactive'
                    }]
                });

                $('.vjs-overlay').addClass('vjs-control-bar');

            }(window, window.videojs));
        }
    }
</script>
