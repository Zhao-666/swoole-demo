<?php

namespace app\index\controller;

class Index
{
    public function index()
    {
//        var_dump(request()->get());
//        var_dump($_GET);
        return 'index';
    }

    public function hello($name = 'ThinkPHP5')
    {
        var_dump($_GET);
        return 'hello,' . $name;
    }
}
