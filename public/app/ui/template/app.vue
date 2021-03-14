<!-- App.vue -->

<v-app>
 <v-navigation-drawer v-model="isSidebarOpen"   absolute
      temporary>

      <v-list-item>
        <v-list-item-content>
          <v-list-item-title class="title">
            API Farm
          </v-list-item-title>
          <v-list-item-subtitle>
            Develop. Clever.
          </v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
      <v-divider></v-divider>
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
      <router-view :key="$route.fullPath"></router-view>
    </v-container>

    <v-snackbar
      v-model="snackbar"
      :multi-line="multiLine"
      :color="color"
    >
      {{ text }}

      <template v-slot:action="{ attrs }">
        <v-btn
          
          text
          :timeout="timeout"
          v-bind="attrs"
          @click="snackbar = false"
        >
          Close
        </v-btn>
      </template>
    </v-snackbar>

  </v-main>

  <v-footer app>
    <!-- -->fghgh
  </v-footer>

   
       
</v-app>