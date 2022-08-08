import { createApp } from "vue";
import App from "./App.vue";
import router from "@/router";
import { TinyEmitter } from "tiny-emitter";
export const bus = new TinyEmitter();

const app = createApp(App);
app.use(router);
app.mount("#app");

