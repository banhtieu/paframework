/// <reference path="../typings/tsd.d.ts" />

window["Controller"] = function () { }
window["Component"] = function () { };


/// base class for all controller
declare class Controller {
    public controllerName: string
    public injectModules: string[]
    public name: string

    prototype: any

    public onLoad()


    [index: string]: any

    $apply(): any
    $apply(exp: string): any
    $apply(exp: (scope: ng.IScope) => any): any

    $applyAsync(): any
    $applyAsync(exp: string): any
    $applyAsync(exp: (scope: ng.IScope) => any): any

    /**
        * Dispatches an event name downwards to all child scopes (and their children) notifying the registered $rootScope.Scope listeners.
        *
        * The event life cycle starts at the scope on which $broadcast was called. All listeners listening for name event on this scope get notified. Afterwards, the event propagates to all direct and indirect scopes of the current scope and calls all registered listeners along the way. The event cannot be canceled.
        *
        * Any exception emitted from the listeners will be passed onto the $exceptionHandler service.
        *
        * @param name Event name to broadcast.
        * @param args Optional one or more arguments which will be passed onto the event listeners.
        */
    $broadcast(name: string, ...args: any[]): ng.IAngularEvent
    $destroy(): void
    $digest(): void
    /**
        * Dispatches an event name upwards through the scope hierarchy notifying the registered $rootScope.Scope listeners.
        *
        * The event life cycle starts at the scope on which $emit was called. All listeners listening for name event on this scope get notified. Afterwards, the event traverses upwards toward the root scope and calls all registered listeners along the way. The event will stop propagating if one of the listeners cancels it.
        *
        * Any exception emitted from the listeners will be passed onto the $exceptionHandler service.
        *
        * @param name Event name to emit.
        * @param args Optional one or more arguments which will be passed onto the event listeners.
        */
    $emit(name: string, ...args: any[]): ng.IAngularEvent

    $eval(): any
    $eval(expression: string, locals?: Object): any
    $eval(expression: (scope: ng.IScope) => any, locals?: Object): any

    $evalAsync(): void
    $evalAsync(expression: string): void
    $evalAsync(expression: (scope: ng.IScope) => any): void

    // Defaults to false by the implementation checking strategy
    $new(isolate?: boolean, parent?: ng.IScope): ng.IScope

    /**
        * Listens on events of a given type. See $emit for discussion of event life cycle.
        *
        * The event listener function format is: function(event, args...).
        *
        * @param name Event name to listen on.
        * @param listener Function to call when the event is emitted.
        */
    $on(name: string, listener: (event: ng.IAngularEvent, ...args: any[]) => any): () => void

    $watch(watchExpression: string, listener?: string, objectEquality?: boolean): () => void
    $watch<T>(watchExpression: string, listener?: (newValue: T, oldValue: T, scope: ng.IScope) => any, objectEquality?: boolean): () => void
    $watch(watchExpression: (scope: ng.IScope) => any, listener?: string, objectEquality?: boolean): () => void
    $watch<T>(watchExpression: (scope: ng.IScope) => T, listener?: (newValue: T, oldValue: T, scope: ng.IScope)
        => any, objectEquality?: boolean): () => void

    $watchCollection<T>(watchExpression: string, listener: (newValue: T, oldValue: T, scope: ng.IScope) => any):
        () => void
    $watchCollection<T>(watchExpression: (scope: ng.IScope) => T, listener: (newValue: T, oldValue: T, scope: ng.IScope) => any):
        () => void

    $watchGroup(watchExpressions: any[], listener: (newValue: any, oldValue: any, scope: ng.IScope) => any):
        () => void
    $watchGroup(watchExpressions: { (scope: ng.IScope): any }[], listener: (newValue: any, oldValue: any, scope: ng.IScope)
        => any): () => void

    $parent: ng.IScope
    $root: ng.IRootScopeService
    $id: number

    // Hidden members
    $$isolateBindings: any
    $$phase: any
}

var BindingFlag = {
    OneWay : "@",
    TwoWay : "=",
    Event : "@",
}

/**
 * component class
 */
class Component extends Controller {

    /**
     * the scope
     * @type {{}}
     */
    scope: any

    /**
     * link the component
     * @param element the html element
     * @param attributes list of attributes
     */
    link(element: HTMLElement, attributes: any) {}
}


/**
 * decorator for inject module
 * @param controller
 * @param propertyKey
 */
function wired(controller: any, propertyKey: string) {
    controller.injectModules = controller.injectModules || []
    controller.injectModules.push(propertyKey)
}


/**
 * Define an angular application with dependencies
 * @param dependencies
 * @returns {IModule}
 */
