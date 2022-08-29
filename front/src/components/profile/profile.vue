<template>
  <div class="container">
    <div class="gauche">
      <span
        class="menuUser"
        v-bind:class="{ active: lien == 'info' }"
        @click="lien = 'info'"
        >Informations</span
      >
      <span
        class="menuUser"
        v-bind:class="{ active: lien == 'annonces' }"
        @click="lien = 'annonces'"
        >Mes annonces</span
      >
      <span
        class="menuUser"
        v-bind:class="{ active: lien == 'annoncesSuivies' }"
        @click="lien = 'annoncesSuivies'"
        >Annonces suivies</span
      >
      <span
        class="menuUser"
        v-bind:class="{ active: lien == 'message' }"
        @click="lien = 'message'"
        >Message</span
      >
    </div>
    <div class="profileUser">
      <div class="droite" v-if="lien == 'info'">
        <user-app></user-app>
      </div>
      <div class="droite" v-if="lien == 'annonces'">
        <tableau-annonces></tableau-annonces>
      </div>
      <div class="droite" v-if="lien == 'annoncesSuivies'">
        <annonces-suivies></annonces-suivies>
      </div>
      <div class="droite" v-if="lien == 'message'">
        <messages-user></messages-user>
      </div>
    </div>
  </div>
</template>
<script>
import AnnoncesSuivies from "../annoncesSuivies/annoncesSuivies.vue";
import Bouton from "../bouton/Bouton.vue";
import TableauAnnonces from "../tableauAnnonces/tableauAnnonces.vue";
import UserApp from "../user/UserApp.vue";
import MessagesUser from "../MessagesUser/messagesUser.vue";
export default {
  name: "Profile",
  data() {
    return {
      lien: "info",
    };
  },
  methods: {},
  components: {
    UserApp,
    Bouton,
    TableauAnnonces,
    AnnoncesSuivies,
    MessagesUser,
  },
  beforeMount() {},
};
</script>

<style scoped src="./style.css"></style>
