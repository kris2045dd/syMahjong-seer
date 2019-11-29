---------------------------
SEER棋牌web端开发文档
[Author: xqj]  [Date: 2019-11-23]
---------------------------
# 第一部分: 环境部署
## 1.软件安装

### 1.系统要求: Linux系统;
### 2.服务器要求: CentOS 7.2及以上系统版本;
### 3.环境要求：apache+mysql5.6+php5.6及以上;
### 4.IDE: PHPstorm;

## 2.环境配置搭建

### 1.安装并配置Apache;
```
第一步：安装Apache
yum install httpd # 根据提示，输入Y安装即可成功安装
systemctl start httpd.service # 启动apache
systemctl enable httpd.service # 设置开机自动启动Apache服务
在开机自动开启Apache服务时会报错Failed to execute operation: Access denied，需要操作如下步骤：
vi /etc/sysconfig/selinux
SELINUX=disabled
:wq! # 保存退出,再输入以下两条命令即可
setenforce 0
getenforce
systemctl enable httpd.service # 设置开机自动启动Apache服务
systemctl restart httpd.service # 重启apache服务
第二步：配置Apache
vi /etc/httpd/conf/httpd.conf #编辑文件
NameVirtualHost *:80  # 找到这一行把 # 去掉
AddHandler cgi-script .cgi # 找到这一行修改为：AddHandler cgi-script .cgi .pl （允许扩展名为.pl的CGI脚本运行）
Options Indexes MultiViews FollowSymLinks # 找到这一行修改为 Options MultiViews FollowSymLinks（不在浏览器上显示树状目录结构）
ServerSignature On # 找到这一行修改为：ServerSignature Off （在错误页中不显示Apache的版本）
DirectoryIndex index.html index.html.var # 找到这一行修改为：DirectoryIndex index.html index.htm Default.html Default.htm index.php Default.php index.html.var （设置默认首页文件，增加index.php）
AllowOverride None # 找到这一行修改为：AllowOverride All （允许.htaccess）
Options Indexes FollowSymLinks # 找到这一行修改为：Options Includes ExecCGI FollowSymLinks（允许服务器执行CGI及SSI，禁止列出目录）
MaxKeepAliveRequests 100 # 找到这一行修改为：MaxKeepAliveRequests 1000 （增加同时连接数）
KeepAlive Off # 找到这一行修改为：KeepAlive On （允许程序性联机）
ServerTokens OS # 找到这一行修改为：ServerTokens Prod （在出现错误页的时候不显示服务器操作系统的名称）
:wq!# 保存退出
systemctl restart httpd.service # 重启apache服务
rm -f /etc/httpd/conf.d/welcome.conf /var/www/error/noindex.html # 删除默认测试页
```
### 2.安装mysql5.6版本并配置远程连接MySQL;
```
yum list installed | grep mysql # 需要检测系统是否自带安装mysql
yum -y remove mysql-libs.x86_64 # 如果发现有系统自带mysql，果断这么干
wget http://repo.mysql.com/mysql-community-release-el6-5.noarch.rpm # 若提示wget说没有这个安装命令，则安装wget命令：yum -y install wget
rpm -ivh mysql-community-release-el6-5.noarch.rpm # 这个rpm还不是mysql的安装文件，只是两个yum源文件，执行后，在/etc/yum.repos.d/ 这个目录下多出mysql-community-source.repo和mysql-community.repo
yum repolist all | grep mysql # 可以用yum repolist mysql这个命令查看一下是否已经有mysql可安装文件
yum install mysql-community-server # 6.安装mysql 服务器命令（一路yes）即可
systemctl start mysqld.service # 启动MySQL服务
mysql -u root # 输入这个命令即可进入MySQL数据库，刚开始安装好的MySQL5.6的密码是空，提示输入password时，直接点击键盘回车键即可，进入MySQL数据库后输入以下三条命令就可以成功修改MySQL服务登陆密码了
1.use mysql;
2.update user set password=PASSWORD("这里输入root用户密码") where User='root';
3.flush privileges;
systemctl enable mysqld.service # 设置开机自动启动mysql服务
mysql_secure_installation # mysql安全设置(系统会一路问你几个问题，看不懂复制之后翻译，基本上一路yes)即可
配置远程连接MySQL命令如下：
mysql -uroot -p密码(你刚刚设置过的那个密码) # 输入本条命令就即可进入到MySQL服务后，输入以下四条命令即可通过MySQL工具（如Navicat for MySQL）就可以远程连接MySQL服务了
use mysql;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '远程登录密码' WITH GRANT OPTION;
flush privileges;
exit;
```
### 3.安装并配置php5.6版本;
```
yum remove php.x86_64 php-cli.x86_64 php-common.x86_64 php-gd.x86_64 php-ldap.x86_64 php-mbstring.x86_64 php-mcrypt.x86_64 php-mysql.x86_64 php-pdo.x86_64 # 删除旧php包
yum install -y epel-release
wget -O /etc/yum.repos.d/epel.repo http://mirrors.aliyun.com/repo/epel-7.repo # 配置epel源，如果提示未安装wget的话，使用yum install wget命令安装后，再使用本条命令即可
rpm -ivh http://rpms.famillecollet.com/enterprise/remi-release-7.rpm # 配置remi源
yum install --enablerepo=remi --enablerepo=remi-php56 php php-opcache php-devel php-mbstring php-mcrypt php-mysqlnd php-phpunit-PHPUnit php-pecl-xdebug php-pecl-xhprof # 安装php5.6.x
yum install --enablerepo=remi --enablerepo=remi-php56 php-fpm # 安装php-fpm
systemctl restart php-fpm # 重启php服务
systemctl restart httpd.service # 重启apache服务，即可
vi /etc/php.ini # 编辑php配置文件
date.timezone = PRC # 把前面的分号去掉，改为date.timezone = PRC
magic_quotes_gpc = On # 打开magic_quotes_gpc来防止SQL注入
display_errors = Off # 关于显示错误信息：注意设为off后就没办法调试了，这里只能填Off
expose_php = Off # 禁止显示php版本的信息
disable_functions = # 列出PHP可以禁用的函数，如果某些程序需要用到这个函数，可以删除，取消禁用。
找到open_basedir修改为：;open_basedir = .:/tmp/
short_open_tag = ON # 支持php短标签
:wq! # 保存退出
systemctl enable mysqld.service #重启MySql
systemctl enable httpd.service #重启Apche
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
注意：在把整个项目上传到服务器后一定要把项目中的cache缓存目录设置为777命令，否则会报错，说你改目录下会没有权限进行读写
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
```
## 2.导入mysql数据库
### 1.先下载Navicat for MySQL工具
```
链接：https://pan.baidu.com/s/1WTOGivSceETZbcWQIVrmgA 提取码：hwjb
```
### 2.将seer_gm.sql文件导入到工具中
```
1.首先打开Navicat for MySQL连接到你的服务器
2.点击右键新建数据库名 *** ，字符集选择utf8mb4 -- UTF-8 Unicode，排序规则选择utf8mb4_0900_ai_ci
3.再双击选中你刚刚新建的数据库后，再右键选择运行sql文件，选择你seer_gm.sql文件导入即可使用
```

# 第三部分：微信入口文件
```
地址：http://服务器ip/weixin/Application/Admin/Controller/WxxController.class.php
1.文件中需要改的参数有:
  1.117行token的参数必须设置为跟微信公众平台的token一致
  2.21行key参数跟node服务器设置的key参数一致
  3.22行的iv参数跟node服务器设置的iv参数一致
  4.20行ip参数改为http://服务器的IP:node服务器端口
2.首先将weixin文件上传到服务器web目录下，如var/www/html
3.将文件中weixin/index.php/admin/Wxx.php第123、124的注释关闭掉后，再将http://服务器ip/weixin/index.php/admin/Wxx/wxPuMsg地址填到微信公众平台中，即可配置成功，配置成功后再将文件中weixin/index.php/admin/Wxx.php第123、124的注释打开即可
```

GM工具初始登录账号和密码：admin、admin123（该账号和密码可修改）
