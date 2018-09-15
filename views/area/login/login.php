<!DOCTYPE html>
<html lang="en-us">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="google-signin-scope" content="profile email">
	<meta name="google-signin-client_id" content="1073006937039-ghhojk0dhllg4p4a89q6j0c5b398gfi5.apps.googleusercontent.com">
	<script src="https://apis.google.com/js/platform.js" async defer></script>
	<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/logo-fav.png">
	<title><?php echo lang('loginsystem')?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/lib/perfect-scrollbar/css/perfect-scrollbar.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/lib/material-design-icons/css/material-design-iconic-font.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/lib/jquery.gritter/css/jquery.gritter.css"/>
	<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ciuis.css" type="text/css"/>
</head>
<style>
	.loginsol {
		position: fixed;
		height: 100%;
		background-color: #929292;
		background-image: url(<?php echo base_url();
		?>/assets/img/login.jpg);
		background-blend-mode: multiply;
		background-position: center center;
		background-size: cover !important;
	}

	.lg-content {
		margin-top: 30%;
		text-align: center;
		padding: 0 50px;
		color: azure;
	}

	.lg-content p {
		color: azure;
	}
</style>
<body class="ciuis-body-splash-screen">
	<div class="ciuis-body-wrapper ciuis-body-login">
		<div class="ciuis-body-content">
			<div class="col-md-4 loginsol">
				<div class="lg-content">
					<h2>Don't have an account yet?</h2>
					</br>
					<a href="/my/area/signup" class="btn btn-warning p-l-20 p-r-20">Sign Up For Free</a>
				</div>
			</div>
			<div style="float: right; margin-top: 2%;" class="main-content container-fluid col-md-8">
				<div class="splash-container">
					<div class="panel panel-default">
						<div class="panel-heading">
							<a href="https://semtasks.com"><img src="<?php echo base_url(); ?>/uploads/ciuis_settings/logo.png" alt="logo" class="logo-img"></a>
