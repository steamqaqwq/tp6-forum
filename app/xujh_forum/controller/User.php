<?php
namespace app\xujh_forum\controller;
use app\BaseController;
use think\facade\Config;
use think\facade\Env;
use think\facade\Db;
class user extends BaseController
{
    use \liliuwei\think\jump;
    // 1803010136 2类方法 渲染注册页面
    public function reg()
    {
        $index = new Index($this->app);
        $sec =  $index->getSection();
        return view('',["section"=>$sec]);
    }
    // 1803010136 1类方法 执行注册
	public function doReg()
    {
        $user = [
            'u_nick'=>input('username'),
            'u_pa' => md5(input('password')),
            'u_email'=>input('email'),
            'u_tel'=>input('telephone')
        ];
        // 查询用户名是否重复
        $nickCheck = Db::name("user")
                    ->where("u_nick",$user["u_nick"])
                    ->find();
        if($nickCheck == null){
            $userIn = Db::connect("mysqladmin")
                    ->name("user")
                    ->insert($user);
            // 判断是否插入成功
            if($userIn == 1){
                // 成功
                $this->success('注册成功！','user/login');
            }else{
                // 失败
                $this->error("注册失败!请重新输入");
            }
            
        }else{
                $this->error("注册失败！用户名重复,已存在用户".$user["u_nick"]);
            }
    }
    // 1803010136 2类方法 渲染登录页面
    public function login()
    {
        return view();
    }
    // 1803010136 1类方法 处理用户登录信息,执行登录验证
    public function doLogin()
    {
        // 获取表单元素值
        $uNick = input("username");
        $uPa = md5(input("password"));
        // 登录验证
        $re = Db::name("user")->where('u_nick',$uNick)->where('u_pa',$uPa)->find();
        if($re == null){
            $this->error("查询结果为空!",'user/login');
        }else{
            $uImg = $re["u_img"];
            session("name",$uNick);
            session("uImg",$uImg);
            $this->success('登录成功','index/index');
        }
    }
    // 1803010136 2类方法 渲染联系我们页面
    public function contact()
    {
        return view();
    }

    public function test(){
        $arr = [
            ["u_nick"=>'aaa','u_pa'=>md5(123456)]
        ];
         $userIn = Db::connect("mysqladmin")
                    ->name("user")
                    ->insertAll($arr);
    }
}



/*$x = Config::get('database');
        dump($x);
        //提取环境变量的值 1803010136
        $forum_user =Env::get('database_forum.username');
        $forum_change =Env::get('database_forumchange.username');
        $forum_admin =Env::get('database_forumadmin.username');
        echo "第一套默认用户方案$forum_user";
        echo "<br>";
        echo "第二套连接用户方案$forum_change <br>";
        echo "第三套管理员方案$forum_admin";*/