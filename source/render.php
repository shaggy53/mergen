<?php 
require realpath(dirname(__FILE__)).'/view.php';

$uri = $_SERVER['REQUEST_URI'];
if($uri == '/'){

	require realpath('../').'/controller/'.$route[$uri]['controller'].'.php';
	$route[$uri]['function']();
}
else{	
	
		$uri = explode('/', $uri);
		if($route[$uri[1]]['type'] == 'get'){
			if(array_search($uri[1], array_keys($route)) != ''){
			$inputs = [];
			require realpath('../').'/controller/'.$route[$uri[1]]['controller'].'.php';
			if(isset($route[$uri[1]]['variables']) || $route[$uri[1]]['variables'] != ''){

				$variables = explode('/', $route[$uri[1]]['variables']);
				$sy = 2;
				foreach ($variables as $v) {
					if(isset($uri[$sy])){
					$inputs[$v] = $uri[$sy];
				}
				else{
					$inputs[$v] = '';
				}
					$sy++;
				}
			}
			$route[$uri[1]]['function']($inputs);
		}else{
			http_response_code(404);
		}
		}
		elseif($route[$uri[1]]['type'] == 'post'){

		}
	}




