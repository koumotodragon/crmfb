<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );

class Login extends CI_Controller {

	public $inactive;
	public $roles;

	function __construct() {

		parent::__construct();
		$this->load->model( 'Settings_Model' );
		define( 'LANG', $this->Settings_Model->get_crm_lang() );
		$this->lang->load( LANG, LANG );
		$settings = $this->Settings_Model->get_settings( 'ciuis' );
		$timezone = $settings[ 'default_timezone' ];
		date_default_timezone_set( $timezone );
		$this->load->model( 'Area_Model' );
		$this->load->library( 'form_validation' );
		$this->form_validation->set_error_delimiters( '<div class="error">', '</div>' );
		$this->inactive = $this->config->item( 'inactive' );
		$this->roles = $this->config->item( 'roles' );
		$timezone = $settings[ 'default_timezone' ];
		date_default_timezone_set( $timezone );
		$this->load->database();
		$this->load->helper('cookie');
		$this->load->helper('file');
		$this->load->library('session');

		$CI = & get_instance();
		//$CI->config->load("facebook", TRUE);
		$config = $CI->config->item('facebook');
		$this->load->library('Facebook', $config);



	}

	function index() {
		if ( isset( $_SESSION[ 'logged_in' ] ) && $_SESSION[ 'logged_in' ] === true ) {
			redirect( 'area/panel' );
		} else {
			redirect( 'area/login/auth' );
		}
	}

	function auth() {
		$data = new stdClass();
		$this->load->helper( 'form' );
		$this->load->library( 'form_validation' );
		$this->form_validation->set_rules( 'email', 'Email', 'required' );
		$this->form_validation->set_rules( 'password', 'Password', 'required' );
		if ( $this->form_validation->run() == false ) {
			$this->load->view( 'area/login/login' );
		} else {
			$email = $this->input->post( 'email' );
			$password = $this->input->post( 'password' );
			if ( $this->Area_Model->resolve_user_login( $email, $password ) ) {
				$contact_id = $this->Area_Model->get_contact_id_from_email( $email );
				$user = $this->Area_Model->get_user( $contact_id );
				$_SESSION[ 'contact_id' ] = ( int )$user->id;
				$_SESSION[ 'customer' ] = ( int )$user->customer_id;
				$_SESSION[ 'name' ] = ( string )$user->name;
				$_SESSION[ 'surname' ] = ( string )$user->surname;
				$_SESSION[ 'email' ] = ( string )$user->email;
				$_SESSION[ 'phone' ] = ( string )$user->phone;
				$_SESSION[ 'admin' ] = ( string )$user->admin;
				$_SESSION[ 'logged_in' ] = ( bool )true;
				redirect( 'area/panel' );
			} else {
				$data->error = lang( 'wrongmessage' );
				$this->load->view( 'area/login/login', $data );

			}
		}
	}

