﻿# 安装说明

## 系统功能说明
1. 系统支持整合多系统的用户中心，实现统一认证，单点登录。
2. 系统支持主题模板，在设计初期就使用了类似MVC架构的设计思路。
3. 系统支持同域（如 www.dingstudio.cn/blog 和 www.dingstudio.cn/bbs 这两个web应用就属于同域）、同父域（如 blog.dingstudio.cn 和 bbs.dingstudio.cn 这两个web应用就属于同父域）、跨父域（这个就不举例了，反正只要可以通过web方式访问就行）的统一认证。（P.S.同域和同父域的实现非常简单，一般只需要在需要整合的站点挂接一个简单的SSOClient插件就可以了。跨父域模式目前处于内测阶段，暂不开放）
4. 系统允许通过开发者自定义拦截器的方法，实现对需要整合站点原有自带登录/注册/密码重置功能入口的彻底屏蔽，从而防止站点出现用户数据混乱。（此功能已经通过测试的系统有：Discuz、WordPress、ZBlogPHP、ZBlogASP、DEDECMS、PHPCMS、Typecho等常见系统）
5. 系统支持与Android端配合实现联合快速认证（二维码扫码登录），以及和各大社交、新闻媒体网站的OAuth 2.0机制配合，实现互联快速登录。（由于Android APP存在部分未知问题，该功能预计会和跨父域功能在下一版本一并发布）
6. 提供Ajax、Iframe登录支持，以及允许通过Ajax接口在已登录后查询用户信息。
7. 提供简单的CSRF防护机制，Demo代码中允许所有域名请求Ajax接口，但是需要在POST请求时携带有效的cors_domain，并赋于当前请求的域。如果是SSO内部请求可以不提供！
8. 忘了说了，还支持用户注册时与第三代支持人工智能的极验验证Geetest配合协同验证用户行为。当然你也可以通过修改源代码将其与登录/密码重置模块互相整合！

## 重要说明
为了顺利启动，请务必将sso系统安装于web根目录下的sso子目录！本系统由于历史遗留问题，默认必须放置sso目录，如果需要调整放置位置，请手动修改被整合站点的拦截器代码以完成部署。
拦截器请到私有仓库 http://git.dingstudio.cn/DavidDing 下载。

## 数据库安装说明

``` sql
create database ssobase;//此语句可以自定义，数据库名称请在config.php中进行修改。

flush privileges;

use ssobase;
create table users (
id int auto_increment primary key,
username varchar(100),
password varchar(100),
usertoken varchar(100),
email varchar(100),
lastIP longtext NULL,
lastOPTime longtext NULL
)default charset=utf8;

flush privileges;
```

## 服务器配置说明（可选）
> 关于nginx伪静态配置：
Bcloud_Nginx_User.Conf 文件为Nginx服务器配置文件。因为作者在用百度BCH虚拟主机，所以文件名有所不同。具体语法与Nginx原生相同！

> 当然，如果配置了伪静态，那么SSO的一些文件也需要稍作改动。如重定向时的URLPath，找回密码邮件投递的URL拼接规则等等。

