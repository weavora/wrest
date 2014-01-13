Yii Rest Extension
===================

The extension organizes REST API in Yii app 

[Weavora's](http://weavora.com) Git Repo - [https://github.com/weavora/wrest](https://github.com/weavora/wrest)

**Features**:

* Fast configuration
* Provides a simple REST CRUD interface (get, list, update, create, delete actions)
* CRUD actions are integrated with Model scenarios

Configuration
-----

1) Download and extract the source into the protected/extensions/ folder.

2) There are config settings for the import section below:

```php
<?php
// main.php
return array(
	...
	'import' => array(
		...
		'ext.wrest.*',
	),
	...
);
```

3) Add REST routes

```php
<?php
// main.php
...
'urlManager'=>array(
	'urlFormat'=>'path',
	'rules'=>array(
		...
		//rest url patterns
		array('api/<model>/delete', 'pattern'=>'api/<model:\w+>/<_id:\d+>', 'verb'=>'DELETE'),
		array('api/<model>/update', 'pattern'=>'api/<model:\w+>/<_id:\d+>', 'verb'=>'PUT'),
		array('api/<model>/list', 'pattern'=>'api/<model:\w+>', 'verb'=>'GET'),
		array('api/<model>/get', 'pattern'=>'api/<model:\w+>/<_id:\d+>', 'verb'=>'GET'),
		array('api/<model>/create', 'pattern'=>'api/<model:\w+>', 'verb'=>'POST'),
		...
	),
),
...
```

4) All your models must use WRestModelBehavior:

```php
<?php
// /models/User.php
class User extends CActiveRecord{
	...
	public function behaviors()
	{
		return array(
			...
			'RestModelBehavior' => array(
				'class' => 'WRestModelBehavior',
			)
			...
		);
	}
	...
}
```

5) Create an 'api' folder in /protected/controllers/. Here all your REST API controllers will be determined.

6) Extend all your REST controlles from WRestController instead of Controller.

Usage
-----

1) Create a model, determine relations, behavior, etc.

```php
<?php
// /models/User.php
class User extends CActiveRecord
{

	public function tableName()
	{
		return "user";
	}

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function behaviors()
	{
		return array(
			'RestModelBehavior' => array(
				'class' => 'WRestModelBehavior',
			)
		);
	}

	public function rules(){
		return array(
			array('username, password', 'required'),
			array('email', 'safe'),
		);
	}

}
```

2) Create a controller in the /protected/controllers/api folder, extend it from WRestController and define rest actions

```php
<?php 
// api/UserController.php

class UserController extends WRestController{

	protected $_modelName = "user"; //model to be used as resource


	public function actions() //determine which of the standard actions will support the controller
	{
		return array(
			'list' => array( //use for get list of objects
				'class' => 'WRestListAction',
				'filterBy' => array( //this param used in `where` expression when forming an db query
					'account_id' => 'account_id', // 'name_in_table' => 'request_param_name'
				),
				'limit' => 'limit', //request parameter name which will contain a limit of object
				'page' => 'page', //request parameter name which will contain a requested page num
				'order' => 'order', //request parameter name which will contain ordering for sort
			),
			'delete' => 'WRestDeleteAction',
			'get' => 'WRestGetAction',
			'create' => 'WRestCreateAction', //provide 'scenario' param
			'update' => array(
				'class' => 'WRestUpdateAction',
				'scenario' => 'update', //as well as in WRestCreateAction optional param
		);
	}
}
```

REST API call samples
=======================

If you use the RestClient class, then you can call api the following way:

```php
<?php
// apiCallSample.php

require 'RestClient.php';
$client = new RestClient('http://api_url/api/', '', '');

$response = $client->get('user'); //return the list of objects {{id:1, username:'Jack'}, {id:2, username:'Nick'}}
$response = $client->get('user/1'); //return a single object with the requested id, for example {id:1, username:'Jack'}
$response = $client->update('user/1', array('username' => 'new user name')); //update user data
$response = $client->delete('user/1'); //delete user with requested id
$response = $client->post('user', array('username'=>'name', 'password' => 'pass', 'email' => 'email@email.com'));//create new user

```



Custom action sample
--------------------

```php
<?php
// api/UserController.php

class UserController extends WRestController{

	protected $_modelName = "user"; //model to be used as a resource
	
	...
	
	public function actionFriends($id)
	{
		$user = User::model()->findByPk($id);
		$friends = $user->findFriends();
		
		if(empty($user))
			$this->sendResponse(404);
			
		$users = array();
		foreach($friends as $friend){
			$users = $friend->getAllAttributes();
		}
		$this->sendResponse(200, $users);
	}
	
	...
	
}
```

Use it with the Access layer filter
-----------------

For providing an access layer for REST actions use [wacf](https://github.com/weavora/yii-wacf)

```php
<?php
// api/UserController.php

class UserController extends WRestController
{

	protected $modelName = "user";

	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	public function accessRules()
	{
		return array(
			array(
				'allow',
				'actions' => array('get', 'list', 'create'),
				'roles' => array('member'),
			),
			array(
				'allow',
				'roles' => array('member'),
				'actions' => array('delete', 'update'),
				'resource' => array(
					'model' => 'User',
					'params' => 'id',
					'ownerField' => 'account_id',
				),
			),
			array(
				'deny',
			),
		);
	}

	public function actions()
	{
		return array(
			'list' => array(
				'class' => 'WRestListAction',
				'filterBy' => array(
					'account_id' => 'account_id',
				),
				'limit' => 'limit',
			),
			'delete' => 'WRestDeleteAction',
			'get' => 'WRestGetAction',
			'create' => 'WRestCreateAction',
			'update' => 'WRestUpdateAction',
		);
	}
}
```

Submitting bugs and feature requests
------------------------------------

Bugs and feature request are tracked on [GitHub](https://github.com/weavora/wrest/issues)

Author
------

Weavora LLC - <http://weavora.com> - <http://twitter.com/weavora><br />
Also see the list of [contributors](https://github.com/weavora/wrest/contributors) who've participated in this project.

License
-------

This library is licensed under the MIT License - see the `LICENSE` file for details
