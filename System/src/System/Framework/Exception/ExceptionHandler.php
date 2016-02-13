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
namespace System\Framework\Exception;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use System\Framework\Template\Templating;

/**
 * ExceptionHandler
 *
 * This class will handle the exceptions if not yet handled.
 *
 * @author  Jordi Kroon <jordi@jordikroon.nl>
 * @since   1.0.0
 * @package Framework
 */
class ExceptionHandler
{
    const LOG_DIR = __DIR__ . '/../../../../Logs';

    /**
     * @var \Exception
     */
    private $exception;

    public function __construct($e)
    {
        $this->exception = $e;
    }

    public function save($file)
    {
        $this->assertFileExist($file);

        $log = new Logger(get_class($this->exception));
        $log->pushHandler(new StreamHandler($file, Logger::DEBUG));

        $log->addCritical(sprintf("Exception thrown: %s", $this->exception->__toString()));
    }

    public function getContent()
    {
        $template = new Templating;
        $template->setCacheDir(__dir__ . '/../../../Cache/twig');
        $template->setViewDir(__dir__ . '/../../Views/');

        return $template->getParser()->render('exception.html.twig', array('name' => get_class($this->exception), 'message' => $this->exception->getMessage(), 'stack' => $this->exception->getTraceAsString()));
    }

    protected function assertFileExist($file)
    {
        if (!file_exists(self::LOG_DIR)) {
            throw new \Exception('Log folder not found, please create a Logs folder in the root of your project');
        }

        $path = self::LOG_DIR . '/' . $file;
        if (!file_exists($path)) {
            touch($path);
        }
    }
}
