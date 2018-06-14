<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Goods extends Controller
{
    public function _initialize()
    {
        if (!\think\Session::has('name') || !\think\Session::has('isAdmin')) {
            $this->redirect(url("Index/index"));
        }
    }
    public function addGoods()
    {
        $this->assign("list", \app\admin\model\Goodstype::all());
        return view();
    }

    public function updateGoods(Request $req)
    {
        $this->assign('data',\app\admin\model\Goods::get($req->get('id')));
        $this->assign("typelist", \app\admin\model\Goodstype::all());
        return view();
    }

    public function listGoods()
    {
        $this->assign("list",\app\admin\model\Goods::paginate(6));
        $this->assign("typelist", \app\admin\model\Goodstype::all());
        return view();
    }

    public function doAddGoods(Request $req)
    {
        $goods = new \app\admin\model\Goods;
        $goods->data($req->post());
        $goods->save();
        return json(["status" => "success"]);
    }

    public function doUpdateGoods(Request $req)
    {
        $goods = new \app\admin\model\Goods;
        $data = $req->post();
        $id = $data['id'];
        unset($data['id']);
        $goods->save($data, ["id" => $id]);
        return json(["status" => "success"]);
    }

    public function doDeleteGoods(Request $req)
    {
        $goods = new \app\admin\model\Goods;
        $goods->get($req->post("id"))->delete();
        return json(["status" => "success"]);
    }

    public function type()
    {
        $this->assign("list", \app\admin\model\Goodstype::paginate(5));
        return view();
    }

    public function addType()
    {
        return view();
    }

    public function doAddType(Request $req)
    {
        $gt = new \app\admin\model\Goodstype;
        if ($gt->get(["type" => $req->post("type")]) != null) {
            return json([
                "status" => "failed",
                "msg" => $req->post("type") . " 已存在",
            ]);
        }
        $gt->data($req->post());
        $gt->save();
        return json(["status" => "success"]);
    }

    public function doUpdateType(Request $req)
    {
        $goods = new \app\admin\model\Goodstype;
        $data = $req->post();
        $id = $data['id'];
        unset($data['id']);
        $goods->save($data, ["id" => $id]);
        return json(["status" => "success"]);
    }

    public function doDeleteType(Request $req)
    {
        $id = $req->post("id");
        if (\app\admin\model\Goods::get(["type_id" => $id])==null) {
            $gt = new \app\admin\model\Goodstype;
            $gt->get($id)->delete();
            return json(["status" => "success"]);
        }else{
            return json([
                "status" => "failed",
                "msg"=>"该分类下还存在商品，不能删除"
                ]);
        }

    }
}
