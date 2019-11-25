---
SEER棋牌GM开发文档
[Author: xqj]  [Date: 2019-11-25]
---
# 第一部分: 环境部署
## 1.软件安装
```
1.系统要求: Linux系统;
2.服务器要求: CentOS 7.2及以上系统版本;
3.环境要求：apache+mysql5.6+php5.6及以上;
4.IDE: PHPstorm;
```
## 2.环境配置搭建
```
1.安装gcc或gcc++; 
2.安装并配置Apache;
3.安装mysql5.6版本;
4.安装并配置php5.6版本;
		  
```
# 第二部分：开发说明
## 1.项目结构
```
.
www  WEB部署目录（或者子目录）
├─application           应用目录
│  ├─admin              后台模块目录
│  │  ├─controller      控制器目录
│  │  ├─lang            语言包
│  │  ├─model           模型目录
│  │  ├─view            视图目录
│  │  ├─config          配置目录
│  │  └─ ...            更多类库目录    
│  │         
│  ├─api                模块目录
│  │  ├─common.php      模块函数文件
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  ├─view            视图目录
│  │  ├─config          配置目录
│  │  └─ ...            更多类库目录
│  │
│  ├─common             模块目录
│  │  ├─controller      控制器目录
│  │  │  └─Url.php      url接口公共文件
│  │  ├─model           模型目录
│  │  ├─view            视图目录
│  │  └─ ...            更多类库目录
│  │
│  ├─command.php        命令行定义文件
│  ├─config.php         配置文件
│  ├─database.php       数据库配置
│  └─tags.php           应用行为扩展定义文件
│
├─route                 路由定义目录
│  ├─route.php          路由定义
│  └─...                更多
│
├─public                WEB目录（对外访问目录）
│  ├─index.php 
│  │  └─assets
│  │     └─js
│  │        └─backend    所有的视图js数据显示文件，与application/admin/controller的逻辑文件是一致的
│  ├─index.php          入口文件
│  ├─router.php         快速测试文件
│  └─.htaccess          用于apache的重写
│
├─thinkphp              框架系统目录
│  ├─lang               语言文件目录
│  ├─library            框架类库目录
│  │  ├─think           Think类库包目录
│  │  └─traits          系统Trait目录
│  │
│  ├─tpl                系统模板目录
│  ├─base.php           基础定义文件
│  ├─convention.php     框架惯例配置文件
│  ├─helper.php         助手函数文件
│  └─logo.png           框架LOGO文件
│
├─extend                扩展类库目录
├─runtime               应用的运行时目录（可写，可定制）
├─vendor                第三方类库目录（Composer依赖库）
├─build.php             自动生成定义文件（参考）
├─composer.json         composer 定义文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
└─think                 命令行入口文件

1.在application/database.php文件中修改以下参数：
hostname:服务器的地址 (必填)
database:导入数据库的数据库名称 (必填)
username:数据库用户名 (必填)
password:数据库密码 (必填)
hostport:数据库端口号 (必填)
2、在application/config.php文件中修改以下参数
IP:与服务器接口调用IP地址设置 (必填)
IP1:跳转页面本地址的IP地址 (必填)
db_config2:mysql://数据库用户名:数据库密码@服务器IP:数据库端口号/数据库名称 # utf8 (必填)
serverException:与node服务器交互时发生错误提示的内容，可自行设置 (必填)
port:数据库端口号 (必填)
	
## 2.导入mysql数据库
1.先下载Navicat for MySQL工具
2.将seer_gm.sql文件导入到工具中

```
GM工具初始登录账号和密码：admin、admin123（该账号和密码可修改）