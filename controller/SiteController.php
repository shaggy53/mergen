<?php
 
function getIndex(){
	echo 'controllere geldin!(getindex/sitecontroller)';
}
function findUri($inputs){
	
  view('pages.index',$inputs);
}