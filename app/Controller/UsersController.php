<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class UsersController extends AppController {
	
	public $name = 'Users';
	public $uses = array();

	public function beforeFilter(){
		$this->Auth->allow('register','fblogin','logout');
	}
	
	
	public function fblogin(){
		App::import('Vendor', 'Facebook/Facebook');
		$this->loadModel('User');
		$facebook   = new Facebook(array(
			'appId' => FB_APP_ID,
			'secret' => FB_APP_SEC,
			'cookie' => TRUE,
		));
		$fbuser = $facebook->getUser();
		if ($fbuser) {
			try {
				$user_profile = $facebook->api('/me');
				$isUser = $this->User->findByFId($user_profile['id']);
				if(empty($isUser)){
					$isEmail = $this->User->findByEmail($user_profile['email']);
					if(empty($isEmail)){
						$this->User->create();
						$this->User->save(array('f_id'=>$user_profile['id'],'name'=>$user_profile['first_name'],'email'=>$user_profile['email'],'password' => $this->Auth->password('')),null);
					}
					else{
						$isEmail['User']['f_id'] = $user_profile['id'];
						$this->User->save($isEmail);
					}
				}
				$user = $this->User->findByFId($user_profile['id']);
				if(!empty($user)){
					$this->Auth->login($user['User']);
					$this->redirect(array('controller'=>'pages','action'=>'index'));
				}
				else{
					$this->redirect(array('controller'=>'users','action'=>'login'));
				}
			}
			catch (Exception $e) {
				echo $e->getMessage();
				exit();
			}
			$user_fbid	= $fbuser;
			$user_email = $user_profile["email"];
			$user_fnmae = $user_profile["first_name"];
			$user_image = "https://graph.facebook.com/".$user_fbid."/picture?type=large";
		}
		else{
			$this->redirect(array('action'=>'login'));
		}
	}
	

	public function login(){
		
		if(!empty($this->request->data)){
			$pass = $this->Auth->password($this->request->data['User']['password']);
			$email = $this->request->data['User']['email'];
			$user = $this->User->find('first',array('conditions'=>array('User.email'=>$email,'User.password'=>$pass,'type'=>'user')));
			if(!empty($user)){
				$this->Auth->login($user['User']);
				$this->redirect(array('controller'=>'pages','action'=>'index'));
			}
			else{
				$this->Session->setFlash('Invalid Credential', 'default', array(), 'login');
			}
		}
		$this->render('login');
	}
	
	public function register(){
		if(!empty($this->request->data)){
			$this->loadModel('User');
			if($this->User->save($this->request->data))
			{
				$uid = $this->User->getLastInsertId();
				$user = $this->User->findById($uid);
				if($this->Auth->login($user['User']))
				{
					$this->redirect(array('controller'=>'pages','action'=>'index'));
				}
			}
			else{
				$this->Session->setFlash('Error While Registration, Please Try Again', 'default', array(), 'register');
			}
			//pr($this->request->data);die;
		}
		$this->render('login');
	}
	
	public function logout(){
		$this->Auth->logout();
		$this->redirect(array('controller'=>'Users','action'=>'login'));
	}

	public function admin_login(){
		if($this->request->isPost())
        {
			$this->loadModel('User');
            $email = $this->request->data['User']['email'];
            $password = $this->Auth->password($this->request->data['User']['password']);
            $isUser = $this->User->find('first',array('conditions'=>array('User.email'=>$email,'User.password'=>$password,'User.type'=>'admin')));
			if(empty($isUser)){
				$this->Session->setFlash('Invalid Credential');
			}
			else{
				if($this->Auth->login($isUser['User']))
				{
					$this->redirect(array('controller'=>'Users','action'=>'list','admin'=>true));
				}
			}
        }
	}
	
	public function admin_list(){
		$conditions = array('type <>' => 'admin');
		if(isset($this->request->query['term'])){
			$conditions['OR'] = array(
										'User.name like' => $this->request->query['term'].'%',
										'User.city like' => $this->request->query['term'].'%'
									  );	
		}
		//pr($conditions);die;
		$this->paginate = array('conditions' =>$conditions ,'order' => array('User.id DESC'),'limit' =>10);  
		
		$users = $this->paginate('User');
		//pr($users);die;
        $this->set('users', $users);
	}
	
	public function admin_search(){
		$this->autoRender = false;
		$keyword = $this->request->query['term'];
		$response = array();
		$options = array('conditions' => array('User.name like'=>$keyword.'%','type <>' => 'admin'),'fields'=>array('name'),
						 'order' => array('User.name'),
						 'group'=>array('User.name'),
						 'limit' =>5);
		
		$name_result = $this->User->find('list',$options);
		foreach($name_result as $r){
			$response[] = array('res'=>$r,'type'=>'Name');
		}
		$options = array('conditions' => array('User.city like'=>$keyword.'%','type <>' => 'admin'),'fields'=>array('city'),
						 'group'=>array('User.city'),
						 'order' => array('User.city'),'limit' =>5);
		
		$city_result = $this->User->find('list',$options);
		foreach($city_result as $r){
			$response[] = array('res'=>$r,'type'=>'City');
		}
        echo json_encode($response);
	}
}
