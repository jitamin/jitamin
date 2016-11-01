Hiject
========

[![Build Status](https://travis-ci.org/Hiject/Hiject.svg?branch=master)](https://travis-ci.org/Hiject/Hiject)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Hiject 是一款免费、开源，使用PHP语言开发的项目管理系统。

![Screenshot](http://hiject.com/hiject.png)

## 功能特性

* 界面简洁、美观
* 可视化的任务管理
* 项目支持列表、看板和甘特图
* 任务可在看板间拖拽
* 多语言，目前支持英文和简体中文
* 过滤搜索
* 可创建团队项目和个人项目
* 支持任务、子任务、附件和评论
* 动作自动触发
* 可视化的统计
* 第三方集成
* 支持插件

## 下一阶段要实现的功能

- [ ] 通过插件与Fixhub集成
- [ ] 复杂度改进
- [ ] 多主题
- [ ] 集成Phinx

## 安装手册

一. 克隆代码

```shell
$ git clone https://github.com/Hiject/Hiject.git
```

二. 安装依赖包

```shell
$ composer install -o --no-dev
```

三. 设置配置文件

```shell
$ cp config/config{.default,}.php // 根据实际情况修改config.php相关配置，请注意shu
```

三. 确保storage目录可写。

```shell
$ chmod -R 0777 storage
```

## 安装环境要求

- [PHP](http://www.php.net) 5.6或更高(推荐使用PHP7)
- 数据库, 推荐使用[MySQL](https://www.mysql.com) 或 [PostgreSQL](http://www.postgresql.org)。 当然[SQLite](https://www.sqlite.org)也可以运行。
- [Composer](https://getcomposer.org)

## 系统演示

体验Hiject, 请访问 [Hiject](http://hiject.com):

- **用户名:** hiject
- **密码:** `hiject`

## 开发相关

Hiject代码里自带编译后的前端静态资源。如果你不想修改前端样式，请直接忽略本环节。

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

Hiject is licensed under the license of MIT.  See the LICENSE for more details.

Hiject is a fork based on Kanboard. Kanboard is Copyright Frédéric Guillot and others.

