/// <reference path="../angular-loader.ts" />


/**
 * The home controller 
 * 
 */
@controller("/", "Views/Home.html")    
class HomeController extends Controller {

    /**
     * Name of the user
     */
    name: string

    /**
     * Message from the server
     */
    serverMessage: string

    /**
     * The hello Service
     */
    @wired
    helloService: HelloService

    /**
     * Handle event when user presses submit
     */
    onSubmit() {
        this.helloService.sayHello(this.name)
            .then((result) => this.serverMessage = result.data as string)
    }
    
}