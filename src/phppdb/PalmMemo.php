<?php namespace phppdb;
/* Class extender for List databases.
 *
 * Copyright (C) 2001 - PHP-PDB development team
 * Licensed under the GNU LGPL software license.
 * See the doc/LEGAL file for more information
 * See https://github.com/fidian/php-pdb for more information about the library
 *
 *
 * Database format is not detailed on the web, but the List homepage is at
 *   http://www.magma.ca/~roo/list/list.html
 * Perl database conversion code is at
 *   http://home.pacbell.net/dunhamd/
 *
 * Format is for List, presumably all versions
 */

/*
 * PalmDB Class
 *
 * Contains all of the required methods and variables to write a pdb file.
 * Extend this class to provide functionality for memos, addresses, etc.
 */
class PalmMemo extends PalmDB {

	const MAXCHARS = 4096;


	// Creates a new database class
	public function __construct() {
		PalmDB::__construct('DATA', 'memo', 'MemoDB');
	}


	/* Returns the size of the current record if no arguments.
	 * Returns the size of the specified record if arguments. */
	public function GetRecordSize($num = false) {
		if ($num === false)
            $num = $this->CurrentRecord;

		if (!isset($this->Records[$num]))
            return 0;
		return strlen($this->Records[$num]) / 2;
	}

	/* Gets a memo's text
	 * Returns the text of the specified memo or the current memo record */
	function GetText($num = false) {
		if ($num === false)
            $num = $this->CurrentRecord;

		if (!isset($this->Records[$num]))
            return '';
		return pack('H*', $this->Records[$num]);
	}

	// Sets a memo's text or the text of the current memo record
	function SetText($text, $num = false) {
		if ($num === false)
            $num = $this->CurrentRecord;
		$this->Records[$num] = $this->String($text, PalmMemo::MAXCHARS - 1) . $this->Int8(0);
	}
}

