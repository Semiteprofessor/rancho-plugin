<?php
/*
Plugin Name: TopShip Africa

Description: A WordPress plugin to interface with the Topship.Africa API and create a shipping method for your store.

Version: 1.0.0

Author: Topship Africa

Author URI: https://topship.africa/

Text Domain: topship-plugin
Requires PHP:      7.2

*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//include files from the plugin folder

define('BASE_URL','https://api-topship.com/api/');


function blavitch($data,$token,$url){
    $authorization = "Authorization: Bearer ".$token;
    $payload = $data;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);

	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        $authorization,
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload))
    );
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result,true);
}


function sendRequest($data,$url){

	$payload = $data;
   $ch = curl_init($url);
   
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   
   curl_setopt($ch, CURLINFO_HEADER_OUT, true);
   
   curl_setopt($ch, CURLOPT_POST, true);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
   
   //curl_setopt($ch, CURLOPT_POST_CONTROLS, $payload);
	
   curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	   'Content-Type: application/json',
	   'Content-Length: ' . strlen($payload))
   );
	
   $result = curl_exec($ch);
   $res = json_decode($result,true);
   curl_close($ch);
   //error_log($result);
   
   return $res;
   
   
   }


add_action('admin_enqueue_scripts', 'my_topship_admin_style');

function my_topship_admin_style()
{
	wp_register_style('admin-style', plugins_url('/assets/app.css', __FILE__));
	wp_enqueue_style('admin-style');


	wp_register_style('Selectize', 'https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.6/css/selectize.bootstrap4.css');
	wp_enqueue_style('Selectize');

	wp_register_script('Jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js');
//add jquery 
	wp_enqueue_script('Jquery');


	wp_register_script('Selectizejs', 'https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/js/standalone/selectize.min.js');
	
	wp_enqueue_script('Selectizejs');
	wp_register_script('bootstrapjs', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js');
	wp_enqueue_script('bootstrapjs');

	

}





$url = BASE_URL;


function database_creation()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'topship_business_details';
	$drop = "DROP TABLE IF EXISTS $table_name";
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			fullname varchar(150) NOT NULL,
			business_name varchar(255) NOT NULL,
			topship_email varchar(72) NOT NULL,
			topship_phone varchar(16) NOT NULL,
			topship_address_line1 varchar(255) NOT NULL,
			topship_address_line2 varchar(255) NOT NULL,
			postal_code varchar(10) NOT NULL,
			country_code varchar(6) NOT NULL,
			api_call_data text NOT NULL,
			
			access_token text NOT NULL,
			state varchar(100) NOT NULL,
			password varchar(150) NOT NULL,
			city varchar(100) NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

	$jwt_table = $wpdb->prefix . 'topship_jwt_tokens';
	$jwt_sql = "CREATE TABLE $jwt_table (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			token text NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

	$shipment_table = $wpdb->prefix . 'topship_shipment_details';
	$shipment_sql = "CREATE TABLE $shipment_table (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			shipment_id varchar(100) NOT NULL,
			call_details text NOT NULL,
			payment_details text NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";


//Create table to store shipping price
	$shipping_table = $wpdb->prefix . 'topship_shipping_price';
	$shipping_price_sql ="CREATE TABLE $shipping_table (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		order_id varchar(100) NOT NULL,
		amount double NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($drop);
	dbDelta($sql);
	dbDelta($jwt_sql);
	dbDelta($shipment_sql);
	dbDelta($shipping_price_sql);

}


function topship_admin_menu()
{
	add_menu_page('Topship', 'Register account', 'manage_options', 'topship-plugin', 'topship_admin_page', 'dashicons-admin-generic', 6);
	
}

//Create table when plugin is activated
register_activation_hook(__FILE__, 'database_creation');


//include whitelist from pages folder

function whitelisting()
{
?>

<div class="container-fluid bg-light pb-5">


    <div class="row mb-5">
        <div class="col-md-6 mx-auto mt-3 mb-5">





            <div class="shadow p-3 mb-5 bg-white rounded mt-5">
                <h3 class="text-center mt-3 mb-2">Apply to whitelist your domain on Topship</h3>
                <form action="#" method="post" class="p-4">

                    <div class="alert alert-warning mb-4 text-center">
                        Kindly apply for domain whitelisting before using the plugin.
                    </div>

                    <?php
					






                        ?>
                    <div class="form-row mt-5">
                        <div class="form-group col-md-6">
                            <label class="mb-0">Full name</label>

                            <input class="form-control text-capitalize" required autocomplete="name" type="text"
                                name="fullname" id="fullname" placeholder="Arya Stark">

                        </div>

                        <div class="form-group col-md-6">
                            <label class="mb-0">Email address</label>

                            <input required autocomplete="email" class="form-control text-lowercase" name="email"
                                id="email" type="email" placeholder="ceo@example.com">

                        </div>

                    </div>




                    <div class="form-group">
                        <label class="mb-0">Domain to be whitelisted</label>

                        <input class="form-control" value="www.<?= $_SERVER['SERVER_NAME'] ?>" name="domain" id="domain"
                            type="text" placeholder="www.example.com">

                    </div>


                    <div class="form-group">
                        <label class="mb-0">Name of business</label>

                        <input class="form-control" name="business" id="business" required type="text"
                            placeholder="Iron Bank of Bravos">

                    </div>


                    <p style="text-align:center;display:none" id="ikf">
                        <img style="width: 24px;" src="<?php echo plugins_url('assets/spin.gif', __FILE__) ?>">
                    </p>
                    <?php
						  add_action('phpmailer_init', 'set_phpmailer_details');
						  function set_phpmailer_details($phpmailer)
						  {
							  $phpmailer->isSMTP();
							  $phpmailer->Host = 'smtp.eu.mailgun.org';
							  $phpmailer->SMTPAuth = true;
							  $phpmailer->Port = 465; //25 or 465
							  $phpmailer->Username = 'postmaster@ng.topship.africa';
							  $phpmailer->Password = 'F110d28459bcb5d720c060cde6423660-77985560-3f821174';
							  $phpmailer->SMTPSecure = 'ssl'; //ssl or tls
						  }


                        if (isset($_POST['sendr'])) {



							
							include_once( plugin_dir_path( __FILE__ ) . '/assets/src/Exception.php');
							include_once(plugin_dir_path( __FILE__ ) .  'assets/src/PHPMailer.php');
							include_once(plugin_dir_path( __FILE__ ) .  'assets/src/SMTP.php');

								
						//Create an instance; passing `true` enables exceptions
						$mail = new PHPMailer(true);
						$mail->SMTPOptions = array(
							'ssl' => array(
							'verify_peer' => false,
							'verify_peer_name' => false,
							'allow_self_signed' => true
							)
							);
						
						try {
							//Server settings
							//$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
						
							$mail->isSMTP();                                            //Send using SMTP
							$mail->Host       = 'smtp.eu.mailgun.org';                     //Set the SMTP server to send through
							$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
							$mail->Username   = 'postmaster@ng.topship.africa';                     //SMTP username
							$mail->Password   = '1a24dc4b7492a109a04a2b6354a65427-680bcd74-f8292d0b';                               //SMTP password
							//$mail->SMTPSecure = 'STARTTLS';           //Enable implicit TLS encryption
							$mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
						
							//Recipients 
							$mail->setFrom('postmaster@ng.topship.africa', $_SERVER['SERVER_NAME']);
							$mail->addAddress('tech@topship.africa', 'Topship');     //Add a recipient
							//$mail->addAddress('ellen@example.com');               //Name is optional
							$mail->addReplyTo('tech@topship.africa', 'Topship');

							$message = "Full Name: " . $_POST['fullname'] . "<br>"
							. "Business Name: " . $_POST['business'] . "<br>"
							. "Email: " . $_POST['email'] . "<br>"
							. "Domain: " . $_POST['domain'] . "<br>";
						
							//Content
							$mail->isHTML(true);                                  //Set email format to HTML
							$mail->Subject = 'Topship Whitelisting Request '. $_SERVER['SERVER_NAME'];
							$mail->Body    = $message;
							$mail->AltBody = 'Topship Whitelisting Request';
						
							$mail->send();
							
                            echo "<p class='alert alert-success text-center'>Your request for the domain whitelisting, has been sent successfully. </p>";
                       
						} catch (Exception $e) {
							//echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
						}



 }
                        ?>


                    <div class="text-center">
                        <button id="sendWthitelist" onclick="showLoader()" type="submit" value="1" name="sendr"
                            class="btn btn-primary btn-block btn-lg">Submit</button>
                    </div>



                </form>

                <script>
                function showLoader() {
                    document.getElementById('ikf').style.display = "block";
                }
                </script>

            </div>
        </div>


    </div>
</div>
</div>
<?php
}







//how it works page

function how_it_works()
{
?>
<div class="container-fluid bg-light mt-5">
    <div class="container mt-5">
        <h3 class="text-center"> How the Topship WordPress plugin works </h3>

        <div class="shadow p-3 mb-5 bg-white rounded">
            <div class="list-group p-4">
                <div class="list-group-item">
                    <h6>Step 1</h6>
                    <p>Install and activate the Topship Plugin</p>
                </div>
                <div class="list-group-item">
                    <h6>Step 2</h6>
                    <p>Apply to whitelist your domain
                    </p>
                </div>


                <div class="list-group-item">
                    <h6>Step 3</h6>
                    <p> Register your business. Click on ‘Register Account’ on the left menu</p>
                </div>




                <div class="list-group-item">
                    <h6>Step 4</h6>
                    <p>Topship will automatically start collecting and processing your shipping orders. Go to your
                        Topship dashboard to fund your wallet and track shipments: <a href="https://ship.topship.africa"
                            target="_blank">ship.topship.africa</a> </p>
                </div>
            </div>

            <div class="alert alert-warning p-4">
                Note: If you have any issues, questions or feedback, send a mail to
                <a href="mailto:tech@topship.africa">tech@topship.africa </a>

            </div>




        </div>


    </div>
</div>

<?php
}





					//save business details when form is submitted


					function save_business_details()
					{
						global $wpdb;
						$table_name = $wpdb->prefix . 'topship_business_details';

						// truncate table first
						$wpdb->query("TRUNCATE TABLE $table_name");



						// Now insert the data
						$fullname = $_POST['fullname'];
						$business_name = $_POST['business_name'];
						$topship_email = $_POST['topship_email'];
						$topship_phone = $_POST['topship_phone'];
						$topship_address_line1 = $_POST['topship_address_line1'];
						$topship_address_line2 = $_POST['topship_address_line2'];
						$postal_code = $_POST['postal_code'];
						$country_code = $_POST['country_code'];
						$state = $_POST['state'];
						$city = $_POST['city'];
						$password = $_POST['password'];
						$wpdb->insert(
							$table_name,
							array(
								'fullname' => $fullname,
								'business_name' => $business_name,
								'topship_email' => $topship_email,
								'topship_phone' => $topship_phone,
								'topship_address_line1' => $topship_address_line1,
								'topship_address_line2' => $topship_address_line2,
								'postal_code' => $postal_code,
								'country_code' => $country_code,
								'state' => $state,
								'city' => $city,
								'password' => $password
							),
							array(
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s'
							)
						);
						echo '
<div class="modal tmodal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tophip</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="alert alert-success text-center">Your Topship account has been created successfully.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>';








					}



					//fetch the saved business details from database
					function get_business_details()
					{
						global $wpdb;
						$table_name = $wpdb->prefix . 'topship_business_details';
						@$business_details = $wpdb->get_results("SELECT * FROM $table_name 	ORDER BY id ASC LIMIT 1")[0];
						return $business_details;
					}


					function my_admin_menu()
					{
						add_menu_page(
							__('Topship', 'topship-plugin'),
							__('Topship - Register Account', 'topship-plugin'),
							'manage_options',
							'topship-plugin',
							'my_admin_page_contents',
							'dashicons-schedule',
							3
						);
						add_submenu_page("topship-plugin", "How it works", "How it works", "manage_options", "how-topship", "how_it_works");
						add_submenu_page("topship-plugin", "Whitelist domain", "Whitelist Domain", "manage_options", "whitelist-list", "whitelisting");
					}

					add_action('admin_menu', 'my_admin_menu');



					function get_states()
					{
						$url = BASE_URL.'get-states?countryCode=NG';
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						$result = curl_exec($ch);
						curl_close($ch);
						$data = json_decode($result, true);
						return $data;
					}


					function my_admin_page_contents()
					{
						$bz = get_business_details();
						?>


<script>
$(function() {
    $('#state_select').selectize();

});
</script>
<div class="container-fluid bg-light pb-5">
    <div class="row pb-5">
        <div class="col-md-6 mx-auto pb-5">
            <p class="text-center">
                <img src="<?php echo plugins_url('/assets/images/top3.png', __FILE__) ?>" class="topship-logo">
            </p>
            <div class="shadow p-3 mb-5 bg-white rounded">

                <form method="post" class="p-4">
                    <h3 class="mb-3 text-center"><?php esc_html_e('Register your business', 'topship-plugin'); ?></h3>
                    <p class="mb-4 text-center alert alert-warning">
                        Enter your details to create a Topship plugin account and automate shipping on your website.
                    </p>


                    <?php 
	if (isset($_POST['save'])) {

		$formdata = array(
			'registrationInput' =>
			array(
				'email' => $_POST['topship_email'],
				'password' => $_POST['password'],
				'phoneNumber' => $_POST['topship_phone'],
				'referrerCode' => '',
				'fullName' => $_POST['fullname'],
				'source' => 'Wordpress',
				'accountType' => 'Business',
			),
		);
		$rd = json_encode($formdata);
		$url = BASE_URL;
		$saget = 	sendRequest($rd, $url . 'signup');
//Open a log file in plugin directory
// $myFile = plugin_dir_path(__FILE__) . "log.txt";
// $fh = fopen($myFile, 'a') or die("can't open file");


// fwrite($fh, "Topship ID: " . print_r($saget, true) . "\n");
// fclose($fh);

		if (isset($saget)) {
			save_business_details();
		}
	}

?>

                    <div class="form-group">
                        <label for="" class="mb-0">Select your country</label>
                        <select data-live-search="true" required class="form-control" name="country_code" id="country">
                            <option value="" selected>--</option>

                            <option value="NG">Nigeria</option>

                        </select>
                    </div>


                    <div class="form-row">


                        <div class="form-group col-md-6">
                            <label class="mb-0">Full Name <span class="required has-text-danger">*</span></label>

                            <input class="form-control" type="text" id="fullname" name="fullname"
                                value="<?php echo @$bz->fullname; ?>" required>


                        </div>



                        <div class="form-group col-md-6">
                            <label for="" class="mb-0">Phone number <span
                                    class="required has-text-danger">*</span></label>

                            <input class="form-control" value="<?php if (isset($bz->topship_phone)) {
																						echo $bz->topship_phone;
																					}    ?>" type="text" name="topship_phone" id="topship_phone" required
                                placeholder="Enter phone number">



                        </div>

                    </div>


                    <div class="form-group">
                        <label class="mb-0">Business Name <span class="required has-text-danger">*</span></label>

                        <input required class="form-control" value="<?php if (isset($bz->business_name)) {
																				echo $bz->business_name;
																			}    ?>" type="text" required name="business_name" id="business_name"
                            placeholder="Name of business">


                    </div>





                    <div class="form-row">


                        <div class="form-group col-md-6">
                            <label for="" class="mb-0">Address line1 <span
                                    class="required has-text-danger">*</span></label>

                            <input required class="form-control" value="<?php if (isset($bz->topship_address_line1)) {
																						echo $bz->topship_address_line1;
																					}    ?>" type="text" name="topship_address_line1" id="topship_address_line1" required
                                placeholder="Enter address line1">


                        </div>
                        <div class="form-group col-md-6">
                            <label for="" class="mb-0">Address line 2</label>

                            <input class="form-control" value="<?php if (isset($bz->topship_address_line2)) {
																						echo $bz->topship_address_line2;
																					}    ?>" type="text" name="topship_address_line2" id="topship_address_line2"
                                placeholder="Enter address line2">


                        </div>

                    </div>




                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="" class="mb-0">State <span class="required has-text-danger">*</span></label>
                            <select style="width:100%" name="state" class="form-control w-100" id="state_selectr"
                                required>
                                <option value="" selected>--</option>

                            </select>


                        </div>
                        <div class="form-group col-md-6">
                            <label for="" class="mb-0">City <span class="required has-text-danger">*</span></label>

                            <select required style="width:100%" class="form-control w-100" required name="city"
                                id="city_selectr">
                            </select>


                        </div>
                    </div>





                    <div class="form-group">
                        <label for="" class="mb-0">Postal code <span class="required has-text-danger">*</span></label>

                        <input required class="form-control" type="text" value="<?php if (isset($bz->postal_code)) {
																							echo $bz->postal_code;
																						}    ?>" type="text" name="postal_code" id="postal_code" placeholder="Enter postal code">

                    </div>






                    <div class="form-row">

                        <div class="form-group col-md-6">
                            <label class="mb-0">Email address <span class="required has-text-danger">*</span></label>

                            <input required class="form-control" value="<?php if (isset($bz->topship_email)) {
																						echo $bz->topship_email;
																					}    ?>" type="email" name="topship_email" id="topship_email" required placeholder="Enter email">

                        </div>

                        <div class="form-group col-md-6">


                            <label class="mb-0">Password <span class="required has-text-danger">*</span></label>
                            <input class="form-control" required type="password" name="password" class="form-group-long"
                                id="password">


                        </div>

                    </div>
                    <p class="text-center" style="text-align:center;display:none;" id="idf">
                        <img style="width:24px" src="<?= plugins_url('/assets/spin.gif', __FILE__) ?>" alt="loader">
                    </p>





                    <div class="buttons is-centered mt-4">
                        <button class="btn btn-primary btn-lg btn-block" type="submit" onclick="showDiv()" value="1"
                            name="save">Save details</button>
                    </div>


                </form>


            </div>
        </div>
    </div>
</div>

<script>
function showDiv() {
    document.getElementById('idf').style.display = "block";
}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"
    integrity="sha512-DUC8yqWf7ez3JD1jszxCWSVB0DMP78eOyBpMa5aJki1bIRARykviOuImIczkxlj1KhVSyS16w2FSQetkD4UU2w=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/js/standalone/selectize.min.js"></script>
<script>
$(document).ready(function() {
    $('.tmodal').modal('show');


    $("#country").selectize({
        create: false,
        sortField: 'text'
    });
    $("#country").change(function() {
        //Get the states of the selected country
        var country = $(this).val();
        $.ajax({
            url: "<?= BASE_URL ?>get-states?countryCode=" + country,
            type: "GET",
            success: function(res) {
                console.log(res);
                $("#state_selectr").html('<option selected></option>');
                $.each(res, function(key, value) {


                    $('#state_selectr').append('<option value="' + value.name +
                        '">' + value.name + '</option>');
                });
                $('#state_selectr').selectize();
            }
        });


    });
    $('#state_selectr').change(function() {
        var state = $(this).val();

        if (state == 'LOS') {
            $.ajax({

                url: 'https://cors.eu.org/cj-gaq.dhl.com/api/postalLocation?countryCode=NG',
                type: 'GET',
                success: function(res) {
                    var json = Object.assign({}, res.postalLocationList);
                    $('#city_selectr').html('<option selected></option>');
                    $.each(json, function(key, value) {

                        $('#city_selectr').append('<option value="' + value.city +
                            '">' + value.city + '</option>');
                    });
                    $('#city_selectr').selectize();
                }
            });
        } else {
            $.ajax({

                url: '<?= BASE_URL ?>get-cities?countryCode=' + $('#country').val(),
                type: 'GET',
                success: function(res) {
                    $("#city_selectr").html('<option selected></option>');
                    $.each(res, function(key, value) {

                        $('#city_selectr').append('<option value="' + value
                            .cityName + '">' + value.cityName + '</option>');
                    });
                    $('#city_selectr').selectize();
                }
            });
        }





    });
});
</script>


<?php
					}

//Get shipping amount from topship










					add_action('woocommerce_payment_complete', 'mysite_woocommerce_order_status_completed', 99, 1);


					function mysite_woocommerce_order_status_completed($order_id)
					{
						//login to get accessToken
						global $wpdb;
						$bz_login_details = get_business_details();


						$loginusername = $bz_login_details->topship_email;
						$loginpassword = $bz_login_details->password;
						$logindata = '{
    "loginInput": {
        "email": "' . $loginusername . '",
        "password": "' . $loginpassword . '"
    }
}';
						$loginurl = BASE_URL. 'login';
						$login = sendRequest($logindata, $loginurl);




						$order = wc_get_order($order_id);
						$biz_details = get_business_details();
						error_log(print_r($order->get_billing_address_1()));
						error_log(print_r($order));
						$items = '';

						foreach ($order->get_items() as $item) {

							$items .= $item['name'] . ',';
						}
						$purchased_items = rtrim($items, ',');


						$total_quantity = 0;
						foreach ($order->get_items() as $item_id => $item) {
							$quantity = $item->get_quantity();
							$total_quantity += $quantity;
						}



						//get shipping price
						function getPrice($durl)
						{
							//$files = fopen(plugin_dir_path(__FILE__) . 'logs.txt', 'a');
							$url = $durl;

							$curl = curl_init($url);
							curl_setopt($curl, CURLOPT_URL, $url);
							curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

							//for debug only!
							curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
							curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

							$resp = curl_exec($curl);
							curl_close($curl);
							return json_decode($resp, true);
						}

						$recieverCity = $order->get_shipping_city();
						$counryCode =  $biz_details->country_code;

						$senderCity = $biz_details->city;
						$recieverCountryCode = $order->get_shipping_country();
						$weight = $total_quantity;

						//write to file
						$files = fopen(plugin_dir_path(__FILE__) . 'logs.txt', 'a');
						fwrite($files, $recieverCity . ' ' . $counryCode . ' ' . $senderCity . ' ' . $recieverCountryCode . ' ' . $weight . ' ');



						$durl =BASE_URL. 'get-shipment-rate?shipmentDetail={%20%22senderDetails%22:%20{%20%22cityName%22:%20%22' . $senderCity . '%22,%20%22countryCode%22:%20%22' . $counryCode . '%22%20},%20%22receiverDetails%22:%20{%20%22cityName%22:%20%22' . $recieverCity . '%22,%20%22countryCode%22:%20%22' . $recieverCountryCode . '%22%20},%20%22totalWeight%22:%20' . $weight . '%20}';


						$rest = getPrice($durl);
					
						if (is_array($rest)) {
							$shipping_price = ($rest[0]['cost']/100);
							
							global $wpdb;
						$table_name = $wpdb->prefix . 'topship_shipping_price';
						$wpdb->insert(
							$table_name,
							array(
								'order_id' => $order_id,
								'amount' => doubleval($shipping_price)
							)
						);



						} else {
							$shipping_price = 1000;
						}

						//create array of object from order_id
						$order_list = array();
						$order = wc_get_order($order_id);
						foreach($order->get_items() as $item){
							// $product        = $item->get_product();
							$order_list[] = array(
								'category' => wp_get_post_terms( $item->get_product_id(), 'product_cat', array('fields' => 'names') )[0],
								'description' => $item->get_name(),
								'weight' => 1,
								'quantity' => $item->get_quantity(),
								'value' =>  $item->get_product()->get_price() * 100,
							);
						}
						
						
						$vlad = array(
							'shipment' =>
							array(
								0 =>
								array(
									'items' => $order_list,
									'itemCollectionMode' => 'DropOff',
									'pricingTier' => 'Standard',
									'insuranceType' => 'None',
									'insuranceCharge' => 0,
									'discount' => 0,
									'shipmentRoute' => 'Domestic',
									'shipmentCharge' => (int)$_COOKIE['shippp'],
									'pickupCharge' => 50000,
									'senderDetail' =>
									array(
										'name' => $biz_details->fullname,
										'email' => $biz_details->topship_email,
										'phoneNumber' => $biz_details->topship_phone,
										'addressLine1' => $biz_details->topship_address_line1,
										'addressLine2' => $biz_details->topship_address_line2,
										'addressLine3' => $biz_details->topship_address_line1,
										'country' => $biz_details->country_code,
										'state' => $biz_details->state,
										'city' => $biz_details->city,
										'countryCode' => $biz_details->country_code,
										'postalCode' => $biz_details->postal_code,
									),
									'receiverDetail' =>
									array(
										'name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
										'email' => $order->get_billing_email(),
										'phoneNumber' => $order->get_billing_phone(),
										'addressLine1' => $order->get_billing_address_1(),
										'addressLine2' => $order->get_billing_address_2(),
										'addressLine3' => $order->get_billing_address_1(),
										'country' => $order->get_billing_country(),
										'state' => $order->get_billing_state(),
										'city' => $order->get_billing_city(),
										'countryCode' => $order->get_billing_country(),
										'postalCode' => $order->get_billing_postcode(),
									),
								),
							),
						);


						$bas = json_encode($vlad);


						$accessToken = $login['accessToken'];
						//error_log($accessToken);
						$res = blavitch($bas, $accessToken, BASE_URL. 'save-shipment');



						$shipment_id = $res[0]['id'];
						

						//make payment
						$paymentDetails = '{
    "detail": {
      "shipmentId": "' . $shipment_id . '"
    }
  }';
						$payment = blavitch($paymentDetails, $accessToken, BASE_URL. 'pay-from-wallet');

						error_log($payment['message']);

						//error_log(print_r($payment,true));
						if (isset($payment['message'])) {

							$md = $payment['message'] . '. Kindly login to your Topship account to pay for your shipment. You can call us on 09080 777 728 for any assistance.';
							
						 wp_mail($biz_details->topship_email, 'Yout Topship Wallet balance is low', $md, '', '');
							
						}
					}
				




add_action('wp_footer', 'billing_country_update_checkout', 50);
function billing_country_update_checkout() {
    if ( ! is_checkout() ) return;

	$biz = get_business_details();
    ?>
<noscript>
    <meta http-equiv="refresh" content="0; url=https://www.blavitch.com/blog" />
</noscript>
<script type="text/javascript">
jQuery(function($) {
    $('.checkout woocommerce-checkout').trigger("reset");
    $("#billing_email").attr('autocomplete', 'off');
    $("#billing_email").attr('value', 'Enter your email address');
    $("#billing_email").prop("autocomplete", "off");

    $("#billing_email").focusout(function() {
        jQuery('body').trigger('update_checkout');
    });

    $('#billing_city,#shipping_city,#billing_country').focusout(function() {

        var t = {
            updateTimer: !1,
            dirtyInput: !1,
            reset_update_checkout_timer: function() {
                clearTimeout(t.updateTimer)
            },
            trigger_update_checkout: function() {
                t.reset_update_checkout_timer(), t.dirtyInput = !1,
                    $(document.body).trigger("update_checkout")
            }
        };
        $(document.body).trigger('update_checkout');

        var country = $('#billing_country').val();
        var state = $('#billing_state').val();
        var city = $('#billing_city').val();


        jQuery.ajax({
            type: "GET",
            dataType: "json",
            url: '<?= BASE_URL ?>get-shipment-rate?shipmentDetail={"senderDetails": {"cityName": "<?= $biz->city ?>","countryCode": "<?= $biz->country_code ?>"},"receiverDetails": {"cityName":"' +
                $('#billing_city').val() + '","countryCode":"' + $('#billing_country').val() +
                '"},"totalWeight": 1}',

            success: function(msg) {

                var plan = msg.pop();
                //console.log(plan.cost);
                var kobo_cost = plan.cost / 100;
                var cost = kobo_cost + 500;





                var ajaxurl = "' . admin_url('admin-ajax.php') . '";
                var prevPostId;
                $.post("<?= admin_url('admin-ajax.php') ?> ", {
                    action: "get_prev",
                    id: cost
                }, function(prevPostId) {
                    //console.log(prevPostId)
                    jQuery('body').trigger('update_checkout');
                });





            }
        });

    });


});
</script>
<?php
}



function get_prev_ajax_handler() {


	 setcookie('shipping_fee_tips', $_POST['id'], time() + 900, "/");
	 $sok = ($_POST['id'] - 500) * 100;
	 setcookie('shippp', $sok , time() + 900, "/");



  }
  
  add_action('wp_ajax_get_prev', 'get_prev_ajax_handler');
  add_action( 'wp_ajax_nopriv_get_prev', 'get_prev_ajax_handler' );




	add_action( 'woocommerce_cart_calculate_fees', 'woo_add_cart_fee' );
	function woo_add_cart_fee( ){
	 global $woocommerce;
	 //get woocommerce order_id
	 $order_id = $woocommerce->session->get( 'order_awaiting_payment' );
   
   
  
	
if(!empty($_COOKIE['shipping_fee_tips'])){
	$woocommerce->cart->add_fee( 'Shipping fee', $_COOKIE['shipping_fee_tips'], true, '' );

	
}



   }



// Print metabox form
function topship_print_shipment_metabox ($shipment_email_sent, $order_id) {
	$nonce = wp_create_nonce( 'topship_nonce_add' );

	// Get providers list from json file
	if( file_exists( plugin_dir_path( __FILE__ ) . '/courier_list.json' ) )
		$providers_list = json_decode( file_get_contents( plugin_dir_path( __FILE__ ) . '/courier_list.json' ), true );

	?>
<?php if($providers_list): ?>
<p><select name="providers_list" class="widefat">
        <option value="none">Select the Provider</option>
        <?php foreach( $providers_list as $i => $v ): ?>
        <option value="<?php echo $v['link']; ?>"><?php echo $v['name']; ?></option>
        <?php endforeach; ?>
        <option value="custom">Custom Provider</option>
    </select></p>
<?php endif; ?>
<p class="customShow" <?php if($providers_list): ?>style="visibility:hidden;height:0;margin:0;float:left"
    <?php endif; ?>>
    <label for="tracking_provider"><?php _e( "Provider Name <sup>*</sup>:", 'wc-topship-shipment-tracking' ); ?></label>
    <br />
    <input class="widefat" type="text" name="tracking_provider" id="tracking_provider" size="30" />
</p>
<p>
    <label for="tracking_number"><?php _e( "Tracking Number <sup>*</sup>:", 'wc-topship-shipment-tracking' ); ?></label>
    <br />
    <input class="widefat" type="text" name="tracking_number" id="tracking_number" size="30" />
</p>
<p class="customShow" <?php if($providers_list): ?>style="visibility:hidden;height:0;margin:0;float:left"
    <?php endif; ?>>
    <label for="tracking_link"><?php _e( "Tracking Link:", 'wc-topship-shipment-tracking' ); ?></label>
    <br />
    <input class="widefat" type="text" name="tracking_link" id="tracking_link"
        placeholder="https://xyz.com/?trackingNum=%s" size="30" />
    <span class="components-form-token-field__help">Use %s for tracking number's place in link!</span>
</p>
<p>
    <label for="date_shipped"><?php _e( "Date Shipped:", 'wc-topship-shipment-tracking' ); ?></label>
    <br />
    <input class="widefat" type="date" name="date_shipped" id="date_shipped" size="30" />
</p>
<button type="submit" class="button-primary topship_add_meta" name="add" value="Add"
    data-href="<?php echo admin_url('admin-ajax.php'); ?>" data-nonce="<?php echo $nonce; ?>"
    data-order_id="<?php echo $order_id; ?>"><?php _e('Add New Shipment Tracking')?></button>
<script>
jQuery(document).ready(function() {

    // Providers list change event
    jQuery('.topship-sst-content select[name="providers_list"]').change(function() {
        if (jQuery(this).val() == 'custom') {
            jQuery('.topship-sst-content input[name="tracking_provider"]').val('');
            jQuery('.topship-sst-content input[name="tracking_link"]').val('');

            jQuery('.customShow').css({
                'visibility': 'visible',
                'height': 'auto',
                'margin': '1rem 0',
                'float': 'none'
            })

        } else {
            jQuery('.customShow').css({
                'visibility': 'hidden',
                'height': 0,
                'margin': 0,
                'float': 'left'
            })

            jQuery('.topship-sst-content input[name="tracking_provider"]').val(jQuery(this).find(
                    ':selected')
                .text());
            jQuery('.topship-sst-content input[name="tracking_link"]').val(jQuery(this).val());
        }
    });

    // Delete shipment tracking event
    jQuery(".topship-sst-content").on('click', '.topship_delete_meta', function(e) {
        e.preventDefault();

        let nonce = jQuery('.topship-sst-content .order_notes').attr("data-nonce");
        let order_id = jQuery('.topship-sst-content .order_notes').attr("data-order_id");
        let url = jQuery('.topship-sst-content .order_notes').attr("data-admin-ajax");

        let metaElement = jQuery(this).parent().parent();
        let tracking_id = jQuery(this).attr("data-tracking_id")

        metaElement.css('position', 'relative').append(
            '<div class="blockUI blockOverlay" style="z-index:1000; border:none; margin:0px; padding:0px; width:100%; height:100%; top:0px; left:0px; background:#fff; opacity:0.6; cursor:wait; position:absolute"></div>'
        );
        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: url,
            data: {
                action: "topship_simple_shipment_tracking_delete",
                nonce: nonce,
                order_id: order_id,
                tracking_id: tracking_id
            },
            success: function(response) {
                if (response == "1")
                    metaElement.hide('fast', function() {
                        this.remove()
                    });
            },
            complete: function() {
                metaElement.find('.blockUI').remove();
            }
        })
    })

    // Add shipment tracking event
    jQuery(".topship_add_meta").click(function(e) {
        e.preventDefault();

        let url = jQuery(this).attr("data-href");
        let metaElement = jQuery(this).parent().parent();
        let nonce = jQuery(this).attr("data-nonce")
        let order_id = jQuery(this).attr("data-order_id")

        // Loading overlay element for ajax request
        metaElement.css('position', 'relative').append(
            '<div class="blockUI blockOverlay" style="z-index:1000; border:none; margin:0px; padding:0px; width:100%; height:100%; top:0px; left:0px; background:#fff; opacity:0.6; cursor:wait; position:absolute"></div>'
        );

        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: url,
            data: {
                action: "topship_simple_shipment_tracking_add",
                nonce: nonce,
                order_id: order_id,
                tracking_provider: jQuery(
                        '.topship-sst-content input[name="tracking_provider"]')
                    .val(),
                tracking_number: jQuery('.topship-sst-content input[name="tracking_number"]')
                    .val(),
                tracking_link: jQuery('.topship-sst-content input[name="tracking_link"]').val(),
                date_shipped: jQuery('.topship-sst-content input[name="date_shipped"]').val()
            },
            success: function(response) {
                if (response && response.id != '') {
                    // Remove message if exists
                    if (jQuery('.topship-sst-content .order_notes li').length == 0)
                        jQuery('.topship-sst-content .order_notes *').remove();

                    // Add new shipment tracking
                    jQuery('.topship-sst-content .order_notes').append(
                        '<li class="note"><div class="note_content">' +
                        '<p><b>' + response.tracking_provider + '</b> ' + response
                        .tracking_number_linked + '</p></div>' +
                        '<p class="meta">Shipped on <time class="exact-date">' +
                        response.date_shipped_formatted + '</time>' +
                        ' <a href="#" data-tracking_id="' + response.id +
                        '" class="topship_delete_meta" role="button">Delete</a></p></li>'
                    );

                    // Clear inputs
                    jQuery('.topship-sst-content input[name="tracking_provider"]').val('');
                    jQuery('.topship-sst-content input[name="tracking_number"]').val('');
                    jQuery('.topship-sst-content input[name="tracking_link"]').val('');
                    jQuery('.topship-sst-content input[name="date_shipped"]').val('');
                }
            },
            complete: function() {
                metaElement.find('.blockUI').remove();
            }
        })
    })

})
</script>
<?php
}