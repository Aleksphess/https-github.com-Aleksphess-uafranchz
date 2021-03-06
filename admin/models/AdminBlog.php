<?php

class admin_news extends AdminTable
{
	public $TABLE               = 'news';
    public $IMG_SIZE            = 300; // макс высота
    public $IMG_VSIZE           = 100;
    public $IMG_RESIZE_TYPE     = 1;
    public $IMG_BIG_SIZE        = 1000 ;
    public $IMG_BIG_VSIZE       = 333;
    public $IMG_NUM             = 1;
    public $ECHO_NAME           = 'title';
    public $SORT                = 'sort DESC';
    public $RUBS_NO_UNDER       = 1;
//    public $FIELD_UNDER         = 'parent_id';
    public $NAME                = "Новости";
    public $NAME2               = "новость";
    public $MULTI_LANG          = 1;
    function __construct()
    {
        $this->fld[] = new Field("title","Заголовок",1, array('multiLang'=>1));
        $this->fld[] = new Field("active","Опубликовать",6,array('showInList'=>1, 'editInList'=>1));
        $this->fld[] = new Field("custom_date","Дата публикации",13,array('showInList'=>1));
        $this->fld[] = new Field("short_description","Короткое описание",2, array('multiLang'=>1));
        $this->fld[] = new Field("text","Текст",2, array('multiLang'=>1));
        $this->fld[] = new Field("alias","Alias (генерируеться, если не заполнен)",1);
        $this->fld[] = new Field("sort","SORT",4);
        $this->fld[] = new Field("creation_time","Date of creation",4);
        $this->fld[] = new Field("update_time","Date of update",4);
    }

    function afterAdd($row)
    {
        if (empty($row['alias'])) {
            $qup = "UPDATE ".$this->TABLE." SET alias = '" . Translit($row['title_1'])."' WHERE id = " . $row['id'];
            pdoExec($qup);
        }
    }

    function afterEdit($row)
    {
        $this->afterAdd($row);
    }
}
class admin_court extends AdminTable
{
    public $TABLE               = 'court';
    public $IMG_SIZE            = 300; // макс высота
    public $IMG_VSIZE           = 100;
    public $IMG_RESIZE_TYPE     = 5;
    public $IMG_BIG_SIZE        = 1000 ;
    public $IMG_BIG_VSIZE       = 333;
    public $IMG_NUM             = 1;
    public $ECHO_NAME           = 'title';
    public $SORT                = 'sort DESC';
    public $RUBS_NO_UNDER       = 1;
//    public $FIELD_UNDER         = 'parent_id';
    public $NAME                = "Посты";
    public $NAME2               = "пост";
    public $MULTI_LANG          = 1;
    function __construct()
    {
        $this->fld[] = new Field("title","Заголовок",1, array('multiLang'=>1));
        $this->fld[] = new Field("active","Опубликовать",6,array('showInList'=>1, 'editInList'=>1));
        $this->fld[] = new Field("custom_date","Дата публикации",13,array('showInList'=>1));
        $this->fld[] = new Field("short_description","Короткое описание",2, array('multiLang'=>1));
        $this->fld[] = new Field("text","Текст",2, array('multiLang'=>1));
        $this->fld[] = new Field("alias","Alias (генерируеться, если не заполнен)",1);
        $this->fld[] = new Field("sort","SORT",4);
        $this->fld[] = new Field("creation_time","Date of creation",4);
        $this->fld[] = new Field("update_time","Date of update",4);
    }

    function afterAdd($row)
    {
        if (empty($row['alias'])) {
            $qup = "UPDATE ".$this->TABLE." SET alias = '" . Translit($row['title_1'])."' WHERE id = " . $row['id'];
            pdoExec($qup);
        }
    }

    function afterEdit($row)
    {
        $this->afterAdd($row);
    }
}
