<?php
/*
 * Template Name: book a demo 
 */
get_header("request");  ?>

<style>
  .iti__country-list{
    font-size: 16px;
    z-index: 3;
  }
  .iti--separate-dial-code .iti__selected-dial-code{
    font-size: 16px;
  }
  .stimezone select{
    font-size: 16px;
  }
  input.form-control.start_date.text-field{
    border-radius: 4px;
  }
  .request-wrapper .logbox {
  padding: 0px 35px 0px 35px;
  max-width: 550px;
  width: 100%;
  margin-left: 50%;
}
.mrequest_form .form-control{
  padding: 20px 18px!important;
}
.mrequest_form .btn{
  margin-top: 0px!important;
}
.mrequest_form h6{
  margin-bottom: 15px!important;
  padding-top: 0px!important;
}
.datepicker td{
  font-size: 14px!important;
}
.datepicker th{
  font-size: 14px!important;
}
@media screen and (max-width: 1000px) {
  .request-wrapper .logbox {
  margin: auto;
}
}
</style>
<div class="mrequest_fixed_box" style="background-image: url(<?php echo get_site_url() . "/wp-content/themes/revechat/images/signupnew/login-bg.svg" ?>); background-repeat: no-repeat; background-size: cover; background-position: center; z-index: 10;" >
  <div class="mrequest_demo_left_fixed_box">
    <header class="signup-new-left">
      <div class="signup-new-lwrap">
      <div class="signup-left-top">
          <a href="https://www.revechat.com/"><img class="desktop-logo" src="<?php echo get_template_directory_uri();?>/images/signupnew/reve-chat-white-logo.svg" alt="ReveChat Logo"></a>
        </div>
        <div class="signup-left-bottom">
          <div class="owl-carousel owl-theme owl-loaded owl-drag">
            <div class="owl-stage-outer">
              <div class="owl-stage" style="transform: translate3d(-1200px, 0px, 0px); transition: all 0.25s ease 0s; width: 2800px;">
                <div class="owl-item" style="width: 390px; margin-right: 10px;">
                  <div class="testtimonial-item item">
                    <p class="testimonial-text">" REVE Chat is a great tool for customer support. We are using both the bot &amp; live chat. While the bot helps to resolve FAQs, chat allows engaging customers with co browsing &amp; video chat "</p>
                    <div class="testimonial-user-content">
                      <img src="https://www.revechat.com/wp-content/themes/revechat/images/signupnew/susan_foo.png" alt="">
                      <div class="testimonial-user-info">
                        <h2>Susan Foo</h2>
                        <p>Business Development Manager,<br> Public Gold</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="owl-item active" style="width: 390px; margin-right: 10px;">
                  <div class="testtimonial-item item">
                    <p class="testimonial-text">" Transcom Digital is using REVE Chat to generate leads from www. transcomdigital.com. We got new leads last year &amp; are using the Click to Chat/Call link in the social media campaigns "</p>
                    <div class="testimonial-user-content">
                      <img src="https://www.revechat.com/wp-content/themes/revechat/images/signupnew/faridul_amin.png" alt="">
                      <div class="testimonial-user-info">
                        <h2>Md. Faridul Amin</h2>
                        <p>Head of Ecommerce &amp; <br> Omnichannel Operations,  Transcom Digital</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="owl-item" style="width: 390px; margin-right: 10px;">
                  <div class="testtimonial-item item">
                    <p class="testimonial-text">" With REVE Bot, we were able to handle 85% of our support queries. We noticed a significant increase in the satisfaction rate and happier customers "</p>
                    <div class="testimonial-user-content">
                      <img src="https://www.revechat.com/wp-content/themes/revechat/images/signupnew/massimiliano.png" alt="">
                      <div class="testimonial-user-info">
                        <h2>Massimiliano Ciarrocca</h2>
                        <p>CTO, Pardgroup SpA</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>
  </div>
</div>
<section class="request-wrapper">
  <div class="container-fluid">
    <div class="row">
     
      <!-- /.col-md-5 -->
      
        <div id="logbox" class="logbox mrequest_form schedule-sucess-msg">
          <div class="mbook_a_demo"> 
          <h2 class="title">Book a REVE Chat Demo</h2>
          <h6>Please fill up the form to book a 30-minute demo</h6>
          <?php echo  do_shortcode('[schedule_visit]'); ?>
          </div>
        
      </div>
      <!-- /.col-md-7 -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container -->
</section>

<!-- /.request-wrapper -->
<?php wp_footer(); ?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri();
                              ?>/css/intlTelInput.css">
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="//static.revechat.com/portal/lib/intlTelInput.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
  jQuery(document).ready(function() {
    var owl = jQuery('.owl-carousel');
    owl.owlCarousel({
      /*
      margin: 10,
      nav: true,
      autoPlay: true,
      autoplayTimeout:20000,
      paginationSpeed: 200,
      loop: true,
      nav: false,
      dots:true,
      items: 1,
      rtl: true,
      autoplayHoverPause:true,
      singleItem: true,
      responsiveClass:true
      */

      nav: true,
      autoplay: true,
      loop: true,
      //rewind: true,
      // infinite:true,
      //singleItem: true,
      nav: false,
      dots: true,
      items: 1,
      // slideBy:6,
      autoplayTimeout: 7000,
      //lazyLoad: true,
      autoplayHoverPause: true,

    })
  })
</script>

<script>
  jQuery('#datepicker').datepicker({ 
    startDate: new Date()
});
</script>
<script type="text/javascript">
_linkedin_partner_id = "4389708";
window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
window._linkedin_data_partner_ids.push(_linkedin_partner_id);
</script><script type="text/javascript">
(function(l) {
if (!l){window.lintrk = function(a,b){window.lintrk.q.push([a,b])};
window.lintrk.q=[]}
var s = document.getElementsByTagName("script")[0];
var b = document.createElement("script");
b.type = "text/javascript";b.async = true;
b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
s.parentNode.insertBefore(b, s);})(window.lintrk);
</script>
<noscript>
<img height="1" width="1" style="display:none;" alt="" src="https://px.ads.linkedin.com/collect/?pid=4389708&fmt=gif" />
</noscript>

</body>

</html>