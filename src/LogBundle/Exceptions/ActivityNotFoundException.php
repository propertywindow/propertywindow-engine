<?php declare(strict_types = 1);

namespace LogBundle\Exceptions;

use AppBundle\Exceptions\Exception;

/**
 * @package LogBundle\Exceptions
 */
class ActivityNotFoundException extends Exception
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

        parent::__construct(sprintf("Could not find activity log with id: %d", $id));
    }
}
