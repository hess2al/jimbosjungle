var times = ["Good Afternoon!", "Good Morning!", "Good Evening!"];

window.onload = greeting();

function greeting() {
    var date = new Date();
    var hours = date.getHours();
    let greetingElement = document.getElementById("greetings");
    let greeting;
    

    if (date.getHours() >= 12 && date.getHours() <= 18) {
        greeting = times[0];
    } else if (date.getHours() < 12) {
        greeting = times[1];
    } else {
        greeting = times[2];
    }
    greetingElement.innerHTML = greeting;
}


function required(inputtx) 
{
  if (inputtx.value.length == 0)
   { 
      alert("message");  	
      return false; 
   }  	
   return true; 
}

function matchPassword() {  
    var pw1 = document.getElementById("password");  
    var pw2 = document.getElementById("repeatpassword");  
    if(pw1 != pw2)  
    {   
      alert("message");
      return false;
    } else {  
      return true;  
    }  
}  
