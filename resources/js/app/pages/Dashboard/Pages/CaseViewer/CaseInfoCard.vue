<template>
  <div>
      <md-card class="general-case-card" v-if="caseData.id">
        <md-card-header>
          <md-avatar>
            <img v-if="caseData.opener && caseData.opener.cedula != user.cedula" 
                 :src="caseData.opener.avatar ? './storage/'+caseData.opener.avatar : './img/faces/avatar.jpg'" 
                 alt="Avatar">
            <img v-if="caseData.recipient && caseData.recipient.cedula != user.cedula" 
                 :src="caseData.recipient.avatar ? './storage/'+caseData.recipient.avatar : './img/faces/avatar.jpg'" 
                 alt="Avatar">
          </md-avatar>
          <div class="md-title">
              {{caseData.opener.lastname}}, {{caseData.opener.name}} / 
              <span v-if="caseData.status != 'waiting'">{{caseData.recipient.lastname}}, {{caseData.recipient.name}}</span>
          </div>
          <div class="md-subhead">
            Mediador(a): <span v-if="caseData.mediator">{{caseData.mediator.name}} {{caseData.mediator.lastname}}</span> 
          </div>
          <badge :type="statusBadge">Estado: <strong>{{caseData.status | statusDic}}</strong></badge>
        </md-card-header>

        <md-card-content>
          <md-icon>date_range</md-icon>
              <span>{{ caseData.created_at | moment("DD-MM-YYYY") }}</span>, 
          <md-icon>select_all</md-icon>
              <md-button class="md-simple md-sm edit-amount" 
                         v-if="caseData.status == 'waiting' && isOpener" 
                         @click.native="editAgreement">
                {{ caseData.amount | priceWithCommas}}
                <md-tooltip md-direction="top" md-delay="600">Editar Monto</md-tooltip>
              </md-button>
              <span v-else>{{ caseData.amount | priceWithCommas}}</span>
              <p v-html="caseData.description"></p>
        </md-card-content>

        <md-card-expand>
          <md-card-actions md-alignment="space-between">
            <div v-if="caseData.status != 'done'">
                    <md-button class="md-success" 
                          v-if="caseData.status == 'reviewing' && isMediator" 
                          @click="approveCase">
                         Aprobar Caso</md-button>

                    <md-button class="md-success" 
                          v-if="caseData.status == 'waiting' && isRecipient" 
                          @click="confirmParticipation">
                          Aceptar participación</md-button>
                  
                  <md-button class="md-success" 
                          v-if="isMediator && caseData.status == 'active'" 
                          @click="setMeeting = true">
                          Agendar Reunion</md-button>
                  
                  <md-button class="md-success" 
                          v-if="isMediator && caseData.status == 'active'" 
                          @click="confirmCall = true">
                          Iniciar Video llamada</md-button>

                  <md-button class="md-danger" 
                         v-if="isOpener" 
                         @click="confirmClosing = true">
                         Cerrar Caso</md-button>

            </div>
            <div v-if="caseData.resolution">
             Resolucion: <strong> {{caseData.resolution == 'canceled' ? 'Cancelado por Solicitante': 'Terminos aceptados por recibiente'}}</strong>
             
            </div>
            <md-button class="md-success" 
                          v-if="isMediator && caseData.status == 'done'" 
                          @click="createDocument">
                          Preparar Documento</md-button>
            <md-card-expand-trigger>
              <md-button class="md-info">
                Ver Propuesta
              </md-button>
            </md-card-expand-trigger>
          </md-card-actions>

          <md-card-expand-content>
            <md-card-content>
              <span class="md-subheading">Propuesta</span>
              <md-card md-with-hover class="agreement-wrapper" v-if="caseData.status == 'waiting' && isOpener" @click.native="editAgreement">
                <md-ripple>
                  <md-card-content v-html="caseData.agreement" ></md-card-content>
                </md-ripple>
                <md-tooltip md-direction="top" md-delay="600">Editar Acuerdo</md-tooltip>
              </md-card>
              <div v-else v-html="caseData.agreement" :class="{'faded': caseData.counter_agreement}"></div>
              <div v-if="caseData.counter_agreement" v-html="caseData.counter_agreement"></div>
              <p> Propuesta por 
                <strong v-if="caseData.counter_agreement">{{ caseData.recipient.name }} {{ caseData.recipient.lastname }}</strong>
                <strong v-else>{{ caseData.opener.name }} {{ caseData.opener.lastname }}</strong> </p>

              <div v-if="caseData.status == 'active'">
                <md-button class="md-success" 
                         v-if="(isRecipient && !IsProposing && !caseData.counter_agreement) || (isOpener && !IsProposing && caseData.inverted)" 
                         @click="confirmAcepting = true">
                         Aceptar Propuesta</md-button>
              
              <md-button class="md-warning" 
                         v-if="isOpener && !IsProposing && caseData.inverted" 
                         @click="confirmDispute = true">
                         Disputar Propuesta</md-button>
              
              <md-button class="md-info" 
                         v-if="isRecipient && !IsProposing  && !caseData.counter_agreement" 
                         @click.native="editAgreement">
                         Disputar Propuesta</md-button>
              </div>
            </md-card-content>
          </md-card-expand-content>
        </md-card-expand>
      </md-card>
      
        <modal v-if="editMode" @close="cancelEdit" class="edit-modal">
          <template slot="header">
            <h3 class="modal-title">Editar Acuerdo</h3>
            <md-button class="md-simple md-just-icon md-round modal-default-button" @click="cancelEdit">
              <md-icon>clear</md-icon>
            </md-button>
          </template>

          <template slot="body">
            <h5 v-if="isRecipient">Edita el acuerdo actual o crea una nueva propuesta</h5>
            <p>
              <md-field>
                <label>Monto</label>
                <md-input v-model="editData.amount" type="number"></md-input>
                <span class="md-helper-text">(opcional)</span>
              </md-field>
            </p>
            <vue-editor id="editor" v-model="editData.agreement"> </vue-editor>
          </template>

          <template slot="footer">
            <md-button class="md-warning" @click="newProposal" v-if="!IsProposing">Plantear Nueva Propuesta</md-button>
            <md-button class="md-simple" @click="editCase" v-else>Salvar Cambios</md-button>
            <md-button class="md-danger md-simple" @click="cancelEdit">Cancelar</md-button>
          </template>
        </modal>

        <!--confirm invitation --> 

        <modal v-if="confirmInvitation" @close="smallAlertModalHide">
          <template slot="header">
            <md-button class="md-simple md-just-icon md-round modal-default-button" @click="smallAlertModalHide">
              <md-icon>clear</md-icon>
            </md-button>
          </template>

          <template slot="body">
            <p>Está seguro(a) que desea participar en este caso</p>
          </template>

          <template slot="footer">
            <md-button class="md-simple md-danger" @click="smallAlertModalHide">Cancelar</md-button>
            <md-button class="md-success" @click="acceptInvitation">Lo Deseo</md-button>
          </template>
        </modal>

        <!-- confirm acepting --> 

         <modal v-if="confirmAcepting" @close="smallAlertModalHide">
          <template slot="header">
            <md-button class="md-simple md-just-icon md-round modal-default-button" @click="smallAlertModalHide">
              <md-icon>clear</md-icon>
            </md-button>
          </template>

          <template slot="body">
            <p>Está seguro(a) que desea aceptar los terminos de este caso</p>
          </template>

          <template slot="footer">
            <md-button class="md-simple md-danger" @click="smallAlertModalHide">Cancelar</md-button>
            <md-button class="md-success" @click="closeCase('acepted')">Lo Deseo</md-button>
          </template>
        </modal>

        <!-- confirm closing --> 

         <modal v-if="confirmClosing" @close="smallAlertModalHide">
          <template slot="header">
            <md-button class="md-simple md-just-icon md-round modal-default-button" @click="smallAlertModalHide">
              <md-icon>clear</md-icon>
            </md-button>
          </template>

          <template slot="body">
            <p>Está seguro(a) que desea abandonar este caso</p>
          </template>

          <template slot="footer">
            <md-button class="md-simple md-danger" @click="smallAlertModalHide">Cancelar</md-button>
            <md-button class="md-success" @click="closeCase('canceled')">Lo Deseo</md-button>
          </template>
        </modal>

         <!-- confirm dispute --> 

         <modal v-if="confirmDispute" @close="smallAlertModalHide">
          <template slot="header">
            <md-button class="md-simple md-just-icon md-round modal-default-button" @click="smallAlertModalHide">
              <md-icon>clear</md-icon>
            </md-button>
          </template>

          <template slot="body">
            <p>Está seguro(a) que desea solicitar una reunión con el Mediador para discutir las propuestas realizadas y llegar a un acuerdo.</p>
          </template>

          <template slot="footer">
            <md-button class="md-simple md-danger" @click="smallAlertModalHide">Cancelar</md-button>
            <md-button class="md-success" @click="requestMeeting">Lo Deseo</md-button>
          </template>
        </modal>

        <!-- confirm call --> 

         <modal v-if="confirmCall" @close="smallAlertModalHide">
          <template slot="header">
            <md-button class="md-simple md-just-icon md-round modal-default-button" @click="smallAlertModalHide">
              <md-icon>clear</md-icon>
            </md-button>
          </template>

          <template slot="body">
            <p>La siguiente accion iniciara una video llamada con los usuario acualmente en linea. ¿Desea continuar? .</p>
          </template>

          <template slot="footer">
            <md-button class="md-simple md-danger" @click="smallAlertModalHide">Cancelar</md-button>
            <md-button class="md-success" @click="initiateVideoCall">Lo Deseo</md-button>
          </template>
        </modal>

        <!-- set Meeting --> 

        <EventModal 
            :active="setMeeting" 
            @acept="setMeetingDate" 
            @close="closeMeeting" 
            :meetingData="newMeetingData"/>
        
        <DocumentModal 
            :caseData="caseData" 
            :documentEditor="documentEditor" 
            @close="closeDocument"
            @success="$emit('success')"
            />
        

    </div>
