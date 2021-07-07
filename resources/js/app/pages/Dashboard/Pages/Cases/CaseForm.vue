<template>
   <modal v-if="isActive" @close="close" class="new-class-modal-case">
      <template slot="header">
        <h4 class="modal-title">Abrir Nuevo Caso</h4>
        <md-button class="md-simple md-just-icon md-round modal-default-button" @click="close">
          <md-icon>clear</md-icon>
        </md-button>
      </template>

      <template slot="body">
        <form novalidate @submit.prevent="validateCaseAndSend">
              <div>
                  <p>Esta informacion nos ayudara a atender su caso mejor.</p>
              </div>
              <div class="md-layout">
                <div class="md-layout-item md-small-size-100 rich-text">
                  <md-field :class="getValidationClass('conflict')">
                      <label>Describa el conflicto</label>
                       <vue-editor id="editor" v-model="form.conflict" required :editorToolbar="customToolbar"> </vue-editor>

                      <span class="md-error" v-if="!$v.form.conflict.required">La descripcion del conflicto es requerida</span>
                      <span class="md-error" v-else-if="!$v.form.conflict.minlength">descripcion no valida</span>
                  </md-field>
                </div>
                <div class="md-layout-item md-small-size-100 rich-text">
                  <md-field :class="getValidationClass('solution')">
                      <label>Solucion propuesta por el solicitante para resolver el conflicto.</label>
                      <vue-editor id="editor-solution" v-model="form.solution" required :editorToolbar="customToolbar"> </vue-editor>

                      <span class="md-error" v-if="!$v.form.solution.required">La solucion para el conflicto es requerida</span>
                      <span class="md-error" v-else-if="!$v.form.solution.minlength">Solucion no valida</span>
                  </md-field>
                </div>
                
              </div>
              <div class="md-layout">
                <div class="md-layout-item md-small-size-100">
                    <md-field :class="getValidationClass('amount')">
                      <label for="cedula">Monto</label>
                      <md-input id="amount" name="amount" v-model.lazy="form.amount" v-money="money" autocomplete="cedula" :disabled="sending" required="required"/>
                      <span class="md-error" v-if="!$v.form.solution.required">El Monto es requerido</span>
                      <span class="md-error" v-else-if="!$v.form.solution.minlength">El Monto no es valido</span>
                    </md-field>
                </div>
              </div>
              <div class="case-specialty md-layout">
                <div class="md-layout-item md-size-100 md-small-size-100">
                    <md-field>
                      <label for="especialities">Mediador Assignado:</label>
                      <md-select
                        v-model="form.selectedEspeciality"
                        name="especialities"
                        id="especialities"
                      >
                          <md-option value="">Automatico</md-option>
                          
                          <md-option :value="0">Rodrigo Piedra ( especialidad de prueba )</md-option>

                      </md-select>
                    </md-field>
                  </div>
              </div>
          
              <md-progress-bar md-mode="indeterminate" v-if="sending" />
        </form>
      </template>

      <template slot="footer">
        <md-button class="md-danger md-simple" @click="close">Close</md-button>
        <md-button class="md-success" @click="saveCase">Abrir Caso</md-button>
      </template>
    </modal>
</template>

<script>
 import { validationMixin } from 'vuelidate'
  import {
    required,
    email,
    minLength,
    maxLength,
    numeric
  } from 'vuelidate/lib/validators'
import { Modal } from '../../../../components'
import IconCheckbox from "../../../../components/Inputs/IconCheckbox";
import { VueEditor } from "vue2-editor";
import {VMoney} from 'v-money'

export default {
  props: ['isActive','recipientData'],
  components: {
    Modal,
    IconCheckbox,
    VueEditor,
  },
  mixins: [validationMixin],
  data: () => ({
        money: {
          decimal: '.',
          thousands: ',',
          prefix: '₡ ',
          suffix: ' ',
          precision: 2,
          masked: false
        },
    form: {
        conflict: null,
        solution: null,
        amount: 0,
        selectedEspeciality: null,
    },
    customToolbar: [["bold", "italic", "underline"], [{ list: "ordered" }, { list: "bullet" }], ["link"]],
    sending: false,
    model: {
      design: false,
      code: false,
      develop: false,
    },
    availableEspecialities: [],
    
  }),
  validations: {
      form: {
        conflict: {
          required,
          minLength: minLength(8)
        },
        solution: {
          required,
          minLength: minLength(8)
        },
        amount: {
          required,
        }
      }
  },
  mounted() {
    this.getAllEspecs();
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
      saveCase () {
        this.$v.$touch()
        console.log('this.$v.$invalid',this.$v.$invalid);
        let messageRequest = {
          ...this.form,
          cedula: this.recipientData.cedula,
          initialData: JSON.stringify({
            ...this.recipientData
          })
        }
        messageRequest.amount = parseInt(messageRequest.amount.replace('₡ ','').replace(',',''));

        if (!this.$v.$invalid) {
          this.sending = true

          axios.post('/case', messageRequest).then((response) => {
             this.sending = false;
             this.$emit('newCase');
             this.close();
          })
          
        }
      },
      clearForm () {
        this.$v.$reset()
        this.form.conflict = null
        this.form.solution = null
        this.form.amount = null
      },
      close() {
        this.$emit('close');
        this.clearForm();
      },
      getAllEspecs() {
        axios.get('especs').then((response) => {
            this.availableEspecialities = response.data.especialties;
          }).catch();
      },
  },
  directives: {money: VMoney}
}
</script>

<style lang="scss">
.new-class-modal-case {
    .modal-container {
      max-width: 991px;
      max-height: 100vh;
      overflow-y: auto;
    }
    .quillWrapper {
      width: 100%;
      margin: 56px 0;
    }
    .rich-text {
      .md-field:not(.md-disabled):after {
        display: none;
      }
    }
}
.case-specialty{
  margin-top: 2rem;
 .choice {
    .icon {
        text-align: center;
        vertical-align: middle;
        height: 116px;
        width: 116px;
        border-radius: 50%;
        color: #999999;
        margin: 0 auto 20px;
        border: 4px solid #CCCCCC;
        transition: all 0.2s;
        -webkit-transition: all 0.2s;    
    }
    &.active {
      .icon {
            border-color: #2975BB;
            color: #2975BB;
        }
    }
    input[type=checkbox] {
        position: absolute;
        left: -10000px;
        z-index: -1;
    }
    i {
        font-size: 32px;
        line-height: 111px;
    }
    &:hover {
      .icon {
        border-color: #2ca8ff;
      }
    }
  }

}  
</style>