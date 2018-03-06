Jitamin
========

[![Build Status](https://travis-ci.org/jitamin/jitamin.svg?branch=master)](https://travis-ci.org/jitamin/jitamin)
[![StyleCI](https://styleci.io/repos/72176201/shield?branch=master)](https://styleci.io/repos/72176201/)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jitamin/jitamin/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jitamin/jitamin/?branch=master)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Jitamin (pronounced /ˈdʒɪtəmɪn/) is a free software written in PHP, intended to handle the project management over the web. 

**Jitamin** is inspired by vitamin. It also stands for JIT(Just In Time) and `Min`(The pinyin of `敏`).

[简体中文](https://github.com/jitamin/jitamin/blob/master/README-zh_CN.md)


![jitamin](http://jitamin.com/img/screenshot.png?v1)

## Features

* Get a clear visual overview of your project
* Multiple themes
* Project/task management
* Support task list, kanban and Gantt views
* Drag and drop tasks
* Multiple language support, build-in English and Chinese language supports
* Filter on search results
* Support personal projects and team projects
* Support tasks, sub-tasks, attachment, comments
* Auto-trigger actions
* Visualized statistics result
* Support third part integration
* Support plugins

[Change Logs](https://github.com/jitamin/jitamin/blob/master/ChangeLog.md)

## Requirements

There are a few things that you will need to have set up in order to run Jitamin:

- A web server: **Nginx**, **Apache** (with mod_rewrite), or **Lighttpd**
- [PHP](http://www.php.net) 5.6+ (PHP7 is recommended)
- Database: [MySQL](https://www.mysql.com) is recommended, also you can choose [PostgreSQL](http://www.postgresql.org) or[SQLite](https://www.sqlite.org)
- [Composer](https://getcomposer.org) 

## Installation

### Get the jitamin source code

```shell
$ cd /var/www
$ git clone https://github.com/jitamin/jitamin.git
$ cd jitamin
```

### Setting the config file

```shell
$ cp .env.example .env
```
> Adjust the `.env` according to your environment, especially the database settings.

### Install the PHP dependency packages

```shell
$ composer install -o --no-dev
```
### Create database,and setting env 
```shell
CREATE DATABASE jitamin;
CREATE USER 'jitamin'@'localhost' IDENTIFIED BY 'jitamin';
GRANT ALL PRIVILEGES ON jitamin.* TO 'jitamin'@'localhost' IDENTIFIED BY 'jitamin' WITH GRANT OPTION;
```
then, edit .env file, change databases,username and password.

### Migrate the database and initialize the database

- create database tables
```shell
vendor/bin/phinx migrate
```

- initialize database
```shell
vendor/bin/phinx seed:run
```
> For installation under Windows, you should replace the command `vendor/bin/phinx` with `vendor\robmorgan\phinx\bin\phinx.bat`.

### Confirm that the directory `bootstrap/cache` and `storage` have write permission

```shell
$ chmod -R 0777 bootstrap/cache
$ chmod -R 0777 storage
```
> Optional steps

```shell
$ php artisan config:cache
$ php artisan route:cache
```

### Access the service through web browser

Open your web browser, enter the address such as http://jitamin.yourdomain.com to  access the web service.
The initial Super Administrator's user name and password are listed below:

- **username:** `admin` or `admin@admin.com`
- **password:** `admin`

## Upgrade steps

### Fetch the latest source code

```shell
$ git fetch --all
$ git checkout latest_tag // Change the  latest_tag to the latested release git tag, such as 0.4.4
```

### Update the dependencies

```shell
$ composer install -o --no-dev
```

### Update the database

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

We have a site to demostrate how Jitamin works, please visit [http://jitamin.com](http://jitamin.com). You can login by either of three kinds of account below.

### Github account

> Press the button `Login with my Github Account`

### Test user

- **username:** `test` or `test@test.com`
- **password:** `test123`

### Manager

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

