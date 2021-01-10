# BlogFu

Absurdly simple flat file blog engine written in PHP.

If you want to see it in action, visit [my blog](https://rickellis.com/blog). Or you can look at the `example.php` file to see how the internals work.

---

## How does it work?

Really well! Just kidding.

I run my entire blog using one HTML page. If the URL contains the name of a blog post it will show it. If the URL is empty it shows a list of blog post titles. That page also shows a 404 message if you mess with the URL.

Here are the key points:

- Blog posts are saved as markdown files. Or they can be any file type.
- There is a table of contents JSON file. It contains the titles to all the blog posts, along with the date and the filename.

I manage the whole thing by hand and pull from Github when I have a new post.

Note: BlogFu does not contain a Markdown parser. I use [Parsedown](https://github.com/erusev/parsedown) which is really simple to use.

---

## Usage Example

This is a very simple example without much HTML formatting and no Markdown parser:

```php
<?php

require('Blogfu.php');

$B = new Blogfu();

if ($B->uriExists()) {

    if ($B->isValidRequest()) {

        $entry = $B->getEntry();
        echo "<p>{$entry->title}</p>";
        echo "<p>{$entry->date}</p>";
        echo "<p>{$entry->body}</p>";
    }
    else {
        echo '404';
    }
}
else {
    foreach ($B->getTitles() as $row) {
        echo "<p>";
        echo "<a href='example.php/{$row->filename}'>{$row->title}</a>";
        echo " - ";
        echo $row->date;
        echo "</p>";
    }
}
?>
```
