import axios from 'axios';
import headers from './common';

//state
const state = {
    expenses: null,
    total_amount: null,
    expenseToUpdate: null,
    operation: "Add"
};

//getter
const getters = {
    StateExpenses: state => state.expenses,
    StateTotalAmount: state => state.total_amount,
    findExpenseById: (state) => (id) => {
        var expense = state.expenses.filter((u) => {
            if (u.id === id) {
                return u;
            }
        });
        return expense[0];
    },
    getExpenseOperation: (state) => state.operation,
    getExpenseToUpdate: (state) => state.expenseToUpdate
};

//action
const actions = {
    // get all expenses
    async getExpenses({ commit }, params) {
        let response = await axios.get("expense", {
            headers,
            params
        });

        commit('setExpenses', response.data)
    },

    // get total amount
    async getTotalAmount({ commit }, params) {
        let response = await axios.get("total_amount", {
            params,
            headers,
        });

        commit('setTotalAmount', response.data)
    },

    //create expense
    async createExpense({ dispatch }, expense) {
        await axios.post('expense', expense, {
            headers,

        })
        await dispatch('getExpenses')
        await dispatch('getTotalAmount')
    },

    setExpenseToUpdate({ commit }, data) {
        commit("setExpenseToUpdate", data);
    },

    //update expense
    async updateExpense({ dispatch }, data) {
        await axios.put(`expense/${data.id}`, data, {
            headers,
        })
        await dispatch('getExpenses')
        await dispatch('getTotalAmount')
    },

    //delete expense
    async deleteExpense({ commit }, id) {
        await axios.delete(`expense/${id}`, {
            headers,

        })
        commit('deleteExpense', id);
    },

    // export expenses
    async exportExpense({commit},params) {
        await axios.get('/export_expense', {
            params,
            headers,
            responseType: "blob",
        }).then((res) => {
            const url = window.URL.createObjectURL(new Blob([res.data])); //create DOM string containing URL
            const link = document.createElement("a"); //created a tab
            link.href = url; //assign url to href of link
            link.setAttribute("download", "expense-report.csv"); //set attribute download together with value
            document.body.appendChild(link); //append link to body
            link.click(); //process link click action
        }).catch((error) => { });
    },
};

//mutation
const mutations = {
    setExpenses(state, expenses) {
        state.expenses = expenses
    },

    setTotalAmount(state, total_amount) {
        state.total_amount = total_amount
    },

    setExpenseToUpdate: (state, data) => {
        state.expenseToUpdate = data.expenseToUpdate;
        state.operation = data.operationName;
        return state;
    },

    deleteExpense: (state, id) => {
        var expenses = state.expenses.filter((u) => {
            if (u.id !== id) {
                return u;
            }
        });
        state.expenses = expenses;
        return state;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};