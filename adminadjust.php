<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.AdminAdjust
 *
 * @copyright   Copyright (C) NPEU 2019.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

/**
 * Tweaks to core admin screens.
 */
class plgSystemAdminAdjust extends JPlugin
{
    protected $autoloadLanguage = true;

    /**
     */
    public function onUserAfterLogin($options)
    {
        JFactory::getApplication()->enqueueMessage('Testing', 'notice');
        return true;
    }
    
    /**
     * Add CSS and JS.
     */
    public function onBeforeRender()
	{
        $app = JFactory::getApplication();
        if (!$app->isAdmin()) {
            return; // Only run in admin
        }
		$document = JFactory::getDocument();
        $document->addStyleSheet('/css/admin-adjust.css');
        
        $document->addScript('/js/admin-adjust.js');
	}
}