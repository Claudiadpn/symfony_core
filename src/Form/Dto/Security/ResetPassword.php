<?php

declare(strict_types=1);

namespace App\Form\Dto\Security;

use App\Form\Dto\DataTransferObjectInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class ResetPassword implements DataTransferObjectInterface
{
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
}
