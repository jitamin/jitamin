Hiject
========

[![Build Status](https://travis-ci.org/Hiject/Hiject.svg?branch=master)](https://travis-ci.org/Hiject/Hiject)
[![StyleCI](https://styleci.io/repos/72176201/shield?branch=master)](https://styleci.io/repos/72176201/)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Hiject/Hiject/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Hiject/Hiject/?branch=master)
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
- [x] 任务进度
- [x] 复杂度改进
- [x] 多主题
- [x] 支持邮箱和用户名登录
- [x] 支持Memcached缓存
- [x] 可对项目进行按赞(亦可当收藏夹使用)
- [x] 支持在线预览PDF和Log文件
- [ ] 集成Phinx

## 安装环境要求

- [PHP](http://www.php.net) 5.6或更高(推荐使用PHP7)
- 数据库, 推荐使用[MySQL](https://www.mysql.com) 或 [PostgreSQL](http://www.postgresql.org)。 当然[SQLite](https://www.sqlite.org)也可以运行。
- [Composer](https://getcomposer.org)

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
$ cp config/config{.default,}.php // 根据实际情况修改config.php相关配置。
```

四. 确保storage目录可写。

```shell
$ chmod -R 0777 storage
```

## 升级步骤

一. 获取最新代码

```shell
$ git fetch --all
$ git checkout {latest_tag} // 请将 latest_tag} 修改为最新的tag，比如：0.0.6
```

二. 更新依赖

```shell
$ composer install -o --no-dev
```

## 系统演示

体验Hiject, 请访问 [Hiject](http://hiject.com):

- **用户名:** `hiject` or `hiject@hiject.com`
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

