<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class UserModelTest extends TestCase
{
    protected function setUp(): void
    {
        test_resetDatabase();
    }

    public function testCreateUserHashesPasswordAndAllowsLogin(): void
    {
        $created = userModel_create('manager', 'motdepasse123');
        $this->assertTrue($created);

        $user = userModel_getByUsername('manager');
        $this->assertNotNull($user);
        $this->assertArrayHasKey('password_hash', $user);
        $this->assertNotSame('motdepasse123', $user['password_hash']);

        $verified = userModel_verifyLogin('manager', 'motdepasse123');
        $this->assertNotNull($verified);
        $this->assertSame('manager', $verified['username']);
    }

    public function testUsernameExistsDetectsDuplicates(): void
    {
        $this->assertTrue(userModel_usernameExists('admin'));
        $this->assertFalse(userModel_usernameExists('inconnu'));
    }
}
