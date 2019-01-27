<?php

function getIndex(){
	echo 'controllere geldin!(getindex/sitecontroller)';
}
function findUri($inputs){
	$degiskenler=['ad' => 'selcuk','soyad' => 'molla'];
  view('pages.index',$degiskenler);
}