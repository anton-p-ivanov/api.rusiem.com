<?php

namespace App\Service;

use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;
use Symfony\Component\Security\Core\Encoder\SelfSaltingEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * Class EncoderService
 * @package App\Service
 */
class EncoderService extends BasePasswordEncoder implements SelfSaltingEncoderInterface
{
    const MAX_PASSWORD_LENGTH = 72;

    /**
     * @param string $raw
     * @param string|null $salt
     *
     * @return string
     */
    public function encodePassword(string $raw, ?string $salt): string
    {
        if ($this->isPasswordTooLong($raw)) {
            throw new BadCredentialsException('Invalid password.');
        }

        return password_hash($this->password($raw, $salt), PASSWORD_BCRYPT);
    }

    /**
     * {@inheritdoc}
     */
    public function isPasswordValid($encoded, $raw, $salt)
    {
        return !$this->isPasswordTooLong($raw) && password_verify($this->password($raw, $salt), $encoded);
    }

    /**
     * @param string $raw
     * @param string|null $salt
     *
     * @return string
     */
    protected function password(string $raw, ?string $salt): string
    {
        return $raw;
//        return ($_SERVER['APP_SECRET'] ?? $_ENV['APP_SECRET'] ?? null) . $raw . $salt;
    }
}