</template>

<script>
import { VueEditor } from "vue2-editor";
import { Badge, Modal } from '../../../../components';
import EventModal from './modules/EventModal'
import DocumentModal from './modules/DocumentModal'

export default {
  components: {
    Badge,
    Modal,
    VueEditor,
    EventModal,
    DocumentModal
  },
  props: {
    caseDataProp: {
      type: Object,
      required: true,
    },
    eventBus: {
      required: true,
    }
  },
  data() {
    return {
      caseData: {},
      user,
      editMode: false,
      editData: {
        agreement: '',
        amount: 0,
        status: '',
      },
      newMeetingData: {
        timeData: {
          hh: '01',
          mm: '00',
          ss: '00',
          a: 'pm'
        },
        selectedDate: new Date()
      },
      
      confirmInvitation: false,
      confirmClosing: false,
      confirmAcepting: false,
      confirmDispute: false,
      confirmCall: false,
      setMeeting: false,
      isOpener: false,
      isMediator: false,
      isRecipient: false,
      IsProposing: false,
      documentEditor: false,
    }
  },
  beforeMount(){
    this.getCase();
  },
  mounted() {
    this.eventBus.$on('newdate', this.enableMeeting);
    console.log(this);
  },
  methods: {
    getCase() {
      this.caseData = this.caseDataProp;
      this.editData.status = this.caseDataProp.status      
    },
    editAgreement() {
      this.editData.agreement = this.caseData.agreement;
      this.editData.amount = this.caseData.amount;
      this.editMode = true;
    },
    cancelEdit() {
      this.editData = {
        agreement: '',
        amount: 0,
      };
      this.editMode = false;
    },
    editCase() {
      axios.put('case/' + this.$route.params.id,this.editData).then((response) => {
          this.caseData = response.data.case;
          this.editMode = false;
        }).catch();
    },
    newProposal() {
      axios.put('case/' + this.$route.params.id +'/invert',this.editData).then((response) => {
          this.caseData = response.data.case;
          this.editMode = false;
          this.$emit('updated', response.data.case);
        }).catch();
    },
    requestMeeting() {
      axios.put('case/' + this.$route.params.id +'/invert',{}).then((response) => {
          this.caseData = response.data.case;
          this.confirmDispute = false;
          this.$emit('updated', response.data.case);
        }).catch();
    },
    acceptInvitation() {
      this.smallAlertModalHide()
      axios.put('case/' + this.$route.params.id + '/init',{}).then((response) => {
          this.caseData = response.data.case;
          this.$emit('updated', response.data.case);
        }).catch();
    },
    closeCase(resolution) {
      this.smallAlertModalHide()
      this.caseData.resolution = resolution;
      axios.put('case/' + this.$route.params.id + '/resolve',this.caseData).then((response) => {
          this.caseData = response.data.case;
          this.$emit('updated', response.data.case);
        }).catch();
    },
    confirmParticipation() {
      this.confirmInvitation = true;
    },
    smallAlertModalHide() {
      this.confirmInvitation = false;
      this.confirmClosing = false;
      this.confirmAcepting = false;
      this.confirmDispute = false;
      this.confirmCall = false
    },
    enableMeeting(date) {
      this.newMeetingData.selectedDate = date.date;
      this.setMeeting = true;
    },
    setMeetingDate() {
      let meetingData = this.newMeetingData;
      
      meetingData.caseId = this.caseData.id;
      meetingData.mediatorId = this.caseData.mediator.id;
      let hour = meetingData.timeData.a == 'pm' ? parseInt(meetingData.timeData.hh) + 12 : parseInt(meetingData.timeData.hh)

      meetingData.selectedDate.setHours(hour.toString(), meetingData.timeData.mm, meetingData.timeData.ss, '00');

      axios.post('meeting',meetingData).then((response) => {
          this.$emit('updatedEvent', response.data.meeting);
          this.$emit('updated', this.caseData);
          this.confirmDispute = false;
          this.closeMeeting();
        }).catch();
     
    },
    closeMeeting() {
      this.setMeeting = false;
    },
    createDocument() {
      this.documentEditor = true;
    },
    closeDocument() {
      this.documentEditor = false;
    },
    initiateVideoCall() {
      this.$eventBus.$emit('makecall');
      this.$router.push('/cases/'+this.caseData.id+'/chat-room');
    },
    approveCase() {
      axios.put('case/' + this.$route.params.id + '/approve',{}).then((response) => {
          this.caseData = response.data.case;
          this.$emit('updated', response.data.case);
        }).catch();
    }
    
  },
  computed: {
    statusBadge() {
      const dic = {
        'active':'success',
        'done':'success',
        'closed':'danger',
        'waiting':'info',
        'reviewing':'info',
      }
      return dic[this.caseData.status];
    }
  },
  watch: {
    caseDataProp() {
      this.caseData = this.caseDataProp;
      if(this.caseDataProp.opener) this.isOpener = this.caseDataProp.opener.cedula == this.user.cedula;
      if(this.caseDataProp.mediator) this.isMediator = this.caseDataProp.mediator.cedula == this.user.cedula 
      if(this.caseDataProp.recipient) this.isRecipient = this.caseDataProp.recipient.cedula ? this.caseDataProp.recipient.cedula == this.user.cedula : this.caseDataProp.recipient == this.user.cedula;

      this.IsProposing = (this.caseDataProp.recipient.cedula == this.user.cedula && this.caseDataProp.inverted) || (this.caseDataProp.opener.cedula == this.user.cedula && this.caseDataProp.inverted == false && !this.caseDataProp.counter_agreement) 
    }
  }
}
</script>

