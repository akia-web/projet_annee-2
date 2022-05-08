<template>
    <div class="connexion">

     
     <div class="bloc1">
          <h1>Connexion <img src="../../assets/herisson.png" alt=""></h1>
          <p>{{msg}}</p>

          <form>
            <input type="text" name="" id="emailConnexion" placeholder="email"><br>
            <input type="password" name="" id="password2" placeholder="mot de passe"><br>
            <div class="droite">
              <button @click="register">Se connecter</button> <br>
            </div>
          </form>
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
          <router-link to="/inscription" class="link"> Inscription </router-link>
        </div>
      </div>

    </div>
</template>

<script>
import Inscription from '@/components/inscription/Inscription.vue'
export default {

  name: 'ConnexionApp',
  data(){
    return{
      msg: null,
      email: null,
      id:null
    }
  },
  methods:{
    async register(event){
      console.log("blabla")
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
        this.msg = "connexion réussi"
        
      }catch(e){
        this.msg = "Les identifiants sont incorrect"
      }
    
    }
  },
   components: {
  Inscription
  }
}
</script>
 <style scoped src="./style.css">
 </style>



   
 
