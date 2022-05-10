<template>
  <div class="page-content">
    <div class="container-fluid">
      <div class="row">
        <!-- flash message -->
        <FlashMessage
          :position="'top right'"
          style="z-index: 999; top: 0; right: 0"
        ></FlashMessage>
        <!-- end flash message -->
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body">
              <!-- page title -->
              <h2 class="mt-2 mb-2 header-title">Add New Expense</h2>
              <!-- import create modal -->
              <create-expense-component></create-expense-component>
              <!-- end import create component -->

              <h2 class="mt-5 mb-2 header-title">Expense List</h2>

              <!-- import edit and list component -->
              <edit-expense-component></edit-expense-component>
              <expense-list-component></expense-list-component>
              <!-- end import edit and list component -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const createExpenseComponent = () => import("./CreateExpense.vue");
const expenseListComponent = () => import("./ExpenseList.vue");
const editExpenseComponent = () => import("./EditExpense.vue");

export default {
  components: {
    createExpenseComponent,
    expenseListComponent,
    editExpenseComponent,
  },
};
</script>
