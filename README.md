# BlogFu

Absurdly simple flat file blog engine written in PHP.

If you want to see it in action, visit [my blog](https://rickellis.com/blog). You can also download the code and run the [example.php](https://github.com/rickellis/BlogFu/blob/main/example.php) file.

---

## How does it work?

Really well! Just kidding.

I run my entire blog using one HTML page. If the URL contains the name of a blog post it gets shown. If the URL is empty it shows a list of blog post titles. That page also shows a 404 if the URL has a segment that doesn't correlate to an actual post.

Blog posts are saved as markdown files (or they can be any file type). I'm including [Parsedown](https://github.com/erusev/parsedown) Markdown parser in the BlogFul distro for convenience. It's very simple to use.

There is a [table of contents](https://github.com/rickellis/BlogFu/blob/main/content/_toc.json) JSON file. It contains the titles and date of all the blog posts. That file can be customized to contain any additional meta data you need to associate with a blog post.

BlogFu includes a simple benchmark function so you can show the elapsed time.

I manage the whole thing by hand and pull from Github when I have a new post.

### Why?

Why do I bother doing this by hand when there are lots of good blog engines? Mostly because I prefer my content to be saved as flat files rather than in a database. This makes my content much more portable and permanent. It's also much more light-weight and minimalist. The entire BlogFu package is only about 250 lines of code vs. something like Wordpress that has 350,000 lines of code and a large database schema. BlogFu is lightning fast in comparison. It doesn't have all the features of the most popular blog packages, of course, but I don't need all that stuff.

---

## Usage Example

Look at the [example.php](https://github.com/rickellis/BlogFu/blob/main/example.php) page to see how BlogFu is used on a single page design. If you prefer to break up your blog into discreet pages that's easy to do as well.

---

### TOC File

The table of contents file contains a JSON object with the titles/date/etc. of your blog posts. Here's the basic format:

```json
{
  "first-post": {
    "title": "My first post",
    "date": "January 1st 2021"
  },
  "another-post": {
    "title": "Another blog post",
    "date": "January 2nd 2021"
  },
  "third-post": {
    "title": "This is my third post",
    "date": "January 3rd 2021"
  }
}
```

**Note:** The index of each object must correspond to the file name of your blog posts (without the file extension). In the example above, the blog post file are named `first-post.md`, `another-post.md`, and `third-post.md`.

If you need additional metadata you can add it to the JSON object and it will be available both in the `getEntry()` function and the `getTitles()` function described in the API section below.

```json
{
  "first-post": {
    "title": "My first post",
    "date": "January 1st 2021",
    "excerpt": "This is an excerpt from my post",
    "pullquote": "Here is a quote!"
  }
}
```

---

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

---

##### URI Validation

```php
$B->uriExists()
```

Returns true/false if the URI contains at least one segments. Note that this function doesn't do any validation. It just looks for the presence of a URI segment. In the example code we use this to determine whether to show the table of contents or render an actual blog post request.

---

##### Validate blog post request

```php
$B->isValidRequest()
```

Returns true/false if the URI segment correlates to an actual blog post file. In the example, we use this function to determine whether we should fetch a blog post or show a 404.

---

##### Get Blog Entry

```php
$B->getEntry()
```

Returns an object containing the blog post file data as well as the item in your TOC file. Use object syntax to access it:

```php
$entry = $B->getEntry();

echo $entry->title;
echo $entry->date;
echo $entry->body;
```

---

##### Get Titles

```php
$B->getTitles()
```

Returns an object containing all the data in your TOC file. Typically you'll loop through this object:

```php
    foreach ($B->getTitles() as $row) {
        echo "<p>";
        echo "<a href='example.php/{$row->filename}'>{$row->title}</a>";
        echo " - ";
        echo $row->date;
        echo "</p>";
    }
```

---

##### Benchmark Functions

These two functions let you set a start and end position and show the elapsed time. Here's how you'll use it:

```php
$B->mark('start');

// A bunch of stuff happens here

$B->mark('end');
echo $B->elapsedTime('start', 'end');
```

**Note:** "start" and "end" are just arbitrary markers. You can use whatever syntaxt you prefer.

## License

MIT

Copyright 2021 Rick Ellis

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
