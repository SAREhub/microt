<?php


namespace SAREhub\Microt\App\Request;


use Respect\Validation\Validatable;

class ValidatorWrapperFactory
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

    public function __invoke()
    {
        return $this->validator;
    }


}
