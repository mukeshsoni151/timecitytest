<?php 
class User extends AppModel
{
	public $name = 'User';
	var $validate = array(
		'email' => array(
			'emailrule-1' =>array('rule'=>'notEmpty','message'=>'Please Enter Email'),
			'emailRule-2' => array(
				'rule'=>'email',
				'message' => 'invalid email address',
			),
			'emailRule-3' => array(
				'rule' => array('isUnique'),
				'message' => 'This email is already registered'),
				),
		'password' => array(
			'checkempty'=>array('rule'=>'notEmpty','message'=>"Password can not be blank"),
			'minlimit'=>array('rule'=>array('minLength', '3'),'message'=>"Password must at least 3 character"),
			'maxlimit'=>array('rule'=>array('maxLength', '50'),'message'=>"Password not more than 15 character"),
			),
		'name' => array(
			'rule'=>'notEmpty',
			'message'=>"Name can not be blank"),
		);
	public function beforeSave($options = array()) {
		if(!empty($this->data['User']['password'])){
       		$this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
		}
		return true;
    }
}