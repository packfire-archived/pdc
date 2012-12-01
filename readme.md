#PDC for PHP

>Smart little worker that checks on your PHP source code's class dependencies.

Packfire Dependency Checker (PDC) is a tiny tool to run and report on missing dependencies and files with missing namespace declaration in a PSR-0 compliant PHP project. 

While most coding errors are reported, with the new autoloading in PHP 5.3, errors such as:

- Class dependencies linkage breaks after file renamed or mvoed
- Files contain naming mistake in namespaces or classes
- Problematic `use` statements
- Unused `use` statements

Some of these problems don't show up in tests or even in usage until manual check uncovers it. 

What PDC does is simply to iterate through all your PHP source code and inspect the `namespace`, `use`, and class usage statements in your code. 

You can run [PDC with your Travis-CI builds](http://packfire.tumblr.com/post/34222935980/github-gist-and-travis-ci-integration) and show the PDC report behind PHPUnit. 

##Requirements

- PHP 5.3 or higher

##Usage

PDC is a Command Line Interface (CLI) tool. It does not have an graphical interface. To run PDC on your source code, simply add the path to the source code behind:

    $ pdc /path/to/src

An optional second parameter allows you to define the autoloader for PDC to perform additional checking. So for example if you use [Composer](http://getcomposer.org/), you can use `vendor/autoload.php` as your second parameter like this:

    $pdc src/ vendor/autoload.php

You can clone this repository and directly run PDC in your Travis-CI builds in the same manner by adding the following into your `.travis.yml` file:

    after_script:
      - git clone https://github.com/packfire/pdc.git
      - php pdc/pdc src

##License

PDC is licensed under the BSD 3-Clause License. See license file in repository for details.