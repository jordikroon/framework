<?php
ini_set('allow_call_time_pass_reference', 1);
/**
 * Text Parsing functions
 * This class parses bbcode in a given text to html,
 * but not in the way you'd expect. It creates an array
 * in which all possible tags and text is stored, and
 * it loops through, searching for correct tags, and
 * attaches them to a new-built string. This way is
 * much faster and more reliable than usual preg/ereg
 * based parsers, and has taken quite a while;)
 * It also includes the highlighthing class for the
 * <b>Quality Bulletin Board</b>. It is very flexible,
 * and works with language files, which are on
 * their turn based on regular expressions.
 * This makes it all very flexible, and easy to edit.
 *
 * @author       JeXuS <jexus@jexus.net>
 * @package      TP
 * @filesource
 * @version      0.95b
 * @date         12-02-2006
 **/
// full error reporting
//error_reporting (E_ALL);

/**
 * This constant means that a regular expression
 * does not need any modifiers.
 *
 * @name   QBB_NONE
 **/
define('QBB_NONE', 0);
/**
 * This constant means that a regular expression
 * needs the '.' to match newlines too.
 *
 * @name   QBB_DOTALL
 **/
define('QBB_DOTALL', 1);
/**
 * This constant means that a regular expression
 * has to match without case-sensitivity.
 *
 * @name   QBB_CASELESS
 **/
define('QBB_CASELESS', 2);

/**
 * Parse text to bbcode using stacks
 *
 * @author      JeXuS   <jexus@jexus.net>
 * @copyright   (c) 2005-2005 JeXuS.net
 * @package     TP
 *
 * @todo        add smiley support
 * @todo        check if XSS protection is secure
 **/
class UBBParser {
	/**#@+
	 * @access   protected
	 */

	/**
	 * Contains parsed text
	 *
	 * @var      array     _parsed
	 **/
	var $_parsed = array();
	/**
	 * Contains aliases
	 *
	 * @var      array     _alias
	 **/
	var $_alias = array('*' => 'li_short', 'hr' => 'line');
	/**
	 * Parse tags?
	 *
	 * @var      boolean   _bb_mode
	 **/
	var $_bb_mode = true;
	/**
	 * Holds bbcode templates
	 *
	 * @var      array     _tpls
	 **/
	var $_tpls = array();
	/**
	 * Regexes that'll be used
	 *
	 * @var      array     _regexes
	 **/
	var $_regexes = array();
	/**
	 * All other simple replacement
	 *
	 * @var      array     _replaces
	 **/
	var $_replaces = array();
	/**
	 * Check if we're inside tags
	 *
	 * Are we parsing inside a tag that's special?
	 * extra care taken, because tags
	 * may be nested
	 *
	 * @var      array     _in_tag
	 **/
	var $_in_tag = array('list' => 0, 'table' => 0, 'tr' => 0);
	/**
	 * ID for div's
	 *
	 * @var      integer   _curr_id
	 **/
	var $_curr_id = 1;

	var $modtags = 0;

	/**#@-*/

	/**#@+
	 * @access   public
	 **/

	/**
	 * BBcode parsing constructor
	 *
	 * Sets up templates, autolinking etc.
	 *
	 * @param    string   [$tpl_file]
	 * @return   boolean
	 **/
	function __construct($tpl_file = 'bbcode_tpl.htm') {

		// get the data from the tpl file

		$data = file_get_contents(__DIR__ . '/' . $tpl_file);
		$data = str_replace('\\', '\\\\', $data);
		$data = str_replace("'", "\'", $data);

		// replace all bb parts with a PHP piece which will insert it
		// into the tpl array
		$data = preg_replace('/<!-- BEGIN BB ([^\s]+) -->(.*?)<!-- END BB \1 -->/si', "\$this->_tpls['\\1'] = trim ('\\2');", $data);
		// evaluate
		eval($data);

		// some action for the replacement of email links etc.
		$this -> _regexes = array('find' => array(
		// emails
		'~(\s|^)([-a-z_][-a-z0-9._]*@[-a-z0-9_]+(?:\.[-a-z0-9_]+)+)\b~si',
		// links with http, https, ftp or even irc
		'#(^|[ \n\r\t])([a-z0-9]{1,6}://([a-z0-9\-]{1,}(\.?)){1,}[a-z]{2,5}(:[0-9]{2,5}){0,1}((\/|~|\#|\?|=|&amp;|&|\+){1}[a-z0-9\-._%]{0,}){0,})#si',
		// links with www.
		'#(^|[ \n\r\t])((www\.){1}([a-z0-9\-]{1,}(\.?)){1,}[a-z]{2,5}(:[0-9]{2,5}){0,1}((\/|~|\#|\?|=|&amp;|&|\+){1}[a-z0-9\-._%]{0,}){0,})#si'), 'replace' => array('\1<a href="mailto:\2">\2</a>', '\1<a href="\2" class="external" rel="external">\2</a>', '\1<a href="http://\2" class="external" rel="external">\2</a>', ));

		// this can handle anything like smilies.
		$this -> _replace = array('find' => array(), 'replace' => array());

		// and return
		return true;
	}

