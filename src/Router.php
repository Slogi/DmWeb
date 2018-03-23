<?php

require_once ("model/SerieStorage.php");



class Router
{
    public function __construct( SerieStorage $serieStor)
    {
        $this->serieStor = $serieStor;
    }

    public function main(){
        var_dump($this->serieStor->read(1));
    }
}