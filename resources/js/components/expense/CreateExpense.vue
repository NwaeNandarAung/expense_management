<template>
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
        <label for="category">Accounting Category</label>
        <select
          class="form-control"
          v-model="form.acc_cat_code"
          name="acc_cat_code"
          :class="{
            'is-invalid': isValid && $v.form.acc_cat_code.$error,
          }"
        >
          <option value="" disabled>Select Accounting Category</option>
          <option
            v-for="category in categoryList"
            :value="category.code"
            :key="category.id"
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
          >Quantity &nbsp;<span style="color: #659cef">(Optional)</span></label
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
          id="createExpenseBtn"
        >
          Save
        </button>
        <button
          type="reset"
          class="btn btn-info pt-2 pb-2"
          @click="resetExpense"
        >
          Cancel
        </button>
      </div>
    </div>
  </form>
  <!-- end form -->
</template>

<script>
import Form from "vform";
import { mapActions, mapGetters } from "vuex";
import { required } from "vuelidate/lib/validators";

export default {
  components: {
    Form,
  },

  data: () => ({
    form: new Form({
      date: new Date().toISOString().substr(0, 10),
      acc_cat_code: "",
      name: "",
      price: "",
      quantity: "",
      total_price: 0,
      company: "",
      supplier: "",
    }),
    categories: [],
    isValid: false,
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
    ...mapActions(["createExpense", "getAccountingCategoryList"]),

    async submit() {
      this.isValid = true;

      try {
        this.$v.$touch();

        if (this.$v.$invalid) {
          return;
        }
        await this.createExpense(this.form);
        await this.resetExpense();
        this.flashMessage.success({
          title: "Success!",
          message: "The new expense has been successfully created",
          time: 5000,
          flashMessageStyle: {
            backgroundColor: "linear-gradient(#e66465, #9198e5)",
          },
        });
      } catch (error) {
        console.log(error.response);
      }
    },

    resetExpense: function () {
      this.isValid = false;
      this.form = {
        date: new Date().toISOString().substr(0, 10),
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

  created: async function () {
    await this.getAccountingCategoryList();
  },

  computed: {
    ...mapGetters(["StateAccountingCategoryList"]),
    // function for calculating total amount
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