<?php 
function view($viewname,$variables = []){
	$vn = explode('.',$viewname);
	$va = '';
	extract($variables);
	foreach ($vn as $v) {
		$va = $va.'/'.$v;
	}
	include(realpath('../').'/views'.$va.'.php');

}