</br>
							<span class="splash-description"><?php echo lang('logindescription')?></span>
						</div>
						<div class="panel-body">
							<?php echo form_open() ?>

							<label class="js-text-danger" for="text-danger">
								<?php if (validation_errors()) : ?>
								<div class="alert alert-danger" role="alert">
									<?= validation_errors() ?>
								</div>
							<?php endif; ?>
							<?php if (isset($error)) : ?>
								<div class="alert alert-danger" role="alert">
									<?= $error ?>
								</div>
							<?php endif; ?>
							</label>

							<div class="form-group text-center">
								<div class="col-xs-6 login-remember">
									<span id="facebook" title="LOGIN WITH FACEBOOK">
										<img alt="" src="<?php echo base_url(); ?>assets/img/facebook.png" width="150" height="45"></a>
									</span>
								</div>
								<div class="col-xs-6 login-remember">
									<div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark" data-width="150" data-height="45"></div>
								</div>
							</div>
							<br><br><br>
							<div class="form-group text-center">
								<i class="mdi mdi-minus"></i></a> OR <i class="mdi mdi-minus"></i></a>
							</div>
							
							
							<div class="form-group">
								<input id="email" type="email" placeholder="<?php echo lang('loginemail')?>" name="email" autocomplete="on" class="form-control" value="<?php echo set_value('email'); ?>">
							</div>
							<div class="form-group">
								<input id="password" type="password" name="password" placeholder="<?php echo lang('loginpassword')?>" class="form-control" value="<?php echo set_value('password'); ?>">
							</div>
							<div class="form-group row login-tools">
								<div class="col-xs-6 login-remember">
									<div class="ciuis-body-checkbox">
										<input type="checkbox" id="remember">
										<label for="remember"><?php echo lang('loginremember')?></label>
									</div>
								</div>
								<div class="col-xs-6 login-forgot-password"><a href="<?php echo base_url('area/forgot')?>"><?php echo lang('loginforget')?></a>
								</div>
							</div>
							<div class="form-group login-submit">
								<button data-dismiss="modal" type="submit" class="btn btn-ciuis btn-xl"><?php echo lang('loginbutton')?></button>
							</div>
							
							
							<div class="form-group row login-tools">
								<div class="col-xs-12 register-email"><a href="<?php echo base_url('area/signup')?>"><?php echo lang('loginregister') ?>  <i class="mdi mdi-long-arrow-right"></i></a></div>
							</div>
							
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<script src="<?php echo base_url(); ?>assets/lib/jquery/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/Ciuis.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/lib/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/lib/jquery.gritter/js/jquery.gritter.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/lib/select2/js/select2.min.js" type="text/javascript"></script>
<?php if ( $this->session->flashdata('ntf1')) {?>
	<script type="text/javascript">
		$.gritter.add( {
			title: '<b><?php echo lang('notification')?></b>',
			text: '<?php echo $this->session->flashdata('ntf1'); ?>',
			class_name: 'color success'
		} );
	</script>
	<?php }?>
	<?php if ( $this->session->flashdata('ntf2')) {?>
	<script type="text/javascript">
		$.gritter.add( {
			title: '<b><?php echo lang('notification')?></b>',
			text: '<?php echo $this->session->flashdata('ntf2'); ?>',
			class_name: 'color primary'
		} );
	</script>
	<?php }?>
	<?php if ( $this->session->flashdata('ntf3')) {?>
	<script type="text/javascript">
		$.gritter.add( {
			title: '<b><?php echo lang('notification')?></b>',
			text: '<?php echo $this->session->flashdata('ntf3'); ?>',
			class_name: 'color warning'
		} );
	</script>
	<?php }?>
	<?php if ( $this->session->flashdata('ntf4')) {?>
	<script type="text/javascript">
		$.gritter.add( {
			title: '<b><?php echo lang('notification')?></b>',
			text: '<?php echo $this->session->flashdata('ntf4'); ?>',
			class_name: 'color danger'
		} );
	</script>
	<?php }?>

	<script>

		window.fbAsyncInit = function() {
			FB.init({
				appId: '<?= FB_APP_ID ?>', // App ID
				cookie:true, // enable cookies to allow the server to access the session
				status:true, // check login status
				xfbml:true, // parse XFBML
				oauth : true, //enable Oauth
				//version: 'v3.1'
			});
		};
		//Read the baseurl from the config.php file
		(function(d){
			var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
			if (d.getElementById(id)) {return;}
			js = d.createElement('script'); js.id = id; js.async = true;
			js.src = "https://connect.facebook.net/en_US/all.js";
			ref.parentNode.insertBefore(js, ref);
		}(document));

		//Onclick for fb login
		$('#facebook').click(function(e) {
			e.preventDefault();
			FB.login(function(response) {
				if(response.authResponse) {
					FB.api('/me?fields=id,email,first_name,last_name', function(response) {
						/*console.log(JSON.stringify(response));
						parent.location ='<?=base_url('area/login/fblogin') ?>'; //redirect uri after closing the facebook popup*/
						$.ajax({
							url : '<?=base_url('area/login/fblogin') ?>',
							type : 'POST',
							data : response,
							dataType: 'json',
							success:function(result){
								if(result == 1){
									window.location.href ='<?=base_url("area/panel")?>';
								}
							}
						});
					});
				}
			},{scope: 'email'}); //permissions for facebook
		});
	</script>

	<script>
		var google_loggedin = 0;

		function onSignIn(googleUser) {

			var profile = googleUser.getBasicProfile();
			var id_token = googleUser.getAuthResponse().id_token;
			var social_id = profile.getId();
			var full_name = profile.getName();
			var given_name = profile.getGivenName();
			var email = profile.getEmail();

			if(google_loggedin > 0){
				parent.location = '<?=base_url('area/login/googlelogin') ?>' + '?social_id='+social_id + '&full_name='+full_name + '&email='+email;
			}else{
				// logout code
			}
			google_loggedin = google_loggedin + 1;

		};

	</script>


</body>
</html>
