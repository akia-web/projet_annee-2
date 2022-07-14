<template>
  <div class="page">
    <h2>Modification de l'annonce : {{ titre }}</h2>
    <message-invalide :tableau="message"></message-invalide>

    <div class="formulaire">
      <div class="gauche">
        <div class="flex">
          <input type="datetime-local" name="" id="" v-model="date" />
          <select v-model="categorie">
            <option disabled value="choisir">Choisir une catégorie</option>
            <option v-for="item in allCategories">
              {{ item.name }}
            </option>
          </select>
        </div>

        <input type="text" placeholder="titre" v-model="titre" />
        <br />
        <textarea
          rows="20"
          placeholder="Description"
          v-model="description"
        ></textarea>

        <button @click="afficher" class="btnAddImg" v-if="imgSelected == null">
          Ajouter une image
          <img class="logo" src="../../assets/image.svg" alt="" />
        </button>
        <br />
        <button @click="afficher" class="btnAddImg" v-if="imgSelected != null">
          Modifier l'image
          <img class="logo" src="../../assets/image.svg" alt="" />
        </button>
        <br />

        <img v-if="imgSelected != null" :src="imgSelected" alt="" />
      </div>

      <div class="droite">
        <input type="text" placeholder="adresse" v-model="adresse" />
        <input
          type="text"
          placeholder="complément d'adresse"
          v-model="complementAdresse"
          @change="afficheCarte"
        />
        <input
          type="text"
          placeholder="code postal"
          v-model="codePostal"
          @change="afficheCarte"
        />
        <input
          type="text"
          placeholder="ville"
          v-model="ville"
          @change="afficheCarte"
        />
        <iframe
          v-if="lienCarte != null"
          :src="lienCarte"
          frameborder="0"
        ></iframe>
      </div>
    </div>
    <bouton
      class="center"
      :message="'Valider l\'annonce'"
      @click="sendAnnonce"
    ></bouton>
    <br />

    <!-- POPUP-->
    <div id="searchImage" class="searchImage visibility">
      <span class="fermer" @click="fermer">x</span>
      <h2>Choisissez une image</h2>
      <input
        type="text"
        placeholder="Quel mot défini votre annonce?"
        v-model="motcle"
        v-on:keyup.enter="getImage"
        class="rechercheImage"
      />
      <div class="resultImage">
        <img
          class="image"
          :src="item"
          alt=""
          @click="addImg(item)"
          v-for="item in tableauImages"
        />
      </div>
    </div>
  </div>
</template>

<script>
import Bouton from "../bouton/Bouton.vue";
import MessageInvalide from "../messages/messageInvalide/messageInvalide.vue";
export default {
  components: { Bouton, MessageInvalide },
  name: "AnnoncesModif",
  data() {
    return {
      annonce: [],
      isConnect: false,
      date: null,
      categorie: "choisir",
      allCategories: [],
      titre: null,
      description: null,
      adresse: null,
      complementAdresse: null,
      codePostal: null,
      ville: null,
      image: null,
      lienCarte: null,
      apikey: "563492ad6f91700001000001a504a5075bdc4026b01c6cdeb568c24b",
      images: null,
      motcle: null,
      tableauImages: [],
      imgSelected: null,
      send: false,
      message: [],
      idAnnonce: null,
    };
  },
  methods: {
    async getInfo() {
      let urlEnCours = window.location.href;
      let id = urlEnCours.substring(37);
      let url = "http://localhost:8000/api/annoncesById/" + id;
      console.log(id);
      await axios.get(url).then(
        (res) => {
          this.annonce = res.data;
          var date = new Date(res.data[0].date.timestamp * 1000).toISOString();
          let blabla = date.slice(0, 16);
          console.log(date);
          this.date = blabla;
          this.titre = res.data[0].name;
          this.categorie = res.data[0].categorie.name;
          this.description = res.data[0].description;
          this.imgSelected = res.data[0].images;
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
        },
        (error) => {
          console.log(error);
          this.message.push(
            "une erreur s'est produite lors de la récuperation de l'article"
          );
        }
      );
    },
    afficheCarte() {
      if (
        this.adresse != null &&
        this.codePostal != null &&
        this.ville != null
      ) {
        this.lienCarte =
          "https://www.google.com/maps?q=" +
          this.adresse +
          "," +
          this.codePostal +
          this.ville +
          "&output=embed";
      }
    },

    getSendButton() {
      if (this.ville != null) {
        this.send = true;
      }
    },

    lala() {
      if (this.ville != null) {
        this.send = true;
      }
    },

    async getImage() {
      this.urlApi =
        "https://api.pexels.com/v1/search?query=" +
        this.motcle +
        "&per_page=30&page=1&orientation=landscape";
      await axios
        .get(this.urlApi, {
          headers: {
            Authorization: this.apikey,
          },
        })
        .then(
          (res) => {
            console.log(res.data.photos);
            this.images = res.data.photos;
          },
          (error) => {}
        );
      this.tableauImages = [];
      let response2 = this.images;
      for (let i = 0; i < response2.length; i++) {
        this.tableauImages.push(response2[i].src.landscape);
      }
    },

    addImg(item) {
      if (confirm("voulez-vous choisir cette image") == true) {
        this.imgSelected = item;
        console.log(this.imgSelected);
        this.fermer();
      }
    },

    afficher() {
      let popup = document.querySelector("#searchImage");
      let page = document.querySelector(".formulaire");
      popup.classList.remove("visibility");
      document.body.scrollTop = 0;
      document.documentElement.scrollTop = 0;
      page.style.opacity = 0;
    },

    fermer() {
      let popup = document.querySelector("#searchImage");
      popup.classList.add("visibility");
      let page = document.querySelector(".formulaire");
      page.style.opacity = 1;
    },

    sendAnnonce() {
      let dateDuJour = new Date();
      if (dateDuJour < this.date) {
        console.log("pas bien");
      } else if (dateDuJour > this.date) {
        console.log("bien");
      }
      if (
        this.date != null &&
        this.categorie != null &&
        this.titre != null &&
        this.description != null &&
        this.imgSelected &&
        this.adresse != null &&
        this.codePostal != null &&
        this.ville != null
      ) {
        let annonce = {
          idUser: localStorage.getItem("animoId"),
          emailUser: localStorage.getItem("animoEmail"),
          titre: this.titre,
          description: this.description,
          categorie: this.categorie,
          image: this.imgSelected,
          date: this.date,
          adresse: this.adresse,
          codePostal: this.codePostal,
          ville: this.ville,
        };

        let url = "http://127.0.0.1:8000/api/annonces/" + this.idAnnonce;

        axios.put(url, annonce).then(
          (res) => {
            this.$router.push("/");
          },
          (error) => {
            console.log(error);
            this.message.push(
              "une erreur s'est produite lors de l'inscription"
            );
          }
        );
      } else {
        this.message.push("Tous les champs ne sont pas remplis");
      }
    },
    async findAllcategories() {
      let url = "http://localhost:8000/api/categories";

      await axios.get(url).then(
        (res) => {
          this.allCategories = res.data;
          console.log(res.data);
        },
        (error) => {}
      );
    },
  },
  beforeMount() {
    this.findAllcategories();
    this.getInfo();
  },
};
</script>

<style scoped src="./style.css">
textarea {
  columns: 100%;
}
</style>
