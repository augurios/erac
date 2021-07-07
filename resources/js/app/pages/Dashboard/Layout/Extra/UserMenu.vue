<template>
  <div class="user">
    <div class="photo">
      <img :src="avatar" alt="avatar" />
    </div>
    <div class="user-info">
      <a
        data-toggle="collapse"
        :aria-expanded="!isClosed"
        @click.stop="toggleMenu"
        @click.capture="clicked"
      >
        
        <span>
          {{ title }}
          <b class="caret"></b>
        </span>
      </a>

      <collapse-transition>
        <div v-show="!isClosed">
          <ul class="nav">
            <slot>
              <li>
                
                <sidebar-item
                  :link="{ name: 'Perfil', icon: 'person', path: '/profile' }"
                >
                </sidebar-item>
              <!-- </li>
              <li>
                
                <a href="#vue">
                  <span class="sidebar-mini">EP</span>
                  <span class="sidebar-normal">Edit Profile</span>
                </a>
              </li> -->
              <li>
                <a href="#" @click="logout">  
                  <span class="sidebar-mini"><i class="material-icons design_bullet-list-67 visible-on-sidebar-mini">power_settings_new</i></span>
                  <span class="sidebar-normal">Cerrar Sesión</span>
                </a>
              </li>
            </slot>
          </ul>
        </div>
      </collapse-transition>
    </div>
  </div>
</template>
<script>
import { CollapseTransition } from "vue2-transitions";

export default {
  components: {
    CollapseTransition
  },
  props: {
    title: {
      type: String,
      default: "Tania Andrew"
    },
    rtlTitle: {
      type: String,
      default: "تانيا أندرو"
    },
    avatar: {
      type: String,
      default: "/img/faces/avatar.jpg"
    }
  },
  data() {
    return {
      isClosed: true
    };
  },
  methods: {
    clicked: function(e) {
      e.preventDefault();
    },
    toggleMenu: function() {
      this.isClosed = !this.isClosed;
    },
    logout: function() {
       axios.post('/logout')
        .then((response) => {
            location.href = '/';
        })
        .catch((error) => {
            console.log('error',error);
        })
    }
  }
};
</script>
<style>
.collapsed {
  transition: opacity 1s;
}
</style>
