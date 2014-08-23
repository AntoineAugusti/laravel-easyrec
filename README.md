Laravel easyrec
===============


[![Build Status](https://img.shields.io/travis/AntoineAugusti/laravel-easyrec/master.svg?style=flat)](https://travis-ci.org/AntoineAugusti/laravel-easyrec)
[![Software License](https://img.shields.io/badge/license-Apache%202.0-brightgreen.svg?style=flat)](LICENSE.md)
[![Latest Version](https://img.shields.io/github/release/AntoineAugusti/laravel-easyrec.svg?style=flat)](https://github.com/AntoineAugusti/laravel-easyrec/releases)

## What is easyrec?
easyrec is an open source recommendation engine system that provides personalized recommendations using a RESTful API.

## The recommendation engine server
You can use the server and call the associated RESTful API maintained by the easyrec team or download easyrec and call the API on one of your servers.

For additional information, take a look at the [easyrec website](http://easyrec.org).

#### Use easyrec with the server maintained by the team
This is the ready-to-go solution. You may want to use this if you don't want to configure another server dedicated to easyrec.

- Create a easyrec account: http://easyrec.org/register
- Open up your mailbox and activate your account
- Create a new Tenant in your dashboard
- Fill your API key and your Tenant ID in the configuration file

#### Configure your own easyrec server
Take a look at the [easyrec installation guide](http://easyrec.sourceforge.net/wiki/index.php?title=Installation_Guide).

## Installation

[PHP](https://php.net) 5.4+ or [HHVM](http://hhvm.com) 3.2+, and [Composer](https://getcomposer.org) are required.

To get the latest version of Laravel easyrec, simply require `"antoineaugusti/laravel-easyrec": "~1.0"` in your `composer.json` file. You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once Laravel easyrec is installed, you need to register the service provider. Open up `app/config/app.php` and add the following to the `providers` key.

* `'Antoineaugusti\LaravelEasyrec\LaravelEasyrecServiceProvider'`

You can register the SentimentAnalysis facade in the `aliases` key of your `app/config/app.php` file if you like.

* `'Easyrec' => 'Antoineaugusti\LaravelEasyrec\Facades\LaravelEasyrec'`

You need to publish the configuration file by running the following command:
```bash
$ php artisan config:publish antoineaugusti/laravel-easyrec
```