<?php

use PHPUnit\Framework\TestCase;

class BaseUserTest extends TestCase {

    protected $userObj;

    protected function setUp() {

        $this->userObj = new Modus\Common\Model\User\User;
        $this->userObj->configure([
                'id' => 1,
                'username' => 'brandon',
                'email' => 'test@example.com',
                'password' => '$2y$10$M0hbA4VjKmG1wEENiKPurONYgBB61nTU/TXkK27gTTi/apl81rbQ2',
        ]);
    }

    public function testGetEmailReturnsEmail() {
        $this->assertEquals($this->userObj->getEmail(), 'test@example.com');
    }

    public function testVerifyCredentialsMatchesPassword() {
        $result = $this->userObj->verifyCredentials('password');
        $this->assertTrue($result);
    }

    public function testBadPasswordIsInvalid() {
        $result = $this->userObj->verifyCredentials('bad password');
        $this->assertFalse($result);
    }

    /**
     * This test depends, because if we aren't verifying credentials right, this test will fail.
     *
     * @depends testVerifyCredentialsMatchesPassword
     */
    public function testSetNewPasswordSetsNewHash() {
        $this->userObj->setNewPassword('a new password');
        $this->assertTrue(($this->userObj->password != 'a new password'));
        $this->assertTrue($this->userObj->verifyCredentials('a new password'));
        $this->assertTrue($this->userObj->isChanged());
    }

    public function testUnchangedObjectMarkedUnchanged() {
        $this->assertFalse($this->userObj->isChanged());
    }
}