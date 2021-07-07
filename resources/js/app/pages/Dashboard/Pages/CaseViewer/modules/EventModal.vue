<template>
  <modal v-if="active" class="meeting-modal">
          <template slot="header">
            <h3 class="modal-title">{{isEdit ? 'Editar': 'Agendar'}} Reunión</h3>
            <md-button class="md-simple md-just-icon md-round modal-default-button" @click="$emit('close')">
              <md-icon>clear</md-icon>
            </md-button>
          </template>

          <template slot="body">
            <p>Seleccione la fecha y hora para la {{isEdit ? 'nueva reunión': 'reunión'}}</p>
            <div class="md-layout">
              <div class="md-layout-item">
                <md-datepicker v-model="meetingData.selectedDate" :md-disabled-dates="disabledDates" />
              </div>
              <div class="md-layout-item" style="margin-top: 14px;">
                  <md-icon class="hour-icon">schedule</md-icon>
                  <vue-timepicker :format="timeFormat" v-model="meetingData.timeData"></vue-timepicker>
              </div>
            </div>
          </template>

          <template slot="footer">
            <md-button class="md-danger" @click="$emit('delete',meetingData)" v-if="isEdit">Borrar</md-button>
            <md-button class="md-simple md-danger" @click="$emit('close')">Cancelar</md-button>
            
            <md-button class="md-success" @click="$emit('acept')">Agendar</md-button>
          </template>
        </modal>
</template>

<script>
import { Badge, Modal } from '../../../../../components';
import VueTimepicker from 'vue2-timepicker/src/vue-timepicker.vue';
export default {
  components: {
    Modal,
    VueTimepicker,
  },
  props: ['active', 'meetingData', 'isEdit'],
  data() {
    return {
      timeFormat: 'hh:mm:ss a',
      disabledDates: date => {
          var now = new Date();
          now.setHours(0,0,0,0);
          if (date > now) {
            return false
          } 
          return true;
      },
    }
  }
}
</script>

<style>

</style>