	/**
	 * Call this to return a parsed text
	 *
	 * @param    string   $text
	 * @return   string
	 **/
	function parse($text, $userrights = '') {

		if (!empty($userrights) && $userrights > 1)// 65 wtf?
		{
			$this -> modtags = 1;
		} else {
			$this -> modtags = 0;
		}

		// remove beginning and ending whitespaces
		$text = trim($text);
		// replace all newlines with a linebreak tag (for later use)
		$text = str_replace(array("\r\n", "\r", "\n"), "[linebreak\0]", $text);

		/*
		 $text = str_replace ('<?', '<?', $text);
		 $text = str_replace ('?>'
		 , '?>', $text);
		 */
		$text = str_replace("[code][linebreak\0]", '[code]', $text);
		$text = preg_replace('{(?<!\[code\])(<\?.+?\?>)}', '[code]$1[/code]', $text);

		// cut the text in pieces so we can easily loop
		$this -> _create_array(htmlspecialchars($text));

		// parse the stack
		$text = $this -> _parse();

		/*
		 $text = str_replace('', '', $text);
		 $text = str_replace('', '', $text);
		 */

		// replace the linebreak tags with normal newlines
		$return = str_replace("[linebreak\0]", "<br />\n", $text);
		// and return
		return $return;
	}

	/**#@-*/

	/**#@+
	 * @access   protected
	 **/

	/**
	 * Parse a template with given variables
	 *
	 * @param    string   $part
	 * @param    array    $args
	 * @return   string
	 **/
	function parse_bb_tpl($part, $args) {
		// just a replace, with evaluation...
		return preg_replace('/{([^}\s]+)}/e', "isset (\$args['\\1']) ? \$args['\\1'] : '';", $this -> _tpls[$part]);
	}

	/**
	 * Cut the text in pieces
	 *
	 * @param    string   $text
	 * @return   boolean
	 **/
	function _create_array($text) {
		// empty the _parsed array
		$this -> _parsed = array();

		// loop as long as the text has content
		while (strlen($text) > 0) {
			// the first found bracket
			$bracket = strpos($text, '[');
			// the second bracket
			$second_bracket = strpos($text, '[', $bracket + 1);
			// and the first closer
			$close = strpos($text, ']');

			// if there isnt a bracket or closer
			if ($bracket === false || $close === false) {
				// the rest is just normal text
				$this -> _parsed[] = $text;
				// return
				return true;
			}

			// loop as long as the [ and ] aren't right
			while ($close < $bracket || $second_bracket < $close) {
				// look if the second bracket comes before the closer
				if ($second_bracket < $close) {
					// if so, $bracket gets the value of $second_bracket
					$bracket = $second_bracket;
					// the second bracket gets a new position
					$second_bracket = strpos($text, '[', $bracket + 1);
					// if that wasn't matched right
					if (!$second_bracket) {
						// the second bracket will be matched after the closer
						$second_bracket = $close + 1;
					}
				}
				// if the closer comes before the [
				if ($close < $bracket) {
					// update the closer
					$close = strpos($text, ']', $close + 1);
				}
			}

			// if the [ and ] seem to be correct
			if ($bracket < $close && $close < $second_bracket) {
				// the text before the tag
				$pre = substr($text, 0, $bracket);
				// if this is empty
				if (strlen($pre) > 0) {
					// add this to the _parsed
					$this -> _parsed[] = $pre;
					// memory saving
					$pre = '';
				}
				// put the text in _parsed
				$this -> _parsed[] = substr($text, $bracket, $close - $bracket + 1);
				// $text has to be updated
				$text = substr($text, $close + 1);
			}
		}
		// return
		return true;
	}

	/**
	 * Parse the text (already cut in pieces)
	 *
	 * @param    array   [$stoppers]
	 * @param    array   [$allow]
	 * @return   string
	 **/
	function _parse($stoppers = array (), $allow = array ()) {
		// if the stack is empty
		if (!$this -> _parsed) {
			// return nothing
			return '';
		}

		// nested level and return text ''
		$level = 0;
		$text = '';

		// simple check so we can easily check
		if (!is_array($stoppers)) {
			$stoppers = array($stoppers);
		}

		// for even levels with bb_mode off
		$stoppers_leveled = array();
		// check for active bb_mode
		if (!$this -> _bb_mode) {
			// if it isn't active, we put all the stoppers in an array
			// so we can check for the correct level
			for ($i = 0, $size = count($stoppers); $i < $size; $i++) {
				// use the tagname function
				$stoppers_leveled[$this -> _find_tagname($stoppers[$i], false, true)] = true;
			}
		}
		// flip the stoppers so we can use isset
		$stoppers = array_flip($stoppers);
		$allow = array_flip($allow);

		// loop the pieces
		while ($piece = array_shift($this -> _parsed)) {
			// if it is a stopper
			if (isset($stoppers[$piece])) {
				// check for zero-level or bb_mode active
				if (($level == 0 && !$this -> _bb_mode) || $this -> _bb_mode) {
					// if correct, return
					return $text;
				}
				// otherwise...
				else {
					// attach this piece
					$text .= $piece;
					// and lower the level
					$level--;
				}
			}
			// if it isn't a stopper
			else {
				// check if we can find a tagname first
				$name = $this -> _find_tagname($piece);
				$name = ($name !== false && isset($this -> _alias[$name])) ? $this -> _alias[$name] : $name;

				$allowed = (isset($allow[$name]) || !$allow) ? true : false;

				// if it was a valid tag, and the correct method exists
				if ($name !== false && method_exists($this, '_parse_bb_' . $name) && $allowed === true) {
					// check if bb_mode is active
					if ($this -> _bb_mode) {
						// if so, find the complete tag without []
						$args = $this -> _find_tagname($piece, true);
						// parse the arguments
						$args = $this -> _parse_bb_arguments($args);
						// the correct method
						$function = '_parse_bb_' . $name;
						// append the parsed text to the text
						$text .= $this -> $function($args);
					}
					// if bb_mode is unactive
					else {
						// check if this is a stopper
						if (isset($stoppers_leveled[$name])) {
							// if so, update the level
							$level++;
						}
						// and append
						$text .= $piece;
					}
				}
				// if it wasn't a tag
				else {
					// check if we're in a disallowed zone
					if (!$allow) {
						// if bb_mode is active
						if ($this -> _bb_mode) {
							// use the regexes to form a nicer text
							$piece = preg_replace($this -> _regexes['find'], $this -> _regexes['replace'], $piece);
							$piece = str_replace($this -> _replace['find'], $this -> _replace['replace'], $piece);
						}
						// just a normal append
						$text .= $piece;
					}
				}
			}
		}
		// after the loop just return
		//return str_ireplace(' kreleger', '', $text);
		return $text;
	}

