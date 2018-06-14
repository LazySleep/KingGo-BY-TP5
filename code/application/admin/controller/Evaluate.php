<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;

class Evaluate extends Controller
{
    public function _initialize()
    {
        if (!\think\Session::has('name') || !\think\Session::has('isAdmin')) {
            $this->redirect(url("Index/index"));
        }
    }
    public function index()
    {
        $data = Db::table('evaluate')
            ->alias('a')
            ->join('user b', 'a.user_id = b.id')
            ->join('goods c', 'a.goods_id = c.id')
            ->field("a.id,c.name goods_name,b.name user_name,a.title,a.content,a.time")
            ->paginate(6);
        $this->assign("list", $data);
        return view();
    }
    public function doDelete(Request $req)
    {
        $ev = new \app\admin\model\Evaluate;
        $ev->get($req->post("id"))->delete();
        return json(["status" => "success"]);
    }

}
