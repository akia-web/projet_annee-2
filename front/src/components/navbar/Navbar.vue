<template>
  <div class="navbar">
    <router-link to="/">
      <img class="logo" src="./../../assets/logo.svg" alt="" />
    </router-link>
    <router-link to="/connexion">
      <svg
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
    </router-link>
  </div>
</template>

<script>
export default {
  name: "Navbar",
  data() {
    return {
      msg: null,
      email: null,
      id: null,
      user: null,
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
          },

          (error) => {}
        );
      }
    },
  },
  beforeMount() {
    this.getUser();
  },
};
</script>
<style src="./../..//css_global/style.css"></style>
<style scoped src="./style.css"></style>
