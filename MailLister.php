<?php

/*

*Plugin Name: MailLister
*Description:Save subscribers on your admin dashboard in easy way.
*Version: 1.0.0
*Author: Ofek Nakar
*Author URI: https://www.loudguys.co
*Plugin URI:/EmailLister
*Text Domain: EmailLister
* License: GPLv2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html



*/


if( !defined('ABSPATH')) exit;




class emailLister {



    function __construct(){
        add_action('admin_menu',array($this,'adminSet'));
        add_shortcode('email_lister_form', array($this,'shortcodeLister'));
        add_action('init',array($this,'emails_posts'));
        add_action('admin_init',array($this,'adminAssets'));
        add_action('init',array($this,'frontScripts'));
        add_action('admin_menu',array($this,'formCap'));

    }
   
   function formCap(){
    
    
    if(isset ($_POST['submit_lister_bulk_email']))
    {

        $msg = sanitize_text_field( $_POST['send_lister_bulk_mail_message_to']);
        $to = sanitize_text_field( $_POST['send_lister_bulk_mail_to'] );
        $subject = sanitize_textarea_field( $_POST['send_lister_bulk_subject_to'] );
        $fromEmail = site_url();
        $message = '<div >'.
        $msg.
        '</div>';
        $headers[] = 'From: '. site_url() . ' <customchatbot@wordpress.net>';
        $headers[]= 'Content-type: text/html';
        wp_mail($to,$subject,$message,$headers);

    }
    
   }



   function adminAssets(){
 

    wp_enqueue_style('adminEmailStyle',plugin_dir_url(__FILE__). 'css/adminEmail.css', array(), '1.0.0', 'all' );
    wp_enqueue_script('adminEmailScript',plugin_dir_url(__FILE__). 'js/adminEmails.js',array());
    wp_enqueue_script('adScript',plugin_dir_url(__FILE__). 'js/ad.js',array());



    $args = array(
        'posts_per_page'=>-1,
        'post_type'=>'list_email_type'
    );
    $ourPosts = get_posts($args);
    $arr = [];
    $arrDates = [];

    foreach($ourPosts as $key=>$val){
         array_push($arr,$val-> post_excerpt);
         array_push($arrDates,$val-> post_date);

        }

    $scriptPost = array(
     $arr,
     $arrDates

    );
    wp_localize_script('adminEmailScript','emailLister',$scriptPost);


    $month = esc_attr(get_option('mail_lister_month'));
    $year = esc_attr(get_option('mail_lister_year'));

    $scriptDate = array(
        $month,
        $year
    );
    wp_localize_script('adScript','theDates',$scriptDate);




    add_settings_section( 'mail_lister_section',null , null, 'email-lister-page' );

    add_settings_field('mail_lister_month', null, array($this,'staticHtml'),'email-lister-page', 'mail_lister_section');
    register_setting('emaillisterplugin', 'mail_lister_month', array('sanitize_callback' => 'sanitize_text_field', 'default' =>'03') ) ;
    register_setting('emaillisterplugin', 'mail_lister_year', array('sanitize_callback' => 'sanitize_text_field', 'default' =>'2022') ) ;


    


   }

   function staticHtml(){
       ?>
       <input type="hidden" name="mail_lister_month"id="mail_lister_month" value="<?php echo esc_attr(get_option('mail_lister_month','03')); ?>">
       <input type="hidden" name="mail_lister_year"id="mail_lister_year" value="<?php echo esc_attr(get_option('mail_lister_year','2022')); ?>">
       <?php 
   }

