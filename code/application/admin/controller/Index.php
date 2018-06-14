<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;

class Index extends Controller
{
    public function index()
    {
        return view();
    }

    public function register()
    {
        return view();
    }
    public function doLogin(Request $req)
    {
        $account = $req->post("account");
        $password = $req->post("password");

        $admin = new \app\admin\model\Admin;
        $data = $admin->get(['name' => $account]);
        if ($data != null && $password == $data["password"]) {
            // 登陆成功
            \think\Session::clear();
            \think\Session::set('name', $account);
            \think\Session::set('isAdmin', true);
            return json(["status" => "success"]);
        } else {
            // 登陆失败
            return json(["status" => "failed"]);
        }
    }

    public function doRegister(Request $req)
    {
        $account = $req->post("account");
        $password = $req->post("password");
        $admin = new \app\admin\model\Admin;
        $data = $admin->get(['name' => $account]);
        if ($data == null) {
            // 注册成功
            $admin->data([
                "name" => $account,
                "password" => $password,
            ]);
            $admin->save();
            return json([
                "status" => "success",
                "msg" => "注册成功",
            ]);
        } else {
            //账号存在
            return json([
                "status" => "failed",
                "msg" => "账号已存在",
            ]);
        }
    }
}
