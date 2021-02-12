<template>
  <v-container
  class="black fill-height pa-0"
  fluid
  >
  <span class="uk-h2 logo-icon">TutorMath</span>

  <div class="user-list">
    <span><b>В кімнаті:</b></span>
    <!-- <ul class="uk-list-bullet"> -->
      <li v-for="user in activeUsers">{{user.name}}</li>
    <!-- </ul> -->
  </div>

  <span class="exit-icon">
    <a href="/online" title="До списку кімнат">
      <v-icon
        x-large
        color="grey"
      >mdi-backburger</v-icon>
    </a>
  </span>


    <video class="main-video" ref="video-there" autoplay></video>
    <video class="pre-video" ref="video-here" autoplay></video>

    <span class="screen-share-icon">
      <v-icon
        x-large
        :color="getmicColor()"
        @click="shareScreen()"
      >mdi-image</v-icon>
    </span>

    <span class="mute-icon">
      <v-icon
        x-large
        :color="getmicColor()"
        @click="voiseControll()"
      >mdi-microphone</v-icon>
    </span>





  </v-container>

</template>

<script>
import Pusher from 'pusher-js';
import Peer from 'simple-peer';
export default {
  props: ['user', 'room_id', 'pusherKey', 'pusherCluster'],
  data() {
    return {
      channel: null,
      stream: null,
      peers: {},
      activeUsers: [],
      voice: false,
      screenShare: false,
    }
  },
  computed: {
    chat() {
      return window.Echo.join('room.' + this.room_id);
    }
  },
  mounted() {
    this.chat
      .here((users) => {
          this.activeUsers = users;
      })
      .joining((user) => {
          this.activeUsers.push(user);
          this.startVideoChat(user.id);
          console.log(user.name + ' приєднався');
      })
      .leaving((user) => {
          this.activeUsers.splice(this.activeUsers.indexOf(user), 1);
          console.log(user.name + ' відʼєднався');
      });
    // this.chat
    //     .listen('VideoChat', (e) => {
    //       console.log(e);
    //     });
    this.setupVideoChat();
  },
  methods: {
    startVideoChat(userId) {
      this.getPeer(userId, true);
      console.log('Chat started!');
    },
    voiseControll() {
      this.voice = !this.voice;
      this.setupVideoChat();
    },
    getmicColor() {
      if (this.voice) {
        return 'red';
      } else {
        return 'grey';
      }
    },
    shareScreen(){
      this.screenShare = !this.screenShare;
      this.setupVideoChat();
      // this.activeUsers.forEach(function(user) {
      //   console.log(user);
      //   // if (user.id != this.user.id) {
      //   //   this.startVideoChat(user.id);
      //   // }
      // });
      this.activeUsers.forEach((user, i) => {
        console.log(user);
        console.log(i);
      });


      console.log('shared');
    },
    getPeer(userId, initiator) {
      if(this.peers[userId] === undefined) {
        let peer = new Peer({
          initiator,
          stream: this.stream,
          trickle: false
        });
        peer.on('signal', (data) => {
          this.channel.trigger(`client-signal-${userId}`, {
            userId: this.user.id,
            data: data
          });
        })
        .on('stream', (stream) => {
          const videoThere = this.$refs['video-there'];
          videoThere.srcObject = stream;
        })
        .on('close', () => {
          const peer = this.peers[userId];
          if(peer !== undefined) {
            peer.destroy();
          }
          delete this.peers[userId];
        })
        .on('error', (err) => {
          console.log(err);
        });
        this.peers[userId] = peer;
      }
      return this.peers[userId];
    },
    async setupVideoChat() {
      // To show pusher errors
      // Pusher.logToConsole = true;

      const stream = await this.getStrim();


      // if (this.screenShare) {
      //   const stream = await navigator.mediaDevices.getDisplayMedia({ video: true, audio: this.voice });
      // } else {
      //   const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: this.voice });
      // }


      const videoHere = this.$refs['video-here'];
      videoHere.srcObject = stream;
      this.stream = stream;
      const pusher = this.getPusherInstance();
      this.channel = pusher.subscribe('presence-video-chat');
      this.channel.bind(`client-signal-${this.user.id}`, (signal) =>
      {
        const peer = this.getPeer(signal.userId, false);
        peer.signal(signal.data);
      });
    },
    getStrim() {
      if (this.screenShare) {
        return navigator.mediaDevices.getDisplayMedia({ video: true, audio: this.voice });
      } else {
        return navigator.mediaDevices.getUserMedia({ video: true, audio: this.voice });
      }
    },
    getPusherInstance() {
      return new Pusher(this.pusherKey, {
        authEndpoint: '/online/auth',
        cluster: this.pusherCluster,
        auth: {
          headers: {
            'X-CSRF-Token': document.head.querySelector('meta[name="csrf-token"]').content
          }
        }
      });
    }
  }
};
</script>

<style>
.video-container {
  width: 500px;
  height: 380px;
  margin: 8px auto;
  border: 3px solid #000;
  position: relative;
  box-shadow: 1px 1px 1px #9e9e9e;
}
.video-here {
  width: 130px;
  position: absolute;
  left: 10px;
  bottom: 16px;
  border: 1px solid #000;
  border-radius: 2px;
  z-index: 2;
}
.video-there {
  width: 100%;
  height: auto;
  max-height: 100%;
  z-index: 1;
}
.text-right {
  text-align: right;
}

.main-video {
  width: 100vw; /* Could also use width: 100%; */
  height: 100vh;
  object-fit: cover;
  position: fixed; /* Change position to absolute if you don't want it to take up the whole page */
  left: 0px;
  top: 0px;
  z-index: 1;
}
.pre-video {
  width: 130px;
  position: absolute;
  left: 10px;
  bottom: 16px;
  border: 1px solid #fff;
  border-radius: 2px;
  z-index: 2;
}
.mute-icon {
  position: absolute;
  right: 20px;
  bottom: 20px;
  z-index: 3;
}
.screen-share-icon {
  position: absolute;
  left: 10px;
  bottom: 140px;
  z-index: 3;
}
.logo-icon {
  position: absolute;
  right: 20px;
  top: 10px;
  opacity: 0.4;
  z-index: 3;
}
.exit-icon {
  position: absolute;
  left: 20px;
  top: 10px;
  opacity: 0.4;
  z-index: 3;
}
.user-list {
  position: absolute;
  left: 10px;
  top: 50px;
  z-index: 3;
  background-color: #fff;
  opacity: 0.2;
  color: #555;
  padding: 15px;
  border-radius: 10px;
}
</style>
