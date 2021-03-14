import ApiFarm from '/app/core/apiFarm.js'




  export default  ApiFarm.extend("grid",{
    data(){
        return {
            monacoOptions: {
                language: 'json',
                scrollBeyondLastLine: false,                
              },
        };
    },
    methods:
    {
      amdRequire: require,      
      resizeMonaco: function() {
        const monacoEditor = this.$refs.monaco.getMonaco();
        const oldLayout = monacoEditor.getLayoutInfo();    
        monacoEditor.layout({ width: this.$refs.monacoparent.clientWidth, height: this.$refs.monacoparent.clientHeight });
      },
    },
    //template:"/app/template/edit.vue"
  });