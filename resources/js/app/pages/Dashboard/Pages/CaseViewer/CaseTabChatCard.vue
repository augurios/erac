<template>
    <div>
      <nav-tabs-card>
          <template slot="content">
            <span class="md-nav-tabs-title">Partes:</span>
            <md-tabs class="md-primary" md-alignment="left" md-swipeable>
              <template slot="md-tab" slot-scope="{ tab }">
               <md-icon>{{ tab.data.icon }}</md-icon> {{ tab.label }} <i class="badge" v-if="tab.data.badge">{{ tab.data.badge }}</i>
              </template>
              <md-tab v-if="caseData.opener" id="tab-opener" :md-label="`${caseData.opener.name} ${caseData.opener.lastname}`" @click="tabChanged('opener')"  :md-template-data="{ badge: unreadMessagesO, icon: 'account_circle' }">
                <ChatWindow  
                  :userData="caseData.opener" 
                  :isUserOnline="isOpenerOnline"
                  :messages="messagesOpener"
                  userRole="Solicitante"
                  @addMessage="addMessage"
                  @loadInfo="loadInfo"
                  :isNested="true"
                  :caseData="caseData"
                />
              </md-tab>

              <md-tab v-if="caseData.recipient" id="tab-recipient" :md-label="`${caseData.recipient.name} ${caseData.recipient.lastname}`"  @click="tabChanged('recipient')"  :md-template-data="{ badge: unreadMessagesR, icon: 'account_circle' }">
                <ChatWindow  
                  :userData="caseData.recipient" 
                  :isUserOnline="isRecipientOnline"
                  :messages="messagesRecipient"
                  userRole="Destinatario(a)"
                  @addMessage="addMessage"
                  @loadInfo="loadInfo"
                  :isNested="true"
                  :caseData="caseData"
                />
              </md-tab>

              <md-tab v-if="caseData.opener" id="tab-notes" md-label="Notas" :md-template-data="{ badge: 0, icon: 'assignment' }" @click="tabChanged('notes')" >
                <h5>Utilice está sección para guardar notas sobre esté caso.</h5>
                <vue-editor id="editor" v-model="caseData.notes"> </vue-editor>
                <md-button class="md-success" @click="editCase" :disabled="isSaving"><md-progress-spinner :md-diameter="24" :md-stroke="3" md-mode="indeterminate" v-if="isSaving"></md-progress-spinner>Salvar Cambios</md-button>
              </md-tab>
              
            </md-tabs>
          </template>
      </nav-tabs-card>
      <UserInfoModal :infoModal="infoModal" @close="infoModal = null" />
    </div>
</template>

