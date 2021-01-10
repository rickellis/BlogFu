<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Blog</title>
    <style>
        body { margin: 30px; }
    </style>
</head>
<body>

<?php

require('Blogfu.php');

$B = new Blogfu();
$B->mark('start');

if ($B->uriExists()) {

    if ($B->isValidRequest()) {

        $entry = $B->getEntry();
        echo "<h1>{$entry->title}</h1>";
        echo "<h5>{$entry->date}</h5>";

        // Run the blog post through Parsedown
        require('Parsedown.php');
        $Parsedown = new Parsedown();
        echo $Parsedown->text($entry->body);
    }
    else {
        echo '404';
    }  
}
else {
    // Show a list of blog post titles
    foreach ($B->getTitles() as $row) {
        echo "<p>";
        echo "<a href='example.php/{$row->filename}'>{$row->title}</a>";
        echo " - ";
        echo $row->date;
        echo "</p>";
    }
}

$B->mark('end');
echo '<p>Elapsed Time: ';
echo $B->elapsedTime('start', 'end');
echo '</p>';
?>

</body>
</html>