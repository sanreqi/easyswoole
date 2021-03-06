<?php


namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\Controller;

class Index extends Controller
{

    public function index()
    {
        $this->response()->write('hello world');
//        $file = EASYSWOOLE_ROOT.'/vendor/easyswoole/easyswoole/src/Resource/Http/welcome.html';
//        if(!is_file($file)){
//            $file = EASYSWOOLE_ROOT.'/src/Resource/Http/welcome.html';
//        }
//        $this->response()->write(file_get_contents($file));
    }

    public function test() {
        $this->response()->write('hi baby');
    }

    protected function actionNotFound(?string $action)
    {
        $this->response()->withStatus(404);
        $file = EASYSWOOLE_ROOT.'/vendor/easyswoole/easyswoole/src/Resource/Http/404.html';
        if(!is_file($file)){
            $file = EASYSWOOLE_ROOT.'/src/Resource/Http/404.html';
        }
        $this->response()->write(file_get_contents($file));
    }
}