<template>
  <div class="component">
    <h1>Gestion des catégories</h1>

    <input
      v-model="newCategorie"
      type="text"
      placeholder="Nom de la nouvelle catégorie"
      @keyup.enter="sendCategorie"
    />
    <p v-for="item in categories">
      {{ item.name }}
      <span v-if="item.annonces > 0">({{ item.annonces }} annonces)</span>
      <poubelle
        v-if="item.annonces == 0"
        @click="deleteCategorie(item.id)"
      ></poubelle>
    </p>
  </div>
</template>

<script>
import poubelle from "../poubelle/poubelle.vue";
export default {
  components: { poubelle },
  name: "gestionCategories",
  data() {
    return {
      categories: [],
      newCategorie: null,
      messageError: null,
    };
  },
  methods: {
    async getAllCategories() {
      let url = "http://localhost:8000/api/categories";
      await axios.get(url).then(
        (res) => {
          this.categories = res.data;
        },
        (error) => {}
      );
    },
    async sendCategorie() {
      let url = "http://localhost:8000/api/categories";
      let data = { name: this.newCategorie };
      await axios.post(url, data).then(
        (res) => {
          this.categories.push(res.data);
          this.newCategorie = null;
        },
        (error) => {
          this.messageError =
            "L'enregistrement de la nouvelle catégorie à échouée";
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
  },
  beforeMount() {
    this.getAllCategories();
  },
};
</script>
<style scoped src="./style.css"></style>
