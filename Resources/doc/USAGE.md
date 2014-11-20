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
                    - { handler: literal/text, params: ['og:type', 'article'] }
                    - { handler: literal/text, params: ['og:site_name', 'My cool site'] }
                    - { handler: field_type/ezstring, params: ['og:title', 'title'] }
                    - { handler: field_type/ezimage, params: ['og:image', 'image', 'opengraph'] }
                article:
                    - { handler: literal/text, params: ['og:type', 'article'] }
                    - { handler: literal/text, params: ['og:site_name', 'My cool site'] }
                    - { handler: field_type/ezstring, params: ['og:title', 'short_title'] }
                    - { handler: field_type/ezxmltext, params: ['og:description', 'short_intro'] }
                    - { handler: field_type/ezimage, params: ['og:image', 'line_image', 'opengraph'] }
```

What happens here is that each element in `content_type_handlers` array corresponds to one content type in your database, and for each content type, you define a list of handlers together with their parameters. Each of the handlers returns one or more meta tags to be rendered, and the bundle collects all meta tags from all handlers and displays them.

For the list of available handlers and available parameters, see below.

## Builtin handlers

## Implementing your own handlers
