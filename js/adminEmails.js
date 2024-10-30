
document.addEventListener('DOMContentLoaded',()=>{


  if(document.querySelector('.email_lister_add_emails_div') == null)
  {

let copys = [...document.querySelectorAll('.email_lister_copy')]
let today = new Date()
let emailsSavedToday = []
let emailsSavedMonth = []
let closeShortcodePreview = document.querySelector('.close_preview_shortcode')
let ShortcodePreviewDiv = document.querySelector('.shortcodeHolder')
let shortcodePreviewText = document.querySelector('.info-lister-start')
let copyShortcodeBtc = document.querySelector('.copy_lister_shortcode')
let previewShortcodeBtc = document.querySelector('.preview_lister_shortcode')
let previewShortcodeDiv = document.querySelector('.preview_lister_shortcode_div')
let shortcodePreviewClick = false
let shortcodePreviewButtonClick = false



// copy shortcode button
copyShortcodeBtc.addEventListener('click',()=>{
  let copyText =  document.querySelector('.shortcode_lister_input')
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */
   /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText.value);
})


// check if expand preview shortcode clicked 
if(localStorage.getItem('previewShortcodeClicked') !== undefined || null)
{
  if(localStorage.getItem('previewShortcodeClicked') === 'true'){

    shortcodePreviewClick = true
    ShortcodePreviewDiv.style.height = '14.5vh'
    shortcodePreviewText.style.display = 'none'
    closeShortcodePreview.innerHTML = 'Expand'
  }
  else{
    shortcodePreviewClick = false
    ShortcodePreviewDiv.style.height = 'auto'
    shortcodePreviewText.style.display = 'block'
    closeShortcodePreview.innerHTML = 'Close'
  }
}
closeShortcodePreview.addEventListener('click',()=>{
  
  if(shortcodePreviewClick === false)
  {

    localStorage.setItem('previewShortcodeClicked','true')
    shortcodePreviewClick = true
    ShortcodePreviewDiv.style.height = '14.5vh'
    shortcodePreviewText.style.display = 'none'
    closeShortcodePreview.innerHTML = 'Expand'
  }
  else {
    localStorage.setItem('previewShortcodeClicked','false')
    shortcodePreviewClick = false
    ShortcodePreviewDiv.style.height = 'auto'
    shortcodePreviewText.style.display = 'block'
    closeShortcodePreview.innerHTML = 'Close'

  }
   
})



// preview shortcode button
previewShortcodeBtc.addEventListener('click',()=>{

   if(shortcodePreviewButtonClick === false){
    arrOfShortCodeAttr.forEach(n=>{
      if(n.includes('mobile-width')) {
        let firstitoration = n.replaceAll('mobile-width=','')
        let lastitoration = firstitoration.replaceAll('"','')
      }
      if(n.includes('radius')){
        let firstitoration = n.replaceAll('radius=','')
        let lastitoration = firstitoration.replaceAll('"','')
        let arr = lastitoration.split(',')
    
        document.querySelector('.email_lister_div').style.borderRadius = `${arr[0]}px`
        document.querySelector('#email_lister_name').style.borderRadius = `${arr[1]}px`
        document.querySelector('#email_lister_email').style.borderRadius = `${arr[1]}px`
        document.querySelector('#email_lister_submit').style.borderRadius = `${arr[2]}px`

      }
      if(n.includes('width') && n.includes('mobile') === false) {
        let firstitoration = n.replaceAll('width=','')
        let lastitoration = firstitoration.replaceAll('"','')
        document.querySelector('.email_lister_div').style.width = `${lastitoration}vw`
      }
      if(n.includes('name-display') ) {
        let firstitoration = n.replaceAll('name-display=','')
        let lastitoration = firstitoration.replaceAll('"','')
        document.querySelector('#email_lister_name').style.display = lastitoration
      }
      if(n.includes('bg-color') && n.includes('send-bg-color') === false) {
        let firstitoration = n.replaceAll('bg-color=','')
        let lastitoration = firstitoration.replaceAll('"','')
        document.querySelector('.email_lister_div').style.backgroundColor = lastitoration
      }
      if(n.includes('send-bg-color')){
        let firstitoration = n.replaceAll('send-bg-color=','')
        let lastitoration = firstitoration.replaceAll('"','')
        document.querySelector('#email_lister_submit').style.backgroundColor = lastitoration
      }
      if(n.includes('send-text') ) {
        let firstitoration = n.replaceAll('send-text=','')
        let lastitoration = firstitoration.replaceAll('"','')
        document.querySelector('#email_lister_submit').value = lastitoration
      }
      if(n.includes('fullname-text') ) {
        let firstitoration = n.replaceAll('fullname-text=','')
        let lastitoration = firstitoration.replaceAll('"','')
        document.querySelector('#email_lister_name').value = lastitoration
      }
      if(n.includes('email-text') ) {
        let firstitoration = n.replaceAll('email-text=','')
        let lastitoration = firstitoration.replaceAll('"','')
        document.querySelector('#email_lister_email').value = lastitoration
  
      }
  
    })


     shortcodePreviewButtonClick =true
     previewShortcodeDiv.style.display ='block'
     previewShortcodeDiv.style.opacity = 1
     previewShortcodeBtc.innerHTML = 'Close Preview'
   }
   else {
     shortcodePreviewButtonClick = false
     previewShortcodeBtc.innerHTML = 'Preview Shortcode'
     previewShortcodeDiv.style.opacity = 0
     setTimeout(() => {
     previewShortcodeDiv.style.display ='none'
     }, 500);

   }

})


// add shortcode value
let arrOfShortCodeAttr = []


if( localStorage.getItem('shortcodesaved') !== undefined || null){
  document.querySelector('.shortcode_lister_input').value  = localStorage.getItem('shortcodesaved')
  arrOfShortCodeAttr = localStorage.getItem('shortcodesaved').split(/(\s+)/);
}
else{
  document.querySelector('.shortcode_lister_input').value = '[email_lister_form name-display="block" width="40" mobile-width="70" bg-color="blue" send-text="subscribe" send-bg-color="red" fullname-text="Enter FullName" radius="10,7.5,5" ]'

}




document.querySelector('.shortcode_lister_input').addEventListener('input',()=>{

  localStorage.setItem('shortcodesaved',document.querySelector('.shortcode_lister_input').value)

  arrOfShortCodeAttr= document.querySelector('.shortcode_lister_input').value.split(/(\s+)/);



 
})



 // analytics check for emails sent in months/days/year
emailLister[1].forEach(m=>{
  let checkToday = new Date(m)

  if(today.getDate() === checkToday.getDate() && today.getDay() === checkToday.getDay() && today.getMonth() === checkToday.getMonth() &&  today.getFullYear() === checkToday.getFullYear())
  {
    emailsSavedToday.push(m)
  }
  
  if(today.getMonth() === checkToday.getMonth() &&  today.getFullYear() === checkToday.getFullYear())
  {
    emailsSavedMonth.push(m)
  }
})

 // set analytics after .5 sec
setTimeout(() => {
  document.querySelector('#subscribeNumVal').innerHTML = emailLister[0].length + 1
  document.querySelector('#subscribeNumTodayVal').innerHTML = emailsSavedToday.length + 1
  document.querySelector('#subscribeNumMonthVal').innerHTML = emailsSavedMonth.length + 1
}, 500);



// add copy options to subscriber email
copys.forEach(c=>{
    c.addEventListener('click',()=>{
      let copyText =  document.querySelector(`[data-id="${c.getAttribute('id')}"]`)
      copyText.select();
      copyText.setSelectionRange(0, 99999); /* For mobile devices */

       /* Copy the text inside the text field */
      navigator.clipboard.writeText(copyText.value);
    })
 })






}



// Send Mails Script
else{ 


  
  let previewButton = document.querySelector('.lister_preview_button')
  let previewDiv = document.querySelector('.lister_preview_div')

  let addButtons = [...document.querySelectorAll('.email_lister_add_emails_add')]
  let _emails = [...document.querySelectorAll('.email_lister_add_emails_value')]

  let maxBulkNum = 50


  let emailsToSent = []
  let previewButtonClicked = false


  document.querySelector('.copy_html').addEventListener('click',()=>{
    let copyText =document.querySelector('#send_lister_bulk_mail_message_to').placeholder



     /* Copy the text inside the text field */
    navigator.clipboard.writeText(copyText);
  })



  previewButton.addEventListener('click',()=>{
    if(previewButtonClicked === false){
      previewDiv.innerHTML = document.querySelector('#send_lister_bulk_mail_message_to').value ||  document.querySelector('#send_lister_bulk_mail_message_to').placeholder
      previewButton.innerHTML = 'Close'
      previewButtonClicked = true
      previewDiv.style.display = 'block'
      previewDiv.style.transition = '.5s linear'
      previewDiv.style.opacity = 1

    }
    else {
      previewButtonClicked = false
      previewButton.innerHTML = 'Preview'
      previewDiv.style.transition = '.5s linear'
      previewDiv.style.opacity = 0
      setTimeout(() => {
        previewDiv.style.display = 'none'
      }, 500);

    }
  })

  document.addEventListener('click',()=>{
    document.querySelector('#send_lister_bulk_mail_to').value = emailsToSent
  })


  addButtons.forEach((b,i)=>{
    let click = false
    b.addEventListener('click',()=>{

         if(emailsToSent.length > maxBulkNum - 1) return;
          if(click === false)
          {
            click = true
            b.innerHTML = 'Delete'
            emailsToSent.push(_emails[i].innerHTML)
            b.setAttribute('id','email_lister_add_hoverd')
          }
          else {
            click = false
            b.innerHTML = 'Add'
            let index = emailsToSent.indexOf(_emails[i].innerHTML);
            if (index > -1) {
              emailsToSent.splice(index, 1); // 2nd parameter means remove one item only
            }
            b.setAttribute('id','')


          }

         
    })

  })

}


})