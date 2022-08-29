<template>
  <div class="page">
    <div class="entete">
      <img :src="info.profilImage" alt="" />
      <p>{{ info.pseudo }}</p>
    </div>
    <p>Nombre d'annonces postées : {{ info.totalAnnonces }}</p>
    <h2>Annonces en cours</h2>
    <div class="containerAnnonces">
      <div class="annonce" v-for="item in info.annonces">
        <img :src="item.images" alt="" />
        <p>{{ item.name }}</p>
        <p>{{ item.date }}</p>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "infoAuthor",
  data() {
    return {
      info: null,
    };
  },
  methods: {
    getInfo() {
 
      window.scrollTo(0, 0);

      this.info = JSON.parse(this.data);

      console.log(this.info);
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
