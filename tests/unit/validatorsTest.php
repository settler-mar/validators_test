<?php

class validatorsTest extends \Codeception\Test\Unit
{
  /**
   * @var \UnitTester
   */
  protected $tester;

  protected function _before()
  {

  }

  protected function _after()
  {
  }

  // tests
  public function testBlackList()
  {
    $validator = new \app\validators\BlacklistValidator();

    $validator->blackList = ['admin', 'root'];

    expect_that($validator->validate("Here is new message from max"));
    expect_not($validator->validate("Here is new message from admin"));
  }

  public function testRegList()
  {
    $validator = new \app\validators\RegMachValidator();

    $validator->patternList = [
        '(a+d+m+i+n+)'
    ];

    expect_that($validator->validate("Here is new message from max"));
    expect_not($validator->validate("Here is new message from admin"));
  }

  public function testNotHaveLink()
  {
    $validator = new \app\validators\NotHaveLinkValidator();

    expect_that($validator->validate("example.com is not a link"));
    expect_not($validator->validate("http://example.com is a link"));
    expect_not($validator->validate("some https://example.com is a link"));
    expect_not($validator->validate("some file://example.com is a link"));
  }
  
  public function testNotHaveEmail(){
    $validator = new \app\validators\NotHaveEmailValidator();

    expect_that($validator->validate("test @example.com for correct email"));
    expect_that($validator->validate("test some@example for correct email"));
    expect_that($validator->validate("test ftp://some@example.com for correct email"));
    expect_not($validator->validate("some file@example.com is a email"));
  }
}