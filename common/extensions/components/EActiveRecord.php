<?php
/**
 * EActiveRecord class
 *
 * Some cool methods to share amount your models
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @copyright 2013 2amigOS! Consultation Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class EActiveRecord extends CActiveRecord
{
	/**
	 * default form ID for the current model. Defaults to get_class()+'-form'
	 */
	private $_formId;

	public function setFormId($value)
	{
		$this->_formId = $value;
	}

	public function getFormId()
	{
		if (null !== $this->_formId)
			return $this->_formId;
		else
		{
			$this->_formId = strtolower(get_class($this)) . '-form';
			return $this->_formId;
		}
	}

	/**
	 * default grid ID for the current model. Defaults to get_class()+'-grid'
	 */
	private $_gridId;

	public function setGridId($value)
	{
		$this->_gridId = $value;
	}

	public function getGridId()
	{
		if (null !== $this->_gridId)
			return $this->_gridId;
		else
		{
			$this->_gridId = strtolower(get_class($this)) . '-grid';
			return $this->_gridId;
		}
	}

	/**
	 * default list ID for the current model. Defaults to get_class()+'-list'
	 */
	private $_listId;

	public function setListId($value)
	{
		$this->_listId = $value;
	}

	public function getListId()
	{
		if (null !== $this->_listId)
			return $this->_listId;
		else
		{
			$this->_listId = strtolower(get_class($this)) . '-list';
			return $this->_listId;
		}
	}

	/**
	 * Logs the record update information.
	 * Updates the four columns: create_user_id, create_date, last_update_user_id and last_update_date.
	 */
	protected function logUpdate()
	{
		$userId = php_sapi_name() === 'cli'
			? -1
			: Yii::app()->user->id;

		foreach (array('create_user_id' => $userId, 'create_date' => time()) as $attribute => $value)
			$this->updateLogAttribute($attribute, $value, (!($userId===-1 || Yii::app()->user->isGuest) && $this->isNewRecord));

		foreach (array('last_update_user_id' => $userId, 'last_update_date' => time()) as $attribute => $value)
			$this->updateLogAttribute($attribute, $value, (!($userId===-1 || Yii::app()->user->isGuest) && !$this->isNewRecord));
	}

	/**
	 * Helper function to update attributes
	 * @param $attribute
	 * @param $value
	 * @param $check
	 */
	protected function updateLogAttribute($attribute, $value, $check)
	{

		if ($this->hasAttribute($attribute) && $check)
			$this->$attribute = $value;

	}

	/**
	 * updates the log fields before saving
	 * @return boolean
	 */
	public function beforeSave()
	{
		$this->logUpdate();
		return parent::beforeSave();
	}

}
