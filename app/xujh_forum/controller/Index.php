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
    public function view($sid = -1)
    {
        // $this->check();
        // 查询帖子
        // $mes = Db::name("mes")
        //     ->where("s_id", 1)
        //     ->order("m_createat", 'desc')
        //     ->order("m_id")
        //     ->field('m_id,m_title,m_type,m_createat,u_nick,s_id')
        //     ->select();  
        $this->checkSid($sid);
        $mes = Db::view('mes')
            ->view('user', 'u_img', 'mes.u_nick=user.u_nick')
            ->where("s_id", $sid)
            ->order("m_createat", 'desc')
            ->order("m_id")
            ->field('m_id,m_title,m_type,m_createat,s_id,u_img')
            ->select();
        // 查询模块
        $section = $this->getSection();
        // 打印查询结果
        // echo '<pre>';
        // print_r($mes);
        // $sectionCur = $section.forEach((item)=>{
        //     echo item;
        // });
        foreach ($section as $key => $value) {
            if ($value["s_id"] == $sid) {
                $curSection = $value;
            }
        }
        return view("", ["mes" => $mes, "section" => $section, "curSection" => $curSection]);
    }
    // 1803010136 2类方法 渲染帖子详情页面
    public function detail($mid = -1)
    {
        $this->checkMid($mid);
        $section = $this->getSection();
        $mes = Db::view('mes', 'm_id,m_title,m_content,m_createat,u_nick')
            ->view('user', 'u_img', 'mes.u_nick=user.u_nick')
            ->view('section', 's_name', 'mes.s_id=section.s_id')
            ->where('m_id', $mid)
            ->find();
        $res = Db::view('res', 'r_content,r_createat,m_id,u_nick')
            ->view('user', 'u_nick,u_img', 'res.u_nick=user.u_nick')
            ->view('mes', 'm_id', 'res.m_id=mes.m_id')
            ->where('mes.m_id', $mid)
            ->select();
        // print_r($res);
        return view("", ["section" => $section, "mes" => $mes, "res" => $res]);
    }
    // 1803010136 2类方法 渲染发帖页面
    public function getSection()
    {
        // $this->check();
        $section = Db::name("section")
            ->order("s_id")
            ->field('s_name,s_pic,s_id')
            ->select();
        return $section;
    }
    public function post()
    {
        $this->check();
        $section = $this->getSection();

        return view("", ["section" => $section]);
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
        return view("", ["section" => $section]);
    }

    // 1803010136 1类方法 处理用户发帖
    public function doPost()
    {
        $this->check();
        // 准备帖子内容
        $mes = [
            'm_title' => input('mtitle'),
            'm_type' => input('mtype'),
            'm_content' => input('mcontent'),
            'u_nick' => session('name'),
            'm_createat' => time(),
            's_id' => input('sid')
        ];
        // print_r($mes) ;
        // 插入帖子信息
        $mesPost = Db::connect("mysqladmin")
            ->name("mes")
            ->insert($mes);

        // 判断写入成果
        if ($mesPost == 1) {
            // 成功
            $this->success("发帖成功!", "index/view");
        } else {
            // 失败
            $this->error("发帖失败!");
        }
    }
    // 1803010136 1类方法 处理用户回帖
    public function doRes($m_id = -1)
    {
        $this->check();
        $this->checkMid($m_id);
        $res = [
            'r_content' => input('r_content'),
            'u_nick' => session('name'),
            'r_createat' => time(),
            'm_id' => $m_id
        ];
        $response = Db::connect('mysqladmin')
            ->name("res")
            ->insert($res);

        // 判断写入成果
        if ($response == 1) {
            // 成功
            $this->success("发帖成功!");
        } else {
            // 失败
            $this->error("发帖失败!");
        }
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
            $info = validate(['file' => [
                'fileSize' => 1024000,
                'fileExt' => 'jpg,png,jpeg',
            ]])->check(['file' => $file]);
        } catch (\think\exception\ValidateException $e) {
            $this->error($e->getMessage());
            return;
        }
        // 文件存储到硬盘
        $saveName = Filesystem::disk("public")->putFile('upload', $file);
        // 查询并存储旧头像
        $res =  Db::connect('mysql')
            ->name("user")
            ->where('u_nick', session("name"))
            ->find();
        $oldFilePath = $res["u_img"];

        // 获取文件的完整名字和路径
        // 格式:站点目录/public/static/..
        $fileName = app()->getRootPath() . 'public/static/xujh_forum/' . $saveName;
        // 获取文件保存到数据库的信息
        // 格式:20201028\25ead331ac0325b26d2d2b12276cf145.png
        $fileInfo = substr($saveName, 7);
        // 判断文件是否存在,形成双分支
        if (file_exists($fileName)) {
            // 成功,更新记录
            $meUpdate = Db::connect('mysqladmin')
                ->name("user")
                ->where('u_nick', session("name"))
                ->update(['u_img' => $fileInfo]);
            // 更新成功
            if ($meUpdate == 1) {
                // 成功,提示,跳转首页
                // 删除旧头像 
                // unlink();
                // 修改当前用户session值
                if ($oldFilePath !== "default.png") {
                    unlink(app()->getRootPath() . 'public/static/xujh_forum/upload/' . $oldFilePath);
                }
                session("uImg", $fileInfo);
                $this->success("头像更新成功!", "index");
            }
        } else {
            // 不存在文件,保存失败,返回上一层
            $this->error("头像上传失败,请重新尝试！");
        }
    }
    // 1803010136 1类方法 处理用户注销
    public function logOut()
    {
        $this->check();
        session('name', null);
        session('uImg', null);
        $this->success('注销成功', 'index');
    }
    // 1803010136 1类方法 检查用户登录状态
    public function check()
    {
        if (!session('name')) {
            $this->error("未登录拒绝访问", 'index/index');
        }
    }
    // 1803010136 1类方法 检查mid值是否合法
    public function checkMid($mid = -1)
    {
        // mid 为空则使用了默认值
        if ($mid == -1) {
            $this->error("错误!!", "view");
            exit();
        }
        // mid 错误 查询数据库 判断mid 是否合法
        $checkmid = Db::connect("mysql")
            // ->field("m_id")
            ->name("mes")
            ->where("m_id", $mid)
            ->find();
        if ($checkmid == null) {
            $this->error("错误!!请勿修改");
            exit();
        }
    }
    // 1803010136 1类方法 检查sid值是否合法
    public function checkSid($sid)
    {
        // mid 为空则使用了默认值
        // echo $sid;
        // return;
        if ($sid == -1) {
            $this->error("错误!!", "view?sid=1");
            exit();
        }
        // sid 错误 查询数据库 判断sid 是否合法
        $checksid = Db::connect("mysql")
            ->name("section")
            // ->field("m_id")
            ->where("s_id", $sid)
            ->find();
        if ($checksid == null) {
            $this->error("错误!!请勿修改");
            exit();
        }
    }
}
