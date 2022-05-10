import Vuex from 'vuex';
import Vue from 'vue';
import createPersistedState from "vuex-persistedstate";
import expense from './modules/expense';
import accountingcategory from './modules/accountingcategory';

// Load Vuex
Vue.use(Vuex);

// Create store
export default new Vuex.Store({
  modules: {
    expense,
    accountingcategory,
  },
  plugins: [createPersistedState()]
});