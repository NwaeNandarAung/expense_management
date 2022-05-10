<template>
  <div class="content">
    <!-- row for search -->
    <div class="row">
      <div class="offset-md-2 col-md-4">
        <!-- form group for filter by date -->
        <div class="form-group expense-form has-search">
          <span class="fa fa-search form-control-feedback"></span>
          <input
            type="date"
            class="form-control"
            placeholder="Search by Date"
            v-model="filter_by_date"
          />
        </div>
        <!-- end form group for filter by date -->
      </div>
      <div class="col-md-4">
        <!-- form group for filter by category -->
        <div class="form-group expense-form has-search">
          <span class="fa fa-search form-control-feedback"></span>
          <input
            type="text"
            class="form-control"
            placeholder="Search by Accounting Category"
            v-model="filter_by_category"
          />
        </div>
        <!-- end form group for filter by category -->
      </div>

      <div class="col-md-2">
        <button class="btn btn-warning mt-4" @click="downloadExpense()">
          Download
        </button>
      </div>
    </div>
    <!-- end row for search -->

    <h4 class="total-amount">
      <span class="color-red">Total Amount : </span>{{ totalAmount }} MMK
    </h4>
    <!-- end show total amount -->

    <!-- expense table -->
    <table class="table table-responsive-lg expense-table">
      <!-- thead of expense table -->
      <thead>
        <tr>
          <th>No</th>
          <th>Date</th>
          <th>Accounting Category</th>
          <th>Item</th>
          <th>Unit Price</th>
          <th>Quantity</th>
          <th>Total Amount</th>
          <th>Supplier Company</th>
          <th>Supplier Person</th>
          <th>Actions</th>
        </tr>
      </thead>
      <!-- end thead of expense table -->

      <!-- tbody of expense table -->
      <tbody>
        <!-- inner template -->
        <template v-if="expenseList.total == 0">
          <tr>
            <td colspan="10">
              <h5 style="text-align: center; color: #659cef">
                Oops! No corresponding data found!
              </h5>
            </td>
          </tr>
        </template>
        <template v-for="(expense, index) in expenseList" v-else>
          <tr :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ expense.date }}</td>
            <td>{{ expense.accounting_category_name }}</td>
            <td>{{ expense.name }}</td>
            <td v-if="expense.price">{{ expense.price }}</td>
            <td v-else>-</td>
            <td v-if="expense.quantity">{{ expense.quantity }}</td>
            <td v-else>-</td>
            <td>{{ expense.total_price }}</td>
            <td v-if="expense.company">{{ expense.company }}</td>
            <td v-else>-</td>
            <td v-if="expense.supplier">{{ expense.supplier }}</td>
            <td v-else>-</td>
            <td colspan="2">
              <button class="btn btn-warning" @click="editExpense(expense.id)">
                <i class="fa fa-edit color-white"></i>
              </button>
              <button
                class="btn btn-danger"
                v-on:click="alertDisplay(expense.id)"
              >
                <i class="fa fa-trash color-white"></i>
              </button>
            </td>
          </tr>
        </template>
        <!-- end inner template -->
      </tbody>
      <!--end tbody of expense table -->
    </table>
    <!-- end expense table -->
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import flatPickr from "flatpickr";

import "flatpickr/dist/flatpickr.css";
import "flatpickr/dist/themes/material_blue.css";

export default {
  data() {
    return {
      components: {
        flatPickr, //date picker
      },

      expenses: [],
      filter_by_date: null,
      filter_by_category: null,
      total_amount: null,
    };
  },

  methods: {
    ...mapActions([
      "setExpenseToUpdate",
      "deleteExpense",
      "getTotalAmount",
      "getExpenses",
      "exportExpense",
    ]),

    //download expense
    async downloadExpense() {
      var params = {
        filter_by_date: this.filter_by_date,
        filter_by_category: this.filter_by_category,
      };
      await this.exportExpense(params);
    },

    //edit action on clicking edit button
    editExpense(id) {
      var expense = this.findExpenseById(id);
      this.setExpenseToUpdate({
        expenseToUpdate: expense,
        operationName: "Update",
      });
      this.$modal.show("edit-expense-modal");
    },

    //display confirmation message on clicking delete button
    alertDisplay(id) {
      this.$swal({
        title: "Are you sure to delete this expense?",
        text: "You can't revert your action",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes Delete it!",
        cancelButtonText: "No, Keep it!",
        showCloseButton: true,
        showLoaderOnConfirm: true,
        confirmButtonColor: "#659cef",
        cancelButtonColor: "#8d989a",
        okButtonColor: "#659cef",
      }).then((result) => {
        if (result.value) {
          this.deleteCurrentExpense(id);
          this.$swal(
            "Deleted",
            "You successfully deleted this expense",
            "success"
          );
        } else {
          this.$swal("Cancelled", "You have cancelled the operation", "info");
        }
      });
    },

    // delete action
    deleteCurrentExpense(id) {
      try {
        this.deleteExpense(id);
      } catch (error) {
        console.log("error", error);
      }
    },
  },

  watch: {
    filter_by_date() {
      var params = {
        filter_by_date: this.filter_by_date,
        filter_by_category: this.filter_by_category,
      };
      this.getExpenses(params);
      this.getTotalAmount(params);
    },
    filter_by_category() {
      var params = {
        filter_by_date: this.filter_by_date,
        filter_by_category: this.filter_by_category,
      };
      this.getExpenses(params);
      this.getTotalAmount(params);
    },
  },

  created: function () {
    var params = {
      filter_by_date: this.filter_by_date,
      filter_by_category: this.filter_by_category,
    };
    this.getExpenses(params);
    this.getTotalAmount(params);
  },

  mounted() {
    let inputs = ['input[placeholder="Search by Date"]'];
    //filter with date range using flat pickr
    inputs.forEach(function (input) {
      flatPickr(input, {
        dateFormat: "Y-m-d",
        mode: "range",
        allowInput: true,
      });
    });
  },

  computed: {
    ...mapGetters(["findExpenseById", "StateTotalAmount", "StateExpenses"]),

    //function for calculating total amount
    total() {
      return this.StateExpenses.data.reduce(function (sum, expense) {
        return sum + expense.total_price;
      }, 0);
    },

    totalAmount: function () {
      var total_amount = this.StateTotalAmount;
      return total_amount;
    },

    expenseList: function () {
      var expenses = this.StateExpenses;
      return expenses;
    },
  },
};
</script>