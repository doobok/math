<template>
  <vue-jitsi-meet
    ref="jitsiRef"
    :domain="domain"
    :options="jitsiOptions"
  ></vue-jitsi-meet>
</template>

<script>
import { JitsiMeet } from '@mycure/vue-jitsi-meet';
export default {
  props: ['user', 'room_id', 'end', 'domain'],
  components: {
    VueJitsiMeet: JitsiMeet
  },
  data() {
    return {
      activeUsers: [],
      recording: false,
    }
  },
  computed: {
    jitsiOptions () {
      return {
        roomName: 'tutor-math-videoroom-' + this.room_id,
        noSSL: false,
        userInfo: {
          // email: 'user@email.com',
          displayName: this.user.realname,
        },
        configOverwrite: {
          enableNoisyMicDetection: false,
          prejoinPageEnabled: false
        },
        interfaceConfigOverwrite: {
          SHOW_JITSI_WATERMARK: false,
          SHOW_WATERMARK_FOR_GUESTS: false,
          SHOW_CHROME_EXTENSION_BANNER: false,
          TOOLBAR_BUTTONS: this.getButtons(),
        },
        onload: this.onIFrameLoad
      };
    },
    // подключаем ехо сервер для слежения
    chat() {
      return window.Echo.join('room.' + this.room_id);
    },
  },
  methods: {
    onIFrameLoad () {
      this.$refs.jitsiRef.addEventListener('videoConferenceLeft', this.exitConf);
    },
    exitConf() {
      document.location.href = "/online";
    },
    // слідкуємо за часом
    updateTime() {
      // підключаємо звуковий файл
      let audio = new Audio('/sounds/beep.mp3');
      const timedif = (this.end - Date.now()) / 1000;
      const timeleft = Math.trunc(timedif);
      console.log(timeleft);
      if (timeleft < 301) {
        // очікуємо поки залишиться меньше 5 хв
        if ( timeleft == 300 || timeleft == 180 || timeleft == 60 ) {
          console.log('ring signal');
          // програємо звуковий сигнал
          let playPromise = audio.play();

          if (playPromise !== undefined) {
            playPromise.then(_ => {
              // Automatic playback started!
            })
            .catch(error => {
              // Auto-play was prevented
            });
          };
        };
        // якщо час закінчився - викидаємо користувача
        if (timeleft !== undefined && timeleft < 1 ) {
          this.exitConf();
        }
      }

    },
    getButtons() {
      if (this.user.role == 'student') {
        return ['microphone', 'camera', 'desktop', 'fullscreen', 'hangup', 'chat', 'raisehand'];
      } else {
        return ['etherpad', 'filmstrip', 'invite',
        'microphone', 'camera', 'desktop', 'fullscreen', 'hangup', 'chat'
        , 'mute-everyone']
      }
    }

  },
  mounted() {
    // відслідковуємо Ехо сервер
    this.chat
      .here((users) => {
          this.activeUsers = users;
      })
      .joining((user) => {
        console.log('user connect');
          this.activeUsers.push(user);
      });

    // запускаємо відстеження часу
    setInterval(this.updateTime, 1000);
  },
  // коли в чаті більше одного - починаємо запис
  watch: {
    activeUsers(users) {
      if (users.length > 1 && !this.recording && this.user.role !== 'student') {
        console.log('more 1 in chat');
        this.$refs.jitsiRef.executeCommand('startRecording', {
          mode: file
        });
      }
    }
  },
}
</script>
