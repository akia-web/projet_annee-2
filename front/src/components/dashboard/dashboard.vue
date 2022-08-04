<template>
  <div>
    <h1>Dashboard</h1>
    <bouton
      v-if="role != 'admin'"
      :message="'deconnexion'"
      @click="deconnexion"
    ></bouton>
    <div v-if="role == 'admin'">
      <button @click="categorieGestions">Gerer les catégories</button>
      <button>Créer une nouvelle annonce</button>
    </div>
    <div class="table" v-if="apparait">
      <div class="tbody" v-for="item in resultAnnonces">
        <div>
          <router-link
            class="lienAnnonce"
            :to="{ name: 'modifAnnonce', params: { id: item.id } }"
            >{{ item.name }}</router-link
          >
        </div>
        <div>
          <p>{{ item.date.dates }}</p>
        </div>
        <div>
          <img class="image" :src="item.images" alt="" />
        </div>
        <div>
          <poubelle @click="deleteAnnonce(item.id)"></poubelle>
        </div>
      </div>
    </div>
    <div v-if="!apparait">
      <loader-site></loader-site>
    </div>
  </div>
</template>

<script>
import Bouton from "../bouton/Bouton.vue";
import loaderSite from "../loaderSite/loaderSite.vue";
import Plume from "../plume/plume.vue";
import Poubelle from "../poubelle/poubelle.vue";
export default {
  components: { loaderSite },
  name: "Dashboard",
  data() {
    return {
      resultAnnonces: null,
      apparait: false,
      role: localStorage.getItem("animoRole"),
    };
  },
  methods: {
    deconnexion() {
      localStorage.removeItem("animoId");
      localStorage.removeItem("animoEmail");
      this.$router.push("/");
    },
    async getAllAnnonces() {
      let url = "http://localhost:8000/api/Allannonces";

      await axios.get(url).then(
        (res) => {
          console.log(res.data);
          // this.resultAnnonces = [];
          let result = res.data;

          for (let i = 0; i < result.length; i++) {
            var date = new Date(result[i].date.date);
            result[i].date.dates =
              date.getDate() +
              "/" +
              (date.getMonth() + 1) +
              "/" +
              date.getFullYear();

            result[i].date.heure =
              " à " + date.getHours() + ":" + date.getMinutes();
            if (result[i].description.length < 50) {
              result[i].descriptionTronque = result[i].description;
            } else {
              result[i].descriptionTronque =
                result[i].description.substring(0, 40) + "...";
            }
          }

          this.resultAnnonces = result;
          this.apparait = true;
        },
        (error) => {}
      );
    },
    async deleteAnnonce(id) {
      let url = "http://localhost:8000/api/annonces/" + id;
      await axios.delete(url);
      let index = 0;
      let tableau = this.resultAnnonces;
      for (let i = 0; i < tableau.length; i++) {
        if (tableau[i].id == id) {
          index = i;
          console.log("ok");
        }
      }
      console.log(index);
      setTimeout(function () {
        tableau.splice(index, 1);
      }, 50);
    },
    categorieGestions() {
      this.$router.push("/categories");
    },
  },
  beforeMount() {
    this.getAllAnnonces();
  },
  components: { Plume, Poubelle, Bouton },
};
</script>
<style scoped src="./style.css"></style>
