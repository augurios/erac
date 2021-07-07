<template>
    <modal v-if="infoModal" @close="close">
          <template slot="header">
            <h4 class="modal-title">Informacion de usuario</h4>
            <md-button class="md-simple md-just-icon md-round modal-default-button" @click="close">
              <md-icon>clear</md-icon>
            </md-button>
          </template>

          <template slot="body">
            <md-avatar class="md-large">
              <img :src="infoModal.avatar ? './storage/'+infoModal.avatar : './img/faces/avatar.jpg'" alt="Avatar">
            </md-avatar>
            <div class="md-layout-item md-layout md-gutter">
              <div class="md-layout-item">
                 <dl>
                  <dt>Nombre:</dt>
                  <dd><strong>{{`${infoModal.name} ${infoModal.lastname}`}}</strong></dd>
                  <dt>Cedula:</dt>
                  <dd><strong>{{infoModal.cedula}}</strong></dd>
                </dl>
              </div>
              <div class="md-layout-item">
                 <dl>
                    <dt>Telefono:</dt> 
                    <dd><strong>{{infoModal.phone}}</strong></dd>
                    <dt>Correo Electronico:</dt> 
                    <dd><strong>{{infoModal.email}}</strong></dd>
                  </dl>
              </div>
            </div>
                           
             <h6>Bio:</h6>
             <div v-html="infoModal.aboutme" class="bio"></div>
          </template>

          <template slot="footer">
            <md-button class="md-info md-round" @click="close">Cerrar</md-button>
          </template>
    </modal>
</template>

<script>
import { Modal } from '../../../../components'
export default {
  components: {
    Modal
  },
  props: ['infoModal'],
  methods: {
    close() {
      this.$emit('close');
    }
  }
}
</script>

<style>

</style>