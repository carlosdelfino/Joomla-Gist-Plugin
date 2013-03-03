<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.gist
 *
 * @copyright   Copyright (C) 2013 Juicy Media Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * GitHub Gist plugin class.
 *
 * @package     Joomla.Plugin
 * @subpackage  Content.gist
 * @since       1.5
 */
class plgContentGist extends JPlugin
{
	/**
	 * Plugin that replaces a custom Gist tag with the embedded JS code provided by GitHub.
	 *
	 * @param   string	The context of the content being passed to the plugin.
	 * @param   mixed	An object with a "text" property or the string to be checked.
	 * @param   array     Additional parameters. See {@see plgEmailCloak()}.
	 * @param   integer  Optional page number. Unused. Defaults to zero.
	 * @return  boolean  True on success.
	 */
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{
		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer')
		{
			return true;
		}
		// if the value gist is not in the page exit, no need to process
		if (JString::strpos($row->text, 'gist>') === false)
		{
			return true;
		}		
		
		if (is_object($row))
		{
			return $this->_gist($row->text, $params);
		}
		return $this->_gist($row, $params);
	}

	/**
	 * Create a Gist based Javascript.
	 *
	 * @param   string  The string to be cloaked.
	 * @param   array   Additional parameters.
	 * @return  boolean  True on success.
	 * 
	 */
	protected function _gist(&$text, &$params)
	{	
		// pickup the username from the param field
		//$username = (string) $this->params->def('github_username', 'unknown');
		
		// find the custom gist tag
		$pattern = "/<gist>(.*?)<\/gist>/";

		// loop through every occurrence
		while (preg_match($pattern, $text, $regs, PREG_OFFSET_CAPTURE))
		{
			// make sure the ID is an int
			$gist_id = (int) trim($regs[1][0]);
			
			// create a link for Gist
			//$replacement = '<script src="https://gist.github.com/'.$username.'/'.$gist_id.'.js"></script>';
			$replacement = '<script src="https://gist.github.com/'.$gist_id.'.js"></script>';
			
			// output the correct link replacing the id and tag in the page
			$text = substr_replace($text, $replacement, $regs[0][1], strlen($regs[0][0]));
		}

		return true;
	}
}