	function fblogin() {
		$facebook_id = $this->facebook->getUser();

		if ($facebook_id) {

			// = $this->facebook->api('/'.$facebook_id.'?fields=id,name,email');
			$user_profile = $this->input->post();
			$user_profile['name']=$user_profile['first_name']." ".$user_profile['last_name'];
			$firstname  = $lastname  = '';
			if (isset($user_profile['name']) && $user_profile['name'] != '') {
				$user_profilearray = explode(' ', $user_profile['name']);
				$firstname = $user_profilearray[0];
				$lastname = isset($user_profilearray[1]) ? $user_profilearray[1] : '';
			}

			$email = isset($user_profile['email'])?$user_profile['email']:'';

			$fbdata['email'] = $email;
			$fbdata['firstname'] = $firstname;
			$fbdata['lastname'] = $lastname;
			$fbdata['login_using'] = 'facebook';
			$fbdata['social_id'] = $facebook_id;
			
			if (! $this->Area_Model->resolve_user_social_login($facebook_id, 'facebook' ) ) {
				// register if not exists
				$this->Area_Model->resolve_signup( $fbdata ) ;
				// SEND EMAIL SETTINGS
				$setconfig = $this->Settings_Model->get_settings_ciuis();
				$subject = lang( 'your_login_informations' );
				$to = $this->input->post( 'email' );
				$data = array(
					'name' => $firstname,
					'password' => "",
					'email' => $email,
					'loginlink' => '' . base_url( 'area/login' ) . ''
				);
				$body = $this->load->view( 'email/accountinfofb.php', $data, TRUE );
				$result = send_email( $subject, $to, $data, $body );
			}

			/** Login code  **/
			$contact_id = $this->Area_Model->get_contact_id_from_social_id( $facebook_id, 'facebook' );
			$user = $this->Area_Model->get_user( $contact_id );
			$_SESSION[ 'contact_id' ] = ( int )$user->id;
			$_SESSION[ 'customer' ] = ( int )$user->customer_id;
			$_SESSION[ 'name' ] = ( string )$user->name;
			$_SESSION[ 'surname' ] = ( string )$user->surname;
			$_SESSION[ 'email' ] = ( string )$user->email;
			$_SESSION[ 'admin' ] = ( string )$user->admin;
			$_SESSION[ 'logged_in' ] = ( bool )true;
			redirect( 'area/panel' );
			//echo 1;


		} else {
			$loginUrl = $this->facebook->getLoginUrl(array('scope' => 'email'));
			header('Location: ' . $loginUrl);
		}
	}
	function fblogin_backup() {
		$facebook_id = $this->facebook->getUser();

		if ($facebook_id) {

			$user_profile = $this->facebook->api('/'.$facebook_id.'?fields=id,name,email');

			$firstname  = $lastname  = '';
			if (isset($user_profile['name']) && $user_profile['name'] != '') {
				$user_profilearray = explode(' ', $user_profile['name']);
				$firstname = $user_profilearray[0];
				$lastname = isset($user_profilearray[1]) ? $user_profilearray[1] : '';
			}

			$email = isset($user_profile['email'])?$user_profile['email']:'';

			$fbdata['email'] = $email;
			$fbdata['firstname'] = $firstname;
			$fbdata['lastname'] = $lastname;
			$fbdata['login_using'] = 'facebook';
			$fbdata['social_id'] = $facebook_id;

			if (! $this->Area_Model->resolve_user_social_login($facebook_id, 'facebook' ) ) {
				// register if not exists
				$this->Area_Model->resolve_signup( $fbdata ) ;
			}

			/** Login code  **/
			$contact_id = $this->Area_Model->get_contact_id_from_social_id( $facebook_id, 'facebook' );
			$user = $this->Area_Model->get_user( $contact_id );
			$_SESSION[ 'contact_id' ] = ( int )$user->id;
			$_SESSION[ 'customer' ] = ( int )$user->customer_id;
			$_SESSION[ 'name' ] = ( string )$user->name;
			$_SESSION[ 'surname' ] = ( string )$user->surname;
			$_SESSION[ 'email' ] = ( string )$user->email;
			$_SESSION[ 'admin' ] = ( string )$user->admin;
			$_SESSION[ 'logged_in' ] = ( bool )true;
			redirect( 'area/panel' );



		} else {
			$loginUrl = $this->facebook->getLoginUrl(array('scope' => 'email'));
			header('Location: ' . $loginUrl);
		}
	}


