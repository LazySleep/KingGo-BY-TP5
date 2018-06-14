<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;

class Index extends Controller
{
    public function index()
    {
        return view();
    }

    public function login()
    {
        return view();
    }

    public function register()
    {
        return view();
    }

    public function leaveword()
    {
        return view();
    }

    public function changePwd()
    {
        $name = \think\Session::get("name");
        $data = \app\index\model\User::get(["name" => $name]);
        $this->assign("tip", $data->find_password_tip);
        return view();
    }

    public function ChangeInfo()
    {
        $name = \think\Session::get("name");
        $data = \app\index\model\User::get(["name" => $name]);

        $this->assign("data", [
            "qq" => $data->qq,
            "email" => $data->email,
            "address" => $data->address,
            "postal" => $data->postal,
            "tel" => $data->tel,
        ]);
        return view();
    }

    public function checkLogin(Request $req)
    {
        $user = $req->post("username");
        $pwd = $req->post("password");
        if (!captcha_check($req->post("verifyCode"))) {
            return json([
                "status" => "failed",
                "msg" => "验证码错误",
            ]);
        } else if (\app\index\model\User::get(["name" => $user, "password" => $pwd]) == null) {
            return json([
                "status" => "failed",
                "msg" => "用户名或密码错误！",
            ]);
        } else {
            \think\Session::clear();
            \think\Session::set("name", $user);
            return json([
                "status" => "success",
            ]);
        }

    }

    public function logout()
    {
        \think\Session::clear();
        $this->redirect(url("Index/index"));
    }

    public function doRegister(Request $req)
    {
        $data = $req->post();
        if (\app\index\model\User::get(["name" => $data["name"]]) != null) {
            return json(["status" => "nameexist", "msg" => "用户名已存在"]);
        }
        if ($data["password"] == $data["repassword"]) {
            unset($data["repassword"]);
            $data["reg_time"] = date("y-m-d H:i:s");
            $user = new \app\index\model\User;
            $user->data($data);
            $user->save();
            return json(["status" => "success"]);
        }
        return json(["status" => "error", "msg" => "其他错误"]);
    }
    public function doChangePwd(Request $req)
    {
        $name = \think\Session::get("name");
        $data = \app\index\model\User::get(["name" => $name]);
        if ($data == null) {
            return json(["status" => "no user", "msg" => "找不到用户"]);
        }
        if ($data->find_password_ans != $req->post("ans")) {
            return json(["status" => "ans error"]);
        }
        if ($data->password != $req->post("old")) {
            return json(["status" => "old error"]);
        }
        $data->password = $req->post("new");
        $data->save();
        return json(["status" => "success"]);
    }

    public function doChangeInfo(Request $req)
    {
        $name = \think\Session::get("name");
        $data = \app\index\model\User::get(["name" => $name]);
        $data->data($req->post());
        $data->save();
        return json(["status" => "success"]);
    }
    public function doLeaveword(Request $req)
    {
        if (!captcha_check($req->post("verifyCode"))) {
            return json([
                "status" => "failed",
                "msg" => "验证码错误",
            ]);
        }
        $name = \think\Session::get("name");
        $user_id = \app\index\model\User::get(["name" => $name])->id;
        $leaveword = new \app\index\model\Leaveword();
        $leaveword->data(["user_id"=>$user_id,"time"=>date("y-m-d H:i:s"),"title"=>$req->post("title"),"content"=>$req->post("content")])->save();
        return json(["status" => "success"]);
    }
}
