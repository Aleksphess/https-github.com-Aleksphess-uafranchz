<?php
namespace common\models;

use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $city = '';
    public $phone = '';
	public $check = '';


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            //['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            [['email', 'username', 'password', 'city', 'phone'], 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Этот email/username уже используется'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
      //  var_dump(1111);die();
        if (!$this->validate()) {
            return null;
        }
        if ($this->check=='on')
        {
            $i=1;

        }
        else
        {
            $i=2;
        }

        $user               = new User();
        $user->username     = $this->username;
        $user->email        = $this->email;

        $user->phone        = $this->phone;
		$user->parent_id 	= $i;
        $user->setPassword($this->password);
        $user->generateAuthKey();
       // var_dump($user->save() ? $user : null);die();
        return $user->save() ? $user : null;
    }
}