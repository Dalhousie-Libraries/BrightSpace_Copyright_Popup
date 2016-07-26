<?php
class d2lException extends Exception{
	protected $httpcode;
	protected $request;
	protected $result;

	public function __construct($message, $httpcode, $request, $result, $code = 0, Exception $previous = null) {
        
    	$this->httpcode = $httpcode;
    	$this->request = $request;
    	$this->message = $message;
    	$this->result = $result;
    	$this->code = $code;
    	
        // make sure everything is assigned properly
        parent::__construct($message, $code);
    }
    
    public function getHttpcode(){
    	return $this->httpcode;
    }
    public function getRequest(){
    	return $this->request;
    }
    public function getResult(){
    	return $this->result;
    }
    public function __toString(){
    	return __CLASS__ . " Status: {$this->httpcode}"
				. "Request: {$this->request}\n"
				. "Result: {$this->result}\n"
				. "Message: '{$this->message}' in {$this->file} ({$this->line})\n"
                                . "{$this->getTraceAsString()}";
    }
}
?>