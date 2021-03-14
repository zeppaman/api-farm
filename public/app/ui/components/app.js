import ApiFarm from '/app/core/apiFarm.js'
import sidebar from '/app/ui/components/sidebar.js';



export default  ApiFarm.extend("app",{
    name: 'app',
    props: {},
   // template:  '/app/template/layout.vue',
    components: {
        'sidebar' : sidebar
    },
    methods:
    {
        toggleDrawer :function()
        {
            console.log("CLICKED");
            this.isSidebarOpen=!this.isSidebarOpen;
        }
    },
    data : function ()
    {
        var data=
        {  count: 0,
            isSidebarOpen : false
        };
        return data;
    }
  })


