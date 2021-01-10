<?php
// -----------------------------------------------------------------------------------
//    ____  _             _____      
//   | __ )| | ___   __ _|  ___|   _ 
//   |  _ \| |/ _ \ / _` | |_ | | | |
//   | |_) | | (_) | (_| |  _|| |_| |
//   |____/|_|\___/ \__, |_|   \__,_|
//                 |___/                     
// 
//   Absurdly simple flat file blog engine written in PHP
// -----------------------------------------------------------------------------------
//   VERSION 1.0.0
// -----------------------------------------------------------------------------------
//   USAGE:  
//
//      $B = new Blogfu(array();
//
//      if ($B->uriExists()) {
//          if ($B->isValidRequest()) {
//              $entry = $B->getEntry();
//              echo $entry->title;
//              echo $entry->body;
//          }
//          else {
//              echo '404';
//          }  
//      }
//      else {
//
//          foreach ($B->getTitles() as $row) {
//              echo $row->date;
//              echo $row->title;
//          }
//      }
// 
// -----------------------------------------------------------------------------------
//  Author   :   Rick Ellis           https://github.com/rickellis/Blogfu
//  License  :   MIT
// -----------------------------------------------------------------------------------

class Blogfu {
    var $uriRequest = '';
    var $marker = array();
    var $options = array(
        'showErrors' => false,
        'baseDir'  => 'content',
        'tocFile'  => '_toc.json',
        'fileExt' => '.md'
    );

   // --------------------------------------------------------------------

    // Constructor. Sets the options and validates them.
    function Blogfu($options = array()) {
        if (is_array($options) and count($options) > 0) {
            foreach ($options as $key => $val) {
                $this->options[$key] = $val;
            }
        }

        if ($this->_validateOptions()) {
            $this->_getUriRequest();
        }
    }

   // --------------------------------------------------------------------

    // Returns true/false if the URI contains a segment
    function uriExists() {
        return ($this->uriRequest !== '') ? true : false;
    }

   // --------------------------------------------------------------------

    // Returns true if the URI corresponds to a blog post file
    function isValidRequest() {

        if ($this->uriExists() === false) {
            return false;
        }

        if (! file_exists($this->_getRequestPath())) {
            $this->_printError('The URI does not correspond to a valid file name.');
            return false;
        }

        return true;
    }

   // --------------------------------------------------------------------

    // Get the blog entry based on the URI request. Returns an object
    function getEntry() {
        
        $titles = $this->_getDecodedTitles();

        if (! isset($titles[$this->uriRequest])) {
            $this->_printError('The URI does not correspond to an item in the TOC file');
            return false;
        }

        $blog = new stdClass; 
        $blog->filename = $this->uriRequest;

        foreach ($titles[$this->uriRequest] as $key => $val) {
            $blog->$key = $val;
        }

        $blog->body = file_get_contents($this->_getRequestPath());
        return $blog;
    }

   // --------------------------------------------------------------------

    // Returns an array with all the info in the TOC file
    function getTitles() {
    
        $i = 1;
        foreach ($this->_getDecodedTitles() as $key => $val) {
            $row = new stdClass;
            $row->filename = $key;

            foreach ($val as $k => $v) {
                $row->$k = $v;
            }
            $result[$i] = $row;
            $i++;
        }

        return $result;
    }

   // --------------------------------------------------------------------

    // Load the TOC Jason file and decode it
    private function _getDecodedTitles() {

        $titles = json_decode(file_get_contents($this->_getTocPath()), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->_printError('JSON ERROR: '.json_last_error_msg());
            return array();
        }

        return $titles;
    }

   // --------------------------------------------------------------------

    // Returns the full path to the TOC file
    private function _getTocPath() {
        return $this->_getOption('baseDir') . $this->_getOption('tocFile');
    }

   // --------------------------------------------------------------------

    // Returns the full path to the requestted blog post
    private function _getRequestPath() {
        return $this->_getOption('baseDir'). $this->uriRequest . $this->_getOption('fileExt');
    }

   // --------------------------------------------------------------------

    // Validates all the options passed to the constructor
    private function _validateOptions() {

        if (! is_bool($this->_getOption('showErrors'))) {
            $this->_setOption('showErrors', true);
            $this->_printError('The showErrors option must be a boolean value (true/false)');
            return false;
        }

        // Validate the baseDir 
        $path = $this->_getOption('baseDir');

        // Resolve the absolute path if possible
        if (realpath($path) !== FALSE)
        {
            $path = realpath($path).'/';
        }
        
        // Add trailing slash if missing
        $path = rtrim($path, '/').'/';

        // Does the directory exist?
        if (! is_dir($path)) {
            $this->_printError('The specified path is not valid: ' . $this->_getOption('baseDir'));
            return false;
        }

        $this->_setOption('baseDir', $path);

        // Validate table of contents file
        if (! file_exists($this->_getTocPath())) {
            $this->_printError('The table of contents file does not exits:  ' . $this->_getTocPath());
            return false;
        }

        // Validate file extension
        $ext = $this->_getOption('fileExt');
        if (substr($ext, 0, 1) != '.') {
            $ext = '.'.$ext;
            $this->_setOption('fileExt', $ext);
        }

        return true;
    }

   // --------------------------------------------------------------------

    // Fetches the URI segment
    private function _getUriRequest(){
        if (! isset($_SERVER['PATH_INFO'])) {
            return;
        }
        $uri = $_SERVER['PATH_INFO'];
        if ($uri == '/') {
            return;
        }

        if (strpos($uri, '/') !== false) {
            $uri = explode('/', $uri)[1];   
        }

        $this->uriRequest = $uri;
    }

   // --------------------------------------------------------------------

    // Helper function to retrieve an option from the $options array
    private function _getOption($which) {
        if (! isset($this->options[$which])) {
            $this->_printError('Invalid option: ' . $which);
            return false;
        }

        return $this->options[$which];
    }

    // --------------------------------------------------------------------

    // Sets an option in the $options array
    private function _setOption($which, $value) {
        $this->options[$which] = $value;
    }

   // --------------------------------------------------------------------

    // Echos an error if showErrors is set to true 
    private function _printError($str) {
        if ($this->options['showErrors'] === true) {
            echo $str;
        }
    }

	// --------------------------------------------------------------------

    // Set a benchmark marker
    function mark($name)
    {
        $this->marker[$name] = microtime();
    }

	// --------------------------------------------------------------------

    // Calculates the time difference between two marked points.

    function elapsedTime($point1 = 'start', $point2 = 'end', $decimals = 4)
    {
        if ( ! isset($this->marker[$point1]))
        {
            return '';
        }

        if ( ! isset($this->marker[$point2]))
        {
            $this->marker[$point2] = microtime();
        }

        list($sm, $ss) = explode(' ', $this->marker[$point1]);
        list($em, $es) = explode(' ', $this->marker[$point2]);

        return number_format(($em + $es) - ($sm + $ss), $decimals);
    }
}