	function googlelogin() {

			$postData = $_GET;
			$firstname  = $lastname  = '';
			if (isset($postData['full_name']) && $postData['full_name'] != '') {
				$postDataarray = explode(' ', $postData['full_name']);
				$firstname = $postDataarray[0];
				$lastname = isset($postDataarray[1]) ? $postDataarray[1] : '';
			}

			$postData['firstname'] = $firstname;
			$postData['lastname'] = $lastname;
			$postData['login_using'] = 'facebook';
			$google_id =  $postData['social_id'];
			$email =  $postData['email'];

			$is_registered =  0 ;
			if($email != ''){
				$is_registered = $this->Area_Model->get_contact_id_from_email($email);
			}
			if( !$is_registered ) {
				$is_registered = $this->Area_Model->resolve_user_social_login($google_id, 'google');
			}
			if( !$is_registered ) {
				$this->Area_Model->resolve_signup( $postData ) ;
			}

			if($email != '') {
				$contact_id = $this->Area_Model->get_contact_id_from_email($email);
			}else{
				$contact_id = $this->Area_Model->get_contact_id_from_social_id( $google_id, 'google' );
			}

			$this->Area_Model->update_email_with_social_id( $contact_id, $email, $google_id);

			/** Login code  **/

			$user = $this->Area_Model->get_user( $contact_id );
			$_SESSION[ 'contact_id' ] = ( int )$user->id;
			$_SESSION[ 'customer' ] = ( int )$user->customer_id;
			$_SESSION[ 'name' ] = ( string )$user->name;
			$_SESSION[ 'surname' ] = ( string )$user->surname;
			$_SESSION[ 'email' ] = ( string )$user->email;
			$_SESSION[ 'admin' ] = ( string )$user->admin;
			$_SESSION[ 'logged_in' ] = ( bool )true;
			redirect( 'area/panel' );

	}
	public function updateprofilearea(){
		$postData = $_POST;
		//echo "<pre>";print_r($postData);exit;
		$this->db->update('contacts', array('email' => $postData['email'], 'name' => $postData['name'], 'phone' => $postData['phone']) , array('id' => $postData['cid']) );
		//$user = $this->Area_Model->update_user_data( $postData['cid'],$postData['name'],$postData['email'],$postData['phone'] );
		echo "Profile Updated Successfully";

	}
	public function get_user_data($id){
		$user = $this->Area_Model->get_user( $id );
		echo json_encode( $user );

	}
	function logout() {
		$data = new stdClass();
		if ( isset( $_SESSION[ 'logged_in' ] ) && $_SESSION[ 'logged_in' ] === true ) {
			foreach ( $_SESSION as $key => $value ) {
				unset( $_SESSION[ $key ] );
			}
			redirect( '/area' );
		} else {
			redirect( '/area' );
		}
	}
	public function forgot() {

		$this->form_validation->set_rules( 'email', 'Email', 'required|valid_email' );

		if ( $this->form_validation->run() == FALSE ) {
			$this->load->view( 'area/login/forgot' );
		} else {
			$email = $this->input->post( 'email' );
			$clean = $this->security->xss_clean( $email );
			$userInfo = $this->Contacts_Model->getUserInfoByEmail( $clean );

			if ( !$userInfo ) {
				$this->session->set_flashdata( 'ntf4', lang( 'customercanffindmail' ) );
				redirect( site_url() . 'area/login' );
			}

			if ( $userInfo->inactive != $this->inactive[ 1 ] ) { //if inactive is not approved
				$this->session->set_flashdata( 'ntf4', lang( 'customerinactiveaccount' ) );
				redirect( site_url() . 'area/login' );
			}

			//build token 

			$token = $this->Contacts_Model->insertToken( $userInfo->id );
			$qstring = $this->base64url_encode( $token );
			$url = site_url() . 'area/reset_password/token/' . $qstring;
			$link = '<a href="' . $url . '">' . $url . '</a>';
			// SEND EMAIL SETTINGS
			$setconfig = $this->Settings_Model->get_settings_ciuis();
			$subject = lang( 'resetyourpassword' );
			$to = $userInfo->email;
			$data = array(
				'name' => $userInfo->name,
				'email' => $userInfo->email,
				'link' => $url,
			);
			$body = $this->load->view( 'email/reset_password.php', $data, TRUE );
			$result = send_email( $subject, $to, $data, $body );
			$this->session->set_flashdata( 'ntf1', '<b>' . lang( 'customerpasswordsend' ) . '</b>' );
			redirect( 'area/login' );

		}

	}

	public

	function reset_password() {
		$token = $this->base64url_decode( $this->uri->segment( 4 ) );
		$cleanToken = $this->security->xss_clean( $token );

		$user_info = $this->Contacts_Model->isTokenValid( $cleanToken ); //either false or array();               

		if ( !$user_info ) {
			$this->session->set_flashdata( 'ntf1', lang( 'tokenexpired' ) );
			redirect( site_url() . 'area/login' );
		}
		$data = array(
			'firstName' => $user_info->name,
			'email' => $user_info->email,
			//                'contact_id'=>$user_info->id, 
			'token' => $this->base64url_encode( $token )
		);

		$this->form_validation->set_rules( 'password', 'Password', 'required|min_length[5]' );
		$this->form_validation->set_rules( 'passconf', 'Password Confirmation', 'required|matches[password]' );

		if ( $this->form_validation->run() == FALSE ) {
			$this->load->view( 'area/login/reset_password', $data );
		} else {

			$post = $this->input->post( NULL, TRUE );
			$cleanPost = $this->security->xss_clean( $post );
			$hashed = password_hash( $cleanPost[ 'password' ], PASSWORD_BCRYPT );
			$cleanPost[ 'password' ] = $hashed;
			$cleanPost[ 'contact_id' ] = $user_info->id;
			unset( $cleanPost[ 'passconf' ] );
			if ( !$this->Contacts_Model->updatePassword( $cleanPost ) ) {
				$this->session->set_flashdata( 'ntf1', lang( 'problemupdatepassword' ) );
			} else {
				$this->session->set_flashdata( 'ntf1', lang( 'passwordupdated' ) );
			}
			redirect( site_url() . 'area/login' );
		}
	}

	public

	function base64url_encode( $data ) {
		return rtrim( strtr( base64_encode( $data ), '+/', '-_' ), '=' );
	}

	public

	function base64url_decode( $data ) {
		return base64_decode( str_pad( strtr( $data, '-_', '+/' ), strlen( $data ) % 4, '=', STR_PAD_RIGHT ) );
	}


}