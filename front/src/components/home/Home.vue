<template>
  <div class="page">
    <div class="baniere"></div>

    <div class="menu">
      <!-- <div>
        <h2>Toutes les annonces</h2>
      </div> -->
    </div>

    <div v-if="affiche">
      <p>Affiner la liste par :</p>
      <div class="menuCategories">
        <span
          v-for="item in categories"
          @click="findAnnonceByCategorieName(item.id)"
          >{{ item.name }}</span
        >

        <span @click="getAllAnnonces">Tous</span>
      </div>

      <div class="containerAnnonces">
        <div class="annonces" v-for="item in resultAnnonces" :key="item.id">
          <h3 class="titre">{{ item.name }}</h3>
          <img :src="item.images" alt="" />

          <div class="mainAnnonces">
            <div class="mainGauche">
              <p class="small">{{ item.user.email }}</p>
              <p class="description small">
                <b> Description </b>:
                <br />
                {{ item.descriptionTronque }}
              </p>
              <p class="small">
                <img class="pin" src="../../assets/pin.jpg" alt="" /> <br />
                {{ item.adresse }} <br />
                {{ item.codepostal }} <br />
                {{ item.ville }}
              </p>
            </div>

            <div class="mainDroite">
              <p class="small">
                <img class="pin" src="../../assets/calendrier.jpg" alt="" />
                <br />
                Le {{ item.date.date }} <br />
                {{ item.date.heure }}
              </p>
            </div>
          </div>

          <bouton
            @click="goAnnonce(item.id)"
            :message="'Détail de l\'annonce'"
          ></bouton>
        </div>
      </div>
    </div>

    <div class="loader" v-if="!affiche">
      <p>Chargement des annonces</p>
      <loader-site></loader-site>
    </div>
  </div>
</template>

<script>
import Bouton from "../bouton/Bouton.vue";
import Annonces from "../annonces/Annonces.vue";
import LoaderSite from "../loaderSite/loaderSite.vue";
export default {
  components: { Bouton, Annonces, LoaderSite },
  name: "Home",
  data() {
    return {
      resultAnnonces: [],
      categories: [],
      affiche: false,
    };
  },
  methods: {
    async getAllAnnonces() {
      let url = "http://127.0.0.1:8000/api/annonces";

      await axios.get(url).then(
        (res) => {
          this.resultAnnonces = [];
          let result = res.data;
          this.affiche = true;

          for (let i = 0; i < result.length; i++) {
            var date = new Date(result[i].date.timestamp * 1000);
            result[i].date.date =
              date.getDate() +
              "/" +
              (date.getMonth() + 1) +
              "/" +
              date.getFullYear();

            result[i].date.heure =
              " à " + date.getHours() + " h " + date.getMinutes();

            if (result[i].description.length < 50) {
              result[i].descriptionTronque = result[i].description;
            } else {
              result[i].descriptionTronque =
                result[i].description.substring(0, 40) + "...";
            }
          }
          this.resultAnnonces = result;
          console.log(result);
        },
        (error) => {}
      );
    },
    async findAllcategories() {
      let url = "http://localhost:8000/api/categories";

      await axios.get(url).then(
        (res) => {
          this.categories = res.data;
          console.log(res.data);
        },
        (error) => {}
      );
    },
    async findAnnonceByCategorieName(id) {
      let url = "http://localhost:8000/api/annonces/" + id;

      await axios.get(url).then(
        (res) => {
          this.resultAnnonces = [];
          let result = res.data;

          for (let i = 0; i < result.length; i++) {
            var date = new Date(result[i].date.timestamp * 1000);
            result[i].date.date =
              date.getDate() +
              "/" +
              (date.getMonth() + 1) +
              "/" +
              date.getFullYear();

            result[i].date.heure =
              " à " + date.getHours() + " h " + date.getMinutes();

            if (result[i].description.length < 50) {
              result[i].descriptionTronque = result[i].description;
            } else {
              result[i].descriptionTronque =
                result[i].description.substring(0, 40) + "...";
            }
          }
          this.resultAnnonces = result;
        },
        (error) => {}
      );
    },

    goAnnonce(id) {
      this.$router.push("/annonce/" + id);
    },
  },
  beforeMount() {
    this.getAllAnnonces();
    this.findAllcategories();
  },
};
</script>
<style scoped src="./style.css"></style>
