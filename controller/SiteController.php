<?php
model('users');

class SiteController
{
    public function getIndex(){
        $users = new Users;
        $search = $users->where('username','=','mergen');
        print_r($search);
    }
    public function findUri($inputs){
        view('pages.index',$inputs);
    }
    public function findUri2($post){
        print_r($post);
    }
}