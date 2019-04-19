<?php

namespace app\validators;

use yii\validators\EmailValidator;

class NotHaveEmailValidator extends \yii\validators\Validator
{

  public $debug = false;

  private $emailPattern = '~\S*@\S*~';

  public function init()
  {
    $this->message = "Содержит email";
    parent::init();
  }

  public function validateAttribute($model, $attribute)
  {
    $value = $model->$attribute;

    preg_match_all($this->emailPattern, $value, $matches);
    if(empty($matches)) return true;

    $isMail = new EmailValidator();
    foreach ($matches[0] as $item){
      if($isMail->validate($item)){
        $model->addError($attribute, $this->generateMessage($item));
        if(!$this->debug) return false;
      }
    }
  }

  public function validate($value, &$error = null)
  {
    preg_match_all($this->emailPattern, $value, $matches);
    if(empty($matches)) return true;

    $isMail = new EmailValidator();
    foreach ($matches[0] as $item){
      if($isMail->validate($item)){
        $error[] = $this->generateMessage($item);
        if(!$this->debug) return false;
      }
    }
  }

  private function generateMessage($item){
    if(!$this->debug) return $this->message;
    return [$this->message,$item];
  }
}