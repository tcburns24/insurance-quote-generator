import { createApp } from "vue";
import router from "./router"; // import the router
import AllCountries from "./components/AllCountries.vue";
import "@coreui/coreui/dist/css/coreui.min.css";

const app = createApp({});

app.component("AllCountries", AllCountries);

app.use(router);
app.mount("#app");
