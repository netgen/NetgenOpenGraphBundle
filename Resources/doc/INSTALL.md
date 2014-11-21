Netgen Open Graph Bundle installation instructions
==================================================

Requirements
------------

* eZ Publish 5.4+ / eZ Publish Community Project 2014.11+

Installation steps
------------------

### Use Composer

Installation of this bundle is pretty straightforward.

Add the following to your composer.json and run `php composer.phar update netgen/open-graph-bundle`
to refresh dependencies:

```json
"require": {
    "netgen/open-graph-bundle": "~1.0"
}
```

### Activate the bundle

Activate the bundle in `ezpublish\EzPublishKernel.php` file.

```php
use Netgen\Bundle\OpenGraphBundle\NetgenOpenGraphBundle;

...

public function registerBundles()
{
   $bundles = array(
       new FrameworkBundle(),
       ...
       new NetgenOpenGraphBundle()
   );

   ...
}
```

### Use the bundle

Add the following in your pagelayout template to output the Open Graph meta tags:

```twig
{% if content is defined %}
    {{ render_netgen_open_graph( content ) }}
{% endif %}
```

Alternatively, you can use `get_netgen_open_graph( content )` to just return the tags
and render them manually, for example:

```twig
{% if content is defined %}
    {% set metaTags = get_netgen_open_graph( content ) %}

    {% for metaTag in metaTags %}
        <meta property="{{ metaTag.tagName|trim }}" content="{{ metaTag.tagValue|trim }}" />
    {% endfor %}
{% endif %}
```
