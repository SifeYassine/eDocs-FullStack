import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";

import Vuesax from "vuesax-alpha";
import "vuesax-alpha/theme-chalk/index.css";
import "vuesax-alpha/theme-chalk/dark/css-vars.css";

const app = createApp(App);

app.use(Vuesax).use(store).use(router).mount("#app");
