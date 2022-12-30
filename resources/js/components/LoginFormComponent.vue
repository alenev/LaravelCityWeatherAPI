<template>
    <div class="container p-5">
      <h3 class="text-center mt-2 mb-5">Login</h3>
      <div class="col-md-12">
        <form v-on:submit.prevent="login_user">
          
         <div class="mb-3">
            <label for="exampleFormControlInput2" class="form-label">Enter Email</label>
           <input type="text" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Email" v-model="form.email">
          </div>
           <div class="mb-3">
           <label for="exampleFormControlInput3" class="form-label">Enter Password</label>
           <input type="password" name="password" class="form-control" id="exampleInputPass1" aria-describedby="passHelp" placeholder="Enter Password" v-model="form.password">
          </div>
          <div class="mb-3">
           <label for="exampleFormControlInput3" class="form-label">Remember for 1 hour (default 3 minute)</label>
           <input type="checkbox" name="remember" class="form-check-input" id="exampleInputRemember1"  v-model="form.remember">
          </div>
    
          <button type="submit" class="btn btn-primary mt-5">Submit</button>
    </form>
    </div>
    </div>
</template>

<script>
import "bootstrap/dist/css/bootstrap.min.css";
import axios from 'axios'
import Swal from 'sweetalert2'
  export default {
  data(){
  return {
   
    form:{
      email: '',
      password: '',
      remember: ''
      
    }
  }
},
  methods:{
     login_user(){
    
      axios
      .post('http://localhost:8000/api/login',this.form)
      .then((resp) =>{
          this.form.email = '';
          this.form.password = '';
          this.form.remember = 1;
          console.table(resp);
          Swal.fire({
          title: '',
          text:   "Loginsuccessfully",
          icon: 'success',
          
        });
      })
      .catch((e)=>{
          console.log(e);
          let message = e;
          if(e.errors){
            message = e.errors;  
          }
          Swal.fire({ title: '', text:   message, icon: 'warning', });
          
      })
    }
  }
  
}
</script>