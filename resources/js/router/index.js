import { createRouter, createWebHistory } from "vue-router";
import QuoteForm from "../components/QuoteForm.vue";
import PostedQuotes from "../components/PostedQuotes.vue";

const routes = [
    { path: "/", component: QuoteForm },
    { path: "/posted-quotes", component: PostedQuotes },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
