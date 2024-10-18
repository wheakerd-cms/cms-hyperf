<?php
declare(strict_types=1);

namespace App\Security\Admin;

use App\Library\JsonWebToken\LibraryJWS;
use Hyperf\Config\Annotation\Value;

/**
 * @SecurityAdminJws
 * @\App\Security\Admin\SecurityAdminJws
 */
class SecurityAdminJws extends LibraryJWS
{

    /**
     * @var string $key
     */
    #[Value('admin.key')]
    protected string $key;
}