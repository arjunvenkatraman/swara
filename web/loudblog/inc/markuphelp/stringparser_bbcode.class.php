<?php
/**
 * BB code string parsing class
 *
 * @author Christian Seiler <spam@christian-seiler.de>
 * @copyright Christian Seiler 2004
 * @package stringparser
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of either:
 *
 *  a) the GNU General Public License as published by the Free
 *  Software Foundation; either version 1, or (at your option) any
 *  later version, or
 *
 *  b) the Artistic License as published by Larry Wall, either version 2.0,
 *     or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See either
 *  the GNU General Public License or the Artistic License for more details.
 *
 *  You should have received a copy of the Artistic License with this Kit,
 *  in the file named "Artistic.clarified".  If not, I'll be glad to provide
 *  one.
 *
 *  You should also have received a copy of the GNU General Public License
 *  along with this program in the file named "COPYING"; if not, write to
 *  the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
 *  MA 02111-1307, USA.
 */
 
require_once dirname(__FILE__).'/stringparser.class.php';

define ('BBCODE_CLOSETAG_FORBIDDEN', -1);
define ('BBCODE_CLOSETAG_OPTIONAL', 0);
define ('BBCODE_CLOSETAG_IMPLICIT', 1);
define ('BBCODE_CLOSETAG_IMPLICIT_ON_CLOSE_ONLY', 2);
define ('BBCODE_CLOSETAG_MUSTEXIST', 3);

define ('BBCODE_NEWLINE_PARSE', 0);
define ('BBCODE_NEWLINE_IGNORE', 1);
define ('BBCODE_NEWLINE_DROP', 2);

define ('BBCODE_PARAGRAPH_ALLOW_BREAKUP', 0);
define ('BBCODE_PARAGRAPH_ALLOW_INSIDE', 1);
define ('BBCODE_PARAGRAPH_BLOCK_ELEMENT', 2);

/**
* BB code string parser class
*/
class StringParser_BBCode extends StringParser {
	/**
	 * String parser mode
	 *
	 * The BBCode string parser works in search mode
	 *
	 * @access private
	 * @var int
	 * @see STRINGPARSER_MODE_SEARCH, STRINGPARSER_MODE_LOOP
	 */
	var $_parserMode = STRINGPARSER_MODE_SEARCH;
	
	/**
	 * Defined BB Codes
	 *
	 * The registered BB codes
	 *
	 * @access private
	 * @var array
	 */
	var $_codes = array ();
	
	/**
	 * Registered parsers
	 *
	 * @access private
	 * @var array
	 */
	var $_parsers = array ();
	
	/**
	 * Defined maximum occurrences
	 *
	 * @access private
	 * @var array
	 */
	var $_maxOccurrences = array ();
	
	/**
	 * Root content type
	 *
	 * @access private
	 * @var string
	 */
	var $_rootContentType = 'block';
	
	/**
	 * Do not output but return the tree
	 *
	 * @access private
	 * @var bool
	 */
	var $_noOutput = false;
	
	/**
	 * Root paragraph handling enabled
	 *
	 * @access private
	 * @var bool
	 */
	var $_rootParagraphHandling = false;
	
	/**
	 * Paragraph handling parameters
	 * @access private
	 * @var array
	 */
	var $_paragraphHandling = array (
		'detect_string' => "\n\n",
		'start_tag' => '<p>',
		'end_tag' => "</p>\n"
	);
	
	/**
	 * Add a code
	 *
	 * @access public
	 * @var string $name The name of the code
	 * @var string $callback_type See documentation
	 * @var string $callback_func The callback function to call
	 * @var array $callback_params The callback parameters
	 * @var string $content_type See documentation
	 * @var array $allowed_within See documentation
	 * @var array $not_allowed_within See documentation
	 * @return bool
	 */
	function addCode ($name, $callback_type, $callback_func, $callback_params, $content_type, $allowed_within, $not_allowed_within) {
		if (isset ($this->_codes[$name])) {
			return false; // already exists
		}
		if (!preg_match ('/^[a-zA-Z0-9*_!+-]+$/', $name, $code)) {
			return false; // invalid
		}
		$this->_codes[$name] = array (
			'name' => $name,
			'callback_type' => $callback_type,
			'callback_func' => $callback_func,
			'callback_params' => $callback_params,
			'content_type' => $content_type,
			'allowed_within' => $allowed_within,
			'not_allowed_within' => $not_allowed_within,
			'flags' => array ()
		);
		return true;
	}
	
	/**
	 * Remove a code
	 *
	 * @access public
	 * @var $name The code to remove
	 * @return bool
	 */
	function removeCode ($name) {
		if (isset ($this->_codes[$name])) {
			unset ($this->_codes[$name]);
			return true;
		}
		return false;
	}
	
	/**
	 * Remove all codes
	 *
	 * @access public
	 */
	function removeAllCodes () {
		$this->_codes = array ();
	}
	
	/**
	 * Set a code flag
	 *
	 * @access public
	 * @var string $name The name of the code
	 * @var string $flag The name of the flag to set
	 * @var mixed $value The value of the flag to set
	 * @return bool
	 */
	function setCodeFlag ($name, $flag, $value) {
		if (!isset ($this->_codes[$name])) {
			return false;
		}
		$this->_codes[$name]['flags'][$flag] = $value;
		return true;
	}
	
	/**
	 * Set occurrence type
	 *
	 * Example:
	 *   $bbcode->setOccurrenceType ('url', 'link');
	 *   $bbcode->setMaxOccurrences ('link', 4);
	 * Would create the situation where a link may only occur four
	 * times in the hole text.
	 *
	 * @access public
	 * @var string $code The name of the code
	 * @var string $type The name of the occurrence type to set
	 * @return bool
	 */
	function setOccurrenceType ($code, $type) {
		return $this->setCodeFlag ($code, 'occurrence_type', $type);
	}
	
	/**
	 * Set maximum number of occurrences
	 *
	 * @access public
	 * @var string $type The name of the occurrence type
	 * @var int $count The maximum number of occurrences
	 * @return bool
	 */
	function setMaxOccurrences ($type, $count) {
		settype ($count, 'integer');
		if ($count < 0) { // sorry, does not make any sense
			return false;
		}
		$this->_maxOccurrences[$type] = $count;
		return true;
	}
	
	/**
	 * Add a parser
	 *
	 * @access public
	 * @var string $type The content type for which the parser is to add
	 * @var mixed $parser The function to call
	 * @return bool
	 */
	function addParser ($type, $parser) {
		if (is_array ($type)) {
			foreach ($type as $t) {
				$this->addParser ($t, $parser);
			}
			return true;
		}
		if (!isset ($this->_parsers[$type])) {
			$this->_parsers[$type] = array ();
		}
		$this->_parsers[$type][] = $parser;
		return true;
	}
	
