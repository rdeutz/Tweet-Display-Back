<?php
/**
 * Tweet Display Back Module for Joomla!
 *
 * @package    TweetDisplayBack
 *
 * @copyright  Copyright (C) 2010-2011 Michael Babker. All rights reserved.
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die;

require_once JPATH_SITE . '/modules/mod_tweetdisplayback/helper.php';

/**
 * Field type to display the version and check for a newer version.
 *
 * @package  TweetDisplayBack
 * @since    2.1
 */
class JFormFieldVersion extends JFormField
{
	/**
	* The form field type.
	*
	* @var    string
	* @since  2.1
	*/
	protected $type = 'Version';

	/**
	 * Method to get the field input.
	 *
	 * @return  string
	 *
	 * @since   2.1
	 */
	protected function getInput()
	{
		return '';
	}

	/**
	 * Method to get the field label.
	 *
	 * @return  string  A message containing the installed version and,
	 *                  if necessary, information on a new version.
	 *
	 * @since   2.1
	 */
	protected function getLabel()
	{
		// Check if cURL is loaded; if not, proceed no further
		if (!extension_loaded('curl'))
		{
			return JText::_('MOD_TWEETDISPLAYBACK_ERROR_NOCURL');
		}
		//If cURL is supported, check the current version available.
		else
		{
			// Get the module's XML
			$xmlfile = JPATH_SITE . '/modules/mod_tweetdisplayback/mod_tweetdisplayback.xml';
			$data = JApplicationHelper::parseXMLInstallFile($xmlfile);

			// The module's version
			$version = substr($data['version'], 0, 5);

			// The target to check against
			$target = 'http://www.babdev.com/updates/TDB_version';

			// Get the JSON data
			$update = ModTweetDisplayBackHelper::getJSON($target);

			// Message containing the version
			$message = '<label style="max-width:100%">' . JText::sprintf('MOD_TWEETDISPLAYBACK_VERSION_INSTALLED', $version);

			// If an update is available, notify the user
			if (version_compare($update['version'], $version, 'gt'))
			{
				$message .= '  <a href="' . $update['notice'] . '" target="_blank">' . JText::sprintf('MOD_TWEETDISPLAYBACK_VERSION_UPDATE', $update['version']) . '</a></label>';
			}
			else
			{
				$message .= '  ' . JText::_('MOD_TWEETDISPLAYBACK_VERSION_CURRENT') . '</label>';
			}
			return $message;
		}
	}
}
