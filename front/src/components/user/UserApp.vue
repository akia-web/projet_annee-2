<template>

<div class="identity">
    <h1>Informations personnelles</h1>
       <div v-if=" messages[0]">
                <!-- <p v-for="(item, index) in messages"> {{item}}</p> -->
                <message-invalide v-for="(item, index) in messages" :message="messages[index]"></message-invalide>
            </div>
        <div class="alert alertGreen" v-if="messageEmail != null">
            <p > {{messageEmail}}</p>
        </div>
      

    
      <p>Email : 
    <span class="email1" v-if="!inputEmail">  {{email}}</span> 
    <span v-if="inputEmail">
      <input id="newEmail" class="inputEmail" type="text" placeholder="nouvel Email">  
      <button @click="inputEmail = false">x</button> <button @click="sendNewEmail">v</button>
    </span>
    <plume @click="modifEmail" v-if="!inputEmail"></plume> </p>

    <bouton message="se déconnecter" @click="disconnect"></bouton>
  
</div>

</template>

<script>
import Bouton from '../bouton/Bouton.vue'
import MessageInvalide from '../messages/messageInvalide/messageInvalide.vue'
import Plume from '../plume/plume.vue'

export default {

  name: 'UserApp',
  data(){
    return{
      email: localStorage.getItem("animoEmail"),
      id:localStorage.getItem("animoId"),
      inputEmail:false,
      messages : [],
      emailValide: false,
      messageEmail:null,
      id: localStorage.getItem("animoId")
    }
  },
  methods:{
    modifEmail(){
        if(this.inputEmail == true){
          this.inputEmail = false
        }else{
          this.inputEmail = true
        }
        
    },

    disconnect(){
      localStorage.removeItem("animoId") 
      localStorage.removeItem("animoEmail") 
     document.location.href = "connexion"
      },

    sendNewEmail(){
      const email = document.querySelector("#newEmail")
      const regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/
      let user = {};

      // validation de l'email
        if(email.value.length < 1){
            this.messages.push("l'email est vide")
            email.classList.add("incorrect") 
            
        }else if(email.value.length > 0 && !email.value.match(regex)){
            
            this.messages.push("Email invalide."); 
            this.messages.push("ex : jean@exemple.com")
            email.classList.add("incorrect") 
            
        }else{
            email.classList.remove("incorrect")
            this.emailValide = true;
        }

        if(this.emailValide == true){
           user = JSON.stringify({
                "email": email.value,
                "id": localStorage.getItem("animoId")
            })
           
           if(localStorage.getItem("animoId") && localStorage.getItem("animoId")!= null && this.emailValide ){
              let url = "http://127.0.0.1:8000/api/updateEmail/"+this.id
            
              axios.put(url,user).then((res)=>{
                this.messageEmail="email enregistré"
                localStorage.setItem("animoEmail", email.value) 
                this.email = email.value
                this.inputEmail = false
               
               
              },
              (error)=>{
                console.log(error)
                this.messages.push("une erreur s'est produite lors de l'inscription")
              }) 
           }           
        }
    }
  
  },
   components: {
      Plume,
      Bouton,
      MessageInvalide

  }
}
</script>
 <style scoped src="./style.css">
 </style>