	/**
	 * Set root content type
	 *
	 * @access public
	 * @var string $content_type The new root content type
	 */
	function setRootContentType ($content_type) {
		$this->_rootContentType = $content_type;
	}
	
	/**
	 * Set paragraph handling on root element
	 *
	 * @access public
	 * @var bool $enabled The new status of paragraph handling on root element
	 */
	function setRootParagraphHandling ($enabled) {
		$this->_rootParagraphHandling = (bool)$enabled;
	}
	
	/**
	 * Set paragraph handling parameters
	 *
	 * @access public
	 * @var string $detect_string The string to detect
	 * @var string $start_tag The replacement for the start tag (e.g. <p>)
	 * @var string $end_tag The replacement for the start tag (e.g. </p>)
	 */
	function setParagraphHandlingParameters ($detect_string, $start_tag, $end_tag) {
		$this->_paragraphHandling = array (
			'detect_string' => $detect_string,
			'start_tag' => $start_tag,
			'end_tag' => $end_tag
		);
	}
	
	/**
	 * Get a code flag
	 *
	 * @access public
	 * @var string $name The name of the code
	 * @var string $flag The name of the flag to get
	 * @var string $type The type of the return value
	 * @var mixed $default The default return value
	 * @return bool
	 */
	function getCodeFlag ($name, $flag, $type = 'mixed', $default = null) {
		if (!isset ($this->_codes[$name])) {
			return $default;
		}
		if (!array_key_exists ($flag, $this->_codes[$name]['flags'])) {
			return $default;
		}
		$return = $this->_codes[$name]['flags'][$flag];
		if ($type != 'mixed') {
			settype ($return, $type);
		}
		return $return;
	}
	
	/**
	 * Set a specific status
	 * @access private
	 */
	function _setStatus ($status) {
		switch ($status) {
			case 0:
				$this->_charactersSearch = array ('[/', '[');
				$this->_status = $status;
				break;
			case 1:
				$this->_charactersSearch = array (']', ' = "', '="', ' = \'', '=\'', ' = ', '=', ': ', ':', ' ');
				$this->_status = $status;
				break;
			case 2:
				$this->_charactersSearch = array (']');
				$this->_status = $status;
				$this->_savedName = '';
				break;
			case 3:
				if ($this->_quoting !== null) {
					$this->_charactersSearch = array ('\\\\', '\\'.$this->_quoting, $this->_quoting.']', $this->_quoting);
					$this->_status = $status;
					break;
				}
				$this->_charactersSearch = array (']');
				$this->_status = $status;
				break;
			case 4:
				$this->_charactersSearch = array (' ', ']', '="', '=\'', '=');
				$this->_status = $status;
				$this->_savedName = '';
				$this->_savedValue = '';
				break;
			case 5:
				if ($this->_quoting !== null) {
					$this->_charactersSearch = array ('\\\\', '\\'.$this->_quoting, $this->_quoting.' ', $this->_quoting.']', $this->_quoting);
				} else {
					$this->_charactersSearch = array (' ', ']');
				}
				$this->_status = $status;
				$this->_savedValue = '';
				break;
			case 7:
				$this->_charactersSearch = array ('[/'.$this->_topNode ('name').']');
				if (!$this->_topNode ('getFlag', 'case_sensitive', 'boolean', true)) {
					$this->_charactersSearch[] = '[/';
				}
				$this->_status = $status;
				break;
			default:
				return false;
		}
		return true;
	}
	
	/**
	 * Abstract method Append text depending on current status
	 * @access private
	 * @param string $text The text to append
	 * @return bool On success, the function returns true, else false
	 */
	function _appendText ($text) {
		if (!strlen ($text)) {
			return true;
		}
		switch ($this->_status) {
			case 0:
			case 7:
				return $this->_appendToLastTextChild ($text);
			case 1:
				return $this->_topNode ('appendToName', $text);
			case 2:
			case 4:
				$this->_savedName .= $text;
				return true;
			case 3:
				return $this->_topNode ('appendToAttribute', 'default', $text);
			case 5:
				$this->_savedValue .= $text;
				return true;
			default:
				return false;
		}
	}
	
	/**
	 * Restart parsing after current block
	 *
	 * To achieve this the current top stack object is removed from the
	 * tree. Then the current item
	 *
	 * @access protected
	 * @return bool
	 */
	function _reparseAfterCurrentBlock () {
		if ($this->_status == 2) {
			// this status will *never* call _reparseAfterCurrentBlock itself
			// so this is called if the loop ends
			// therefore, just add the [/ to the text
			
			// _savedName should be empty but just in case
			$this->_cpos -= strlen ($this->_savedName);
			$this->_savedName = '';
			$this->_status = 0;
			$this->_appendText ('[/');
			return true;
		} else {
			return parent::_reparseAfterCurrentBlock ();
		}
	}
	
	/**
	 * Apply parsers
	 */
	function _applyParsers ($type, $text) {
		if (!isset ($this->_parsers[$type])) {
			return $text;
		}
		foreach ($this->_parsers[$type] as $parser) {
			if (is_callable ($parser)) {
				$ntext = call_user_func ($parser, $text);
				if (is_string ($ntext)) {
					$text = $ntext;
				}
			}
		}
		return $text;
	}
	
