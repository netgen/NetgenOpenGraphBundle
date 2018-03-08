Netgen Open Graph Bundle changelog
==================================

## 1.3.0 (08.03.2018)

* Added `literal/url` handler which can generate the absolute URL based on current request
* Separated Twig runtime from Twig extension
* Use Twig namespaces
* Updates to tests
* PSR-4 project structure
* Dropped support for PHP 5.5

## 1.2.0 (14.12.2017)

* Dropped support for eZ Publish 5 and lower
* Removed all `*.class` parameters
* All services marked as private
* Enabled Scrutinizer service
* Updates to tests

## 1.1.5 (26.04.2017)

* PHP CS Fixer 2
* Fix deprecation in YML

## 1.1.4 (01.02.2017)

* Removed deprecated YML syntax
* Full test coverage
* Switched CS to PSR-2

## 1.1.3 (12.12.2015)

* Real fix for `E_WARNING` in meta tag collector if semantic config does not exist
* Remove deprecated unquoted service references in YML files

## 1.1.2 (13.10.2015)

* Fix `E_WARNING` in meta tag collector if semantic config does not exist

## 1.1.1 (17.08.2015)

* Make sure `ezimage` field type handler generates full image URI even when variation returns the relative URI

## 1.1 (15.05.2015)

* Implement the ability to define the array of field identifiers to be used in field type handlers
* Add the ability to silence exceptions in Twig functions based on `%kernel.debug%` parameter

## 1.0.3 (22.04.2015)

* Fix exception on pages where open graph tags are not configured (caused by fix in `1.0.2`)

## 1.0.2 (22.04.2015)

* Fix an edge case bug where some handler configuration could be duplicated

## 1.0.1 (22.04.2015)

* Remove need for specifying image alias in `ezimage` field type handler
* Fix issue with being able to add only one handler of the same type

## 1.0 (21.11.2014)

* Initial release
