<?php declare(strict_types = 1);

namespace ConversationBundle\Exceptions;

use AppBundle\Exceptions\Exception;

/**
 * @package ConversationBundle\Exceptions
 */
class EmailNotFoundException extends Exception
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

        parent::__construct(sprintf("Could not find email with id: %d", $id));
    }
}
