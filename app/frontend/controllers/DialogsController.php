<?php

namespace frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\base\ErrorException;
use common\components\SeoComponent;
use common\components\MailComponent;
use common\models\Lots;

use common\models\Dialogs;
use common\models\DialogMessages;

class DialogsController extends \common\components\BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['create', 'index', 'dialog', 'check-messages','send-message','read-messages'],
                'rules' => [
                    [
                        'actions'   => ['create', 'index', 'dialog', 'check-messages','send-message','read-messages'],
                        'allow'     => true,
                        'roles'     => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class'     => VerbFilter::className(),
                'actions'   => [
                    'index'                 => ['get'],
                    'dialog'                => ['get'],
                    'create-dialog'         => ['post'],
                    'check-messages'        => ['get'],
                    'send-message'          => ['post'],
                    'read-messages'         => ['post'],
                ],
            ],
        ];
    }
    
     public function beforeAction($action)
    {
        if (in_array($action->id, ['read-messages','send-message','send-answer','delete-message'])) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }
    
    public function actionIndex()
    {       
        $dialogs = DialogMessages::find()->joinWith('from')
            ->where([DialogMessages::tableName().'.user_to' =>  Yii::$app->user->identity->id])
            ->orderBy('dialog_messages.is_read ASC, dialog_messages.creation_time DESC')
            ->all();
       // var_dump($dialogs);die();

       // die();
        SeoComponent::setByTemplate('default', [
            'name' => 'Диалоги',
        ]);
        
        return $this->render('dialogs.twig', [
            'dialogs' => $dialogs 
        ]);
    }
    
    public function actionDialog($id)
    {
//        unset($this->view->assetBundles['frontend\assets\AppAsset']);
        $current_user_id = Yii::$app->user->identity->id;
        $dialog          = Dialogs::find()->byId($id)->joinWith(['lot', 'messages'], true)->limit(1)->one();
        if(is_null($dialog))
        {
            throw new NotFoundHttpException("Dialog doesn't exists!", 404);
        }
        if(!in_array($current_user_id, [$dialog->lot_owner, $dialog->interlocutor_id]) || ($dialog->status != 1))
        {
            throw new \yii\web\ForbiddenHttpException("Access denied!");
        }
        // при открытии диалога ставим всем своим сообщениям статус "прочитано"
        $update_sql = "UPDATE `".DialogMessages::tableName()."` SET `is_read` = 1, `is_showed` = 1 "
                . "WHERE `dialog_id` = ".$dialog->id." AND `user_to` = ".$current_user_id;
        Yii::$app->db->createCommand($update_sql)->execute();
        
        SeoComponent::setByTemplate('default', [
            'name' => 'Диалог',
        ]);
        
        return $this->render('messages.twig', [
            'dialog' => $dialog
        ]);
    }
    
    public function actionCreateDialog()
    {
        $request = Yii::$app->request;
        if($request->isAjax)
        {
            throw new BadRequestHttpException("Wrong request", 400);
        }
        
        $post               = $request->post();
        $lot_id             = strip_tags(trim($post['lot_id']));
        $owner_id           = strip_tags(trim($post['owner_id']));
        $interlocutor_id    = strip_tags(trim($post['interlocutor_id']));
        $dialog             = Dialogs::find()->andWhere([Dialogs::tableName().'.lot_id' => $lot_id,
                                                         Dialogs::tableName().'.lot_owner' => $owner_id,
                                                         Dialogs::tableName().'.interlocutor_id' => $interlocutor_id])
                                            ->limit(1)->one();
        $lot = \common\models\Lots::findOne($lot_id);
        if(is_null($dialog))
        {
            $dialog                     = new Dialogs();
            $dialog->subject            = '';
            $dialog->lot_id             = $lot_id;
            $dialog->lot_owner          = $owner_id;
            $dialog->interlocutor_id    = $interlocutor_id;
            $dialog->status             = 1;
            
            if(!$dialog->save() || is_null($lot))
            {
                throw new ErrorException('Cannot create a new dialog!');
            }
            MailComponent::mailsend(['name' => $lot->name], 'create_dialog');
            // здесь все безопасно, потому никаких проверок не делаю
            $lot->suggestions_count = $lot->suggestions_count + 1;
            $lot->save();
        }
        return $this->redirect(\yii\helpers\Url::to(['/dialog/'.$dialog->id]), 301)->send();
    }
    
    public function actionSendMessage()
    {
      //  Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if(!$request->isAjax)
        {
            throw new BadRequestHttpException("Wrong request", 400);
        }
        $current_user_id    = Yii::$app->user->identity->id;

        $post               = $request->post();
        $lot=Lots::find()->byId($post['dialog_id'])->limit(1)->one();
        //var_dump($post);die();
        $message            = new DialogMessages();
        $message->lot_id    = $lot->id;
        $message->user_from = $current_user_id;
        $message->user_to   = $lot->owner->id;
        //var_dump($post);
        $message->text      = $post['quest'];
        $message->is_read   = 0;
     //var_dump($message->save());die();
        if($message->save())
        {
            return $data=true;
        }
        else
        {
            return ['ans' => false, 'msg' => serialize($message->getErrors())];
        }
    }
    public function actionSendAnswer()
    {
        //  Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if(!$request->isAjax)
        {
            throw new BadRequestHttpException("Wrong request", 400);
        }
        $current_user_id    = Yii::$app->user->identity->id;

        $post               = $request->post();
        $dialog=DialogMessages::find()->byId($post['answer_id'])->limit(1)->one();
       // var_dump($dialog);die();
        $message            = new DialogMessages();
        $message->lot_id    = $dialog->lot_id;
        $message->user_from = $current_user_id;
        $message->user_to   = $dialog->user_from;
        //var_dump($post);
        $message->text      = $post['answer'];
        $message->is_read   = 0;
        $dialog->is_read    = 1;
        //var_dump($message->save());die();
        if($message->save() && $dialog->save())
        {
            return $data=true;
        }
        else
        {
            return ['ans' => false, 'msg' => serialize($message->getErrors())];
        }
    }
    public function actionDeleteMessage()
    {
        //  Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if(!$request->isAjax)
        {
            throw new BadRequestHttpException("Wrong request", 400);
        }

        $post               = $request->post();
        $dialog=DialogMessages::find()->byId($post['dialogId'])->limit(1)->one();
        //var_dump($post);die();
        if( $dialog->delete())
        {
            return $data=true;
        }
        else
        {
            return ['ans' => false, 'msg' => serialize($message->getErrors())];
        }
    }
    public function actionCheckMessages($dialog_id = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = '';
        $msgs_count = DialogMessages::countUnreadMessages();
        if(!is_null($dialog_id) && $dialog_id > 0 && $msgs_count)
        {
            $messages = DialogMessages::dialogUnreadMessages($dialog_id);
            foreach ($messages as $msg)
            {
                $msg->is_showed = 1;
                $msg->save();
            }
//            $data = $this->renderAjax('checked_messages.twig', ['messages' => $messages]);   
            
            $data = '';    
        } 
        else 
        {
            $dialogs = Dialogs::dialogsWithUnreadMessages();
        }

        return ['count' => $msgs_count, 'data' => $data];
    }
    
    public function actionReadMessages($dialog_id = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!is_null($dialog_id))
        {
            $transaction = Yii::$app->getDb()->beginTransaction();
            $messages = DialogMessages::dialogUnreadMessages((int)$dialog_id);
            if(is_null($messages))
            {
                $transaction->rollBack();
                return ['status' => true];
            }
            $update_statuses = [];
            foreach ($messages as $msg)
            {
                $msg->is_read = 1;
                $msg->is_showed = 1;
                $update_statuses[] = $msg->save();
            }
            if(in_array(false, $update_statuses))
            {
                $transaction->rollBack();
                return ['status' => false];
            } else {
                $transaction->commit();
                return ['status' => true];
            }
        }

        return ['status' => true];
    }

}