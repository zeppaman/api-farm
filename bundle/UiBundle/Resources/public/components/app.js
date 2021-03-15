import ApiFarm from '/bundles/core/lib/apiFarm.js'
import sidebar from '/bundles/ui/components/sidebar.js';



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
            isSidebarOpen : false,
            multiLine: true,
            snackbar: false,
            color: "success",
            timeout: 10,
            text: "",

        };
        return data;
    },
    mounted: async function()
    {
        this.subscribe("ui.message", (data)=>{
            this.snackbar=true;
            this.text=data.message;
            this.color=data.type;
        })
    }
  })


