import { createApp } from "vue";
import router from "./router"; // import the router
import QuoteForm from "./components/QuoteForm.vue";
import PostedQuotes from "./components/PostedQuotes.vue";

const app = createApp({});

app.component("QuoteForm", QuoteForm);
app.component("PostedQuotes", PostedQuotes);

app.use(router);
app.mount("#app");
