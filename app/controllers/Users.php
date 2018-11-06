<?php
class Users extends  Controller {
    public function __construct()
    {
        $this->userModel = $this->model('User');
    }
    public function register(){
        // Check for post
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Sanitize post data

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data =[
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
            ];
            // Validate Email
            if (empty($data['email'])){
                $data['email_err'] = 'Please enter a valid email.';
            }else{
                if ($this->userModel->find_user_by_email($data['email'])){
                    $data['email_err'] = 'Email is already taken.';
                }
            }
            // Validate Name
            if (empty($data['name'])){
                $data['name_err'] = 'Please enter your name.';
            }
            // Validate Password
            if (empty($data['password'])){
                $data['password_err'] = 'Please enter password.';
            }elseif (strlen($data['password']) < 6){
                $data['password_err'] = 'Password must be at least 6 characters.';
            }
            // Validate Confirm-Password
            if (empty($data['confirm_password'])){
                $data['confirm_password_err'] = 'Please confirm password.';
            }else{
                if($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match.';
                }
            }

            //check to see if errors are empty
            if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                //Validated

                //Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                //Register User
                if($this->userModel->register($data)){
                    flash('register_success', 'You are registered and can login');
                    redirect('users/login');
                }else{
                    die('etwas ist mit den los!');
                }
            } else{
                $this->view('users/register', $data);
            }

        }else{
            // Init data
            $data =[
              'name' => '',
              'email' => '',
              'password' => '',
              'confirm_password' => '',
              'name_err' => '',
              'email_err' => '',
              'password_err' => '',
              'confirm_password_err' => '',
            ];

            $this->view('users/register', $data);
        }
    }


    public function login()
    {
        // Check for post method
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',
            ];
            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter a valid email.';
            }
            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password.';
            }

            // Check user/email
            if ($this->userModel->find_user_by_email($data['email'])){

            }else{
                $data['email_err'] = 'No user found';
            }

            //check to see if errors are empty
            if (empty($data['email_err']) &&  empty($data['password_err'])) {
                //Validated and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if ($loggedInUser){
                    // create session variables
                    $this->create_user_session($loggedInUser);
                }else{
                    $data['password_err'] = 'Password incorrect';
                    $this->view('users/login', $data);
                }

            } else {
                $this->view('users/login', $data);
            }

        } else {
            // Init data
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',
            ];
            $this->view('users/login', $data);
        }
    }
    public function create_user_session($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        redirect('posts');
    }

    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        session_destroy();
        redirect('users/login');
    }
}