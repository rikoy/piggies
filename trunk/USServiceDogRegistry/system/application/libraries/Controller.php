<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Modular Extensions - PHP4
 *
 * Adapted from the CodeIgniter Core Classes
 * @copyright	Copyright (c) 2006, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 *
 * Description:
 * This library is used by CodeIgniter to instantiate controllers
 * and module controllers.
 *
 * Install this file as application/libraries/Controller.php
 *
 * @version: 4.2.06 (c) Wiredesignz 2008-07-19
 **/

/* load the modules helper */
require_once APPPATH.'helpers/modules_helper.php';

/* load the CI_Loader class */
require_once BASEPATH.'libraries/Loader.php';

/** Module controller class **/
class Controller extends CI_Base
{
	/* the module directory */
	var $_module;

	/** Constructor **/
	function Controller()
	{
		parent::CI_Base();

		/* set the module loader */
		$this->load =& $this;
				
		/* set module directory*/
		$this->_module = modules_path();
		
		log_message('debug', get_class($this)." Controller Initialized");

		/* assign parent libraries */
		$this->_assign_libraries();

		/* autoload module items */
		$this->_autoloader();
	}

	/** Assign parent libraries **/
	function _assign_libraries()
	{
		if ($ci =& modules_instance())
		{
			$class = strtolower(get_class($this));

			foreach (get_object_vars($ci) as $key => $object)
	        {
				if ( ! is_object($object) OR isset($this->$key) OR $key == $class)

					continue;
					
				$this->$key =& $ci->$key;
	        }
			
			/* reset the instance */
			modules_instance($this);
        }
		else
		{
			/* get reference to CI core */
			modules_instance(get_instance());
			
			/* create CI_Loader instance */
			$this->loader =& new CI_Loader();
			
			/* CI core classes */
			$classes = array(
				'config'	=> 'Config',
				'input'		=> 'Input',
				'benchmark'	=> 'Benchmark',
				'uri'		=> 'URI',
				'output'	=> 'Output',
				'lang'		=> 'Language',
			);
	
			/* assign the core classes */
			foreach ($classes as $var => $class)
			{
				( ! isset($this->$var)) AND	$this->$var =& load_class($class);
			}	
			
			/* autoload application items */
			$this->loader->_ci_autoloader();		
		}
	}

	/** Return current controller instance **/
	function &instance()
	{
		$instance =& modules_instance();

		return $instance;
	}
	
//-loader----------------------------------------------------------------------

	/** Load a module config file **/
	function config($file = '', $use_sections = FALSE)
	{
		$file = ($file == '') ? 'config' : modules_decode($file);

		if (in_array($file, $this->config->is_loaded, TRUE))

			return;

		list($path, $file) = modules_find($file, $this->_module, 'config/');
		
		if ($path === FALSE)
		{
			$this->loader->config($file, TRUE);

			return;
		}

		if ($config = modules_load_file($file, $path, 'config'))
		{
			/* reference to the config object */
			$current_config =& $this->config->config;

			if ($use_sections === TRUE)
			{
				if (isset($current_config[$file]))
				{
					$current_config[$file] = array_merge($current_config[$file], $config);
				}
				else
				{
					$current_config[$file] = $config;
				}
			}
			else
			{
				$current_config = array_merge($current_config, $config);
			}

			$this->config->is_loaded[] = $file;

			unset($config);
		}
	}

	/** Load the database drivers **/
	function database($params = '', $return = FALSE, $active_record = FALSE)
	{
		if (class_exists('CI_DB') AND $return == FALSE AND $active_record == FALSE)

			return FALSE;

		require_once(BASEPATH.'database/DB'.EXT);

		if ($return === TRUE)

			return DB($params, $active_record);

		$this->db =& DB($params, $active_record);

		$this->_assign_to_models();
	}

	/** Load dbforge **/
	function dbforge()
	{
		$this->loader->dbforge();
	}

	/** Load dbutil **/
	function dbutil()
	{
		$this->loader->dbutil();
	}

	/** Load files **/
	function file($path, $return = FALSE)
	{
		$this->loader->file($path, $return);
	}

	/** Load a module helper **/
	function helper($helper)
	{
	 	if (is_array($helper))
		{
			foreach ($helper as $item)
			{
				$this->helper($item);
			}

			return;
		}
		
		$helper = modules_decode($helper);

		if (isset($this->loader->_ci_helpers[$helper]))

			return;

		list($path, $helper) = modules_find($helper.'_helper', $this->_module, 'helpers/');

		if ($path === FALSE)
		{
			$this->loader->helper($helper);

			return;
		}
		else
		{
			modules_load_file($helper, $path);

			$this->loader->_ci_helpers[$helper] = TRUE;
		}
	}

	/** Load a module language file **/
	function language($langfile, $lang = '')
	{
		$deft_lang = $this->config->item('language');

		$idiom = ($lang == '') ? $deft_lang : $lang;

		$_langfile = modules_decode($langfile);

		if (in_array($_langfile.EXT, $this->lang->is_loaded, TRUE))

			return;

		/* get this module subpath */
		$subpath = strtolower(get_class($this));

		list($path, $langfile) = modules_find($idiom.'/'.$_langfile.'_lang', $this->_module, 'language/', $subpath);

		if ($path === FALSE)
		{
			$this->loader->language($_langfile, $lang);

			return;
		}

		/* reference to language object */
		$current_language =& $this->lang->language;

		if($lang = modules_load_file($langfile, $path, 'lang'))
		{
			$current_language = array_merge($current_language, $lang);

			$this->lang->is_loaded[] = $langfile.EXT;

			unset($lang);
		}
	}

