<?php

declare(strict_types=1);

namespace App\Form\Dto\Security;

use App\Form\Dto\DataTransferObjectInterface;
use App\Validator\Constraint as AppConstraints;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @AppConstraints\DtoUniqueEntity(entityClass="App\Entity\User", fieldMapping={"email": "email"}, message="There is already a user with email {{ value }}.")
 */
final class Registration implements DataTransferObjectInterface
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public $firstname;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public $lastname;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$#",
     *     message="Passwords must be at least 8 characters long, and have at least one uppercase letter, one lowercase letter, one number and one special character (@$!%*?&)."
     * )
     */
    public $password;

    /**
     * @var bool
     *
     * @Assert\IsTrue(message="You must accept terms & conditions to use our service.")
     */
    public $conditionsAccepted;

    /**
     * @var bool
     */
    public $optinCommercial;
}
