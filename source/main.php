<?php

function writeObj($obj, $exit = false){
	print_r($obj);
	if($exit)
		exit;
}


function connectDB(){
	global $connection;
	$connection = null;
	try {
        $connection = new \PDO(DB_TYPE . ":host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD, array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::ATTR_PERSISTENT => FALSE,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            )
        );
    } catch (\PDOException $e) {
    	writeObj($e);
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 İç Sunucu Hatası', true, 500);
        echo "<h1>Veritabanı Bağlantısında Hata! (MY001)</h1>";
        exit;
    }
}


function sessionStart()
{
    if (session_id() == '') {
        ini_set('session.cookie_domain', '.' . SITE_DOMAIN);
        session_cache_limiter(false);
        session_start();
    }
}


function clearHtml($var, $remove = false)
{
    if (is_array($var)) {
        return array_map(function ($v) use ($remove) {
            return clearHtml($v, $remove);
        }, $var);
    }

    return trim(!is_string($var) ? $var : ($remove ? strip_tags($var) : htmlspecialchars($var)));
}

function getSlug($string, $replace = [], $delimiter = '-')
{
    $string = str_replace('ı', 'i', $string);
    $string = str_replace('İ', 'i', $string);
    if (!extension_loaded('iconv')) {
        throw new Exception('iconv module not loaded');
    }

    $oldLocale = setlocale(LC_ALL, '0');
    setlocale(LC_ALL, 'tr_TR.UTF-8');

    if (!empty($replace) && is_array($replace)) {
        $string = str_replace(array_keys($replace), array_values($replace), $string);
    }

    $string = preg_replace('#[^\pL\d]+#u', '-', $string);

    $string = trim($string, '-');

    $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);

    $clean = preg_replace('#[^a-zA-Z0-9\/_|+ -]#', '', $clean);
    $clean = strtolower($clean);
    $clean = preg_replace('#[\/_|+ -]+#', $delimiter, $clean);
    $clean = trim($clean, $delimiter);

    setlocale(LC_ALL, $oldLocale);

    return $clean;
}

function getroute($name,$vrb = []){
    global $route;
    $key = array_search($name, array_column($route, 'name'));
    $namert = array_keys($route);
    $uri = $namert[$key];
    if($vrb != ''){
        foreach ($vrb as $v) {
            if($v != '')
            $uri = $uri.'/'.$v;
        }
    }
    $protocol = strtolower(explode('/', $_SERVER['SERVER_PROTOCOL'])[0]);
    return $protocol.'://'.$_SERVER['SERVER_NAME'].'/'.$uri;
}
function part($part){
    include('../views/pages/parts/'.$part.'.php');
}
