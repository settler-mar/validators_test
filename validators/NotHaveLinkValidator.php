<?php

namespace app\validators;


class NotHaveLinkValidator extends \yii\validators\Validator
{

  public $debug = false;

  private $linkPattern = '/\b(https?|ftps?|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i';

  public function init()
  {
    $this->message = "Содержит линк";
    parent::init();
  }

  public function validateAttribute($model, $attribute)
  {
    $value = $model->$attribute;

    preg_match_all($this->linkPattern, $value, $matches);
    if (empty($matches)) return true;

    foreach ($matches[0] as $item) {
      $model->addError($attribute, $this->generateMessage($item));
      if (!$this->debug) return false;
    }
    return true;
  }

  public function validate($value, &$error = null)
  {
    preg_match_all($this->linkPattern, $value, $matches);
    if (empty($matches)) return true;

    foreach ($matches[0] as $item) {
      $error[] = $this->generateMessage($item);
      if (!$this->debug) return false;
    }
    return true;
  }

  private function generateMessage($item)
  {
    if (!$this->debug) return $this->message;
    return [$this->message, $item];
  }
}