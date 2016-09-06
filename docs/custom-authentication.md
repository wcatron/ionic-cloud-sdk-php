# Custom Authentication

## Quick Start Guide

1. Create your authenticator class that implements `Ionic\Interfaces\CustomAuthenticator`.

2. Create an instance of your authenticator class.
 
 ```$authenticator = new TestAuthenticator();```

3. Create an instance of the `CustomAuthentication` class with your secret and the authenticator.

 ``` $authentication = new CustomAuthentication('foxtrot', $authenticator); ```

4. Finally call process passing the token, redirect URI, and state from the get request.

 ``` $redirect = $authentication->process($_GET['token'], $_GET['redirect_uri'], $_GET['state']); ```

5. Set redirect url header to the returned value.
