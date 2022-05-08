<template>
    <div class="coucou">
    <h3>{{msg}}</h3>
    </div>
</template>

<script>
export default {
  name: 'LalalaMessage',
  data(){
    return{
      msg: 'je viens du component'
    }
  }
  // props: {
  //   msg: String
  // }
}
</script>