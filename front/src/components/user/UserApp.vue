<template>
  <div class="identity">
    <h1>Informations personnelles</h1>
    <div v-if="messages[0]">
      <!-- <p v-for="(item, index) in messages"> {{item}}</p> -->
      <message-invalide
        v-for="(item, index) in messages"
        :message="messages[index]"
      ></message-invalide>
    </div>
    <div class="alert alertGreen" v-if="messageEmail != null">
      <p>{{ messageEmail }}</p>
    </div>

    <p v-if="!wantToModifyImage">
      <img
        v-if="image == null"
        class="user2"
        src="../../assets/person-circle.svg"
        alt=""
      />

      <img v-if="image != null" class="user2" :src="image" alt="" />
      <span class="phraseLogoImage" @click="changeModifyImage"
        >Modifier la photo de profile</span
      >
    </p>

    <div class="uploadImage" v-if="wantToModifyImage">
      <div
        class="containerImage"
        @drop="dropImage"
        @dragenter="dragOver"
        @dragover.prevent=""
        @dragleave="dragLeave"
      >
        <svg
          version="1.1"
          id="Capa_1"
          xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink"
          x="0px"
          y="0px"
          viewBox="0 0 486.3 486.3"
          style="enable-background: new 0 0 486.3 486.3"
          xml:space="preserve"
        >
          <g>
            <g>
              <path
                d="M395.5,135.8c-5.2-30.9-20.5-59.1-43.9-80.5c-26-23.8-59.8-36.9-95-36.9c-27.2,0-53.7,7.8-76.4,22.5
			c-18.9,12.2-34.6,28.7-45.7,48.1c-4.8-0.9-9.8-1.4-14.8-1.4c-42.5,0-77.1,34.6-77.1,77.1c0,5.5,0.6,10.8,1.6,16
			C16.7,200.7,0,232.9,0,267.2c0,27.7,10.3,54.6,29.1,75.9c19.3,21.8,44.8,34.7,72,36.2c0.3,0,0.5,0,0.8,0h86
			c7.5,0,13.5-6,13.5-13.5s-6-13.5-13.5-13.5h-85.6C61.4,349.8,27,310.9,27,267.1c0-28.3,15.2-54.7,39.7-69
			c5.7-3.3,8.1-10.2,5.9-16.4c-2-5.4-3-11.1-3-17.2c0-27.6,22.5-50.1,50.1-50.1c5.9,0,11.7,1,17.1,3c6.6,2.4,13.9-0.6,16.9-6.9
			c18.7-39.7,59.1-65.3,103-65.3c59,0,107.7,44.2,113.3,102.8c0.6,6.1,5.2,11,11.2,12c44.5,7.6,78.1,48.7,78.1,95.6
			c0,49.7-39.1,92.9-87.3,96.6h-73.7c-7.5,0-13.5,6-13.5,13.5s6,13.5,13.5,13.5h74.2c0.3,0,0.6,0,1,0c30.5-2.2,59-16.2,80.2-39.6
			c21.1-23.2,32.6-53,32.6-84C486.2,199.5,447.9,149.6,395.5,135.8z"
              />
              <path
                d="M324.2,280c5.3-5.3,5.3-13.8,0-19.1l-71.5-71.5c-2.5-2.5-6-4-9.5-4s-7,1.4-9.5,4l-71.5,71.5c-5.3,5.3-5.3,13.8,0,19.1
			c2.6,2.6,6.1,4,9.5,4s6.9-1.3,9.5-4l48.5-48.5v222.9c0,7.5,6,13.5,13.5,13.5s13.5-6,13.5-13.5V231.5l48.5,48.5
			C310.4,285.3,318.9,285.3,324.2,280z"
              />
            </g>
          </g>
        </svg>
      </div>
      <span @click="changeModifyImage">Annuler</span>
    </div>
    <div class="detailImage" v-if="photoName != null">
      <img
        v-if="photoName != null"
        :src="getFileImage(fileImage)"
        @load="loadFileImage(fileImage)"
        alt=""
      />
      <p>{{ fileImage.name }}</p>
      <div class="containerOptions">
        <span class="options optionGreen" @click="validerImage">v</span>
        <span class="options optionRed" @click="annulerImage">x</span>
      </div>
    </div>

    <p>
      Email :
      <span class="email1" v-if="!inputEmail"> {{ email }}</span>
      <span v-if="inputEmail" class="flex">
        <input
          id="newEmail"
          class="inputEmail"
          type="text"
          placeholder="nouvel Email"
        />
        <!-- <button @click="inputEmail = false">x</button>
        <button @click="sendNewEmail">v</button> -->
        <div class="containerOptions">
          <span class="options optionGreen" @click="sendNewEmail">v</span>
          <span class="options optionRed" @click="inputEmail = false">x</span>
        </div>
      </span>
      <plume @click="modifEmail" v-if="!inputEmail" title="Modifier"></plume>
    </p>

    <p>
      Pseudo :
      <span class="email1" v-if="!inputPseudo"> {{ pseudo }}</span>
      <span v-if="inputPseudo" class="flex">
        <input
          id="newPseudo"
          class="inputEmail"
          type="text"
          placeholder="nouveau pseudo"
        />
        <div class="containerOptions">
          <span class="options optionGreen" @click="sendNewPseudo">v</span>
          <span class="options optionRed" @click="inputPseudo = false">x</span>
        </div>

        <!-- <button @click="inputPseudo = false">x</button>
        <button @click="sendNewPseudo">v</button> -->
        <span class="danger" v-if="pseudoError != null">{{ pseudoError }}</span>
      </span>
      <plume @click="modifPseudo" v-if="!inputPseudo"></plume> <br />
    </p>

    <bouton message="se déconnecter" @click="disconnect"></bouton>
    <span class="red" @click="supprimer(id)">Supprimer le compte</span>
  </div>
