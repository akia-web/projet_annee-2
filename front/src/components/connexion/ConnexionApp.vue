<template>
    <div v-if="!emailStorage" class="connexion page">

   
     <div class="bloc1">
          <h1>Connexion <img src="../../assets/herisson.png" alt=""></h1>

            <div v-if=" messagesInvalide[0]">
              <message-invalide :tableau="messagesInvalide"></message-invalide>
            </div>
            
            <input type="text" name="" id="emailConnexion" placeholder="email"><br>
            <input type="password" name="" id="password2" placeholder="mot de passe"><br>
            <div class="droite">
              <bouton :message="'se connecter'" @click="register"></bouton> <br>
            </div>
          
      </div>
      <hr>

      <div class="bloc2">
        <h1>Pas encore inscrit ? </h1>
        
        <div class="groupe">
          <p>Rejoignez-nous pour : 
            <ul>
              <li>Participer à des évènements</li>
              <li>Proposer des évènements</li>
              <li>Partager des photos de vos animaux</li>
              <li>Gagner des points</li>
            </ul>
          </p>
         
          <img class="img-inscription" src="../../assets/inscription.png" alt="">
        </div>
        
        
        <div class="inscription">
          <bouton class="link" :message="'Inscription'" @click="redirectInscription"></bouton> <br>
          
        </div>
      </div>

    </div>
    <div class="page" v-else>
      <user-app></user-app>
    </div>
</template>

<script>
import Inscription from '@/components/inscription/Inscription.vue'
import UserApp from '../user/UserApp.vue'
import Bouton from '../bouton/Bouton.vue'
import MessageInvalide from '../messages/messageInvalide/messageInvalide.vue'
export default {

  name: 'ConnexionApp',
  data(){
    return{
      messagesInvalide: [],
      email: null,
      id:null,
      emailStorage : localStorage.getItem("animoEmail"),
      animoId : localStorage.getItem("animoId"), 
      isConnected: false
    }
  },
  methods:{
    async register(event){
      event.preventDefault()
      let email = document.querySelector("#emailConnexion")
      let password = document.querySelector("#password2")
      let user = {}
      let url = "http://127.0.0.1:8000/api/register"
      user = JSON.stringify({
          "email": email.value,
          "password": password.value,
      })
      
      try{
        let result =  await axios.post(url,user);
              
        console.log(result.data) 
        this.email= result.data.email,
        this.id = result.data.id  
        localStorage.setItem('animoId',this.id)
        localStorage.setItem('animoEmail', this.email)
        this.isConnected = true
        if(this.isConnected){
          this.$router.push('/');
        }
      
        
      }catch(e){
         this.messagesInvalide.push("Les identifiants sont incorrect") 
      } 
    },

    redirectInscription(){
      this.$router.push('inscription');
    }
  },
   components: {
  Inscription,
      UserApp,
      Bouton,
      MessageInvalide
  }
}
</script>
 <style scoped src="./style.css">
 </style>



   
 
