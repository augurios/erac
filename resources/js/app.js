/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import VueRouter from "vue-router";

window.Vue = require('vue');
window.storageUrl = 'https://documents.erac.online/index.php/api';
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import DashboardPlugin from "./app/material-dashboard";

// Plugins
import App from "./app/App.vue";
import Chartist from "chartist";

// router setup
import routes from "./app/routes/routes";
import VueCroppie from 'vue-croppie';
import 'croppie/croppie.css' // import the croppie css manually
import VueChatScroll from 'vue-chat-scroll';

Vue.use(VueChatScroll)

Vue.use(VueCroppie);
// plugin setup
Vue.use(VueRouter);
Vue.use(DashboardPlugin);

const moment = require('moment')
require('moment/locale/es')
Vue.use(require('vue-moment'), {
  moment
})

// configure router
const router = new VueRouter({
  routes, // short for routes: routes
  scrollBehavior: to => {
    if (to.hash) {
      return { selector: to.hash };
    } else {
      return { x: 0, y: 0 };
    }
  },
  linkExactActiveClass: "nav-item active"
});

// global library setup
Vue.prototype.$Chartist = Chartist;

Vue.prototype.notif = function(text, type) {
  let mytype = type ? type : 'success'
  this.$notify({
    message: text,
    icon: 'add_alert',
    horizontalAlign: 'right',
    verticalAlign: 'bottom',
    type: mytype
  })
}

Vue.prototype.$eventBus = new Vue(); // Global event bus

Vue.filter('statusDic', function (value) {
  if (!value) return ''
  const dictionary = {
    active: 'Activo',
    done:   'Finalizado',
    waiting: 'En Espera',
    reviewing: 'En Revision'
  }
  return dictionary[value];
});

Vue.filter('numberWithCommas', function (value) {
  if (!value) return ''
  var parts = value.toString().split(".");
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  return parts.join(".");
});

Vue.filter('priceWithCommas', function (value) {
  if (!value) return ''
  var parts = value.toString().split(".");
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  return "₡" + parts.join(".");
});

/* eslint-disable no-new */
new Vue({
  el: "#app",
  render: h => h(App),
  router
});
