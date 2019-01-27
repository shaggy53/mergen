<?php
 
function getIndex(){
	echo 'controllere geldin!(getindex/sitecontroller)';
}
function findUri($inputs){
	print_r($inputs);
	exit;
  view('pages.index',$inputs);
}
function findUri2($post){
   print_r($post);
}