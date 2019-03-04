![GitHub release](https://img.shields.io/github/release/texnixe/kirby3-related.svg?maxAge=1800) ![License](https://img.shields.io/github/license/mashape/apistatus.svg) ![Kirby 3 Pluginkit](https://img.shields.io/badge/Pluginkit-YES-cca000.svg)

# Kirby Related

Kirby 3 Related is a [Kirby CMS](https://getkirby.com) plugin that lets you fetch pages or files related to the current page/file based on matching values in a given field. The resulting collection is sorted by number of matches as an indicator of relevance.

Example:
The current page has a tags field with three values (red, green, blue). You want to find all sibling pages with at least 2 matching tag values.

## Commercial Usage

This plugin is free but if you use it in a commercial project please consider

- [making a donation](https://www.paypal.me/texnixe/10) or
- [buying a Kirby license using this affiliate link](https://a.paddle.com/v2/click/1129/38380?link=1170)

## Installation

### Download

[Download the files](https://github.com/texnixe/kirby3-related/archive/master.zip) and place them inside `site/plugins/kirby-related`.

### Git Submodule
You can add the plugin as a Git submodule.

    $ cd your/project/root
    $ git submodule add https://github.com/texnixe/kirby3-related.git site/plugins/kirby-related
    $ git submodule update --init --recursive
    $ git commit -am "Add Kirby Related plugin"

Run these commands to update the plugin:

    $ cd your/project/root
    $ git submodule foreach git checkout master
    $ git submodule foreach git pull
    $ git commit -am "Update submodules"
    $ git submodule update --init --recursive


## Usage

### Related pages
```
<?php

$relatedPages = $page->related($options);

foreach($relatedPages as $p) {
  echo $p->title();
}

```

### Related files

```
<?php

$relatedImages = $image->related($options);

foreach($relatedImages as $image) {
  echo $image->filename();
}

```

### Config options

You can set the cache to true or false using the `texnixe.related.cache` option.

```
return [
  'texnixe.related.cache' => true
];
```


### Options

You can pass an array of options:

```
<?php
$relatedPages = $page->related(array(
  'searchCollection' => $page->siblings()->visible(),
  'searchField'      => 'tags',
  'matches'          => 2,
  'delimiter'        => ',',
  'languageFilter'   => false
  ));
?>
```

#### searchCollection

The pages collection to search in.
Default: `$page->siblings()`

#### searchField

The name of the field to search in.
Default: tags

#### delimiter

The delimiter that you use to separate values in a field
Default: ,

#### matches

The minimum number of values that should match.
Default: 1

#### languageFilter

Filter related items by language in a multi-language installation.
Default: false

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you encounter any problem, please [create an issue](https://github.com/texnixe/kirby3-codepen/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)


It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.

