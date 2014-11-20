Netgen Open Graph Bundle documentation
======================================

## Basic configuration

## Configuring content type handlers

This bundle uses semantic configuration to define how meta tags will look for specific content type. Additionally, semantic configuration is [site access aware](https://doc.ez.no/display/EZP/How+to+expose+SiteAccess+aware+configuration+for+your+bundle), so you can specify different meta tags for different site accesses.

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

What happens here is that each element in `content_type_handlers` array corresponds to one content type in your database, and for each content type, you define a list of handlers together with the meta tag name and handler parameters. Each of the handlers returns one or more meta tags (for example, `og:video` tag can have multiple subtags, like `og:video:type` or `og:video:width`) to be rendered, and the bundle collects all meta tags from all handlers and displays them.

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

This handler will return the value of `TextLine` field type. You can specify the field definition identifier as a first parameter, and optionally, a fallback value if the field is empty.

The following config:

```yaml
{ handler: field_type/ezstring, tag: 'og:title', params: ['title'] }
```

Will result in following meta tag:

```html
<meta property="og:title" content="My super cool title" />
```

where `My super cool title` is the value of `title` field.

### field_type/eztext

This handler will return the value of `TextBlock` field type. You can specify the field definition identifier as a first parameter, and optionally, a fallback value if the field is empty.

The following config:

```yaml
{ handler: field_type/eztext, tag: 'og:description', params: ['short_description'] }
```

Will result in following meta tag:

```html
<meta property="og:description" content="My super cool description" />

where `My super cool description` is the value of `short_description` field.

### field_type/ezxmltext

This handler will return the value of `XmlText` field type. You can specify the field definition identifier as a first parameter, and optionally, a fallback value if the field is empty.

The following config:

```yaml
{ handler: field_type/ezxmltext, tag: 'og:description', params: ['description'] }
```

Will result in following meta tag:

```html
<meta property="og:description" content="My super cool description" />

where `My super cool description` is the value of `description` field.

### field_type/ezimage

This handler will return the path to the image stored in `Image` field type. You can specify the field definition identifier as a first parameter, and optionally, image variation to use as a second parameter (`opengraph` variation will be used by default), and a fallback path to an image if the field is empty.

The following config:

```yaml
{ handler: field_type/ezimage, tag: 'og:image', params: ['image', 'my_variation', 'bundles/site/images/opengraph_default_image.png'] }
```

Will result in following meta tag:

```html
<meta property="og:image" content="http://mysite.com/var/ezdemo_site/storage/images/portfolio/design-and-architecture/503-44-eng-EU/Design-and-Architecture_opengraph.png" />

## Implementing your own handlers