<script>
import { NavTabsCard } from '../../../../components'
import ChatWindow from './ChatWindow'
import UserInfoModal from './UserInfoModal'
import { VueEditor } from "vue2-editor";
export default {
  components: {
    NavTabsCard,
    ChatWindow,
    UserInfoModal,
    VueEditor
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
      messagesOpener: [],
      messagesRecipient: [],
      isMediatorOnline: false,
      isOpenerOnline: false,
      isRecipientOnline: false,
      currentTab: 'opener',
      unreadMessagesO: 0,
      unreadMessagesR: 0,
      infoModal: null,
      isSaving: false,
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
        this.notif(message.action == 'acepted' ? `${e.user.name} se ha aceptado los terminos del caso` : `${e.user.name} se ha cancelado el caso` );
      } else if(message.case == this.caseData.id) {
        if(message.user_id == this.caseData.opener.id || message.recipient == this.caseData.opener.cedula) {
          this.messagesOpener.push({
            ...message,
            user: e.user
          });
          if(this.currentTab != 'opener') {
            this.unreadMessagesO++;
          }
        } else {
          this.messagesRecipient.push({
            ...message,
            user: e.user
          });
          if(this.currentTab != 'recipient') {
            this.unreadMessagesR++;
          }
        }
      }
    });    
    this.$eventBus.$on('makecall',this.makeCall);
  },
  beforeDestroy() {
    Echo.leave('room');
  },
  methods: {
    getCase() {
      this.caseData = this.caseDataProp;
      this.listenToChat()
    },
    listenToChat() {
      let { status } = this.caseData;
      let activeUsers = this.users;
      const isMember = this.isMemberOfCase;
      Echo.join('room')
        .here(users => {
          users.forEach(member => {
            if(status != 'waiting') {
              isMember(member, true);
            }
          });
        })
        .joining((user) => {
            if(status != 'waiting' && isMember(user, true)) {
              this.notif(`${user.name} se ha conectado`);
            }
        })
        .leaving((user) => {
            isMember(user, false);
            console.log('leaving', user);
        })
    },
    fetchMessages() {
        axios.get(`/messages/${this.caseData.id}?api_token=${this.user.api_token}`).then(response => {
            let messagesOpener = [];
            let messagesRecipient = [];
            response.data.message.forEach(msg => {
              if(msg.user_id == this.caseData.opener.id || msg.recipient.id == this.caseData.opener.id) {
                messagesOpener.push(msg);
              } else if (msg.user_id == this.caseData.recipient.id || msg.recipient.id == this.caseData.recipient.id) {
                messagesRecipient.push(msg);
              }
            });
            this.messagesOpener = messagesOpener;
            this.messagesRecipient = messagesRecipient;
        });
    },
    isMemberOfCase(member, isNew) {
      let { mediator,opener,recipient } = this.caseData;
      if(opener.cedula == member.cedula ) {
        this.isOpenerOnline = isNew ? true : false;
         return member;
       } else if(recipient.cedula == member.cedula) {
         this.isRecipientOnline = isNew ? true : false;
         return member;
       }
       return false
    },
    addMessage(msg,userRole) {
      let newMessage;
      let recipient;
      if(userRole == 'Solicitante') {
        recipient = this.caseData.opener.cedula
      } else {
        recipient = this.caseData.recipient.cedula
      }
      let messageRequest = {
            message: msg,
            recipient: recipient,
            case: this.caseData.id,
            user: this.user,
            created_at: new Date()
      }
      if(msg) {
          userRole == 'Solicitante' ? this.messagesOpener.push(messageRequest) : this.messagesRecipient.push(messageRequest);

          axios.post('/messages?api_token=' + user.api_token, messageRequest)
      }
    },
    addMessageAction(recipient) {
      let messageRequest = {
            message: 'invitation',
            recipient: recipient,
            case: this.caseData.id,
            user: this.user,
            created_at: new Date(),
            action: 'invitation'
      }

      // this.messagesOpener.push(messageRequest)

      axios.post('/messages?api_token=' + user.api_token, messageRequest)
      
    },
    makeCall() {
      this.addMessageAction(this.caseData.opener.cedula)
      this.addMessageAction(this.caseData.recipient.cedula)
    },
    tabChanged(tab) {
      if(tab != this.currentTab) {
        this.currentTab = tab;
        this.unreadMessagesO = 0;
        this.unreadMessagesR = 0;
      }
    },
    loadInfo(user) {
      this.infoModal = user
    },
    editCase() {
      this.isSaving = true;
      axios.put('case/' + this.$route.params.id + '/notes',this.caseData).then((response) => {
          this.caseData = response.data.case;
          this.isSaving = false;
        }).catch((response) => {
          this.caseData = response.data.case;
          this.notif('Cambios Guardados con exito');
          this.isSaving = false;
        });
    }
  },
}
</script>
<style lang="scss" scoped>
.md-card-nav-tabs.md-card-nav-tabs.md-card-nav-tabs .badge {
    width: 23px;
    height: 23px;
    display: flex;
    min-width: 0px!important;
    justify-content: center;
    align-items: center;
    position: absolute;
    top: 0px;
    right: 0px;
    background: red;
    border-radius: 100%;
    font-size: 15px !important;
    font-style: normal;
    font-weight: 600;
    font-family: "Roboto Mono", monospace;
    box-shadow: -1px 1px 2px #0000008c;
}

.md-card-nav-tabs {
    margin-top: 3rem;
}
#tab-notes button.md-button.md-success.md-theme-default {
    float: right;
    margin-bottom: 1rem;
}


</style>
