# BlogFu

Absurdly simple flat file blog engine written in PHP.

If you want to see it in action, visit [my blog](https://rickellis.com/blog). You can also download the code and run the [example.php](https://github.com/rickellis/BlogFu/blob/main/example.php) file.

---

## How does it work?

Really well! Just kidding.

I run my entire blog using one HTML page. If the URL contains the name of a blog post it gets shows. If the URL is empty it shows a list of blog post titles. That page also shows a 404 if the URL has a segment that doesn't corellate to an actual post.

Blog posts are saved as markdown files (or they can be any file type). I'm including [Parsedown](https://github.com/erusev/parsedown) in the BlogFul distro for convenience. It's very simple to use.

There is a [table of contents](https://github.com/rickellis/BlogFu/blob/main/content/_toc.json) JSON file. It contains the titles and date of all the blog posts. That file can be customized to contain any additional meta data you need to associate with a blog post.

I manage the whole thing by hand and pull from Github when I have a new post.

### Why?

Why do I bother doing this by hand when there are lots of good blog engines? Mostly because I prefer my content to be saved as flat files rather than in a database. This makes my content much more portable and permanent. It's also much more light weight and minimalist. The entire BlogFu package is only about 250 lines of code vs. something like Wordpress that has 350,000 lines of code and a large database schema. BlogFu is lighning fast in comparison. It doesn't have all the features of Wordpress, of couse, but I don't need all that stuff.

---

## Usage Example

Look at the [example.php](https://github.com/rickellis/BlogFu/blob/main/example.php) page to see how BlogFu is used.

## API Reference

To instantiate the Blogful class just call:

```php
$B = new Blogfu();
```

You can optionally pass config options to the constructor if you want to change the defaults:

```php
$B = new Blogfu(array(
    'showErrors' => false,
    'baseDir'  => 'content',
    'tocFile'  => '_toc.json',
    'fileExt' => '.md'
));
```

#### Option Description

- **showErrors** (Boolean). Lets you enable error reporting. Mostly this does validation checking. This is good during development but you'll probably want to turn it off for deployment.

- **baseDir** (string). The name of the folder containing your blog files. If the folder is not in the same folder as BlogFu include the path.

- **tocFile** (string). The name of the **JSON** file containing your blog post titles, date, etc.

- **fileExt** (string). The file extension of your blog files. Since the URL only contains the name of a post and not the file extension, BlogFu needs to know what the extension is.
