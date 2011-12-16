<pre>
<?php

include 'class.zoo.php';

// Enter your api key here. You can get a key at zootool.com/api
$key = '';
$secret = '';

// create your zoo object
$zoo = new ZooPHP($key, $secret);

// set the output format (options are json, array or object)
$zoo->setFormat('object');

// get a list of popular items
$zoo->getPopularItems();

?>
</pre>