	/**
	 * find the name of a tag in a piece of the content
	 *
	 * @param    string    $name
	 * @param    boolean   [$complete]
	 * @param    boolean   [$closer]
	 * @return   string
	 **/
	function _find_tagname($name, $complete = false, $closer = false) {
		// check if it could be valid
		if (substr($name, 0, 1) == '[' && substr($name, -1) == ']') {
			// take off the [ and ]
			$name = substr($name, 1, -1);
			// if it matches a /
			if (substr($name, 0, 1) == '/' && $closer !== false) {
				// take the / off too
				$name = substr($name, 1);
			}

			// if it should be the complete tag
			if ($complete !== false) {
				// then return it now
				return $name;
			}
			// otherwise use strtok to match untill ' ' or '='
			return strtok($name, ' =');
		}
		// if it wasn't valid, return boolean false
		return false;
	}

	/**
	 * parse argument strings
	 *
	 * @param    string   $total
	 * @return   array
	 **/
	function _parse_bb_arguments($total) {
		// instantiate for errors
		$matches = array();
		// match everything like argument='blablabla bla' or argument=blabla or even arg='bla\'bla\\'bla\\'
		preg_match_all('/([A-Za-z_]+?)=(?:(\'|&quot;)(.*?(?<!\\\\)(?:\\\\\\\\)*)\2|([^\s]+))' . '(?:\s|$)/s', $total, $matches, PREG_SET_ORDER);

		// put up the arguments array
		$args = array();
		// loop through all matches
		for ($i = 0, $size = count($matches); $i < $size; $i++) {
			// check if the quoted value was empty
			if (trim($matches[$i][3]) == '') {
				// if the unquoted value was empty
				if (trim($matches[$i][4]) == '') {
					// the value is ''
					$value = '';
				}
				// if not
				else {
					// assign the unquoted value
					$value = $matches[$i][4];
				}
			}
			// otherwise
			else {
				// assign the quoted value
				$value = $matches[$i][3];
			}

			// erase whitespaces
			$value = trim($value);
			// if the value wasn't empty
			if ($value != '') {
				// assign this argument to our array
				$args[strtolower($matches[$i][1])] = $value;
			}
		}

		// return the arguments
		return $args;
	}

	/*
	 * THE PARSING FUNCTIONS BEGIN HERE
	 * a function should be built like this:
	 * name: _parse_bb_*tagname*
	 * 1 argument, $args for the given arguments
	 * pickup the content with $this->_parse (array ('stoppers1', 'stoppers2'));
	 * or with $this->_parse ('stopper');
	 * return.
	 **/

