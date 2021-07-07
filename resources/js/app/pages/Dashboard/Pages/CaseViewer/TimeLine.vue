<template>
  <time-line plain :type="simpleTimeline" class="case-timeline">
        <time-line-item 
            inverted 
            v-for="item in timeLine"
            :key="item.id"
            :badge-type="item.type" :badge-icon="item.icon">
          <badge slot="header" :type="item.type">{{item.title}}</badge>
          <div slot="content" v-html="item.content">
          </div>

          <h6 slot="footer">
            <i class="ti-time"></i>
            {{ item.date | moment("from") }}
          </h6>
        </time-line-item>

        
      </time-line>
</template>

<script>
import { TimeLine, TimeLineItem, Badge } from "../../../../components";
export default {
  components: {
    TimeLine,
    TimeLineItem,
    Badge
  },
  props: {
    caseDataProp: {
      type: Object,
      required: true,
    },
    caseMeetings: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      responsive: false,
      caseData: {},
      timeLine: []
    };
  },
  computed: {
    simpleTimeline() {
      if (this.responsive) {
        return "simple";
      }
      return "";
    }
  },
  watch: {
    caseDataProp() {
      this.caseData = this.caseDataProp;
      this.setUpTimeline();
    },
    caseMeetings() {
      this.setUpTimeline();
    }
  },
  methods: {
    onResponsiveInverted() {
      if (window.innerWidth < 768) {
        this.responsive = true;
      } else {
        this.responsive = false;
      }
    },
    setUpTimeline() {
      let timeLineObj = [{
        type: 'danger',
        icon: 'card_travel',
        title: 'Caso Abierto',
        content: `${this.caseData.agreement} Propuesta por <strong>${this.caseData.opener.name} ${this.caseData.opener.lastname}</strong>`,
        date: this.caseData.created_at,
        id: 1
      }]

      if(this.caseData.accepted) {
        timeLineObj.push({
          type: 'success',
          icon: 'done_outline',
          title: 'Caso Aceptado',
          content:  `<strong>${this.caseData.recipient.name} ${this.caseData.recipient.lastname}</strong> ha aceptado participar en esta mediacion y se a unido a la misma`,
          date: this.caseData.dateAcepted,
          id:2
        })
      }

      if(this.caseData.counter_agreement) {
        timeLineObj.push({
          type: 'info',
          icon: 'rule_folder',
          title: 'Nueva Propuesta',
          content:  `${this.caseData.counter_agreement} Propuesta por <strong>${this.caseData.recipient.name} ${this.caseData.recipient.lastname}</strong>`,
          date: this.caseData.counter_date,
          id:3
        })
      }

      if(this.caseData.counter_agreement && !this.caseData.inverted && !this.caseMeetings.length) {
        timeLineObj.push({
          type: 'warning',
          icon: 'record_voice_over',
          title: 'Reunion Solicitada',
          content:  `<strong>${this.caseData.opener.name} ${this.caseData.opener.lastname}</strong> A solicitado una reunión con el ó la Mediador(a) para discutir el conflicto entre las 2 propuestas.`,
          date: this.caseData.updated_at,
          id: 4
        })
      } else if(this.caseMeetings.length) {
        timeLineObj.push({
          type: 'info',
          icon: 'event',
          title: 'Reunion Agendada',
          content:  `<strong>${this.caseData.mediator.name} ${this.caseData.mediator.lastname}</strong> A agendado una reunion para el <strong>${this.$moment(new Date(this.caseMeetings[this.caseMeetings.length - 1].meeting_date)).format("dddd D, MMMM, YYYY")}</strong> para resolver el conflicto entre las 2 partes.`,
          date: this.caseMeetings[this.caseMeetings.length - 1].created_at,
          id: 4
        })
      }

      if(this.caseData.status == 'done') {
        timeLineObj.push({
          type: 'success',
          icon: 'verified',
          title: 'Caso Terminado',
          content: this.caseData.resolution == 'acepted' ? `${this.caseData.recipient.name} ha aceptado los terminos del caso` : `${this.caseData.opener.name} ha cancelado el caso`,
          date: this.caseData.updated_at,
          id:5
        })
      }

      this.timeLine = timeLineObj;
    }
  },
  beforeMount() {
    this.onResponsiveInverted();
    window.addEventListener("resize", this.onResponsiveInverted);
    this.caseData = this.caseDataProp;
  },
  mounted() {
    this.setUpTimeline();
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.onResponsiveInverted);
  }
};
</script>

<style lang="scss">
.tab-pane-1 {
      width: 100%;
}
.case-timeline{
  .timeline {
    &:before {
    left: 3%;
    }
  }
  .timeline > li > .timeline-panel {
        width: 92%;
  }
  .timeline > li > .timeline-badge {
      left: 3.1%;
  }
}
</style>