	/** Load a module library **/
	function library($library, $params = NULL)
    {
		$_library = modules_decode($library);

		if (isset($this->$_library))

		   return $this->$_library;

		list($path, $library) = modules_find($library, $this->_module, 'libraries/');

		if ($path === FALSE)
        {
		   $this->loader->_ci_load_class($library, $params);
     	}
		else
		{
			modules_load_file($library, $path);

			$library = ucfirst($library);

			$this->$_library = new $library($params);
		}
		
		$this->_assign_to_models();
    }

	/** Load a module method **/
	function method($method, $params = NULL)
	{
		if (method_exists($this, $method))

			return $this->$method($params);

		$_method = modules_decode($method);

		list($path, $method, $home) = modules_find($method, $this->_module, 'methods/');

		modules_load_file($method, $path);

		$method = ucfirst($method);

		$_method = new $method();

		return $_method->index($params);
	}

	/** Load a module model **/
	function model($model, $alias = FALSE, $connect = FALSE)
	{
		$_alias = ($alias === FALSE) ? modules_decode($model) : strtolower($alias);

		if (isset($this->$_alias))

			return $this->$_alias;
		
		list($path, $model) = modules_find($model, $this->_module, 'models/');

		(class_exists('Model')) OR load_class('Model', FALSE);

		($connect === TRUE) AND $this->database();

		modules_load_file($model, $path);

		$this->loader->_ci_models[] = $_alias;

		$model = ucfirst($model);

		$this->$_alias = new $model();
	}

	/** Load a module controller **/
	function module($module, $params = NULL)
	{
		(is_array($module)) AND list($module, $params) = each($module);

		$controller = modules_decode($module);

		/* don't try to reload self */
		if ($controller == strtolower(get_class($this)))

			return;

		/* don't reload an existing controller */
		if (isset($this->$controller))

			return;

		$this->$controller = modules_load(array($module => $params));
	}

	/** Load a module plugin **/
	function plugin($plugin)
	{
		if (is_array($plugin))
		{
			$this->loader->plugin($plugin);

			return;
		}

		$plugin = modules_decode($plugin);

		if (isset($this->loader->_ci_plugins[$plugin]))

			return;

		list($path, $_plugin) = modules_find($plugin.'_pi', $this->_module, 'plugins/');

		if ($path === FALSE)
		{
			$this->loader->plugin($plugin);

			return;
		}

		modules_load_file($_plugin, $path);

		$this->loader->_ci_plugins[$plugin] = TRUE;
	}

	/** Load scripts **/
	function script($scripts = array())
	{
		$this->loader->script($scripts);
	}

	/** Load variables to output buffer **/
	function vars($vars = array())
	{
		$this->loader->vars($vars);
	}

	/** Load a module view **/
	function view($view, $vars = array(), $return = FALSE)
	{
		/* get this module subpath */
		$subpath = strtolower(get_class($this));
		
		list($path, $view) = modules_find($view, $this->_module, 'views/', $subpath);

		$this->loader->_ci_view_path = $path;

		return $this->loader->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->loader->_ci_object_to_array($vars), '_ci_return' => $return));
	}

//-autoloader------------------------------------------------------------------

	function _autoloader()
	{
		list($path, $file) = modules_find('autoload', $this->_module, 'config/');

		if ($path === FALSE)
		{
			if (isset($this->autoload))
			{
				/* use autoload class var array */
				$autoload = $this->autoload;
			}
			else

				return;
		}
		else

			$autoload = modules_load_file($file, $path, 'autoload');

		/* autoload config */
		if (isset($autoload['config']))
		{
			foreach ($autoload['config'] as $key => $val)
			{
				$this->config($val, TRUE);
			}
		}

		/* autoload helpers, plugins, scripts, languages */
		foreach (array('helper', 'plugin', 'script', 'language') as $type)
		{
			if (isset($autoload[$type]))
			{
				foreach ($autoload[$type] as $item)
				{
					$this->$type($item);
				}
			}
		}

		/* autoload database & libraries */
		if (isset($autoload['libraries']))
		{
			if (in_array('database', $autoload['libraries']))
			{
				/* autoload database */
				if ( ! $db = $this->config->item('database'))
				{
					$db['params'] = "default";
					$db['active_record'] = TRUE;
				}

				$this->db = $this->database($db['params'], TRUE, $db['active_record']);

				$autoload['libraries'] = array_diff($autoload['libraries'], array('database'));
			}

			/* autoload libraries */
			foreach ($autoload['libraries'] as $library)
			{
				$this->library($library);
			}
		}

		/* autoload models */
		if (isset($autoload['model']))
		{
			foreach ($autoload['model'] as $model => $alias)
			{
				(is_numeric($model)) ? $this->model($alias) : $this->model($model, $alias);
			}
		}

		/* get this module subpath */
		$class = strtolower(get_class($this));

		/* autoload module controllers */
		if (isset($autoload['modules']) AND ! in_array($class, $autoload['modules']))
		{
			foreach ($autoload['modules'] as $item)
			{
				$this->module($item);
			}

			/* reset after autoload */
			modules_instance($this);
		}

		unset($autoload);
	}

//-assign-libraries-to-models--------------------------------------------------

	function _assign_to_models()
	{
		if (isset($this->loader->_ci_models))
		{
			foreach ($this->loader->_ci_models as $model)
			{
				if (isset($this->$model))

					$this->$model->_assign_libraries();
			}
		}
	}
}