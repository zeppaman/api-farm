<template>
<div>
    {{valore}}
</div>
</template>



<script>

    httpVueLoader.component(Vue,"Parent.vue")
    module.exports= {
          data() {
                return {
                    valore:"parent"
                }
          }
    }
</script>