<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Authentication Helper 
 *
 * @version: 1.0.0
 */

	/**
	 * logIn() 
	 *
	 * Sets log in status within the session 
	 **/
	function logIn($username, $admin = false)
	{
		// Set session data
		if( $admin )
		{
			$_SESSION['marcLoginAdmin'] = 1;
		}
		else
		{
			$_SESSION['marcLogin'] = 1;
		}
	}

	/**
	 * logOut() 
	 *
	 * Destroys the session
	 **/
	function logOut()
	{
		// Destroy session data
		unset( $_SESSION['marcLogin'] );
		unset( $_SESSION['marcLoginAdmin'] );
	}

	/**
	 * isLoggedIn() 
	 *
	 * Verifies that the user has a valid auth session 
	 **/
	function isLoggedIn()
	{
		// Check for session and makes sure the IP is
		// the same.
		return isset( $_SESSION['marcLogin'] );
	}

	/**
	 * isLoggedInAdmin() 
	 *
	 * Verifies that the user has a valid auth session AND
	 * that the session is labeled for admin 
	 **/
	function isLoggedInAdmin()
	{
		// Check for session and makes sure the IP is
		// the same.
		return isset( $_SESSION['marcLoginAdmin'] );
	}
