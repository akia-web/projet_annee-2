<template>
        <div class="alert alertRed" v-for="(item, index) in tableau">
        <span @click="fermer">x</span>
        <p>{{item}}</p>
    </div>
    
</template>

<script>

export default{
    methods:{
        fermer(){
            let message = event.target.nextSibling.innerHTML
            let indexMessageSupprimer = this.tableau.indexOf(message)
            let tableau = this.tableau          
            setTimeout( function () {tableau.splice(indexMessageSupprimer,1)}, 100 )   
        }
    },
    props: {
        tableau: Array
    }
}

</script>

 <style scoped src="./style.css">
 </style>