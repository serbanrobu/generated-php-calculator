<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;

/**
 * Created by kurtis on 2015-10-04.
 * Calculator class for performing operations
 * Sets all the word and numbers into an array
 * Recursively parses each value in the array until it gets a return value
 */
class Calculator {

    //command is initial string, tokens is array holding the words and numbers
    //spot keeps track of where we are in the array
    //variable array for storing calculated values from let statements, range a-z (26)
    private $command;
    private $tokens;
    private $result;
    private $spot;
    private $variables;
    private $logger;

    public function __construct()
    {
        $this->initLogger();
        $this->logger->info("Calculator created");
    }

    protected function execute()
    {
        $this->logger->info("Calculator class executing");
        //check empty or null command
        if(is_null($this->command) || empty($this->command)) {
            $this->logger->error("command string in calculator class is null or empty, program exiting");
            echo "No commands found";
            exit(0);
        }
        //26 possible vars
        $this->variables = array_fill(0, 26, 0);
        //split by non-word characters
        $this->tokens = preg_split('/[^\w]+/', $this->command, -1, PREG_SPLIT_NO_EMPTY);
        $this->logger->info("tokens[]: " . (implode(', ', $this->tokens)));
        $this->result = $this->parseTokens();
        $this->logger->info("result: " . $this->result);
        return $this->result;
    }

    //uses spot variable to remember position
    private function parseTokens()
    {
        $value = 0;
        switch ($this->tokens[$this->spot]) {
            case "add":
                $this->logger->info("add detected");
                $value = $this->add();
                break;
            case "sub":
                $this->logger->info("sub detected");
                $value = $this->sub();
                break;
            case "mult":
                $this->logger->info("mult detected");
                $value = $this->mult();
                break;
            case "div":
                $this->logger->info("div detected");
                $value = $this->div();
                break;
            case "let":
                $this->logger->info("let detected");
                $value = $this->let();
                $this->spot++;
                $value = $this->parseTokens();
                break;
            default:
                $ch = $this->tokens[$this->spot][0];
                //test if variable previously set, then return that value
                if(strlen($this->tokens[$this->spot]) == 1 && ($ch >= 'a' && $ch <= 'z')) {
                    $this->logger->info("array value detected: " . $this->variables[ord($ch)-97]);
                    return $this->variables[ord($ch)-97];
                }
                else {
                    //else number found
                    $this->logger->info("number detected " . $this->tokens[$this->spot]);
                    $value = (int) $this->tokens[$this->spot];
                }
                break;
        }
        return $value;
    }

    //let statement: let(var, value/expression, expression using var)
    //differs from other methods as it returns parseTokens for the expression using the variable
    private function let()
    {
        $this->spot++;
        $val = $this->tokens[$this->spot][0];
        $this->spot++;
        $this->variables[ord($val)-97] = $this->parseTokens();
        $value = $this->parseTokens();
        $this->logger->info("let statement produced: " . $value);
        return $value;
    }

    //add method: add(value/expression, value/expression)
    //gets the value using parseTokens at each value/expression and adding them
    //incrementing global spot variable which keeps track of our spot in the tokens[] array
    //same algorithm is used for sub, div, and mult
    private function add()
    {
        $this->spot++;
        $v1 = $this->parseTokens();
        $this->spot++;
        $v2 = $this->parseTokens();
        $value = $v1+$v2;
        $this->logger->info("add statement produced: " . $value);
        return $value;
    }

    //subtraction method,
    private function sub()
    {
        $this->spot++;
        $v1 = $this->parseTokens();
        $this->spot++;
        $v2 = $this->parseTokens();
        $value = $v1-$v2;
        $this->logger->info("sub statement produced: " . $value);
        return $value;
    }

    //multiplication method
    private function mult()
    {
        $this->spot++;
        $v1 = $this->parseTokens();
        $this->spot++;
        $v2 = $this->parseTokens();
        $value = $v1*$v2;
        $this->logger->info("mult statement produced: " . $value);
        return $value;
    }

    //division method
    private function div()
    {
        $this->spot++;
        $v1 = $this->parseTokens();
        $this->spot++;
        $v2 = $this->parseTokens();
        $value = $v1/$v2;
        $this->logger->info("div statement produced: " . $value);
        return $value;
    }

    //set up the logger
    private function initLogger()
    {
        try {
            $this->logger = new Logger('name');
            $this->logger->pushHandler(new StreamHandler('src/main/resources/your.log', Logger::DEBUG));
            $this->logger->pushProcessor(new PsrLogMessageProcessor);
        }
        catch(Exception $e) {
            $e->getMessage();
            echo "WARNING: Unable to initialize logger\n";
        }
    }

    //setter for command string
    protected function setCommand($command)
    {
        $this->command = $command;
    }
    //getter for command string
    protected function getCommand()
    {
        return $this->command;
    }
}