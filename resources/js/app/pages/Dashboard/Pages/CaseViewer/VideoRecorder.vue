<template>
  <modal v-if="enabled" class="edit-modal">
    <template slot="header">
      <h3 class="modal-title">Confirmar Firma de Documento</h3>
      <md-button class="md-simple md-just-icon md-round modal-default-button" @click="$emit('close')">
        <md-icon>clear</md-icon>
      </md-button>
    </template>

    <template slot="body">
      <h5>Sostenga su cedula y confirme su voluntad de firmar este documento</h5>
      <div>
          <video id="myVideo" playsinline class="video-js vjs-default-skin">
              <p class="vjs-no-js">
                  To view this video please enable JavaScript, or consider upgrading to a
                  web browser that
                  <a href="https://videojs.com/html5-video-support/" target="_blank">
                      supports HTML5 video.
                  </a>
              </p>
          </video>
          <br>
          <button type="button" class="btn btn-info" @click.prevent="startRecording()" v-bind:disabled="isStartRecording" id="btnStart">Grabar</button>
          <button type="button" class="btn btn-success" @click.prevent="submitVideo()" v-bind:disabled="isSaveDisabled" id="btnSave">{{ submitText }}</button>
          <button type="button" class="btn btn-primary" @click.prevent="retakeVideo()" v-bind:disabled="isRetakeDisabled" id="btnRetake">Tomar de nuevo</button>
      </div>
    </template>

     <template slot="footer" >
      <!-- <md-button class="md-success" @click="attachFile" v-if="!isEditing || isEditing && isEditing.status == 'draft'">Adjuntar documento al caso</md-button>
      <md-button class="md-simple md-success" @click="saveFile" >Salvar Cambios</md-button> -->
      <md-button class="md-danger md-simple" @click="$emit('close')">Cancelar</md-button>
    </template>
      
  </modal>
</template>

<script>
import { Modal } from '../../../../components';
import 'video.js/dist/video-js.css'
import 'videojs-record/dist/css/videojs.record.css'
import videojs from 'video.js'
import 'webrtc-adapter'
import RecordRTC from 'recordrtc'
import Record from 'videojs-record/dist/videojs.record.js'
import FFmpegjsEngine from 'videojs-record/dist/plugins/videojs.record.ffmpegjs.js';
export default {
    props: ['doc','enabled'],
    components: {
      Modal
    },
    data() {
        return {
            player: '',
            retake: 0,
            isSaveDisabled: true,
            isStartRecording: false,
            isRetakeDisabled: true,
            submitText: 'Enviar video',
            user,
            options: {
                controls: true,
                bigPlayButton: false,
                controlBar: {
                    deviceButton: false,
                    recordToggle: false,
                    pipToggle: false
                },
                width: 500,
                height: 300,
                fluid: true,
                plugins: {
                    record: {
                        pip: false,
                        audio: true,
                        video: true,
                        maxLength: 10,
                        debug: true
                    }
                }
            }
        }
    },
    mounted() {
        this.player = videojs('myVideo', this.options, () => {
            // print version information at startup
            var msg = 'Using video.js ' + videojs.VERSION +
                ' with videojs-record ' + videojs.getPluginVersion('record') +
                ' and recordrtc ' + RecordRTC.version;
            videojs.log(msg);
        });
        // error handling
        this.player.on('deviceReady', () => {
            this.player.record().start();
            console.log('device ready:');
        });
        this.player.on('deviceError', () => {
            console.log('device error:', this.player.deviceErrorCode);
        });
        this.player.on('error', (element, error) => {
            console.error(error);
        });
        // user clicked the record button and started recording
        this.player.on('startRecord', () => {
            console.log('started recording!');
        });
        // user completed recording and stream is available
        this.player.on('finishRecord', () => {
            this.isSaveDisabled = false;
            if(this.retake == 0) {
                this.isRetakeDisabled = false;
            }
            // the blob object contains the recorded data that
            // can be downloaded by the user, stored on server etc.
            console.log('finished recording: ', this.player.recordedData);
        });
    },
    methods: {
        startRecording() {
            this.isStartRecording = true;
            this.player.record().getDevice();
        },
        submitVideo() {
            // this.isSaveDisabled = true;
            this.isRetakeDisabled = true;
            var data = this.player.recordedData;
            var formData = new FormData();
            formData.append('video', data, data.name);
            formData.append('user', this.user.id );
            formData.append('document', this.doc.id );
            formData.append('case', this.doc.case_id );

            this.submitText = "Cargando "+data.name;
            console.log('uploading recording:', data.name);
            this.player.record().stopDevice();
            fetch(`${window.storageUrl}/savevideo`, {
                method: 'POST',
                mode:'cors',
                body: formData,
                // headers: {
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                // }
            }).then(
                success => {
                    console.log('recording upload complete.');
                    this.submitText = "Carga Completa";
                    this.$emit('success');
                }
            ).catch(
                error =>{
                    console.error('an upload error occurred!');
                    this.submitText = "Carga fallida";
                    this.isSaveDisabled = false;
                    this.isRetakeDisabled = false;
                }
            );
        },
        retakeVideo() {
            this.isSaveDisabled = true;
            this.isRetakeDisabled = true;
            this.retake += 1;
            this.player.record().start();
        }
    },
    beforeDestroy() {
        if (this.player) {
            this.player.dispose();
        }
    }
}
</script>