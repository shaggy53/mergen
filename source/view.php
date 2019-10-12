<?php
function part($part){
    $part = explode('.',$part);
    $incl = '/';
    foreach ($part as $p){
        $incl .= $p.'/';
    }
    $incl = substr($incl,0,-1);
 return include(realpath('../').'/views'.$incl.'.mio');
}
function view($viewname,$variables = []){
	$vn = explode('.',$viewname);
	$va = '';
	extract($variables);
	foreach ($vn as $v) {
		$va .= '/'.$v;
	}
	include(realpath('../').'/views'.$va.'.mio');

}