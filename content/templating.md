<!--
order : 4
-->

## Templating

### Available template variables.

- Site variables:

```
{= site.title}
{= site.description}
{= site.keywords}
{= site.language}
{= site.author}
```
- Page Variables (metadata)

```
{= metadata.title}
{= metadata.description}
{= metadata.image}
{= metadata.tags}
{= metadata.layout}
{= metadata.timestamp|date}
{= metadata.url}
```

- Main content :

```
{= content|raw}
{= menu_global|raw}
{= breadcrumbs|raw}
```

- Helper vars:

```
{= path.url}
{= is_404}
```
