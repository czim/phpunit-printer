<?php

namespace Czim\PHPUnitPrinter;

use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestFailure;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestSuite;
use PHPUnit\TextUI\ResultPrinter;
use PHPUnit\Util\Filter;
use UnexpectedValueException;

class PrettyPrinter extends ResultPrinter implements TestListener
{

    const STATE_SUCCESS    = '.';
    const STATE_FAILED     = 'F';
    const STATE_ERROR      = 'E';
    const STATE_RISKY      = 'R';
    const STATE_SKIPPED    = 'S';
    const STATE_INCOMPLETE = 'I';

    /**
     * @var string
     */
    protected $className;

    /**
     * @var string|null
     */
    protected $previousClassName;

    /**
     * See STATE_ constants.
     *
     * @var string
     */
    protected $previousState;


    public function startTestSuite(TestSuite $suite): void
    {
        parent::startTestSuite($suite);
    }

    public function startTest(Test $test): void
    {
        $this->className = get_class($test);
    }

    public function endTest(Test $test, float $time): void
    {
        parent::endTest($test, $time);

        $name = $this->makeFriendlyTestNameString($test);

        $color = $this->getColorStringForState($this->previousState);

        $this->write(' ');

        $stateString = $this->getFriendlyStateString($this->previousState);
        if ($stateString) {
            $this->writeWithColor('fg-white', '[', false);
            $this->writeWithColor($color, $stateString, false);
            $this->writeWithColor('fg-white', ']  ', false);
        }

        $this->writeWithColor($color, $name, false);
        $this->write(' ');

        if ($this->isTimeBeyondSlowThreshold($time)) {
            $this->writeWithColor('fg-white', '[', false);
            $color = $this->isTimeBeyondVerySlowThreshold($time)
                ?   'fg-red'
                :   'fg-yellow';
            $this->writeWithColor($color, number_format($time, 3), false);
            $this->writeWithColor('fg-white', 's]', false);
        }

        $this->writeNewLine();
    }

    protected function writeProgress(string $progress): void
    {
        $this->numTestsRun++;

        if ($this->previousClassName !== $this->className) {

            $this->writeNewLine();

            $this->writeWithColor('fg-white', '[ ', false);
            $this->writeWithColor('fg-yellow', str_pad($this->numTestsRun - 1, $this->numTestsWidth, ' ', STR_PAD_LEFT), false);
            $this->writeWithColor('fg-white', ' / ', false);
            $this->writeWithColor('fg-white', $this->numTests, false);
            $this->writeWithColor('fg-white', ' ]   ', false);

            $this->writeWithColor('bold', $this->className, false);

            $this->writeNewLine();
        }

        $this->previousClassName = $this->className;


        $normalizedProgressState = strtoupper($this->filterColorCodes($progress));

        $this->previousState = $normalizedProgressState;

        $stateColor = $this->getColorStringForState($normalizedProgressState);

        switch ($normalizedProgressState) {

            case static::STATE_SUCCESS:
                $this->writeWithColor($stateColor, '  ✓', false);
                break;

            case static::STATE_SKIPPED:
                $this->writeWithColor($stateColor, '  -', false);
                break;

            case static::STATE_INCOMPLETE:
                $this->writeWithColor($stateColor, '  -', false);
                break;

            case static::STATE_FAILED:
                $this->writeWithColor($stateColor, '  x', false);
                break;

            case static::STATE_ERROR:
                $this->writeWithColor($stateColor, '  x', false);
                break;

            case static::STATE_RISKY:
                $this->writeWithColor($stateColor, '  -', false);
                break;
        }
    }

    /**
     * @param string $state
     * @return string
     */
    protected function getColorStringForState($state)
    {
        switch ($state) {

            case static::STATE_SUCCESS:
                return 'fg-green';

            case static::STATE_SKIPPED:
                return 'fg-yellow';

            case static::STATE_INCOMPLETE:
                return 'fg-yellow';

            case static::STATE_FAILED:
                return 'fg-red';

            case static::STATE_ERROR:
                return 'fg-red';

            case static::STATE_RISKY:
                return 'fg-magenta';
        }

        throw new UnexpectedValueException("Unknown progress state: '{$state}'");
    }

    /**
     * @param string $state
     * @return string
     */
    protected function getFriendlyStateString($state)
    {
        switch ($state) {

            case static::STATE_SUCCESS:
                return '';

            case static::STATE_SKIPPED:
                return 'skipped';

            case static::STATE_INCOMPLETE:
                return 'incomplete';

            case static::STATE_FAILED:
                return 'FAILED';

            case static::STATE_ERROR:
                return 'ERROR';

            case static::STATE_RISKY:
                return 'risky';
        }

        throw new UnexpectedValueException("Unknown progress state: '{$state}'");
    }

    /**
     * @param string $string
     * @return string
     */
    protected function filterColorCodes($string)
    {
        return preg_replace('#\\x1b[[][^A-Za-z]*[A-Za-z]#', '', $string);
    }

    protected function printDefectTrace(TestFailure $defect): void
    {
        $this->write($this->formatExceptionMsg($defect->getExceptionAsString()));

        $trace = Filter::getFilteredStacktrace(
            $defect->thrownException()
        );

        if (!empty($trace)) {
            $this->write("\n" . $trace);
        }

        $exception = $defect->thrownException()->getPrevious();

        while ($exception) {
            $this->write(
                "\nCaused by\n" .
                TestFailure::exceptionToString($exception) . "\n" .
                Filter::getFilteredStacktrace($exception)
            );
            $exception = $exception->getPrevious();
        }
    }

    protected function formatExceptionMsg($exceptionMessage): string
    {
        $exceptionMessage = str_replace("+++ Actual\n", '', $exceptionMessage);
        $exceptionMessage = str_replace("--- Expected\n", '', $exceptionMessage);
        $exceptionMessage = str_replace('@@ @@', '', $exceptionMessage);

        if ($this->colors) {
            $exceptionMessage = preg_replace('/^(Exception.*)$/m', "\033[01;31m$1\033[0m", $exceptionMessage);
            $exceptionMessage = preg_replace('/(Failed.*)$/m', "\033[01;31m$1\033[0m", $exceptionMessage);
            $exceptionMessage = preg_replace("/(\-+.*)$/m", "\033[01;32m$1\033[0m", $exceptionMessage);
            $exceptionMessage = preg_replace("/(\++.*)$/m", "\033[01;31m$1\033[0m", $exceptionMessage);
        }

        return $exceptionMessage;
    }

    protected function makeFriendlyTestNameString(Test $test): string
    {
        $testMethodName = \PHPUnit\Util\Test::describe($test);

        $testMethodName[1] = str_replace('_', '', ucwords($testMethodName[1], '_'));

        preg_match_all('/((?:^|[A-Z])[a-z]+)/', $testMethodName[1], $matches);

        $testNameArray = array_map('strtolower', $matches[0]);

        if ($testNameArray[0] === 'test') {
            array_shift($testNameArray);
        }

        $name = implode(' ', $testNameArray);

        return $this->appendDataSeTotName($name, $testMethodName[1]);
    }

    protected function appendDataSeTotName($name, $testMethodName): string
    {
        preg_match('/\bwith data set "([^"]+)"/', $testMethodName, $dataSetMatch);

        if (empty($dataSetMatch)) {
            return $name;
        }

        return $name . ' [' . $dataSetMatch[1] . ']';
    }

    protected function isTimeBeyondSlowThreshold(float $time): bool
    {
        return $time > 0.5;
    }

    protected function isTimeBeyondVerySlowThreshold(float $time): bool
    {
        return $time > 1.0;
    }
}
