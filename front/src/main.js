import { createApp } from "vue";
import App from "./App.vue";
import router from "@/router";
// import $bus from "@/event/event";
// export const eventBus = createApp(App);
const app = createApp(App);
app.use(router);
app.mount("#app");
// app.config.globalProperties.$bus = $bus;
