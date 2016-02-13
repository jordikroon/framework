<?php
/**
 * Framework
 *
 * @author      Jordi Kroon <jordi@jordikroon.nl>
 * @copyright   2014 Jordi Kroon
 * @link        http://www.jordikroon.nl
 * @version     1.0.0
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace System\Framework\Database;

use System\Framework\Config;

/**
 * Database
 *
 * This class will initialize the PDO connection
 *
 * @author  Jordi Kroon <jordi@jordikroon.nl>
 * @since   1.0.0
 * @package Framework
 */
class Database extends \PDO {

    /**
     * Calling the database handler
     */
     
	public function __construct() {

		$config = new Config;
		$config -> loadFile(__dir__ . '/../../../../../Config/application.php');
		$db = $config -> get('database');

		parent::__construct('mysql:dbname=' . $db['database'] . ';host=' . $db['host'], $db['username'], $db['password']);
		$this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

}