    function adminSet(){
        add_menu_page('EmailLister','Mail Lister', 'manage_options' ,'email-lister-page',array($this,'adminHTML'),plugin_dir_url(__FILE__) . 'menuIcons.svg',100);
        add_submenu_page('email-lister-page','Send Emails','Send Mails','manage_options','email-lister-page-send',array($this,'adminSendEmailHTML'));

    }

     
    function frontScripts(){
     

        if (isset( $_POST["email_lister_submit"] ) && wp_verify_nonce( $_POST["cpt_nonce_field"], "cpt_nonce_action" ) ) {
                
            
            $my_cptpost_args = array(
            
            'post_title'   => sanitize_text_field($_POST['email_lister_name']),
            'post_content'  => sanitize_text_field($_POST['email_lister_email']),
            'post_excerpt'  => sanitize_text_field($_POST['email_lister_email']),
            'post_status'   => 'publish',
            'post_type' => 'list_email_type',
            'post_author'   => 1,
            'post_category' => array(1)
            
            );
            // insert the post into the database
            wp_insert_post($my_cptpost_args);
            
            
            }
            
            
        wp_enqueue_script('listerScript',plugin_dir_url(__FILE__). '/listerD.js',array());
        wp_enqueue_script( 'jqueryexample',plugin_dir_url(__FILE__). '/jquery.js',array());

        $args = array(
            'posts_per_page'=>-1,
            'post_type'=>'list_email_type'
        );
        $ourPosts = get_posts($args);
        $arr = [];
    
        foreach($ourPosts as $key=>$val){
             array_push($arr,$val-> post_excerpt);
            }
    
        $scriptPost = array(
         $arr,
    
        );
        wp_localize_script('listerScript','emailLister',$scriptPost);
        wp_enqueue_style('listerStyle',plugin_dir_url(__FILE__). 'css/style.css', array(), '1.0.0', 'all' );
    }

    function adminHTML(){
        ?>

        <div class="preview_lister_shortcode_div">

         <div class="email_lister_div">
     
          <form class="email_lister_form" method="POST" action="/">
               <input type="text" name="email_lister_name" id="email_lister_name" class="email_lister_input" value="full name" >
                <input type="email" name="email_lister_email" id="email_lister_email" class="email_lister_input" value="email" >
                <input type="submit" id="email_lister_submit" value="Send" name="email_lister_submit" >

          </form>
           
         </div>


        </div>


        <div class="email_lister_bannner">
           Mail Lister <div class="animate-logo-span-mail">📣</div>
        </div>
       <div class="shortcodeHolder">
           <div class="close_preview_shortcode lister_simple_btc">Close</div>
      <p class="info-lister-start">
          Hello :) Change and Customize your Subscriber shortcode and preview the result,
          when someone submit their details through Mail lister shortcode you will see 
          immediately the information add into your Mail Lister dashboard,when you collect
          emails you can use Send Mails on the menu to send bulk customize Emails to your 
          subscribers
      </p>
      <textarea class="shortcode_lister_input" placeholder='[email_lister_form name-display="block" width="40" mobile-width="70" bg-color="blue" send-text="subscribe" send-bg-color="red" fullname-text="Full name" radius="10,7.5,5"]'></textarea>
       <div class="copy_lister_shortcode lister_simple_btc">Copy Shortcode</div>        <div class="preview_lister_shortcode lister_simple_btc">Preview Shortcode</div> 
       <p class="info-lister-start" style="text-align:center;margin:2% 0%;">
           Learn more about other MapleWP Plugins in our website
           <a href="https://maple-wp.com" target="_blank">Learn more</a>
       </p>
      
    </div>  


