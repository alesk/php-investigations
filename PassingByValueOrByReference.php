<?php

/**
 * Assumption:
 *
 * Arrays if passed by value are copied and changing the value does not 
 * affect the value at the original array.
 * See `testArrayPassingByValue`.
 *
 * If array is passed by reference, mutating passed array mutates original.
 * See `testArrayPassingByReference`.
 *
 * If array contains objects, the copy of aray will contain copies of objects
 * identifiers which are still pointing to the original object.
 * See `testArrayPAssingByValueCotainingObjects`.
 *
 * Variables pointing to object are actually pointing to object identifiers,
 * so after a copy, the new copy still contains the same object identifier
 * and effectively "points" to the same object. That's why passing by value
 * or passing by reference makes no difference.
 **/

class A { public $foo = 1;}

class PassingByValueOrByReferenceTest extends PHPUnit_Framework_TestCase {

  public function testArrayPassingByValue() {

    $a = [1, 2, "someString"];

    $passByValue = function($array) {
      $array[0] = 100; 
      $array[2] .= " changed";
    };

    $this->assertEquals(1, $a[0], 'Initial value of element at 0 is 1');
    $this->assertEquals("someString", $a[2], 'Initial value of element at 2 is someString');
    $passByValue($a);
    $this->assertEquals(1, $a[0], 'After passing by value, the value of element at 0 is still 1');
    $this->assertEquals("someString", $a[2], 'After passing by value, the value of element at 2 is still someString');
  }

  public function testArrayPassingByReference() {

    $a = [1, 2, "someString"];

    $passByReference= function(&$array) {
      $array[0] = 100;
      $array[2] .= " changed";
    };

    $this->assertEquals(1, $a[0], 'Initial value of element at 0 is 1');
    $this->assertEquals("someString", $a[2], 'Initial value of element at 2 is someString');
    $passByReference($a);
    $this->assertEquals(100, $a[0], 'After apssing by reference, the value of element at 0 is  100');
    $this->assertEquals("someString changed", $a[2], 'After passing by value, the value of element at 2 is still someString changed');
  }

  public function testArrayPassingByValueContainingObjects() {

    $a = [new A(), new A()];

    $passByValue = function($array) {$array[0]->foo = 100;};

    $this->assertEquals(1, $a[0]->foo, 'Initial value of element at 0 is 1');
    $passByValue($a);
    $this->assertEquals(100, $a[0]->foo, 'Passing by value does still changes the value if value is object\'s property.');
  }

  public function testArrayObjectPassingByValue() {

    $a = new ArrayObject([1, 2]);

    $passByValue = function($array) {$array[0] = 100;};

    $this->assertEquals(1, $a[0], 'Initial value of element at 0 is 1');
    $passByValue($a);
    $this->assertEquals(100, $a[0], 'Passing by array object by value makes possible to mutate original\'s values.');
  }

  public function testObjectPassingByValue() {
    $a = new A();

    $passByValue = function($object) {$object->foo = 100;};

    $this->assertEquals(1, $a->foo, 'Initial value of foo is 1');
    $passByValue($a);
    $this->assertEquals(100, $a->foo, 'Passing object by value and mutating it\'s property changes the original object.');

  }

  public function testObjectPassingByReference() {
    $a = new A();

    $passByValue = function($object) {$object->foo = 100;};

    $this->assertEquals(1, $a->foo, 'Initial value of foo is 1');
    $passByValue($a);
    $this->assertEquals(100, $a->foo, 'Passing object by reference and mutating it\'s property changes the original object.');

  }
}
