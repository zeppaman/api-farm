
 <v-card
    class="mx-auto my-12"
    fluid
  >
<v-card-title>Edit</v-card-title>
<v-card-text>






<v-form >
    <v-container>
    <v-row >
     <v-btn
       block
      color="warning"
      v-show="!isValid"
      
    >Invalid JSON</v-btn>

    </v-row>
      <v-row style="height:600px" ref="monacoparent">
     
            <monaco-editor
                ref="monaco"
                v-model="itemJson"
                :options="monacoOptions"
                :amdRequire="amdRequire"
                @editorDidMount="resizeMonaco()"                    
                @change="onChange"
                language="json"                         
            ></monaco-editor>
   </v-row>

     

    <v-btn
      color="error"
      class="mr-4"
      
    >
      Delete
    </v-btn>

   

      <v-btn
    
      color="success"
      class="mr-4"
      align-end
      :disabled="!isValid"
       @click="onSave()"
    >
      Save
    </v-btn>
       </v-row>
    </v-container>
  </v-form>
  </v-card-text>


       
</v-card>
