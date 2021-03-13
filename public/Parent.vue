<template>
<div>
    {{valore}}
</div>
</template>



<script type="module" data-name="main">


  var parent ={
          data() {
                return {
                    valore:this.fetchData()
                };
          },
          methods: {
               fetchData:  function() {
                   return "parent";
               }
          }
    }

    export default parent;

</script>