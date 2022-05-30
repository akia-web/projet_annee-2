<template>
        <div class="alert alertRed">
        <span @click="fermer">x</span>
        <p>{{message}}</p>
    </div>
    
</template>

<script>

export default{
    methods:{
        fermer(){
            let parent = event.target.parentNode.parentNode
            let enfant = event.target.parentNode
            enfant.classList.add("supprimer")
         
           setTimeout( function () {
               parent.removeChild(enfant)}, 600 ) 

        }
    },
    props: {
        message: String
    }
}

</script>

 <style scoped src="./style.css">
 </style>