import ApiFarm from '/app/core/apiFarm.js'




export default  ApiFarm.extend("sidebar",{
    name: 'sidebar',
    props: {},
    template:  '/app/template/sidebar/sidebar.vue',
    data () {
      return {
        items: [
          { title: 'Dashboard', icon: 'mdi-view-dashboard' },
          { title: 'Photos', icon: 'mdi-image' },
          { title: 'About', icon: 'mdi-help-box' },
        ],
        right: null,
      }
    },
    mounted: async function () {
    
      console.log("SIDEBAR MOUNTED");
      console.log(this.services.dataServiceFactory);
    }

  });


