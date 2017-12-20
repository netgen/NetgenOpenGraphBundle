Netgen Open Graph Bundle installation instructions
==================================================

Requirements
------------

* eZ Platform 1.0+

Installation steps
------------------

### Use Composer

Run the following command from your project root to install the bundle:

```bash
$ composer require netgen/open-graph-bundle
```

### Activate the bundle

Activate the `Netgen\Bundle\OpenGraphBundle\NetgenOpenGraphBundle` bundle in `app/AppKernel.php` file:

### Use the bundle

Add the following in your pagelayout template to output the Open Graph meta tags:

```twig
{% if content is defined %}
    {{ render_netgen_open_graph(content) }}
{% endif %}
```

Alternatively, you can use `get_netgen_open_graph(content)` to just return the tags
and render them manually, for example:

```twig
{% if content is defined %}
    {% set meta_tags = get_netgen_open_graph(content) %}

    {% for meta_tag in meta_tags %}
        <meta property="{{ meta_tag.tagName|trim }}" content="{{ meta_tag.tagValue|trim }}" />
    {% endfor %}
{% endif %}
```
