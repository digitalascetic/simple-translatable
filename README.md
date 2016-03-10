Simple Translatable
======================================

This bundle provides common backend services for eatout websites.

**Stable version**: none
**Current version**: 1.0.*\@dev


Installation
-------------

Add the following lines to your composer.json

      "require": {
        ...
        "digitalascetic/simple-translatable": "dev-master"
      }
      
In order to let composer gain access to eatout-admin repository you have to add your ssh public key
to the keys authorized to to the [repository deployments keys](https://bitbucket.org/digitalascetic/eatout-admin/admin/deploy-keys/).

 
Add the following line to your `app/AppKernel.php`:

      ...
      new DigitalAscetic\SimpleTranslatable\SimpleTranslatableBundle(),
      ...

      
Usage
-----

TODO