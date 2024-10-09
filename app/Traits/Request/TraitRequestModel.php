<?php
declare(strict_types=1);

namespace App\Traits\Request;

use Hyperf\Contract\ValidatorInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

/**
 * @TraitRequestRules
 * @\App\Traits\Request\TraitRequestRules
 */
trait TraitRequestModel
{

    public function validator(ValidatorFactoryInterface $factory): ValidatorInterface
    {
        $this->scenes = $this->dao->getModel()->scenes;

        return $this->createDefaultValidator($factory);
    }
}