  <v-data-table
    dense
    :headers="headers"
    :items="items"
    item-key="name"
    class="elevation-1"

    :items-per-page="itemsPerPage"
    :loading="isLoading"
    :server-items-length="itemsCount"
    @click:row="goTo($event)"
    @pagination="paginate($event)"
    @update:sort-by="sortBy($event)"
    @update:sort-desc="sortDesc($event)"
  ></v-data-table>
