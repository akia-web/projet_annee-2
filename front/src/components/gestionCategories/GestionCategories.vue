<template>
  <div class="component">
    <span
      class="btnCategorie ajout"
      v-if="!ajoutCategorie"
      @click="ajoutCategorie = true"
    >
      Ajouter une categorie
    </span>

    <div class="ContainerNewCategorie" v-if="ajoutCategorie">
      <p class="danger" v-if="errorEnregistrement != null">
        {{ errorEnregistrement }}
      </p>
      <input
        v-model="newCategorie"
        type="text"
        placeholder="Nom de la nouvelle catégorie"
      />
      <br />
      <span>Validation de l'admin pour publication d'annonces </span>
      <input type="checkbox" v-model="validateByAdmin" />

      <p class="error" v-if="messageError != null">{{ messageError }}</p>
      <input type="file" @change="getImage" id="lalala" style="display: none" />

      <div class="containerImg">
        <button
          class="btnAddImg"
          onclick="document.getElementById('lalala').click();"
        >
          <img src="../../assets/upload.png" alt="" />
        </button>
        <img
          v-if="image != null"
          class="imageCategorie"
          :src="getFileImage(image)"
          @load="loadFileImage(image)"
        />
      </div>

      <br />
      <button class="envoyer" :disabled="!isValid" @click="sendCategorie">
        Envoyer la categorie
      </button>
      <span
        class="btnCategorie annuler"
        v-if="ajoutCategorie"
        @click="ajoutCategorie = false"
      >
        Annuler l'ajout de la categorie
      </span>
    </div>

    <table>
      <tr class="entete">
        <th>Nom</th>
        <th>Nombre d'articles</th>
        <th>Image</th>
        <th>categorie admin</th>
        <th>actions</th>
      </tr>
      <tbody>
        <tr v-for="item in categories">
          <td class="titreCategorie" @click="modifier(item)">
            {{ item.name }}
          </td>
          <td>{{ item.annonces }}</td>
          <td><img class="imageCategorie" :src="item.image" alt="" /></td>
          <td><p v-if="item.adminApproval">x</p></td>
          <td>
            <poubelle
              v-if="item.annonces == 0"
              @click="deleteCategorie(item.id)"
            ></poubelle>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import poubelle from "../poubelle/poubelle.vue";
import { getFileImage, loadFileImage } from "../../utils.js";
export default {
  components: { poubelle },
  name: "gestionCategories",
  setup() {
    return {
      getFileImage,
      loadFileImage,
    };
  },
  data() {
    return {
      categories: [],
      newCategorie: null,
      messageError: null,
      image: null,
      ajoutCategorie: false,
      errorEnregistrement: null,
      validateByAdmin: false,
    };
  },
  methods: {
    async getAllCategories() {
      let url = "http://localhost:8000/api/categories";
      await axios.get(url).then(
        (res) => {
          this.categories = res.data;
        },
        (error) => {
          // this.messageError = error.data;
        }
      );
    },
    async sendCategorie(e) {
      e.preventDefault();
      let url = "http://localhost:8000/api/categories";
      let data = new FormData();
      data.append("name", this.newCategorie);
      data.append("image", this.image);
      data.append("adminApproval", this.validateByAdmin);
      await axios.post(url, data).then(
        (res) => {
          this.categories.push(res.data);
          this.newCategorie = null;
          this.image = null;
          this.errorEnregistrement = null;
          this.validateByAdmin = false;
        },
        (error) => {
          console.error(error.response);
          this.errorEnregistrement = error.response.data;
        }
      );
    },
    async deleteCategorie(id) {
      let url = "http://localhost:8000/api/categories/" + id;
      await axios.delete(url);
      let index = 0;
      let tableau = this.categories;
      for (let i = 0; i < tableau.length; i++) {
        if (tableau[i].id == id) {
          index = i;
        }
      }
      tableau.splice(index, 1);
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
    modifier(item) {
      let sendData = JSON.stringify(item);
      this.$router.push({ name: "modifCategorie", params: { data: sendData } });
    },
  },
  computed: {
    isValid() {
      return this.newCategorie != null && this.image != null;
    },
  },
  beforeMount() {
    this.getAllCategories();
  },
};
</script>
<style scoped src="./style.css"></style>
