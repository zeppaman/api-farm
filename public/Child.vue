<template>
<div>
   {{valore}}
</div>
</template>



<script type="module">
     import  parent from 'Parent.vue'
    
    parent.methods.fetchData= function()
    {
        return "CHILD VALUE";
    };
    module.exports=parent;
</script>
