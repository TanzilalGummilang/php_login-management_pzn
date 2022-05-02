<?php

namespace TanzilalGummilang\PHP\LoginManagement\Controller {

  require_once __DIR__ . '/../Helper/helper.php';

  use PHPUnit\Framework\TestCase;
  use TanzilalGummilang\PHP\LoginManagement\Config\Database;
  use TanzilalGummilang\PHP\LoginManagement\Domain\Session;
  use TanzilalGummilang\PHP\LoginManagement\Domain\User;
  use TanzilalGummilang\PHP\LoginManagement\Repository\SessionRepository;
  use TanzilalGummilang\PHP\LoginManagement\Repository\UserRepository;
  use TanzilalGummilang\PHP\LoginManagement\Service\SessionService;


  class UserControllerTest extends TestCase
  {
    private UserController $userController;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    protected function setUp(): void
    {
      $this->userController = new UserController;

      $this->sessionRepository = new SessionRepository(Database::getConnection());
      $this->sessionRepository->deleteAll();

      $this->userRepository = new UserRepository(Database::getConnection());
      $this->userRepository->deleteAll();

      putenv("mode=test");
    }

    // register view test
    public function testRegister()
    {
      $this->userController->register();

      $this->expectOutputRegex("[Register]");
      $this->expectOutputRegex("[Id]");
      $this->expectOutputRegex("[Name]");
      $this->expectOutputRegex("[Password]");
      $this->expectOutputRegex("[Register New User]");
    }

    public function testPostRegisterSuccess()
    {
      $_POST['id'] = "tanzilal";
      $_POST['name'] = "Tanzilal Gummilang";
      $_POST['password'] = "rahasia";

      $this->userController->postRegister();

      // $this->expectOutputString("");
      $this->expectOutputRegex("[Location: /users/login]");
    }

    public function testPostRegisterValidationError()
    {
      $_POST['id'] = "";
      $_POST['name'] = "";
      $_POST['password'] = "";

      $this->userController->postRegister();

      $this->expectOutputRegex("[Register]");
      $this->expectOutputRegex("[Id]");
      $this->expectOutputRegex("[Name]");
      $this->expectOutputRegex("[Password]");
      $this->expectOutputRegex("[Register New User]");
      $this->expectOutputRegex("[Id, Name, Password cannot blank !!]");
    }

    public function testPostRegisterDuplicate()
    {
      $user = new User;
      $user->id = "tanzilal";
      $user->name = "Tanzilal Gummilang";
      $user->password = "rahasia";

      $this->userRepository->save($user);

      $_POST['id'] = "tanzilal";
      $_POST['name'] = "Tanzilal Gummilang";
      $_POST['password'] = "rahasia";

      $this->userController->postRegister();

      $this->expectOutputRegex("[Register]");
      $this->expectOutputRegex("[Id]");
      $this->expectOutputRegex("[Name]");
      $this->expectOutputRegex("[Password]");
      $this->expectOutputRegex("[Register New User]");
      $this->expectOutputRegex("[User Id already exist]");
    }
    // end register view test

    // login view test
    public function testLogin()
    {
      $this->userController->login();

      $this->expectOutputRegex("[Login User]");
      $this->expectOutputRegex("[Id]");
      $this->expectOutputRegex("[Password]");
    }

    public function testLoginSuccess()
    {
      $user = new User;
      $user->id = "tanzilal";
      $user->name = "Tanzilal Gummilang";
      $user->password = password_hash("rahasia", PASSWORD_BCRYPT);

      $this->userRepository->save($user);

      $_POST['id'] = "tanzilal";
      $_POST['password'] = "rahasia";

      $this->userController->postLogin();

      $this->expectOutputRegex("[Location: /]");
      $this->expectOutputRegex("[X-PZN-SESSION: ]");
    }

    public function testLoginValidationError()
    {
      $_POST['id'] = "";
      $_POST['password'] = "";

      $this->userController->postLogin();

      $this->expectOutputRegex("[Login User]");
      $this->expectOutputRegex("[Id or Password cannot blank !!]");
    }

    public function testLoginUserNotFound()
    {
      $_POST['id'] = "notFound";
      $_POST['password'] = "notFound";

      $this->userController->postLogin();

      $this->expectOutputRegex("[Login User]");
      $this->expectOutputRegex("[Id or Password wrong !!]");
    }

    public function testLoginWrongPassword()
    {
      $user = new User;
      $user->id = "tanzilal";
      $user->name = "Tanzilal Gummilang";
      $user->password = password_hash("rahasia", PASSWORD_BCRYPT);

      $this->userRepository->save($user);

      $_POST['id'] = "tanzilal";
      $_POST['password'] = "salahpassword";

      $this->userController->postLogin();

      $this->expectOutputRegex("[Password or Id wrong !!]");
    }
    // end login view test

    // logout test
    public function testLogout()
    {
      $user = new User;
      $user->id = "tanzilal";
      $user->name = "Tanzilal Gummilang";
      $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
      $this->userRepository->save($user);

      $session = new Session;
      $session->id = uniqid();
      $session->userId = $user->id;
      $this->sessionRepository->save($session);

      $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

      $this->userController->logout();

      $this->expectOutputRegex("[Location: /]");
      $this->expectOutputRegex("[X-PZN-SESSION: ]");
    }
    // end logout test

