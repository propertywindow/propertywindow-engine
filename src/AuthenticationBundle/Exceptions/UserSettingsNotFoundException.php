<?php declare(strict_types = 1);

namespace AuthenticationBundle\Exceptions;

use AppBundle\Exceptions\Exception;

/**
 * @package AuthenticationBundle\Exceptions
 */
class UserSettingsNotFoundException extends Exception
{
    /**
     * @var int
     */
    private $id;

    /**
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;

        parent::__construct(sprintf("Could not find settings for user with id: %d", $id));
    }
}
