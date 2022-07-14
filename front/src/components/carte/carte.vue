<template>
  <div>
    <h3 @click="goAnnonce(item.id)" class="titre option">{{ item.name }}</h3>
    <img :src="item.images" alt="" />

    <div class="mainAnnonces">
      <div class="mainGauche">
        <p class="description small">
          <b> Description </b>:
          <br />
          {{ item.descriptionTronque }}
        </p>
        <p class="small">
          <img class="pin" src="../../assets/pin.jpg" alt="" /> <br />
          {{ item.adresse }} <br />
          {{ item.codepostal }} <br />
          {{ item.ville }}
        </p>
      </div>

      <div class="mainDroite">
        <p class="small">
          <img class="pin" src="../../assets/calendrier.jpg" alt="" />
          <br />
          Le {{ item.date.dates }} <br />
          {{ item.date.heure }}
        </p>
      </div>
    </div>

    <bouton
      :message="'Ne plus participer'"
      @click="removeFollow(item.id)"
      v-if="participe"
    ></bouton>
  </div>
</template>

<script>
import Bouton from "../bouton/Bouton.vue";

export default {
  components: { Bouton },
  data() {
    return {
      participe: null,
    };
  },
  methods: {
    goAnnonce(id) {
      this.$router.push("/annonce/" + id);
    },
    async removeFollow(id) {
      let userId = localStorage.getItem("animoId");
      let url = "http://localhost:8000/api/followAnnonce/" + userId + "/" + id;
      await axios.delete(url);
      this.participe = false;
    },
  },
  props: {
    item: Object,
  },
};
</script>
<style scoped src="./style.css"></style>
