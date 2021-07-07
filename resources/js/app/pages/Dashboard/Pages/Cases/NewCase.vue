<template>
  <div>
    <modal v-if="addNewActive" @close="closeStepOne" class="new-class-modal">
      <template slot="header">
        <h4 class="modal-title">Abrir Nuevo Caso</h4>
        <md-button class="md-simple md-just-icon md-round modal-default-button" @click="closeStepOne">
          <md-icon>clear</md-icon>
        </md-button>
      </template>

      <template slot="body">
        <form novalidate class="md-layout" @submit.prevent="validateUser">
              <div>
                  <p>Utilize esta herramienta para abrir nuevos casos, inserte la informacion de la persona o entidad empresarial con quien desea mediar.</p>
              </div>
              <div class="md-layout md-gutter">
                <div class="md-layout-item md-small-size-100">
                  <md-field :class="getValidationClass('name')">
                    <label for="first-name">Nombre</label>
                    <md-input name="first-name" id="first-name" autocomplete="given-name" v-model="form.name" :disabled="sending" />
                    <span class="md-error" v-if="!$v.form.name.required">El primer nombre es requerido</span>
                    <span class="md-error" v-else-if="!$v.form.name.minlength">Nombre invalido</span>
                  </md-field>
                </div>

                <div class="md-layout-item md-small-size-100">
                  <md-field :class="getValidationClass('lastname')">
                    <label for="last-name">Apellidos</label>
                    <md-input name="last-name" id="last-name" autocomplete="family-name" v-model="form.lastname" :disabled="sending" />
                    <span class="md-error" v-if="!$v.form.lastname.required">Los Apellidos son requeridos</span>
                    <span class="md-error" v-else-if="!$v.form.lastname.minlength">Apellidos invalidos</span>
                  </md-field>
                </div>
              </div>

              <div class="md-layout md-gutter">
                <div class="md-layout-item md-small-size-100">
                  <md-field :class="getValidationClass('cedula')">
                    <label for="cedula">Cedula</label>
                    <md-input type="number" id="cedula" name="cedula" autocomplete="cedula" v-model="form.cedula" :disabled="sending" />
                    <span class="md-error" v-if="!$v.form.cedula.required">La Cedula es requerida</span>
                    <span class="md-error" v-else-if="!$v.form.cedula.minlength">cedula invalida</span>
                  </md-field>
                </div>

                <div class="md-layout-item md-small-size-100">
                  <md-field :class="getValidationClass('phone')">
                    <label for="phone">Telefono</label>
                    <md-input type="number" id="phone" name="phone" autocomplete="phone" v-model="form.phone" :disabled="sending" />
                    <span class="md-error" v-if="!$v.form.phone.minlength">Telefono no valido</span>
                  </md-field>
                </div>
              </div>

              <md-field :class="getValidationClass('email')">
                <label for="email">Email</label>
                <md-input type="email" name="email" id="email" autocomplete="email" v-model="form.email" :disabled="sending" />
                <span class="md-error" v-if="!$v.form.email.required">El correo es requerido</span>
                <span class="md-error" v-else-if="!$v.form.email.email">Correo invalido</span>
              </md-field>
        </form>
      </template>

      <template slot="footer">
        <md-button class="md-danger md-simple" @click="closeStepOne">Close</md-button>
        <md-button class="md-success" @click="validateUser">Preparar Caso</md-button>
      </template>
    </modal>
    <!-- case modal -->
   <CaseForm v-if="stepTwoActive" :isActive="stepTwoActive" @close="closeStepTwo" :recipientData="form" @newCase="newCase" />
  </div>
</template>

<script>
 import { validationMixin } from 'vuelidate'
  import {
    required,
    email,
    minLength,
    maxLength
  } from 'vuelidate/lib/validators'
import { Modal } from '../../../../components'
import CaseForm from './CaseForm'

export default {
  components: {
    Modal,
    CaseForm
  },
  props: ['addNewActive'],
  mixins: [validationMixin],
  data: () => ({
      form: {
        name: null,
        lastname: null,
        phone: null,
        cedula: null,
        email: null,
      },
      sending: false,
      lastUser: null,
      stepTwoActive: false
    }),
    validations: {
      form: {
        name: {
          required,
          minLength: minLength(3)
        },
        lastname: {
          required,
          minLength: minLength(3)
        },
        phone: {
          required,
          minLength: maxLength(9)
        },
        cedula: {
          minLength: minLength(9),
          required
        },
        email: {
          required,
          email
        },
        
      },
      
    },
    methods: {
      getValidationClass (fieldName) {
        const field = this.$v.form[fieldName]

        if (field) {
          return {
            'md-invalid': field.$invalid && field.$dirty
          }
        }
      },
      clearForm () {
        this.$v.$reset()
        this.form.name = null
        this.form.lastname = null
        this.form.phone = null
        this.form.cedula = null
        this.form.email = null
      },
      
      validateUser ($event) {
        
        // $event.preventDefault();
        this.$v.$touch()
        console.log('this.$v.$invalid',this.$v.$invalid);
        if (!this.$v.$invalid) {
          this.hideStepOne();
          this.goToStepTwo();
        }
      },
      goToStepTwo() {
        this.stepTwoActive = true;
      },
      closeStepOne() {
        this.$emit('close');
        this.clearForm()
      },
      hideStepOne() {
        this.$emit('close');
      },
      closeStepTwo() {
        this.stepTwoActive = false;
        this.clearForm()
      },
      newCase() {
        this.$emit('newCase');
        this.closeStepOne();
      }
    }
}
</script>
<style lang="scss">
  .new-class-modal .md-progress-bar {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
  }
  .md-menu-content.md-select-menu {
    z-index: 9998;
  }
</style>