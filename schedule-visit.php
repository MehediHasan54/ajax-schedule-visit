<?php
/*
Plugin Name: AJAX Schedule Visit Plugin
Plugin URI: http://www.revechat.com
Description: Simple AJAX Schedule Visit
Version: 1.0
Author: Zeerin
Author URI: www.revechat.com
*/

// blocking direct access
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_action( 'plugins_loaded', 'schedule_visit_load', 35 );

if ( !function_exists( 'schedule_visit_load' ) ) {
	function schedule_visit_load() {
		load_plugin_textdomain( 'schedule_visit', false, dirname( plugin_basename( __FILE__ ) ) );
		new schedule_visit();
	}
}

if ( !class_exists('schedule_visit', false) ) {

	class schedule_visit {

		private $schedule_errors = array();
		
		function __construct() {

			add_action( 'wp_enqueue_scripts', array( $this, 'schedule_plugin_scripts'));
			// ajax for logged in users
			add_action( 'wp_ajax_add_schedule', array( $this, 'add_schedule' ) ); 
			// ajax for not logged in users
			add_action( 'wp_ajax_nopriv_add_schedule', array( $this, 'add_schedule') ); 
		}
		
		public function schedule_plugin_scripts() {
			
			if ( ! is_page_template( 'page-signup.php' ) ) {
				wp_enqueue_style( 'intlTelInput-css',plugins_url('css/intlTelInput.css', __FILE__) );
				wp_enqueue_script('intlTelInput-script', plugins_url('js/intlTelInput.js', __FILE__), array('jquery'), '1.0', true);

				if ( ! is_page_template( 'page-request-a-demo.php' ) ) {
					wp_enqueue_style( 'schedule-css',plugins_url('css/schedule.css', __FILE__), array(), '5.8.4' );

					//wp_enqueue_style( 'schedule-css', plugins_url( 'css/schedule.css', __FILE__ ), array(), '12345' );

					wp_enqueue_script('schedule-script', plugins_url('js/schedule.js', __FILE__), array('jquery'), '1.9', true);
				}

				wp_localize_script('schedule-script', 'ajax_schedule', array(
			        'ajaxurl' => admin_url('admin-ajax.php'),'directory_url' => get_template_directory_uri()
			    ));
			}
		}
		
		function create_schedule_form() {

			$utm_source='';$utm_medium='';$utm_campaign='';$utm_content='';
			$modal_utm_str='';
			
			$is_landing_page=false;
			if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
			$url = "https://";   
			else  
				$url = "http://";   
			// Append the host(domain name, ip) to the URL.   
			$url.= $_SERVER['HTTP_HOST'];   
			
			// Append the requested resource location to the URL   
			$url.= $_SERVER['REQUEST_URI']; 
			// $current_url='https://www.revechat.com/demo-request/?utm_source=LinRetOthers&utm_medium=LinkedIn&utm_campaign=OtherIndustries';
			// if($url==$current_url){
			// $utm_str='utm_source=LinRetOthers&utm_medium=LinkedIn&utm_campaign=OtherIndustries';
			// }else{
			// 	$utm_str="";
			// }
			
			if(isset($_GET['utm_source']) && $_GET['utm_source']!=''){
				$utm_source=$_GET['utm_source'];
			}
			if(isset($_GET['utm_medium']) && $_GET['utm_medium']!=''){
				$utm_medium=$_GET['utm_medium'];
			}
			if(isset($_GET['utm_campaign']) && $_GET['utm_campaign']!=''){
				$utm_campaign=$_GET['utm_campaign'];
			}
			if(isset($_GET['utm_content']) && $_GET['utm_content']!=''){
				$utm_content=$_GET['utm_content'];
			}
			
			echo '<div class="schedule-msg" style="margin-bottom: 10px;"></div>';
			//if((!isset($error) && $error=="") || (!isset($success) && $success=="")){
			echo '<form action="'.esc_url( $_SERVER['REQUEST_URI'] ).'" method="post" accept-charset="utf-8" name="form" id="topForm" class="down">';
			
				echo '<input type="hidden" value="'.esc_url( $_SERVER['REQUEST_URI'] ).'" name="utm_source" id="name">';
		   
			echo '<div class="row mb-3"><div class="col">';
			echo '<input type="text" class="form-control text-field" name="cf-name" id="name" placeholder="Enter Your Name" required="required" autocomplete="off" pattern="[a-zA-Z0-9 ]+" value="'.( isset( $_POST["cf-name"] ) ? esc_attr( $_POST["cf-name"] ) : '' ) . '" size="40" />';
			echo '</div></div>';

			echo '<div class="row mb-3"><div class="col">';
			echo '<input type="email" class="form-control text-field" name="cf-email" id="email" placeholder="Enter Your Email" required="required" autocomplete="off" value="'.( isset( $_POST["cf-email"] ) ? esc_attr( $_POST["cf-email"] ) : '' ) . '" size="40" />';
			echo '</div></div>';

			echo '<div class="row mb-3"><div class="col">';
			if(is_page_template("page-request-a-demo.php")){
				echo '<label class="mprequest" >Enter your mobile </label>';
		   }
			echo '<input type="tel" class="form-control text-field" name="cf-mobile"  id="phone" style="padding-left: 125px;" placeholder="Enter Your Mobile" autocomplete="off" required="required" value="' . ( isset( $_POST["cf-mobile"] ) ? esc_attr( $_POST["cf-mobile"] ) : '' ) . '" size="40" />';
			echo '</div></div>';
			echo '<input type="hidden" name="country"  id="country_code" value="">';
			echo '<input type="hidden" name="calling_code"  id="calling_code" value="">';

			echo '<div class="row mb-3"><div class="col">';
			echo '<input type="text" class="form-control text-field" name="cf-organization"  id="organization" placeholder="Enter Your Organization" autocomplete="off" required="required" value="' . ( isset( $_POST["cf-organization"] ) ? esc_attr( $_POST["cf-organization"] ) : '' ) . '" size="40" />';
			echo '</div></div>';


			echo '<div class="form-group row stimezone mb-3">
				<div class="offset-md-2 col-md-8">
					<label for="timezone"><strong>Time zone:</strong></label>';
					include( plugin_dir_path( __FILE__ ) . 'timezonelists.php');
			echo '</div></div>';


			/*
			 echo '<div class="form-group row">
			   <div class="col-sm-5">
			      <label>Meeting Timezone<a data-bs-content="Select timezone" data-bs-placement="bottom" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-container="body" class="tooltipbtn" data-original-title="Meeting timezone" title="">i</a></label>
			      <div> <select class="form-control meetingTimezone" id="dropdownTimeZone"></select> </div>
			   </div>
			   <div class="col-sm-6">
			      <label>Meeting Duration<a data-bs-content="Write the meeting duration" data-bs-placement="bottom" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-container="body" class="tooltipbtn" data-original-title="Meeting duration" title=""></a></label>
			      <div class="radio-btn-wrapper"><label class="radio-inline"> <input type="radio" name="meetingDuration" value="15">15 min</label><label class="radio-inline"> <input type="radio" name="meetingDuration" value="30">30 min</label>  	<label class="radio-inline"> <input type="radio" name="meetingDuration" value="45">45 min</label> <label class="radio-inline"> <input type="radio" name="meetingDuration" value="60">60 min</label> </div>
			   </div>
			</div>';
*/
			echo '<div class="row"><div class="col">';
            echo ' <div class="start_date input-group mb-3 sdatepicker-wrap"><input class="form-control start_date text-field" type="text" placeholder="Select Date" id="datepicker" name="booking_date" autocomplete="off" required>';
            echo '</div></div>';

           
            echo '<div class="col">';
            echo '<select class="form-select text-field" aria-label="Default select example" name="booking_time" required>
		      <option value="">Select Time</option>
		      <option value="8:00 AM">8:00 AM</option>
		      <option value="9:00 AM">9:00 AM</option>
		      <option value="10:00 AM">10:00 AM</option>
		      <option value="11:00 AM">11:00 AM</option>
		      <option value="12:00 PM">12:00 PM</option>
		      <option value="1:00 PM">1:00 PM</option>
		      <option value="2:00 PM">2:00 PM</option>
		      <option value="3:00 PM">3:00 PM</option>
		      <option value="4:00 PM">4:00 PM</option>
		      <option value="5:00 PM">5:00 PM</option>
		      <option value="6:00 PM">6:00 PM</option>
		      <option value="7:00 PM">7:00 PM</option>
		      <option value="8:00 PM">8:00 PM</option>
		    </select>';
		    echo '</div></div>';
           
			if(is_page_template("page-request-a-demo.php")){
				echo '<label class="mprequest" >Your message</label>';
		   }
			echo '<div class="row mb-3"><div class="col">';
			echo '<textarea class="form-control text-field" name="cf-message"  placeholder="Message..." required="required" autocomplete="off"  value="' . ( isset( $_POST["cf-message"] ) ? esc_attr( $_POST["cf-message"] ) : '' ) . '" rows="3"/></textarea>';
			echo '</div></div>';
			echo '<div class="row"><div class="col text-center"><button type="submit" class="g-recaptcha btn btn-primary qc-submit-btn submit_btn1"><b>Submit</b></button></div></div>';
			echo '<div class="row"><div class="col text-center"><button disabled type="submit" class=" btn btn-primary qc-submit-btn submit_btn"><div class="waviy">
			<span style="--i:1">S</span>
			<span style="--i:2">u</span>
			<span style="--i:3">b</span>
			<span style="--i:4">m</span>
			<span style="--i:5">i</span>
			<span style="--i:6">t</span>
			<span style="--i:7">i</span>
			<span style="--i:8">n</span>
			<span style="--i:9">g</span>
		   </div></button></div></div>';
			echo '</form>';
			//}
		}
		
		public function validate_form($name, $email, $mobile_no, $organization, $dropdowntimezone, $booking_date, $booking_time) {
		
			if(empty($name) || empty($email) || empty($mobile_no) || empty($organization) || empty($booking_date) || empty($booking_time)) {
			
				array_push($this->schedule_errors,"No field should be left empty");
				
			}
			
			if (strlen($name) < 4 ) {
				array_push($this->schedule_errors,"Name should be at least 4 characters");
				
			}
			// if($recaptchaResponse->success==false && $recaptchaResponse->score<=0.5){
			// 	array_push($this->schedule_errors,"reCaptcha validation failed");
			// }
			if (!is_email($email) ) {
				array_push($this->schedule_errors, "Please enter a valid Email address");
				
			}
			if (empty($dropdowntimezone) ) {
				array_push($this->schedule_errors, "Please select your TimeZone");
				
			}

			if(!preg_match('/^\+?([0-9]{1,4})\)?[-. ]?([0-9]{10})$/',$mobile_no)){
				array_push($this->schedule_errors, "Please enter a valid mobile number");
			}
			
			
			return $this->schedule_errors;
		
		}
		// public function verifyRecaptcha($token){
		// 	$secretKey = "6LfoO1glAAAAAPA5Pk0jRQZhPT3WQlSmU0fSBNu7";
		// 	$url = 'https://www.google.com/recaptcha/api/siteverify';
		// 	$data = array('secret' => $secretKey, 'response' => $token);
		// 	$options = array(
		// 		'http' => array(
		// 			'header'  => 'Content-type: application/x-www-form-urlencoded\r\n',
		// 			'method'  => 'POST',
		// 			'content' => http_build_query($data),
		// 		),
		// 	);
		// 	$context  = stream_context_create($options);
		// 	$result = file_get_contents($url, false, $context);
		// 	$response = json_decode($result);
		// 	return $response;
		// }
		public function add_schedule() {
			//global $wpdb;
			if(!empty($_POST)){
		
				// unserialize the data
				parse_str($_POST["data"], $_POST);
				//$recaptchaResponse=$this->verifyRecaptcha($_POST["g-recaptcha-response"]);
				// if it's book a demo page
                $utm_str=sanitize_text_field($_POST["utm_source"]);
	
				// sanitize form values
				
				$name= sanitize_text_field($_POST["cf-name"]);
				$email= filter_var($_POST["cf-email"], FILTER_SANITIZE_EMAIL);
				$mobile_no = sanitize_text_field($_POST["mobile_number"]);
				$organization = sanitize_text_field($_POST["cf-organization"]);

				$dropdowntimezone = $_POST["dropdowntimezone"];

				$booking_date = date("F j, Y",strtotime($_POST["booking_date"]));
				
				$booking_time = $_POST["booking_time"];
				$message = esc_textarea($_POST["cf-message"]);

				$get_schedule_errors = $this->validate_form($name, $email, $mobile_no, $organization, $dropdowntimezone, $booking_date, $booking_time);
	
				if(count($get_schedule_errors)>0){
					$schedule_err_details="";
					foreach($get_schedule_errors as $error){
						$schedule_err_details.=$error.'<br/>';
					}
					$data['error']= '<div class="row alert alert-danger" style="text-align:left; width: 100%; margin: auto; font-size: 16px; padding: 8px 18px;">'.$schedule_err_details.'</div>';
					$ajaxResponse=array('status'=>false,'type'=>'fresh','desc'=> $data['error']);
					echo json_encode($ajaxResponse);
					exit;
				}
				
				else{
					global $wpdb;

					$time = time() * 1000;
        			$ONE_DAY = 24 * 60 * 60 * 1000;

					$sql = "SELECT * FROM rchat_book_demo WHERE email='$email' or mobile_no='$mobile_no'";
					 // and (('$time'-creation_time)<='$ONE_DAY') order by creation_time desc";
					//$sql = "SELECT * FROM rchat_book_demo WHERE email='$email' and mobile_no='$mobile_no'";
					//echo $sql;
					$is_duplicate = $wpdb->get_results($sql);
					if(sizeof($is_duplicate) > 25){
						$data['error']='<div class="row already-registered">
							<div class="col-md-12">
								<div class="card">
									<img src="'.plugins_url('images/fluent_chat-warning-24-filled.png', __FILE__).'" class="img-fluid center-block">
									<div class="card-body">
										<h5 class="card-title text-center">You have already requested 3 times to book a demo. Please try again in 24 hours.</h5>
										<div class="text-center">
										<button class="btn btn-primary okay-btn" onClick="backToPrevious()">Okay</button>
										</div>
									</div>
								</div>
							</div>
							</div>';
							$ajaxResponse=array('status'=>false, 'type'=>'duplicate','desc'=> $data['error']);
							echo json_encode($ajaxResponse);
							exit;
					} else {
						$result=$wpdb->insert('rchat_book_demo',array('name'=>$name, 'email'=>$email, 'mobile_no'=>$mobile_no, 'organization'=>$organization, 'timezone'=>$dropdowntimezone, 'booking_date'=>$booking_date, 'booking_time'=>$booking_time, 'message'=>$message, 'creation_time' => time() * 1000,'visitor_ip' => $_SERVER['REMOTE_ADDR']));

						
						if($result==false){
							
							$data['error']='<div class="row already-registered">
							<div class="col-md-12">
								<div class="card">
									<img src="'.plugins_url('images/fluent_chat-warning-24-filled.png', __FILE__).'" class="img-fluid center-block">
									<div class="card-body">
										<h5 class="card-title text-center">Sorry! your contact details insertion failed.</h5>
										<div class="text-center">
										<button class="btn btn-primary okay-btn" onClick="backToPrevious()">Okay</button>
										</div>
									</div>
								</div>
							</div>
							</div>';
							$ajaxResponse=array('status'=>false, 'type'=>'dbfail', 'desc'=> $data['error']);
							echo json_encode($ajaxResponse);
							exit;
						}
						elseif($utm_str == "/revechat.com/price/")
						{
							$to = 'developer.hasan5@gmail.com';
							$subject='New Pricing Request from '.$name;
							$header_client = array(
								'Content-Type: text/html; charset=UTF-8',
								'From: developer.hasan5@gmail.com'
							);
							$content_client = 'Hi '.$name.',<br><br>
								Thank you for showing your interest in REVE Chat and booking a demo. This is a confirmation of your appointment. Please take a look at the details below:<br><br>

								Timezone: '.$dropdowntimezone.'<br>
								Date: '.$booking_date.'<br>
								Time: '.$booking_time.'<br><br>

								One of our Product Experts will get back to you soon.<br><br>
								REVE Chat is an omnichannel customer engagement platform that features chatbots, live chat, co-browsing, ticketing system, and more to provide sales and support assistance.<br><br>

								Feel free to keep in touch with us if you have any queries.<br><br>
								 
								Regards,<br>

								REVE Chat team';
							wp_mail($email, 'Thank you for booking REVE Chat demo!', $content_client, $header_client);
							$headers = array(
								'Content-Type: text/html; charset=UTF-8',
								'Reply-To: Ryan from REVE Chat <developer.hasan5@gmail.com>',
							    'From: developer.hasan5@gmail.com', 
							   
							    'CC: h.mehedi@revesoft.com'
							);
						
							$content='<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Verdana; font-size:13px">
								<tr>
									<td>
										<p>Hi,</p>
										<p>There is a <b>price request</b> for REVE Chat with the following details:</p>
										<p>Here are the visitor details:</p><br/>

									</td>
								</tr>
							</table>

							<table style="border-collapse: collapse; background-color: #ffd; width: 500px; font-size: 10pt;font-family:sans-serif; text-align: left;">
								<tr>
									<td align="left" width="200" style="border: 1px solid #F28B0C; padding: 2px;">Name </td>
									<td align="left" width="300" style="border: 1px solid #F28B0C; padding: 2px;">'.$name.'</td>
								</tr>
								<tr>
									<td align="left" width="200" style="border: 1px solid #F28B0C; padding: 2px;">Email</td>
									<td align="left" width="300" style="border: 1px solid #F28B0C; padding: 2px;">'.$email.'</td>
								</tr>
								<tr>
									<td align="left" width="200" style="border: 1px solid #F28B0C; padding: 2px;">Contact</td>
									<td align="left" width="300" style="border: 1px solid #F28B0C; padding: 2px;">'.$mobile_no.'</td>
								</tr>
								<tr>
									<td align="left" width="200" style="border: 1px solid #F28B0C; padding: 2px;">Company</td>
									<td align="left" width="300" style="border: 1px solid #F28B0C; padding: 2px;">'.$organization.'</td>
								</tr>
								<tr>
									<td align="left" width="200" style="border: 1px solid #F28B0C; padding: 2px;">Message</td>
									<td align="left" width="300" style="border: 1px solid #F28B0C; padding: 2px;">'.$message.'</td>
								</tr>
								<tr>
									<td align="left" width="200" style="border: 1px solid #F28B0C; padding: 2px;">Timezone</td>
									<td align="left" width="300" style="border: 1px solid #F28B0C; padding: 2px;">'.$dropdowntimezone.'</td>
								</tr>
								<tr>
									<td align="left" width="200" style="border: 1px solid #F28B0C; padding: 2px;">Date</td>
									<td align="left" width="300" style="border: 1px solid #F28B0C; padding: 2px;">'.$booking_date.'</td>
								</tr>
								<tr>
									<td align="left" width="200" style="border: 1px solid #F28B0C; padding: 2px;">Time</td>
									<td align="left" width="300" style="border: 1px solid #F28B0C; padding: 2px;">'.$booking_time.'</td>
								</tr>
							</table>

							<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Verdana; font-size:13px">
								<tr>
									<td>
										<p>&nbsp;</p>
										<p>Please make sure that the person is approached at the scheduled date & time.</p>
										<p>Regards,</p>
									</td>
								</tr>
								
							</table>';

							$content .= '<table>
							<tr>
									<td>
									<p style="font-size: 9px; color: gray;" > UTM: '.$utm_str.'</p>
									</td>
							</tr>
					               </table>';
					
							// If email has been process for sending, display a success message
							wp_mail($to, $subject, $content, $headers);
							
							
								$data['success']='<div class="row success">
								<div class="col-md-12">
									<div class="card">
										<img src="'.plugins_url('images/emojione_white-heavy-check-mark.png', __FILE__).'" class="img-fluid center-block">
										<div class="card-body">
											<h5 class="card-title text-center">Thank You!<br/> Your schedule has been booked successfully.</h5>
											<p class="text-center" style="font-size: 12px;margin-bottom:30px;">One of our Product Experts will contact you soon.</p>
											<a href="https://www.revechat.com/" class="text-center" target="_blank" >
											<button class="btn btn-primary okay-btn" onClick="backToPrevious()" style="font-size: 16px;">Visit Homepage</button>
											</a>
										</div>
									</div>
								</div>
								</div>';

								$ajaxResponse=array('status'=>true,'desc'=> $data['success']);
								echo json_encode($ajaxResponse);
								ob_end_flush();
								exit;
							

						}
						else
						{
							$to = 'developer.hasan5@gmail.com';
							$subject='New Demo Request from '.$name;
							$header_client = array(
								'Content-Type: text/html; charset=UTF-8',
								'From: developer.hasan5@gmail.com'
							);
							$content_client = 'Hi '.$name.',<br><br>
								Thank you for showing your interest in REVE Chat and booking a demo. This is a confirmation of your appointment. Please take a look at the details below:<br><br>

								Timezone: '.$dropdowntimezone.'<br>
								Date: '.$booking_date.'<br>
								Time: '.$booking_time.'<br><br>

								One of our Product Experts will get back to you soon.<br><br>
								REVE Chat is an omnichannel customer engagement platform that features chatbots, live chat, co-browsing, ticketing system, and more to provide sales and support assistance.<br><br>

								Feel free to keep in touch with us if you have any queries.<br><br>
								 
								Regards,<br>

								REVE Chat team';
							wp_mail($email, 'Thank you for booking REVE Chat demo!', $content_client, $header_client);
							$headers = array(
								'Content-Type: text/html; charset=UTF-8',
								'Reply-To: Ryan from REVE Chat <developer.hasan5@gmail.com>',
							    'From: developer.hasan5@gmail.com', 
							   
							    'CC: h.mehedi@revesoft.com'
							);
							
							$content='<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Verdana; font-size:13px">
								<tr>
									<td>
										<p>Hi,</p>
										<p>There is a request for REVE Chat Demo with the following details:</p>
										<p>Here are the visitor details:</p><br/>

									</td>
								</tr>
							</table>

							<table style="border-collapse: collapse; background-color: #ffd; width: 500px; font-size: 10pt;font-family:sans-serif; text-align: left;">
								<tr>
									<td align="left" width="200" style="border: 1px solid #F28B0C; padding: 2px;">Name </td>
									<td align="left" width="300" style="border: 1px solid #F28B0C; padding: 2px;">'.$name.'</td>
								</tr>
								<tr>
									<td align="left" width="200" style="border: 1px solid #F28B0C; padding: 2px;">Email</td>
									<td align="left" width="300" style="border: 1px solid #F28B0C; padding: 2px;">'.$email.'</td>
								</tr>
								<tr>
									<td align="left" width="200" style="border: 1px solid #F28B0C; padding: 2px;">Contact</td>
									<td align="left" width="300" style="border: 1px solid #F28B0C; padding: 2px;">'.$mobile_no.'</td>
								</tr>
								<tr>
									<td align="left" width="200" style="border: 1px solid #F28B0C; padding: 2px;">Company</td>
									<td align="left" width="300" style="border: 1px solid #F28B0C; padding: 2px;">'.$organization.'</td>
								</tr>
								<tr>
									<td align="left" width="200" style="border: 1px solid #F28B0C; padding: 2px;">Message</td>
									<td align="left" width="300" style="border: 1px solid #F28B0C; padding: 2px;">'.$message.'</td>
								</tr>
								<tr>
									<td align="left" width="200" style="border: 1px solid #F28B0C; padding: 2px;">Timezone</td>
									<td align="left" width="300" style="border: 1px solid #F28B0C; padding: 2px;">'.$dropdowntimezone.'</td>
								</tr>
								<tr>
									<td align="left" width="200" style="border: 1px solid #F28B0C; padding: 2px;">Date</td>
									<td align="left" width="300" style="border: 1px solid #F28B0C; padding: 2px;">'.$booking_date.'</td>
								</tr>
								<tr>
									<td align="left" width="200" style="border: 1px solid #F28B0C; padding: 2px;">Time</td>
									<td align="left" width="300" style="border: 1px solid #F28B0C; padding: 2px;">'.$booking_time.'</td>
								</tr>
							</table>

							<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Verdana; font-size:13px">
								<tr>
									<td>
										<p>&nbsp;</p>
										<p>Please make sure that the person is approached at the scheduled date & time.</p>
										<p>Regards,</p>
									</td>
								</tr>
								
							</table>';

							$content .= '<table>
							<tr>
									<td>
									<p style="font-size: 9px; color: gray;" > UTM: '.$utm_str.'</p>
									</td>
							</tr>
					               </table>';
					
							// If email has been process for sending, display a success message
							wp_mail($to, $subject, $content, $headers);
							
							
								$data['success']='<div class="row success">
								<div class="col-md-12">
									<div class="card">
										<img src="'.plugins_url('images/emojione_white-heavy-check-mark.png', __FILE__).'" class="img-fluid center-block">
										<div class="card-body">
											<h5 class="card-title text-center">Thank You!<br/> Your schedule has been booked successfully.</h5>
											<p class="text-center" style="font-size: 12px;margin-bottom:30px;">One of our Product Experts will contact you soon.</p>
											<a href="https://www.revechat.com/" class="text-center" target="_blank" >
											<button class="btn btn-primary okay-btn" onClick="backToPrevious()" style="font-size: 16px;">Visit Homepage</button>
											</a>
										</div>
									</div>
								</div>
								</div>';

								$ajaxResponse=array('status'=>true,'desc'=> $data['success']);
								echo json_encode($ajaxResponse);
								ob_end_flush();
								exit;
							

						}
					}
				}
				
				
			}
		}


		
		public function cf_shortcode() {
			ob_start();
			$this->create_schedule_form();
			return ob_get_clean();
		}


	}
}
$schedule = new schedule_visit();

add_shortcode('schedule_visit', array($schedule, 'cf_shortcode'));

?>