<?php
/*

This is a quick script that fixed a need I had to cache my podcast RSS feeds because of slow 
Wordpress performance on shared hosting. If anyone else has this problem, I hope this is
helpful. 



Copyright 2024 Jeremy Dennis

Permission is hereby granted, free of charge, to any person obtaining a copy of this software 
and associated documentation files (the “Software”), to deal in the Software without 
restriction, including without limitation the rights to use, copy, modify, merge, publish, 
distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or 
substantial portions of the Software.

THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING
BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND 
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE
*/

$url='https://www.transmissionspodcast.com/category/podcasts/feed?redirect=no'; // RSS feed URL
$showprefix='transmissions'; // prefix for cache file name to easily identify it if there are multiple files.
$response = getRSS($url,$showprefix);
$sx = simplexml_load_string($response);
header('Content-type: text/xml');
echo $response;

function getRSS($url,$showprefix) {
    // cache files are created in a subdirectory named "cache" that must be writable
	$cacheFile =  __DIR__.'/cache/'. $showprefix . '_' . md5($url) . ".xml";

	echo "";
    if (file_exists($cacheFile)) {
        $cacheoutput = fopen($cacheFile, 'r');
		$cacheTime = filemtime($cacheFile);
		echo "";

        // time value here determines length of cache.  
        if ($cacheTime > strtotime('-3 hours')) {
			echo "";
			$response = file_get_contents($cacheFile);
			return $response;
        }

        // else delete cache file
        fclose($cacheoutput);
        unlink($cacheFile);
    }

	$rss = file_get_contents($url);

    $cacheoutput = fopen($cacheFile, 'a+');//w
    fwrite($cacheoutput, $rss);
    fclose($cacheoutput);
	echo "";
    return $rss;
}
?>