    // update profile test
    public function testUpdateProfile()
    {
      $user = new User;
      $user->id = "tanzilal";
      $user->name = "Tanzilal Gummilang";
      $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
      $this->userRepository->save($user);

      $session = new Session;
      $session->id = uniqid();
      $session->userId = $user->id;
      $this->sessionRepository->save($session);

      $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

      $this->userController->updateProfile();

      $this->expectOutputRegex("[Profile]");
      $this->expectOutputRegex("[Id]");
      $this->expectOutputRegex("[$user->id]");
      $this->expectOutputRegex("[Name]");
      $this->expectOutputRegex("[$user->name]");
      $this->expectOutputRegex("[Update Profile]");
    }

    public function testPostUpdateProfileSuccess()
    {
      $user = new User;
      $user->id = "tanzilal";
      $user->name = "Tanzilal Gummilang";
      $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
      $this->userRepository->save($user);

      $session = new Session;
      $session->id = uniqid();
      $session->userId = $user->id;
      $this->sessionRepository->save($session);

      $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

      $_POST['name'] = "Gilang";
      $this->userController->postUpdateProfile();

      $this->expectOutputRegex("[Location: /]");

      $result = $this->userRepository->findById($user->id);
      $this->assertEquals("Gilang",$result->name);
    }

    public function testPostUpdateProfileValidationError()
    {
      $user = new User;
      $user->id = "tanzilal";
      $user->name = "Tanzilal Gummilang";
      $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
      $this->userRepository->save($user);

      $session = new Session;
      $session->id = uniqid();
      $session->userId = $user->id;
      $this->sessionRepository->save($session);

      $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

      $_POST['name'] = "";
      $this->userController->postUpdateProfile();

      $this->expectOutputRegex("[Profile]");
      $this->expectOutputRegex("[Id]");
      $this->expectOutputRegex("[$user->id]");
      $this->expectOutputRegex("[Name]");
      $this->expectOutputRegex("[$user->name]");
      $this->expectOutputRegex("[Update Profile]");
      $this->expectOutputRegex("[Id or Name cannot blank !!]");
    }
    // end update profile test

    // update password
    public function testUpdatePassword()
    {
      $user = new User;
      $user->id = "tanzilal";
      $user->name = "Tanzilal Gummilang";
      $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
      $this->userRepository->save($user);

      $session = new Session;
      $session->id = uniqid();
      $session->userId = $user->id;
      $this->sessionRepository->save($session);

      $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

      $this->userController->updatePassword();

      $this->expectOutputRegex("[Password]");
      $this->expectOutputRegex("[Id]");
      $this->expectOutputRegex("[$user->id]");
      $this->expectOutputRegex("[Old Password]");
      $this->expectOutputRegex("[New Password]");
    }

    public function testPostUpdatePasswordSuccess()
    {
      $user = new User;
      $user->id = "tanzilal";
      $user->name = "Tanzilal Gummilang";
      $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
      $this->userRepository->save($user);

      $session = new Session;
      $session->id = uniqid();
      $session->userId = $user->id;
      $this->sessionRepository->save($session);

      $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

      $_POST['oldPassword'] = "rahasia";
      $_POST['newPassword'] = "baru";

      $this->userController->postUpdatePassword();

      $this->expectOutputRegex("[Location: /]");

      $result = $this->userRepository->findById($user->id);

      $this->assertTrue(password_verify("baru", $result->password));
    }

    public function testPostUpdatePasswordValidationError()
    {
      $user = new User;
      $user->id = "tanzilal";
      $user->name = "Tanzilal Gummilang";
      $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
      $this->userRepository->save($user);

      $session = new Session;
      $session->id = uniqid();
      $session->userId = $user->id;
      $this->sessionRepository->save($session);

      $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

      $_POST['oldPassword'] = "";
      $_POST['newPassword'] = "";

      $this->userController->postUpdatePassword();

      $this->expectOutputRegex("[Password]");
      $this->expectOutputRegex("[Id]");
      $this->expectOutputRegex("[$user->id]");
      $this->expectOutputRegex("[Old Password]");
      $this->expectOutputRegex("[New Password]");
      $this->expectOutputRegex("[Id, Old Password, New Password cannot blank !!]");
    }

    public function testPostUpdatePasswordWrongOldPassword()
    {
      $user = new User;
      $user->id = "tanzilal";
      $user->name = "Tanzilal Gummilang";
      $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
      $this->userRepository->save($user);

      $session = new Session;
      $session->id = uniqid();
      $session->userId = $user->id;
      $this->sessionRepository->save($session);

      $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

      $_POST['oldPassword'] = "salah";
      $_POST['newPassword'] = "firmino";

      $this->userController->postUpdatePassword();

      $this->expectOutputRegex("[Password]");
      $this->expectOutputRegex("[Id]");
      $this->expectOutputRegex("[$user->id]");
      $this->expectOutputRegex("[Old Password]");
      $this->expectOutputRegex("[New Password]");
      $this->expectOutputRegex("[Old password is wrong]");
    }
    // end update password

  }

}