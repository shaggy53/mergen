<?php

$route=[
'/' => [
'type' => 'get',	
'controller' =>'SiteController',
'function' => 'getIndex',
'name' => 'home'
],

'find' => [
'type' => 'get',
'variables' => 'v1/v2/v3/v4/seyit',	
'controller' =>'SiteController',
'function' => 'findUri',
'name' => 'finds'
],
'find2' => [
'type' => 'post',
'controller' =>'SiteController',
'function' => 'findUri2',
'name' => 'findss'
],
'find3' => [
'type' => 'get',
'variables' => 'v1/v2/v3/v4/seyit',	
'controller' =>'SiteController',
'function' => 'findUri3',
'name' => 'findsss'
],

];