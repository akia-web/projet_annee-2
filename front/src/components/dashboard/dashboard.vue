<template>
  <div class="page">
    <h1>Dashboard</h1>
    <div class="containerMenu">
      <span
        class="menuDashboard"
        v-bind:class="{ menuDashboardActive: template == 'allAnnonces' }"
        @click="template = 'allAnnonces'"
        >Toutes les annonces</span
      >
      <span
        class="menuDashboard"
        @click="template = 'categorie'"
        v-bind:class="{ menuDashboardActive: template == 'categorie' }"
        >Gerer les catégories</span
      >
      <span class="menuDashboard" @click="nouvelleAnnonce"
        >Créer une nouvelle annonce</span
      >
      <span
        class="menuDashboard"
        @click="template = 'messages'"
        v-bind:class="{ menuDashboardActive: template == 'messages' }"
        >Messages</span
      >
      <span
        class="menuDashboard"
        @click="template = 'gallerie'"
        v-bind:class="{ menuDashboardActive: template == 'gallerie' }"
        >Gallerie</span
      >
      <span class="menuDashboard rouge" @click="deconnexion">Déconnexion</span>
    </div>

    <div v-if="template == 'allAnnonces'">
      <div class="containerAnnonceOptions">
        <span
          v-bind:class="{ annonceOptionActive: periode == 'actuelle' }"
          class="annoncesOption"
          @click="periode = 'actuelle'"
          >Actuelles</span
        >
        <span
          v-bind:class="{ annonceOptionActive: periode == 'passer' }"
          class="annoncesOption"
          @click="periode = 'passer'"
          >Passées</span
        >
      </div>

      <div class="table" v-if="apparait">
        <div
          class="tbody"
          v-for="item in resultAnnonces.actuelles"
          v-if="periode == 'actuelle'"
        >
          <div>
            <router-link
              class="lienAnnonce"
              :to="{ name: 'modifAnnonce', params: { id: item.id } }"
              >{{ item.name }}</router-link
            >
          </div>
          <div>
            <p>{{ item.date }}</p>
          </div>
          <div>
            <img class="image" :src="item.images" alt="" />
          </div>
          <div>
            <img class="logo" :src="item.categorie" alt="" />
          </div>
          <div>
            <input
              type="checkbox"
              v-model="item.isApprouved"
              @click="modifApprouved(item)"
            />
          </div>
          <div>
            <poubelle @click="deleteAnnonce(item.id)"></poubelle>
          </div>
        </div>

        <div
          class="tbody"
          v-for="item in resultAnnonces.passees"
          v-if="periode == 'passer'"
        >
          <div>
            <router-link
              class="lienAnnonce"
              :to="{ name: 'modifAnnonce', params: { id: item.id } }"
              >{{ item.name }}</router-link
            >
          </div>
          <div>
            <p>{{ item.date }}</p>
          </div>
          <div>
            <img class="image" :src="item.images" alt="" />
          </div>
          <div>
            <img class="logo" :src="item.categorie" alt="" />
          </div>
          <div>
            <input
              type="checkbox"
              v-model="item.isApprouved"
              @click="modifApprouved(item)"
            />
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
    <div v-if="template == 'categorie'">
      <gestion-categories></gestion-categories>
    </div>
    <div v-if="template == 'messages'">
      <messages-user></messages-user>
    </div>
    <div v-if="template == 'gallerie'">
      <gallerie></gallerie>
    </div>
  </div>
</template>

<script>
import Bouton from "../bouton/Bouton.vue";
import Gallerie from "../gallerie/gallerie.vue";
import GestionCategories from "../gestionCategories/GestionCategories.vue";
import LoaderSite from "../loaderSite/loaderSite.vue";
import MessagesUser from "../MessagesUser/messagesUser.vue";
import Plume from "../plume/plume.vue";
import Poubelle from "../poubelle/poubelle.vue";
export default {
  components: {
    Plume,
    Poubelle,
    Bouton,
    LoaderSite,
    GestionCategories,
    MessagesUser,
    Gallerie,
  },
  name: "Dashboard",
  data() {
    return {
      resultAnnonces: null,
      apparait: false,
      role: localStorage.getItem("animoRole"),
      periode: "actuelle",
      template: "allAnnonces",
    };
  },
  methods: {
    deconnexion() {
      localStorage.removeItem("animoId");
      localStorage.removeItem("animoEmail");
      localStorage.removeItem("animoRole");
      document.location.href = "/";
    },
    async getAllAnnonces() {
      if (this.role != "admin") {
        this.$router.push("/");
      }
      let url = "http://localhost:8000/api/Allannonces";

      await axios.get(url).then(
        (res) => {
          this.resultAnnonces = res.data;

          this.apparait = true;
        },
        (error) => {}
      );
    },
    async deleteAnnonce(id) {
      let url = "http://localhost:8000/api/annonces/" + id;
      await axios.delete(url);
      let index = 0;
      let tableau = null;
      if (this.periode == "actuelle") {
        tableau = this.resultAnnonces.actuelles;
      } else {
        tableau = this.resultAnnonces.passees;
      }
      for (let i = 0; i < tableau.length; i++) {
        if (tableau[i].id == id) {
          index = i;
          console.log("ok");
        }
      }

      console.log(index);

      tableau.splice(index, 1);
    },
    categorieGestions() {
      this.$router.push("/categories");
    },
    async modifApprouved(item) {
      let data = item;
      data.role = this.role;
      let url = "http://localhost:8000/api/changeApprouved";

      await axios.put(url, data);
    },
    nouvelleAnnonce() {
      this.$router.push("/nouvelle-annonce");
    },
  },
  beforeMount() {
    this.getAllAnnonces();
  },
};
</script>
<style scoped src="./style.css"></style>
