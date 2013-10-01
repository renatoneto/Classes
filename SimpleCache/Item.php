<?php
class SimpleCache_Item 
{

    public $expiration;
    public $content;

    public function __construct($content, $expiration) 
    {
	      $this->expiration = $expiration;
	      $this->content = $content;
    }

    public function __toString() 
    {
	      return serialize($this);
    }

}
