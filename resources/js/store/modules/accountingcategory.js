import axios from 'axios';
import headers from './common';

//state
const state = {
    accounting_categories: null,
};

//getter
const getters = {
    StateAccountingCategoryList: state => state.accounting_categories,
};

//action
const actions = {
    //all accounting categories
    async getAccountingCategoryList({ commit }) {
        let response = await axios.get("all_accounting_categories", {
            headers
        })
        commit('setAccountingCategoryList', response.data)
    },
};

//mutation
const mutations = {
    setAccountingCategoryList(state, accounting_categories) {
        state.accounting_categories = accounting_categories
    },
};

export default {
    state,
    getters,
    actions,
    mutations
};