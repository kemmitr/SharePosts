<?php
class Post{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function get_posts(){
        $this->db->query('SELECT *,
                              posts.id as postId,
                              users.id as userId,
                              posts.created_at as date_written
                              FROM posts
                              INNER JOIN users
                              ON posts.userd_id = users.id
                              ORDER BY posts.created_at DESC');
        $results = $this->db->resultSet();
        return $results;
    }
    public function add_post($post){
        $this->db->query('INSERT INTO posts(title, body, userd_id) VALUES(:title, :body, :userd_id)');
        $this->db->bind(':title', $post['title']);
        $this->db->bind(':body', $post['body']);
        $this->db->bind(':userd_id', $_SESSION['user_id']);
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function update_post($post){
        $this->db->query('UPDATE posts SET title = :title, body = :body WHERE id = :id');
        $this->db->bind(':id', $post['id']);
        $this->db->bind(':title', $post['title']);
        $this->db->bind(':body', $post['body']);
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function get_post_by_id($id){
        $this->db->query('SELECT * FROM posts WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();
        return $row;
    }

    public function delete_post($id){
        $this->db->query('DELETE FROM posts WHERE id = :id');
        $this->db->bind(':id', $id);
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }
}