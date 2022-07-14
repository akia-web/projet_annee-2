<template>
  <div class="annonces" v-for="item in tableau" :key="item.id">
    <carte :item="item"></carte>
  </div>

 
</template>
<script>
import carte from "../carte/carte.vue";
export default {
  components: { carte },
  methods: {
    goAnnonce(id) {
      this.$router.push("/annonce/" + id);
    },
  },
  props: {
    tableau: Array,
  },
};
</script>

<style scoped src="./style.css"></style>
