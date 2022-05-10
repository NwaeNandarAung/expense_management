/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

Vue.config.devtools = false;

require("./bootstrap");

// window.Vue = require("vue");
import Vue from "vue";

import VueRouter from "vue-router";
import router from "./routes";
import App from "./layouts/App";
import store from "./store";
import axios from "axios";

// Sweet Alert
/**
 * Documantation Page
 * https://vue2-simplert.gitbook.io/docs/
 */
 import VueSweetalert2 from "vue-sweetalert2";
 import "sweetalert2/dist/sweetalert2.css";

// Vue JS Modal
/**
 * Documantation Page
 * https://euvl.github.io/vue-js-modal/
 */
import VModal from "vue-js-modal";


// Vuelidate
/**
 * Documantation Page
 * https://vuelidate.js.org/
 */
import Vuelidate from "vuelidate";

// Flash Message
/**
 * Documantation Page
 * https://vuejsexamples.com/single-flash-message-with-vue/
 */
import FlashMessage from "@smartweb/vue-flash-message";


//Input required css
import "../assets/css/vue.css";

Vue.use(VueSweetalert2);
Vue.use(VModal);
Vue.use(VueRouter);
Vue.use(Vuelidate);
Vue.use(FlashMessage);


//axios
// axios.defaults.withCredentials = true;
axios.defaults.baseURL = process.env.MIX_API_URL;
Vue.config.productionTip = true;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
  el: "#app",
  router,
  store,
  components: { App },
});
