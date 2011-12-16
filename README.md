# AlfredZoo

## Overview

**AlfredZoo** is an extension for [AlfredApp](http://www.alfredapp.com/) that lets you post bookmarks to [Zootool](http://zootool.com/).

## Usage

### Setup

Open `~/Library/Application\ Support/Alfred/extensions/scripts/Zootool/script.php` and update the `$username` and `$password` fields

### Adding a bookmark

`z <url> <title>` (title can't do spaces right now)

example: `z www.google.com Google`

##Todo:
* Finish this README file
* Make encoded settings to work
* Create setup functionality like [AlfredTweet](http://jdfwarrior.tumblr.com/post/12598255041/alfredtweet)
* Figure out how to parse XML using [simplexml](http://www.php.net/manual/en/simplexml.requirements.php)
* Make extension deployable
* Make an icon


### Resources:
* Adam Hopkins' [ZooPHP](http://adamhopkinson.co.uk/blog/2010/04/12/zoophp-a-php-wrapper-for-zootool/)
* Andy Wenk's [ZooGatePHP](https://github.com/andywenk/ZootoolGatePHP)
* [PHP Encoder](http://www.myphpscripts.net/tutorial.php?id=9)
* [Zootool API](http://zootool.com/api/docs/general)