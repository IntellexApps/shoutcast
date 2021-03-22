#!/bin/env php
<?php

use Intellex\SHOUTcast\Info;
use Intellex\SHOUTcast\ParseException;

require "../vendor/autoload.php";

// Print results
$verbose = !isset($argv[1]) || $argv[1] !== 'quiet';

// Success cases
$success = [
	"96,1,192,600,93,128,Intellex - Test"                                                                       =>
		[ true, 96, 93, 192, 600, 128, 'Intellex - Test' ],
	"
<!doctype html><html itemscope=\"\" itemtype=\"http://schema.org/SearchResultsPage\" lang=\"en-RS\">
	<html>
<body>
		36,1,93,200,36,64,Song,with,commas;
</body>
</html>

"                                                                                                           =>
		[ true, 36, 36, 93, 200, 64, 'Song,with,commas;' ],
	"<html><body> 7263 , 1 , 11932 , 14000 ,  5721,256 , Song , with , commas , and , spaces ;  </body></html>" =>
		[ true, 7263, 5721, 11932, 14000, 256, 'Song , with , commas , and , spaces ;' ],
	"0,0,5123,9000,0,32,"                                                                                       =>
		[ false, 0, 0, 5123, 9000, 32, null ],
	"0,0,5123,9000,0,32,null"                                                                                   =>
		[ false, 0, 0, 5123, 9000, 32, null ],
];

// Error cases
$error = [ null, "", "ON,123", "96,1,600,192,93,128", "96,1,600,192,93,128br,ERROR" ];

// URL parsing cases
$urls = [
	"http://8.8.8.8/stream"                  => "http://8.8.8.8/7.html",
	"http://231.27.1.91/stream/"             => "http://231.27.1.91/7.html",
	"http://10.0.0.1:7231/stream?os=android" => "http://10.0.0.1:7231/7.html",
	"http://127.0.1.1:5612/7.html"           => "http://127.0.1.1:5612/7.html",
	"https://18.122.0.12/"                   => "https://18.122.0.12/7.html",
	"https://4.32.231.223"                   => "https://4.32.231.223/7.html",
	"https://12.2.91.23:8080"                => "https://12.2.91.23:8080/7.html",
	"https://43.21.33.12:10001/"             => "https://43.21.33.12:10001/7.html"
];

// Execute success cases
foreach ($success as $case => $expected) {
	try {
		$info = Info::parse($case);
		if (
			$info->isOnline() === $expected[0] &&
			$info->currentListeners() === $expected[1] &&
			$info->uniqueCurrentListeners() === $expected[2] &&
			$info->peakListeners() === $expected[3] &&
			$info->maxConnections() === $expected[4] &&
			$info->quality() === $expected[5] &&
			$info->onAir() === $expected[6]
		) {
			continue;
		}
	} catch (ParseException $e) {
	}

	if ($verbose) {
		echo "FAIL!\non {$case}\n\n";
	}
	exit(1);
}

// Execute error cases
foreach ($error as $case) {
	try {
		Info::parse($case);
	} catch (ParseException $e) {
		continue;
	}

	if ($verbose) {
		echo "FAIL!\nno exception on {$case}\n\n";
	}
	exit(1);
}

// Execute URL modification
foreach ($urls as $input => $expected) {
	$result = Info::getInfoURL($input);
	if ($result === $expected) {
		continue;
	}

	if ($verbose) {
		echo "FAIL!\non info URL parsing for '{$input}', got '{$result}' instead of '{$expected}'\n\n";
	}
	exit(1);
}

if ($verbose) {
	echo "ALL TESTS PASSED!\n\n";
}
exit(0);
