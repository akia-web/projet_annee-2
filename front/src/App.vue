<template>
  <Navbar />
 <!-- <HelloWorld msg="Welcome to Your Vue.js App"/> -->
 

  <!--  <router-link :to="{name: 'dynamic', params: { id: 33 } }"> page </router-link> -->

    <router-view />
</template>



<script>
import HelloWorld from './components/HelloWorld.vue'

import LalalaMessage from './components/LalalaMessage.vue'

import ConnexionApp from './components/connexion/ConnexionApp.vue'

import Navbar from './components/navbar/Navbar.vue'


export default {
  name: 'App',
  data(){
    return{
      Authenticated: false,
      email: null,
      id:null
    }
  },

  components: {
     HelloWorld,
    'lalala': LalalaMessage,
    ConnexionApp,
    Navbar
  }
}
</script>

<style src="./css_global/style.css">

</style>
