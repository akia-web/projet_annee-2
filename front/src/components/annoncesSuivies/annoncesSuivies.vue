<template>
  <div class="containerAnnnonceSuivie">
    <h1>Annonces Suivies</h1>
    <div>
      <div class="menuAnnonceFollow">
        <span
          @click="encours"
          v-bind:class="{ active: affiche == 'encours' }"
          class="option"
          >En cours
        </span>

        <span
          @click="passer"
          class="option"
          v-bind:class="{ active: affiche == 'passé' }"
        >
          Passés</span
        >
      </div>

      <div v-if="affiche == 'encours'" class="containerAnnonces">
        <cartes :tableau="annonces.actuelles"></cartes>
      </div>

      <div v-if="affiche == 'passé'" class="containerAnnonces">
        <cartes :tableau="annonces.passees"></cartes>
      </div>
    </div>
  </div>
</template>
<script>
import Cartes from "../cartes/cartes.vue";
export default {
  name: "AnnoncesSuivies",
  data() {
    return {
      tableauEnCours: [],
      tableauPasse: [],
      annonces: [],
      userId: localStorage.getItem("animoId"),
      affiche: "encours",
      result: null,
    };
  },
  methods: {
    passer() {
      this.affiche = "passé";
    },
    encours() {
      this.affiche = "encours";
    },
    async getInfo() {
      let url =
        "http://127.0.0.1:8000/api/allFollowAnnonceByUser/" + this.userId;

      await axios.get(url).then(
        (res) => {
          this.annonces = res.data;
        },

        (error) => {
          console.log(error);
        }
      );
      console.log(this.annonces);
    },
  },

  components: { Cartes },
  beforeMount() {
    this.getInfo();
  },
};
</script>

<style scoped src="./style.css"></style>
