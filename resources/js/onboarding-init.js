require('./bootstrap');

window.Vue = require('vue');
import DashboardPlugin from "./app/material-dashboard";
import OnBoarding from "./onboarding/OnBoarding.vue";
Vue.use(DashboardPlugin);
/* eslint-disable no-new */
new Vue({
  el: "#app",
  render: h => h(OnBoarding)
});