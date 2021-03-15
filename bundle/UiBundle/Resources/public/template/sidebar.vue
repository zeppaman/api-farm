
<v-list-item>
  <v-list-item-content>
  <v-treeview :items="items" >   
    <template slot="label" slot-scope="{ item }">
      <a @click="navigate(item.route)">{{ item.name }}</a>
    </template>

  </v-treeview>
  </v-list-item-content>
</v-list-item>


     



