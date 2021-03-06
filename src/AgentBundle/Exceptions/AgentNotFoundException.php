<?php
declare(strict_types = 1);

namespace AgentBundle\Exceptions;

use AppBundle\Exceptions\Exception;

/**
 * AgentNotFound Exception
 */
class AgentNotFoundException extends Exception
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

        parent::__construct(sprintf("Could not find agent with id: %d", $id));
    }
}
