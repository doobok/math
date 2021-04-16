<template>
  <vue-jitsi-meet
    ref="jitsiRef"
    domain="meet.jit.si"
    :options="jitsiOptions"
  ></vue-jitsi-meet>
</template>

<script>
import { JitsiMeet } from '@mycure/vue-jitsi-meet';
export default {
  props: ['user', 'room_id'],
  components: {
    VueJitsiMeet: JitsiMeet
  },
  computed: {
    jitsiOptions () {
      return {
        roomName: 'tutor-math-room-name' + this.room_id,
        noSSL: false,
        userInfo: {
          // email: 'user@email.com',
          displayName: this.user.name,
        },
        configOverwrite: {
          enableNoisyMicDetection: false,
          prejoinPageEnabled: false
        },
        interfaceConfigOverwrite: {
          SHOW_JITSI_WATERMARK: false,
          SHOW_WATERMARK_FOR_GUESTS: false,
          SHOW_CHROME_EXTENSION_BANNER: false,
          TOOLBAR_BUTTONS: [
              'etherpad', 'filmstrip', 'invite',
              'microphone', 'camera', 'desktop', 'fullscreen', 'hangup', 'chat', 'raisehand'
              , 'mute-everyone',
          ],
        },
        onload: this.onIFrameLoad
      };
    },
  },
  methods: {
    onIFrameLoad () {
      this.$refs.jitsiRef.addEventListener('videoConferenceLeft', this.exitConf);
    },
    exitConf() {
      document.location.href = "/online";
    }
  },
};
</script>