function createApp(dependencies: string[] = []) {
    angular.module("Controllers", [])
    angular.module("services", [])

    dependencies.push("Controllers")
    dependencies.push("services")

    var module = angular.module("application", dependencies)
    module.config(function ($locationProvider: ng.ILocationProvider,
                            $routeProvider: ng.route.IRouteProvider) {
        $locationProvider.html5Mode(true)

        $routeProvider.otherwise({
            redirectTo: "/"
        })
    })

    return module
}

/**
 * The controller decorator to decorate a controller
 * @param path path to this controller
 * @param templateUrl the url to the view
 */
function controller(path: string = null, templateUrl: string = null) {
    return function (target: any) {
        var controllerModule = angular.module("Controllers")
        var controllerName = target.name
        var controller = new target() as Controller
        controller.injectModules = controller.injectModules || []

        var controllerFunction = function () {

            var $scope = arguments[0] as Controller

            // copy properties
            for (var property in controller) {
                $scope[property] = controller[property]
            }

            // copy injected modules
            for (var i = 0; i < controller.injectModules.length; i++) {
                var moduleName = controller.injectModules[i]
                $scope[moduleName] = arguments[i + 1]
            }

            if ($scope.onLoad) {
                $scope.onLoad()
            }
        }

        controllerFunction.$inject = ["$scope"].concat(controller.injectModules)

        controllerModule.controller(controllerName, controllerFunction)

        if (path != null) {
            var applicationModule = angular.module("application")
            applicationModule.config(function ($routeProvider:ng.route.IRouteProvider) {
                $routeProvider.when(path, {
                    controller: controllerName,
                    templateUrl: templateUrl
                })
            })
        }
    }
}


/**
 * Bind an attribute 
 * @param flag
 */
function attribute(flag: string = BindingFlag.OneWay) {
    return function (target: Component, propertyKey: string) {
        target.scope = target.scope || {}
        target.scope[propertyKey] = flag
    }
}

/**
 * The controller decorator to decorate a controller
 * @param tag tag of the component
 * @param templateUrl the url to the view
 */
function component(tag: string, templateUrl: string) {
    return function (target: any) {

        var controllerModule = angular.module("Controllers")
        var component = new target() as Component

        component.injectModules = component.injectModules || []

        
        var controllerFunction = function () {
            var $scope = arguments[0] as Component

            // copy properties
            for (var property in component) {
                $scope[property] = component[property]
            }

            // copy injected modules
            for (var i = 0; i < component.injectModules.length; i++) {
                var moduleName = component.injectModules[i]
                $scope[moduleName] = arguments[i + 1]
            }

            if ($scope.onLoad) {
                $scope.onLoad()
            }
        }

        console.log(component.scope)

        controllerFunction.$inject = ["$scope"].concat(component.injectModules)

        var componentFunction = function () {

            return {
                templateUrl: templateUrl,
                restrict: 'EA',
                scope: component.scope || {},
                link: function ($scope, element, attrs) {
                    if ($scope["link"]) {
                        $scope.link(element, attrs)
                    }
                },
                controller: controllerFunction
            }
        }

        controllerModule.directive(tag, componentFunction)
    }
}


/**
 * Define a service
 */
function service(name: string) {
    return function (target: any) {
        var serviceModule = angular.module("services")
        var serviceName = name
        var service = new target()
        service.injectModules = service.injectModules || []

        var serviceFunction = function () {
            // copy injected modules
            for (var i = 0; i < service.injectModules.length; i++) {
                var moduleName = service.injectModules[i]
                service[moduleName] = arguments[i]
            }

            return service
        }

        serviceFunction.$inject = service.injectModules

        serviceModule.factory(serviceName, serviceFunction)
    }
}


/**
 * The remote service based for all remote service
 * object
 */
class RemoteService {

    @wired
    $http: ng.IHttpService

    /**
     * Handle the error
     */
    errorHandler: (error: any) => void

    /**
     * the configure end point
     */
    static endPoint: string = "gateway.php/"

    /**
     * Execute a request
     * @param path
     * @param params
     * @param success handle successful
     * @returns {IHttpPromise<T>}
     */
    execute<T>(path: string, params: any[] = [], success: (T) => void = null): ng.IHttpPromise<T> {
        var promise = this.$http.post(RemoteService.endPoint + path, params)

        if (this.errorHandler) {
            promise.catch((error) => {
                this.errorHandler(error.data)
            })
        }

        if (success) {
            promise.then(function (result) {
                success(result.data)
            })
        }

        return promise
    }

    /**
     *
     * @param path
     * @param flow
     * @param success
     * @returns {ng.IPromise<T>}
     */
    uploadFile<T>(path: string, flow: flowjs.IFlow, success: (T) => void = null): ng.IPromise<T> {

        flow.opts["testChunks"] = false
        flow.opts["target"] = RemoteService.endPoint + path

        
        flow.upload()

        return null
    }
}