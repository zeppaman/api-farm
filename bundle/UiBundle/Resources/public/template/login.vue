
<v-card
  elevation="2"
  loading
   class="mx-auto my-12"
    max-width="374"
>
<v-card-title>Login</v-card-title>

    <v-card-text>
      <v-form
        ref="form"
        v-model="valid"
        lazy-validation
        elevation="14"
      loading
      outlined
      shaped
      tile
      >
        <v-text-field
          v-model="name"
          :counter="10"
          :rules="nameRules"
          label="Username"
          required
        ></v-text-field>

        <v-text-field
          v-model="password"
          :rules="passwordRules"
          label="Password"
          required
        ></v-text-field>


            <v-btn
            :disabled="!valid"
            color="success"
            class="mr-4"
            @click="login()"
            >
            Login
            </v-btn>

        </v-btn>
      </v-form>
      </v-card-text>
</v-card>