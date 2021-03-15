import apiFarm from '/bundles/core/lib/apiFarm.js'


 var base= {
    data: () => ({
      valid: true,
      name: '',
      nameRules: [
        v => !!v || 'Useranme is required',
        v => (v && v.length <= 10) || 'Name must be less than 10 characters',
      ],
      password: '',
      passwordRules: [
        v => !!v || 'Password is required',
      ],
      
    }),

    methods: {
      login () {
        let isvalid=this.$refs.form.validate();
        if(isvalid)
        {
            apiFarm.login(this.name,this.password).then(x=>{
                this.valid=false;
            });
        }
      },      
    },
   // template:"/app/template/login.vue"
  }
        


export default  apiFarm.extend("login",base)