	/**
	 * show bold text
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_b($args) {
		return '<b>' . $this -> _parse('[/b]') . '</b>';
	}

	/**
	 * show italic text
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_i($args) {
		return '<i>' . $this -> _parse('[/i]') . '</i>';
	}

	/**
	 * show underlined text
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_u($args) {
		return '<u>' . $this -> _parse('[/u]') . '</u>';
	}

	/**
	 * show striken text
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_s($args) {
		return '<s>' . $this -> _parse('[/s]') . '</s>';
	}

	/**
	 * show subscript text
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_sub($args) {
		return '<sub>' . $this -> _parse('[/sub]') . '</sub>';
	}

	/**
	 * show superscript text
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_sup($args) {
		return '<sup>' . $this -> _parse('[/sup]') . '</sup>';
	}

	function _parse_bb_tab($args) {
		return '<div style="margin: 0 30px;">' . $this -> _parse('[/tab]') . '</div>';
	}

	/**
	 * show colored text
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_color($args) {
		// parse the text
		$text = $this -> _parse('[/color]');

		// check the color
		$color = (isset($args['color'])) ? $args['color'] : false;
		// match it to a simple regex
		$color = (preg_match('/^(?:#(?:[a-f0-9]{3}){1,2}|[a-z]{3,})$/i', $color)) ? $color : false;
		// return a span with the correct style if the color was correct
		return ($color !== false) ? '<span style="color: ' . $color . ';">' . $text . '</span>' : $text;
	}

	/**
	 * show quick links to PHP.net
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_php($args) {
		// parse the text
		$text = $this -> _parse('[/php]');

		$text = str_replace('"', "", $text);
		$text = str_replace('\'', "", $text);
		$text = strip_tags($text);
		return '<a href="http://www.php.net/' . urlencode($text) . '" title="PHP functie ' . htmlspecialchars($text) . '" rel="external" class="external">' . htmlspecialchars($text) . '</a>';
	}

	/**
	 * show YouTube videos
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_youtube($args) {
		// parse the text
		$text = $this -> _parse('[/youtube]');

		$text = str_replace('"', "", $text);
		$text = str_replace('\'', "", $text);
		$text = strip_tags($text);
		return '<iframe width="460" height="320" src="//www.youtube.com/embed/' . htmlspecialchars($text) . '" frameborder="0" allowfullscreen></iframe>';
	}

	/**
	 * show quick links to Google
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_google($args) {
		// parse the text
		$text = $this -> _parse('[/google]');

		$text = str_replace('"', "", $text);
		$text = str_replace('\'', "", $text);
		$text = strip_tags($text);
		// http://www.google.nl/#hl=nl&source=hp&q=testset&btnG=Google+zoeken&oq=testset&aq=f&aqi=g6g-s1g3&aql=undefined&gs_sm=s&gs_upl=706l1129l0l7l4l0l0l0l0l174l465l2.2l4&bav=on.2,or.r_gc.r_pw.&fp=62113c346707e160&biw=1680&bih=836
		return '<a rel="external" class="external" href="http://google.com/#hl=nl&amp;source=hp&amp;q=' . urlencode($text) . '&amp;btnG=Google+zoeken" title="Google ' . $text . '">' . $text . '</a>';
	}

	/**
	 * show offtopic notes in small font
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_offtopic($args) {
		return '<small class="offtopic">Offtopic:<br />' . $this -> _parse('[/offtopic]') . '</small>';
	}

	/**
	 * show source
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_bron($args) {
		return '<span class="small" style="color: #5c5c5c;">Bron: ' . $this -> _parse('[/bron]') . '</span>';
	}

	/**
	 * show text with other size
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_size($args) {
		// parse the text
		$text = $this -> _parse('[/size]');
		// get the size from the argument
		$size = (isset($args['size'])) ? $args['size'] : '';

		// look if the size is correct
		switch ( $size ) {
			// xsmall
			case 'xsmall' :
				$size = '0.7em';
				break;
			// small
			case 'small' :
				$size = '0.9em';
				break;
			case 'kop' :
				$size = '1.2em';
				break;
			// medium
			case 'medium' :
				$size = '0.9em';
				break;
			// large
			case 'large' :
				$size = '18px';
				break;
			// xlarge
			case 'xlarge' :
				$size = '22px';
				break;
			default :
				return $text;
		}
		// return the span-ed text
		return '<span style="font-size: ' . $size . ';">' . $text . '</span>';
	}

	/**
	 * show lists
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_list($args) {
		// add an entry to our array
		$this -> _in_tag['list']++;
		// parse 'till /list
		$text = $this -> _parse('[/list]', array('li', 'li_short'));
		// remove this entry from the array
		$this -> _in_tag['list']--;
		// check if we can find a type, either from the argument type, or list='type'
		$type = (isset($args['list'])) ? $args['list'] : '';
		// if there was no correct type
		if (empty($type) || $type == 'ul') {
			// we just return it unordered
			return '<ul>' . $text . '</ul>';
		}
		// otherwise, we return ordered
		return '<ol type="' . $type . '">' . $text . '</ol>';
	}

	/**
	 * show a list item ([*])
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_li_short($args) {
		// parse untill a linebreak
		$text = $this -> _parse("[linebreak\0]");
		// check if we're inside a list
		return ($this -> _in_tag['list']) ? '<li>' . $text . '</li>' : $text;
	}

	/**
	 * show a normal list item
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_li($args) {
		// thesame as the previous tag, but now 'till the normal ender
		$text = $this -> _parse('[/li]');
		return ($this -> _in_tag['list']) ? '<li>' . $text . '</li>' : $text;
	}

	/**
	 * show a table
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_table($args) {

		// cellpadding
		$padding = (isset($args['padding']) && is_numeric($args['padding'])) ? $args['padding'] : 1;
		// cellspacing
		$spacing = (isset($args['spacing']) && is_numeric($args['spacing'])) ? $args['spacing'] : 1;
		// border
		$border = (isset($args['border']) && is_numeric($args['border'])) ? $args['border'] : 1;

		// add an entry to our array
		$this -> _in_tag['table']++;
		// parse 'till /list
		$text = $this -> _parse('[/table]', array('tr'));
		// remove this entry from the array
		$this -> _in_tag['table']--;
		// return a correct piece
		return sprintf('<table cellpadding="%s" cellspacing="%s" border="%s" class="pr-latest">%s</table>',
		//return sprintf ('<table class="pr-latest">%s</table>',
		$padding, $spacing, $border, $text);
	}

	/**
	 * show a new table row
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_tr($args) {
		$this -> _in_tag['tr']++;
		// parse untill a linebreak
		$text = $this -> _parse('[/tr]', array('td'));
		$this -> _in_tag['tr']--;
		// check if we're inside a list
		return ($this -> _in_tag['table']) ? '<tr>' . $text . '</tr>' : $text;
	}

	/**
	 * show a td
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_td($args) {
		// parse untill a linebreak
		$text = $this -> _parse('[/td]');
		// check if we're inside a list
		return ($this -> _in_tag['tr']) ? '<td>' . $text . '</td>' : $text;
	}

	/**
	 * show a line
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_line($args) {
		// a normal tag, simple line
		return '<hr />';
	}

	/**
	 * show a url
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_url($args) {
		// 2 regexes to validate
		$regex = array(
		// links with: ftp http or https
		'~^(?:https?|ftp|irc)://[-a-z0-9+&@#/%?=\~_|!:,.;]*[-a-z0-9+&@#/%=\~_|]$~i',
		// url's that begin with www. or something like that
		'~^[-a-z0-9+&@#/%?=\~_|!:,.;]+[-a-z0-9+&@#/%=\~_|]$~i');

		// check if it was set there
		if (!isset($args['url'])) {
			// if so, disable ubb_mode
			$this -> _bb_mode = false;
		}
		// parse until /url
		$text = $this -> _parse('[/url]');
		// activate bb_mode no matter what
		$this -> _bb_mode = true;

		// check the url with the argument and the given text
		$url = (isset($args['url'])) ? $args['url'] : ((!empty($text)) ? $text : false);

		// if no input was given
		if ($url === false) {
			// return '' (text is also empty;))
			return '';
		}

		// check the url with 2 regex, first with www, second without
		/*
		 $url = (preg_match ($regex[0], $url))
		 ? $url
		 : (
		 (preg_match ($regex[1], $url))
		 ? 'http://' . $url
		 : false
		 );
		 */
		$url = (!filter_var($url, FILTER_VALIDATE_URL) ? false : $url);

