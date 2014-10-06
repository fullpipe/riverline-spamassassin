# README

## What is Riverline\SpamAssassin

``Riverline\SpamAssassin`` is a simple lib to get SpamAssassin score and report for an Email.

## Requirements

* PHP 5.3
* Guzzle 3.*

## Installation

Add to your `composer.json`:
```json
{
   "require": {
        ...
        "fullpipe/spamassassin": "dev-master"
        ...
    }
}
```

## Usage

Currently, only one provider is available : ``PostmarkWebservice``.

It use the Postmark free Spamcheck webservice available here :
http://spamcheck.postmarkapp.com/doc

```php
<?php

use Riverline\SpamAssassin\PostmarkWebservice

$spamAssassin = new PostmarkWebservice($rawEmail, true);
echo $spamAssassin->getScore();
echo $spamAssassin->getReport();
var_dump($spamAssassin->getReportAsArray());
```
