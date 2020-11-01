<?php
namespace app\xujh_forum\controller;
use app\BaseController;
use think\facade\Config;
use think\facade\Env;
use think\facade\Db;
use \think\facade\Filesystem;
class index extends BaseController
{
    use \liliuwei\think\jump;
    // 1803010136 2类方法 渲染门户页面
    public function index()
    {
        
        return view();
    }
    // 1803010136 2类方法 渲染帖子列表页面
    public function view()
    {
        // $this->check();
        // 查询帖子
        $mes = Db::name("mes")
            -> where("s_id",1)
            -> order("m_createat",'desc')
            -> order("m_id")
            -> field('m_id,m_title,m_type,m_createat,u_nick')
            -> select();
        // 查询模块
        $section = Db::name("section")
                -> order("s_id")
                ->field('s_name')
                ->select();
        // 打印查询结果
           // echo '<pre>';
           // print_r($mes);
        return view("",["mes"=>$mes,"section"=>$section]);
    }
     // 1803010136 2类方法 渲染帖子详情页面
    public function detail()
    {
        return view();
    }
     // 1803010136 2类方法 渲染发帖页面
    public function getSection()
    {
        // $this->check();
        $section = Db::name("section")
                ->order("s_id")
                ->field('s_name,s_pic')
                ->select();
        return $section;
    }

    public function post()
    {
        $this->check();
        $section = $this->getSection();

        return view("",["section"=>$section]);
    }
     // 1803010136 2类方法 渲染修改密码页面
    public function changePa()
    {
        return view();
    }
     // 1803010136 2类方法 渲染上传头像页面
    public function me()
    {
        $section = $this->getSection();
        return view("",["section"=>$section]);
    }
    
    // 1803010136 1类方法 处理用户发帖
    public function doPost()
    {
        $this->check();
        // 准备帖子内容
        $mes = [
            'm_title'=>input('mtitle'),
            'm_type' => input('mtype'),
            'm_content'=>input('mcontent'),
            'u_nick'=>session('name'),
            'm_createat'=>time(),
            's_id'=>input('sid')
        ];
        // print_r($mes) ;
        // 插入帖子信息
        $mesPost = Db::connect("mysqladmin")
                ->name("mes")
                ->insert($mes);

        // 判断写入成果
        if($mesPost == 1){
            // 成功
            $this->success("发帖成功!","index/view");
        }else{
            // 失败
            $this->error("发帖失败!");
        }
    }
    // 1803010136 1类方法 处理用户回帖
    public function doRes()
    {
        $this->success('回帖成功');
    }
    // 1803010136 1类方法 处理用户修改密码
    public function doChangePa()
    {
        $this->success('修改密码成功');
    }
    // 1803010136 1类方法 处理用户上传头像
    public function upMe()
    {
        $this->check();
        // 获取表单文件对象
        $file = request()->file('uimg');
        // 验证文件是否符合要求
        try {
            $info =validate(['file' => [
                'fileSize' => 102400,
                'fileExt' => 'jpg,png,jpeg',
            ]])->check(['file' => $file]);                                                                        
        } catch (\think\exception\ValidateException $e) {
            $this->error($e->getMessage());
            return;
        }
        // 文件存储到硬盘
        $saveName = Filesystem::disk("public")->putFile('upload',$file);
        echo $saveName;
        return;
        // 查询并存储旧头像
        $res =  Db::connect('mysql')
                ->name("user")
                ->where('u_nick',session("name"))
                ->find();
        $oldFilePath = $res["u_img"];

        // 获取文件的完整名字和路径
        // 格式:站点目录/public/static/..
        $fileName = app()->getRootPath().'public/static/xujh_forum/'.$saveName;
        // 获取文件保存到数据库的信息
        // 格式:20201028\25ead331ac0325b26d2d2b12276cf145.png
        $fileInfo = substr($saveName,7);
        // 判断文件是否存在,形成双分支
        if(file_exists($fileName)){
            // 成功,更新记录
            $meUpdate = Db::connect('mysqladmin')
                        ->name("user")
                        ->where('u_nick',session("name"))
                        ->update(['u_img'=>$fileInfo]);
            // 更新成功
            if ($meUpdate ==1 ) {
                // 成功,提示,跳转首页
                // 删除旧头像 
                // unlink();
                // 修改当前用户session值
                if($oldFilePath !== "default.png"){
                    unlink(app()->getRootPath().'public/static/xujh_forum/upload/'.$oldFilePath);
                }
                session("uImg",$fileInfo);
                $this->success("头像更新成功!","index");
            }
        }else{
            // 不存在文件,保存失败,返回上一层
            $this->error("头像上传失败,请重新尝试！");
        }
    }
    // 1803010136 1类方法 处理用户注销
    public function logOut()
    {
        $this->check();
        session('name',null);
        session('uImg',null);
        $this->success('注销成功','index');
    }
    // 1803010136 1类方法 检查用户登录状态
    public function check()
    {
        if(!session('name')){
            $this->error("未登录拒绝访问",'index/index');
        }
    }
}
