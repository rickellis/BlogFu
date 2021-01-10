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