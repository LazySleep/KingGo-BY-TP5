<?php
namespace app\index\controller;

use think\Controller;
use think\Request;

class ShoppingCar extends Controller
{
    public function index()
    {
        return view();
    }

    public function orderList()
    {
        $this->assign("list", \app\index\model\Order::where("pay_name", \think\Session::get('name'))->paginate(10));
        return view();
    }

    public function singleAll(Request $req)
    {
        $this->assign("list", \app\index\model\Order::get($req->post("id")));
        return view();
    }

    public function commitOrder(Request $res)
    {
        if (!\think\Session::has('name') || \think\Session::has('isAdmin')) {
            return json(["status" => "no login"]);
        }
        if ($res->post("commit") != null) {
            $user = \app\index\model\User::get(["name" => \think\Session::get('name')]);
            $data = \json_decode(\think\Cookie::get("car"));
            $goods_string = "";
            $count_string = "";
            $total = 0;
            foreach ($data as $value) {
                $goods = \app\index\model\Goods::get($value[0]);
                $goods_string .= $goods->name . ";";
                $count_string .= $value[1] . ";";
                $total += $value[1] * $goods->price;
            }
            $no = date("YmdHis") . $user->id;
            $order = new \app\index\model\Order;
            $order->data([
                "no" => $no,
                "goods_string" => $goods_string,
                "count_string" => $count_string,
                "client_name" => $user->true_name,
                "sex" => "X",
                "address" => $user->address,
                "postal" => $user->postal,
                "tel" => $user->tel,
                "email" => $user->email,
                "receive_way" => "快递",
                "pay_way" => "货到付款",
                "leaveword" => "",
                "time" => date("Y-m-d H:i:s"),
                "pay_name" => $user->name,
                "status" => "准备出货",
                "total" => $total,
            ])->save();
            $order = \app\index\model\Order::get(["no" => $no]);
            $order->no = $order->no . $order->id;
            $order->save();
            return json(["status" => "success"]);
        } else {
            return json(["status" => "error"]);
        }
    }
}
