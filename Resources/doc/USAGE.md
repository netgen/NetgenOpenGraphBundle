Netgen Open Graph Bundle documentation
======================================

## Basic configuration

## Configuring content type handlers

This bundle uses semantic configuration to define how meta tags will look for specific content type. Additionally,
semantic configuration is [site access aware][1], so you can specify different meta tags for different content types,
per site access.

To configure meta tags for your content types, you can use semantic config similar to the following:

```yaml
netgen_open_graph:
    system:
        frontend_group:
            content_type_handlers:
                blog_post:
                    - { handler: literal/text, tag: 'og:type', params: ['article'] }
                    - { handler: literal/text, tag: 'og:site_name', params: ['My cool site'] }
                    - { handler: field_type/ezstring, tag: 'og:title', params: ['title'] }
                    - { handler: field_type/ezimage, tag: 'og:image', params: ['image', 'opengraph'] }
                article:
                    - { handler: literal/text, tag: 'og:type', params: ['article'] }
                    - { handler: literal/text, tag: 'og:site_name', params: ['My cool site'] }
                    - { handler: field_type/ezstring, tag: 'og:title', params: ['short_title'] }
                    - { handler: field_type/ezxmltext, tag: 'og:description', params: ['short_intro'] }
                    - { handler: field_type/ezimage, tag: 'og:image', params: ['line_image', 'opengraph'] }
```

What happens here is that each element in `content_type_handlers` array corresponds to one content type in your
database, and for each content type, you define a list of handlers together with the meta tag name and handler
parameters. Each of the handlers returns one or more meta tags (for example, `og:video` tag can have multiple
subtags, like `og:video:type` or `og:video:width`) to be rendered, and the bundle collects all meta tags from
all handlers and displays them.

For the list of available handlers and available parameters, see below.

## Built in handlers

### literal/text

This handler will simply return the text you specify in parameters.

The following config:

```yaml
{ handler: literal/text, tag: 'og:site_name', params: ['My super cool site'] }
```

Will result in following meta tag:

```html
<meta property="og:site_name" content="My super cool site" />
```

### field_type/ezstring

This handler will return the value of `TextLine` field type. You can specify the field definition identifier
as a first parameter, and optionally, a fallback value if the field is empty.

The following config:

```yaml
{ handler: field_type/ezstring, tag: 'og:title', params: ['title', 'My fallback title'] }
```

Will result in following meta tag:

```html
<meta property="og:title" content="My super cool title" />
```

where `My super cool title` is the value of `title` field.

In cases where `title` field is empty, meta tag will look like this:

```html
<meta property="og:title" content="My fallback title" />
```

### field_type/eztext

This handler will return the value of `TextBlock` field type. You can specify the field definition identifier
as a first parameter, and optionally, a fallback value if the field is empty.

The following config:

```yaml
{ handler: field_type/eztext, tag: 'og:description', params: ['short_description', 'My fallback description'] }
```

Will result in following meta tag:

```html
<meta property="og:description" content="My super cool description" />
```

where `My super cool description` is the value of `short_description` field.

In cases where `short_description` field is empty, meta tag will look like this:

```html
<meta property="og:description" content="My fallback description" />
```

### field_type/ezxmltext

This handler will return the value of `XmlText` field type. You can specify the field definition identifier
as a first parameter, and optionally, a fallback value if the field is empty.

The following config:

```yaml
{ handler: field_type/ezxmltext, tag: 'og:description', params: ['description', 'My fallback description'] }
```

Will result in following meta tag:

```html
<meta property="og:description" content="My super cool description" />
```

where `My super cool description` is the value of `description` field.

In cases where `description` field is empty, meta tag will look like this:

```html
<meta property="og:description" content="My fallback description" />
```

### field_type/ezimage

This handler will return the path to the image stored in `Image` field type. You can specify the field definition
identifier as a first parameter, and optionally, image variation to use as a second parameter (`opengraph` variation
will be used by default), and a fallback path to an image if the field is empty.

