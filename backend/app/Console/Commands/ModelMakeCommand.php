<?php

namespace App\Console\Commands;

//use Illuminate\Console\Command;
use Illuminate\Foundation\Console\ModelMakeCommand as Command;

class ModelMakeCommand extends Command
{
//_/\*\*
//\* Get the default namespace for the class.
//\*
//\*_ **_@param_** _string $rootNamespace
//\*_ **_@return_** _string
//\*/_
    protected function getDefaultNamespace($rootNamespace)
{
    return "{$rootNamespace}\Models";
}
}