		$text = (empty($text)) ? $url : $text;


		return ($url !== false) ? sprintf('<a href="%s">%s</a>', $url, $text) : $text;
	}

	/**
	 * show bold text
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_email($args) {
		$regex = '/^(?:[-a-z_][-a-z0-9._]*@[-a-z0-9_]+(?:\.[-a-z0-9_]+)+)$/si';

		// check if it was set there
		if (!isset($args['email'])) {
			// if so, disable ubb_mode
			$this -> _bb_mode = false;
		}
		// parse until /url
		$text = $this -> _parse('[/email]');
		// activate bb_mode no matter what
		$this -> _bb_mode = true;

		// check the url with the argument and the given text
		$email = (isset($args['email'])) ? $args['email'] : ((!empty($text)) ? $text : false);

		// if no input was given
		if ($email === false) {
			// return '' (text is also empty;))
			return '';
		}

		// check the email with the regex
		$email = (preg_match($regex, $email)) ? $email : false;
		$text = (empty($text)) ? $email : $text;

		return ($email !== false) ? sprintf('<a href="mailto:%s">%s</a>', $email, $text) : $text;
	}

	/**
	 * show an image
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_img($args) {
		// drop out of bb_mode
		$this -> _bb_mode = false;
		// get the url
		$img = (isset($args['img'])) ? $args['img'] : $this -> _parse('[/img]');

		// go back into bb_mode
		$this -> _bb_mode = true;

		$img = strip_tags($img);
		$img = str_replace('"', '', $img);
		$img = str_replace('\'', '', $img);

		if (!empty($img) && filter_var($img, FILTER_VALIDATE_URL)) {
			$max = 500;
			if ($size = @getimagesize($img)) {
				list($width, $height, $type) = $size;

				if ($width > $max) {
					$nwidth = $max;
					$height = (int) round($height / ($width / $max));
					$width = $nwidth;
				} else {
					$width = $width;
					$height = $height;
				}
			}

			// get some basic arguments
			if (!empty($args['width']))
				$width = $args['width'];
			if (!empty($args['height']))
				$height = $args['height'];
			$alt = (isset($args['alt'])) ? $args['alt'] : $img;

			$return = '';

			// create the return statement
			if (!empty($height) && !empty($width))
				$return .= '<a href="' . $img . '" title="' . htmlspecialchars($img) . '" rel="lightbox">';

			$return .= '<img src="' . $img . '"';
			$return .= ($width) ? ' width="' . $width . '"' : '';
			$return .= ($height) ? ' height="' . $height . '"' : '';
			$return .= ($alt) ? ' alt="' . $alt . '"' : '';
			$return .= ' />';

			if (!empty($height) && !empty($width))
				$return .= '</a>';
		} else
			$return = '<img src="/img/wrong-image.png" title="Wrong image" alt="Wrong image" />';

		// and return
		return $return;
	}

	/**
	 * show a quote
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_quote($args) {
		// set up arguments
		$args = array('QUOTE_BY' => (isset($args['quote']) && !empty($args['quote'])) ? $args['quote'] : 'Quote', 'QUOTE_TEXT' => trim(preg_replace('/(^\[linebreak\0]|\[linebreak\0]$)/', '', $this -> _parse('[/quote]')), "\r\n"));
		// return
		return $this -> parse_bb_tpl('quote', $args);
	}



	/**
	 * show a modedit
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_modedit($args) {
		if ($this -> modtags == 1) {
			// set up arguments

			$args = array('QUOTE_BY' => (isset($args['modedit']) && !empty($args['modedit'])) ? $args['modedit'] : 'Edit', 'QUOTE_TEXT' => trim(preg_replace('/(^\[linebreak\0]|\[linebreak\0]$)/', '', $this -> _parse('[/modedit]')), "\r\n"));
			// return
			return $this -> parse_bb_tpl('modedit', $args);
		}
	}

	/**
	 * show offtopic notes in small font
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_edit($args) {
		return '<div class="Block"><small>Edit:</small><div class="Quote">' . $this -> _parse('[/edit]') . '</div></div>';
	}

	/**
	 * show a code block
	 * code may be highlighted with languages like
	 * PHP, JAVA(script), CSS etc.
	 *
	 * @param    array    args
	 * @return   string
	 * @see      highlighter
	 **/
	function _parse_bb_code($args) {
		// drop out of bb_mode
		$this -> _bb_mode = false;
		// this will parse from till the closer
		$code = $this -> _parse('[/code]');
		// go back in bb_mode
		$this -> _bb_mode = true;

		// take further action to have correct code
		$code = trim(str_replace("[linebreak\0]", "\n", $code), "\r\n");
		$code = str_replace("[code]", '', $code);
		// check for a starting position
		$start = (isset($args['s']) && $args['s'] > 0) ? $args['s'] : 1;
		// count the newlines
		$size = substr_count($code, "\n");
		// update the size
		$size += $start;

		// should we highlight?
		if (!(isset($args['highlight']) && $args['highlight'] == 'no')) {
			// default language is php, but can be different
			$language = (isset($args['lang'])) ? $args['lang'] : 'php';

			// decode html entities
			$code = html_entity_decode($code);
			// start the highlight object.. this does all the work
			$h = new highlighter($language, $code);
			// get the highlighted code
			$code = $h -> get_code();
		}
		// if we should't highlight
		else {
			$language = 'Geen highlighting';
			// do some basic stuff
			$code = str_replace("\t", '    ', $code);
			$code = str_replace(' ', '&nbsp;', $code);
			$code = nl2br($code);
		}

		// create lines
		$lines = '';
		// loop
		for ($i = $start; $i <= $size; $i++) {
			$lines .= $i . '<br />';
		}
		// take away last <br />
		$lines = substr($lines, 0, -6);

		// argument assignment
		$args = array('CODE_NAME' => (isset($args['file'])) ? ' (<i>' . $args['file'] . '</i>)' : '', 'CODE_LANGUAGE' => $language, 'CODE_ID' => 'field_' . $this -> _curr_id++, 'CODE_LEFT_WIDTH' => 14 + ((strlen($size) - 1) * 8), 'CODE_LINES' => $lines, 'CODE_CONTENT' => $code, );

		// return parsed text
		return $this -> parse_bb_tpl('code', $args);
	}

	/**
	 * show a block without parsing
	 *
	 * @param    array   $args
	 * @return   string
	 **/
	function _parse_bb_ignore($args) {
		// drop out of bb_mode
		$this -> _bb_mode = false;
		// parse until /ignore
		$text = $this -> _parse('[/ignore]');
		// go back into bb_mode
		$this -> _bb_mode = true;
		// and simply return
		return $text;
	}

	/**#@-*/
}

