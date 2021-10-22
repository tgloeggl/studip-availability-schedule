<?php
/*
teilnehmer_conflicts.php - Verfügbarkeitsanzeige der Teilnehmer eines Seminares
Copyright (C) 2005-2013 Marco Diedrich <marco.diedrich@uni-osnabrueck.de>, Till Glöggler <till.gloeggler@uni-osnabrueck.de>

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

?>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td class="blank">
            <br/>
            <?
            $all_semester = Semester::getAll();
            if (!$really) { ?>
            <b>
            Sie können sich einen sogenannten Verfügbarkeitsplan anzeigen lassen.<br/>
            Es handelt sich dabei um eine Wochenübersicht, worauf sie erkennen können
            wieviele ihrer Teilnehmer zu den jeweiligen Zeiten andere Veranstaltungen belegen.<br/>
            <br/>
            Klicken Sie auf ein Semester, um sich dafür diesen Verfügbarkeitsplan anzeigen zu lassen:<br/>
            <?
                foreach ($all_semester as $key => $val) {
                    if ($val['ende'] >= time()) {
                        echo '<a href="' . URLHelper::getLink('?really=yes&semester_id=' . $key) .'">' . $val['name'] . '</a>';
                        echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
                    }
                }
            ?>
            </b><br/>
            <br/>
            <? } else {
                flush();
                $plan = createPlanData(Request::get('cid'), $all_semester[$semester_id]['beginn']);
                if (!$plan) {
                    echo MessageBox::error('Sie können dieses Tool nur nutzen, wenn Sie mehr als 5 Teilnehmer in ihrer Veranstaltung haben!');
                } else {
                    printTable($plan, $all_semester[$semester_id]['name']);
                    flush();
                    echo "<br/><br/>";
                    printList($plan);
                }
            }
            ?>
            <br/>
        </td>
    </tr>
</table>