The following config:

```yaml
{ handler: field_type/ezimage, tag: 'og:image', params: ['image', 'my_variation', 'bundles/site/images/opengraph_default_image.png'] }
```

Will result in following meta tag:

```html
<meta property="og:image" content="http://mysite.com/var/ezdemo_site/storage/images/portfolio/design-and-architecture/503-44-eng-EU/Design-and-Architecture_opengraph.png" />
```

In cases where `image` field is empty, meta tag will look like this:

```html
<meta property="og:image" content="http://mysite.com/bundles/site/images/opengraph_default_image.png" />
```

## Implementing your own handlers

### Generic handlers

If provided handlers are not enough for you, you can implement your own. Handlers are full blown services,
so you can require any dependencies you need. To properly define the handler, you need to implement
`Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface` interface, which defines one method `getMetaTags`.

However, implementing this interface won't give you access to the `Content` object by default. In some cases
this will be enough, but for the most of the use cases, you will want to have access to it. In that case, you
need to also implement `Netgen\Bundle\OpenGraphBundle\Handler\ContentAware` interface, which will give you access
to the `Content` object.

Alternatively, you can extend an abstract `Netgen\Bundle\OpenGraphBundle\Handler\Handler` class, which already
implements both of the interfaces (and provides the default implementation of `setContent` method from `ContentAware`),
so you only need to write your own `getMetaTags` method from `HandlerInterface`:

```php
<?php

namespace Vendor\Bundle\SiteBundle\OpenGraphHandler;

use Netgen\Bundle\OpenGraphBundle\Handler\Handler;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;

class MyCustomHandler extends Handler
{
    /**
     * Returns the array of meta tags
     *
     * @param string $tagName
     * @param array $params
     *
     * @return \Netgen\Bundle\OpenGraphBundle\MetaTag\Item[]
     */
    public function getMetaTags( $tagName, array $params = array() )
    {
        $tagValue = ....;

        return array(
            new Item( $tagName, $tagValue )
        );
    }
}
```

### Field type handlers

One special category of meta tag handlers are field type handlers. The bundle provides couple of those
(for `TextLine`, `TextBlock`, `XmlText` and `Image` field types), and of course, you can implement your own.

The process is similar when implementing generic handlers, but this time around, you need to extend the provided
`Netgen\Bundle\OpenGraphBundle\Handler\FieldType\Handler` abstract handler. This handler will give you access to
`Content` and `Field` objects with which you can generate meta tags. The only requirement is that the first parameter
always needs to be the identifier of the field to use. 

In your handler, you need to at least implement `supports()` method, which defines which field type your handler
supports, for example:

```php
/**
 * Returns if this field type handler supports current field
 *
 * @param \eZ\Publish\API\Repository\Values\Content\Field $field
 *
 * @return bool
 */
protected function supports( Field $field )
{
    return $field->value instanceof \Vendor\Bundle\MyBundle\MyCustomFieldType\Value;
}
```

As for getting the field value for the meta tag, abstract handler has a default and trivial implementation,
which will cast the field value to string. If you require something more complicated than that, you can override
the method `getFieldValue()`. For example `XmlText` field type handler has the following implementation:

```php
/**
 * Returns the field value, converted to string
 *
 * @param \eZ\Publish\API\Repository\Values\Content\Field $field
 * @param string $tagName
 * @param array $params
 *
 * @return string
 */
protected function getFieldValue( Field $field, $tagName, array $params = array() )
{
    if ( !$this->fieldHelper->isFieldEmpty( $this->content, $params[0] ) )
    {
        return trim( str_replace( "\n", " ", strip_tags( $field->value->xml->saveXML() ) ) );
    }
    else if ( !empty( $params[1] ) )
    {
        return (string)$params[1];
    }

    return '';
}
```

[1]: https://doc.ez.no/display/EZP/How+to+expose+SiteAccess+aware+configuration+for+your+bundle
