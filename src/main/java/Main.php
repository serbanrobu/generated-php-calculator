<?php
require_once 'Logger.php';

/**
 * Created by kurtis on 2015-10-04.
 * Main class, creates a calculator class
 * sets the command from the arg[0]
 * executes it and gets return value
 */
class Main {
    private static $logger;

    public static function main($args) {
        self::initLogger();
        self::$logger->info("program starting");
        if(count($args) < 1) {
            self::$logger->error("no command line arguments found, program exiting");
            echo "No arguments found\n";
            echo "Example argument: \"add(2,2)\"\n";
            exit(0);
        }
        self::$logger->info("arguments: " . $args[0]);
        //create calculator, set the command and run
        $calculator = new Calculator();
        $calculator->setCommand($args[0]);
        $result = $calculator->execute();
        echo $result . "\n";
        self::$logger->info("program complete with value: " . $result);
    }

    //set our logger properites, file specified is in resources folder
    private static function initLogger() {
        try {
            $props = new Properties();
            $istream = new FileInputStream("src/main/resources/log4j.properties");
            $props->load($istream);
            $istream->close();
            PropertyConfigurator::configure($props);
            //set the level to debug
            Logger::getRootLogger()->setLevel(Level::getDebug());
            self::$logger = Logger::getLogger(__CLASS__);
        }
        catch(Exception $e) {
            $e->printStackTrace();
            echo "WARNING: Unable to initialize logger\n";
        }
    }
}