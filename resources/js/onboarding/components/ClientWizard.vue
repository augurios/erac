<template>
  <div class="md-layout">
    <div class="md-layout-item md-size-100 md-xsmall-size-80 mx-auto">
      <simple-wizard>
        <template slot="header">
          <h3 >Bienvenidos a Resolucion de conflictos en linea E-Rac</h3>
          <h5 class="category">
           Los Siguentes formularios le ayudaran a crear una cuenta, abrir un caso y enviar el mismo.
          </h5>
        </template>

        <wizard-tab :before-change="() => validateStep('step1')">
          <template slot="label">
            Personal
          </template>
          <first-step ref="step1" @on-validated="onStepValidated"></first-step>
        </wizard-tab>

        <wizard-tab :before-change="() => validateStep('step2')">
          <template slot="label">
            Caso
          </template>
          <second-step
            ref="step2"
            @on-validated="onStepValidated"
          ></second-step>
        </wizard-tab>

        <wizard-tab :before-change="() => validateStep('step3')">
          <template slot="label">
            Acusado
          </template>
          <third-step ref="step3" @on-validated="wizardComplete"></third-step>
        </wizard-tab>
      </simple-wizard>
    </div>
  </div>
</template>
<script>
import FirstStep from "./Wizard/FirstStep.vue";
import SecondStep from "./Wizard/SecondStep.vue";
import ThirdStep from "./Wizard/ThirdStep.vue";
import Swal from "sweetalert2";
import SimpleWizard from "./Wizard/comps/Wizard";
import WizardTab from "./Wizard/comps/WizardTab";

export default {
  data() {
    return {
      wizardModel: {},
    };
  },
  components: {
    FirstStep,
    SecondStep,
    ThirdStep,
    SimpleWizard,
    WizardTab
  },
  beforeMount() {
   
  },
  mounted() {
    
  },  
  methods: {
    validateStep(ref) {
      return this.$refs[ref].validate();
    },
    onStepValidated(validated, model) {
      this.wizardModel = { ...this.wizardModel, ...model };
    },
    wizardComplete() {
      Swal.fire({
        title: "Good job!",
        text: "You clicked the finish button!",
        type: "success",
        confirmButtonClass: "md-button md-success",
        buttonsStyling: false
      });
    }
  }
};
</script>
