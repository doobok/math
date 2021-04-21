<template>
  <div class="uk-inline uk-light" uk-height-viewport>

    <vue-jitsi-meet
      ref="jitsiRef"
      :domain="domain"
      :options="jitsiOptions"
    ></vue-jitsi-meet>

    <div v-if="countdown" class="uk-position-large uk-position-top-center uk-padding-large">
      <p class="uk-h4 uk-text-center">До початку заняття залишилось</p>
      <div ref="countdownRef" class="uk-grid-small uk-child-width-auto uk-margin" uk-grid uk-countdown>
          <div>
              <div class="uk-countdown-number uk-countdown-days"></div>
          </div>
          <div class="uk-countdown-separator">:</div>
          <div>
              <div class="uk-countdown-number uk-countdown-hours"></div>
          </div>
          <div class="uk-countdown-separator">:</div>
          <div>
              <div class="uk-countdown-number uk-countdown-minutes"></div>
          </div>
          <div class="uk-countdown-separator">:</div>
          <div>
              <div class="uk-countdown-number uk-countdown-seconds"></div>
          </div>
      </div>
    </div>
  </div>
</template>

<script>
import { JitsiMeet } from '@mycure/vue-jitsi-meet';
export default {
  props: ['user', 'room_id', 'domain'],
  components: {
    VueJitsiMeet: JitsiMeet
  },
  data() {
    return {
      activeUsers: [],
      recording: false,
      countdown: true,
      time: {
        now: 0,
        start: '',
        end: 0,
        countdown: '',
      }
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
    getData () {
      axios
          .get('/api/v1/online-room-times', { params: { id: this.room_id }})
          .then(response => {
            // присвоюэмо усі дані
            this.time.now = response.data.now - Date.now();
            this.time.start = response.data.start;
            this.time.end = response.data.end;
            this.time.countdown = Date.parse(response.data.start);
            // запускаємо таймер
            UIkit.countdown(this.$refs.countdownRef, {date: this.time.start});
          });
    },
    // підключаємо фрейм конференції
    onIFrameLoad () {
      this.$refs.jitsiRef.addEventListener('videoConferenceLeft', this.exitConf);
    },
    // вихід з конференції
    exitConf() {
      document.location.href = "/online";
    },
    // слідкуємо за часом до початку та завершення заняття
    updateTime() {
      // підключаємо звуковий файл
      let audio = new Audio('/sounds/beep.mp3');
      // чекаємо поки не почнеться заняття
      if (this.countdown) {
        if (Date.now() + this.time.now > this.time.countdown) {
          this.countdown = false;
        }
      // слідкуємо за часом який залишився до кінця заняття
      } else {
        const timedif = (this.time.end - Date.now() + this.time.now) / 1000;
        const timeleft = Math.trunc(timedif);
        console.log(timeleft);
        if (timeleft < 301) {
          // очікуємо поки залишиться меньше 5 хв
          if ( timeleft == 300 || timeleft == 180 || timeleft == 60 ) {
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
    // отримуємо параметри
    this.getData();

    // відслідковуємо Ехо сервер
    this.chat
      .here((users) => {
          this.activeUsers = users;
      })
      .joining((user) => {
          this.activeUsers.push(user);
      });

    // запускаємо відстеження часу
    setInterval(this.updateTime, 1000);
  },
  // коли в чаті більше одного - починаємо запис
  watch: {
    activeUsers(users) {
      if (users.length > 1 && !this.recording && this.user.role !== 'student') {
        this.$refs.jitsiRef.executeCommand('startRecording', {
          mode: file
        });
        this.recording = true;
      }
    }
  },
}
</script>
