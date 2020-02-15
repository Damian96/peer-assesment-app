<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class UniqueCombo
 * @property  string otherColumn
 * @property  \Request request
 * @property string table
 * @property string column
 * @property string attribute
 * @package App\Rules
 */
class UniqueCombo implements Rule
{
    private $otherColumn;
    private $request;
    private $table;
    private $attribute;

    /**
     * Create a new rule instance.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $table
     * @param string $column
     * @param string $otherColumn
     */
    public function __construct(Request $request, string $table, string $column, string $otherColumn)
    {
        $this->request = $request;
        $this->table = $table;
        $this->column = $column;
        $this->otherColumn = $otherColumn;
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
        return !DB::table($this->table)->where($this->column, '=', $value)
            ->where($this->otherColumn, '=', $this->request->get($this->otherColumn))
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The combination of {$this->attribute} and {$this->otherColumn} is not correct.";
    }
}
