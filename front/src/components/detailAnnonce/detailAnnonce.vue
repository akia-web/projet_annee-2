<template>
  <div class="page">
    <div class="annonce" v-if="affiche">
      <message-invalide :tableau="message"></message-invalide>
      <img class="imgAnnonce" :src="annonce.images" alt="" />
      <p class="warning" v-if="!annonce.isActual">
        /!\ Cet événement n'est plus d'actualité.
      </p>
      <h2>{{ annonce.name }}</h2>
      <div class="entete">
        <div>
          <div class="avatar">
            <img :src="annonce.avatar" alt="" />
            <p class="small">{{ annonce.pseudo }}</p>
          </div>
          <span><b>catégorie :</b></span>
          <span>{{ annonce.categorie.name }}</span>
          <p class="description">{{ annonce.description }}</p>
        </div>

        <div class="containerDate">
          <div class="date">
            <img class="picto" src="../../assets/calendrier.jpg" alt="" />
            <p>{{ annonce.date }}</p>
          </div>

          <div class="date">
            <img class="picto" src="../../assets/horloge.png" alt="" />
            <p>{{ annonce.minute }}</p>
          </div>
        </div>
      </div>

      <div class="adresse">
        <div>
          <h3>Le lieu du rendez-vous</h3>

          <p>
            {{ annonce.adresse }} <br />
            {{ annonce.codePostal }} <br />
            {{ annonce.ville }}
          </p>
        </div>

        <iframe
          v-if="lienCarte != null"
          :src="lienCarte"
          frameborder="0"
        ></iframe>
      </div>

      <div v-if="isConnect">
        <div v-if="annonce.isActual">
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
              @click="nePlusParticiper(annonce.id)"
              v-if="participe"
            ></bouton>
          </div>
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
      lienCarte: null,
      email: localStorage.getItem("animoEmail"),
      userId: localStorage.getItem("animoId"),
      idAnnonce: null,
      participe: null,
      myAnnonce: null,
      isConnect: false,
      annonce: null,
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

          this.lienCarte =
            "https://www.google.com/maps?q=" +
            this.annonce.adresse +
            "," +
            this.annonce.codePostal +
            this.annonce.ville +
            "&output=embed";
          this.affiche = true;

          if (res.data.authorEmail == this.email) {
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
        params: { id: this.annonce.id },
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
