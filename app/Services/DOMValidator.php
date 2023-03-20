<?php

namespace App\Services;

use DOMDocument;
use DOMException;
use Exception;
use Illuminate\Support\Facades\File;

class DOMValidator
{
    /**
     * @var DOMDocument
     */
    protected $handler;
    /**
     * @var string
     */
    protected $schema;
    /**
     * @var int
     */
    public $feedErrors = 0;
    /**
     * Formatted libxml Error details
     *
     * @var array
     */
    public $errorDetails;

    /**
     * Validation Class constructor Instantiating DOMDocument
     *
     * @param DOMDocument $handler [description]
     */
    public function __construct($schema)
    {
        $this->handler = new DOMDocument('1.0', 'utf-8');
        $this->schema = $schema;
    }
    /**
     * @param \libXMLError object $error
     *
     * @return string
     */
    private function libxmlDisplayError($error)
    {
        $errorString = "Error $error->code in $error->file (Line:{$error->line}):";
        $errorString .= trim($error->message);
        return $errorString;
    }
    /**
     * @return array
     */
    private function libxmlDisplayErrors()
    {
        $errors = libxml_get_errors();
        $result = [];

        foreach ($errors as $error) {
            $result[] = $this->libxmlDisplayError($error);
        }
        libxml_clear_errors();
        return $result;
    }

    /**
     * Validate Incoming XML against Listing Schema
     *
     * @param string $file
     *
     * @return bool
     *
     * @throws Exception
     */
    public function validateWithXsd($file)
    {
        if (!class_exists('DOMDocument')) {
            throw new DOMException("'DOMDocument' class not found!");
            return false;
        }
        if (!File::exists($this->schema)) {
            throw new Exception('Schema is Missing, Please add schema to schema property');
            return false;
        }
        libxml_use_internal_errors(true);
        if (!($fp = fopen($file, "r"))) {
            die("Could not open XML input");
        }

        $contents = fread($fp, filesize($file));
        fclose($fp);

        $this->handler->loadXML($contents, LIBXML_NOBLANKS);
        if (!$this->handler->schemaValidate($this->schema)) {
            $this->errorDetails = $this->libxmlDisplayErrors();
            $this->feedErrors   = 1;
        } else {
            //The file is valid
            return true;
        }
    }

    /**
     * Validate Incoming XML against Listing Schema
     *
     * @param string $file
     *
     * @return bool
     *
     * @throws Exception
     */
    public function validateWithRng($file)
    {
        if (!class_exists('DOMDocument')) {
            throw new DOMException("'DOMDocument' class not found!");
            return false;
        }
        if (!File::exists($this->schema)) {
            throw new Exception('Schema is Missing, Please add schema to schema property');
            return false;
        }
        libxml_use_internal_errors(true);
        if (!($fp = fopen($file, "r"))) {
            die("Could not open XML input");
        }

        $contents = fread($fp, filesize($file));
        fclose($fp);

        $this->handler->loadXML($contents, LIBXML_NOBLANKS);
        if (!$this->handler->relaxNGValidate($this->schema)) {
            $this->errorDetails = $this->libxmlDisplayErrors();
            $this->feedErrors   = 1;
        } else {
            //The file is valid
            return true;
        }
    }
    /**
     * Display Error if Resource is not validated
     *
     * @return array
     */
    public function displayErrors()
    {
        return $this->errorDetails;
    }
}
