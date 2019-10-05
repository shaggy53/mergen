<?php

class Controller
{
    function loadModel($modelname){
        require realpath(dirname(__FILE__)).'/Model.php';
        require realpath('../').'/Model/'.$modelname.'.php';

    }
}