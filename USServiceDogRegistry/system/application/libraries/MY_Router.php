<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 * @copyright	Copyright (c) 2006, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 *
 * Description:
 * This library extends the CodeIgniter router class.
 *
 * Install this file as application/libraries/MY_Router.php
 *
 * @version: 4.2.06 (c) Wiredesignz 2008-07-19
 **/

/* define the modules base path */
define('MODBASE', APPPATH.'modules/');

class MY_Router extends CI_Router
{
	function _validate_request($segments)
	{
		( ! isset($segments[1])) AND $segments[1] = 'index';

		/* locate the module controller */
		list($path, $file, $home) = $this->find($segments[1], $segments[0]);

		if ($path === FALSE)

			list($path, $file) = $this->find($segments[0], $segments[0]);

		/* no controllers were found */
		if ($path === FALSE)

			show_404($file);

		// set the directory path
		$this->set_directory(str_replace(APPPATH, '', $path));

		/* remove the directory segment */
		($segments[1] == $file) AND $segments = array_slice($segments, 1);

		/* set the module home */
		($home) AND router::path($home) OR router::path($file);

		return $segments;
	}

	function fetch_directory()
	{
		return ($this->directory) ? '../'.$this->directory : '';
	}

	/** Locate the module controller **/
	function find($file, $path = '', $base = 'controllers/')
	{
		if (($pos = strrpos($file, '/')) !== FALSE)
	    {
			$path  = substr($file, 0, $pos);
			$file  = substr($file, $pos + 1);
	    }

		$path .= '/';

		$paths2scan = array(APPPATH.$base, MODBASE.$path.$base);

		foreach ($paths2scan as $path2)
		{
			foreach (array($file, ucfirst($file)) as $name)
			{
				if (is_file($path2.$name.EXT))

					return array(substr($path2, 0, -1), $name, substr($path, 0, -1));
			}
		}

		/* file not found */
		return array(FALSE, $file, FALSE);
	}
}

class Router
{
	/** Holds the module name **/
	function path($path = NULL)
	{
		static $_path;

		($path) AND $_path = $path;

		return $_path;
	}	
}
