<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
 * @package local_envbar
 * @copyright 2016 Catalyst
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../config.php');
require_once(dirname(__FILE__).'/config.php');
require_once(dirname(__FILE__) . '/config_form.php');
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('local_envbar');

//page nav bar
$PAGE->set_url('/local/envbar/index.php');
$node = $PAGE->settingsnav->find('envbar', navigation_node::TYPE_SETTING);
if ($node) {
    $node->make_active();
}
$PAGE->navbar->add(get_string('pluginname', 'local_envbar'));

$PAGE->set_title(get_string('pluginname', 'local_envbar'));
$PAGE->set_heading(get_string('pluginname', 'local_envbar'));



$sets = EnvbarConfigSetFactory::instances();
$form = new local_envbar_form(null, array('sets' => $sets));

if($data = $form->get_data()){
    $confSet = EnvbarConfigSetFactory::newRecord();
    $keys = array_keys($confSet->get_params());
    foreach($data->{$keys[0]} as $set_id => $_){
        $set = $sets[$set_id];
        foreach($keys as $key){
            $set->$key = $data->{$key}[$set_id];
        }
        $set->save($DB);
    }
    redirect($CFG->wwwroot.'/local/envbar/index.php');
}

echo $OUTPUT->header();


echo $OUTPUT->heading(get_string('header_envbar', PLUGIN_NAME_ENVBAR));

echo $form->display();

echo $OUTPUT->footer();
