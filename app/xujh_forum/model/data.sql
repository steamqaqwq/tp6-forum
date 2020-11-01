-- 1803010136 创建数据库
create database xujh_forum;

-- 1803010136 创建用户表
use xujh_forum;
create table xujh_user(
	u_nick varchar(10) primary key not null,
	u_pa char(32) not null,
	u_email char(30) not null,
	u_tel varchar(15) not null
);
-- 1803010136 创建原贴信息表
create table xujh_mes(
	m_id int primary key AUTO_INCREMENT not null,
	m_title varchar(30) not null,
	m_content text not null,
	m_type text not null,
	u_nick varchar(10) not null,
	m_createat int not null,
	s_id int not null
);
-- 1803010136 mes表插入数据
insert into xujh_mes
	(m_title,m_createat,s_id,m_type,u_nick)
values
	("title1",1602680635,1,'问答','user1'),
	("title2",1602162235,1,'博客','user2'),
	("title3",1237876,1,'博客','user3');
-- 1803010136 创建回复信息表
create table xujh_res(
	r_id int primary key not null,
	r_content text not null,
	u_nick varchar(10) not null,
	r_createat int not null,
	m_id int not null
);
-- 1803010136 创建模板信息表
create table xujh_section(
	s_id int primary key AUTO_INCREMENT not null,
	s_name varchar(8) not null,
	s_remark varchar(50) not null,
	s_pic varchar(20)
);

-- 1803010136 创建用户表
create table xujh_user(
	u_nick varchar(10) primary key not null,
	u_pa char(32),
	u_email varchar(30) not null,
	u_tel varchar(15) not null,
);

-- 填充板块
insert into xujh_section
	(s_name,s_remark,s_pic)
	values
	("Laravel","Laravel",'laravel.png'),
	("Python","Python",'python.png'),
	("微信小程序","微信小程序",'wechat.png'),
	("Vue.js","Vue.js",'vuejs.png'),
	("Node.js","Node.js",'nodejs.png'),
	('产品经理','产品经理','manager.png'),
	("写作的艺术","写作的艺术",'artist.png');
	
-- 1803010136 添加一条用户记录
insert into xujh_user
	(u_nick,u_pa)
	values
	("xu",md5("123456"));

-- 1803010136 用户授予权限
create user 'read' identified by '12345678';
grant select on xujh_forum.* to 'read';

create user 'change' identified by '12345678';
grant select,update,insert on xujh_forum.* to 'change';


alter table xujh_user add u_img varchar(46) default 'default.png';
-- alter table xujh_user drop column u_img;