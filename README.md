# Simple parser for the SHOUTcast stream info

### Why

SHOUTcast stream info is in the most ridiculous info API we have ever seen. Period.

So instead of hurting our brains every time we encounter one - we have built this parser. All for a simple 7-element comma-separated one-liner :)

##### We "love" it because
 - The info is located on `7.html` page **(seems random)**
 - The info is actually one-line CSV (we like that), but it is wrapped in HTML **(WT#!)**
 - The ordering of elements in the info is **completely random**

## Anyway, usage of this library

The easiest way is to supply the SHOUTcast stream URL, and then use the retrieved results.

**Note:** Behind the scenes, the info is retrieved over standard HTTP request.

```php
<?php

// Load the info
$streamURL = "https://8.8.8.8:8080/stream";
$streamInfo = \Intellex\SHOUTcast\Info::parse($streamURL);

// Use the info
$streamInfo->isOnline();               // True if the stream is running, false otherwise 
$streamInfo->currentListeners();       // The number of currently active listeners
$streamInfo->uniqueCurrentListeners(); // The number of currently connected unique clients
$streamInfo->peakListeners();          // The maximum number of simultaneous listeners ever
$streamInfo->maxConnections();         // The maximum number of connections supported by this stream
$streamInfo->quality();                // The quality of the stream, as bitrate
$streamInfo->onAir();                  // The name of the current song or show (can be null)
```

Alternatively you can manually load the raw SHOUTcast info and only ise this library to parse it:

```php
<?php
$rawInfo = file_get_contents("https://8.8.8.8:8080/7.html");
$streamInfo = \Intellex\SHOUTcast\Info::parseStreamURL($rawInfo);

// $streamInfo is the same as in the example above
```


## SHOUTcast stream info

The SHOUTcast stream info could be found on any SHOUTcast and it is readable via simple HTTP request.

#### URL

The URL to the info can be retrieved by replacing the `/stream` with `/7.html` at the end of the stream URL.

I.e. `https://8.8.8.8:5050/stream` becomes `https://8.8.8.8:5050/7.html`.

![meme](https://i.imgflip.com/52qclr.jpg)

#### Format

Ahhhh yiiiiiis....

If you are still reading this, we are pretty sure you will give up in just a few moments :)

##### In example
```html
<html><body>7263,1,11932,14000,5721,256,PINKFONG - Baby Shark</body></html>
```
Yes, it is wrapped in HTML, for reasons... I guess...

##### Explanation
The format is as follows (excluding the HTML container): 
```
CURR,ONLINE,PEAK,MAX,UNIQ,BIT,NAME
```

Where
 - CURR - The current number of listeners, as integer
 - ONLINE - If the stream is running than 1, otherwise 0, as integer.
 - PEAK - The maximum number of listeners that have ever been tuned in at once, as integer
 - MAX - The maximum connection this server is able to handle, as integer
 - UNIQ - The number of unique clients currently tuned in (always <= CURR), as integer
 - BIT - The bitrate of the stream, as integer
 - NAME - The current song or show, as string (the only optional parameter)
 
Did I mention that it is also wrapped in the HTML? :D

```
Now we will give you a moment to appreciate the ordering of the elements :)
```


Licence
--------------------
MIT License

Copyright (c) 2021 Intellex

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

Credits
--------------------
Script has been written by the [Intellex](https://intellex.rs/en) team.
