<template>
  <div class="md-layout" v-show="caseData.id">
    <div class="md-layout-item md-medium-size-50 md-small-size-100 md-size-50">
      <CaseInfoCard :caseDataProp="caseData" @updated="updated" @updatedEvent="updatedEvent" :eventBus="EventBus" @success="getDocuments"/>
      <CaseChatCard v-if="caseData.id && user.role != 'mediator' && caseData.status == 'active'" :caseDataProp="caseData" @updated="getCase"/>
      <CaseTabChatCard v-if="caseData.id && user.role == 'mediator' && caseData.status == 'active'" :caseDataProp="caseData" @updated="getCase"></CaseTabChatCard>
      <CaseDocuments v-if="caseData.status == 'done'" 
                    :documents="caseDocuments" 
                    :parts="{mediator: caseData.mediator, opener: caseData.opener, recipient: caseData.recipient}" 
                    @success="getDocuments">
      </CaseDocuments>
    </div>
    <div class="md-layout-item md-medium-size-50 md-small-size-100 md-size-50">
      <tabs
        :tab-name="['Historial', 'Agenda']"
        color-button="primary"
        :plain="true"
        class="case-tabs"
        @switchPanel="handleTapChange"
      >
       <template slot="tab-pane-1">
          <TimeLine v-if="caseData.id" :caseDataProp="caseData" :caseMeetings="caseMeetings"/>
       </template>
       <template slot="tab-pane-2">
          <CaseAgenda :caseDataProp="caseData" @newdate="castNewDate" :caseMeetings="caseMeetings" />
       </template>
      </tabs>
    </div>
  </div>
</template>

<script>
import CaseInfoCard from "./CaseViewer/CaseInfoCard";
import CaseChatCard from "./CaseViewer/CaseChatCard";
import CaseTabChatCard from "./CaseViewer/CaseTabChatCard";
import TimeLine from "./CaseViewer/TimeLine";
import CaseAgenda from "./CaseViewer/CaseAgenda";
import CaseDocuments from "./CaseViewer/CaseDocuments";
import { Tabs } from "../../../components";

export default {
  components: {
    CaseInfoCard,
    CaseChatCard,
    CaseTabChatCard,
    TimeLine,
    Tabs,
    CaseAgenda,
    CaseDocuments
  },
  data() {
    return {
      caseData: {},
      caseMeetings: [],
      caseDocuments: [],
      user,
      curentTab: null,
      EventBus: new Vue()
    }
  },
  mounted(){
    this.getCase();
  },
  methods: {
    getCase() {
      axios.get('case/' + this.$route.params.id).then((response) => {
          this.caseData = response.data.case;
          this.getMeetings();
          if(this.caseData.status == 'done') {
            this.getDocuments();
          }
        }).catch();
    },
    getMeetings() {
      axios.get('meetings/' + this.caseData.id).then((response) => {
          this.caseMeetings = response.data.meetings;
        }).catch();
    },
    getDocuments() {
      axios.get('api/documents/' + this.caseData.id + '?api_token=' + user.api_token).then((response) => {
          this.caseDocuments = response.data.documents;
        }).catch();
    },
    updated(newCase) {
      this.caseData = newCase;
    },
    handleTapChange(newTab) {
      this.curentTab = newTab;
    },
    castNewDate(date) {
      this.EventBus.$emit('newdate', date);
    },
    updatedEvent(meeting) {
      this.caseMeetings.push(meeting)
    }
  },
}
</script>

<style lang="scss">
.case-tabs {
  margin-top: 0;
  .md-list.nav-tabs.md-theme-default {
    justify-content: right;
    padding: 0 7px;
  }

  .md-card {
    margin: 0;
  }
  
  .timeline {
    margin-top: 0;
  }
}

</style>