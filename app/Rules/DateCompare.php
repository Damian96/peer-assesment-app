<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class DateCompare implements Rule
{
    /**
     * @var string
     */
    private $operator;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var string
     */
    private $otherAttribute;
    /**
     * @var string
     */
    private $attribute;

    /**
     * Create a new rule instance.
     *
     * @param Request $request
     * @param string $operator
     * @param string $otherAttribute
     */
    public function __construct(Request $request, string $otherAttribute, string $operator = '>')
    {
        $this->operator = $operator;
        $this->request = $request;
        $this->otherAttribute = $otherAttribute;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->attribute = $attribute;
        return eval("return (strtotime('{$value}') {$this->operator} strtotime('{$this->request->get($this->otherAttribute)}'));");
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "{$this->attribute} should be greater than {$this->otherAttribute}";
    }
}
