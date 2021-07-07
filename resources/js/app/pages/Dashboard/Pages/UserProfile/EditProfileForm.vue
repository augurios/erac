<template>
  <form @submit.prevent="update" ref="form" action="#" method="post" v-on:submit.prevent>
    <md-card>
      <md-card-header
        class="md-card-header-icon"
        :class="getClass(headerColor)"
      >
        <div class="card-icon">
          <md-icon>perm_identity</md-icon>
        </div>
        <h4 class="title">
          Perfil - <small>Aquí puede actualizar su información de cuenta</small>
        </h4>
      </md-card-header>

      <md-card-content>
        <div class="md-layout">
          <div class="md-layout-item md-small-size-100 md-size-33" v-if="userInfo.role === 'admin' || userInfo.role === 'super-admin'">
            <md-field>
              <label></label>
                <label for="role">Tipo de Cuenta</label>
                <md-select v-model="userInfo.role" name="role" id="role">
                  <md-option value="super-admin">super-admin</md-option>
                  <md-option value="admin">admin</md-option>
                  <md-option value="mediator">mediator</md-option>
                  <md-option value="company">company</md-option>
                  <md-option value="client">client</md-option>
                </md-select>
              </md-field>
          </div>

          <div class="md-layout-item md-small-size-100 md-size-33">
            <md-field>
              <label>Nombre</label>
              <md-input v-model="userInfo.name" type="text" required></md-input>
            </md-field>
          </div>

           <div class="md-layout-item md-small-size-100 md-size-33">
            <md-field>
              <label>Apellidos</label>
              <md-input v-model="userInfo.lastname" type="text" required></md-input>
            </md-field>
          </div>

          <div class="md-layout-item md-small-size-100 md-size-33">
            <md-field>
              <label>Email Address</label>
              <md-input v-model="userInfo.email" type="email" :disabled="userInfo.role != 'super-admin' && userInfo.role != 'admin'" required></md-input>
            </md-field>
          </div>
         
          <div class="md-layout-item md-small-size-100 md-size-33">
            <md-field>
              <label>Telefono</label>
              <md-input v-model="userInfo.phone" type="phone" required></md-input>
            </md-field>
          </div>
          
          <!-- <div class="md-layout-item md-small-size-100 md-size-33">
            <md-field>
              <label>Cedula</label>
              <md-input v-model="userInfo.cedula" type="text" disabled></md-input>
            </md-field>
          </div> -->

          <div class="md-layout-item md-small-size-100 md-size-100">
            <md-field>
              <label>Biografia</label>
              <md-textarea v-model="userInfo.aboutme"></md-textarea>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-100" v-if="userInfo.role === 'mediator'">
                  <md-field>
                    <label for="especialities">Agregar Especialidad</label>
                    <md-select
                      v-model="profile.especialties"
                      name="especialities"
                      id="especialities"
                    >
                        <md-option value="">Ninguna</md-option>
                        
                        <md-option v-for="espec in availableEspecialities" :key="espec.id" :value="espec.id">{{ espec.title }}</md-option>
                    </md-select>
                  </md-field>
          </div>          
          <div class="md-layout-item md-size-100 text-right">
            <md-button class="md-raised md-primary mt-4" type="submit">Actualizar Perfil</md-button>
          </div>
          
        </div>
      </md-card-content>
    </md-card>
  </form>
</template>
<script>
export default {
  name: "edit-profile-form",
  props: {
    headerColor: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      userInfo: { ...user},
      availableEspecialities: [],
      profile: {
        especialties:''
      }
    };
  },
  mounted() {
    this.getAllEspecs();
  },
  methods: {
    getClass: function(headerColor) {
      return "md-card-header-" + headerColor + "";
    },
    update (event) {
      event.preventDefault();
      let processData = this.userInfo
          processData.userProfile = this.profile

      axios.put('/users/' + this.userInfo.id + '/profile',processData )
        .then((response) => {
          user = this.userInfo;
          this.$eventBus.$emit('userUpdate',user);
          this.notif(response.data.message);
        })
        .catch((error) => {
          
          let msg = Object.keys(error.response.data.errors)[0];
          this.notif(error.response.data.errors[msg][0], 'danger');
        })
    },
    getAllEspecs() {
      axios.get('especs').then((response) => {
         this.availableEspecialities = response.data.especialties;
      }).catch();

      axios.get('profile/' + this.userInfo.cedula).then((response) => {
         if(response.data.Profile) this.profile = response.data.Profile;
      }).catch();
    },
  }
};
</script>
<style></style>
