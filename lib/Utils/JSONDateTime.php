<?php

namespace OCA\DuplicateFinder\Utils;

use DateTime;

class JSONDateTime extends \DateTime implements \JsonSerializable {
	public function jsonSerialize() : string {
		return $this->format(DateTime::ATOM);
	}
}
