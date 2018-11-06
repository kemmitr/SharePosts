<?php
class Posts extends Controller
{
    public function __construct()
    {
        if (!is_logged_in()) {
            redirect('users/login');
        }
        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');
    }

    public function index()
    {
        $posts = $this->postModel->get_posts();
        $data = [
            'posts' => $posts
        ];
        $this->view('posts/index', $data);
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post = $this->postModel->get_post_by_id($id);
            // Check for owner
            if ($post->userd_id != $_SESSION['user_id']) {
                redirect('posts');
            }
            if($this->postModel->delete_post($id)){
                flash('post_message', 'Post deleted successfully!');
                redirect('posts');
            }else{
                die('etwas ist mit den loss.');
            }

        }else{
            redirect('posts');
        }
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'user_id' => $_SESSION['userd_id'],
                'title_err' => '',
                'body_err' => '',
            ];
            // Validate title
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter a title.';
            }
            // Validate Body
            if (empty($data['body'])) {
                $data['body_err'] = 'body cannot be empty.';
            }

            //check to see if errors are empty
            if (empty($data['title_err']) && empty($data['body_err'])) {
                //Validated and set logged in user
                if($this->postModel->add_post($data)){
                    flash('post_message', 'Post added successfully!');
                    redirect('posts');
                }else{
                    die('Etwas ist mit den los');
                }
            } else {
                $this->view('users/login', $data);
            }

        } else {
            $data = [
                'title' => '',
                'body' => '',
            ];
            $this->view('posts/add', $data);
        }
    }

    public function show($id){
        $post = $this->postModel->get_post_by_id($id);
        $user = $this->userModel->get_user_by_id($post->userd_id);
        $data = [
            'post' => $post,
            'user' => $user
        ];
        $this->view('posts/show', $data);
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'user_id' => $_SESSION['userd_id'],
                'title_err' => '',
                'body_err' => '',
            ];
            // Validate title
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter a title.';
            }
            // Validate Body
            if (empty($data['body'])) {
                $data['body_err'] = 'body cannot be empty.';
            }

            //check to see if errors are empty
            if (empty($data['title_err']) && empty($data['body_err'])) {
                //Validated and set logged in user
                if($this->postModel->update_post($data)){
                    flash('post_message', 'Post updated successfully!');
                    redirect('posts');
                }else{
                    die('Etwas ist mit den los');
                }
            } else {
                $this->view('posts/edit', $data);
            }

        } else {
            $post = $this->postModel->get_post_by_id($id);
            // Check for owner
            if ($post->userd_id != $_SESSION['user_id']){
                redirect('posts');
            }
            $data = [
                'id' => $id,
                'title' => $post->title,
                'body' => $post->body,
            ];
            $this->view('posts/edit', $data);
        }
    }

}