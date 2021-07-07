<template>
  <md-card class="md-card-profile">
    <div class="md-card-avatar" v-show="!croppieImage">
        <img class="img" :src="cardUserImage">
    </div>
    <div class="md-card-avatar" v-show="croppieImage">
      <vue-croppie ref="croppieRef" :enableOrientation="true" :boundary="{ width: 140, height: 140}" :viewport="{ width:132, height:132, 'type':'square' }">
      </vue-croppie>
    </div>

    <md-card-content>
      
      <h4 class="card-title">{{ `${user.name} ${user.lastname}`}}</h4>
      <h6 class="category text-gray">Cedula: {{ `${user.cedula[0]}-${user.cedula.substring(1, 5)}-${user.cedula.substring(5, 9)}` }}</h6>
      <p class="card-description">
        {{ user.aboutme }}
      </p>
      <input type="file" name="avatar" id="avatar" accept="image/*" @change="selectFile" class="hidden">
      <template v-if="!croppieImage">
      <md-button class="md-round" onclick="document.getElementById('avatar').click(); return false;" :class="getColorButton(buttonColor)"
        >Actualizar imagen</md-button
      >
       </template>
      <template v-else>
      <md-button class="md-round" @click="uploadImage" :class="getColorButton(buttonColor)"
        >Salvar Imagen</md-button>
      </template>
    </md-card-content>
  </md-card>
</template>
<script>
export default {
  name: "user-card",
  props: {
    cardUserImage: {
      type: String,
      default: "./img/faces/avatar.jpg"
    },
    buttonColor: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      user: user,
      croppieImage: null,
      cropped: null,
    };
  },
  mounted() {
    this.$eventBus.$on('userUpdate', (updatedUser) => {
      this.user = {...updatedUser};
    });
  },
  beforeDestroy() {
    this.$eventBus.$off('userUpdate');
  },
  methods: {
    getColorButton: function(buttonColor) {
      return "md-" + buttonColor + "";
    },
    selectFile (e) {
      let files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;

      let reader = new FileReader()
      
      let thisSub = this;

      
      reader.onload = e => {
        thisSub.croppieImage = e.target.result;
        thisSub.$refs.croppieRef.bind({
          url: e.target.result
        });
        // this.$emit('image-loaded', e.target.result)
      }
      reader.readAsDataURL(files[0])
    },
    uploadImage () {

      let options = {
        type: 'base64',
        size: { width: 512, height: 512 },
        format: 'jpeg'
      };
      
      this.$refs.croppieRef.result(options, output => {
        this.cropped = output;
        this.postImage();
      });
      
    },
    baseToFile(base){
      var arr = base.split(','),
            mime = arr[0].match(/:(.*?);/)[1],
            bstr = atob(arr[1]), 
            n = bstr.length, 
            u8arr = new Uint8Array(n);
            
        while(n--){
            u8arr[n] = bstr.charCodeAt(n);
        }

      const postDate = new Date().getTime();
      var newFile = new File([u8arr], this.user.username, {lastModified: new Date()})
      return newFile;
    },
    postImage() {
      this.$emit('image-loaded', this.cropped)

      let data = new FormData()

      data.append('avatar', this.baseToFile(this.cropped));

      axios.post('/users/' + this.user.id + '/avatar', data)
        .then((response) => {
          // this.showNotification({type: response.data.status, message: response.data.message})
          this.user.avatar = response.data.avatar
          this.croppieImage = null;
          this.notif(response.data.message);
        })
        .catch((error) => {
          this.notif(error.response.data.message, 'danger');
        })
    }
  }
};
</script>
<style scoped>
  .card-title {
    margin-bottom: 0.5rem;
  }
  .card-title,
  .md-card-content {
    margin-top: 0 !important;
  }
  .hidden {
    display: none;
  }
</style>
