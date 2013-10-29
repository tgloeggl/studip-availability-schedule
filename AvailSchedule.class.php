<?php
/*
 * AvailSchedule.class.php
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * @author      Till Gl�ggler <till.gloeggler@elan-ev.de>
 * @copyright   2013 ELAN e.V. <http://www.elan-ev.de>
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GPL version 2
 * @category    Stud.IP
 */

require_once 'lib/raumplan/draw_raumPlan.php';
require_once 'lib/raumplan/ConflictCounter.class.php';
require_once 'lib/classes/SemesterData.class.php';

class AvailSchedule extends StudipPlugin implements SystemPlugin
{

    /**
     * Initialize a new instance of the plugin.
     */
    function __construct()
    {
        parent::__construct();

        $seminar_id = Request::option('cid', $GLOBALS['SeminarSession']);

        $sem = Seminar::getInstance($seminar_id);
        
        if (in_array($sem->status, studygroup_sem_types()) === false) {
            if (Navigation::hasItem('/course/members') && $GLOBALS['perm']->have_studip_perm('tutor', $seminar_id)) {
                $navigation = Navigation::getItem('/course/members');
                $navigation->addSubNavigation('conflicts', new Navigation(_('Verf�gbarkeit'), PluginEngine::getLink('availschedule/show')));
            }
        }
    }


    function show_action() {
        $seminar_id = Request::option('cid');

        if (!$seminar_id || !$GLOBALS['perm']->have_studip_perm('tutor', $seminar_id)) {
            die;
        }

        PageLayout::setTitle($GLOBALS['SessSemName']['header_line'] . ' - ' . _('Verf�gbarkeitsplan'));
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
