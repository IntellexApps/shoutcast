<?php namespace Intellex\SHOUTcast;

/**
 * Definition of Info.
 */
class Info {

	/** @var bool True if the stream is running, false otherwise. */
	private $isOnline;

	/** @var int The number of currently active listeners. */
	private $currentListeners;

	/** @var int The number of currently connected unique clients. */
	private $uniqueCurrentListeners;

	/** @var int The maximum number of simultaneous listeners ever. */
	private $peakListeners;

	/** @var int The maximum number of connections supported by this stream. */
	private $maxConnections;

	/** @var int The quality of the stream, as bitrate. */
	private $quality;

	/** @var string|null The name of the current song or show. */
	private $onAir;

	/**
	 * Initialize the Info.
	 *
	 * @param bool        $isOnline               True if the stream is running, false otherwise.
	 * @param int         $currentListeners       The number of currently active listeners.
	 * @param int         $uniqueCurrentListeners The number of currently connected unique clients.
	 * @param int         $peakListeners          The maximum number of simultaneous listeners ever.
	 * @param int         $maxConnections         The maximum number of connections supported by this stream.
	 * @param int         $quality                The quality of the stream, as bitrate.
	 * @param string|null $onAir                  The name of the current song or show.
	 */
	public function __construct($isOnline, $currentListeners, $uniqueCurrentListeners, $peakListeners, $maxConnections, $quality, $onAir) {
		$this->isOnline = $isOnline;
		$this->currentListeners = $currentListeners;
		$this->uniqueCurrentListeners = $uniqueCurrentListeners;
		$this->peakListeners = $peakListeners;
		$this->maxConnections = $maxConnections;
		$this->quality = $quality;
		$this->onAir = trim($onAir) !== '' && strtolower(trim($onAir)) != 'null'
			? trim($onAir)
			: null;
	}

	/**
	 * Parse the raw data into the class.
	 * It will automatically remove all HTML and extra spacings.
	 *
	 * The expected format is: CUR,ONLINE,PEAK,MAX,UNIQ,BIT,PLAYING
	 *
	 * @param string $rawInput The raw input to parse.
	 *
	 * @return Info The info about the SHOUTcast stream.
	 * @throws ParseException if the supplied input cannot be parsed.
	 */
	public static function parse($rawInput) {

		// Clean the input
		$cleanInput = preg_replace("[\n\t]", '', trim(strip_tags($rawInput)));

		// Match via regular expressions
		if (!preg_match('~^(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,(.*?)$~', $cleanInput, $match)) {
			throw new ParseException($rawInput);
		}

		return new Info(
			$match[2] == 1,
			$match[1] * 1,
			$match[5] * 1,
			$match[3] * 1,
			$match[4] * 1,
			$match[6] * 1,
			$match[7]
		);
	}

	/**
	 * Parse the SHOUTcast info from the stream URL.
	 *
	 * @param string $streamURL The raw input to parse.
	 *
	 * @return Info The info about the SHOUTcast stream.
	 * @throws ParseException if the supplied input cannot be parsed.
	 */
	public static function parseStreamURL($streamURL) {
		return self::parse(file_get_contents(self::getInfoURL($streamURL)));
	}

	/**
	 * Convert the supplied stream URL to a URL to the stream info (7.html).
	 *
	 * @param string $streamURL The original stream URL.
	 *
	 * @return string The URL to the stream info.
	 */
	public static function getInfoURL($streamURL) {
		return preg_replace("~^(https?://[^/]+)(/.*|)$~", "$1/7.html", trim($streamURL));
	}

	/** @return bool True if the stream is running, false otherwise. */
	public function isOnline() {
		return $this->isOnline;
	}

	/** @return int The number of currently active listeners. */
	public function currentListeners() {
		return $this->currentListeners;
	}

	/** @return int The number of currently connected unique clients. */
	public function uniqueCurrentListeners() {
		return $this->uniqueCurrentListeners;
	}

	/** @return int The maximum number of simultaneous listeners ever. */
	public function peakListeners() {
		return $this->peakListeners;
	}

	/** @return int The maximum number of connections supported by this stream. */
	public function maxConnections() {
		return $this->maxConnections;
	}

	/** @return int The quality of the stream, as bitrate. */
	public function quality() {
		return $this->quality;
	}

	/** @return string|null The name of the current song or show. */
	public function onAir() {
		return $this->onAir;
	}

}
