<?php
namespace app\index\controller;

use think\Controller;
use think\Request;

class Search extends Controller
{
    public function index(Request $req)
    {
        $data = $req->get();
        if(isset($data["keyword"]) && $data["keyword"]!=""){
            $goods = new \app\index\model\Goods;
            $res = $goods->where('name', "like", "%" . $data["keyword"] . "%")->paginate(40,false,['query' => $req->param()]);
            $this->assign("list",$res);
            return view();
        }else{
            $this->redirect(url("Index/index"));
        }       
    }

}
