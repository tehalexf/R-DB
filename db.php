<?php
    $MYSQL_HOSTNAME = "localhost";
    $MYSQL_USER     = "res-ex";
    $MYSQL_PASS     = "shangrila07";
    $MYSQL_PROJ_DB  = "res-ex";

	$mysql_default_connection = mysqli_connect($MYSQL_HOSTNAME,$MYSQL_USER,$MYSQL_PASS, $MYSQL_PROJ_DB) or die(mysql_error());

	function sql_iter($query)
	{
    		return mysqli_fetch_array($query);
    }
	class R_DB
	{
		public $current_connection;
		public $table;
		public $display_matches;

		function __construct($database, $current_connection_select = NULL)
		{
			global $mysql_default_connection;

			$this->current_connection = $mysql_default_connection;
			$this->table = $database;

			if($current_connection_select)
				$this->current_connection = $current_connection_select;
		}
		function db_insert($db_select, $db_data)
		{

			$comma  = 0;
			$db_query_select = "(";
			$db_query_values = "(";
			foreach($db_select as $db_data_select)
		  	{
		  		if($comma != 0)
		  			$db_query_select .= ", ";
		  		else
		  			$comma = 1;

		  		
		  		$db_query_select .= $db_data_select;
		  	}
		  	$db_query_select .= ")";
			$comma  = 0;
			foreach($db_data as $db_data_select)
		  	{
		  		if($comma != 0)
		  			$db_query_values .= ", ";
		  		else
		  			$comma = 1;

		  		
		  		$db_query_values .= "'".mysqli_real_escape_string($this->current_connection, $db_data_select)."'";
		  	}
		  	$db_query_values .= ")";
			$comma  = 0;
		 	$db_final_query = "INSERT INTO ".$this->table." ".$db_query_select." VALUES".$db_query_values.";";
		 	mysqli_query($this->current_connection, $db_final_query) or die(mysql_error());
		}

		function db_delete($condition_array)
		{
			$mysql_query = "DELETE FROM ".$this->table." WHERE (";
			$comma = 0;
			foreach($condition_array as $condition=>$value)
			{
					
  				if($comma != 0)
  					$mysql_query .= ", ";
  				else
  					$comma = 1;

  				$mysql_query .= $condition." = '".$value."'";
			}
			$mysql_query .= ");";
			mysqli_query($this->current_connection, $mysql_query);
		}

		function db_search($condition_array = NULL, $select_options = NULL)
		{
			$mysql_query = "SELECT ";
			if($select_options)
			{
				$comma = 0;
				$mysql_query .= "(";
				foreach($select_options as $option)
  				{
  					
	  				if($comma != 0)
	  					$mysql_query .= ", ";
	  				else
	  					$comma = 1;
	  				$mysql_query .= $option;
  				}
  				$mysql_query .= ") ";
			}
			else
				$mysql_query .= "* ";
			$mysql_query .=  "FROM ".$this->table." WHERE ";
			if($condition_array)
			{
				$comma = 0;
				foreach($condition_array as $condition=>$value)
  				{
	  				if($comma != 0)
	  					$mysql_query .= ", ";
	  				else
	  					$comma = 1;
	  				$mysql_query .= "".$condition." = '".$value."'";
  				}
  				$mysql_query .= ";";
			}
			else
				$mysql_query .= "1;";
			$mysql_search = mysqli_query($this->current_connection, $mysql_query);
			$this->display_matches = mysqli_num_rows($mysql_search);
			return $mysql_search;
		}
		function db_update($condition_array, $select_options = NULL)
		{
			$mysql_query = "UPDATE ".$this->table." SET ";
			$comma = 0;
			foreach($condition_array as $condition=>$value)
			{
  				if($comma != 0)
  					$mysql_query .= ", ";
  				else
  					$comma = 1;
  				$mysql_query .= $condition." = '".$value."'";
			}
			$mysql_query .= " WHERE ";
			if($select_options)
			{
				$comma = 0;
				$mysql_query .= "(";
				foreach($select_options as $condition=>$value)
				{
	  				if($comma != 0)
	  					$mysql_query .= ", ";
	  				else
	  					$comma = 1;
	  				$mysql_query .= $condition."='".$value."'";
				}
				
  				$mysql_query .= "); ";
			}
			else
				$mysql_query .= "1; ";
			echo $mysql_query;
			mysqli_query($this->current_connection, $mysql_query);
		}
	}
?>
