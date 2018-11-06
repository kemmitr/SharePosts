<?php
class User{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    // Find user by email
    public function find_user_by_email($email){
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();

        if ($this->db->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }
    public function get_user_by_id($id){
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();

        return $row;
    }

    // Register User
    public function register($user){
        $this->db->query('INSERT INTO users(name, email, password) VALUES(:name, :email, :password)');
        $this->db->bind(':name', $user['name']);
        $this->db->bind(':email', $user['email']);
        $this->db->bind(':password', $user['password']);
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }

    // Login User
    public function login($email, $password){
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();
        $hashed_password = $row->password;
        if (password_verify($password, $hashed_password)){
            return $row;
        }else{
            return false;
        }

    }
}