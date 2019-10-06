<?php


namespace App\Models;


use Backpack\CRUD\app\Models\Traits\InheritsRelationsFromParentModel;

class Student extends User
{
    use InheritsRelationsFromParentModel;

}
