<?php namespace Intellex\SHOUTcast;

/**
 * Class ParseException is thrown when the info cannot be parsed.
 *
 * @package Intellex\SHOUTcast
 */
class ParseException extends \Exception {

	/** @var string The original input that could not have been parsed. */
	private $rawInput;

	/**
	 * ParseException constructor.
	 *
	 * @param string $rawInput The original input that could not have been parsed
	 */
	public function __construct($rawInput) {
		$this->rawInput = $rawInput;
		parent::__construct("Unable to parse SHOUTcast info from '{$this->rawInput}'");
	}

	/** @return string The original input that could not have been parsed. */
	public function getOriginalInput() {
		return $this->rawInput;
	}

}
