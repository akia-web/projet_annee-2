<template>
  <div class="page">
    <div class="annonce" v-if="affiche">
      <message-invalide :tableau="message"></message-invalide>
      <img class="imgAnnonce" :src="image" alt="" />

      <div class="entete">
        <h2>{{ titre }}</h2>
        <p>Le {{ date }} {{ heure }}</p>
      </div>
      <span><b>catégorie :</b></span> <span>{{ categorie }}</span>
      <p class="description">{{ description }}</p>

      <div class="adresse">
        <div>
          <h3>Le lieu du rendez-vous</h3>

          <p>
            {{ adresse }} <br />
            {{ codePostal }} <br />
            {{ ville }}
          </p>
        </div>

        <iframe
          v-if="lienCarte != null"
          :src="lienCarte"
          frameborder="0"
        ></iframe>
      </div>

      <div v-if="isConnect">
        <div v-if="myAnnonce">
          <bouton
            class="participer"
            :message="'Modifier mon annonce'"
            @click="modifAnnonce"
            v-if="!participe"
          ></bouton>
        </div>

        <div v-else-if="!myAnnonce">
          <bouton
            class="participer"
            :message="'Participer à l\'évenement'"
            @click="participer"
            v-if="!participe"
          ></bouton>

          <bouton
            class="participer"
            :message="'Ne plus participer'"
            @click="nePlusParticiper(idAnnonce)"
            v-if="participe"
          ></bouton>
        </div>
      </div>
    </div>
    <div class="loader" v-if="!affiche">
      <p>Chargement de l'annonce ...</p>
      <loader-site></loader-site>
    </div>
  </div>
</template>

<script>
import Bouton from "../bouton/Bouton.vue";
import LoaderSite from "../loaderSite/loaderSite.vue";
import MessageInvalide from "../messages/messageInvalide/messageInvalide.vue";
export default {
  components: { Bouton, MessageInvalide, LoaderSite },
  name: "DetailAnnonce",
  data() {
    return {
      affiche: false,
      message: [],
      titre: null,
      image: null,
      description: null,
      date: null,
      heure: null,
      adresse: null,
      codePostal: null,
      ville: null,
      lienCarte: null,
      email: localStorage.getItem("animoEmail"),
      userId: localStorage.getItem("animoId"),
      idAnnonce: null,
      participe: null,
      myAnnonce: null,
      isConnect: false,
    };
  },
  methods: {
    userIsConnected() {
      if (localStorage.getItem("animoEmail")) {
        this.isConnect = true;
      } else {
        this.isConnect = false;
      }
    },
    async getInfo() {
      let urlEnCours = window.location.href;
      let id = urlEnCours.substring(30);
      let url = "http://localhost:8000/api/annoncesById/" + id;
      console.log(id);
      await axios.get(url).then(
        (res) => {
          console.log(res.data);
          this.annonce = res.data;
          var date = new Date(res.data[0].date.timestamp * 1000);

          this.date =
            date.getDate() +
            " " +
            this.getMonth(date.getMonth() + 1) +
            " " +
            date.getFullYear();

          this.heure = " à " + date.getHours() + " h " + date.getMinutes();
          this.titre = res.data[0].name;
          this.categorie = res.data[0].categorie.name;
          this.description = res.data[0].description;
          this.image = res.data[0].images;
          this.adresse = res.data[0].adresse;
          this.ville = res.data[0].ville;
          this.codePostal = res.data[0].codepostal;
          this.lienCarte =
            "https://www.google.com/maps?q=" +
            this.adresse +
            "," +
            this.codePostal +
            this.ville +
            "&output=embed";
          this.idAnnonce = id;
          this.affiche = true;

          if (
            res.data[0].user.email == this.email &&
            res.data[0].user.id == this.userId
          ) {
            this.myAnnonce = true;
          } else {
            this.myAnnonce = false;
          }
        },
        (error) => {
          console.log(error);
          this.message.push(
            "une erreur s'est produite lors de la récuperation de l'annonce"
          );
        }
      );
      url =
        "http://localhost:8000/api/isFollowAnnonce?userId=" +
        this.userId +
        "&idAnnonce=" +
        id;

      await axios.get(url).then(
        (res) => {
          this.participe = res.data;
        },
        (error) => {}
      );
    },

    getMonth(month) {
      switch (month) {
        case 1:
          return "janvier";

        case 2:
          return "fevrier";

        case 3:
          return "mars";

        case 4:
          return "avril";

        case 5:
          return "mai";

        case 6:
          return "juin";

        case 7:
          return "juillet";

        case 8:
          return "aout";

        case 9:
          return "septembre";

        case 10:
          return "octobre";

        case 11:
          return "novembre";

        case 12:
          return "décembre";
      }
    },
    async participer() {
      let info = {
        userId: this.userId,
        emailUser: this.email,
        idAnnonce: this.idAnnonce,
      };
      console.log(info);
      let url = "http://localhost:8000/api/followAnnonce";

      await axios.post(url, info).then(
        (res) => {
          this.participe = true;
        },
        (error) => {
          console.log(error);
          this.message.push(
            "une erreur s'est produite lors de l'inscription à la participation"
          );
        }
      );
    },
    async nePlusParticiper(id) {
      let url =
        "http://localhost:8000/api/followAnnonce/" + this.userId + "/" + id;
      await axios.delete(url);
      this.participe = false;
    },
    modifAnnonce() {
      // window.location.href =
      //   "http://localhost:8080/modify-annonce/" + this.idAnnonce;
      this.$router.push({
        name: "modifAnnonce",
        params: { id: this.idAnnonce },
      });
    },
  },
  beforeMount() {
    this.getInfo();
    this.userIsConnected();
  },
};
</script>

<style scoped src="./style.css">
textarea {
  columns: 100%;
}
</style>