/**
 * Highlighting class, supporting multiple
 * languages in the form of language files,
 * using regexes to match keywords etc.
 *
 * @author    JeXuS
 * @package   TP
 **/
class highlighter {
	/**#@+
	 * @access   private
	 **/

	/**
	 * holds the language data
	 *
	 * @var      array     _data
	 **/
	var $_data = array();
	/**
	 * holds the highlighted text
	 *
	 * @var      string    _highlighted
	 **/
	var $_highlighted = '';
	/**
	 * holds the inserted code
	 *
	 * @var      string    _code
	 **/
	var $_code = '';
	/**
	 * holds the style used for highlighting
	 *
	 * @var      string    _span
	 **/
	var $_span = '<span style="%s">%s</span>';

	/**#@-*/

	/**#@+
	 * @access   public
	 **/

	/**
	 * The constructor, this makes sure the language
	 * file is included, and then begins the highlighting
	 * process.
	 *
	 * @param    string   $language
	 * @param    string   $code
	 * @return   boolean
	 **/
	function highlighter($language, $code) {
		// the current directory
		$this_dir = str_replace('\\', '/', dirname(__FILE__));

		// we prefer unix newlines
		$code = str_replace(array("\r\n", "\r"), "\n", $code);
		// erase beginning and ending newlines
		$code = trim($code, "\n");

		// check if the given language is an existing
		// language file
		if (!is_file($language)) {
			// if not, remove all non-alpanumeric character
			$language = preg_replace('~[^A-Za-z0-9]+~', '', $language);
			// and convert it to lowercase
			$language = strtolower($language);

			// take the directory we're in from the __FILE__ constant, and
			// append the filename we want
			$filename = "{$this_dir}/languages/{$language}.lang.php";

			// if the file doesn't exist...
			if (!is_file($filename)) {
				$this -> _highlighted = nl2br(htmlspecialchars($code));
				return true;
			}
			// if the file does exist
			else {
				// include it
				include $filename;
			}
		}
		// if the language is an existing file
		else {
			// include it
			include $language;
		}

		// if the language data isn't set
		if (!isset($language_data)) {
			$this -> _highlighted = nl2br(htmlspecialchars($code));
			return true;
		}

		// set the language data to our member
		$this -> _data = $language_data;
		// prepare all language regexes
		$this -> _prepare_language();
		// put the code in our member
		$this -> _code = $code;
		// memory saving
		$code = '';
		// walk through the code
		$this -> _walk_through();
		// cleanup and finish the code
		$this -> _finish_code();

		// and return true
		return true;
	}

