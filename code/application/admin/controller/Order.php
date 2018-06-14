<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Order extends Controller
{
    public function _initialize()
    {
        if (!\think\Session::has('name') || !\think\Session::has('isAdmin')) {
            $this->redirect(url("Index/index"));
        }
    }

    public function editOrder(Request $req)
    {
        if ($req->has("key", "get")) {
            $key = $req->get("key");
            $this->assign("list", \app\admin\model\Order::where("no|goods_string|client_name|address|tel|email|receive_way|pay_name", "like", "%" . $key . "%")->paginate(6));
        } else {
            $this->assign("list", \app\admin\model\Order::paginate(6));
        }
        return view();
    }

    public function queryOrder()
    {
        return view();
    }

    public function delete(Request $req)
    {
        \app\admin\model\Order::destroy($req->post());
        return json(["status" => "success"]);
    }
    public function singleAll(Request $req)
    {
        $this->assign("list", \app\admin\model\Order::get($req->post("id")));
        return view();
    }
    public function doUpdateStatus(Request $req)
    {
        $order = \app\admin\model\Order::get($req->post("id"));
        $order->data(["status"=>$req->post("status")]);
        $order->save();
        return json(["status" => "success"]);
    }
}
