<?php

namespace exp4php\Tests;

use exp4php\ExpressionBuilder;

class BasicTest extends \PHPUnit\Framework\TestCase
{
    public function testBasic()
    {
        $equation = "1*(0.2*10+1*20+4.5)";
        $mathCalculator = new ExpressionBuilder($equation);
        $value = $mathCalculator->build()->evaluate();
        $this->assertEquals($value, 26.5);
    }

    public function providerEquations()
    {
        return [
            [
                'equation' => 'max(8,12)',
                'expectedValue' => 12,
            ],
            [
                'equation' => 'min(140,(1*(0.2*durationInMinute+1*distanceInKm+4.5)))',
                'expectedValue' => 140,
            ],
            [
                'equation' => 'max(140,(1*(0.2*durationInMinute+1*distanceInKm+4.5)))',
                'expectedValue' => 144.5,
            ],
            [
                'equation' => 'mround(10.50, 20)',
                'expectedValue' => 20,
            ],
        ];
    }

    /**
     * @dataProvider providerEquations
     */
    public function testWithVariables($equation, $expectedValue)
    {
        $mathCalculator = new ExpressionBuilder($equation);

        $customFunctions = [
            new MinFunctionHelper('min'),
            new MaxFunctionHelper('max'),
            new RoundToMultipleHelper('mround'),
        ];

        foreach ($customFunctions as $mathFunction) {
            $mathCalculator->func($mathFunction);
        }

        $formulaVariables = [
            'distanceInKm' => 100,
            'durationInMinute' => 200,
            'boardingFee' => 300
        ];
        foreach ($formulaVariables as $variableName => $value) {
            $mathCalculator->variable($variableName);
        }

        $mathCalculatorBuild = $mathCalculator->build();

        foreach ($formulaVariables as $variableName => $value) {
            $mathCalculatorBuild->setVariable($variableName, $value);
        }

        $value = (double) $mathCalculatorBuild->evaluate();
        $this->assertEquals($value, $expectedValue);
    }
}
