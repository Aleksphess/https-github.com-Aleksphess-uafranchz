<?php
class admin_user extends AdminTable
{
    public $SORT = 'username ASC';
    public $NAME = 'Пользователи сайта';
    public $NAME2 = 'пользователя';
    public $ECHO_NAME       = 'username';
    public $FIELD_UNDER = 'parent_id';
   
    function __construct() {
        $this->fld = [
            new Field("username","Имя",1),
            new Field("status","Статус",1),
            new Field("email","E-mail",1, ['showInList'=>1]),
            new Field("phone","Телефон",1, ['showInList'=>1]),
            $this->fld[] = new Field("parent_id","Находится в групе",9, array(
                'showInList'=>0, 'editInList'=>0, 'valsFromTable'=>'user_categories', 'valsFromCategory'=>null,
                'valsEchoField'=>'name')),
            new Field("password_hash","Хэш пароля",1),
            new Field("password_reset_token","Токен сброса пароля",1),
            new Field("auth_key","Ключ авторизации",1),
            new Field("password_hash","Пароль",1),
            new Field("created_at", "Создан", 1),
            new Field("updated_at", "Обновлен", 1)
        ];
    }

};
class admin_user_categories extends AdminTable
{
    public $SORT = 'name ASC';
    public $NAME = 'Пользователи сайта';
    public $NAME2 = 'пользователя';
    public $ECHO_NAME       = 'name';
    //public $FIELD_UNDER = 'group_id';
	public $RUBS_NO_UNDER = 1;
   
    function __construct() {
        $this->fld = [
					
            new Field("name","Имя",1),
            
        ];
    }

};
