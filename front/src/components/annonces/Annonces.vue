<template>
    <div class="page">
        <h2>Créer une nouvelle annonce</h2>
       
       
       <div class="formulaire">
        <div class="gauche">
            <div class="flex">
            <input type="datetime-local" name="" id="" v-model="date">
            <select v-model="categorie">
                <option disabled value="choisir">Choisir une catégorie</option>
                <option>blabla</option>
            </select>
            </div>
            
            <input type="text" placeholder="titre" v-model="titre"> <br>
            <textarea rows="20" placeholder="Description" v-model="description"></textarea>
        </div>

        <div class="droite">
            <input type="text" placeholder="adresse" v-model="adresse">
            <input type="text" placeholder="complément d'adresse"  v-model="complementAdresse">
            <input type="text" placeholder="code postal"  v-model="codePostal">
            <input type="text" placeholder="ville"  v-model="ville">

            <button @click="afficheCarte">Prévisualiser la carte</button>
            
            <iframe  :src=lienCarte frameborder="0"></iframe>
        </div>
      
       </div>
      
      <bouton :message="'Valider l\'annonce'"></bouton> <br>
      <button @click="afficher" >Ajouter une image</button>
        


        <div id="searchImage" class="searchImage visibility" >
            <span class="fermer">x</span>
            <h2>Choisissez une image</h2>
            <input type="text" placeholder="rechercher une image" v-model="motcle" v-on:keyup.enter="getImage" class="rechercheImage">
              <div class="resultImage" >
                <img class="image" :src=item alt="" @click="addImg(item)" v-for="item in tableauImages">
            
            
        </div>
        </div>

      
        
       
    
    </div>
</template>

<script>


import Bouton from '../bouton/Bouton.vue';
export default {
  components: { Bouton },
  name: 'Annonces',
  data(){
    return{
    haveCompte: this.getUser(),
    isConnect: false,
    date:null,
    categorie: "choisir",
    titre: null,
    description: null,
    adresse:null,
    complementAdresse: null,
    codePostal: null,
    ville: null,
    image: null,
    lienCarte: null,
    apikey: "563492ad6f91700001000001a504a5075bdc4026b01c6cdeb568c24b",
    images:null,
    motcle:null,
    tableauImages: [],
    imgSelected:null
  



    }
  },
   methods:{
     async getUser(){
        let id = localStorage.getItem("animoId");
        let email = localStorage.getItem("animoEmail")
        let url = "http://127.0.0.1:8000/api/user?email="+email+"&id="+id;
    
        await axios.get(url).then((res)=>{
          this.isConnect = res.data
          if(this.isConnect == false){
            localStorage.removeItem("animoId") 
            localStorage.removeItem("animoEmail") 
            document.location.href = "connexion"
          }
                
        },
        (error)=>{
          localStorage.removeItem("animoId") 
          localStorage.removeItem("animoEmail") 
        })               
      },

      afficheCarte(){
          this.lienCarte = "https://www.google.com/maps?q="+this.adresse+","+this.codePostal+this.ville+"&output=embed"
      },



     async getImage(){
          this.urlApi = "https://api.pexels.com/v1/search?query="+this.motcle+"&per_page=15&page=1&orientation=landscape"
        //  this.urlApi = "https://api.pexels.com/v1/search?query=dog&per_page=15&page=1&orientation=landscape"
  

         
        let response = await axios.get(this.urlApi,{
             headers:{
                 "Authorization": this.apikey
                }
             }).then((res)=>{
                 console.log(res.data.photos)
                this.images=res.data.photos

              
                
        },
        (error)=>{
          
        })    
            this.tableauImages = []
            let response2 = this.images
          for(let i=0; i<response2.length; i++){
                    this.tableauImages.push(response2[i].src.medium)
                }
      },
    
        addImg(item){
            if(confirm("voulez-vous choisir cette image")==true){
                this.imgSelected = item
                console.log(this.imgSelected)
            }
            
        },

        afficher(){
            console.log("bouh")
            let popup = document.querySelector("#searchImage")
            console.log(popup)
            popup.classList.remove("visibility")
        }

      
    }

}

</script>

<style scoped src="./style.css">
textarea{
    columns: 100%;
}
</style>