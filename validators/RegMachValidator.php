<?php

namespace app\validators;

class RegMachValidator extends \yii\validators\Validator
{

  public $debug = false;

  public $patternList = [
      '(f+a+c+e+b+[o0]+[o0]+k+)',
      '(f+(a+)?.{1,2}c+e+b+.{1,2}o+k+)',
      '(f+.{1,3}b+o+[kc]+)'
  ];

  public function init()
  {
    $this->message = "Регулярное выражение";
    parent::init();
  }

  public function validateAttribute($model, $attribute)
  {
    $value = $model->$attribute;

    foreach ($this->patternList as $item) {
      preg_match_all('/' . $item . '/m', $value, $matches);
      if (!empty($matches)) {
        $model->addError($attribute, $this->generateMessage($item, $matches[1][0]));
        if (!$this->debug) return false;
      }
    }
  }

  public function validate($value, &$error = null)
  {
    foreach ($this->patternList as $item) {
      preg_match_all('/' . $item . '/m', $value, $matches);
      if (!empty($matches)) {
        $error[] = $this->generateMessage($item, $matches[1][0]);
        if (!$this->debug) return false;
      }
    }
  }

  private function generateMessage($item, $res)
  {
    if (!$this->debug) return $this->message;
    return [$this->message, $res, $item];
  }
}