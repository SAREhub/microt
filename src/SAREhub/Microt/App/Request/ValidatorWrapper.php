<?php


namespace SAREhub\Microt\App\Request;


use Respect\Validation\Validatable;

class ValidatorWrapper
{
    /**
     * @var Validatable
     */
    private $validator;

    public function __construct(Validatable $validator)
    {
        $this->validator = $validator;
    }

    public static function create(Validatable $validator): self
    {
        return new self($validator);
    }

    public function getValidator(): Validatable
    {
        return $this->validator;
    }
}
