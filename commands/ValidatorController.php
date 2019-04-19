<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 19.04.19
 * Time: 16:50
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\widgets\Table;
use yii\helpers\Console;
use yii\validators\NumberValidator;

class ValidatorController extends Controller
{
  private $validation_list = [
      "app\\validators\\BlacklistValidator" => "Черный список",
      "app\\validators\\RegMachValidator" => "Регулярное выражение",
      "app\\validators\\NotHaveEmailValidator" => "email",
      "app\\validators\\NotHaveLinkValidator" => "Наличие ссылок",
  ];

  private $autoList = [1, 2, 3]; //номера тестов выполняемые при автотесте. начинается с индекса 1.

  private $str;

  /**
   * Ручной выбор тестов
   * @param bool $str
   * @throws \Exception
   */
  public function actionIndex($str = false)
  {
    $this->getString($str);

    $errors = [];
    while (true) {
      $k = $this->menu();
      if (empty($k)) break;

      $validator = new $k();
      if (property_exists($validator, 'debug')) {
        $validator->debug = true;
      }
      $validator->validate($this->str, $errors);

      echo "\n";
    }

    $this->echoError($errors);
  }

  /**
   * Выполнить автотест
   */
  public function actionAuto($str = false)
  {
    $this->getString($str);

    $errors = [];
    $validatesName = array_keys($this->validation_list);
    $i = 1;
    echo $this->ansiFormat("Выполненные ваидаторы\n", Console::FG_GREEN);
    foreach ($this->autoList as $k) {
      if (!isset($validatesName[$k - 1])) {
        continue;
      }

      $k = $validatesName[$k - 1];
      $validator = new $k();
      if (property_exists($validator, 'debug')) {
        $validator->debug = true;
      }
      $validator->validate($this->str, $errors);

      $item = $this->validation_list[$k];
      echo $this->ansiFormat($i++ . ') ', Console::FG_YELLOW) . $item . "\n";
    }

    echo "\n";
    $this->echoError($errors, false);
  }


  /**
   * Выводит меню для выбора необходимого валидатора
   */
  private function menu()
  {
    echo $this->ansiFormat("Варианты валидацию\n", Console::FG_GREEN);
    $k = 1;
    echo $this->ansiFormat('0) ', Console::FG_YELLOW) . "Результаты\n";
    foreach ($this->validation_list as $item) {
      echo $this->ansiFormat($k . ') ', Console::FG_YELLOW) . $item . "\n";
      $k++;
    }

    $nv = new NumberValidator();
    do {
      echo $this->ansiFormat('Сделайте свой выбор', Console::FG_GREEN);
      echo ": ";
      $k = readline();
    } while (
        !$nv->validate($k) ||
        $k > count($this->validation_list)
    );

    if (!empty($k)) {
      $validatesName = array_keys($this->validation_list);
      return $validatesName[$k - 1];
    }
    return null;
  }

  /**
   * Получает строку для валидации
   */
  private function getString($str = false)
  {
    if (!empty($str)) {
      $this->str = $str;
      echo $this->str . "\n";
    } else {
      Console::clearScreen();
      echo $this->ansiFormat('Введите текст для теста ', Console::FG_GREEN);
      echo $this->ansiFormat('(текст по умолчанию)', Console::FG_YELLOW);
      echo ": ";
      $this->str = readline();
      if (empty($this->str)) {
        $this->str = "Here is new message from Administration !!!
      You can find me on facebook or by mail example@gmail.com.
      My site http://example.com";
        echo $this->str . "\n";
      }
    }
    echo "\n";
  }

  /**
   * Вывод ошибок
   */
  private function echoError($errors, $clean = true)
  {
    if (count($errors) == 0) {
      echo "Ошибок не найдено\n";
    } else {
      if ($clean) Console::clearScreen();
      echo "Result:\n";
      echo Table::widget([
          'headers' => ['Тип', 'Слово', 'Дополнительно'],
          'rows' => $errors,
      ]);
    }
  }
}