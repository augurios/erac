<template>
<div>
  <nav-tabs-card>
        <template slot="content">
          <span class="md-nav-tabs-title">Docs:</span>
          <md-tabs class="md-primary" md-alignment="left">
            <md-tab id="tab-docs" md-label="Documentos" md-icon="attach_email">
              <md-table v-model="documents" v-if="documents.length" >
                <md-table-row
                  slot="md-table-row"
                  slot-scope="{ item }"
                  md-auto-select
                >
                  <md-table-cell> <strong>{{ item.created_at | moment("DD-MM-YYYY") }}</strong> </md-table-cell>
                  <md-table-cell>
                    <md-button class="md-just-icon md-simple md-primary" v-if="item.status != 'draft'" @click="downloadFile(item.id)">
                      <md-icon>cloud_download</md-icon>
                      <md-tooltip md-direction="top">Descargar</md-tooltip>
                    </md-button>
                    <md-button class="md-just-icon md-simple md-primary" v-if="item.status == 'draft' && user.role == 'mediator'" @click="editDoc(item)">
                      <md-icon>edit</md-icon>
                      <md-tooltip md-direction="top">Editar</md-tooltip>
                    </md-button>
                    <md-button class="md-just-icon md-simple md-primary" v-if="item.status == 'attached' && !isSigned(item)" @click="signDoc(item)">
                      <md-icon>edit</md-icon>
                      <md-tooltip md-direction="top">Firmar</md-tooltip>
                    </md-button>
                    <md-button class="md-just-icon md-simple md-danger" v-if="item.status == 'draft' && user.role == 'mediator'" @click="confirmDel(item)">
                      <md-icon>close</md-icon>
                      <md-tooltip md-direction="top" >Borrar</md-tooltip>
                    </md-button>
                  </md-table-cell>
                </md-table-row>
              </md-table>
              <p v-else>No hay ningun documento</p>
            </md-tab>

            <md-tab id="tab-recs" md-label="Videos" md-icon="videocam" v-if="videos">
              <md-table v-model="videos">
                <md-table-row
                  slot="md-table-row"
                  slot-scope="{ item }"
                  md-selectable="multiple"
                  md-auto-select
                >
                  <md-table-cell>{{ item.tab }}</md-table-cell>
                  <md-table-cell>
                    <md-button class="md-just-icon md-simple md-primary">
                      <md-icon>edit</md-icon>
                      <md-tooltip md-direction="top">Edit</md-tooltip>
                    </md-button>
                    <md-button class="md-just-icon md-simple md-danger">
                      <md-icon>close</md-icon>
                      <md-tooltip md-direction="top">Close</md-tooltip>
                    </md-button>
                  </md-table-cell>
                </md-table-row>
              </md-table>
            </md-tab>

            
          </md-tabs>
        </template>
      </nav-tabs-card>
      <DocumentModal 
            :caseData="caseData" 
            :documentEditor="documentEditor" 
            @close="closeDocument" 
            @success="$emit('success')"
            :isEditing="currentDoc"
            />
      <!--confirm invitation --> 

        <modal v-if="confirmDelete" @close="confirmDelete = false">
          <template slot="header">
            <md-button class="md-simple md-just-icon md-round modal-default-button" @click="confirmDelete = false">
              <md-icon>clear</md-icon>
            </md-button>
          </template>

          <template slot="body">
            <p>¿Está seguro(a) que desea elimiar este documento?</p>
          </template>

          <template slot="footer">
            <md-button class="md-simple md-danger" @click="confirmDelete = false">Cancelar</md-button>
            <md-button class="md-success" @click="deteleFile">Elimiar</md-button>
          </template>
        </modal>

        <!--preview docu --> 

        <modal v-if="showPreview" @close="showPreview = false" class="preview-modal">
          <template slot="header">
            <md-button class="md-simple md-just-icon md-round modal-default-button" @click="showPreview = false">
              <md-icon>clear</md-icon>
            </md-button>
          </template>

          <template slot="body">
            <div v-html="currentDoc.content"></div>
          </template>

          <template slot="footer">
            <md-button class="md-simple md-danger" @click="showPreview = false">Cancelar</md-button>
            <md-button class="md-success" >Descargar</md-button>
          </template>
        </modal>

        <VideoRecorder 
            v-if="recorderOn"
            :enabled="recorderOn"
            :doc="editingDoc"
            @close="recorderOn = false" 
            @success="transToSign"
            />
  </div>
