<?php
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Ionic\Helpers\CustomAuthentication;
use Ionic\Helpers\CustomAuthenticationException;
use Ionic\Interfaces\CustomAuthenticator;

/**
 * Created by PhpStorm.
 * User: westoncatron
 * Date: 9/6/16
 * Time: 1:06 PM
 */

/**
 * Class TestAuthenticator
 * @covers CustomAuthenticator
 */
class TestAuthenticator implements CustomAuthenticator {
    function authenticate($data) {
        $credentials = [
            "username" => "dan",
            "password" => "123"
        ];
        if ($data['username'] != $credentials["username"]) {
            throw new CustomAuthenticationException("Invalid Credentials");
        }
        if ($data['password'] != $credentials["password"]) {
            throw new CustomAuthenticationException("Invalid Credentials");
        }
        return true;
    }
}

class CustomAuthenticationTest extends PHPUnit_Framework_TestCase {
    function testProcess() {
        $token = JWT::encode(
            [
                "app_id" => "test-id",
                "data"   => [
                    "username" => "dan",
                    "password" => "123"
                ],
                "exp"    => time() + 30
            ], 'foxtrot');

        $_GET = [
            'token'        => $token,
            'redirect_uri' => 'https%3A%2F%2Fapi.ionic.io%2Fauth%2Fintegrations%2Fcustom%3Fapp_id%3DFAKEID',
            'state'        => '3bfab190a93c1d95a081bf9b64924564'
        ];

        $authenticator = new TestAuthenticator();
        $authentication = new CustomAuthentication('foxtrot', $authenticator);

        try {
            $data = $authentication->process($_GET['token'], $_GET['redirect_uri'], $_GET['state']);
        } catch (CustomAuthenticationException $e) {
            $this->assertFalse(true, "Should not throw exception.");
        }

        $this->assertTrue($data == "https%3A%2F%2Fapi.ionic.io%2Fauth%2Fintegrations%2Fcustom%3Fapp_id%3DFAKEID&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjp0cnVlfQ.jb3IdjafXwitGHxp6NrcYK_mGIB4fTHW_-bZYbiP3tQ&state=3bfab190a93c1d95a081bf9b64924564");
    }

    function testProcessAuthenticationExceptionCredentials() {
        $token = JWT::encode(
            [
                "app_id" => "test-id",
                "data"   => [
                    "username" => "dan",
                    "password" => "wrong"
                ],
                "exp"    => time() + 30
            ], 'foxtrot');

        $_GET = [
            'token'        => $token,
            'redirect_uri' => 'https%3A%2F%2Fapi.ionic.io%2Fauth%2Fintegrations%2Fcustom%3Fapp_id%3DFAKEID',
            'state'        => '3bfab190a93c1d95a081bf9b64924564'
        ];

        $authenticator = new TestAuthenticator();
        $authentication = new CustomAuthentication('foxtrot', $authenticator);

        $this->setExpectedException(CustomAuthenticationException::class);
        $data = $authentication->process($_GET['token'], $_GET['redirect_uri'], $_GET['state']);

        $this->assertTrue($data == "https%3A%2F%2Fapi.ionic.io%2Fauth%2Fintegrations%2Fcustom%3Fapp_id%3DFAKEID&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjp0cnVlfQ.jb3IdjafXwitGHxp6NrcYK_mGIB4fTHW_-bZYbiP3tQ&state=3bfab190a93c1d95a081bf9b64924564");
    }
    function testProcessInvalidTimeException() {
        $token = JWT::encode(
            [
                "app_id" => "test-id",
                "data"   => [
                    "username" => "dan",
                    "password" => "wrong"
                ],
                "exp"    => time() - 30
            ], 'foxtrot');

        $_GET = [
            'token'        => $token,
            'redirect_uri' => 'https%3A%2F%2Fapi.ionic.io%2Fauth%2Fintegrations%2Fcustom%3Fapp_id%3DFAKEID',
            'state'        => '3bfab190a93c1d95a081bf9b64924564'
        ];

        $authenticator = new TestAuthenticator();
        $authentication = new CustomAuthentication('foxtrot', $authenticator);

        $this->setExpectedException(ExpiredException::class);
        $data = $authentication->process($_GET['token'], $_GET['redirect_uri'], $_GET['state']);

        $this->assertTrue($data == "https%3A%2F%2Fapi.ionic.io%2Fauth%2Fintegrations%2Fcustom%3Fapp_id%3DFAKEID&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjp0cnVlfQ.jb3IdjafXwitGHxp6NrcYK_mGIB4fTHW_-bZYbiP3tQ&state=3bfab190a93c1d95a081bf9b64924564");
    }
}
