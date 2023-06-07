<?php
use PHPUnit\Framework\TestCase;

/**
 * Created by kurtis on 2015-10-05.
 * All the tests from the example input
 */
class TestCalculator extends TestCase {
    public function testAdd() {
        $calculator = new Calculator();
        $calculator->setCommand("add(1,2)");
        $this->assertEquals(3, $calculator->execute(), "add(1,2) = 3 ");
    }

    public function testAddWithMult() {
        $calculator = new Calculator();
        $calculator->setCommand("add(1, mult(2,3))");
        $this->assertEquals(7, $calculator->execute(), "add(1, mult(2,3)) = 7");
    }

    public function testMultWithDiv() {
        $calculator = new Calculator();
        $calculator->setCommand("mult(add(2,2),div(9,3))");
        $this->assertEquals(12, $calculator->execute(), "mult(add(2,2),div(9,3)) = 12");
    }

    public function testLetWithAdd() {
        $calculator = new Calculator();
        $calculator->setCommand("let(a,5,add(5,5))");
        $this->assertEquals(10, $calculator->execute(), "let(a,5,add(5,5)) = 10");
    }

    public function testMultLetWithMultAndAdd() {
        $calculator = new Calculator();
        $calculator->setCommand("let(a,5,let(b,mult(a,10),add(b,a)))");
        $this->assertEquals(55, $calculator->execute(), "let(a,5,let(b,mult(a,10),add(b,a))) = 55");
    }

    public function testMultLetWithMultAdd() {
        $calculator = new Calculator();
        $calculator->setCommand("let(a,let(b,10,add(b,b)),let(b,20,add(a,b)))");
        $this->assertEquals(40, $calculator->execute(), "let(a,let(b,10,add(b,b)),let(b,20,add(a,b))) = 40");
    }
}
?>