</template>

<script>
import { NavTabsCard, Modal } from '../../../../components'
import DocumentModal from './modules/DocumentModal'
import VideoRecorder from './VideoRecorder'
import HelloSign from 'hellosign-embedded';

export default {
  props: ['documents', 'videos', 'parts'],
  components: {
    NavTabsCard,
    Modal,
    DocumentModal,
    VideoRecorder
  },
  data() {
    return {
      caseData: {
        id: null,
        counter_agreement: null,
      },
      editingDoc: null,
      documentEditor: false,
      currentDoc: null,
      user,
      confirmDelete: false,
      showPreview: false,
      recorderOn: false,
      helloSignOn: false,
      signUrl: null,
      HelloSign
    }
  },
  methods: {
    editDoc(item) {
      this.currentDoc = item;
      this.caseData = {
        id: item.case_id,
        counter_agreement: item.content,
        ...this.parts 
      }

      this.documentEditor = true;
    },
    downloadFile(id) {
      this.isLoading = true;
      axios.get(`api/document/${id}/download?api_token=${user.api_token}`,{}).then((response) => {
        window.open(response.data.$doc,'_blank') 
          this.isLoading = false;
        }).catch(() => {
          this.isLoading = false;
        });
    },
    closeDocument() {
      this.documentEditor = false;
    },
    onSelect(item) {
      this.currentDoc = item;
      this.showPreview = true;
    },
    signDoc(doc) {
      this.editingDoc = doc;
      this.recorderOn = true;
      // this.makeSignRequest()
    },
    confirmDel(item) {
      this.currentDoc = item;
      this.confirmDelete = true;
    },
    deteleFile() {
      this.confirmDelete = false;
      axios.delete(`api/document/${this.currentDoc.id}?api_token=${user.api_token}`).then((response) => {
        this.$emit('success');
        }).catch();
    },
    isSigned(doc) {
      //console.log('doc', doc);
      if (this.user.role == 'mediator' && doc.mediator_signed) {
        return 'mediator_signed'
      } else if (this.user.email === this.parts.opener.email && doc.opener_signed) {
        return 'opener_signed'
      } else if (doc.recipient_signed) {
        return 'recipient_signed'
      }
      return false;
    },
    transToSign() {
      this.recorderOn = false;
      this.makeSignRequest()
    },
    makeSignRequest() {
      this.isLoading = true;
      axios.post(`api/document/sign/${this.editingDoc.id}?api_token=${user.api_token}`, {
        signatureId: this.getSignatureId()
      }).then((response) => {

          this.signUrl = response.data.signUrl;
          
          const client = new HelloSign();

          client.open(this.signUrl, {
            clientId: response.data.clientId,
            locale: HelloSign.locales.ES_LA
          });

          client.on('sign', (data) => {
            this.saveSignature(data.signatureId, this.editingDoc.id);
          });
        
          this.helloSignOn = true;
          this.isLoading = false;

        }).catch(() => {
          this.isLoading = false;
        });
    },
    getSignerType() {
      if (this.user.role == 'mediator') {
        return 'mediator_signed'
      } else if (this.user.email === this.parts.opener.email) {
        return 'opener_signed'
      } else {
        return 'recipient_signed'
      }
    },
    getSignatureId() {
      if (this.user.role == 'mediator') {
        return this.editingDoc.mediator_sign_id
      } else if (this.user.email === this.parts.opener.email) {
        return this.editingDoc.opener_sign_id
      } else {
        return this.editingDoc.recipient_sign_id
      }
    },
    saveSignature(signatureId, documentId) {
      const signerType = this.getSignerType();

      axios.post(`api/document/${documentId}/save-signature?api_token=` + user.api_token,{
        signatureId,
        signerType,
      }).then((response) => {
        this.$emit('success');
        }).catch(() => {
          // this.isLoading = false;
        });
    }
  },
}
</script>

<style>
.md-table-cell:last-child .md-table-cell-container {
  text-align: right;
}
.preview-modal .modal-container {
      max-width: 991px !important;
     
}
.preview-modal .modal-body {
     min-height: 360px;
}
.x-hellosign-embedded__iframe {
    height: 100% !important;
}
</style>