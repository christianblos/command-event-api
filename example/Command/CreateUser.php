<?php
declare(strict_types=1);

class CreateUser
{
    /** @var string */
    private $email;
    /** @var string */
    private $password;

    public function __construct(string $email, string $password)
    {
        $this->email    = $email;
        $this->password = $password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
