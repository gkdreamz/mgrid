# Mgrid - Grid solution for PHP 5

Mgrid generates for you a optimal and handy grid. This version has great 
improvements in regardless to renders, dependencies and refactoring the code.
Please take a look at the [`changelog`][1]


## Instalation

### The library

#### Composer

The recommended way to install Mgrid is [through
composer](http://getcomposer.org). Just create a `composer.json` file into the 
root of your project and add the following lines:

    {
        "require": {
            "mdn/mgrid": "dev-master"
        }
    }

then run the `php composer.phar install` command to install it. At this stage 
everything should go smooth. 


#### Download

Alternatively, you can download the [`mgrid.zip`][1] file and extract it.

### The Assets

Into the package you can find the assets:

    YOUR_FOLDER_VENDOR/mdn/lib/Mgrid/templates/default/assets

Here you have 2 options:

    1. Just move the folder to the root of your application;
    2. Just create a symlink.


## Usage

At the moment this grid is only available to go with Zend Framework 1.x. 
We are working to try to make it as universal as possible. 
Keen to help? Join the [`github project`][2].


## Core Concepts

This grid should be able to display data sources from PHP arrays and Doctrine Query Builder objects.
Rendering it in plain html table, pdf and csv. Filters and sort by columns are also 
available.


## Docs

See the `doc` directory for more detailed documentation or go to http://mgrid.mdnsolutions.com/documentation.



##About


##Requirements

- Any flavor of PHP 5.3 or above should do
- Twig template engine 1.* version

##Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/medinadato/mgrid/issues)


##PHP and Frameworks Compatibility

It can either be used with flat PHP or any Framework out there. 
Here I've tried to follow the [PSR][4] standards. Making this component as 
portable as possible. 


##Author

Renato Medina - <medinadato@gmail.com> - <http://twitter.com/medinadato><br />
See also the list of [contributors](https://github.com/medinadato/mgrid/contributors) 
which participated in this project.


##License

Mgrid is licensed under the MIT License - see the `LICENSE` file for details


[1]: http://mgrid.mdnsolutions.com/download
[2]: https://github.com/medinadato/mgrid/
[3]: http://mgrid.mdnsolutions.com/changelog
[4]: http://www.php-fig.org/psr/2/
