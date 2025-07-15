import { createRouter, createWebHistory } from "vue-router";
import AllCountries from "../components/AllCountries.vue";
import PostedQuotes from "../components/PostedQuotes.vue";

const routes = [
    { path: "/", component: AllCountries },
    { path: "/posted-quotes", component: PostedQuotes },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
