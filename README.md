# MakvilleStorage plugin for CakePHP

## Installation

You can install this plugin into your CakePHP application using [composer](https://getcomposer.org).

The recommended way to install composer packages is:

```
composer require makville/cakephp-storage-plugin
```

Configure AWS Bucket

```
Configure::write('aws_key', 'key');
Configure::write('aws_secret', 'secret');
Configure::write('aws_bucket', 'root bucket'); //the website e.g example.com is a good choice.
Configure::write('aws_version', '2006-03-01'); //use this like this
Configure::write('aws_region', 'region'); //e.g us-west-2
```