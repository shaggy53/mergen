<?php

$route=[
'/' => [
'controller' =>'SiteController',
'function' => 'getIndex',
'name' => 'home'
],

'find' => [
'variables' => 'v1/v2/v3/v4/seyit',	
'controller' =>'SiteController',
'function' => 'findUri',
'name' => 'finds'
],
'find2' => [
'variables' => 'v1/v2/v3/v4/seyit',	
'controller' =>'SiteController',
'function' => 'findUri2',
'name' => 'findss'
],
'find3' => [
'variables' => 'v1/v2/v3/v4/seyit',	
'controller' =>'SiteController',
'function' => 'findUri3',
'name' => 'findsss'
],

];