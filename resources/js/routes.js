import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);
const routes = [
    {
        path: "/expense",
        name: "expense",
        component: function () {
            return import("./components/expense/Expense");
        },
    },
];

// Register route
const router = new VueRouter({
    mode: "history",
    history: true,

    base: process.env.BASE_URL,
    routes,
});

export default router;
