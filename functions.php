<?
namespace exp4php\Tests;

use exp4php\func\Func;

class MaxFunctionHelper extends Func
{
    public function apply($args) 
    {
        return max($args[0], $args[1]);
    }
}


class MinFunctionHelper extends Func
{
    public function apply($args)
    {
        return min($args[0], $args[1]);
    }
}

class RoundToMultipleHelper extends Func
{
    public function apply($args)
    {
        @list($value, $multiple) = $args;
        if (null === $multiple || $multiple <= 0) {
            return $value;
        }

        $multiplier = 1 / $multiple;

        return round($value * $multiplier) / $multiplier;
    }
}
