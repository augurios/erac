<template>
  <div>
    
      
  <div class="full-page" v-if="path === '/client-onboarding'">
    <md-toolbar md-elevation="0" class="md-transparent md-toolbar-absolute">
      <div class="md-toolbar-row md-offset">
        <div class="md-toolbar-section-start">
           <a href="/"><img src="/images/new_logo.png" /></a>
        </div>
        <div class="md-toolbar-section-end">
          
        </div>
      </div>
    </md-toolbar>
    <div class="wrapper wrapper-full-page" >
      <div
        class="page-header header-filter"
         style="background-image: url('./img/register.jpg');"
      >
        <div class="container md-offset">
          <zoom-center-transition
            :duration="pageTransitionDuration"
            mode="out-in"
            class="md-size-100"
          >
            <ClientWizard></ClientWizard>
          </zoom-center-transition>
        </div>
        <footer class="footer">
          <div class="container md-offset">
            <nav>
              <ul>
                <li>
                   <a href="/">
                    Home
                  </a>
                </li>
                <li>
                   <a href="#">
                    Company
                  </a>
                </li>
                <li>
                  <a href="#">
                    Portfolio
                  </a>
                </li>
                <li>
                  <a href="#">
                    Blog
                  </a>
                </li>
              </ul>
            </nav>
            <div class="copyright text-center">
              &copy; {{ new Date().getFullYear() }}
              <a
                href="https://www.creative-tim.com/?ref=mdf-vuejs"
                target="_blank"
                >Creative Tim</a
              >, made with <i class="fa fa-heart heart"></i> for a better web
            </div>
          </div>
        </footer>
      </div>
    </div>
  </div>
    <!-- <Wizard v-else></Wizard> -->
  </div>
</template>

<script>
import { ZoomCenterTransition } from "vue2-transitions";
// import Wizard from "./components/ServiceWizard.vue";
import ClientWizard from "./components/ClientWizard.vue";
export default {
  components: {
    ZoomCenterTransition,
    // Wizard,
    ClientWizard
  },
  data() {
    return {
      wizardModel: {},
      path: new URL(location.href).pathname,
      pageTransitionDuration: 300,
    };
  },
};
</script>
<style scoped>
  .md-toolbar-section-start img {
    max-width: 134px;
    position: relative;
    top: 22px;
}
</style>