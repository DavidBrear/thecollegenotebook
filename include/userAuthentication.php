<?php

	/**
		Written on December 11th 2007. This file is solely for user authentication. It should not actualy print anything unless from the connection string. 
	*/
	/**
		authUser is a function for authentication of the user. This function takes in an id and retrieves the database entry for that user based on the cookies set on the user's browser. If the query does not return an entry, the user is not properly logged in
		and the function will return false. This is important to make sure users don't create their own cookies and access another user's data.
	*/
	function authUser($id, $connectionString)
	{
		
		if( (isset($_COOKIE['AK']) && (isset($_COOKIE['_sess']) )))
		{
			$userData = mysql_query('SELECT * FROM login WHERE (id='.$id.' AND auth_key="'.$_COOKIE['AK'].'" AND _sess = "'.$_COOKIE['_sess'].'");', $connectionString) or die(mysql_error());
		}
		else
		{
			return false;
		}
		if(mysql_num_rows($userData) == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
?>