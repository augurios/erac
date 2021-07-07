<template>
    <div>
        <md-card class="md-card-calendar">
          <md-card-content>
            <fullCalendar
              ref="calendar"
              defaultView="dayGridMonth"
              :plugins="calendarPlugins"
              :events="events"
              :header="header"
              :buttonIcons="buttonIcons"
              :selectHelper="true"
              :selectable="true"
              :locale="esLocale"
              @dateClick="castNewDate"
              @eventClick="eventClicked"
            />
          </md-card-content>
        </md-card>
         <EventModal 
            :active="editDate" 
            @acept="saveNewMeeting" 
            @close="editDate = false" 
            @delete="deleteMeeting"
            :meetingData="meetingData"
            :isEdit="true"/>
    </div>
</template>
<script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import timeGridPlugin from "@fullcalendar/timegrid";
import esLocale from '@fullcalendar/core/locales/es';
import EventModal from './modules/EventModal'

const getTwoDigits = (int) => {
  let stringInt = int.toString();
  if(stringInt.length < 2) {
    return `0${stringInt}`
  }
  return stringInt;
}

export default {
  components: {
    FullCalendar,
    EventModal
  },
  props: {
    caseDataProp: {
      type: Object,
      required: true,
    },
    caseMeetings: {
      required: true,
    }
  },
  data() {
    return {
      calendarPlugins: [dayGridPlugin, interactionPlugin, timeGridPlugin],
      esLocale,
      header: {
        center: "dayGridMonth,timeGridWeek,timeGridDay",
        right: "prev,next,today"
      },
      buttonIcons: {
        close: "fa-times",
        prev: "left-single-arrow",
        next: "right-single-arrow",
        prevYear: "fa-angle-double-left",
        nextYear: "fa-angle-double-right"
      },
      user,
      events: [],
      editDate: false,
      meetingData: {
        timeData: {
          hh: '01',
          mm: '00',
          ss: '00',
          a: 'pm'
        },
        selectedDate: new Date()
      }
    };
  },
  mounted() {
    this.setUpTimeline();
    
  },
  methods: {
    setUpTimeline() {
      this.events = [];
      this.events.push(this.buildEvent('Caso Abierto',this.caseDataProp.created_at,false,));

      if(this.caseDataProp.accepted) {
        this.events.push(this.buildEvent('Caso Aceptado',this.caseDataProp.dateAcepted,false,))
      }

      if(this.caseDataProp.counter_agreement) {
        this.events.push(this.buildEvent('Nueva Propuesta',this.caseDataProp.counter_date,false,))
      }

      if(this.caseDataProp.status == 'done') {
         this.events.push(this.buildEvent('Caso Terminado',this.caseDataProp.updated_at,false,))
      }

      this.caseMeetings.forEach(meeting => {
        const eventClass = user.role == 'mediator' ? 'meeting-event' : 'client-event';
        this.events.push(this.buildEvent('Reunion con Mediador',meeting.meeting_date,false,eventClass,meeting.id))
      });
    },
    buildEvent(title,start,allDay,className, id, end,url) {
      let event = {
        title,
        start,
        allDay
      }

      if(end) event.end = end;
      if(id) event.id = id;
      if(className) event.className = className;
      if(url) event.url = url;
      
      return event
    }, 
    castNewDate(date) {
      this.$emit('newdate', date);
    },
    eventClicked($event) {
      if($event.event.title === 'Reunion con Mediador' && this.user.role == 'mediator') {
        this.editDate = true;
        let ampm = $event.event.start.getHours() > 12 ? 'pm' : 'am';
        let hours = ampm == 'pm' ? $event.event.start.getHours() - 12 : $event.event.start.getHours();

        this.meetingData = {
          timeData: {
            hh: getTwoDigits(hours),
            mm: getTwoDigits($event.event.start.getMinutes()),
            ss: getTwoDigits($event.event.start.getSeconds()),
            a: ampm
          },
          selectedDate: $event.event.start,
          id:$event.event.id
        }
      }
    },
    saveNewMeeting() {
      let meetingData = this.meetingData;
      
      let hour = meetingData.timeData.a == 'pm' ? parseInt(meetingData.timeData.hh) + 12 : parseInt(meetingData.timeData.hh);

      meetingData.selectedDate.setHours(hour.toString(), meetingData.timeData.mm, meetingData.timeData.ss, '00');

      axios.put('meeting/' + meetingData.id,meetingData).then((response) => {
          let index = this.events.findIndex(event => event.id == response.data.meeting.id);
          this.events[index].start = response.data.meeting.meeting_date;
          this.editDate = false;
        }).catch();
    },
    deleteMeeting(meetingData) {
        axios.delete('meeting/' + meetingData.id,meetingData).then((response) => {
          let index = this.events.findIndex(event => event.id == meetingData.id);
          this.events.splice(index, 1);
          this.editDate = false;
        }).catch();
      }
  },
  watch: {
    caseMeetings() {
      this.setUpTimeline();
    }
  }
};
</script>
<style lang="scss">
.meeting-event { 
    cursor: pointer;
    box-shadow: 0 0 0;
    transition: all 0.4s ease;
    &:hover {
      box-shadow: 0 14px 26px -12px rgba(153, 153, 153, 0.42), 0 4px 23px 0px rgba(0, 0, 0, 0.12),
    }
  }
</style>
