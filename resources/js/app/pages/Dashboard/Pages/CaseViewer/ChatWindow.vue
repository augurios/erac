<template>
  <div>
    <md-card class="chat-window" :class="{'nested' : isNested}">
          <md-card-header>
              <md-avatar>
                  <img v-if="userData" :src="userData.avatar ? './storage/'+userData.avatar : './img/faces/avatar.jpg'" alt="Avatar">
                </md-avatar>

              <div class="md-title" v-if="userData">{{ userData.name }} {{ userData.lastname }} <md-button class="md-info md-round md-simple md-just-icon" @click="loadInfo(userData)">
                <md-icon>info</md-icon>
                <md-tooltip md-direction="top">Perfil</md-tooltip>
              </md-button></div>
              <div class="md-subhead">{{ userRole }}</div>
              <md-badge v-if="userData" v-bind:class="{ 'online': isUserOnline }"> </md-badge>
              
            </md-card-header>

            <md-card-content>
              <md-list class="md-triple-line" v-if="userData && messages.length" v-chat-scroll="{always: true, smooth: true}">
                <md-list-item 
                    v-for="(item, index) in messages"
                    :key="item.id"
                    v-bind:class="{ 'self': item.user.cedula == user.cedula }">
                      <template v-if="item.action != 'invitation'">
                          <md-avatar>
                            <img :src="item.user.avatar ? './storage/'+item.user.avatar : './img/faces/avatar.jpg'" alt="item.user.name">
                          </md-avatar>

                          <div class="md-list-item-text">
                            <span>{{item.user.name}}</span>
                            <span class="message-item">{{item.message}}</span>
                            <p>{{ item.created_at | moment("from") }}</p>
                          </div>
                          <div class="md-list-action">
                            <md-button class="md-icon-button md-simple" v-if="item.user.cedula == user.cedula">
                              <md-icon class="md-primary">create</md-icon>
                            </md-button>
                          
                          </div>
                      </template>
                      <template v-if="item.action === 'invitation'">
                          <div class="md-list-item-text">
                            <span class="message-item">Video Llamada Iniciada</span>
                            <p>{{ item.created_at | moment("from") }}</p>
                          </div>
                          <div class="md-list-action">
                            <md-button class="md-icon-button md-simple" v-if="index === Object.keys(messages).length - 1" @click="joinCall">
                              <md-icon class="md-primary">video_call</md-icon>
                            </md-button>
                          
                          </div>
                      </template>
                </md-list-item>
              </md-list>
              <div  v-if="messages.length == 0">
                <md-list>
                  <md-list-item>
                      <span class="md-caption">Utiliza está ventana de chat para comunicarte con el/la {{ userRole }}</span>
                  </md-list-item>
                </md-list>
              </div>
                
             
              <md-field>
                <label>Escribir mensaje para el/la {{ userRole }}</label>
                <md-textarea v-model="newMessage" @keyup.enter.native="addMessage" ></md-textarea>
              </md-field>
              <md-button class="md-success" @click="addMessage" :disabled="!newMessage.length">Enviar Mensaje</md-button>
            </md-card-content>

            <!-- <md-card-actions>
            
            </md-card-actions> -->
    </md-card>
  </div>
</template>

<script>
export default {
  props: {
    userData: {
      type: Object,
      required: true
    },
    isUserOnline: {
      required: true,
    },
    messages: {
      required: true
    },
    userRole: {
      required: true,
      default: 'Solicitante',
    },
    isNested: {
      required: false,
      default: false,
    },
    caseData: {
      required: false,
      default: false,
    }
  },
  data() {
    return {
      newMessage: '',
      user
    }
  },
  methods: {
    addMessage (event) {
      this.$emit('addMessage', this.newMessage, this.userRole)
      this.newMessage = '';
    },
    loadInfo(user) {
      this.$emit('loadInfo', user)
    },
    joinCall() {
      this.$router.push('/cases/'+this.caseData.id+'/chat-room');
    }
  }
}
</script>
<style lang="scss" scoped>
.md-card {
    &.nested {
      box-shadow: 0 0 0;
      margin: 1rem 0;
      .md-card-content {
        padding-bottom: 0;
      }
    }
    .md-info.md-button {
        position: absolute;
        margin-top: -12px;
    }
    .md-card-content {
      .md-caption{
        margin: 1rem;
      }
   }
}
.md-badge {
  background-color: #999999 !important;
  top: 2rem;
  right: initial;
  left: 3rem;
  width: 1rem;
  height: 1rem;
  border: 2px solid #fff;
}

.md-badge.online {
  background-color: #4caf50 !important;
}

.md-list.md-theme-default {
    box-shadow: inset 0 1px 4px 0 rgba(0, 0, 0, 0.14);
    background-color: #6cb2eb14;
    border-radius: 6px;
    max-height: 350px;
    height: 350px;
    overflow: hidden;
    overflow-y: auto;
    margin-top: -14px;
    padding-top: 0;
    padding-bottom: 0;
}
.md-has-textarea {
      margin-bottom: 0.5rem;
}
.md-field .md-textarea {
    min-height: 75px !important;
    height: 75px;
}
.md-list-item {
  margin:0;
  transition: all 0.5s ease;
  &.self {
    background-color: #0000000a;
  }
  .md-button {
      opacity: 0.2;
      transition: all 0.5s ease;
    }
  &:hover {
    background-color: #ffffff75;
    .md-button {
      opacity: 0.9;
    }
  }
  p {
      margin: 0;
      font-size: 14px;
      opacity: 0.5;
    }
}
.message-item {
  white-space: initial;
  margin: 0 0 0.2rem;
}
</style>
<style>
.modal-body h6 {
  margin: 0;
}
dl dd {
  margin-inline-start: 0;
}
</style>