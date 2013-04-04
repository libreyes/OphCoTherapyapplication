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

/**
 * This is the model class for table "et_ophcotherapya_patientsuit".
 *
 * The followings are the available columns in table:
 * @property string $id
 * @property integer $event_id
 * @property integer $left_treatment_id
 * @property integer $right_treatment_id
 * @property string $left_angiogram_baseline_date
 * @property string $right_angiogram_baseline_date
 * @property boolean $left_nice_compliance
 * @property boolean $right_nice_compliance
 *
 * The followings are the available model relations:
 *
 * @property ElementType $element_type
 * @property EventType $eventType
 * @property Event $event
 * @property User $user
 * @property User $usermodified
 * @property OphCoTherapyapplication_Treatment $left_treatment
 * @property OphCoTherapyapplication_Treatment $right_treatment
 * @property Eye $eye
 */

class Element_OphCoTherapyapplication_PatientSuitability extends SplitEventTypeElement
{
	public $service;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'et_ophcotherapya_patientsuit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('event_id, eye_id, left_treatment_id, left_angiogram_baseline_date, left_nice_compliance, right_treatment_id, right_angiogram_baseline_date, right_nice_compliance,', 'safe'),
			array('left_treatment_id, left_angiogram_baseline_date, left_nice_compliance', 'requiredIfSide', 'side' => 'left'),
			array('right_treatment_id, right_angiogram_baseline_date, right_nice_compliance', 'requiredIfSide', 'side' => 'right'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, event_id, eye_id, left_treatment_id, left_angiogram_baseline_date, left_nice_compliance, right_treatment_id, right_angiogram_baseline_date, right_nice_compliance', 'safe', 'on' => 'search'),
		);
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'element_type' => array(self::HAS_ONE, 'ElementType', 'id','on' => "element_type.class_name='".get_class($this)."'"),
			'eventType' => array(self::BELONGS_TO, 'EventType', 'event_type_id'),
			'event' => array(self::BELONGS_TO, 'Event', 'event_id'),
			'user' => array(self::BELONGS_TO, 'User', 'created_user_id'),
			'usermodified'    => array(self::BELONGS_TO, 'User', 'last_modified_user_id'),
			'eye' => array(self::BELONGS_TO, 'Eye', 'eye_id'),
			'left_treatment'  => array(self::BELONGS_TO, 'OphCoTherapyapplication_Treatment', 'left_treatment_id'),
			'right_treatment' => array(self::BELONGS_TO, 'OphCoTherapyapplication_Treatment', 'right_treatment_id'),
			// TODO - use appropriate statics for these values
			'left_responses' => array(self::HAS_MANY, 'OphCoTherapyapplication_PatientSuitability_DecisionTreeNodeResponse', 'patientsuit_id', 'on' => 'left_responses.eye_id = ' . SplitEventTypeElement::LEFT),
			'right_responses' => array(self::HAS_MANY, 'OphCoTherapyapplication_PatientSuitability_DecisionTreeNodeResponse', 'patientsuit_id', 'on' => 'right_responses.eye_id = ' . SplitEventTypeElement::RIGHT),
		);
	}

	public function sidedFields() {
		return array('treatment_id', 'angiogram_baseline_date', 'nice_compliance');
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'event_id' => 'Event',
			'left_treatment_id' => 'Treatment',
			'left_angiogram_baseline_date' => 'Angiogram Baseline Date',
			'left_nice_compliance' => 'NICE Compliance',
			'right_treatment_id' => 'Treatment',
			'right_angiogram_baseline_date' => 'Angiogram Baseline Date',
			'right_nice_compliance' => 'NICE Compliance',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('event_id', $this->event_id, true);
		$criteria->compare('left_treatment_id', $this->left_treatment_id);
		$criteria->compare('left_angiogram_baseline_date', $this->left_angiogram_baseline_date);
		$criteria->compare('left_nice_compliance', $this->left_nice_compliance);
		$criteria->compare('right_treatment_id', $this->right_treatment_id);
		$criteria->compare('right_angiogram_baseline_date', $this->right_angiogram_baseline_date);
		$criteria->compare('right_nice_compliance', $this->right_nice_compliance);
		
		return new CActiveDataProvider(get_class($this), array(
			'criteria' => $criteria,
		));
	}



	protected function beforeSave()
	{
		return parent::beforeSave();
	}

	protected function afterSave()
	{

		return parent::afterSave();
	}

	protected function beforeValidate()
	{
		return parent::beforeValidate();
	}
	
	/*
	 * if either the left or right treatment requires the contraindications to be provided, returns true. otherwise returns false
	 * 
	 * @return boolean $required
	 */
	public function contraindicationsRequired() {
		return ($this->left_treatment && $this->left_treatment->contraindications_required) ||
		($this->right_treatment && $this->right_treatment->contraindications_required);
	}
	
	public function updateDecisionTreeResponses($side, $update_responses) {
		$current_responses = array();
		$save_responses = array();
		if ($side == $this::LEFT) {
			$responses = $this->left_responses;
		}
		elseif ($side == $this::RIGHT) {
			$responses = $this->right_responses;
		}
		else {
			throw Exception("Invalid side value");
		}
		
		foreach ($responses as $curr_resp) {
			$current_responses[$curr_resp->node_id] = $curr_resp;
		}
				
		// go through each node response, if there isn't one for this element,
		// create it and store for saving
		// if there is, check if the value is the same ... if it has changed
		// update and store for saving, otherwise remove from the current responses array
		// anything left in current responses at the end is ripe for deleting
		foreach ($update_responses as $node_id => $value) {
			if (!array_key_exists($node_id, $current_responses)) {
				$s = new OphCoTherapyapplication_PatientSuitability_DecisionTreeNodeResponse();
				$s->attributes = array('patientsuit_id' => $this->id, 'eye_id' => $side, 'node_id' => $node_id, 'value' => $value);
				$save_responses[] = $s;
			} else {
				if ($current_responses[$node_id]->value != $value) {
					$current_responses[$node_id]->value = $value;
					$save_responses[] = $current_responses[$node_id];
					unset($current_responses[$node_id]);
				}
			}
		}
		// save what needs saving
		foreach ($save_responses as $save) {
			$save->save();
		}
		// delete the rest
		foreach ($current_responses as $curr) {
			$curr->delete();
		}
		
	}
}
?>