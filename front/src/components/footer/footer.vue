<template>
  <div class="flex">
    <div>
      <span @click="goTo('/mentions-legales')"> Mentions légales </span> <br />
      <span @click="goTo('/politique-confidentialite')">
        Politique de confidentialité
      </span>
    </div>
    <div>
      <span>Contact : contact@pet-activity.com</span> <br />
      <span>Téléphone : 06 00 00 00 00</span>
    </div>
  </div>
</template>
<script>
export default {
  name: "Footer",
  data() {
    return {};
  },
  methods: {
    goTo(adress) {
      this.$router.push(adress);
    },
  },
  beforeMount() {},
};
</script>
<style scoped src="./style.css"></style>
