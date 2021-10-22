<?php
/*
 * AvailSchedule.class.php
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * @author      Till Glöggler <till.gloeggler@elan-ev.de>
 * @copyright   2013 ELAN e.V. <http://www.elan-ev.de>
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GPL version 2
 * @category    Stud.IP
 */

require_once 'lib/raumplan/draw_raumPlan.php';
require_once 'lib/raumplan/ConflictCounter.class.php';

class AvailSchedule extends StudipPlugin implements SystemPlugin
{

    /**
     * Initialize a new instance of the plugin.
     */
    function __construct()
    {
        parent::__construct();

        $sem_type = Context::getArtNum();

        if (isset($sem_type) && !$GLOBALS['SEM_CLASS'][$GLOBALS['SEM_TYPE'][$sem_type]['class']]['studygroup_mode'] &&
            Navigation::hasItem('/course/members') && $GLOBALS['perm']->have_studip_perm('tutor', Context::getId())) {
            $navigation = Navigation::getItem('/course/members');
            $navigation->addSubNavigation('conflicts', new Navigation(_('Verfügbarkeit'), PluginEngine::getLink('availschedule/show')));
        }
    }


    function show_action()
    {
        $seminar_id = Context::getId();

        if (!$seminar_id || !$GLOBALS['perm']->have_studip_perm('tutor', $seminar_id)) {
            die;
        }

        PageLayout::setTitle(Context::getHeaderLine() . ' - ' . _('Verfügbarkeitsplan'));
        Navigation::activateItem('/course/members/conflicts');

        $template_path = $this->getPluginPath() . '/templates';
        $template_factory = new Flexi_TemplateFactory($template_path);

        $template = $template_factory->open('schedule');
        $layout = $GLOBALS['template_factory']->open('layouts/base');
        $template->set_layout($layout);
        $template->set_attribute('really', Request::option('really'));
        $template->set_attribute('semester_id', Request::option('semester_id'));

        echo $template->render();
    }
}
