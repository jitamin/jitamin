Jitamin
========

[![Build Status](https://travis-ci.org/jitamin/jitamin.svg?branch=master)](https://travis-ci.org/jitamin/jitamin)
[![StyleCI](https://styleci.io/repos/72176201/shield?branch=master)](https://styleci.io/repos/72176201/)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jitamin/jitamin/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jitamin/jitamin/?branch=master)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Jitamin (读作/ˈdʒɪtəmɪn/) 是一款免费、开源，使用PHP语言开发的项目管理系统。Jitamin灵感来自于Vitamin，并结合了Just In Time(准时)和`敏`的拼音`min`，意指`效率`与`敏捷`是项目管理的维他命。

![jitamin](https://cloud.githubusercontent.com/assets/15666864/21602058/204b5dd4-d1cb-11e6-9fc6-0570d64eae9f.png)

## 功能特性

* 简洁、美观的界面
* 可视化的任务管理
* 支持列表、看板和甘特图等任务视图
* 任务可拖拽移动
* 多语言，默认带英文和简体中文语言包
* 过滤搜索
* 可创建团队项目和个人项目
* 支持任务、子任务、附件和评论
* 动作自动触发
* 可视化的统计
* 第三方集成
* 支持插件

## 下一阶段要实现的功能

- [ ] 通过插件与Fixhub集成
- [x] 任务进度
- [x] 复杂度改进
- [x] 多主题
- [x] 支持邮箱和用户名登录
- [x] 支持Memcached缓存
- [x] 可对项目进行按赞(亦可当收藏夹使用)
- [x] 支持在线预览PDF和Log文件
- [x] 引入phinx进行数据迁移管理
- [x] 可自定义控制台默认首页
- [ ] 集成twig模板引擎

## 安装环境要求

- [PHP](http://www.php.net) 5.6或更高(推荐使用PHP7)
- 数据库, 推荐使用[MySQL](https://www.mysql.com) 或 [PostgreSQL](http://www.postgresql.org)。 当然[SQLite](https://www.sqlite.org)也可以运行。
- [Composer](https://getcomposer.org)

## 安装手册

一. 克隆代码

```shell
$ git clone https://github.com/jitamin/jitamin.git
```

二. 安装依赖包

```shell
$ composer install -o --no-dev
```

三. 设置配置文件

```shell
$ cp config/config{.default,}.php
```
> 根据实际情况修改 `config/config.php` 相关配置文件。

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

五. 确保storage目录可写。

```shell
$ chmod -R 0777 storage
```

六. 通过浏览器访问

安装完成后，请通过浏览器访问你的Jitamin网址，如：http://jitamin.dev

初始管理员的用户名和密码：

- **用户名:** `admin` or `admin@admin.com`
- **密码:** `admin`

## 升级步骤

一. 获取最新代码

```shell
$ git fetch --all
$ git checkout {latest_tag} // 请将 {latest_tag} 修改为最新的tag，比如：0.3.0
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

## 系统演示

体验Jitamin, 请访问 [http://jitamin.com](http://jitamin.com):

一. 管理员用户
- **用户名:** `jitamin` or `jitamin@jitamin.com`
- **密码:** `jitamin`

## 开发相关

Jitamin代码里自带编译后的前端静态资源。如果你不想修改前端样式，请直接忽略本环节。

工具集：

- Node.js
- Bower
- Gulp

```shell
npm install --global gulp
bower install
gulp
```

## License

Jitamin is licensed under the license of MIT.  See the LICENSE for more details.

Jitamin is a fork based on Kanboard. Kanboard is Copyright Frédéric Guillot and others.