	/**
	 * return parsed code
	 *
	 * @return   string
	 **/
	function get_code() {
		return $this -> _highlighted;
	}

	/**#@-*/

	/**#@+
	 * @access   private
	 **/

	/**
	 * prepare language regexes
	 *
	 * @return   boolean
	 **/
	function _prepare_language() {
		// the things we need to go through
		$correct = array('script_delim', 'comments', 'strings', 'regexes', 'symbols', 'keywords');

		// check if oo_splitters should be done
		if (isset($this -> _data['oo_splitters']) && count($this -> _data['oo_splitters']) > 0) {
			$this -> _data['is_oo_lang'] = true;
			$correct[] = 'oo_splitters';
		}

		// loop through the parts
		foreach ($correct as $part) {
			// check if we've an array to loop, or just one thing
			if (isset($this -> _data[$part][0]) && !is_array($this -> _data[$part][0])) {
				// make it a regular regex, always starting at the beginning
				$this -> _data[$part][0] = '~^' . str_replace('~', '\~', $this -> _data[$part][0]) . '~';
				// try the dotall bit
				$this -> _data[$part][0] .= ($this -> _data[$part][1] & QBB_DOTALL) ? 's' : '';
				// and the caseless bit
				$this -> _data[$part][0] .= ($this -> _data[$part][1] & QBB_CASELESS) ? 'i' : '';
				// still make it an array
				$this -> _data[$part] = array($this -> _data[$part][0]);
			}
			// if it's an array
			else {
				// loop through
				foreach ($this->_data[$part] as $i => $piece) {
					// make it a regular regex, always starting at the beginning
					$this -> _data[$part][$i][0] = '~^' . str_replace('~', '\~', $this -> _data[$part][$i][0]) . '~';
					// try the dotall bit
					$this -> _data[$part][$i][0] .= ($this -> _data[$part][$i][1] & QBB_DOTALL) ? 's' : '';
					// and the caseless bit
					$this -> _data[$part][$i][0] .= ($this -> _data[$part][$i][1] & QBB_CASELESS) ? 'i' : '';
					// change it
					$this -> _data[$part][$i] = $this -> _data[$part][$i][0];
				}
			}
		}
		return true;
	}

	/**
	 * cleanup and finish code
	 *
	 * @return boolean
	 **/
	function _finish_code() {
		// if it's empty
		if (empty($this -> _highlighted)) {
			// return false
			return false;
		}
		// loop as long as the regex is valid
		while (preg_match('~(<span[^>]+>)([^<]+)</span>(\s*)\1~', $this -> _highlighted, $match)) {
			// concat all span's with thesame color next to it
			$this -> _highlighted = str_replace($match[0], $match[1] . $match[2] . $match[3], $this -> _highlighted);
		}
		// replace tabs with four &nbsp;'s, and double spaces with 2 &nbsp;'s
		$this -> _highlighted = str_replace(array("\t", '  '), array('&nbsp;&nbsp;&nbsp;&nbsp;', '&nbsp;&nbsp;'), $this -> _highlighted);
		// graphical fix
		$this -> _highlighted = str_replace("\n ", "\n&nbsp;", $this -> _highlighted);
		// nl2br, losing the \n
		$this -> _highlighted = str_replace("\n", '<br />', $this -> _highlighted);
		// and return
		return true;
	}

	/**
	 * checking if there's a valid match
	 *
	 * @param    integer   &$i
	 * @param    array     $parts
	 * @return   boolean
	 **/
	function _check_for_match(&$i, $parts) {
		// the text to check
		$check_text = substr($this -> _code, $i);

		// loop through the parts given
		foreach ($parts as $part) {
			// go through the regexes
			foreach ($this->_data[$part] as $j => $regex) {
				// try to match
				if (preg_match($regex, $check_text, $match)) {
					// if it's a match with a keyword, and there's a links availabe...
					if ($part == 'keywords' && !empty($this -> _data['links'][$j])) {
						// create a valid link
						$link = sprintf($this -> _data['links'][$j], urlencode($match[0]));
						// attach an underline style
						$style = $this -> _data['styles'][$part][$j] . ' text-decoration: underline;';
						// the piece will be only a link, no span
						$piece = '<a href="' . $link . '" title="' . $link . '" style="' . $style . '" >' . htmlspecialchars($match[0]) . '</a>';
					}
					// if it's a regular piece
					else {
						// just a normal sprintf
						$piece = sprintf($this -> _span, $this -> _data['styles'][$part][$j], htmlspecialchars($match[0]));
					}

					// append the piece
					$this -> _highlighted .= $piece;
					// update &$i
					$i += strlen($match[0]) - 1;
					// return true
					return true;
				}
			}
		}

		// return false
		return false;
	}

