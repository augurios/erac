<template>
  <div class="container">
    <div class="video-container" ref="video-container">
      <video class="video-here" ref="video-here" autoplay muted></video>  
      <video class="video-there" ref="video-there" autoplay :muted="isVideoMuted"></video>
     <div class="spinner-container" v-if="videoLoading">
        <md-progress-spinner :md-diameter="100" :md-stroke="10" md-mode="indeterminate"></md-progress-spinner>
     </div>
     <md-button class="md-warning back-button" @click="goBack">
        <md-icon class="md-info">arrow_back</md-icon>
      </md-button>
      <div class="tool-bar-wrap" v-if="user.role === 'mediator' && videoOn">
        <md-content class="md-elevation-13 tool-bar">
            <md-button class="md-info" @click="toggleMute">
              <md-icon class="md-info" v-if="!isVideoMuted">volume_off</md-icon>
              <md-icon class="md-info" v-else>volume_up</md-icon>
            </md-button>
             <md-button class="md-info" @click="toggleMic">
              <md-icon class="md-info" v-if="!isMicMuted">mic_off</md-icon>
              <md-icon class="md-info" v-else>mic</md-icon>
            </md-button>
            <md-button class="md-danger" @click="closeChat"><md-icon class="md-danger">close</md-icon></md-button>
           
        </md-content>
      </div>
      <md-content class="md-elevation-13 active-users" v-if="channel && channel.members">
        <span class="md-subheading">Usuarios en llamada</span>
        <div class="text-right" v-for="(userCur) in onlineUsers" :key="userCur.userId">
           <md-button class="md-primary" v-if="userCur.userInfo.id != user.id && user.role === 'mediator' && !videoOn && !videoLoading" @click="startVideoChat(userCur.userId)">Hablar con {{`${userCur.userInfo.name} ${userCur.userInfo.lastname}`}}</md-button>
           <badge v-else-if="userCur.userInfo.id != user.id" type="success">{{`${userCur.userInfo.name} ${userCur.userInfo.lastname}`}}</badge>
        </div>
        <div class="text-right" v-if="!onlineUsers.length">
          <small>No hay usuarios conectados</small>
        </div>
      </md-content>
    </div>
  </div>
</template>
<script>
import Pusher from 'pusher-js';
import Peer from 'simple-peer';
import { Badge } from '../../../components'

export default {
  props: {
    caseData: {
      required: false,
      default: false,
    }
  },
  components: {
    Badge
  },
  data() {
    return {
      channel: null,
      stream: null,
      peers: {},
      pusherKey: null,
      pusherCluster: null,
      others: [],
      isVideoMuted: false,
      videoOn: false,
      videoLoading: false,
      activeVideo: null,
      isMicMuted: false,
      user,
      caseId: null
    }
  },
  mounted() {
    console.log('thissss',this);
    this.caseId = this.$route.params.id;
    let elem = document.getElementById("pusher-data")
    this.pusherKey = elem.getAttribute('pusher-key');
    this.pusherCluster = elem.getAttribute('pusher-cluster');
    this.setupVideoChat();
  },
  methods: {
    startVideoChat(userId) {
      this.videoOn = true;
      this.videoLoading = true;
      this.getPeer(userId, true);
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
          this.videoLoading = false;
          this.activeVideo = userId;
        })
        .on('close', () => {
          const peer = this.peers[userId];
          if(peer !== undefined) {
            peer.destroy();
          }
          delete this.peers[userId];
        });
        console.log('peer',peer);
        this.peers[userId] = peer;
      } 
      return this.peers[userId];
    },
    async setupVideoChat() {
      const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
      const videoHere = this.$refs['video-here'];
      videoHere.srcObject = stream;
      this.stream = stream;
      const pusher = this.getPusherInstance();
      this.channel = pusher.subscribe(`presence-video-chat-${this.caseId}`);
      console.log('stream', stream, this.channel);
      this.channel.bind(`client-signal-${this.user.id}`, (signal) => 
      {
        const peer = this.getPeer(signal.userId, false);
        peer.signal(signal.data);
      });
     
      this.channel.bind('pusher:member_added', function(member) {
        console.log('member',member);
      });

      await axios.get('video_chat').then((response) => { this.others = response.data.others }).catch();
    },
    getPusherInstance() {
      return new Pusher(this.pusherKey, {
        authEndpoint: '/auth/video_chat',
        cluster: this.pusherCluster,
        auth: {
          headers: {
            'X-CSRF-Token': document.head.querySelector('meta[name="csrf-token"]').content
          }
        }
      });
    },
    closeChat() {
      const closeVideo = this.$refs['video-there'];
      closeVideo.srcObject = null;
      if (this.peers[this.activeVideo]) this.peers[this.activeVideo]._events.close();
      this.peers = {}; 
      this.videoOn = false;
      this.isMicMuted = false;
    },
    toggleMute() {
      const videoTheres = this.$refs['video-there'];
      this.isVideoMuted = !this.isVideoMuted;
      videoTheres.muted = this.isVideoMuted;
    },
    toggleMic() {
      this.isMicMuted = !this.isMicMuted;
      this.stream.getAudioTracks().forEach(track => {track.enabled = !this.isMicMuted});
    },
    goBack() {
      if(this.stream.close) this.stream.close();
      this.stream = null,
      this.channel.disconnect();
      this.channel = null;
      this.peers = {}; 
      this.closeChat();
      window.location.replace('/app#/cases/'+this.caseId+'/');
      location.reload();
      // this.$router.push('/cases/'+this.caseId+'/');
    }
  },
  computed: {
    onlineUsers() {
      let chunky = [];

      if(this.channel && this.channel.members && this.channel.members.count) {
        this.channel.members.each(function(member) {
            chunky.push({
              userId : member.id,
              userInfo : member.info,
            })
        });
      }

      return chunky
    }
  }
};
</script>
<style>
.video-container {
  width: auto;
  height: auto;
  position: fixed;
  top: 0;
  z-index: 90000;
  left: 0;
  bottom: 0;
  right: 0;
  background: #000000;
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
  width: 120%;
  height: 97% !important;
  z-index: 1;
}
.active-users {
  position: absolute;
  right:0;
  bottom:0;
  padding: 12px;
  border-radius: 3px;
  
}
.active-users .badge {
    margin-top: 5px;
}
.text-right {
  text-align: right;
}
.tool-bar-wrap {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    text-align: center;
}
.tool-bar {
    display: inline-block;
    padding: 4px 12px;
}
.spinner-container {
    position: absolute;
    top: 50%;
    left: 50%;
    margin-left: -50px;
}
.video-container button.md-button.md-warning.back-button.md-theme-default {
    position: absolute;
    left: 10px;
    top: 15px;
}
</style>