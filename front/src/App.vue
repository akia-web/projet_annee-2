<template>
  <Navbar v-on:connect="getUser($event)" />
  <!-- <HelloWorld msg="Welcome to Your Vue.js App"/> -->

  <!--  <router-link :to="{name: 'dynamic', params: { id: 33 } }"> page </router-link> -->

  <router-view />
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
  methods: {
    getUser(event) {
      console.log(event);
      // this.email = localStorage.getItem("animoEmail");
      // this.id = localStorage.getItem("animoId");
      // let url =
      //   "http://127.0.0.1:8000/api/user?email=" +
      //   this.email +
      //   "&id=" +
      //   this.id;

      // await axios.get(url).then(
      //   (res) => {
      //     console.log(res.data);
      //   },

      //   (error) => {}
      // );
    },
  },

  components: {
    HelloWorld,
    lalala: LalalaMessage,
    ConnexionApp,
    Navbar,
  },
};
</script>

<style src="./css_global/style.css"></style>
