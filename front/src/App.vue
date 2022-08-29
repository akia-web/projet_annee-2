<template>
  <div>
    <Navbar />
    <router-view />
  </div>
</template>

<script>
import HelloWorld from "./components/HelloWorld.vue";

import LalalaMessage from "./components/LalalaMessage.vue";

import ConnexionApp from "./components/connexion/ConnexionApp.vue";

import Navbar from "./components/navbar/Navbar.vue";

export default {
  name: "App",
  data() {
    return {
      Authenticated: false,
      user: null,
      id: null,
      email: null,
    };
  },
  methods: {},

  components: {
    HelloWorld,
    lalala: LalalaMessage,
    ConnexionApp,
    Navbar,
  },
};
</script>

<style src="./css_global/style.css"></style>
