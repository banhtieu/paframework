<?php

namespace Application\Service;


use Application\Model\User;
use Application\Validator\UserValidator;
use Core\Database\Collection;
use Core\Helper\Session;

/**
 * The User service
 * @package Application\Service
 */
class UserService {

    /**
     * the key to store current user in session
     */
    const currentUser = "currentUser";

    /**
     * @var Collection the User collection
     */
    private $userCollection;

    /**
     * @var UserValidator validator to validate User
     */
    private $userValidator;

    /**
     * UserService constructor.
     * @param Collection $userCollection User collection to access User
     * @param UserValidator $userValidator validate to validate User
     */
    public function __construct(Collection $userCollection, UserValidator $userValidator) {
        $this->userCollection = $userCollection;
        $this->userValidator = $userValidator;
    }


    /**
     * register a User by email and password
     *
     * @param string $displayName the display name of the user
     * @param string $email
     * @param string $password the password
     *
     * @return int id of the User
     */
    public function register($displayName, $email, $password) {

        $user = new User();

        $user->displayName = $displayName;
        $user->email = $email;
        $user->password = $password;

        // generate a validate token
        $user->validateToken = uniqid();
        $this->userValidator->validateUser($user);

        // encrypt password
        $user->password = md5($password);
        $this->userCollection->save($user);

        Session::set(self::currentUser, $user->id);

        return $user->id;
    }


    /**
     * validate the user token
     * @param int $userId the user
     * @param string $token the token to validate
     *
     * @throws \Exception if the token is not valid
     */
    public function validateUser($userId, $token) {
        /** @var User $user */
        $user = $this->userCollection->buildQuery()
                    ->findBy("id", $userId)
                    ->findBy("validateToken", $token)
                    ->first();

        if ($user) {
            $user->validated = true;
            $this->userCollection->save($user);
        } else {
            throw new \Exception("Invalid token.. please try again.");
        }

    }


    /**
     * login using an email and password
     *
     * @param string $email email to login
     * @param string $password password
     *
     * @return User the User
     * @throws \Exception if login fail
     */
    public function login($email, $password) {
        /** @var User $user */
        $user = $this->userCollection->buildQuery()
                    ->findBy("email", $email)
                    ->findBy("password", md5($password))
                    ->first();

        if ($user == null) {
            throw new \Exception("Email or password is not valid!");
        }

        Session::set(self::currentUser, $user->id);
        
        return $user;
    }


    /**
     *
     * @return User the current logged in user
     */
    public function getCurrentUser() {
        /** @var User $user */
        $user = $this->userCollection->buildQuery()
                    ->findBy("id", intval(Session::get(self::currentUser)))
                    ->first();

        return $user;
    }
}