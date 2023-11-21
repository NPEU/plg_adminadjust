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
     * Replace strings in the body.
     */
    public function onAfterRender()
    {
        // Note this should be in it's own plugin, really.
        $app = JFactory::getApplication();

        // Only in admin:
        if ($app->isClient('administrator')) {
            // New password fields should now use 'new-password' instead of 'off' for autocomplete.
            // Get the response body.
            $body = $app->getBody();

            #$app->setBody(preg_replace('#(<input.*?type="password".*?)autocomplete="off"#s', '$1autocomplete="new-password"', $body));
            $app->setBody(str_replace('autocomplete="off" class="validate-password"', 'autocomplete="new-password" class="validate-password"', $body));
        }

        // Only in site:
        if ($app->isClient('site')) {
            // Replace all absolute local URLs with the correct ones:
            if ($_SERVER['SERVER_NAME'] != 'www.npeu.ox.ac.uk') {
                // Get the response body.
                $body = $app->getBody();
                $app->setBody(str_replace('https://www.npeu.ox.ac.uk', 'https://' . $_SERVER['SERVER_NAME'], $body));
            }
        }
    }

    /**
     * Add CSS and JS.
     */
    public function onBeforeRender()
    {
        $app = JFactory::getApplication();
        if (!$app->isClient('administrator')) {
            return; // Only run in admin
        }
        $document = JFactory::getDocument();
        $document->addStyleSheet('/plugins/system/adminadjust/assets/css/admin-adjust.min.css');
        #document->addStyleSheet('https://cdn.jsdelivr.net/npm/webui-popover@1.2.18/dist/jquery.webui-popover.min.css');


        #$document->addScript('https://cdn.jsdelivr.net/npm/webui-popover@1.2.18/dist/jquery.webui-popover.min.js');
        #$document->addScript(' https://cdnjs.cloudflare.com/ajax/libs/showdown/1.9.0/showdown.min.js');
        $document->addScript('/plugins/system/adminadjust/assets/js/admin-adjust.js');
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