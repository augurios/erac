<template>
  <div class="md-layout">
    <div class="md-layout-item md-size-66 md-xsmall-size-80 mx-auto">
      <simple-wizard>
        <template slot="header">
          <md-avatar class="md-large">
            <img :src="companyProfile.avatar" alt="People">
          </md-avatar>
          <h3 class="title">{{companyProfile.name}} </h3>
          <h5 class="category">
           Queremos ayudarte con tu disputa, mediante el siguiente formulario.
          </h5>
        </template>

        <wizard-tab :before-change="() => validateStep('step1')">
          <template slot="label">
            About
          </template>
          <first-step ref="step1" @on-validated="onStepValidated"></first-step>
        </wizard-tab>

        <wizard-tab :before-change="() => validateStep('step2')">
          <template slot="label">
            Account
          </template>
          <second-step
            ref="step2"
            @on-validated="onStepValidated"
          ></second-step>
        </wizard-tab>

        <wizard-tab :before-change="() => validateStep('step3')">
          <template slot="label">
            Address
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
import { SimpleWizard, WizardTab } from "../../app/components";

export default {
  data() {
    return {
      wizardModel: {},
      companyCid: null,
      companyProfile: {}
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
    this.companyCid = new URL(location.href).searchParams.get('cid');
    axios.get('users/public/' + this.companyCid).then((response) => {
          this.companyProfile = response.data.message;
        }).catch();
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
