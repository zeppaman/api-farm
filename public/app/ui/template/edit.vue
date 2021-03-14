
 <v-card
    class="mx-auto my-12"
    fluid
  >
<v-card-title>Edit</v-card-title>
<v-card-text>
<v-form >
    <v-container>
      <v-row style="height:600px" ref="monacoparent">
     
            <monaco-editor
                ref="monaco"
                value="{}"
                :options="monacoOptions"
                :amdRequire="amdRequire"
                @editorDidMount="resizeMonaco()"                    
               
                language="json"                         
            ></monaco-editor>
   </v-row>

 <v-row>
       <v-btn
    
      color="success"
      class="mr-4"
    >
      Validate
    </v-btn>

    <v-btn
      color="error"
      class="mr-4"
      
    >
      Reset Form
    </v-btn>

    <v-btn
      color="warning"
    
    >
      Reset Validation
    </v-btn>
       </v-row>
    </v-container>
  </v-form>
  </v-card-text>


       
</v-card>
