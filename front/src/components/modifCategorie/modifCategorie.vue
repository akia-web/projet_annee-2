<template>
  <div class="ContainerNewCategorie">
    <h1>Modification de la categorie {{ titre }}</h1>
    <p class="danger" v-if="errorEnregistrement != null">
      {{ errorEnregistrement }}
    </p>
    <input
      v-model="categorie.name"
      type="text"
      placeholder="Nom de la nouvelle catégorie"
    />
    <br />
    <span>Validation de l'admin pour publication d'annonces </span>
    <input type="checkbox" v-model="categorie.adminApproval" />

    <p class="error" v-if="messageError != null">{{ messageError }}</p>

    <input type="file" @change="getImage" id="lalala" style="display: none" />

    <div class="containerImg">
      <button
        class="btnAddImg"
        onclick="document.getElementById('lalala').click();"
      >
        <img src="../../assets/upload.png" alt="" />
      </button>

      <img class="imageCategorie" :src="categorie.image" alt="" />
      <img
        v-if="image != null"
        class="imageFleche"
        src="../../assets/fleche-droite.png"
        alt=""
      />

      <img
        v-if="image != null"
        class="imageCategorie"
        :src="getFileImage(image)"
        @load="loadFileImage(image)"
      />
    </div>

    <button class="envoyer" :disabled="!isValid" @click="sendCategorie">
      Envoyer la categorie
    </button>
  </div>
</template>

<script>
import { getFileImage, loadFileImage } from "../../utils.js";
export default {
  name: "modifCategorie",
  setup() {
    return {
      getFileImage,
      loadFileImage,
    };
  },
  data() {
    return {
      messageError: null,
      errorEnregistrement: null,
      categorie: null,
      image: null,
      titre: null,
    };
  },
  methods: {
    getInfo() {
      let transformDataToObject = JSON.parse(this.data);
      this.categorie = transformDataToObject;
      this.titre = this.categorie.name;
    },

    async sendCategorie(e) {
      e.preventDefault();
      let url = "http://localhost:8000/api/modifCategories";
      let data = new FormData();
      data.append("name", this.categorie.name);
      data.append("image", this.image);
      data.append("adminApproval", this.validateByAdmin);
      data.append("id", this.categorie.id);
      await axios.post(url, data).then(
        (res) => {
          window.location.href = "/categories";
        },
        (error) => {
          console.error(error.response);
          this.errorEnregistrement = error.response.data;
        }
      );
    },

    getImage(e) {
      console.log(e.target.files[0]);
      this.messageError = null;
      if (e.target.files[0].type != "image/png") {
        this.image = null;
        this.messageError = "Le fichier choisi doit être de type png";
      } else {
        this.image = e.target.files[0];
      }
    },
  },
  computed: {
    isValid() {
      return this.categorie.name != "";
    },
  },
  beforeMount() {
    this.getInfo();
  },
  props: {
    data: String,
  },
};
</script>
<style scoped src="./style.css"></style>
