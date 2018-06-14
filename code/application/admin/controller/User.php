<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;

class User extends Controller
{
    public function _initialize()
    {
        if (!\think\Session::has('name') || !\think\Session::has('isAdmin')) {
            $this->redirect(url("Index/index"));
        }
    }
    public function info()
    {
        $this->assign("list", \app\admin\model\User::paginate(6));
        return view();
    }
    public function leaveWord()
    {
        $data = Db::table('leaveword')
            ->alias('a')
            ->join('user b', 'a.user_id = b.id')
            ->field("a.id,b.name,a.title,a.content,a.time")
            ->paginate(6);
        $this->assign("list", $data);
        return view();
    }
    public function banUser(Request $req)
    {
        $user = new \app\admin\model\User;
        $user->save(["freeze" => 1], ["id" => $req->post("id")]);
        return json(["status" => "success"]);
    }
}
