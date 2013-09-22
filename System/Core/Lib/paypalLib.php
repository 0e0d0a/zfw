<?php

class paypalLib  extends Core {
    /**
     * @var \layoutI18n
     */
    private $_i18n;

    private $_lastError;   // holds the last error encountered
    private $ipn_log;    // bool: log IPN results to text file?
    private $ipn_log_file;   // filename of the IPN log
    private $ipn_response;   // holds the IPN response from paypal	
    private $ipn_data = array(); // array contains the POST values for IPN
    private $fields = array();  // array holds the fields to submit to paypal
    private $submit_btn = '';  // Image/Form button
    private $button_path = '';  // The path of the buttons

    public function __construct() {
        //$this->_i18n = $i18n;
        
        $this->paypal_url = $this->cfg()->get('paypal', 'paypalUrl');
        $this->_lastError = '';
        $this->ipn_response = '';

        $this->ipn_log_file = $this->cfg()->get('paypal', 'paypal_lib_ipn_log_file');
        $this->ipn_log = $this->cfg()->get('paypal', 'paypal_lib_ipn_log');

        $this->button_path = $this->cfg()->get('paypal', 'paypal_lib_button_path');

        // populate $fields array with a few default values.  See the paypal
        // documentation for a list of fields and their data types. These defaul
        // values can be overwritten by the calling script.
        $this->add_field('rm', '2');     // Return method = POST
        $this->add_field('cmd', '_xclick');

        $this->add_field('currency_code', $this->cfg()->get('paypal', 'currencyCode'));
        //$this->add_field('quantity', '1');
    }

    function button($value) {
        // changes the default caption of the submit button
        $this->submit_btn = form_submit('pp_submit', $value);
    }

    function image($file) {
        $this->submit_btn = '<input type="image" name="add" src="' . site_url($this->button_path . '/' . $file) . '" border="0" />';
    }

    function add_field($field, $value) {
        // adds a key=>value pair to the fields array, which is what will be 
        // sent to paypal as POST variables.  If the value is already in the 
        // array, it will be overwritten.
        $this->fields[$field] = $value;
    }

    function paypalFormFields() {
        $str = '';
        foreach ($this->fields as $name => $value)
            $str .= form_hidden($name, $value) . "\n";
        return $str;
    }
    
    function paypal_form($form_name = 'paypal_form') {
        $str = '<form method="post" action="' . $this->paypal_url . '" name="' . $form_name . '"/>' . "\n";
        $str .= $this->paypalFormFields() . "\n";
        $str .= '<p>' . $this->submit_btn . '</p>';
        $str .= form_close() . "\n";
        return $str;
    }

    function validate_ipn() {
        // parse the paypal URL
        $url_parsed = parse_url($this->paypal_url);

        $magicQuotesExists = function_exists('get_magic_quotes_gpc');
        $post_string = '';
        if ($_POST) {
            foreach ($_POST as $field => $value) {
                $this->ipn_data[$field] = $value;
                $post_string .= $field.'='.(($magicQuotesExists && get_magic_quotes_gpc()==1)?urlencode(stripslashes($value)):urlencode($value)).'&';
            }
        }
        
        $post_string .= 'cmd=_notify-validate'; // append ipn command
        // open the connection to paypal
        $fp = fsockopen('ssl://'.$url_parsed['host'], '443', $err_num, $err_str, 30);
        if (!$fp) {
            // could not open the connection.  If loggin is on, the error message
            // will be in the log.
            $this->_lastError = "fsockopen error no. $errnum: $errstr";
            $this->log_ipn_results(false);
            return false;
        } else {
            // Post the data back to paypal
            fputs($fp, 'POST '.$url_parsed['path']." HTTP/1.1\r\n");
            fputs($fp, 'Host: '.$url_parsed['host']."\r\n");
            fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
            fputs($fp, 'Content-length: '.strlen($post_string)."\r\n");
            fputs($fp, "Connection: close\r\n\r\n");
            fputs($fp, $post_string . "\r\n\r\n");

            // loop through the response from the server and append to variable
            while (!feof($fp))
                $this->ipn_response .= fgets($fp, 1024);

            fclose($fp); // close connection
        }

        if (strpos($this->ipn_response, 'VERIFIED')!==false) {
            // Valid IPN transaction.
            $this->log_ipn_results(true);
            return true;
        } else {
            // Invalid IPN transaction.  Check the log for details.
            $this->_lastError = 'IPN Validation Failed.';
            $this->log_ipn_results(false);
            return false;
        }
    }

    function log_ipn_results($success) {
        if (!$this->ipn_log)
            return;  // is logging turned off?
        // Timestamp
        $text = '[' . date('m/d/Y g:i A') . '] - ';

        // Success or failure being logged?
        if ($success)
            $text .= "SUCCESS!\n";
        else
            $text .= 'FAIL: ' . $this->_lastError . "\n";

        // Log the POST variables
        $text .= "IPN POST Vars from Paypal:\n";
        foreach ($this->ipn_data as $key => $value)
            $text .= "$key=$value, ";

        // Log the response from the paypal server
        $text .= "\nIPN Response from Paypal Server:\n " . $this->ipn_response;

        // Write to log
        $fp = fopen($this->ipn_log_file, 'a');
        fwrite($fp, $text . "\n\n");

        fclose($fp);  // close file
    }

    function dump() {
        // Used for debugging, this function will output all the field/value pairs
        // that are currently defined in the instance of the class using the
        // add_field() function.

        ksort($this->fields);
        echo '<h2>ppal->dump() Output:</h2>' . "\n";
        echo '<code style="font: 12px Monaco, \'Courier New\', Verdana, Sans-serif;  background: #f9f9f9; border: 1px solid #D0D0D0; color: #002166; display: block; margin: 14px 0; padding: 12px 10px;">' . "\n";
        foreach ($this->fields as $key => $value)
            echo '<strong>' . $key . '</strong>:	' . urldecode($value) . '<br/>';
        echo "</code>\n";
    }

}

?>