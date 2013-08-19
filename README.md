# Mgrid - Grid solution for PHP 5

Mgrid version 2.x generates an optimal grid for your application. This version has great 
improvements in regardless to renders, dependencies, and other features.
Please take a look at the [`changelog`][3].

Thanks, Renato Medina.

## Instalation


### 1. The library

#### 1.1. Composer

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


#### 1.2. Download

Alternatively, you can download the [`mgrid.zip`][1] file and extract it.

### 2. The Assets

Into the package you can find the assets:

    YOUR_FOLDER_VENDOR/mdn/lib/Mgrid/templates/default/mgrid-assets

Here you have 2 options:

    1. Just move the folder to the root of your application, making it accessible 
       by your url (e.g. http://www.yourdomain.com/mgrid-assets)

    2. Just create a symlink.


## PHP and Frameworks Compatibility

It can either be used with flat PHP or any Framework out there. 
Here I've tried to follow the PSR standards. Making this component as portable as possible.

Mgrid uses Twig Template Engine to render its html. 
If you not familiar with Twig, you can take a look here. Although, it's not necessary to know how Twig works to use Mgrid.

Keen to help? Join the [`github project`][2].


## Core Concepts

This grid should be able to display data sources from simple PHP arrays.
The output is in HTML. Filters, sorting, pagination are also available.


## Docs

Go to http://mgrid.mdnsolutions.com/documentation for more detailed documentation.


##About

Available on the component [`website`][5].

##Requirements

- Any flavor of PHP 5.3 or above should do
- Twig template engine 1.* version

##Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/medinadato/mgrid/issues)


##Author

Renato Medina - <medinadato@gmail.com> - <http://twitter.com/medinadato><br />
See also the list of [contributors](https://github.com/medinadato/mgrid/contributors) 
which participated in this project.


##License

Mgrid is released under the LGPL license. - see the [`LICENSE`][6] page for details


[1]: http://mgrid.mdnsolutions.com/install#download-manual
[2]: https://github.com/medinadato/mgrid/
[3]: http://mgrid.mdnsolutions.com/changelog
[4]: http://www.php-fig.org/psr/2/
[5]: http://mgrid.mdnsolutions.com/about
[6]: http://mgrid.mdnsolutions.com/license