	/**
	 * Handle status
	 * @access private
	 * @param int $status The current status
	 * @param string $needle The needle that was found
	 * @return bool
	 */
	function _handleStatus ($status, $needle) {
		switch ($status) {
			case 0: // NORMAL TEXT
				if ($needle != '[' && $needle != '[/') {
					$this->_appendText ($needle);
					return true;
				}
				if ($needle == '[') {
					$node =& new StringParser_BBCode_Node_Element ($this->_cpos);
					$res = $this->_pushNode ($node);
					if (!$res) {
						return false;
					}
					$this->_setStatus (1);
				} else if ($needle == '[/') {
					if (count ($this->_stack) <= 1) {
						$this->_appendText ($needle);
						return true;
					}
					$this->_setStatus (2);
				}
				break;
			case 1: // OPEN TAG
				if ($needle == ']') {
					return $this->_openElement (0);
				} else if (trim ($needle) == ':' || trim ($needle) == '=') {
					$this->_quoting = null;
					$this->_setStatus (3); // default value parser
					break;
				} else if (trim ($needle) == '="' || trim ($needle) == '= "' || trim ($needle) == '=\'' || trim ($needle) == '= \'') {
					$this->_quoting = substr (trim ($needle), -1);
					$this->_setStatus (3); // default value parser with quotation
					break;
				} else if ($needle == ' ') {
					$this->_setStatus (4); // attribute parser
					break;
				} else {
					$this->_appendText ($needle);
					return true;
				}
				break;
			case 2: // CLOSE TAG
				if ($needle != ']') {
					$this->_appendText ($needle);
					return true;
				}
				$closecount = 0;
				if (!$this->_isCloseable ($this->_savedName, $closecount)) {
					$this->_setStatus (0);
					$this->_appendText ('[/'.$this->_savedName.$needle);
					return true;
				}
				$this->_setStatus (0);
				for ($i = 0; $i < $closecount; $i++) {
					if ($i == $closecount - 1) {
						$this->_topNode ('setHadCloseTag');
					}
					if (!$this->_popNode ()) {
						return false;
					}
				}
				break;
			case 3: // DEFAULT ATTRIBUTE
				if ($this->_quoting !== null) {
					if ($needle == '\\\\') {
						$this->_appendText ('\\');
						$this->_quoting = null;
						return true;
					} else if ($needle == '\\'.$this->_quoting) {
						$this->_appendText ($this->_quoting);
						$this->_quoting = null;
						return true;
					} else if ($needle == $this->_quoting.']') {
						// MAKE SURE THIS CODE --->
						$needle = ']';
						$this->_quoting = null;
					}
				}
				// ---> CONTINUES HERE!
				if ($needle != ']') {
					$this->_appendText ($needle);
					return true;
				}
				return $this->_openElement (1);
				break;
			case 4: // ATTRIBUTE NAME
				if ($needle == ' ') {
					if (strlen ($this->_savedName)) {
						$this->_topNode ('setAttribute', $this->_savedName, true);
					}
					// just ignore and continue in same mode
					$this->_setStatus (4); // reset parameters
					return true;
				} else if ($needle == ']') {
					if (strlen ($this->_savedName)) {
						$this->_topNode ('setAttribute', $this->_savedName, true);
					}
					return $this->_openElement (2);
				} else if ($needle == '=') {
					$this->_quoting = null;
					$this->_setStatus (5);
					return true;
				} else if ($needle == '="') {
					$this->_quoting = '"';
					$this->_setStatus (5);
					return true;
				} else if ($needle == '=\'') {
					$this->_quoting = '\'';
					$this->_setStatus (5);
					return true;
				} else {
					$this->_appendText ($needle);
					return true;
				}
				break;
			case 5: // ATTRIBUTE VALUE
				if ($this->_quoting !== null) {
					if ($needle == '\\\\') {
						$this->_appendText ('\\');
						return true;
					} else if ($needle == '\\'.$this->_quoting) {
						$this->_appendText ($this->_quoting);
						return true;
					} else if ($needle == $this->_quoting.' ') {
						$this->_topNode ('setAttribute', $this->_savedName, $this->_savedValue);
						$this->_setStatus (4);
						return true;
					} else if ($needle == $this->_quoting.']') {
						$this->_topNode ('setAttribute', $this->_savedName, $this->_savedValue);
						return $this->_openElement (2);
					} else if ($needle == $this->_quoting) {
						// can't be, only ']' and ' ' allowed after quoting char
						return $this->_reparseAfterCurrentBlock ();
					} else {
						$this->_appendText ($needle);
						return true;
					}
				} else {
					if ($needle == ' ') {
						$this->_topNode ('setAttribute', $this->_savedName, $this->_savedValue);
						$this->_setStatus (4);
						return true;
					} else if ($needle == ']') {
						$this->_topNode ('setAttribute', $this->_savedName, $this->_savedValue);
						return $this->_openElement (2);
					} else {
						$this->_appendText ($needle);
						return true;
					}
				}
				break;
			case 7:
				if ($needle == '[/') {
					// this was case insensitive match
					if (strtolower (substr ($this->_text, $this->_cpos + strlen ($needle), strlen ($this->_topNode ('name')) + 1)) == strtolower ($this->_topNode ('name').']')) {
						// this matched
						$this->_cpos += strlen ($this->_topNode ('name')) + 1;
					} else {
						// it didn't match
						$this->_appendText ($needle);
						return true;
					}
				}
				$closecount = $this->_savedCloseCount;
				if (!$this->_topNode ('validate')) {
					return $this->_reparseAfterCurrentBlock ();
				}
				// do we have to close subnodes?
				if ($closecount) {
					// get top node
					$mynode =& $this->_stack[count ($this->_stack)-1];
					// close necessary nodes
					for ($i = 0; $i <= $closecount; $i++) {
						if (!$this->_popNode ()) {
							return false;
						}
					}
					if (!$this->_pushNode ($mynode)) {
						return false;
					}
				}
				$this->_setStatus (0);
				$this->_popNode ();
				return true;
			default: 
				return false;
		}
		return true;
	}
	
