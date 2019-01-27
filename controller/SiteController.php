<?php
 
function getIndex(){
	echo 'controllere geldin!(getindex/sitecontroller)';
}
function findUri($inputs){
  view('pages.index',$inputs);
}
function findUri2($post){
   print_r($post);
}