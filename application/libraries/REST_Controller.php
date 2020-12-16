<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class REST_Controller extends Controller {
    
    private $method;
    private $format;
    
    private $get_args;
    private $put_args;
    private $args;
    
    // List all supported methods, the first will be the default format
    private $supported_formats = array(
		'xml' 		=> 'application/xml',
		'json' 		=> 'application/json',
		'serialize' => 'text/plain',
		'php' 		=> 'text/plain',
		'html' 		=> 'text/html',
		'csv' 		=> 'application/csv'
	);
    
    // Constructor function
    function __construct()
    {
        parent::Controller();
        
        // Set up our GET variables
    	$this->get_args = $this->uri->uri_to_assoc();
    	
    	// Set up out PUT variables
    	parse_str(file_get_contents('php://input'), $this->put_args);
    	
    	// Merge both for one mega-args variable
    	$this->args = array_merge($this->get_args, $this->put_args);
    	
    	// Which format should the data be returned in?
	    $this->format = $this->_detect_format();
	    
	    // How is this request being made? POST, DELETE, GET, PUT?
	    $this->method = $this->_detect_method();
    }
    
    /* 
     * Remap
     * 
     * Requests are not made to methods directly The request will be for an "object".
     * this simply maps the object and method to the correct Controller method.
     */
    function _remap($object_called)
    {
    	$controller_method = $object_called.'_'.$this->method;
		
		if(method_exists($this, $controller_method))
		{
			$this->$controller_method();
		}
		
		else
		{
			show_404();
		}
    }
    
    /* 
     * Responce
     * 
     * Takes pure data and optionally a status code, then creates the responce
     */
    function response($data = '', $http_code = 200)
    {
        $this->output->set_status_header($http_code);
        
        // If the method exists, call it
        if(method_exists($this, '_'.$this->format))
        {
	    	// Set a XML header
	    	$this->output->set_header('Content-type: '.$this->supported_formats[$this->format]);
    	
        	$formatted_data = $this->{'_'.$this->format}($data);
        	$this->output->set_output( $formatted_data );
        }
        
        else
		{
        	$this->output->set_output( $data );
        }
    }

    
    /* 
     * Detect format
     * 
     * Detect which format should be used to output the data
     */
    private function _detect_format()
    {
    	if(array_key_exists('format', $this->args) && array_key_exists($this->args['format'], $this->supported_formats))
    	{
    		return $this->args['format'];
    	}
    	
    	// If a HTTP_ACCEPT header is present...
	    if($this->input->server('HTTP_ACCEPT'))
	    {
	    	// Check to see if it matches a supported format
	    	foreach(array_keys($this->supported_formats) as $format)
	    	{
		    	if(strpos($this->input->server('HTTP_ACCEPT'), $format) !== FALSE)
		    	{
		    		return $format;
		    	}
	    	}
	    }
	    
	    // If it doesnt match any or no HTTP_ACCEPT header exists, uses the first (default) supported format
	    list($default)=array_keys($this->supported_formats);
	    return $default;
    }
    
    
    /* 
     * Detect method
     * 
     * Detect which method (POST, PUT, GET, DELETE) is being used
     */
    private function _detect_method()
    {
    	$method = strtolower($this->input->server('REQUEST_METHOD'));
    	if(in_array($method, array('get', 'delete', 'post', 'put')))
    	{
	    	return $method;
    	}

    	return 'get';
    }
    
    
    // INPUT FUNCTION --------------------------------------------------------------
    
    public function get($key)
    {
    	return array_key_exists($key, $this->get_args) ? $this->input->xss_clean( $this->get_args[$key] ) : '' ;
    }
    
    public function post($key)
    {
    	return $this->input->post($key);
    }
    
    public function put($key)
    {
    	return array_key_exists($key, $this->put_args) ? $this->input->xss_clean( $this->put_args[$key] ) : '' ;
    }
    
    // FORMATING FUNCTIONS ---------------------------------------------------------
    
    // Format XML for output
    private function _xml($data = array(), $structure = NULL, $basenode = 'xml')
    {
    	// turn off compatibility mode as simple xml throws a wobbly if you don't.
		if (ini_get('zend.ze1_compatibility_mode') == 1)
		{
			ini_set ('zend.ze1_compatibility_mode', 0);
		}

		if ($structure == NULL)
		{
			$structure = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$basenode />");
		}

		// loop through the data passed in.
		foreach($data as $key => $value)
		{
			// no numeric keys in our xml please!
			if (is_numeric($key))
			{
				// make string key...
				//$key = "item_". (string) $key;
				$key = "item";
			}

			// replace anything not alpha numeric
			$key = preg_replace('/[^a-z0-9_-]/i', '', $key);

			// if there is another array found recrusively call this function
			if (is_array($value))
			{
				$node = $structure->addChild($key);
				// recrusive call.
				$this->_xml($value, $node, $basenode);
			}
			else
			{
				// add single node.

				$value = htmlentities($value, ENT_NOQUOTES, "UTF-8");

				$UsedKeys[] = $key;

				$structure->addChild($key, $value);
			}

		}
    	
		// pass back as string. or simple xml object if you want!
		return $structure->asXML();
    }
    
    // Format HTML for output
    private function _html($data = array())
    {
		// Multi-dimentional array
		if(isset($data[0]))
		{
			$headings = array_keys($data[0]);
		}
		
		// Single array
		else
		{
			$headings = array_keys($data);
		}
		
		$this->load->library('table');
		
		$this->table->set_heading($headings);
		
		foreach($data as &$row)
		{
			$this->table->add_row($row);
		}
		
		return $this->table->generate();
    }
    
    // Format HTML for output
    private function _csv($data = array())
    {
		// Multi-dimentional array
		if(isset($data[0]))
		{
			$headings = array_keys($data[0]);
		}
		
		// Single array
		else
		{
			$headings = array_keys($data);
		}
		
		$output = implode(',', $headings)."\r\n";
		
		foreach($data as &$row)
		{
			$output .= '"'.implode('","',$row)."\"\r\n";
		}
		
		return $output;
    }
    
    // Encode as JSON
    private function _json($data = array())
    {
    	return json_encode($data);
    }
    
    // Encode as Serialized array
    private function _serialize($data = array())
    {
    	return serialize($data);
    }
    
    // Encode raw PHP
    private function _php($data = array())
    {
    	return var_export($data);
    }
    
    
}
?>