	/**
	 * walk through the code, searching
	 * for all kinds of things, comments,
	 * strings, keywords etc.
	 *
	 * @return   boolean
	 **/
	function _walk_through() {
		// fixed length
		$length = strlen($this -> _code);

		// we're not in highlighting
		$in_highlighting = false;
		// should we check for delimiters
		$check_delim = true;
		// if the delimiters array is empty
		if (count($this -> _data['script_delim']) == 0) {
			// we always highlight
			$in_highlighting = true;
			// we don't check on delimiters
			$check_delim = false;
			// prepend the default color now
			$this -> _highlighted .= '<span style="' . $this -> _data['styles']['overall'] . '">';
		}

		// loop through
		for ($i = 0; $i < $length; $i++) {
			// take a big chunk of remaining code
			$text = substr($this -> _code, $i);
			// take the first character
			$first = substr($text, 0, 1);
			// previous character
			$prev = substr($this -> _code, $i - 1, 1);

			// check if the first character is a space
			if (ctype_space($first)) {
				// if so, append it
				$this -> _highlighted .= $first;
				// and continue
				continue;
			}
			/** note: the above increases speed a LOT **/

			// should we check for delimiters...
			if ($check_delim === true && $in_highlighting === false) {
				// if we match a delimiter
				if (preg_match($this -> _data['script_delim']['start'], $text, $match)) {
					// append it to the source
					$this -> _highlighted .= '<span style="' . $this -> _data['styles']['overall'] . '">';
					// we're in highlighting
					$in_highlighting = true;
					// we need to start here again with highlighting
					$i--;
					// free ourselves from the loop
					continue;
				}
				// append the first character
				$this -> _highlighted .= htmlspecialchars($first);
				// and continue
				continue;
			}

			// check if in highlighting
			if ($in_highlighting === true) {
				// check this last
				if ($check_delim === true) {
					// try to match an ending delimiter
					if (preg_match($this -> _data['script_delim']['end'], $text, $match)) {
						// if matched, append it to the current code
						$this -> _highlighted .= htmlspecialchars($match[0]) . '</span>';
						// we've skipped out of highlighting
						$in_highlighting = false;
						// update counter
						$i += strlen($match[0]) - 1;
						// go to the beginning of the loop
						continue;
					}
				}

				// in which parts should we look?
				$find = array('comments', 'strings', 'regexes');
				// simple check if we MAY match keywords
				if (!ctype_alpha($prev) && $prev != '_') {
					// if correct, add it
					$find[] = 'keywords';
				}

				// check for a match, give $i by reference
				if ($this -> _check_for_match($i, $find)) {
					// if matched, continue
					continue;
				}

				// if we've got a `punct` character
				if (ctype_punct($first)) {
					// check if this is an OO lang
					if (isset($this -> _data['is_oo_lang'])) {
						// if so, try to match a object splitter
						if (preg_match($this -> _data['oo_splitters'][0], $text, $match)) {
							// if matched, append this piece
							$this -> _highlighted .= sprintf($this -> _span, $this -> _data['styles']['oo_splitters'][0], htmlspecialchars($match[0]));
							// update $i
							$i += strlen($match[0]) - 1;

							// new piece, not very long
							$text = substr($this -> _code, $i, 100);
							// try to match a method/child
							if (preg_match('~^[a-z*(_][a-z0-9_*]*~i', $text, $match)) {
								// if matched, append it
								$this -> _highlighted .= sprintf($this -> _span, $this -> _data['styles']['oo_methods'][0], htmlspecialchars($match[0]));
								// update $i
								$i += strlen($match[0]) - 1;
							}
							// continue in both cases
							continue;
						}
					}

					// loop through all symbols
					foreach ($this->_data['symbols'] as $j => $regex) {
						// if we find a valid match
						if (preg_match($regex, $text, $match)) {
							// if match, append |* starts to get boring
							$this -> _highlighted .= sprintf($this -> _span, $this -> _data['styles']['symbols'][$j], htmlspecialchars($match[0]));
							// update $i
							$i += strlen($match[0]) - 1;
							// jump out of this loop, and continue the outer
							continue 2;
						}
					}
				}

				// simple check
				if (!ctype_alnum($prev)) {
					// try to match an integer/float
					if (preg_match('~^(?:0x[0-9A-Fa-f]+|[0-9]+(?:\.[0-9]+)?)~', $text, $match)) {
						// if matched, append
						$this -> _highlighted .= sprintf($this -> _span, $this -> _data['styles']['numbers'], htmlspecialchars($match[0]));
						// update $i
						$i += strlen($match[0]) - 1;
						// continue
						continue;
					}
				}
			}
			// append this character
			$this -> _highlighted .= htmlspecialchars(substr($this -> _code, $i, 1));
		}
		// if we're still in highlighting
		if ($in_highlighting === true) {
			// append a closing span
			$this -> _highlighted .= '</span>';
		}

		// return
		return true;
	}

	/**#@-*/
}
