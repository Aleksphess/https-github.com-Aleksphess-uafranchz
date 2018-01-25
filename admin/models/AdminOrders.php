<?php
/*
 *  Заказы
 * */


class admin_orders extends AdminTable
{
	public $IMG_NUM = 0;
	public $SORT = 'id DESC';
	public $ECHO_NAME = 'name';
    
	public $NAME='Заказы';
	public $NAME2='заказ';
	

	function __construct()
	{
		$this->fld=array(
			new Field("name","Имя",1),
            new Field("surname","Фамилия",1, array('showInList'=>1)),
            new Field("fathername","Отчество",1, array('showInList'=>1)),
            new Field("crt","Дата создания",5, array('showInList'=>1)),
            new Field("indef_code","ИИН",1, array('showInList'=>1)),
            //new Field("date_birth","Дата рождения",13, array('showInList'=>1)),
            new Field("email","E-mail",1, array('showInList'=>1)),
            new Field("tel","Телефон",1, array('showInList'=>1)),
            new Field("delivery_type","Доставка",1, array('showInList'=>1)),
            new Field("delivery_address","Адрес доставки",1, array('showInList'=>1)),
			new Field("user_ip","IP адрес",1),
			new Field("base_product_id","Базовый продукт", 9, array(
				'showInList'=>1, 'editInList'=>0, 'valsFromTable'=>'products', 'valsFromCategory'=>NULL, 
				'valsEchoField'=>'name')),
			new Field("company_id","Страховая", 9, array(
				'showInList'=>1, 'editInList'=>0, 'valsFromTable'=>'companies', 'valsFromCategory'=>NULL, 
				'valsEchoField'=>'name')),
			new Field("status","Статус", 9, array(
				'showInList'=>1, 'editInList'=>0, 'valsFromTable'=>'orders_statuses', 'valsFromCategory'=>NULL, 
				'valsEchoField'=>'name')),
			new Field("paid","Оплачен", 6, array('showInList'=>1)),
			new Field("params","Параметры заказа",4, array('showInList'=>0)),
			new Field("creation_date","Date of creation",4),
		);
        

	}
	
	function show_crt($row) {
		return date('d.m.Y H:i', $row['creation_date']);
	}
        
        function show_params($row) {
		return '<pre>'.print_r(json_decode(stripslashes($row['params'])),true).'</pre>';
	}

};

class admin_orders_statuses extends AdminTable
{
	public $IMG_NUM = 0;
	public $ECHO_NAME = 'name';
    
	public $NAME="Статусы заказов";
	public $NAME2="статус";
	
	function __construct()
	{
		$this->fld=array(
			new Field("name","Имя",1),
			new Field("sort","SORT",4),
			
			new Field("creation_date","Date of creation",4),
		);
        

	}
	

};

class admin_callbacks extends AdminTable
{
	public $SORT = 'done ASC, id DESC';
	public $IMG_NUM = 0;
	public $ECHO_NAME = 'tel';
    
	public $NAME="Обратный звонок";
	public $NAME2="заказ";
	
	function __construct()
	{
		$this->fld=array(
			new Field("tel","Телефон",1),
			new Field("crt","Дата создания",5, array('showInList'=>1)),
			new Field("when","Когда связаться",1, ['showInList'=>1]),
			new Field("comment","Комментарий",1, ['showInList'=>1, 'editInList'=>1, 'inputWidthInList'=>300]),
			new Field("page_name","Отправлено со страницы",1),
			new Field("done","Обработан",6, ['showInList'=>1, 'editInList'=>1]),
			new Field("user_ip","IP адрес",1),
			new Field("creation_date","Date of creation",4),
		);
        

	}
	
	function show_crt($row) {
		return date('d.m.Y H:i', $row['creation_date']);
	}

	

};


