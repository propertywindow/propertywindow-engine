<?php declare(strict_types = 1);

namespace PropertyBundle\Exceptions;

use AppBundle\Exceptions\Exception;

/**
 * @package PropertyBundle\Exceptions
 */
class TermsNotFoundException extends Exception
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

        parent::__construct(sprintf("Could not find terms with id: %d", $id));
    }
}
