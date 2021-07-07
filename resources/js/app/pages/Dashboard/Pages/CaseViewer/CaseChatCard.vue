<template>
  <div>
    <ChatWindow  
      v-if="caseData.mediator"
      :userData="caseData.mediator" 
      :isUserOnline="isMediatorOnline"
      :messages="messages"
      userRole="Mediador(a)"
      @addMessage="addMessage"
      @loadInfo="loadInfo"
      :caseData="caseData"
    />
    <UserInfoModal :infoModal="infoModal" @close="infoModal = null" />
  </div>
</template>

<script>
import { Modal } from '../../../../components'
import ChatWindow from './ChatWindow'
import UserInfoModal from './UserInfoModal'
export default {
  components: {
    Modal,
    ChatWindow,
    UserInfoModal
  },
  props: {
    caseDataProp: {
      type: Object,
      required: true,
    }
  },
  data() {
    return {
      caseData: {},
      user,
      messages: [],
      isMediatorOnline: false,
      newMessage:'',
      infoModal: null,
    }
  },
  mounted(){
    this.getCase();
    this.fetchMessages();
    Echo.private('chat')
    .listen('SendMessage', (e) => {
      let {message} = e;
      
      if(message.action && message.action === "invitation" && message.case ==  this.caseData.id) {
          message.fresh = true
      } 

      if(message.action && message.action != "invitation" && message.case ==  this.caseData.id) {
        this.$emit('updated');
        this.notif(message.action == 'acepted' ? `${e.user.name} ha aceptado los terminos del caso` : `${e.user.name} ha cancelado el caso` );
      } else if((message.user_id == user.id || message.recipient == user.cedula) && message.case ==  this.caseData.id) {
        this.messages.push({
          ...message,
          user: e.user
        });
      }
      
    });    
  },
  destroyed() {
    Echo.leave('room');
  },
  methods: {
    getCase() {
      this.caseData = this.caseDataProp;
      this.listenToChat();
    },
    listenToChat() {
      let { status } = this.caseData;
      let activeUsers = this.users;
      const isMember = this.isMemberOfCase;
      Echo.join('room')
        .here(users => {
          users.forEach(member => {
            if(status != 'waiting' && this.caseData.opener) {
              isMember(member, true);
            }
          });
        })
        .joining((user) => {
            if(status != 'waiting' && this.caseData.opener && isMember(user, true)) {
              this.notif(`${user.name} se ha conectado`);
            }
        })
        .leaving((user) => {
            isMember(user, false);
        })
    },
    fetchMessages() {
        axios.get(`/messages/${this.caseData.id}?api_token=${this.user.api_token}`).then(response => {
            let myMessages = [];
            response.data.message.forEach(msg => {
              if(msg.user_id == user.id || msg.recipient.id == user.id) {
                myMessages.push(msg);
              }
            });
            this.messages = myMessages;
        });
    },
    isMemberOfCase(member, isNew) {
      let { mediator,opener,recipient } = this.caseData;
      if(opener.cedula == member.cedula ) {
         return member;
       } else if(recipient.cedula == member.cedula) {
         return member;
       } else if (mediator.cedula == member.cedula) {
         this.isMediatorOnline = isNew ? true : false;
         return member;
       }
       return false
    },
    addMessage(msg) {
        let messageRequest = {
            message: msg,
            recipient: this.caseData.mediator.cedula,
            case: this.caseData.id,
            user: this.user,
            created_at: new Date()
        }
        if(msg) {
          this.messages.push(messageRequest);
          axios.post('/messages?api_token=' + user.api_token, messageRequest)
        }
    },
    loadInfo(user) {
      this.infoModal = user
    }
  },
  watch: {
    caseDataProp() {
      this.caseData = this.caseDataProp;
    }
  }
}
</script>
