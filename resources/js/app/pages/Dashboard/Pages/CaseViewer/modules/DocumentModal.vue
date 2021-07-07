<template>
  <modal v-if="documentEditor" class="edit-modal">
    <template slot="header">
      <h3 class="modal-title">Crear Documento</h3>
      <md-button class="md-simple md-just-icon md-round modal-default-button" @click="$emit('close')">
        <md-icon>clear</md-icon>
      </md-button>
    </template>

    <template slot="body">
      <h5>Crear Documento ligado al caso</h5>
      <!-- template selector -->
      <md-field>
        <label for="templates">Plantilla:</label>
        <md-select v-model="selectedTemplate" name="templates" id="templates" required >
          <md-option v-for="template in availableTemplates" :key="template.templateId" :value="template.templateId" >{{template.title}}</md-option>
        </md-select>
      </md-field>

      <vue-editor id="editor" v-model="editingDocument"> </vue-editor>
    </template>

    <template slot="footer" v-if="!isLoading">
      <md-button class="md-success" @click="attachFile" v-if="isEditing && isEditing.status == 'draft'" :disabled="!selectedTemplate.length">Adjuntar documento al caso</md-button>
      <md-button class="md-simple md-success" @click="saveFile" :disabled="!selectedTemplate.length">Salvar Cambios</md-button>
      <md-button class="md-danger md-simple" @click="$emit('close')">Cancelar</md-button>
    </template>
    <template slot="footer" v-else>
      <md-progress-spinner class="md-primary" :md-diameter="30" :md-stroke="3" md-mode="indeterminate"></md-progress-spinner>
    </template>
  </modal>
</template>

<script>
import { Badge, Modal } from '../../../../../components';
import { VueEditor } from "vue2-editor";
// import HelloSign from 'hellosign-embedded';

export default {
  components: {
    Modal,
    VueEditor,
  },
  props: ['caseData', 'documentEditor', 'isEditing'],
  data() {
    return {
      editingDocument: null,
      isLoading: false,
      selectedTemplate: '',
      availableTemplates: []
    }
  },
  mounted() {
    this.getTemplates();
  },
  methods: {
    attachFile() {
      this.isLoading = true;
      const attachPayload = {
        ...this.caseData,
        templateId : this.selectedTemplate
      }
      axios.post(`api/document/${this.isEditing.id}/attach?api_token=${user.api_token}`, attachPayload).then((response) => {
        this.isLoading = false;

        this.$emit('close');
        this.$emit('success');
        }).catch(() => {
          this.isLoading = false;
        });
    },
    getTemplates() {
      axios.get(`api/document/templates?api_token=${user.api_token}`,{}).then((response) => {
            this.availableTemplates = response.data.template;
            this.selectedTemplate = this.editingDocument.template_id;
        }).catch(() => {
          console.log('Error loading templates!');
        });
    },
    saveFile() {
      let documentData = {
        content: this.editingDocument,
        caseId: this.caseData.id,
        templateId : this.selectedTemplate,
      }
      this.isLoading = true;
      return this.isEditing ? this.editFile(documentData) : this.newFile(documentData);
    },
    newFile(documentData) {
      axios.post('api/document?api_token=' + user.api_token,documentData).then((response) => {
          this.isLoading = false;
          this.$emit('close');
          this.$emit('success');
        }).catch(() => {
          this.isLoading = false;
        });
    },
    editFile(documentData) {
      axios.put(`api/document/${this.isEditing.id}?api_token=${user.api_token}`,documentData).then((response) => {
          this.isLoading = false;
          this.$emit('close');
          this.$emit('success');
        }).catch(() => {
          this.isLoading = false;
        });
    },
  },
  watch: {
    documentEditor(val) {
      if(val) {
        this.editingDocument = this.caseData.counter_agreement || this.caseData.agreement;
        this.selectedTemplate = this.isEditing ? this.isEditing.template_id : '';
      }
    }
  }
}
</script>

<style>

</style>