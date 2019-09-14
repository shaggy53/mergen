<?php


class SiteController
{

    public function getIndex(){
        echo 'controllere geldin!(getindex/sitecontroller)';
    }
    public function findUri($inputs){
        view('pages.index',$inputs);
    }
    public function findUri2($post){
        print_r($post);
    }
}