<template>
  <ValidationObserver ref="form">
    <form @submit.prevent="validate">
      <div>
        <h5 class="info-text">
          Comencemos con su informacion personal, necesaria para la creacion de la cuenta y para el expediente en el caso. 
        </h5>
        <div class="md-layout">
          
          <div class="md-layout-item md-size-50 mt-4 md-small-size-100">
            <ValidationProvider
              name="firstName"
              rules="required"
              v-slot="{ passed, failed }"
            >
              <md-field
                :class="[
                  { 'md-error': failed },
                  { 'md-valid': passed },
                  { 'md-form-group': true }
                ]"
              >
                <md-icon>face</md-icon>
                <label>Nombre</label>
                <md-input v-model="firstName" type="text"> </md-input>

                <slide-y-down-transition>
                  <md-icon class="error" v-show="failed">close</md-icon>
                </slide-y-down-transition>
                <slide-y-down-transition>
                  <md-icon class="success" v-show="passed">done</md-icon>
                </slide-y-down-transition>
              </md-field>
            </ValidationProvider>

            <ValidationProvider
              name="lastName"
              rules="required"
              v-slot="{ passed, failed }"
            >
              <md-field
                :class="[
                  { 'md-error': failed },
                  { 'md-valid': passed },
                  { 'md-form-group': true }
                ]"
              >
                <md-icon>face</md-icon>
                <label>Apellidos</label>
                <md-input v-model="lastName" type="text"> </md-input>

                <slide-y-down-transition>
                  <md-icon class="error" v-show="failed">close</md-icon>
                </slide-y-down-transition>
                <slide-y-down-transition>
                  <md-icon class="success" v-show="passed">done</md-icon>
                </slide-y-down-transition>
              </md-field>
            </ValidationProvider>
            
          </div>

          <div class="md-layout-item md-size-50 mt-4 md-small-size-100">
             <ValidationProvider name="idNumber" rules="required" v-slot="{ passed, failed }">
              <md-field
                :class="[
                  { 'md-error': failed },
                  { 'md-valid': passed },
                  { 'md-form-group': true }
                ]"
              >
                <md-icon>featured_video</md-icon>
                <label>Cedula</label>
                <md-input v-model="idNumber" type="text"> </md-input>

                <slide-y-down-transition>
                  <md-icon class="error" v-show="failed">close</md-icon>
                </slide-y-down-transition>
                <slide-y-down-transition>
                  <md-icon class="success" v-show="passed">done</md-icon>
                </slide-y-down-transition>
              </md-field>
            </ValidationProvider>
            

            <ValidationProvider
              name="Telefono"
              rules="required"
              v-slot="{ passed, failed }"
            >
              <md-field
                :class="[
                  { 'md-error': failed },
                  { 'md-valid': passed },
                  { 'md-form-group': true }
                ]"
              >
                <md-icon>phone</md-icon>
                <label>Telefono</label>
                <md-input v-model="phoneNumber" type="number"> </md-input>

                <slide-y-down-transition>
                  <md-icon class="error" v-show="failed">close</md-icon>
                </slide-y-down-transition>
                <slide-y-down-transition>
                  <md-icon class="success" v-show="passed">done</md-icon>
                </slide-y-down-transition>
              </md-field>
            </ValidationProvider>
          </div>
          <div class="md-layout-item md-size-100 ml-auto mt-4 md-small-size-100">
            <h5 class="info-text">
              La siguiente sera la informacion de acceso a tu cuenta E-Rac &copy;
            </h5>
          </div>

          <div class="md-layout-item md-size-100 ml-auto mt-4 md-small-size-100">
            <ValidationProvider
              name="email"
              rules="required|email"
              v-slot="{ passed, failed }"
            >
              <md-field
                :class="[
                  { 'md-error': failed },
                  { 'md-valid': passed },
                  { 'md-form-group': true }
                ]"
              >
                <md-icon>email</md-icon>
                <label>Email</label>
                <md-input v-model="email" type="text"> </md-input>

                <slide-y-down-transition>
                  <md-icon class="error" v-show="failed">close</md-icon>
                </slide-y-down-transition>
                <slide-y-down-transition>
                  <md-icon class="success" v-show="passed">done</md-icon>
                </slide-y-down-transition>
              </md-field>
            </ValidationProvider>
          </div>


          <div class="md-layout-item md-size-50 mt-4 md-small-size-100">
            <ValidationProvider name="newPassword" rules="required" v-slot="{ passed, failed }">
              <md-field
                :class="[
                  { 'md-error': failed },
                  { 'md-valid': passed },
                  { 'md-form-group': true }
                ]"
              >
                <md-icon>lock_outline</md-icon>
                <label>Contraseña</label>
                <md-input v-model="newPassword" type="password"> </md-input>

                <slide-y-down-transition>
                  <md-icon class="error" v-show="failed">close</md-icon>
                </slide-y-down-transition>
                <slide-y-down-transition>
                  <md-icon class="success" v-show="passed">done</md-icon>
                </slide-y-down-transition>
              </md-field>
            </ValidationProvider>
          </div>

          <div class="md-layout-item md-size-50 mt-4 md-small-size-100">
            <ValidationProvider name="confirmPassword" rules="required" v-slot="{ passed, failed }">
              <md-field
                :class="[
                  { 'md-error': failed },
                  { 'md-valid': passed },
                  { 'md-form-group': true }
                ]"
              >
                <md-icon>lock_outline</md-icon>
                <label>Confirmar Contraseña</label>
                <md-input v-model="confirmPassword" type="password"> </md-input>

                <slide-y-down-transition>
                  <md-icon class="error" v-show="failed">close</md-icon>
                </slide-y-down-transition>
                <slide-y-down-transition>
                  <md-icon class="success" v-show="passed">done</md-icon>
                </slide-y-down-transition>
              </md-field>
            </ValidationProvider>
          </div>

          <div class="md-layout-item md-size-100 ml-auto mt-4 md-small-size-100">
            <ValidationProvider name="confirmPassword" rules="required" v-slot="{ passed, failed }">
              <md-field
                :class="[
                  { 'md-error': failed },
                  { 'md-valid': passed },
                ]"
              >
                
                <md-checkbox v-model="array" value="1"> Estoy de acuerdo con los <a href="#something">Terminos y condiciones</a>.</md-checkbox>

                <slide-y-down-transition>
                  <md-icon class="error" v-show="failed">close</md-icon>
                </slide-y-down-transition>
                <slide-y-down-transition>
                  <md-icon class="success" v-show="passed">done</md-icon>
                </slide-y-down-transition>
              </md-field>
            </ValidationProvider>
          </div>

        </div>
      </div>
    </form>
  </ValidationObserver>
</template>
<script>
import { SlideYDownTransition } from "vue2-transitions";
import { extend } from "vee-validate";
import { required, email } from "vee-validate/dist/rules";

extend("required", required);
extend("email", email);

export default {
  components: {
    SlideYDownTransition
  },
  props: {
    avatar: {
      type: String,
      default: "./img/default-avatar.png"
    }
  },
  data() {
    return {
      image: "",
      firstName: "",
      lastName: "",
      phoneNumber: "",
      email: "",
      idNumber: ""
    };
  },
  mounted() {
    console.log('file'), this.model;
  },
  methods: {
    handlePreview(file) {
      this.model.imageUrl = URL.createObjectURL(file.raw);
    },
    onFileChange(e) {
      var files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      this.createImage(files[0]);
    },
    validate() {
      return this.$refs.form.validate().then(res => {
        this.$emit("on-validated", res);
        return res;
      });
    },
    createImage(file) {
      var reader = new FileReader();
      var vm = this;

      reader.onload = e => {
        vm.image = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  }
};
</script>
<style></style>
