<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;

class Home extends Controller
{
    public function _initialize()
    {
        if (!\think\Session::has('name') || !\think\Session::has('isAdmin')) {
            $this->redirect(url("Index/index"));
        }
    }

    public function logout()
    {
        \think\Session::clear();
        $this->redirect(url("Index/index"));
    }

    public function index()
    {
        return view();
    }
    public function changePwd()
    {
        return view();
    }
    public function updatePassword(Request $req)
    {
        $old = $req->post("old");
        $newp = $req->post("new");        
        $admin = new \app\admin\model\Admin;
        $data = $admin->get(['name' => \think\Session::get('name')]);
        if($data==NULL){
            return json(["status"=>"failed"]);
        }else{
            if($data->password !=  $old ){
                return json(["status"=>"old error"]);
            }else{
                $data->password = $newp;
                $data->save();
                return json(["status"=>"success"]);
            }
        }
    }

}
