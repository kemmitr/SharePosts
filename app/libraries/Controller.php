<?php
class Controller{
    //Load Model
    public function model($model){
        //require model file
        require_once '../app/models/'. $model. '.php';

        //instantiate model
        return new $model;
    }
    // Load view
    public function view($view, $data = []){
        if (file_exists('../app/views/'. $view . '.php')){
            require_once '../app/views/'. $view . '.php';
        }else{
            die('View does not exits');
        }
    }
}