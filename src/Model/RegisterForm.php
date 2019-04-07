<?php


namespace App\Model;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterForm
{
    /**
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/[A-z\d\.\-]+/")
     */
    public $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="8", max="24")
     * @Assert\Regex(pattern="/[A-z\d]+/")
     */
    public $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="8", max="24")
     * @Assert\Regex(pattern="/[A-z\d]+/")
     */
    public $confirmPassword;

    public function __construct(Request $request = null)
    {
        if (null === $request) {
            return;
        }

        $this->username = $request->get('username');
        $this->password = $request->get('password');
        $this->confirmPassword = $request->get('confirmPassword');
    }

    /**
     * @param ValidatorInterface $validator
     * @return bool
     */
    public function validate(ValidatorInterface $validator): bool {
        if (false === $validator->validate($this)) {
            return false;
        }

        if ($this->confirmPassword !== $this->password) {
            return false;
        }

        return true;
    }
}