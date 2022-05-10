<template>
  <!-- modal -->
  <modal
    name="edit-expense-modal"
    height="auto"
    width="80%"
    :scrollable="true"
    :clickToClose="false"
  >
    <button
      type="button"
      class="close"
      data-dismiss="modal"
      @click="hideModal()"
    >
      <span aria-hidden="true">&times;</span>
    </button>

    <!-- form -->
    <form class="expense-form" @submit.prevent="submit">
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="date">Date</label>
          <input
            type="date"
            class="form-control"
            placeholder="Date"
            name="date"
            v-model="form.date"
            :class="{
              'is-invalid': isValid && $v.form.date.$error,
            }"
          />
          <div
            v-if="isValid && $v.form.date.$error"
            class="invalid-feedback"
            style="display: block; font-size: 1em"
          >
            <span v-if="!$v.form.date.required">Date is required</span>
          </div>
        </div>
        <div class="form-group col-md-4">
          <label for="acc_cat_code">Accounting Category</label>
          <select
            class="form-control"
            v-model="form.acc_cat_code"
            name="acc_cat_code"
            :class="{
              'is-invalid': isValid && $v.form.acc_cat_code.$error,
            }"
          >
            <option value="null" disabled>Select Accounting Category</option>
            <option
              v-for="category in categoryList"
              :value="category.code"
              :key="category.code"
            >
              {{ category.name }}
            </option>
          </select>
          <div
            v-if="isValid && $v.form.acc_cat_code.$error"
            class="invalid-feedback"
            style="display: block; font-size: 1em"
          >
            <span v-if="!$v.form.acc_cat_code.required"
              >Accounting Category is required</span
            >
          </div>
        </div>
        <div class="form-group col-md-4">
          <label for="name">Item</label>
          <input
            type="text"
            class="form-control"
            placeholder="Item"
            name="name"
            v-model="form.name"
            :class="{
              'is-invalid': isValid && $v.form.name.$error,
            }"
          />
          <div
            v-if="isValid && $v.form.name.$error"
            class="invalid-feedback"
            style="display: block; font-size: 1em"
          >
            <span v-if="!$v.form.name.required">Item is required</span>
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="price"
            >Unit Price &nbsp;<span style="color: #659cef"
              >(Optional)</span
            ></label
          >
          <input
            type="number"
            class="form-control"
            placeholder="Unit Price"
            name="price"
            v-model="form.price"
          />
        </div>
        <div class="form-group col-md-4">
          <label for="quantity"
            >Quantity &nbsp;<span style="color: #659cef"
              >(Optional)</span
            ></label
          >
          <input
            type="number"
            class="form-control"
            placeholder="Quantity"
            name="quantity"
            v-model="form.quantity"
          />
        </div>
        <div class="form-group col-md-4">
          <label for="total_price">Total Amount</label>
          <input
            v-if="this.form.price || this.form.quantity"
            type="number"
            class="form-control"
            placeholder="Total Amount"
            name="total_price"
            id="total_price"
            :value="total"
          />
          <input
            v-else
            type="number"
            class="form-control"
            placeholder="Total Amount"
            name="total_price"
            id="total_price"
            v-model="form.total_price"
          />
          <div
            v-if="isValid && $v.form.total_price.$error"
            class="invalid-feedback"
            style="display: block; font-size: 1em"
          >
            <span v-if="!$v.form.total_price.required"
              >Total Amount is required</span
            >
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="company"
            >Supplier Company &nbsp;<span style="color: #659cef"
              >(Optional)</span
            ></label
          >
          <input
            type="text"
            class="form-control"
            placeholder="Supplier Company"
            name="company"
            v-model="form.company"
          />
        </div>
        <div class="form-group col-md-4">
          <label for="supplier"
            >Supplier Person &nbsp;<span style="color: #659cef"
              >(Optional)</span
            ></label
          >
          <input
            type="text"
            class="form-control"
            placeholder="Supplier Person"
            name="supplier"
            v-model="form.supplier"
          />
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-12 pt-3" align="center">
          <button
            type="submit"
            :disabled="form.busy"
            class="btn btn-save"
            id="editExpenseBtn"
          >
            Save
          </button>
        </div>
      </div>
    </form>
    <!-- end form -->
  </modal>
  <!-- end modal -->
</template>

<script>
import Form from "vform";
import { mapGetters, mapActions } from "vuex";
import { required } from "vuelidate/lib/validators";

export default {
  components: {
    Form,
  },

  data: () => ({
    form: new Form({
      id: 0,
      date: "",
      acc_cat_code: "",
      name: "",
      price: "",
      quantity: "",
      total_price: 0,
      company: "",
      supplier: "",
    }),
    isValid: false,
    categories: [],
  }),

  validations: {
    form: {
      date: { required },
      acc_cat_code: { required },
      name: { required },
      total_price: { required },
    },
  },

  methods: {
    ...mapActions([
      "updateExpense",
      "setExpenseToUpdate",
      "getAccountingCategoryList",
    ]),

    expenseToUpdate: function () {
      if (this.getExpenseToUpdate) {
        this.form.date = this.getExpenseToUpdate.date;
        this.form.acc_cat_code = this.getExpenseToUpdate.acc_cat_code;
        this.form.name = this.getExpenseToUpdate.name;
        this.form.price = this.getExpenseToUpdate.price;
        this.form.quantity = this.getExpenseToUpdate.quantity;
        this.form.total_price = this.getExpenseToUpdate.total_price;
        this.form.company = this.getExpenseToUpdate.company;
        this.form.supplier = this.getExpenseToUpdate.supplier;
        this.form.id = this.getExpenseToUpdate.id;
      }
    },

    async submit() {
      this.isValid = true;

      try {
        this.$v.$touch();

        if (this.$v.$invalid) {
          return;
        }

        await this.updateExpense(this.form);
        this.hideModal();
        await this.resetExpense();
        this.flashMessage.success({
          title: "Success!",
          message: "The expense has been successfully updated",
          time: 5000,
          flashMessageStyle: {
            backgroundColor: "linear-gradient(#e66465, #9198e5)",
          },
        });
      } catch (error) {
        console.log(error.response);
      }
    },

    hideModal() {
      this.$modal.hide("edit-expense-modal");
    },

    resetExpense: function () {
      this.isValid = false;
      this.form = {
        date: "",
        acc_cat_code: "",
        name: "",
        price: "",
        quantity: "",
        total_price: "",
        company: "",
        supplier: "",
      };
    },
  },

  watch: {
    getExpenseToUpdate: function () {
      if (
        this.getExpenseOperation &&
        this.getExpenseOperation.toLowerCase() === "update"
      ) {
        this.expenseToUpdate();
      }
    },
  },

  created: async function () {
    await this.getAccountingCategoryList();
  },

  computed: {
    ...mapGetters([
      "getExpenseOperation",
      "getExpenseToUpdate",
      "StateAccountingCategoryList",
    ]),
    //function for calculating total amount
    total: function () {
      let calculatedTotal = this.form.price * this.form.quantity;
      this.form.total_price = calculatedTotal;

      return calculatedTotal;
    },

    categoryList: function () {
      var categories = this.StateAccountingCategoryList;
      return categories;
    },
  },
};
</script>