</template>

<script>
import Bouton from "../bouton/Bouton.vue";
import MessageInvalide from "../messages/messageInvalide/messageInvalide.vue";
import Plume from "../plume/plume.vue";
import { getFileImage, loadFileImage } from "../../utils.js";
import { bus } from "../../main";

export default {
  name: "UserApp",
  setup() {
    return {
      getFileImage,
      loadFileImage,
    };
  },
  data() {
    return {
      email: localStorage.getItem("animoEmail"),
      id: localStorage.getItem("animoId"),
      inputEmail: false,
      inputPseudo: false,
      messages: [],
      emailValide: false,
      messageEmail: null,
      id: localStorage.getItem("animoId"),
      pseudoError: null,
      pseudo: null,
      image: null,
      fileImage: [],
      photoName: null,
      wantToModifyImage: false,
      selectedFile: null,
    };
  },
  methods: {
    modifEmail() {
      if (this.inputEmail == true) {
        this.inputEmail = false;
      } else {
        this.inputEmail = true;
      }
    },

    modifPseudo() {
      if (this.inputPseudo == true) {
        this.inputPseudo = false;
      } else {
        this.inputPseudo = true;
      }
    },
    disconnect() {
      localStorage.removeItem("animoId");
      localStorage.removeItem("animoEmail");
      localStorage.removeItem("animoRole");

      document.location.href = "/";
    },

    sendNewEmail() {
      const email = document.querySelector("#newEmail");
      const regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
      let user = {};

      // validation de l'email
      if (email.value.length < 1) {
        this.messages.push("l'email est vide");
        email.classList.add("incorrect");
      } else if (email.value.length > 0 && !email.value.match(regex)) {
        this.messages.push("Email invalide.");
        this.messages.push("ex : jean@exemple.com");
        email.classList.add("incorrect");
      } else {
        email.classList.remove("incorrect");
        this.emailValide = true;
      }

      if (this.emailValide == true) {
        user = JSON.stringify({
          email: email.value,
          id: localStorage.getItem("animoId"),
        });

        if (
          localStorage.getItem("animoId") &&
          localStorage.getItem("animoId") != null &&
          this.emailValide
        ) {
          let url = "http://127.0.0.1:8000/api/updateEmail/" + this.id;

          axios.put(url, user).then(
            (res) => {
              this.messageEmail = "email enregistré";
              localStorage.setItem("animoEmail", email.value);
              this.email = email.value;
              this.inputEmail = false;
            },
            (error) => {
              console.log(error);
              this.messages.push(
                "une erreur s'est produite lors de l'inscription"
              );
            }
          );
        }
      }
    },

    sendNewPseudo() {
      let input = document.querySelector("#newPseudo");

      let pseudoNettoyer = input.value.trim();
      if (pseudoNettoyer.length == 0) {
        this.pseudoError = "Vous devez renseigner un pseudo";
        input.classList.add("incorrect");
      } else {
        input.classList.remove("incorrect");
        this.pseudoError = null;
        this.pseudo = pseudoNettoyer;

        let user = { pseudo: pseudoNettoyer, id: this.id };

        let url = "http://127.0.0.1:8000/api/updatePseudo/" + this.id;

        axios.put(url, user).then(
          (res) => {
            this.inputPseudo = false;
          },
          (error) => {
            console.log(error);
            this.messages.push(
              "une erreur s'est produite lors du changement de pseudo"
            );
          }
        );
      }
    },
    getUser() {
      let url =
        "http://127.0.0.1:8000/api/user?email=" + this.email + "&id=" + this.id;

      axios.get(url).then(
        (res) => {
          this.pseudo = res.data.pseudo;
          this.image = "http://localhost:8000/uploads/" + res.data.image;
        },
        (error) => {
          console.log(error);
          this.messages.push(
            "une erreur s'est produite lors du changement de pseudo"
          );
        }
      );
    },
    isAdmin() {
      let role = localStorage.getItem("animoRole");
      if (role == "admin") {
        this.$router.push("dashboard");
      }
    },

    dropImage(e) {
      e.preventDefault();
      const inputValue = e.target.files || e.dataTransfer.files;

      for (let i = 0; i < inputValue.length; i++) {
        this.fileImage = inputValue[i];
        console.log(inputValue[i]);
        this.photoName = inputValue[i].name;
      }
    },
    dragOver() {
      let container = document.querySelector(".containerImage");
      let logo = document.querySelector("#Capa_1");
      container.style.borderColor = "green";
      logo.style.fill = "green";
    },

    dragLeave() {
      let container = document.querySelector(".containerImage");
      let logo = document.querySelector("#Capa_1");
      container.style.borderColor = "#84c476";
      logo.style.fill = "#84c476";
    },
    async validerImage() {
      // this.image = this.photoName;
      this.wantToModifyImage = false;
      this.photoName = null;
      let url = "http://127.0.0.1:8000/api/updateAvatar/" + this.id;
      const fd = new FormData();
      fd.append("image", this.fileImage);

      await axios.post(url, fd).then((res) => {
        this.image = "http://localhost:8000/uploads/" + res.data;
        bus.emit("update-image", this.image);
      });
    },
    annulerImage() {
      this.wantToModifyImage = false;
      this.photoName = null;
    },
    changeModifyImage() {
      if (this.wantToModifyImage == true) {
        this.wantToModifyImage = false;
      } else {
        this.wantToModifyImage = true;
      }
    },
    async supprimer(id) {
      let url = "http://127.0.0.1:8000/api/user/" + this.id;
      await axios.delete(url).then(
        (res) => {
          localStorage.removeItem("animoId");
          localStorage.removeItem("animoEmail");
          localStorage.removeItem("animoRole");
          window.location.href = "/";
        },
        (error) => {}
      );
    },
  },

  beforeMount() {
    this.isAdmin();
    this.getUser();
  },

  components: {
    Plume,
    Bouton,
    MessageInvalide,
  },
};
</script>
<style scoped src="./style.css"></style>
