<template>
  <div class="page">
    <div class="containerConversations">
      <div class="gauche">
        <div class="containerNewMessage" @click="idConversation = 'newMessage'">
          <img class="newMessage" src="../../assets/newMessage.png" alt="" />
        </div>
        <div
          v-for="item in conversations"
          class="utilisateurs"
          v-bind:class="{ active: idConversation == item.id }"
          @click="getInfoConversation(item)"
        >
          <img class="conversationImage" :src="item.image" alt="" />
          <p>{{ item.pseudo }}</p>
        </div>
      </div>
      <div class="droite">
        <!--Nouveau message-->
        <div v-if="idConversation == 'newMessage'">
          <span>A : </span>
          <input
            class="search"
            type="text"
            v-model="search"
            @keydown="getUser"
            placeholder="rechercher..."
            v-if="utilisateurSelect == null"
          />
          <span class="contact" v-if="utilisateurSelect != null">
            <span>{{ utilisateurSelect.pseudo }}</span>
            <span class="delete" @click="deleteContact"> x </span>
          </span>

          <div class="resultUtilisateur" v-if="resultSearch != null">
            <div v-for="item in resultSearch" @click="selectUser(item)">
              <img :src="item.images" alt="" />
              <p>{{ item.pseudo }}</p>
            </div>
          </div>
          <textarea
            class="textareaNewMessage"
            v-model="message"
            placeholder="écrire un message ..."
            @keydown.enter="sendMessage"
          ></textarea>
        </div>
        <!--MessageConversation-->
        <div>
          <div v-for="item in messages" class="containerMessage">
            <img class="avatarConv" :src="item.image" alt="" />
            <div class="column">
              <div class="headerMessage">
                <span>{{ item.date }} {{ item.heure }}</span>
              </div>
              <span class="currentUser itemMessage">
                {{ item.message }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="containerBas">
      <div class="Basgauche"></div>
      <div class="Basdroite">
        <textarea
          v-if="idConversation != 'newMessage'"
          v-model="message"
          placeholder="écrire un message ..."
          @keydown.enter="sendMessageExistingConversation"
        ></textarea>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "MessagesUser",
  data() {
    return {
      lien: "info",
      idConversation: null,
      messages: [],
      search: null,
      resultSearch: null,
      utilisateurSelect: null,
      currentUser: localStorage.getItem("animoId"),
      message: null,
      conversations: [],
      destinataire: null,
    };
  },
  methods: {
    async getUser() {
      console.log("ca change");
      this.resultSearch = null;
      let url =
        "http://localhost:8000/api/pseudoUser?search=" +
        this.search +
        "&id=" +
        this.currentUser;
      await axios.get(url).then((res) => {
        console.log(res.data);
        this.resultSearch = res.data;
      });
    },
    selectUser(user) {
      this.utilisateurSelect = user;
      this.search = null;
      this.resultSearch = null;
    },
    deleteContact() {
      this.utilisateurSelect = null;
    },
    async sendMessage() {
      let url = "http://localhost:8000/api/conversation";
      let data = {
        expediteur: this.currentUser,
        participant2: this.utilisateurSelect.id,
        message: this.message,
      };
      await axios.post(url, data).then(
        (res) => {
          console.log(res.data);
          let conversationExiste = false;

          this.idConversation = res.data.conversation.id;
          for (let i = 0; i < this.conversations.length; i++) {
            if (res.data.conversation.id == this.conversations[i].id) {
              let conversationExiste = true;
            }
          }

          if (!conversationExiste) {
            let convers = {
              id: res.data.conversation.id,
              pseudo: res.data.conversation.participant2,
              image: res.data.conversation.imageDestinataire,
              destinataire: this.utilisateurSelect.id,
            };
            this.conversations.push(convers);
          }
          this.message = null;
        },
        (error) => {}
      );
    },
    async sendMessageExistingConversation() {
      let url = "http://localhost:8000/api/conversation";
      let data = {
        expediteur: this.currentUser,
        participant2: this.destinataire,
        message: this.message,
      };
      console.log(data);
      await axios.post(url, data).then(
        (res) => {
          console.log(res.data);

          let newMessage = {
            date: res.data.date,
            expediteur: this.currentUser,
            heure: res.data.heure,
            image: res.data.image,
            message: res.data.message,
            pseudo: res.data.pseudo,
          };
          this.messages.push(newMessage);
          this.message = null;
        },
        (error) => {}
      );
    },
    async getAllConversation() {
      let url = "http://localhost:8000/api/conversation?id=" + this.currentUser;
      await axios.get(url).then((res) => {
        this.conversations = res.data;
        this.idConversation = res.data[0].id;
        this.destinataire = res.data[0].destinataire;
      });
    },
    async getMessages() {
      let url = "http://localhost:8000/api/conversation/" + this.idConversation;
      await axios.get(url).then(
        (res) => {
          console.log(res.data);
          this.messages = res.data;
        },
        (error) => {}
      );
    },
    getInfoConversation(item) {
      this.idConversation = item.id;
      this.destinataire = item.destinataire;
    },
  },

  beforeMount() {
    this.getAllConversation();
  },
  watch: {
    idConversation: function (val) {
      if (this.idConversation != "newMessage") {
        this.getMessages();
      }
      if (this.idConversation == "newMessage") {
        this.messages = null;
      }
    },
  },
};
</script>

<style scoped src="./style.css"></style>
