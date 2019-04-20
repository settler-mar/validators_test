<?php

namespace app\validators;

class BlacklistValidator extends \yii\validators\Validator
{

  public $debug = false;

  public $blackList = [
      'SiteAnalyst',
      'SiteAdmin',
      'Administration',
      'admin'
  ];

  public function init()
  {
    $this->message = "Запрещеное слово";
    parent::init();
  }

  public function validateAttribute($model, $attribute)
  {
    $value = $model->$attribute;

    foreach ($this->blackList as $item) {
      if (strpos($value, $item) !== false) {
        $model->addError($attribute, $this->generateMessage($item));
        if (!$this->debug) return false;
      }
    }
    return true;
  }

  public function validate($value, &$error = null)
  {
    foreach ($this->blackList as $item) {
      if (strpos($value, $item) !== false) {
        $error[] = $this->generateMessage($item);
        if (!$this->debug) return false;
      }
    }
    return true;
  }

  private function generateMessage($item)
  {
    if (!$this->debug) return $this->message;
    return [$this->message, $item];
  }
}