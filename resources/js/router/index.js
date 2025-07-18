import { createRouter, createWebHistory } from "vue-router";
import AllCountries from "../components/AllCountries.vue";
import PostedQuotes from "../components/PostedQuotes.vue";
import Country from "../components/Country.vue";

const routes = [
    { path: "/", component: AllCountries },
    { path: "/posted-quotes", component: PostedQuotes },
    {
        path: "/country/:name",
        name: "country",
        component: Country,
        props: true,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
