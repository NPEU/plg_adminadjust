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

    /**
     * Prepare form and add my field.
     *
     * @param   JForm  $form  The form to be altered.
     * @param   mixed  $data  The associated data for the form.
     *
     * @return  boolean
     *
     * @since   <your version>
     */
    function onContentPrepareForm($form, $data)
    {
        $app    = JFactory::getApplication();
        $option = $app->input->get('option');

        switch($option)
        {
            case 'com_menus' :
                if ($app->isClient('administrator') && isset($data->type) && $data->type == 'component')
                {
                    JForm::addFormPath(__DIR__ . '/forms');
                    $form->loadFile('menu_item', false);
                }

                return true;

            case 'com_modules' :
                if ($app->isClient('administrator'))
                {
                    JForm::addFormPath(__DIR__ . '/forms');
                    $form->loadFile('module', false);
                }

                return true;
        }

        return true;
    }
}