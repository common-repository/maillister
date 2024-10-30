document.addEventListener('DOMContentLoaded',()=>{

console.log('blop')

let emailARR = emailLister[0]
let emailInput = document.querySelector('#email_lister_email')
let listerForm = document.querySelector('.email_lister_form')
let listerDiv = document.querySelector('.email_lister_div')
let erroMessage = document.querySelector('.lister_warn_message')

$("#email_lister_submit").click(function(e){ 

    e.preventDefault()

    if(validateEmail(emailInput.value)){
       erroMessage.style.transition = '.5s linear'
       erroMessage.style.opacity = 0
     
       setTimeout(() => {
           erroMessage.style.display = 'none'
       }, 500);
         
        if(emailARR.includes(`${emailInput.value}`)) 
        {
          erroMessage.style.display = 'block'
          erroMessage.style.transition = '.5s linear'
          erroMessage.style.opacity = 1
          erroMessage.innerHTML = 'Email address is already on our list'
        }
        else {
            erroMessage.style.transition = '.5s linear'
            erroMessage.style.opacity = 0
            setTimeout(() => {
                $("#email_lister_submit").unbind('click').click();
                erroMessage.style.display = 'none'
            }, 500);
        }
            
            
       

    }
    else{
        erroMessage.innerHTML = 'Enter correct email address'
        erroMessage.style.display = 'block'
        erroMessage.style.transition = '.5s linear'
        erroMessage.style.opacity = 1
    }
    
    
})
let mql = window.matchMedia('(max-width: 767px)');
if(mql.matches) listerDiv.style.width = `${listerDiv.getAttribute('data-mobile-width')}vw`

console.log()
function validateEmail(email) 
    {
        var re = /\S+@\S+\.\S+/;
        return re.test(email);
    }




})