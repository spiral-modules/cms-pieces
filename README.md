# Pieces Module

This module provide ability to store page pieces data in database (currently ORM only) and
access it via Web-API. Also it's compiles two versions of views: one for site editor and one for
common site user. The functionality can be sometimes useful especially with something like
[WriteAway](https://writeaway.github.io/).

## Installation

```sh
$ composer require spiral/pieces
$ ./spiral register spiral/pieces
$ ./spiral up
```

### Add Bootloader

```php
const LOAD = [
    //...
    Spiral\Pieces\PiecesBootloaders::class,
]
```

### Configure permissions
Check `app/config/modules/pieces.php` for details.

### Metadata
There are two alternatives to include metadata to your pages: "static" and "runtime". First one will
fully compile during views compilation and there will be no requests to database during page load.
Second one will not.

```html
<dark:use path="pieces/meta" as="pieces:meta"/>

<pieces:meta title="Foo" description="Bar" keywords="Baz">
  <meta name="foo" content="bar">
</pieces:meta>
```

The code above is "static" metadata. You can optionally pass some defaults: `title`, `description`,
`keywords` arguments and put custom default html (see code above).

If you need something a bit more complex than dumb static pages, then currently you need to use
"runtime" metadata. In opposite to "static" metadata you should pass `namespace`, `view` and `code`
arguments, but `title`, `description` and `keywords` are still optional.

```html
<dark:use path="pieces/runtime-meta" as="pieces:meta"/>

<?php #compile
/** @var Article $entity */
?>

<pieces:meta title="<?= $entity->title ?>" description="<?= $entity->description ?>"
             namespace="<?= $this->namespace #compile ?>" view="<?= $this->view #compile ?>"
             code="<?= $entity->id ?>">
    <meta name="foo" content="bar">
</pieces:meta>
```

It's useful to note, that both alternatives will append (in editor mode):
```php
<script>
  window.metadata = <?= json_encode($meta) ?>;
</script>
```
to help you with frontend editor setup.

If you don't like the behaviour you're always free to write your own code... And make a PR. :-)

### Pieces

Currently there's only "static" pieces.

```html
<dark:use path="pieces/piece" as="pieces:piece"/>

<pieces:piece name="sample-piece">
  Piece content.
</pieces:piece>
```

In editor mode content of the piece will be wrapped in div like this:
```html
<div data-piece="html" data-id="sample-piece">
  Piece content.
</div>
```
