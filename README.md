Jitamin
========

[![Build Status](https://travis-ci.org/jitamin/jitamin.svg?branch=master)](https://travis-ci.org/jitamin/jitamin)
[![StyleCI](https://styleci.io/repos/72176201/shield?branch=master)](https://styleci.io/repos/72176201/)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jitamin/jitamin/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jitamin/jitamin/?branch=master)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Jitamin (读作/ˈdʒɪtəmɪn/) 是一款免费、开源，使用PHP语言开发的项目管理系统。Jitamin灵感来自于Vitamin，并结合了Just In Time(准时)和`敏`的拼音`min`，意指`效率`与`敏捷`是项目管理的维他命。

Jitamin (pronounce as/ˈdʒɪtəmɪn/) is a free, open source project management system developed in PHP language.


![jitamin](https://cloud.githubusercontent.com/assets/15666864/21678087/312aab60-d378-11e6-8244-56882545970c.jpeg)

## 功能特性 (Features listed in Chinese language)

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

## Features

* simple interface
* support multiply styles
* project/task management
* support list, billboard Gantt chart views
* drag and drop operations
* multiple language support, build-in English and Chinese language supports
* filter on search results
* can manage personal projects and team projects
* support tasks, sub-tasks, attachment, comments
* auto-triger actions
* visualized statistics result
* support third part integration
* support plugins

[Change Logs](https://github.com/jitamin/jitamin/blob/master/ChangeLog.md)

## Features in our TODO list

- [ ] integrate Fixhub through plugins (通过插件与Fixhub集成)
- [ ] integrate twig template engion (集成twig模板引擎)

## Installation Prerequisites

- [PHP](http://www.php.net) 5.6 or later(PHP7 is recommended)
- database, [MySQL](https://www.mysql.com) is recommended, also you can choose [PostgreSQL](http://www.postgresql.org) or[SQLite](https://www.sqlite.org)
- Dependency Manager for PHP [Composer](https://getcomposer.org) 

## Installation Manual

1. Get the jitman source code

```shell
$ git clone https://github.com/jitamin/jitamin.git
```

2. Setting the config file

```shell
$ cp config/config{.default,}.php
```
> Adjust the `config/config.php` according to your environment, especially the database setting.

3. install the PHP dependency packages

```shell
$ composer install -o --no-dev
```

4. migrate the database and initialize the database

- create database tables
```shell
vendor/bin/phinx migrate
```

- initialize database
```shell
vendor/bin/phinx seed:run
```
> For installation under Windows, you should replace the command `vendor/bin/phinx` with `vendor\robmorgan\phinx\bin\phinx.bat`.

5. Confirm that the directory bootstrap/cache and storage have write permission.

```shell
$ chmod -R 0777 bootstrap/cache
$ chmod -R 0777 storage
```
> Optional steps

```shell
$ php artisan config:cache
$ php artisan route:cache
```

6. Access the service through web browser

Open your web browser, enter the address such as http://jitamin.dev to  access the web service ：

The initial Super Administrator's user name and password are listed below:

- **username:** `admin` or `admin@admin.com`
- **password:** `admin`

## Upgrade steps

1. Fetch the latest source code

```shell
$ git fetch --all
$ git checkout latest_tag // Change the  latest_tag to the latested release git tag, such as 0.4.4
```

2. Update the dependencies

```shell
$ composer install -o --no-dev
```

3. Update the database

```shell
vendor/bin/phinx migrate
```
> For updating under Windows, you should replace the command `vendor/bin/phinx` with `vendor\robmorgan\phinx\bin\phinx.bat`.

> Optional steps

```shell
$ php artisan config:cache
$ php artisan route:cache
```

## Demo

We have a site to demostrate how Jitamin works, please visit [http://jitamin.com](http://jitamin.com):

1. You can login by Github account

> Press the button `Login with my Github Account`

2. You can either login by a local test user

- **username:** `test` or `test@test.com`
- **password:** `test123`

3. Administrator login

- **username:** `jitamin` or `jitamin@jitamin.com`
- **password:** `jitamin`

## Development

Jitamin has its own pre-compiled static resources, if you don't want to change the web frontend styles, just skip this section.

Tools：

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

