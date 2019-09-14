<?php
require realpath(dirname(__FILE__)).'/view.php';

$uri = $_SERVER['REQUEST_URI'];

if (preg_match('/\b(' . preg_quote('?', '/') . '\w+)/', $uri, $match)) {
   $tmp = explode('?',$uri);
   $uri = $tmp[0];
   $tmp2 = explode('&',$tmp[1]);
   $requestdata = [];
   foreach ($tmp2 as $tm){
       $tm = explode('=',$tm);
       $requestdata[$tm[0]] = $tm[1];
   }
}

if($uri == '/'){

	require realpath('../').'/controller/'.$route[$uri]['controller'].'.php';
    $run = new  $route[$uri]['controller'];
    $function = $route[$uri]['function'];
    $run->{$function}();
}
else{	
	
		$uri = explode('/', $uri);
		if(array_search($uri[1], array_keys($route)) != ''){
		    if($route[$uri[1]]['type'] == 'get'){
		        if($_SERVER['REQUEST_METHOD'] != 'GET'){
		           echo 'invalid method';
		           exit;
                }
		    	$inputs = [];
		    	require realpath('../').'/controller/'.$route[$uri[1]]['controller'].'.php';

		    	if(isset($route[$uri[1]]['variables']) || @$route[$uri[1]]['variables'] != ''){

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
		    	if(isset($requestdata)){
		    	    foreach ($requestdata as $key => $value)
		    	    $inputs[$key] = $value;
                }
		    	$run = new $route[$uri[1]]['controller'];
		    	$function = $route[$uri[1]]['function'];
                $run->{$function}($inputs);
		    }
		    elseif($route[$uri[1]]['type'] == 'post'){
		    	$inputs = [];
		    	if($_SERVER['REQUEST_METHOD'] != 'POST'){
                    echo 'invalid method';
                    exit;
		    	}else{
                    require realpath('../').'/controller/'.$route[$uri[1]]['controller'].'.php';
                    if(isset($requestdata)){
                        foreach ($requestdata as $key => $value)
                            $inputs[$key] = $value;
                    }else{
                        $inputs = [];
                    }
                    $run = new $route[$uri[1]]['controller'];
                    $function = $route[$uri[1]]['function'];
                    $run->{$function}($inputs);
		    	}

		    }

		}else{
			http_response_code(404);
		}
	}
function model($modelname){
    require realpath(dirname(__FILE__)).'/Model.php';
    require realpath('../').'/Model/'.$modelname.'.php';

}




