<!-- App.vue -->

<v-app>
 <v-navigation-drawer v-model="isSidebarOpen"   absolute
      temporary>
  <sidebar app> 
  </sidebar>
</v-navigation-drawer>

  <v-app-bar color="blue"
  flat
  app>
       <v-toolbar-title class="ml-0 pl-4">
        <v-app-bar-nav-icon @click.stop="toggleDrawer()"></v-app-bar-nav-icon>
        <span class="hidden-sm-and-down">Api FARM</span>
    </v-toolbar-title>
  </v-app-bar>

 
  <v-main>

    <!-- Provides the application the proper gutter -->
    <v-container fluid>

      <!-- If using vue-router -->
      <router-view></router-view>
    </v-container>
  </v-main>

  <v-footer app>
    <!-- -->fghgh
  </v-footer>

   
       
</v-app>