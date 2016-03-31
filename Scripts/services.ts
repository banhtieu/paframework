/** generated model for User */
class User {

    email: string

    displayName: string

    password: string

    validated: boolean

    validateToken: string

    id: number
}

/** generated service for HelloService */
@service("helloService")
class HelloService extends RemoteService {

    /**
     * Start by saying hello
     *
     * @param string name name of the user
     * @return string message to that user
     */
    sayHello(name) {
        return this.execute("HelloService/sayHello", [name])
    }
}

/** generated service for UserService */
@service("userService")
class UserService extends RemoteService {

    /**
     * register a User by email and password
     *
     * @param string displayName the display name of the user
     * @param string email
     * @param string password the password
     *
     * @return int id of the User
     */
    register(displayName, email, password) {
        return this.execute("UserService/register", [displayName, email, password])
    }

    /**
     * validate the user token
     * @param int userId the user
     * @param string token the token to validate
     *
     * @throws \Exception if the token is not valid
     */
    validateUser(userId, token) {
        return this.execute("UserService/validateUser", [userId, token])
    }

    /**
     * login using an email and password
     *
     * @param string email email to login
     * @param string password password
     *
     * @return User the User
     * @throws \Exception if login fail
     */
    login(email, password) {
        return this.execute("UserService/login", [email, password])
    }

    /**
     *
     * @return User the current logged in user
     */
    getCurrentUser() {
        return this.execute("UserService/getCurrentUser", [])
    }
}

