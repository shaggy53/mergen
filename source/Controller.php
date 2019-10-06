<?php

class Controller
{
    public $users;
    public function __construct()
    {
        require realpath(dirname(__FILE__)).'/Model.php';
        $models = $this->getModelDirs('../Model');
        foreach ($models as $model){
            if(strstr($model,'.php'))
            require $model;
        }

    }
    private function getModelDirs($dir, &$results = array()){
        $files = scandir($dir);

        foreach($files as $key => $value){
            $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
            if(!is_dir($path)) {
                $results[] = $path;
            } else if($value != "." && $value != "..") {
                $this->getModelDirs($path, $results);

            }
        }

        return $results;
    }


}