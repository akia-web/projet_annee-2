<template>
    <section class="inscription">
        <h2>Inscription</h2>

            <div v-if=" messages[0]">
                <message-invalide v-for="(item, index) in messages" :message="messages[index]"></message-invalide>
            </div>
           

            <div class="alert alertGreen" v-if=" messagesValidee != null">
                <p > {{messagesValidee}}</p>
            </div>

        
            <input id="email" type="email" placeholder="email"><br>
            <input id="password" type="password" placeholder ="mot de passe"><br>
            <input id="confirmPassword" type="password" placeholder = "Confirmation du mot de passe"> <br>

            <bouton :message="'S\'inscrire'" @click="inscription"></bouton>
           
        

    </section>
  
</template>

<script>
import Bouton from '../bouton/Bouton.vue'
import messageInvalide from '../messages/messageInvalide/messageInvalide.vue'
export default {
  components: { messageInvalide, Bouton },
  name: 'Inscription',
    data(){
    return{
     
      formValide: null,
      emailValide: null,
      mdpValide: null,
      matchMdpAndConfirmMdp: null,
      messages : [],
      messagesValidee: null,
    }
  },
  methods:{
      inscription(){  
        if(this.messages.length>0){
            this.messages = []
        }
       
        const email = document.querySelector("#email")
        const password = document.querySelector("#password")
        const confirmPassword = document.querySelector("#confirmPassword")
        let user = {};
        const regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/

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
        

        //validation mot de passe
        if(password.value.length< 1){
            this.messages.push("Le mot de passe est vide");
            password.classList.add("incorrect")  
            
        }else if(password.value.length< 8){
            this.messages.push("Le mot de passe trop court"); 
            password.classList.add("incorrect") 
            
        }else{
            password.classList.remove("incorrect") 
            this.mdpValide = true
        }
       
        // regarder si les mots de passes sont les mêmes
       if(confirmPassword.value.length < 1){
            this.messages.push("Le mot de passe de confirmation est vide"); 
            confirmPassword.classList.add("incorrect") 
            
       }else if(password.value != confirmPassword.value){
            this.messages.push("Les mots de passes ne sont pas les mêmes"); 
            confirmPassword.classList.add("incorrect") 
            
        }else{
            this.matchMdpAndConfirmMdp = true
            confirmPassword.classList.remove("incorrect") 
        }

        if(this.emailValide && this.mdpValide && this.matchMdpAndConfirmMdp){
            this.formValide = true
             user = JSON.stringify({
                "email": email.value,
                "password": password.value,
            })
        }

       
        if(this.formValide){
            
            let url = "http://127.0.0.1:8000/api/createOneUser"
            
            axios.post(url,user).then((res)=>{
                console.log(res)
                this.messagesValidee="inscription validée"
                
                // a voir
                window.setTimeout( this.$router.push({ name: 'connexion' }), 1000)
               
            },
            (error)=>{
                console.log(error)
                this.messages.push("une erreur s'est produite lors de l'inscription")
            })               
            
           
           
             
        }

      
      }, 
  }

}
</script>
    <style scoped src="./style.css">
</style>