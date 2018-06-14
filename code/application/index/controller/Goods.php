<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;

class Goods extends Controller
{
    public function index(Request $req)
    {
        \think\Cookie::set("set","123456");
        $id = $req->get("id");
        $this->assign("data",\app\index\model\Goods::get($id));
        $data = Db::table('evaluate')
            ->alias('a')
            ->join('user b', 'a.user_id = b.id')
            ->field("a.id,b.name user_name,a.title,a.content,a.time")
            ->where("goods_id",$id)
            ->paginate(6);
        $this->assign("evaluate",$data);
        return view();
    }

    public function randData(Request $req)
    {
        $type = $req->get("type");
        $length = $req->get("length");
        $tid = \app\index\model\Goodstype::get(["type" => $type])->id;
        $sql = 'SELECT id,name,pic_path,price,vip_price  FROM `goods` WHERE type_id = ' . $tid . ' AND id >= (SELECT floor( RAND() * (SELECT MAX(id) FROM `goods` WHERE type_id = ' . $tid . ') ) )LIMIT ' . $length;
        if (($res = Db::query($sql)) != false) {
            if ($res != null) {
                return json([
                    "status" => "success",
                    "data" => json_encode($res),
                ]);
            }
        }
        return json(["status" => "error"]);
    }

    public function getData(Request $req){
        return json(\app\index\model\Goods::get($req->get("id")));
    }
}
