<?php
declare(strict_types=1);

namespace App\Traits\Model;

/**
 * @TraitModelValidator
 * @\App\Traits\Model\TraitModelValidator
 */
trait TraitModelValidator
{

    public array $scenes = [];

    abstract public function rules(): array;
}