	/**
	 * Open the next element
	 *
	 * @access private
	 * @return bool
	 */
	function _openElement ($type = 0) {
		$name = $this->_topNode ('name');
		if (!isset ($this->_codes[$name])) {
			if (isset ($this->_codes[strtolower ($name)]) && !$this->getCodeFlag (strtolower ($name), 'case_sensitive', 'boolean', true)) {
				$name = strtolower ($name);
			} else {
				return $this->_reparseAfterCurrentBlock ();
			}
		}
		$occ_type = $this->getCodeFlag ($name, 'occurrence_type', 'string');
		if ($occ_type !== null && isset ($this->_maxOccurrences[$occ_type])) {
			$max_occs = $this->_maxOccurrences[$occ_type];
			$occs = $this->_root->getNodeCountByCriterium ('flag:occurrence_type', $occ_type);
			if ($occs >= $max_occs) {
				return $this->_reparseAfterCurrentBlock ();
			}
		}
		$closecount = 0;
		$this->_topNode ('setCodeInfo', $this->_codes[$name]);
		if (!$this->_isOpenable ($name, $closecount)) {
			return $this->_reparseAfterCurrentBlock ();
		}
		$this->_setStatus (0);
		switch ($type) {
		case 0:
			$cond = ($this->_codes[$name]['callback_type'] == 'usecontent' || $this->_codes[$name]['callback_type'] == 'usecontent?');
			break;
		case 1:
			$cond = ($this->_codes[$name]['callback_type'] == 'usecontent' || ($this->_codes[$name]['callback_type'] == 'usecontent?' && $this->_codes[$name]['callback_params']['usecontent_param'] != 'default'));
			break;
		case 2:
			$cond = ($this->_codes[$name]['callback_type'] == 'usecontent' || ($this->_codes[$name]['callback_type'] == 'usecontent?' && !in_array (@$this->_codes[$name]['callback_params']['usecontent_param'], array_keys ($this->_topNodeVar ('_attributes')))));
			break;
		default:
			$cond = false;
			break;
		}
		if ($cond) {
			$this->_savedCloseCount = $closecount;
			$this->_setStatus (7);
			return true;
		}
		if (!$this->_topNode ('validate')) {
			return $this->_reparseAfterCurrentBlock ();
		}
		// do we have to close subnodes?
		if ($closecount) {
			// get top node
			$mynode =& $this->_stack[count ($this->_stack)-1];
			// close necessary nodes
			for ($i = 0; $i <= $closecount; $i++) {
				if (!$this->_popNode ()) {
					return false;
				}
			}
			if (!$this->_pushNode ($mynode)) {
				return false;
			}
		}
		
		if ($this->_codes[$name]['callback_type'] == 'simple_replace_single' || $this->_codes[$name]['callback_type'] == 'callback_replace_single') {
			if (!$this->_popNode ())  {
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Is a node closeable?
	 *
	 * @access private
	 * @return bool
	 */
	function _isCloseable ($name, &$closecount) {
		$node =& $this->_findNamedNode ($name, false);
		if ($node === false) {
			return false;
		}
		$scount = count ($this->_stack);
		for ($i = $scount - 1; $i > 0; $i--) {
			$closecount++;
			if ($this->_stack[$i]->equals ($node)) {
				return true;
			}
			if ($this->_stack[$i]->getFlag ('closetag', 'integer', BBCODE_CLOSETAG_IMPLICIT) == BBCODE_CLOSETAG_MUSTEXIST) {
				return false;
			}
		}
		return false;
	}
	
	/**
	 * Is a node openable?
	 *
	 * @access private
	 * @return bool
	 */
	function _isOpenable ($name, &$closecount) {
		if (!isset ($this->_codes[$name])) {
			return false;
		}
		
		$closecount = 0;
		
		$allowed_within = $this->_codes[$name]['allowed_within'];
		$not_allowed_within = $this->_codes[$name]['not_allowed_within'];
		
		$scount = count ($this->_stack);
		if ($scount == 2) { // top level element
			if (!in_array ($this->_rootContentType, $allowed_within)) {
				return false;
			}
		} else {
			if (!in_array ($this->_stack[$scount-2]->_codeInfo['content_type'], $allowed_within)) {
				return $this->_isOpenableWithClose ($name, $closecount);
			}
		}
		
		for ($i = 1; $i < $scount - 1; $i++) {
			if (in_array ($this->_stack[$i]->_codeInfo['content_type'], $not_allowed_within)) {
				return $this->_isOpenableWithClose ($name, $closecount);
			}
		}
		
		return true;
	}
	
	/**
	 * Is a node openable by closing other nodes?
	 *
	 * @access private
	 * @return bool
	 */
	function _isOpenableWithClose ($name, &$closecount) {
		$tnname = $this->_topNode ('name');
		if (isset ($this->_codes[strtolower($tnname)]) && !$this->getCodeFlag (strtolower($tnname), 'case_sensitive', 'boolean', true)) {
			$tnname = strtolower($tnname);
		}
		if (!in_array ($this->getCodeFlag ($tnname, 'closetag', 'integer', BBCODE_CLOSETAG_IMPLICIT), array (BBCODE_CLOSETAG_FORBIDDEN, BBCODE_CLOSETAG_OPTIONAL))) {
			return false;
		}
		$node =& $this->_findNamedNode ($name, true);
		if ($node === false) {
			return false;
		}
		$scount = count ($this->_stack);
		if ($scount < 3) {
			return false;
		}
		for ($i = $scount - 2; $i > 0; $i--) {
			$closecount++;
			if ($this->_stack[$i]->equals ($node)) {
				return true;
			}
			if (in_array ($this->_stack[$i]->getFlag ('closetag', 'integer', BBCODE_CLOSETAG_IMPLICIT), array (BBCODE_CLOSETAG_IMPLICIT_ON_CLOSE_ONLY, BBCODE_CLOSETAG_MUSTEXIST))) {
				return false;
			}
		}
		
		return false;
	}
	
	/**
	 * Abstract method: Close remaining blocks
	 * @access private
	 */
	function _closeRemainingBlocks () {
		// everything closed
		if (count ($this->_stack) == 1) {
			return true;
		}
		// not everything close
		if ($this->strict) {
			return false;
		}
		while (count ($this->_stack) > 1) {
			if ($this->_topNode ('getFlag', 'closetag', 'integer', BBCODE_CLOSETAG_IMPLICIT) == BBCODE_CLOSETAG_MUSTEXIST) {
				return false; // sorry
			}
			$res = $this->_popNode ();
			if (!$res) {
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Find a node with a specific name in stack
	 *
	 * @access private
	 * @return mixed
	 */
	function &_findNamedNode ($name, $searchdeeper = false) {
		$lname = strtolower ($name);
		if (isset ($this->_codes[$lname]) && !$this->getCodeFlag ($lname, 'case_sensitive', 'boolean', true)) {
			$name = $lname;
			$case_sensitive = false;
		} else {
			$case_sensitive = true;
		}
		$scount = count ($this->_stack);
		if ($searchdeeper) {
			$scount--;
		}
		for ($i = $scount - 1; $i > 0; $i--) {
			if (!$case_sensitive) {
				$cmp_name = strtolower ($this->_stack[$i]->name ());
			} else {
				$cmp_name = $this->_stack[$i]->name ();
			}
			if ($cmp_name == $name) {
				return $this->_stack[$i];
			}
		}
		return false;
	}
	
	/**
	 * Abstract method: Output tree
	 * @access private
	 * @return bool
	 */
	function _outputTree () {
		if ($this->_noOutput) {
			return true;
		}
		$output = $this->_outputNode ($this->_root);
		if (is_string ($output)) {
			$this->_output = $this->_applyPostfilters ($output);
			unset ($output);
			return true;
		}
		
		return false;
	}
	
	/**
	 * Output a node
	 * @access private
	 * @return bool
	 */
	function _outputNode (&$node) {
		$output = '';
		if ($node->_type == STRINGPARSER_BBCODE_NODE_PARAGRAPH || $node->_type == STRINGPARSER_BBCODE_NODE_ELEMENT || $node->_type == STRINGPARSER_NODE_ROOT) {
			$ccount = count ($node->_children);
			for ($i = 0; $i < $ccount; $i++) {
				$suboutput = $this->_outputNode ($node->_children[$i]);
				if (!is_string ($suboutput)) {
					return false;
				}
				$output .= $suboutput;
			}
			if ($node->_type == STRINGPARSER_BBCODE_NODE_PARAGRAPH) {
				return $this->_paragraphHandling['start_tag'].$output.$this->_paragraphHandling['end_tag'];
			}
			if ($node->_type == STRINGPARSER_BBCODE_NODE_ELEMENT) {
				return $node->getReplacement ($output);
			}
			return $output;
		} else if ($node->_type == STRINGPARSER_NODE_TEXT) {
			$output = $node->content;
			$before = '';
			$after = '';
			$ol = strlen ($output);
			switch ($node->getFlag ('newlinemode.begin', 'integer', BBCODE_NEWLINE_PARSE)) {
			case BBCODE_NEWLINE_IGNORE:
				if ($ol && $output{0} == "\n") {
					$before = "\n";
				}
				// don't break!
			case BBCODE_NEWLINE_DROP:
				if ($ol && $output{0} == "\n") {
					$output = substr ($output, 1);
					$ol--;
				}
				break;
			}
			switch ($node->getFlag ('newlinemode.end', 'integer', BBCODE_NEWLINE_PARSE)) {
			case BBCODE_NEWLINE_IGNORE:
				if ($ol && $output{$ol-1} == "\n") {
					$after = "\n";
				}
				// don't break!
			case BBCODE_NEWLINE_DROP:
				if ($ol && $output{$ol-1} == "\n") {
					$output = substr ($output, 0, -1);
					$ol--;
				}
				break;
			}
			// can't do anything
			if ($node->_parent === null) {
				return $before.$output.$after;
			}
			if ($node->_parent->_type == STRINGPARSER_BBCODE_NODE_PARAGRAPH)  {
				$parent =& $node->_parent;
				unset ($node);
				$node =& $parent;
				unset ($parent);
				// if no parent for this paragraph
				if ($node->_parent === null) {
					return $before.$output.$after;
				}
			}
			if ($node->_parent->_type == STRINGPARSER_NODE_ROOT) {
				return $before.$this->_applyParsers ($this->_rootContentType, $output).$after;
			}
			if ($node->_parent->_type == STRINGPARSER_BBCODE_NODE_ELEMENT) {
				return $before.$this->_applyParsers ($node->_parent->_codeInfo['content_type'], $output).$after;
			}
			return $before.$output.$after;
		}
	}
	
	/**
	 * Abstract method: Manipulate the tree
	 * @access private
	 * @return bool
	 */
	function _modifyTree () {
		// first pass: try to do newline handling
		$nodes =& $this->_root->getNodesByCriterium ('needsTextNodeModification', true);
		$nodes_count = count ($nodes);
		for ($i = 0; $i < $nodes_count; $i++) {
			$v = $nodes[$i]->getFlag ('opentag.before.newline', 'integer', BBCODE_NEWLINE_PARSE);
			if ($v != BBCODE_NEWLINE_PARSE) {
				$n =& $nodes[$i]->findPrevAdjentTextNode ();
				if (!is_null ($n)) {
					$n->setFlag ('newlinemode.end', $v);
				}
				unset ($n);
			}
			$v = $nodes[$i]->getFlag ('opentag.after.newline', 'integer', BBCODE_NEWLINE_PARSE);
			if ($v != BBCODE_NEWLINE_PARSE) {
				$n =& $nodes[$i]->firstChildIfText ();
				if (!is_null ($n)) {
					$n->setFlag ('newlinemode.begin', $v);
				}
				unset ($n);
			}
			$v = $nodes[$i]->getFlag ('closetag.before.newline', 'integer', BBCODE_NEWLINE_PARSE);
			if ($v != BBCODE_NEWLINE_PARSE) {
				$n =& $nodes[$i]->lastChildIfText ();
				if (!is_null ($n)) {
					$n->setFlag ('newlinemode.end', $v);
				}
				unset ($n);
			}
			$v = $nodes[$i]->getFlag ('closetag.after.newline', 'integer', BBCODE_NEWLINE_PARSE);
			if ($v != BBCODE_NEWLINE_PARSE) {
				$n =& $nodes[$i]->findNextAdjentTextNode ();
				if (!is_null ($n)) {
					$n->setFlag ('newlinemode.begin', $v);
				}
				unset ($n);
			}
		}
		
		// second pass a: do paragraph handling on root element
		if ($this->_rootParagraphHandling) {
			$res = $this->_handleParagraphs ($this->_root);
			if (!$res) {
				return false;
			}
		}
		
		// second pass b: do paragraph handling on other elements
		unset ($nodes);
		$nodes =& $this->_root->getNodesByCriterium ('flag:paragraphs', true);
		$nodes_count = count ($nodes);
		for ($i = 0; $i < $nodes_count; $i++) {
			$res = $this->_handleParagraphs ($nodes[$i]);
			if (!$res) {
				return false;
			}
		}
		
		// second pass c: search for empty paragraph nodes and remove them
		unset ($nodes);
		$nodes =& $this->_root->getNodesByCriterium ('empty', true);
		$nodes_count = count ($nodes);
		if (isset ($parent)) {
			unset ($parent); $parent = null;
		}
		for ($i = 0; $i < $nodes_count; $i++) {
			if ($nodes[$i]->_type != STRINGPARSER_BBCODE_NODE_PARAGRAPH) {
				continue;
			}
			unset ($parent);
			$parent =& $nodes[$i]->_parent;
			$parent->removeChild ($nodes[$i], true);
		}
		
		return true;
	}
	
	/**
	 * Handle paragraphs
	 * @access private
	 * @param object $node The node to handle
	 * @return bool
	 */
	function _handleParagraphs (&$node) {
		// if this node is already a subnode of a paragraph node, do NOT 
		// do paragraph handling on this node!
		if ($this->_hasParagraphAncestor ($node)) {
			return true;
		}
		$dest_nodes = array ();
		$last_node_was_paragraph = false;
		$prevtype = STRINGPARSER_NODE_TEXT;
		$paragraph = null;
		while (count ($node->_children)) {
			$mynode =& $node->_children[0];
			$node->removeChild ($mynode);
			$sub_nodes =& $this->_breakupNodeByParagraphs ($mynode);
			for ($i = 0; $i < count ($sub_nodes); $i++) {
				if (!$last_node_was_paragraph || ($prevtype == $sub_nodes[$i]->_type)) {
					unset ($paragraph);
					$paragraph =& new StringParser_BBCode_Node_Paragraph ();
				}
				$prevtype = $sub_nodes[$i]->_type;
				if ($sub_nodes[$i]->_type != STRINGPARSER_BBCODE_NODE_ELEMENT || $sub_nodes[$i]->getFlag ('paragraph_type', 'integer', BBCODE_PARAGRAPH_ALLOW_BREAKUP) != BBCODE_PARAGRAPH_BLOCK_ELEMENT) {
					$paragraph->appendChild ($sub_nodes[$i]);
					$dest_nodes[] =& $paragraph;
					$last_node_was_paragraph = true;
				} else {
					$dest_nodes[] =& $sub_nodes[$i];
					$last_onde_was_paragraph = false;
					unset ($paragraph);
					$paragraph =& new StringParser_BBCode_Node_Paragraph ();
				}
			}
		}
		$count = count ($dest_nodes);
		for ($i = 0; $i < $count; $i++) {
			$node->appendChild ($dest_nodes[$i]);
		}
		unset ($dest_nodes);
		unset ($paragraph);
		return true;
	}
	
	/**
	 * Search for a paragraph node in tree in upward direction
	 * @access private
	 * @param object $node The node to analyze
	 * @return bool
	 */
	function _hasParagraphAncestor (&$node) {
		if ($node->_parent === null) {
			return false;
		}
		$parent =& $node->_parent;
		if ($parent->_type == STRINGPARSER_BBCODE_NODE_PARAGRAPH) {
			return true;
		}
		return $this->_hasParagraphAncestor ($parent);
	}
	
	/**
	 * Break up nodes
	 * @access private
	 * @param object $node The node to break up
	 * @return array
	 */
	function &_breakupNodeByParagraphs (&$node) {
		$detect_string = $this->_paragraphHandling['detect_string'];
		$dest_nodes = array ();
		// text node => no problem
		if ($node->_type == STRINGPARSER_NODE_TEXT) {
			$cpos = 0;
			while (($npos = strpos ($node->content, $detect_string, $cpos)) !== false) {
				$subnode =& new StringParser_Node_Text (substr ($node->content, $cpos, $npos - $cpos), $node->occurredAt + $cpos);
				// copy flags
				foreach ($node->_flags as $flag => $value) {
					if ($flag == 'newlinemode.begin') {
						if ($cpos == 0) {
							$subnode->setFlag ($flag, $value);
						}
					} else if ($flag == 'newlinemode.end') {
						// do nothing
					} else {
						$subnode->setFlag ($flag, $value);
					}
				}
				$dest_nodes[] =& $subnode;
				unset ($subnode);
				$cpos = $npos + strlen ($detect_string);
			}
			$subnode =& new StringParser_Node_Text (substr ($node->content, $cpos), $node->occurredAt + $cpos);
			if ($cpos == 0) {
				$value = $node->getFlag ('newlinemode.begin', 'integer', null);
				if ($value !== null) {
					$subnode->setFlag ('newlinemode.begin', $value);
				}
			}
			$value = $node->getFlag ('newlinemode.end', 'integer', null);
			if ($value !== null) {
				$subnode->setFlag ('newlinemode.end', $value);
			}
			$dest_nodes[] =& $subnode;
			unset ($subnode);
			return $dest_nodes;
		}
		// not a text node or an element node => no way
		if ($node->_type != STRINGPARSER_BBCODE_NODE_ELEMENT) {
			$dest_nodes[] =& $node;
			return $dest_nodes;
		}
		if ($node->getFlag ('paragraph_type', 'integer', BBCODE_PARAGRAPH_ALLOW_BREAKUP) != BBCODE_PARAGRAPH_ALLOW_BREAKUP || !count ($node->_children)) {
			$dest_nodes[] =& $node;
			return $dest_nodes;
		}
		$dest_node =& $node->duplicate ();
		$nodecount = count ($node->_children);
		// now this node allows breakup - do it
		for ($i = 0; $i < $nodecount; $i++) {
			$firstnode =& $node->_children[0];
			$node->removeChild ($firstnode);
			$sub_nodes =& $this->_breakupNodeByParagraphs ($firstnode);
			for ($j = 0; $j < count ($sub_nodes); $j++) {
				if ($j != 0) {
					$dest_nodes[] =& $dest_node;
					unset ($dest_node);
					$dest_node =& $node->duplicate ();
				}
				$dest_node->appendChild ($sub_nodes[$j]);
			}
			unset ($sub_nodes);
		}
		$dest_nodes[] =& $dest_node;
		return $dest_nodes;
	}
}

/**
 * Node type: BBCode Element node
 * @see StringParser_BBCode_Node_Element::_type
 */
define ('STRINGPARSER_BBCODE_NODE_ELEMENT', 32);

/**
 * Node type: BBCode Paragraph node
 * @see StringParser_BBCode_Node_Paragraph::_type
 */
define ('STRINGPARSER_BBCODE_NODE_PARAGRAPH', 33);


/**
 * BBCode String parser paragraph node class
 */
class StringParser_BBCode_Node_Paragraph extends StringParser_Node {
	/**
	 * The type of this node.
	 * 
	 * This node is a bbcode paragraph node.
	 *
	 * @access private
	 * @var int
	 * @see STRINGPARSER_BBCODE_NODE_PARAGRAPH
	 */
	var $_type = STRINGPARSER_BBCODE_NODE_PARAGRAPH;
	
	/**
	 * Determines whether a criterium matches this node
	 *
	 * @access public
	 * @param string $criterium The criterium that is to be checked
	 * @param mixed $value The value that is to be compared
	 * @return bool True if this node matches that criterium
	 */
	function matchesCriterium ($criterium, $value) {
		if ($criterium == 'empty') {
			if (!count ($this->_children)) {
				return true;
			}
			if (count ($this->_children) > 1) {
				return false;
			}
			if ($this->_children[0]->_type != STRINGPARSER_NODE_TEXT) {
				return false;
			}
			if (!strlen ($this->_children[0]->content)) {
				return true;
			}
			if (strlen ($this->_children[0]->content) > 2) {
				return false;
			}
			$f_begin = $this->_children[0]->getFlag ('newlinemode.begin', 'integer', BBCODE_NEWLINE_PARSE);
			$f_end = $this->_children[0]->getFlag ('newlinemode.end', 'integer', BBCODE_NEWLINE_PARSE);
			$content = $this->_children[0]->content;
			if ($f_begin != BBCODE_NEWLINE_PARSE && $content{0} == "\n") {
				$content = substr ($content, 1);
			}
			if ($f_end != BBCODE_NEWLINE_PARSE && $content{strlen($content)-1} == "\n") {
				$content = substr ($content, 0, -1);
			}
			if (!strlen ($content)) {
				return true;
			}
			return false;
		}
	}
}

/**
 * BBCode String parser element node class
 */
class StringParser_BBCode_Node_Element extends StringParser_Node {
	/**
	 * The type of this node.
	 * 
	 * This node is a bbcode element node.
	 *
	 * @access private
	 * @var int
	 * @see STRINGPARSER_BBCODE_NODE_ELEMENT
	 */
	var $_type = STRINGPARSER_BBCODE_NODE_ELEMENT;
	
	/**
	 * Element name
	 *
	 * @access private
	 * @var string
	 * @see StringParser_BBCode_Node_Element::name
	 * @see StringParser_BBCode_Node_Element::setName
	 * @see StringParser_BBCode_Node_Element::appendToName
	 */
	var $_name = '';
	
	/**
	 * Element flags
	 * 
	 * @access private
	 * @var array
	 */
	var $_flags = array ();
	
	/**
	 * Element attributes
	 * 
	 * @access private
	 * @var array
	 */
	var $_attributes = array ();
	
	/**
	 * Had a close tag
	 *
	 * @access private
	 * @var bool
	 */
	var $_hadCloseTag = false;
	
	/**
	 * Was processed by paragraph handling
	 *
	 * @access private
	 * @var bool
	 */
	var $_paragraphHandled = false;
	
	//////////////////////////////////////////////////
	
	/**
	 * Duplicate this node (but without children / parents)
	 *
	 * @access public
	 * @return object
	 */
	function &duplicate () {
		$newnode =& new StringParser_BBCode_Node_Element ($this->occurredAt);
		$newnode->_name = $this->_name;
		$newnode->_flags = $this->_flags;
		$newnode->_attributes = $this->_attributes;
		$newnode->_hadCloseTag = $this->_hadCloseTag;
		$newnode->_paragraphHandled = $this->_paragraphHandled;
		$newnode->_codeInfo = $this->_codeInfo;
		return $newnode;
	}
	
	/**
	 * Retreive name of this element
	 *
	 * @access public
	 * @return string
	 */
	function name () {
		return $this->_name;
	}
	
	/**
	 * Set name of this element
	 *
	 * @access public
	 * @param string $name The new name of the element
	 */
	function setName ($name) {
		$this->_name = $name;
		return true;
	}
	
	/**
	 * Append to name of this element
	 *
	 * @access public
	 * @param string $chars The chars to append to the name of the element
	 */
	function appendToName ($chars) {
		$this->_name .= $chars;
		return true;
	}
	
	/**
	 * Append to attribute of this element
	 *
	 * @access public
	 * @param string $name The name of the attribute
	 * @param string $chars The chars to append to the attribute of the element
	 */
	function appendToAttribute ($name, $chars) {
		if (!isset ($this->_attributes[$name])) {
			$this->_attributes[$name] = $chars;
			return true;
		}
		$this->_attributes[$name] .= $chars;
		return true;
	}
	
	/**
	 * Set attribute
	 *
	 * @access public
	 * @param string $name The name of the attribute
	 * @param string $value The new value of the attribute
	 */
	function setAttribute ($name, $value) {
		$this->_attributes[$name] = $value;
		return true;
	}
	
	/**
	 * Set code info
	 *
	 * @access public
	 * @param array $info The code info array
	 */
	function setCodeInfo ($info) {
		$this->_codeInfo = $info;
		$this->_flags = $info['flags'];
		return true;
	}
	
	/**
	 * Get attribute value
	 *
	 * @access public
	 * @param string $name The name of the attribute
	 */
	function attribute ($name) {
		if (!isset ($this->_attributes[$name])) {
			return null;
		}
		return $this->_attributes[$name];
	}
	
	/**
	 * Set flag that this element had a close tag
	 *
	 * @access public
	 */
	function setHadCloseTag () {
		$this->_hadCloseTag = true;
	}
	
	/**
	 * Set flag that this element was already processed by paragraph handling
	 *
	 * @access public
	 */
	function setParagraphHandled () {
		$this->_paragraphHandled = true;
	}
	
	/**
	 * Get flag if this element was already processed by paragraph handling
	 *
	 * @access public
	 * @return bool
	 */
	function paragraphHandled () {
		return $this->_paragraphHandled;
	}
	
	/**
	 * Get flag if this element had a close tag
	 *
	 * @access public
	 * @return bool
	 */
	function hadCloseTag () {
		return $this->_hadCloseTag;
	}
	
	/**
	 * Determines whether a criterium matches this node
	 *
	 * @access public
	 * @param string $criterium The criterium that is to be checked
	 * @param mixed $value The value that is to be compared
	 * @return bool True if this node matches that criterium
	 */
	function matchesCriterium ($criterium, $value) {
		if ($criterium == 'tagName') {
			return ($value == $this->_name);
		}
		if ($criterium == 'needsTextNodeModification') {
			return (($this->getFlag ('opentag.before.newline', 'integer', BBCODE_NEWLINE_PARSE) != BBCODE_NEWLINE_PARSE || $this->getFlag ('opentag.after.newline', 'integer', BBCODE_NEWLINE_PARSE) != BBCODE_NEWLINE_PARSE || ($this->_hadCloseTag && ($this->getFlag ('closetag.before.newline', 'integer', BBCODE_NEWLINE_PARSE) != BBCODE_NEWLINE_PARSE || $this->getFlag ('closetag.after.newline', 'integer', BBCODE_NEWLINE_PARSE) != BBCODE_NEWLINE_PARSE))) == (bool)$value);
		}
		if (substr ($criterium, 0, 5) == 'flag:') {
			$criterium = substr ($criterium, 5);
			return ($this->getFlag ($criterium) == $value);
		}
		if (substr ($criterium, 0, 6) == '!flag:') {
			$criterium = substr ($criterium, 6);
			return ($this->getFlag ($criterium) != $value);
		}
		if (substr ($criterium, 0, 6) == 'flag=:') {
			$criterium = substr ($criterium, 6);
			return ($this->getFlag ($criterium) === $value);
		}
		if (substr ($criterium, 0, 7) == '!flag=:') {
			$criterium = substr ($criterium, 7);
			return ($this->getFlag ($criterium) !== $value);
		}
		return parent::matchesCriterium ($criterium, $value);
	}
	
	/**
	 * Get first child if it is a text node
	 *
	 * @access public
	 * @return mixed
	 */
	function &firstChildIfText () {
		$ret =& $this->firstChild ();
		if (is_null ($ret)) {
			return $ret;
		}
		if ($ret->_type != STRINGPARSER_NODE_TEXT) {
			// DON'T DO $ret = null WITHOUT unset BEFORE!
			// ELSE WE WILL ERASE THE NODE ITSELF! EVIL!
			unset ($ret);
			$ret = null;
		}
		return $ret;
	}
	
	/**
	 * Get last child if it is a text node AND if this element had a close tag
	 *
	 * @access public
	 * @return mixed
	 */
	function &lastChildIfText () {
		$ret =& $this->lastChild ();
		if (is_null ($ret)) {
			return $ret;
		}
		if ($ret->_type != STRINGPARSER_NODE_TEXT || !$this->_hadCloseTag) {
			// DON'T DO $ret = null WITHOUT unset BEFORE!
			// ELSE WE WILL ERASE THE NODE ITSELF! EVIL!
			if ($ret->_type != STRINGPARSER_NODE_TEXT && !$ret->hadCloseTag ()) {
				$ret2 =& $ret->_findPrevAdjentTextNodeHelper ();
				unset ($ret);
				$ret =& $ret2;
				unset ($ret2);
			} else {
				unset ($ret);
				$ret = null;
			}
		}
		return $ret;
	}
	
	/**
	 * Find next adjent text node after close tag
	 *
	 * returns the node or null if none exists
	 *
	 * @access public
	 * @return mixed
	 */
	function &findNextAdjentTextNode () {
		$ret = null;
		if (is_null ($this->_parent)) {
			return $ret;
		}
		if (!$this->_hadCloseTag) {
			return $ret;
		}
		$ccount = count ($this->_parent->_children);
		$found = false;
		for ($i = 0; $i < $ccount; $i++) {
			if ($this->_parent->_children[$i]->equals ($this)) {
				$found = $i;
				break;
			}
		}
		if ($found === false) {
			return $ret;
		}
		if ($found < $ccount - 1) {
			if ($this->_parent->_children[$found+1]->_type == STRINGPARSER_NODE_TEXT) {
				return $this->_parent->_children[$found+1];
			}
			return $ret;
		}
		if ($this->_parent->_type == STRINGPARSER_BBCODE_NODE_ELEMENT && !$this->_parent->hadCloseTag ()) {
			$ret =& $this->_parent->findNextAdjentTextNode ();
			return $ret;
		}
		return $ret;
	}
	
	/**
	 * Find previous adjent text node before open tag
	 *
	 * returns the node or null if none exists
	 *
	 * @access public
	 * @return mixed
	 */
	function &findPrevAdjentTextNode () {
		$ret = null;
		if (is_null ($this->_parent)) {
			return $ret;
		}
		$ccount = count ($this->_parent->_children);
		$found = false;
		for ($i = 0; $i < $ccount; $i++) {
			if ($this->_parent->_children[$i]->equals ($this)) {
				$found = $i;
				break;
			}
		}
		if ($found === false) {
			return $ret;
		}
		if ($found > 0) {
			if ($this->_parent->_children[$found-1]->_type == STRINGPARSER_NODE_TEXT) {
				return $this->_parent->_children[$found-1];
			}
			if (!$this->_parent->_children[$found-1]->hadCloseTag ()) {
				$ret =& $this->_parent->_children[$found-1]->_findPrevAdjentTextNodeHelper ();
			}
			return $ret;
		}
		return $ret;
	}
	
	function &_findPrevAdjentTextNodeHelper () {
		$lastnode =& $this->lastChild ();
		if ($lastnode->_type == STRINGPARSER_NODE_TEXT) {
			return $lastnode;
		}
		if (!$lastnode->hadCloseTag ()) {
			$ret =& $lastnode->_findPrevAdjentTextNodeHelper ();
		} else {
			$ret = null;
		}
		return $ret;
	}
	
	/**
	 * Get Flag
	 *
	 * @access public
	 * @param string $flag The requested flag
	 * @param string $type The requested type of the return value
	 * @param mixed $default The default return value
	 */
	function getFlag ($flag, $type = 'mixed', $default = null) {
		if (!isset ($this->_flags[$flag])) {
			return $default;
		}
		$return = $this->_flags[$flag];
		if ($type != 'mixed') {
			settype ($return, $type);
		}
		return $return;
	}
	
	/**
	 * Validate code
	 *
	 * @access public
	 */
	function validate () {
		if ($this->_codeInfo['callback_type'] != 'simple_replace' && $this->_codeInfo['callback_type'] != 'simple_replace_single') {
			if (!is_callable ($this->_codeInfo['callback_func'])) {
				return false;
			}
			
			if (($this->_codeInfo['callback_type'] == 'usecontent' || $this->_codeInfo['callback_type'] == 'usecontent?') && count ($this->_children) == 1 && $this->_children[0]->_type == STRINGPARSER_NODE_TEXT) {
				$res = call_user_func ($this->_codeInfo['callback_func'], 'validate', $this->_attributes, $this->_children[0]->content, $this->_codeInfo['callback_params'], $this);
				if ($res) {
					// ok, now, if we've got a usecontent type, set a flag that
					// this may not be broken up by paragraph handling!
					// but PLEASE do NOT change if already set to any other setting
					// than BBCODE_PARAGRAPH_ALLOW_BREAKUP because we could
					// override e.g. BBCODE_PARAGRAPH_BLOCK_ELEMENT!
					$val = $this->getFlag ('paragraph_type', 'integer', BBCODE_PARAGRAPH_ALLOW_BREAKUP);
					if ($val == BBCODE_PARAGRAPH_ALLOW_BREAKUP) {
						$this->_flags['paragraph_type'] = BBCODE_PARAGRAPH_ALLOW_INSIDE;
					}
				}
				return $res;
			}
			
			return call_user_func ($this->_codeInfo['callback_func'], 'validate', $this->_attributes, null, $this->_codeInfo['callback_params'], $this);
		}
		return (bool)(!count ($this->_attributes));
	}
	
	/**
	 * Get replacement
	 *
	 * @access public
	 */
	function getReplacement ($subcontent) {
		if ($this->_codeInfo['callback_type'] == 'simple_replace' || $this->_codeInfo['callback_type'] == 'simple_replace_single') {
			if ($this->_codeInfo['callback_type'] == 'simple_replace_single') {
				if (strlen ($subcontent)) { // can't be!
					return false;
				}
				return $this->_codeInfo['callback_params']['start_tag'];
			}
			return $this->_codeInfo['callback_params']['start_tag'].$subcontent.$this->_codeInfo['callback_params']['end_tag'];
		}
		// else usecontent, usecontent? or callback_replace or callback_replace_single
		// => call function (the function is callable, determined in validate()!)
		return call_user_func ($this->_codeInfo['callback_func'], 'output', $this->_attributes, $subcontent, $this->_codeInfo['callback_params'], $this);
	}
	
	/**
	 * Dump this node to a string
	 */
	function _dumpToString () {
		$str = "bbcode \"".substr (preg_replace ('/\s+/', ' ', $this->_name), 0, 40)."\"";
		if (count ($this->_attributes)) {
			$attribs = array_keys ($this->_attributes);
			sort ($attribs);
			$str .= ' (';
			$i = 0;
			foreach ($attribs as $attrib) {
				if ($i != 0) {
					$str .= ', ';
				}
				$str .= $attrib.'="';
				$str .= substr (preg_replace ('/\s+/', ' ', $this->_attributes[$attrib]), 0, 10);
				$str .= '"';
				$i++;
			}
			$str .= ')';
		}
		return $str;
	}
}

?>