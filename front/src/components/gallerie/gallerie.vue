<template>
  <div class="page">
    <h1>Gallerie</h1>
    <input
      type="file"
      @change="getImage"
      id="gallerieImage"
      style="display: none"
    />
    <div v-if="id != null">
      <div class="containerImg" v-if="image == null">
        <button
          class="btnAddImg"
          onclick="document.getElementById('gallerieImage').click();"
        >
          <img class="addImage" src="../../assets/addImage.png" alt="" /> <br />
          ajouter une image
        </button>
      </div>
    </div>

    <div class="viewImage" v-if="image != null">
      <p @click="image = null">x</p>
      <input
        type="file"
        @change="getImage"
        id="modifyImage"
        style="display: none"
      />
      <img
        onclick="document.getElementById('gallerieImage').click()"
        class="modifyImage"
        src="../../assets/editImage.png"
        alt=""
      />
      <img
        v-if="image != null"
        class="imageCategorie"
        :src="getFileImage(image)"
        @load="loadFileImage(image)"
      />
      <button class="sendImage" @click="sendImage">Envoyer</button>
    </div>

    <div class="containerGallerie">
      <img
        @click="afficher(item)"
        v-for="item in gallerie"
        :src="item.image"
        alt=""
      />
    </div>
    <div class="vueImageSelect" v-if="imageSelect != null">
      <div class="entete">
        <div class="containerUser" @click="seeAuthor(imageSelect.userId)">
          <img class="avatar" :src="imageSelect.avatar" alt="" />
          <span>{{ imageSelect.pseudo }}</span>
        </div>
        <div
          class="containerSuppression"
          @click="deleteImage(imageSelect.id)"
          v-if="imageSelect.userId == id || role == 'admin'"
        >
          <p>Supprimer l'image</p>
          <img src="../../assets/corbeille.png" alt="" />
        </div>
        <p @click="imageSelect = null">x</p>
      </div>

      <div class="containerImageSelect">
        <button class="buttonImage" @click="getNextImage('previous')">
          <img class="fleche" src="../../assets/gauche.png" alt="" />
        </button>
        <img :src="imageSelect.image" alt="" />
        <button class="buttonImage" @click="getNextImage('next')">
          <img class="fleche" src="../../assets/droite.png" alt="" />
        </button>
      </div>
    </div>
  </div>
</template>
<script>
import { getFileImage, loadFileImage } from "../../utils.js";
export default {
  name: "gallerie",
  setup() {
    return {
      getFileImage,
      loadFileImage,
    };
  },
  data() {
    return {
      id: localStorage.getItem("animoId"),
      image: null,
      messageError: null,
      errorEnregistrement: null,
      gallerie: [],
      imageSelect: null,
      role: localStorage.getItem("animoRole"),
    };
  },
  methods: {
    async getGallerie() {
      let url = "http://localhost:8000/api/gallerie";
      await axios.get(url).then(
        (res) => {
          this.gallerie = res.data;
        },
        (error) => {}
      );
    },
    async sendImage(e) {
      e.preventDefault();
      let url = "http://localhost:8000/api/gallerie";
      let data = new FormData();
      data.append("image", this.image);
      data.append("userId", this.id);
      await axios.post(url, data).then(
        (res) => {
          this.image = null;
          this.errorEnregistrement = null;
          this.gallerie.push(res.data);
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
      if (
        e.target.files[0].type == "image/png" ||
        e.target.files[0].type == "image/jpeg"
      ) {
        this.image = e.target.files[0];
      } else {
        this.image = null;
        this.messageError = "Le fichier choisi doit être de type png";
        console.log(this.messageError);
      }
    },
    getNextImage(params) {
      let tableau = this.gallerie;
      let index = 0;
      for (let i = 0; i < tableau.length; i++) {
        if (tableau[i].image == this.imageSelect.image) {
          index = i;
        }
      }
      if (params == "next") {
        if (index == tableau.length - 1) {
          this.imageSelect = tableau[0];
        } else {
          this.imageSelect = tableau[index + 1];
        }
      } else if (params == "previous") {
        if (index == 0) {
          this.imageSelect = tableau[tableau.length - 1];
        } else {
          this.imageSelect = tableau[index - 1];
        }
      }
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
    async deleteImage(id) {
      let url = "http://localhost:8000/api/gallerie/" + id;
      await axios.delete(url).then(
        (res) => {
          let tableau = this.gallerie;
          let index = 0;
          for (let i = 0; i < tableau.length; i++) {
            if (tableau[i].image == this.imageSelect.image) {
              index = i;
            }
          }

          if (index == tableau.length - 1) {
            this.imageSelect = tableau[0];
          } else {
            this.imageSelect = tableau[index + 1];
          }
          tableau.splice(index, 1);
        },
        (error) => {}
      );
    },
    afficher(item) {
      document.body.scrollTop = 0;
      document.documentElement.scrollTop = 0;
      this.imageSelect = item;
    },
  },
  beforeMount() {
    this.getGallerie();
  },
};
</script>
<style scoped src="./style.css"></style>
