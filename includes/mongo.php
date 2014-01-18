<?php
	if(class_exists('mongo') != true){
	    class mongo{
	    	
			private $connection;
	    	public $db;
			
			function __construct(){
				//$this->connection = new MongoClient("mongodb://alexfaber:lost@linus.mongohq.com:10089/LF");
				$this->db = $this->MongoClient("mongodb://alexfaber:lost@linus.mongohq.com:10089/LF")->LF;
			}
			
			/*
			 * $tags is an array.
			 */
			public function insert($user, $item, $description, $tags, $location){
				// $document = array( "title" => "Calvin and Hobbes", "author" => "Bill Watterson" );
				// $collection->insert($document);
				$date = date('M d Y');
				$document = array("Date Created" => $date, "Item" => $item , "Found_Location" =>  $location, "User" => $user, "Matched" => 0, "Tags" => $tags, "Description" => $description, "Match_id" => "");
				$db->Lost->insert($document);
			}
	    }
	}
?>