          <div class="email_lister_holder_select">
          <form action="options.php" method="POST" class="email_lister_options_form">

<?php
    settings_fields('emaillisterplugin');
    do_settings_sections('email-lister-page');
    submit_button('Filter');
?>

</form>
  <p class="lister_select_inline_p">Filter by Month</p>
   <div class="custom-select" style="width:200px;">
  <select id="lister_select_month">
    <option value="0">Filter by Month</option>
    <option value="01" >January</option>
    <option value="02">February</option>
    <option value="03">March</option>
    <option value="04">April</option>
    <option value="05">May</option>
    <option value="06">June</option>
    <option value="07">July</option>
    <option value="08">August</option>
    <option value="09">September</option>
    <option value="10">October</option>
    <option value="11">November</option>
    <option value="12">December</option>
  </select>
</div>
<p class="lister_select_inline_p">Filter by Year</p>
<div class="custom-select" style="width:200px;">
  <select id="lister_select_year">
    <option value="0">Filter by Year</option>
    <option value="2020" >2020</option>
    <option value="2021" >2021</option>
    <option value="2022" >2022</option>
    <option value="2023" >2023</option>
    <option value="2024" >2024</option>
    <option value="2025" >2025</option>
    <option value="2026" >2026</option>
  </select>
</div>

          </div>


        <div class="email_lister_holder">
            
    
        <?php
        $url = get_bloginfo('url');
     
      
$args = array(
    'posts_per_page'=>-1,
    'post_type'=>'list_email_type'
);
$ourPosts = get_posts($args);
$randNum = 0;

foreach($ourPosts as $key=>$val){
    $randNum +=1;
    $dates =  explode('-', $val-> post_date);




    if($dates[1] == get_option('mail_lister_month','03') && $dates[0] == get_option('mail_lister_year','2022'))
    {
   ?>
      <div class="email_lister_subscribe_row">
            <p class="lister_label"><span class="lister_num_span"><?php echo esc_attr($randNum) ?>. </span>Name : </p>
            <div class="email_lister_name_val"><?php echo esc_attr( $val ->post_title ) ?></div>
            <p class="lister_label">Email : </p>
            <input type="text" data-id="<?php echo esc_attr($val -> ID) ?>" class="email_lister_email_val" value="<?php echo esc_attr( $val ->post_excerpt ) ?>">
            <div class="email_lister_copy" id="<?php echo esc_attr($val -> ID) ?>">Copy</div>
            <a href="<?php echo wp_nonce_url(site_url() ."/wp-admin/post.php?action=delete&amp;post=". $val -> ID,'delete-post_'. $val->ID) ?>" class="email_lister_delete_subscribe">x</a>

        </div>
   <?php

} 
else if(get_option('mail_lister_year') == '0' && get_option('mail_lister_month') == $dates[1])
{
    ?>
    <div class="email_lister_subscribe_row">
          <p class="lister_label"><span class="lister_num_span"><?php echo esc_attr($randNum) ?>. </span>Name : </p>
          <div class="email_lister_name_val"><?php echo esc_attr( $val ->post_title ) ?></div>
          <p class="lister_label">Email : </p>
          <input type="text" data-id="<?php echo esc_attr($val -> ID) ?>" class="email_lister_email_val" value="<?php echo esc_attr( $val ->post_excerpt ) ?>">
          <div class="email_lister_copy" id="<?php echo esc_attr($val -> ID) ?>">Copy</div>
          <a href="<?php echo wp_nonce_url(site_url() ."/wp-admin/post.php?action=delete&amp;post=". $val -> ID,'delete-post_'. $val->ID) ?>" class="email_lister_delete_subscribe">x</a>

      </div>
 <?php

}
else if(get_option('mail_lister_month') == '0' && get_option('mail_lister_year') == $dates[0])
{
    ?>
    <div class="email_lister_subscribe_row">
          <p class="lister_label"><span class="lister_num_span"><?php echo esc_attr($randNum) ?>. </span>Name : </p>
          <div class="email_lister_name_val"><?php echo esc_attr( $val ->post_title ) ?></div>
          <p class="lister_label">Email : </p>
          <input type="text" data-id="<?php echo esc_attr($val -> ID) ?>" class="email_lister_email_val" value="<?php echo esc_attr( $val ->post_excerpt ) ?>">
          <div class="email_lister_copy" id="<?php echo esc_attr($val -> ID) ?>">Copy</div>
          <a href="<?php echo wp_nonce_url(site_url() ."/wp-admin/post.php?action=delete&amp;post=". $val -> ID,'delete-post_'. $val->ID) ?>" class="email_lister_delete_subscribe">x</a>

      </div>
 <?php

}
else if(get_option('mail_lister_month') == '0' && get_option('mail_lister_year') == '0'){
    ?>
    <div class="email_lister_subscribe_row">
          <p class="lister_label"><span class="lister_num_span"><?php echo esc_attr($randNum) ?>. </span>Name : </p>
          <div class="email_lister_name_val"><?php echo esc_attr( $val ->post_title ) ?></div>
          <p class="lister_label">Email : </p>
          <input type="text" data-id="<?php echo esc_attr($val -> ID) ?>" class="email_lister_email_val" value="<?php echo esc_attr( $val ->post_excerpt ) ?>">
          <div class="email_lister_copy" id="<?php echo esc_attr($val -> ID) ?>">Copy</div>
          <a href="<?php echo wp_nonce_url(site_url() ."/wp-admin/post.php?action=delete&amp;post=". $val -> ID,'delete-post_'. $val->ID) ?>" class="email_lister_delete_subscribe">x</a>

      </div>
 <?php
}

}

