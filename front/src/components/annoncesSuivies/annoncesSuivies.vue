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
        <cartes :tableau="tableauPasse"></cartes>
      </div>

      <div v-if="affiche == 'passé'">
        <cartes :tableau="tableauEnCours"></cartes>
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
          console.log(res.data);
          this.result = res.data;
          for (let i = 0; i < this.result.length; i++) {
            if (this.result[i].description.length < 50) {
              this.result[i].descriptionTronque = this.result[i].description;
            } else {
              this.result[i].descriptionTronque =
                this.result[i].description.substring(0, 40) + "...";
            }

            let date = this.result[i].date.date;
            this.result[i].date.dates = date.slice(0, 10);
            this.result[i].date.heure = date.slice(11, 16);
            let dateAnnonce = new Date(this.result[i].date.dates);

            let dateAnnonceConvertie =
              dateAnnonce.getFullYear() +
              "-" +
              (dateAnnonce.getMonth() + 1) +
              "-" +
              dateAnnonce.getDate();

            let dateJour = new Date(Date.now());
            let dateDuJourConvertie =
              dateJour.getFullYear() +
              "-" +
              (dateJour.getMonth() + 1) +
              "-" +
              dateJour.getDate();
            console.log(dateDuJourConvertie <= dateAnnonceConvertie);
            console.log("date du jour " + dateDuJourConvertie);
            console.log("date de l'annonce " + dateAnnonceConvertie);

            if (dateDuJourConvertie <= dateAnnonceConvertie) {
              this.tableauEnCours.push(this.result[i]);
            } else {
              this.tableauPasse.push(this.result[i]);
            }
          }

          // for (let j = 0; j < resultAnnonces.length; j++) {
          //
          // },
        },

        (error) => {
          console.log(error);
        }
      );
    },
  },

  components: { Cartes },
  beforeMount() {
    this.getInfo();
  },
};
</script>

<style scoped src="./style.css"></style>
