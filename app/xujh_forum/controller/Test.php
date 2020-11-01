<?php
namespace app\xujh_forum\controller;

use app\BaseController;
use think\facade\Config;
use think\facade\Env;
use think\facade\Db;

class test extends BaseController
{
	use \liliuwei\think\jump;
    public function showChar()
    {
        $x = Config::get('database');
        dump($x);
        //提取环境变量的值
        $forum_user =Env::get('database_forum.username');
        $forum_change =Env::get('database_forumchange.username');
        $forum_admin =Env::get('database_forumadmin.username');
        echo "第一套默认用户方案$forum_user";
        echo "<br>";
        echo "第二套连接用户方案$forum_change <br>";
        echo "第三套管理员方案$forum_admin";
        $result = Db::name('user')->find();
        echo "<br>";
        var_dump($result);
    }
    public function showPage()
    {
        return view('',['unick'=>'许佳洪']);
    }
    public function jumpTest()
    {

    	// return $this->result(['username'=>'xujh','sex'=>'nan']);
    	// return view('',['unick'=>'许佳洪']);
    	// return $this->success('这是testJump方法传递的信息','xujh_forum/test/showpage1');
        // return $this->success('登录成功！即将跳转到showpage页面','xujh_forum/test/showpage');
        return $this->error('登录失败!请稍后再尝试。');
       // }
   }
}
