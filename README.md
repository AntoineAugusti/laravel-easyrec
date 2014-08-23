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

## Usage
### Actions
The following variables are common to the actions methods.

##### Required parameters
- `$itemid`: An item ID to identify an item on your website. Eg: "POST42"
- `$itemdescription`: An item description that is displayed when showing recommendations on your website.
- `$itemurl`: An item URL that links to the item page. Please give an absolute path.
- `$sessionid`: A session ID of a user. If not given, will try to guess with the PHP function `session_id()`

##### Optional parameters
- `$userid`: A user ID.
- `$itemimageurl`: An optional item image URL that links to an imagine of the item. Please give an absolute path.
- `$actiontime`: An action time parameter that overwrites the current timestamp of the action. The parameter has the format "dd_MM_yyyy_HH_mm_ss".
- `$itemtype`: An item type that denotes the type of the item (`IMAGE`, `BOOK` etc.). If not supplied, the default value `ITEM` will be used.

#### View
This action should be raised if a user views an item.
##### Function signature
`view($itemid, $itemdescription, $itemurl, $userid = null, $itemimageurl = null, $actiontime = null, $itemtype = null, $sessionid = null)`

##### Parameters
Non-null variables in the function signature are required.

##### Example response.
The response is displayed here as JSON but will be returned as a PHP array.
```json
{
	"action": "view",
	"tenantid": "EASYREC_DEMO",
	"userid": "24EH1723322222A3",
	"sessionid": "F3D4E3BE31EE3FA069F5434DB7EC2E34",
	"item": {
	  "id": "42",
	  "itemType": "ITEM",
	  "description": "Fatboy Slim - The Rockafeller Skank",
	  "url": "/item/fatboyslim"
	}
}
```

#### Buy
This action should be raised if a user buys an item.
##### Function signature
`buy($itemid, $itemdescription, $itemurl, $userid = null, $itemimageurl = null, $actiontime = null, $itemtype = null, $sessionid = null)`

##### Parameters
Non-null variables in the function signature are required.

##### Example response.
The response is displayed here as JSON but will be returned as a PHP array.
```json
{
	"tenantid": "EASYREC_DEMO",
	"action": "buy",
	"userid": "24EH1723322222A3",
	"sessionid": "F3D4E3BE31EE3FA069F5434DB7EC2E34",
	"item": {
	  "id": "42",
	  "type": "ITEM",
	  "description": "Fatboy Slim - The Rockafeller Skank",
	  "url": "/item/fatboyslim"
	}
}
```

#### Rate
This action should be raised if a user rates an item.
##### Function signature
`rate($itemid, $ratingvalue, $itemdescription, $itemurl, $userid = null, $itemimageurl = null, $actiontime = null, $itemtype = null, $sessionid = null)`

##### Parameters
Non-null variables in the function signature are required. The rating value is an additional parameter.
- `$ratingvalue`: the rating value of the item. Must be an integer in the range from 1 to 10.

##### Example response.
The response is displayed here as JSON but will be returned as a PHP array.
```json
{
	"tenantid": "rate",
	"action": "rate",
	"userid": "24EH1723322222A3",
	"sessionid": "F3D4E3BE31EE3FA069F5434DB7EC2E34",
	"item": {
	  "id": "42",
	  "type": "ITEM",
	  "description": "Fatboy Slim - The Rockafeller Skank",
	  "ratingValue": "10",
	  "url": "/item/fatboyslim"
	}
}
```