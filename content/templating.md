<!--
order : 4
-->

## Templating

### Available template variables.

#### Site variables :

These are the global template variables, the ones that describe the website and are defined on the main configuration array located on the `index.php` file under the `$_CONFIG` array variable.


```
{$ site.title}
{$ site.description}
{$ site.keywords}
{$ site.language}
{$ site.author}
```


#### Page Variables (metadata)

These are defined when creating new content on each markdown file. They are located on the top of the document in the form of HTML comment and you can create as much as you want, they only need to be written in one line and separated by a colon. For example :

```none
title : This is the Title
my_custom_variable_01 : This is a custom variable
my_custom_variable_02 : Another custom variable
```
You can access them later using the normal template variable syntax used in mosquito: {= my_custom_variable_01}. These are the default Page template variables :

```
{$ metadata.title}
{$ metadata.description}
{$ metadata.image}
{$ metadata.tags}
{$ metadata.layout}
{$ metadata.timestamp|date}
{$ metadata.url}
```

#### Main content :

```
{$ content}
{$ menu_global}
{$ breadcrumbs}
```

#### Helper vars:

```
{$ path.url}
{$ is_404}
```
