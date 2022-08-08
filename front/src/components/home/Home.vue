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
          @click="getAllAnnonces"
          v-bind:class="{ active: categorie == 'toutes' }"
        >
          Tous
          <img class="imgCategorie" src="../../assets/tous.png" />
        </span>
        <span
          v-for="item in categories"
          @click="findAnnonceByCategorieName(item.id)"
          v-bind:class="{ active: categorie == item.name }"
          >{{ item.name }}
          <img
            class="imgCategorie"
            :src="require(`@/assets/${item.name}.png`)"
          />
        </span>
      </div>

      <div class="containerAnnonces">
        <div class="annonces" v-for="item in resultAnnonces" :key="item.id">
          <div class="containerTitre">
            <div></div>
            <h3 class="titre">
              {{ item.name }}
            </h3>
            <img
              class="imgCategorie"
              :src="require(`@/assets/${item.categorie.name}.png`)"
            />
          </div>

          <img :src="item.images" alt="" />

          <div class="mainAnnonces">
            <div class="mainGauche">
              <div class="avatar" @click="seeAuthor(item.authorId)">
                <img :src="item.avatar" alt="" />
                <p class="small">
                  {{ item.pseudo }}
                </p>
              </div>

              <p class="description small">
                <b> Description </b>:
                <br />
                {{ item.descriptionTronque }}
              </p>
              <p class="small">
                <img class="pin" src="../../assets/pin.jpg" alt="" /> <br />
                {{ item.adresse }} <br />
                {{ item.codePostal }} <br />
                {{ item.ville }}
              </p>
            </div>

            <div class="mainDroite">
              <p class="small">
                <img class="pin" src="../../assets/calendrier.jpg" alt="" />
                <br />
                Le {{ item.date }} <br />
                {{ item.minute }}
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
      categorie: "toutes",
    };
  },
  methods: {
    async getAllAnnonces() {
      this.categorie = "toutes";
      let url = "http://127.0.0.1:8000/api/annonces/now";

      await axios.get(url).then(
        (res) => {
          console.log(res.data);
          this.resultAnnonces = [];
          let result = res.data;
          this.affiche = true;

          for (let i = 0; i < result.length; i++) {
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
          console.log(res.data);

          for (let i = 0; i < result.length; i++) {
            if (result[i].description.length < 50) {
              result[i].descriptionTronque = result[i].description;
            } else {
              result[i].descriptionTronque =
                result[i].description.substring(0, 40) + "...";
            }
          }
          this.resultAnnonces = result;
          this.categorie = result[0].categorie.name;
        },
        (error) => {}
      );
    },

    goAnnonce(id) {
      this.$router.push("/annonce/" + id);
    },
    async seeAuthor(id) {
      let url = "http://localhost:8000/api/seeAnnoncesByUser/" + id;
      let data = null;
      await axios.get(url).then(
        (res) => {
          data = JSON.stringify(res.data);
          this.$router.push({
            name: "getAuthor",
            params: { data: data },
          });
        },
        (error) => {}
      );
    },
  },
  beforeMount() {
    this.getAllAnnonces();
    this.findAllcategories();
  },
};
</script>
<style scoped src="./style.css"></style>
