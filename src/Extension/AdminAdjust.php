<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.AdminAdjust
 *
 * @copyright   Copyright (C) NPEU 2023.
 * @license     MIT License; see LICENSE.md
 */

namespace NPEU\Plugin\System\AdminAdjust\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\Event;
use Joomla\Event\SubscriberInterface;

/**
 * Tweaks to core admin screens.
 */
class AdminAdjust extends CMSPlugin implements SubscriberInterface
{
    protected $autoloadLanguage = true;

    /**
     * An internal flag whether plugin should listen any event.
     *
     * @var bool
     *
     * @since   4.3.0
     */
    protected static $enabled = false;


    /**
     * Constructor
     *
     */
    public function __construct($subject, array $config = [], bool $enabled = true)
    {
        // The above enabled parameter was taken from teh Guided Tour plugin but it ir always seems
        // to be false so I'm not sure where this param is passed from. Overriding it for now.
        $enabled = true;


        #$this->loadLanguage();
        $this->autoloadLanguage = $enabled;
        self::$enabled          = $enabled;

        parent::__construct($subject, $config);
    }

    /**
     * function for getSubscribedEvents : new Joomla 4 feature
     *
     * @return array
     *
     * @since   4.3.0
     */
    public static function getSubscribedEvents(): array
    {
        return self::$enabled ? [
            'onAfterRender'        => 'onAfterRender',
            'onBeforeRender'       => 'onBeforeRender',
            'onContentPrepareForm' => 'onContentPrepareForm'
        ] : [];
    }


    /**
     * Replace strings in the body.
     */
    public function onAfterRender(Event $event): void
    {
        // Note this should be in it's own plugin, really.
        $app = Factory::getApplication();

        // Only in admin:
        // I don't think this is necessary anymore (DEC 2023)
        /*if ($app->isClient('administrator')) {
            // New password fields should now use 'new-password' instead of 'off' for autocomplete.
            // Get the response body.
            $body = $app->getBody();

            #$app->setBody(preg_replace('#(<input.*?type="password".*?)autocomplete="off"#s', '$1autocomplete="new-password"', $body));
            #$app->setBody(str_replace('autocomplete="off" class="validate-password"', 'autocomplete="new-password" class="validate-password"', $body));
        }*/

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
    public function onBeforeRender(Event $event): void
    {
        $app = Factory::getApplication();
        if (!$app->isClient('administrator')) {
            return; // Only run in admin
        }
        $document = Factory::getDocument();
        #$document->addStyleSheet('/plugins/system/adminadjust/assets/css/admin-adjust.min.css');
        $document->addStyleSheet('/plugins/system/adminadjust/assets/css/admin-adjust.css');
        #document->addStyleSheet('https://cdn.jsdelivr.net/npm/webui-popover@1.2.18/dist/jquery.webui-popover.min.css');


        #$document->addScript('https://cdn.jsdelivr.net/npm/webui-popover@1.2.18/dist/jquery.webui-popover.min.js');
        #$document->addScript(' https://cdnjs.cloudflare.com/ajax/libs/showdown/1.9.0/showdown.min.js');
        #$document->addScript('/plugins/system/adminadjust/assets/js/admin-adjust.js');
    }

    /**
     * Prepare form and add my field.
     *
     * @param   Form  $form  The form to be altered.
     * @param   mixed  $data  The associated data for the form.
     *
     * @return  boolean
     *
     * @since   <your version>
     */
    public function onContentPrepareForm(Event $event): void
    {
        [$form, $data] = array_values($event->getArguments());

        if (!$form instanceof \Joomla\CMS\Form\Form) {
            return;
        }

        $app    = Factory::getApplication();
        $option = $app->input->get('option');
        FormHelper::addFormPath(dirname(dirname(__DIR__)) . '/forms');

        switch($option)
        {
            case 'com_menus' :
                if ($app->isClient('administrator') && isset($data->type) && $data->type == 'component') {
                    $form->loadFile('menu_item', false);
                }

                return;

            case 'com_modules' :
                if ($app->isClient('administrator')) {
                    $form->loadFile('module', false);
                }

                return;
        }

        return;
    }
}