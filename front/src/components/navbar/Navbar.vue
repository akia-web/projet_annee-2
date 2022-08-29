<template>
  <div class="navbar">
    <img
      @click="goTo('')"
      class="logo pointer"
      src="./../../assets/logo.svg"
      alt=""
    />

    <div
      class="containerImageMenu"
      @click="goTo('bonnes-adresses')"
      v-bind:class="{ active: page == 'bonnes-adresses' }"
    >
      <img class="navImg" src="../../assets/lieux.png" alt="" />
      <p>Bonnes adresses</p>
    </div>

    <div
      class="containerImageMenu"
      @click="goTo('gallerie')"
      v-bind:class="{ active: page == 'gallerie' }"
    >
      <img class="navImg" src="../../assets/galerie.png" alt="" />
      <p>Gallerie</p>
    </div>

    <div @click="goTo('connexion')">
      <svg
        v-if="image == null"
        xmlns="http://www.w3.org/2000/svg"
        fill="#84c476"
        class="user"
        viewBox="0 0 16 16"
      >
        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
        <path
          fill-rule="evenodd"
          d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"
        />
      </svg>
      <img v-if="image != null" :src="image" alt="" class="user" />
    </div>
  </div>
</template>

<script>
import { bus } from "../../main";
export default {
  name: "Navbar",
  data() {
    return {
      msg: null,
      email: null,
      id: null,
      user: null,
      image: null,
      role: localStorage.getItem("animoRole"),
      page: null,
    };
  },
  methods: {
    async getUser() {
      this.email = localStorage.getItem("animoEmail");
      this.id = localStorage.getItem("animoId");
      if (this.email != null && this.id != null) {
        let url =
          "http://127.0.0.1:8000/api/user?email=" +
          this.email +
          "&id=" +
          this.id;

        await axios.get(url).then(
          (res) => {
            console.log(res.data);
            this.user = res.data;
            if (res.data.image != null) {
              this.image = "http://localhost:8000/uploads/" + res.data.image;
            }
          },

          (error) => {}
        );
      }
    },
    goTo(adresse) {
      this.$router.push("/" + adresse);
      this.page = adresse;
    },
    deconnexion() {
      localStorage.removeItem("animoId");
      localStorage.removeItem("animoEmail");
      localStorage.removeItem("animoRole");
      this.role = "";
      this.image = null;
      this.$router.push("/");
    },
  },
  beforeMount() {
    this.getUser();
  },
  created() {
    bus.on("update-image", (data) => {
      this.image = data;
    });
  },
};
</script>
<style src="./../..//css_global/style.css"></style>
<style scoped src="./style.css"></style>
