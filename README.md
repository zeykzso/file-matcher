Oro File Inventor
==========
This project is a proof of concept for an application that is used to search for files by content.

##### requires php >= 5.6

It contains two bundles:

## OroInventorBundle
This bundle exposes a service "oro_file_inventor" which can be used to search for files by content.
 The bundle needs a basic configuration:

```yaml
oro_file_inventor:
    root_search_folders:
        - /path/to/any/folder/src/Oro/FileInventorBundle/Tests/ExampleFileRepository
    default_search_engine: oro_file_inventor.symfony_finder
```

The `root_search_folders` contains an array of folders to look in, if a path starts with '/'
it is considered to be an absolute path, otherwise it should be a relative path from the project root.

The bundle supports integration with any file search engines, the default one uses
the [Symfony Finder Component](http://symfony.com/doc/current/components/finder.html)

To create a new search engine and register it for the inventor service, create a new symfony service,
tag it with `file_inventor_search_engine` and give an unique alias:
```yaml
    oro_file_inventor.any_search_engine_service:
        class: Oro\FileInventorBundle\Inventor\AnySearchEngineNamespace\AnySearchEngine
        arguments: [@any_deps]
        tags:
            - {name: file_inventor_search_engine, alias: any_search_engine }
```
The class `AnySearchEngine` must implement `FileSearchEngineInterface` with it's methods:
`searchString`, `searchRegex`, `supportsRegex`.

Then, when using the search functionality, specify the alias as argument 3 for the inventor search function:
```php
$container->get('oro_file_inventor')->search($searchString, $isRegex, $searchEngineAlias);
```
The `$isRegex=true` flag means the service will call `searchRegex` instead of `searchString`.

There are two example search folders which are containing some sample text files, they are used both in the
proof of concept frontend UI and functional tests.

## AppBundle

This bundle contains a homepage which can be used to search for files in the example directories.
Just start the symfony's build-in server, and navigating to `http://127.0.0.1:8000`
will display the search page.
