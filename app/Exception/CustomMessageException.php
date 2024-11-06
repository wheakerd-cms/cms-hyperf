<?php
declare(strict_types=1);

namespace App\Exception;

use Hyperf\Server\Exception\ServerException;

/**
 * @document Thrown a custom exceptions
 * @CustomMessageException
 * @\App\Exception\CustomMessageException
 */
class CustomMessageException extends ServerException
{
}