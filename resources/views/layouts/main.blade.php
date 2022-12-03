<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="css/app.css" rel="stylesheet">

        <!-- Styles -->
        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.dark\:text-gray-500{--tw-text-opacity:1;color:#6b7280;color:rgba(107,114,128,var(--tw-text-opacity))}}
        </style>

        <style>
          
        </style>
    </head>
    <body class="antialiased">
    @csrf
    @yield('content')


 <div id="preloader" class="loader">
	<div class="loader-inner">
		<div class="loader-line-wrap">
			<div class="loader-line"></div>
		</div>
		<div class="loader-line-wrap">
			<div class="loader-line"></div>
		</div>
		<div class="loader-line-wrap">
			<div class="loader-line"></div>
		</div>
		<div class="loader-line-wrap">
			<div class="loader-line"></div>
		</div>
		<div class="loader-line-wrap">
			<div class="loader-line"></div>
		</div>
	</div>
</div>

<script type="text/javascript">

    function hidePreloader(){
        let preloader = document.getElementById('preloader');
        preloader.style.display = "none";
    }
    function showPreloader(){
        let preloader = document.getElementById('preloader');
        preloader.style.display = "block";
    }
    function showLogin(){
        login_box.style.display = "block";
        logged_in_box.style.display = "none";
    }
    
    function hideLogin(){
        login_box.style.display = "none";
        logged_in_box.style.display = "block";
    }

    function RenderHomePage(){ 

       showPreloader();

       let xyz_access_token = localStorage.getItem('xyz_access_token');
       if(xyz_access_token && xyz_access_token.length > 1){

          $atoken = 'Bearer '+xyz_access_token+'';
 
           fetch('api/home', {
            method: 'GET', 
            headers: {
               'Accept': 'application/json',
               'Authorization': $atoken,
               'X-CSRF-Token': document.querySelector('input[name=_token]').value
            }
         })
         .then(function(response) {

        hidePreloader();

            return response.json().then((data) => {

             if(data.error && data.error.indexOf('geodata') > -1){
               alert('Geodata receive problem. Refresh page.');
            }


        if(data.main.city){
        let el = document.getElementById('weather_UI_city'); 
        let text = document.createTextNode(data.main.city);
        el.appendChild(text); 
        }

        if(data.main.temp){
        let el = document.getElementById('weather_UI_temp'); 
        let text = document.createTextNode(data.main.temp+"°C");
        el.appendChild(text); 
        }

        if(data.main.pressure){
        let el = document.getElementById('weather_UI_pressure'); 
        let text = document.createTextNode("pressure: "+data.main.pressure);
        el.appendChild(text); 
        }

        if(data.main.humidity){
        let el = document.getElementById('weather_UI_humidity'); 
        let text = document.createTextNode("humidity "+data.main.humidity);
        el.appendChild(text); 
        }

        if(data.main.temp_min){
        let el = document.getElementById('weather_UI_temp_min'); 
        let text = document.createTextNode("temp min "+data.main.temp_min+"°C");
        el.appendChild(text); 
        }

        if(data.main.temp_max){
        let el = document.getElementById('weather_UI_temp_max'); 
        let text = document.createTextNode("temp max "+data.main.temp_max+"°C");
        el.appendChild(text); 
        }

            }).catch((err) => {
                console.log(err);
            }) 

            hidePreloader();
         })
          .catch(function(error) {
              console.error('Error:', error);
              hidePreloader();
          });

        }else{

            showLogin();
            hidePreloader();

        }
    }

    function checkUserAuth(){
       let xyz_access_token = localStorage.getItem('xyz_access_token');
       if(xyz_access_token && xyz_access_token.length > 1){

          $atoken = 'Bearer '+xyz_access_token+'';
 
           fetch('api/user', {
            method: 'GET', 
            headers: {
               'Accept': 'application/json',
               'Authorization': $atoken,
               'X-CSRF-Token': document.querySelector('input[name=_token]').value
            }
         })
         .then(function(response) {

            if(response.status && response.status == 200){
                hideLogin();
                RenderHomePage();
               }else{
                showLogin();
               }
               hidePreloader();
               
         })
          .catch(function(error) {
              console.error('Error:', error);
              hidePreloader();
          });


        }else{

            showLogin();
            hidePreloader();
        }
    }

    async function logout(){
        let xyz_access_token = localStorage.getItem('xyz_access_token');
        if(xyz_access_token && xyz_access_token.length > 1){

          $atoken = 'Bearer '+xyz_access_token+'';
    
           fetch('api/logout', {
            method: 'GET', 
            headers: {
               'Accept': 'application/json',
               'Authorization': $atoken,
               'X-CSRF-Token': document.querySelector('input[name=_token]').value
            }
         })
         .then(function(response) {
             return response.json().then((data) => {
                showLogin();
            }).catch((err) => {
                console.log(err);
            }) 
         })
          .catch(function(error) {
              console.error('Error:', error);
          });


        }else{
            console.log('xyz_access_token not found');
        }

        }


        async function google_login_API(code, remember){

        let token = document.querySelector('input[name=_token]').value;
        let login_data = JSON.stringify({'auth_code': code, 'remember': remember});


        await fetch('api/google/login', {
            method: 'POST', 
            headers: {
               'Content-Type': 'application/json',
               'Accept': 'application/json',
               'url': 'api/google/login',
               "X-CSRF-Token": document.querySelector('input[name=_token]').value
            },
            body: login_data
         })
         .then(function(response) {

             return response.json().then((data) => {
                if(data.error && data.error.length > -1){

                    showLogin();
                    hidePreloader();

                }else if(data && data.length > -1){

                  localStorage.setItem('xyz_access_token', data);
                  let urlObj = new URL(window.location);
                  urlObj.search = '';
                  let rurl = urlObj.toString();
                  window.location.href = rurl;

                }

            }).catch((err) => {
                console.log('error '+err);
            }) 
         })
          .catch(function(error) {
              console.error('Error:', error);
          });
      }


      async function google_login_UI(){
        await fetch('api/google/login/url', {
            method: 'GET', 
            headers: {
               'Content-Type': 'application/json',
               'Accept': 'application/json',
               'url': 'api/google/login/url',
               "X-CSRF-Token": document.querySelector('input[name=_token]').value
            },
         })
         .then(function(response) {

             return response.json().then((data) => {

              window.location = data;

            }).catch((err) => {
                console.log(err);
            }) 
         })
          .catch(function(error) {
              console.error('Error:', error);
          });
      }


    let login_box = document.getElementById("login_box");
    let logged_in_box = document.getElementById("logged_in_box");
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const code = urlParams.get('code');

    document.addEventListener("DOMContentLoaded", function(event) { 

    
    let rbcb = document.getElementById('remember');
    if(rbcb){
     rbcb.addEventListener('click', (event) =>{
     if(event.target.checked){
        localStorage.setItem('remember', 1);
     }else{
        localStorage.setItem('remember', 0);
     }  
 
    })
    }

      let google_login_button = document.getElementById('google_login_button');
      google_login_button.onclick = async () => {
       await google_login_UI();
      }
     

      let google_logout_button = document.getElementById('logged_in_box');
      google_logout_button.onclick = async () => {
      await logout();
      }

      
      if(code && code.length > 1){
        let remember = localStorage.getItem('remember');
        if(remember && remember == 1){
        localStorage.setItem('remember', 0);
        }  
        google_login_API(code, remember);
      }else{
        localStorage.setItem('remember', 0);
        checkUserAuth();
      }


    }); 


</script>

    </body>
</html>


