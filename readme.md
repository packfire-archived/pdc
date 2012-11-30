#PDC for PHP

Packfire Dependency Checker (PDC) is a tiny tool to run and report on missing dependencies and files with missing namespace declaration in a PSR-0 compliant PHP project. 

While most coding errors are reported, with the new autoloading in PHP 5.3, errors such as:

- Class dependencies linkage breaks after file renamed or mvoed
- Files contain naming mistake in namespaces or classes
- Problematic `use` statements

Some of these problems don't show up in tests or even in usage until manual check uncovers it. 

You can run [PDC with your Travis-CI builds](http://packfire.tumblr.com/post/34222935980/github-gist-and-travis-ci-integration) and show the PDC report behind PHPUnit. 

##Requirements

- PHP 5.3 or higher

##Usage

PDC is a Command Line Interface (CLI) tool. It does not have an graphical interface. To run PDC on your source code, simply add the path to the source code behind:

    $ pdc /path/to/src