<style lang="scss">
.general-case-card {
    .edit-amount {
      margin: 0;
      .md-ripple {
          padding: 0.1rem 0.25rem !important;
          font-size: 15px;
      }
    }
    .badge {
      position: absolute;
      top: 1rem;
      right: 1rem;
    }
    .faded {
      opacity: 0.4;
      &::before{
        content:"Propuesta anterior:";
        font-weight: bold;
      }
    }
  } 
  .edit-modal .modal-container {
      max-width: 991px !important;
  }
  .agreement-wrapper {
    margin-top:0;
    overflow: hidden;
    transition-delay: 0.3s !important;
    &:not(:hover) {
      box-shadow: 0 0px 0px 0 transparent;
    }
  }
.meeting-modal {
  .hour-icon {
    float: left;
    margin-top: 4px;
  }

  .vue__time-picker {
    &.time-picker {
      float: right;
      width: 9em;
    }
    input.display-time {
      border: 1px solid transparent;
      border-bottom: 1px solid #d2d2d2;
    }

    .clear-btn {
      width: 32px;
      min-width: 32px;
      height: 32px;
      margin: 0;
      position: absolute;
      right: -15px;
      transition: .4s cubic-bezier(.4,0,.2,1);
      top: 0;
      border-radius: 50%;
      color:#999999 !important;
      background-color: #FFFFFF !important;
      box-shadow: 0 2px 2px 0 rgba(153, 153, 153, 0.14), 0 3px 1px -2px rgba(153, 153, 153, 0.2), 0 1px 5px 0 rgba(153, 153, 153, 0.12);
      span {
        font-size: 1.3em;
        font-weight: 400;
      }
      &:hover {
        box-shadow: 0 14px 26px -12px rgba(153, 153, 153, 0.42), 0 4px 23px 0px rgba(0, 0, 0, 0.12),
      }
    }
  }
  
}
</style>