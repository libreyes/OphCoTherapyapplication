<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2013
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2013, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

class OphCoTherapyapplication_API extends BaseAPI {
	
	/**
	 * Gets the last drug that was applied for for the given patient, episode and side
	 * 
	 * @param Patient $patient
	 * @param Episode $episode
	 * @param string $side
	 * @throws Exception
	 * 
	 * @return OphTrIntravitrealinjection_Treatment_Drug
	 */
	public function getLatestApplicationDrug($patient, $episode, $side) {
		if ($episode) {
			$event_type = $this->getEventType();
			
			$criteria = new CDbCriteria;
			$criteria->compare('event.event_type_id',$event_type->id);
			$criteria->compare('event.episode_id',$episode->id);
			$criteria->order = 't.created_date desc';
			$criteria->limit = 1;
			
			$eye_ids = array('eye_id' => SplitEventTypeElement::BOTH);
				
			if ($side == 'left') {
				$eye_ids[] = SplitEventTypeElement::LEFT;
			}
			else if ($side == 'right') {
				$eye_ids[] = SplitEventTypeElement::RIGHT;
			}
			else {
				throw new Exception('unrecognised side value ' . $side);
			}
			
			$criteria->addInCondition('eye_id', $eye_ids);
			
			if ($suit = Element_OphCoTherapyapplication_PatientSuitability::model()->with('event', $side . '_treatment', $side . '_treatment.drug')->find($criteria)) {
				return $suit->{$side . '_treatment'}->drug;
			}
			
		}
	}
}