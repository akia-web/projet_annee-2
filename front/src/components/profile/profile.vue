<template>
  <div class="container">
    <div class="gauche">
      <button @click="lien = 'info'">Informations</button>
      <button @click="lien = 'annonces'">Mes annonces</button>
      <button @click="lien = 'annoncesSuivies'">Annonces suivies</button>
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
    </div>
  </div>
</template>
<script>
import AnnoncesSuivies from "../annoncesSuivies/annoncesSuivies.vue";
import Bouton from "../bouton/Bouton.vue";
import TableauAnnonces from "../tableauAnnonces/tableauAnnonces.vue";
import UserApp from "../user/UserApp.vue";
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
  },
  beforeMount() {},
};
</script>

<style scoped src="./style.css"></style>
