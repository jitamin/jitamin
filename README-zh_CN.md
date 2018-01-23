Jitamin
========

[![Build Status](https://travis-ci.org/jitamin/jitamin.svg?branch=master)](https://travis-ci.org/jitamin/jitamin)
[![StyleCI](https://styleci.io/repos/72176201/shield?branch=master)](https://styleci.io/repos/72176201/)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jitamin/jitamin/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jitamin/jitamin/?branch=master)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Jitamin (读作/ˈdʒɪtəmɪn/) 是一款免费、开源，使用PHP语言开发的项目管理系统。Jitamin灵感来自于Vitamin，并结合了Just In Time(准时)和`敏`的拼音`min`，意指`效率`与`敏捷`是项目管理的维他命。

![jitamin](http://jitamin.com/img/screenshot.png?v1)

## 功能特性

* 简洁、美观的界面
* 支持多主题
* 可视化的任务管理
* 支持列表、看板和甘特图等任务视图
* 可拖拽式的任务操作
* 支持多语言，内置英文和简体中文语言包
* 过滤搜索
* 可创建团队项目和个人项目
* 支持任务、子任务、附件和评论
* 动作自动触发
* 可视化的统计
* 第三方集成
* 支持插件

[版本更新说明](https://github.com/jitamin/jitamin/blob/master/ChangeLog.md)

## 安装环境要求

- [PHP](http://www.php.net) 5.6或更高(推荐使用PHP7)
- 数据库, 推荐使用[MySQL](https://www.mysql.com) 或 [PostgreSQL](http://www.postgresql.org)。 当然[SQLite](https://www.sqlite.org)也可以运行。
- [Composer](https://getcomposer.org)

## 安装手册

一. 克隆代码

假设我们把jitamin部署在 /var/www

```shell
$ cd /var/www
$ git clone https://github.com/jitamin/jitamin.git jitamin
$ cd jitamin
```

二. 设置配置文件

```shell
$ cp .env.example .env
```
> 根据实际情况修改 `.env` 相关配置文件，重点关注数据库相关的设置。

三. 安装依赖包

```shell
$ composer install -o --no-dev
```

四. 安装数据库迁移和初始数据

- 创建数据表
```shell
vendor/bin/phinx migrate
```

- 安装初始数据
```shell
vendor/bin/phinx seed:run
```
> Windows环境请将上述命令中的 `vendor/bin/phinx` 替换为 `vendor\robmorgan\phinx\bin\phinx.bat`

五. 确保bootstrap/cache和storage目录可写。

```shell
$ chmod -R 0777 bootstrap/cache
$ chmod -R 0777 storage
```
> 可选步骤

```shell
$ php artisan config:cache
$ php artisan route:cache
```

六. 配置Web服务器

请将Web服务器的根目录指向 `public/`, 请参考 [examples/](/examples) 下的相关配置文件，里面包含 Apache和Nginx的配置范例。

> 注意: `examples/` 提供的仅仅是范例，并不能保证直接拷贝就能使用，需要根据实际情况进行相关配置调整。

七. 通过浏览器访问

安装完成后，请通过浏览器访问你的Jitamin网址，如：http://jitamin.yourdomain.com

初始管理员的用户名和密码：

- **用户名:** `admin` or `admin@admin.com`
- **密码:** `admin`

## 升级步骤

一. 获取最新代码

```shell
$ git fetch --all
$ git checkout latest_tag // 请将 latest_tag 修改为最新的tag，比如：0.4.4
```

二. 更新依赖

```shell
$ composer install -o --no-dev
```

三. 更新数据表

```shell
vendor/bin/phinx migrate
```
> Windows环境请将上述命令中的 `vendor/bin/phinx` 替换为 `vendor\robmorgan\phinx\bin\phinx.bat`

> 可选步骤

```shell
$ php artisan config:cache
$ php artisan route:cache
```

## 系统演示

体验Jitamin, 请访问 [http://jitamin.com](http://jitamin.com):

一. 使用Github账号

> 请点击登录页下方的 `Login with my Github Account`

二. 普通用户

- **用户名:** `test` or `test@test.com`
- **密码:** `test123`

三. 管理员用户

- **用户名:** `jitamin` or `jitamin@jitamin.com`
- **密码:** `jitamin`

## 开发相关

Jitamin代码里自带编译后的前端静态资源。如果你不想修改前端样式，请直接忽略本环节。

工具集：

- Node.js
- Bower
- Gulp

```shell
yarn install || npm install
bower install
gulp
```

## License

Jitamin is licensed under the license of MIT.  See the LICENSE for more details.

Jitamin is a fork based on Kanboard. Kanboard is Copyright Frédéric Guillot and others.
