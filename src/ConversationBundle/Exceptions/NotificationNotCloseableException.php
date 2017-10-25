<?php declare(strict_types=1);

namespace ConversationBundle\Exceptions;

use AppBundle\Exceptions\Exception;

/**
 * @package ConversationBundle\Exceptions
 */
class NotificationNotCloseableException extends Exception
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

        parent::__construct(sprintf("Notification %d cannot be closed", $id));
    }
}