 ?>
</div>


<div class="email_lister_stats_holder">
    <h2 class="lister_menu_title">
        Analytics
    </h2>
    <div class="lister_stats_row">
        <p class="listertitleText">Subscribers All Time</p><p class="listertitleValue" id="subscribeNumVal">...</p>
    </div>
    <div class="lister_stats_row">
      <p class="listertitleText">Subscribers This Month</p><p class="listertitleValue" id="subscribeNumMonthVal">...</p>
    </div>
    <div class="lister_stats_row">
      <p class="listertitleText">Subscribers Today</p><p class="listertitleValue" id="subscribeNumTodayVal">...</p>
    </div>
</div>



<div class="wpmaple_logo">
  <a href="leaflogo" href="https://maple-wp.com" target="_blank"> 
    <div class="wpLogo">Made by MapleWP</div>
    </a>
    <div class="leaf"></div>
</div>

<?php

    }











    function emails_posts()
    {
    
        register_post_type( 'list_email_type',
        array(
            'labels'=>array(
                'name'=>__('ListerEmails'),
                'singular_name'=>__('Email'),
                'add_new'=>null,
                'add_new_item'=> null,
                'edit_item'=> __('Edit Email'),
    
            ),
            
            'menu_position'=>55,
            'public'=>true,
            'show_ui'=>false,
            'show_in_nav'=>false,
            'supports' => array( 'title', 'excerpt', ),    
            'exclude_from_search' => true,
	    	'capability_type' => 'post',
        )
       );
        
  
    }

    function shortcodeLister($atts =[]){










        $attributes = shortcode_atts(
            array(
               'name-display'=> '',
               'width' => '',
               'mobile-width' => '',
               'bg-color' => '',
               'send-text' => '',
               'send-bg-color' => '',
               'email-text' => '',
               'fullname-text' => '',
               'radius'=> '',
             ), 
            $atts
        );

        $displayName = preg_replace( '/\s*,\s*/', ',', filter_var( $attributes['name-display'], FILTER_SANITIZE_STRING ) ); 
        $no_whitespacesWidth = preg_replace( '/\s*,\s*/', ',', filter_var( $attributes['width'], FILTER_SANITIZE_STRING ) ); 
        $no_whitespacesMobileWidth = preg_replace( '/\s*,\s*/', ',', filter_var( $attributes['mobile-width'], FILTER_SANITIZE_STRING ) ); 
        $no_whitespacesBGColor = preg_replace( '/\s*,\s*/', ',', filter_var( $attributes['bg-color'], FILTER_SANITIZE_STRING ) ); 
        $no_whitespacesSendText = preg_replace( '/\s*,\s*/', ',', filter_var( $attributes['send-text'], FILTER_SANITIZE_STRING ) ); 
        $no_whitespacesSendBGColor = preg_replace( '/\s*,\s*/', ',', filter_var( $attributes['send-bg-color'], FILTER_SANITIZE_STRING ) ); 
        $no_whitespacesEmailText = preg_replace( '/\s*,\s*/', ',', filter_var( $attributes['email-text'], FILTER_SANITIZE_STRING ) ); 
        $no_whitespacesNameText = preg_replace( '/\s*,\s*/', ',', filter_var( $attributes['fullname-text'], FILTER_SANITIZE_STRING ) ); 
        $no_whitespacesRadius = preg_replace( '/\s*,\s*/', ',', filter_var( $attributes['radius'], FILTER_SANITIZE_STRING ) ); 
       
        $width_array = explode( ',', $no_whitespacesWidth );
        $MobileWidth_array = explode( ',', $no_whitespacesMobileWidth );
        $bgColor_array = explode( ',', $no_whitespacesBGColor );
        $Send_array = explode( ',', $no_whitespacesSendText );
        $SendBgColor_array = explode( ',', $no_whitespacesSendBGColor );
        $Email_array = explode( ',', $no_whitespacesEmailText );
        $Name_array = explode( ',', $no_whitespacesNameText );
        $Radius_array = explode( ',', $no_whitespacesRadius );

        ?>
     
        <div class="email_lister_div" data-width="<?php echo esc_attr($width_array[0])?>" data-mobile-width="<?php echo esc_attr($MobileWidth_array[0])?>" style="width:<?php echo esc_attr($width_array[0])?>vw;background-color:<?php echo esc_attr( $bgColor_array[0]) ?>;border-radius:<?php if($Radius_array[0] !== '') echo esc_attr( $Radius_array[0]); ?>px !important;">
         <div class="lister_warn_message">
             Email adress already taken
         </div>   
        <form class="email_lister_form" method="POST" action="/">
               <input type="text" name="email_lister_name" id="email_lister_name" class="email_lister_input" value="
               <?php 
                   if($Name_array[0] == '') echo 'Enter your FullName';
                   else echo esc_attr( $Name_array[0]);
                  ?>
               " style="border-radius:<?php if($Radius_array[1] !== '') echo esc_attr($Radius_array[1]);?>px !important;display:<?php echo esc_attr( $displayName) ?>;">
                <input type="email" name="email_lister_email" id="email_lister_email" class="email_lister_input" value="
                <?php 
                   if($Email_array[0] == '') echo 'Enter your Email';
                   else echo esc_attr( $Email_array[0]);
                  ?>
                  " style="border-radius:<?php if($Radius_array[1] !== '') echo esc_attr($Radius_array[1]);?>px !important;">
                <input type="submit" id="email_lister_submit" value="<?php 
                   if($Send_array[0] == '') echo 'Send';
                   else echo esc_attr( $Send_array[0]);
                  ?>" name="email_lister_submit" style="background-color:
                  <?php 
                   if($SendBgColor_array[0] == '') echo 'green';
                   else echo esc_attr( $SendBgColor_array[0]);?>!important;border-radius:<?php if($Radius_array[2] !== '') echo esc_attr($Radius_array[2]);?>px !important;">



           <?php wp_nonce_field("cpt_nonce_action", "cpt_nonce_field" ); ?>


            </form>
           
        </div>


        <?php




}


