import apiFarm from '/app/core/apiFarm.js'




export default  apiFarm.extend("sidebar",{
    name: 'sidebar',
    props: {},
   // template:  '/app/template/sidebar.vue',
    data () {
      return {
        items: [ ],
        right: null,        
      }
    },

    methods:{
      navigate:  async function (item) {
        console.log(item);
        if(item)
        {
          apiFarm.router.push(item);
        }
      }
    },
    mounted: async function () 
    { 
      
        await apiFarm.registerMenus();
        this.items=apiFarm.menu;
      
    }

  });


