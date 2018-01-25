<?php
/*
 *  Services
 * */

class admin_services extends AdminTable
{
    public $SORT = 'sort ASC';
	public $IMG_SIZE = 100;
	public $IMG_RESIZE_TYPE = 1;
	public $IMG_BIG_SIZE = 250;
	public $IMG_BIG_VSIZE = 250;
	public $IMG_NUM = 0;
	public $ECHO_NAME = 'name';
	public $FIELD_UNDER = 'parent_id';
    public $RUBS_NO_UNDER = 1;    
	public $NAME = "Наши услуги";
	public $NAME2 = "услугу";
	public $EXTRA_TABLES = ['seo'=>['record_id'=>'id', 'table_name'=>'services']];
	
	public $MULTI_LANG = 1;
	
	function __construct()
	{
		$this->fld = [
			new Field("alias","Alias",1),
			new Field("name","Название",1, ['multiLang'=>1]),
			new Field("title","META TITLE",1, ['multiLang'=>1, 'fromTable'=>'seo']),
			new Field("description","META Description",1, ['multiLang'=>1, 'fromTable'=>'seo']),
            new Field("text","Подробное описание (HTML)",16, ['multiLang'=>1]),
            new Field("parent_id","Родитель", 9, [
	'showInList'=>0, 'editInList'=>0, 'valsFromTable'=>'services', 'valsFromCategory'=>null, 
	'valsEchoField'=>'name']),                  
			new Field("hide","Не відображати",6, ['showInList'=>1]),
			new Field("sort","SORT",4),
			new Field("creation_time","Date of creation",4),
			new Field("update_time","Date of update",4),
		];
	}
}
