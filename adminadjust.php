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
     * Plugin that changes the language code used in the <html /> tag.
     *
     * @return  void
     *
     * @since   2.5
     */
    public function onAfterRender()
    {
        // Note this should be in it's own plugin, really.
        $app = JFactory::getApplication();
        // Use this plugin only in site application.
        if ($app->isClient('site')) {
            switch ($_SERVER['SERVER_NAME']) {
                case 'dev.npeu.ox.ac.uk' :
                    $env = 'development';
                    break;
                case 'test.npeu.ox.ac.uk':
                    $env = 'testing';
                    break;
                default:
                    $env = 'production';
            }

            // Get the response body.
            $body = $app->getBody();

            if ($env == 'development') {
                $app->setBody(str_replace('https://www.npeu.ox.ac.uk', 'https://dev.npeu.ox.ac.uk', $body));
            } elseif ($env == 'testing') {
                $app->setBody(str_replace('https://www.npeu.ox.ac.uk', 'https://test.npeu.ox.ac.uk', $body));
            }
        }
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
        #document->addStyleSheet('https://cdn.jsdelivr.net/npm/webui-popover@1.2.18/dist/jquery.webui-popover.min.css');

        
        #$document->addScript('https://cdn.jsdelivr.net/npm/webui-popover@1.2.18/dist/jquery.webui-popover.min.js');
        #$document->addScript(' https://cdnjs.cloudflare.com/ajax/libs/showdown/1.9.0/showdown.min.js');
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