function adminSendEmailHTML(){

    $args = array(
        'posts_per_page'=>-1,
        'post_type'=>'list_email_type'
    );
    $ourPosts = get_posts($args);

 

    ?>
    <div class="lister_small_gap"></div>
    <div class="email_lister_add_emails_div">

    <?php
    $num = 0;
   foreach($ourPosts as $key=>$val){
       $num +=1;
       ?>

      <div class="email_lister_add_emails_row">
          <p class="email_lister_add_emails_mails"><span class="lister_num_span"><?php echo $num ?>.</span> <span class="email_lister_add_emails_value"> <?php echo $val-> post_excerpt ?></span></p>
          <div class="email_lister_add_emails_add">Add</div>
      </div>
       <?php
  

   }
    ?>
    </div>
       

    <div class="lister_preview_button">Preview</div>

    <div class="lister_preview_div"></div>

    <div class="email_lister_sent_emails_div">
      
      <form id="email_lister_send_bul_mail" method="POST">
          <p class="email_lister_bulk_title">Email To </p>
          <textarea id="send_lister_bulk_mail_to" name="send_lister_bulk_mail_to"class="lister_bulk_mail_input lister_textarea"></textarea>
          <p class="email_lister_bulk_title">Email Subject</p>
          <input type="text" class="lister_bulk_mail_input lister_input" id="send_lister_bulk_subject_to" name="send_lister_bulk_subject_to">
          <p class="email_lister_bulk_title">Email Message</p>
          <textarea id="send_lister_bulk_mail_message_to" class="lister_bulk_mail_input lister_msg" name="send_lister_bulk_mail_message_to"
          placeholder='
          <style>
             
main div{
width:32.5vw;
height:35vh;
position:relative;
margin:3% auto;
background-image:url(https://media.giphy.com/media/OF0yOAufcWLfi/giphy.gif);
background-size:cover;
background-repeat:no-repeat;

}
main p{
text-align:center;color:#000;font-size:1.25em;width:70%;position:relative;left:15%;
}
section p{
    position: relative;
    display: inline-block;
    font-size: 4em;
    font-weight: 800;
    top: -50px;
    left: 15%;
    color: #000;
}
section div {
    background-size: contain;
    background-repeat: no-repeat;
    position: absolute;
    width: 120px;
    height: 120px;
    left: 25%;
    top: 15%;
}
main a{
font-weight:700;
text-decoration:none;
padding:1%;background-color:#3275b6;text-align:center !important;color:#fff;display:block;position:relative;margin:0 auto;width:100px;
}
section{
width:100%;height:20%;position:absolute;top:80%;left:0%;
}
p{
text-align:center;
}
h1{
font-weight:900;
text-align:center;
color:#3275b6;
font-size:3em;
  -webkit-animation: glow 1s ease-in-out infinite alternate;
  -moz-animation: glow 1s ease-in-out infinite alternate;
  animation: glow 1s ease-in-out infinite alternate;
}
 @keyframes glow {

   from {
    text-shadow: 0 0 10px #3275b6; 0 0 20px #3275b6; 0 0 30px #e60073, 0 0 40px #e60073, 0 0 50px #e60073, 0 0 60px #e60073, 0 0 70px #e60073;
  }
  to {
    text-shadow: 0 0 20px #3275b6;, 0 0 30px #ff4da6, 0 0 40px #ff4da6, 0 0 50px #ff4da6, 0 0 60px #ff4da6, 0 0 70px #ff4da6, 0 0 80px #ff4da6;
  }
}
</style>
<div >
<main>
<div></div>

<p >
MapleWP is a team dedicated to create easy to use, easy to expand and helpful wordpress products to the wordpress community, our products our target towards developers and designers, and even dedicated site owners that want to expand their engagement with their users and visitors on their platform,you can learn more about MapleWP products on our website.
</p>

<a href="https://maple-wp.com" >Learn More</a></main><section>
<p >MapleWP</p>
<div></div></section></div> '

></textarea>
             <div class="copy_html">Copy HTML</div>
          <input type="submit" id="submit_lister_bulk_email" value="Send" name="submit_lister_bulk_email">
      </form>
    
    </div>

    <div class="wpmaple_logo">
    <a href="leaflogo" href="https://maple-wp.com" target="_blank"> 
    <div class="wpLogo">Made by MapleWP</div>
    <div class="leaf"></div>
    </a>
    </div>


   <?php
}



}




$emailLister = new emailLister();










?>