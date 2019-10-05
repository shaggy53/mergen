<?php
Controller::loadModel('users');

class SiteController extends Controller
{
    public function getIndex(){
        $users = new Users;
        $search = $users->select('users.id as id,users_jion.test_sutun')->join('users_jion')->where('username','=','mergen2')->where('password','=','mergenn')->groupBy('users.password')->orderBy('users.id','DESC')->get('array');
        //$search = $users->query("SELECT * FROM `users` INNER JOIN `users_jion` ON users.id = users_jion.users_id WHERE `username`='mergen2' AND `password`='mergenn' GROUP BY users.password ORDER BY users.id DESC")->get();
        print_r($search);
    }
    public function findUri($inputs){
        view('pages.index',$inputs);
    }
    public function findUri2($post){
        print_r($post);
    }
}