<template>
  <!-- <Navbar v-on:connect="getUser($event)" /> -->
  <Navbar  />
  <!-- <HelloWorld msg="Welcome to Your Vue.js App"/> -->

  <!--  <router-link :to="{name: 'dynamic', params: { id: 33 } }"> page </router-link> -->

  <router-view />
  <!-- <Footer /> -->
</template>

<script>
import HelloWorld from "./components/HelloWorld.vue";

import LalalaMessage from "./components/LalalaMessage.vue";

import ConnexionApp from "./components/connexion/ConnexionApp.vue";

import Navbar from "./components/navbar/Navbar.vue";

import Footer from "./components/footer/footer.vue";

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
  methods: {
 
  },

  components: {
    HelloWorld,
    lalala: LalalaMessage,
    ConnexionApp,
    Navbar,
    Footer,
  },
};
</script>

<style src="./css_global/style.css"></style>
