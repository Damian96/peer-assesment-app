
window.projectVersion = 'master';

(function(root) {

    var bhIndex = null;
    var rootPath = '';
    var treeHtml = '        <ul>                <li data-name="namespace:App" class="opened">                    <div style="padding-left:0px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="App.html">App</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:App_Console" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="App/Console.html">Console</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:App_Console_Kernel" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="App/Console/Kernel.html">Kernel</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:App_Exceptions" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="App/Exceptions.html">Exceptions</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:App_Exceptions_Handler" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="App/Exceptions/Handler.html">Handler</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:App_Http" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="App/Http.html">Http</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:App_Http_Controllers" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="App/Http/Controllers.html">Controllers</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:App_Http_Controllers_ApiController" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Controllers/ApiController.html">ApiController</a>                    </div>                </li>                            <li data-name="class:App_Http_Controllers_Controller" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Controllers/Controller.html">Controller</a>                    </div>                </li>                            <li data-name="class:App_Http_Controllers_CourseController" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Controllers/CourseController.html">CourseController</a>                    </div>                </li>                            <li data-name="class:App_Http_Controllers_FormController" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Controllers/FormController.html">FormController</a>                    </div>                </li>                            <li data-name="class:App_Http_Controllers_GroupController" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Controllers/GroupController.html">GroupController</a>                    </div>                </li>                            <li data-name="class:App_Http_Controllers_SessionController" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Controllers/SessionController.html">SessionController</a>                    </div>                </li>                            <li data-name="class:App_Http_Controllers_UserController" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Controllers/UserController.html">UserController</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:App_Http_Middleware" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="App/Http/Middleware.html">Middleware</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:App_Http_Middleware_Authenticate" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Middleware/Authenticate.html">Authenticate</a>                    </div>                </li>                            <li data-name="class:App_Http_Middleware_AuthenticateAPI" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Middleware/AuthenticateAPI.html">AuthenticateAPI</a>                    </div>                </li>                            <li data-name="class:App_Http_Middleware_CheckForMaintenanceMode" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Middleware/CheckForMaintenanceMode.html">CheckForMaintenanceMode</a>                    </div>                </li>                            <li data-name="class:App_Http_Middleware_CheckRole" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Middleware/CheckRole.html">CheckRole</a>                    </div>                </li>                            <li data-name="class:App_Http_Middleware_EncryptCookies" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Middleware/EncryptCookies.html">EncryptCookies</a>                    </div>                </li>                            <li data-name="class:App_Http_Middleware_EnsureEmailIsVerified" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Middleware/EnsureEmailIsVerified.html">EnsureEmailIsVerified</a>                    </div>                </li>                            <li data-name="class:App_Http_Middleware_RedirectIfAuthenticated" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Middleware/RedirectIfAuthenticated.html">RedirectIfAuthenticated</a>                    </div>                </li>                            <li data-name="class:App_Http_Middleware_ResponseCacheMiddleware" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Middleware/ResponseCacheMiddleware.html">ResponseCacheMiddleware</a>                    </div>                </li>                            <li data-name="class:App_Http_Middleware_TrimStrings" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Middleware/TrimStrings.html">TrimStrings</a>                    </div>                </li>                            <li data-name="class:App_Http_Middleware_TrustProxies" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Middleware/TrustProxies.html">TrustProxies</a>                    </div>                </li>                            <li data-name="class:App_Http_Middleware_VerifyCsrfToken" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Middleware/VerifyCsrfToken.html">VerifyCsrfToken</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:App_Http_Requests" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="App/Http/Requests.html">Requests</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:App_Http_Requests_PaginationRequest" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Requests/PaginationRequest.html">PaginationRequest</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:App_Http_Resources" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="App/Http/Resources.html">Resources</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:App_Http_Resources_Group" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Resources/Group.html">Group</a>                    </div>                </li>                            <li data-name="class:App_Http_Resources_GroupCollection" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Resources/GroupCollection.html">GroupCollection</a>                    </div>                </li>                            <li data-name="class:App_Http_Resources_Session" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Resources/Session.html">Session</a>                    </div>                </li>                            <li data-name="class:App_Http_Resources_SessionCollection" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="App/Http/Resources/SessionCollection.html">SessionCollection</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="class:App_Http_Kernel" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="App/Http/Kernel.html">Kernel</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:App_Listeners" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="App/Listeners.html">Listeners</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:App_Listeners_LogSuccessfulLogin" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="App/Listeners/LogSuccessfulLogin.html">LogSuccessfulLogin</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:App_Notifications" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="App/Notifications.html">Notifications</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:App_Notifications_AppResetPasswordEmail" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="App/Notifications/AppResetPasswordEmail.html">AppResetPasswordEmail</a>                    </div>                </li>                            <li data-name="class:App_Notifications_AppVerifyEmail" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="App/Notifications/AppVerifyEmail.html">AppVerifyEmail</a>                    </div>                </li>                            <li data-name="class:App_Notifications_SessionStartEmail" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="App/Notifications/SessionStartEmail.html">SessionStartEmail</a>                    </div>                </li>                            <li data-name="class:App_Notifications_StudentEnrollEmail" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="App/Notifications/StudentEnrollEmail.html">StudentEnrollEmail</a>                    </div>                </li>                            <li data-name="class:App_Notifications_StudentInviteEmail" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="App/Notifications/StudentInviteEmail.html">StudentInviteEmail</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:App_Providers" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="App/Providers.html">Providers</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:App_Providers_AppServiceProvider" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="App/Providers/AppServiceProvider.html">AppServiceProvider</a>                    </div>                </li>                            <li data-name="class:App_Providers_AuthServiceProvider" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="App/Providers/AuthServiceProvider.html">AuthServiceProvider</a>                    </div>                </li>                            <li data-name="class:App_Providers_BroadcastServiceProvider" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="App/Providers/BroadcastServiceProvider.html">BroadcastServiceProvider</a>                    </div>                </li>                            <li data-name="class:App_Providers_EventServiceProvider" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="App/Providers/EventServiceProvider.html">EventServiceProvider</a>                    </div>                </li>                            <li data-name="class:App_Providers_RouteServiceProvider" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="App/Providers/RouteServiceProvider.html">RouteServiceProvider</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:App_Rules" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="App/Rules.html">Rules</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:App_Rules_DateCompare" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="App/Rules/DateCompare.html">DateCompare</a>                    </div>                </li>                            <li data-name="class:App_Rules_FQDN" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="App/Rules/FQDN.html">FQDN</a>                    </div>                </li>                            <li data-name="class:App_Rules_PrependedEmailExists" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="App/Rules/PrependedEmailExists.html">PrependedEmailExists</a>                    </div>                </li>                            <li data-name="class:App_Rules_UniqueCombo" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="App/Rules/UniqueCombo.html">UniqueCombo</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="class:App_Course" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="App/Course.html">Course</a>                    </div>                </li>                            <li data-name="class:App_Form" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="App/Form.html">Form</a>                    </div>                </li>                            <li data-name="class:App_FormTemplate" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="App/FormTemplate.html">FormTemplate</a>                    </div>                </li>                            <li data-name="class:App_Group" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="App/Group.html">Group</a>                    </div>                </li>                            <li data-name="class:App_Question" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="App/Question.html">Question</a>                    </div>                </li>                            <li data-name="class:App_Review" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="App/Review.html">Review</a>                    </div>                </li>                            <li data-name="class:App_Session" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="App/Session.html">Session</a>                    </div>                </li>                            <li data-name="class:App_StudentCourse" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="App/StudentCourse.html">StudentCourse</a>                    </div>                </li>                            <li data-name="class:App_StudentGroup" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="App/StudentGroup.html">StudentGroup</a>                    </div>                </li>                            <li data-name="class:App_StudentSession" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="App/StudentSession.html">StudentSession</a>                    </div>                </li>                            <li data-name="class:App_User" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="App/User.html">User</a>                    </div>                </li>                </ul></div>                </li>                </ul>';

    var searchTypeClasses = {
        'Namespace': 'label-default',
        'Class': 'label-info',
        'Interface': 'label-primary',
        'Trait': 'label-success',
        'Method': 'label-danger',
        '_': 'label-warning'
    };

    var searchIndex = [
                    
            {"type": "Namespace", "link": "App.html", "name": "App", "doc": "Namespace App"},{"type": "Namespace", "link": "App/Console.html", "name": "App\\Console", "doc": "Namespace App\\Console"},{"type": "Namespace", "link": "App/Exceptions.html", "name": "App\\Exceptions", "doc": "Namespace App\\Exceptions"},{"type": "Namespace", "link": "App/Http.html", "name": "App\\Http", "doc": "Namespace App\\Http"},{"type": "Namespace", "link": "App/Http/Controllers.html", "name": "App\\Http\\Controllers", "doc": "Namespace App\\Http\\Controllers"},{"type": "Namespace", "link": "App/Http/Middleware.html", "name": "App\\Http\\Middleware", "doc": "Namespace App\\Http\\Middleware"},{"type": "Namespace", "link": "App/Http/Requests.html", "name": "App\\Http\\Requests", "doc": "Namespace App\\Http\\Requests"},{"type": "Namespace", "link": "App/Http/Resources.html", "name": "App\\Http\\Resources", "doc": "Namespace App\\Http\\Resources"},{"type": "Namespace", "link": "App/Listeners.html", "name": "App\\Listeners", "doc": "Namespace App\\Listeners"},{"type": "Namespace", "link": "App/Notifications.html", "name": "App\\Notifications", "doc": "Namespace App\\Notifications"},{"type": "Namespace", "link": "App/Providers.html", "name": "App\\Providers", "doc": "Namespace App\\Providers"},{"type": "Namespace", "link": "App/Rules.html", "name": "App\\Rules", "doc": "Namespace App\\Rules"},
            
            {"type": "Class", "fromName": "App\\Console", "fromLink": "App/Console.html", "link": "App/Console/Kernel.html", "name": "App\\Console\\Kernel", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Console\\Kernel", "fromLink": "App/Console/Kernel.html", "link": "App/Console/Kernel.html#method_schedule", "name": "App\\Console\\Kernel::schedule", "doc": "&quot;Define the application&#039;s command schedule.&quot;"},
                    {"type": "Method", "fromName": "App\\Console\\Kernel", "fromLink": "App/Console/Kernel.html", "link": "App/Console/Kernel.html#method_commands", "name": "App\\Console\\Kernel::commands", "doc": "&quot;Register the commands for the application.&quot;"},
            
            {"type": "Class", "fromName": "App", "fromLink": "App.html", "link": "App/Course.html", "name": "App\\Course", "doc": "&quot;Class User&quot;"},
                                                        {"type": "Method", "fromName": "App\\Course", "fromLink": "App/Course.html", "link": "App/Course.html#method___get", "name": "App\\Course::__get", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Course", "fromLink": "App/Course.html", "link": "App/Course.html#method___set", "name": "App\\Course::__set", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Course", "fromLink": "App/Course.html", "link": "App/Course.html#method_boot", "name": "App\\Course::boot", "doc": "&quot;The \&quot;booting\&quot; method of the model.&quot;"},
                    {"type": "Method", "fromName": "App\\Course", "fromLink": "App/Course.html", "link": "App/Course.html#method_user", "name": "App\\Course::user", "doc": "&quot;Get the user that owns the course.&quot;"},
                    {"type": "Method", "fromName": "App\\Course", "fromLink": "App/Course.html", "link": "App/Course.html#method_instructor", "name": "App\\Course::instructor", "doc": "&quot;Alias of self::user.&quot;"},
                    {"type": "Method", "fromName": "App\\Course", "fromLink": "App/Course.html", "link": "App/Course.html#method_sessions", "name": "App\\Course::sessions", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Course", "fromLink": "App/Course.html", "link": "App/Course.html#method_students", "name": "App\\Course::students", "doc": "&quot;Retrieve the courses that the users is registered on&quot;"},
                    {"type": "Method", "fromName": "App\\Course", "fromLink": "App/Course.html", "link": "App/Course.html#method_getByInstructor", "name": "App\\Course::getByInstructor", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Course", "fromLink": "App/Course.html", "link": "App/Course.html#method_copyToCurrentYear", "name": "App\\Course::copyToCurrentYear", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Course", "fromLink": "App/Course.html", "link": "App/Course.html#method_copied", "name": "App\\Course::copied", "doc": "&quot;Returns whether the course has been already copied to the current academic year&quot;"},
                    {"type": "Method", "fromName": "App\\Course", "fromLink": "App/Course.html", "link": "App/Course.html#method_getCurrentYears", "name": "App\\Course::getCurrentYears", "doc": "&quot;Retrieve the courses of the current academic year.&quot;"},
                    {"type": "Method", "fromName": "App\\Course", "fromLink": "App/Course.html", "link": "App/Course.html#method_toAcademicYear", "name": "App\\Course::toAcademicYear", "doc": "&quot;Format timestamp to a proper &#039;ac_year&#039; [SE-YYYY]&quot;"},
                    {"type": "Method", "fromName": "App\\Course", "fromLink": "App/Course.html", "link": "App/Course.html#method_acYearToTimestamp", "name": "App\\Course::acYearToTimestamp", "doc": "&quot;Format the given ac_year [SE-YYYY] to a UNIX timestamp&quot;"},
                    {"type": "Method", "fromName": "App\\Course", "fromLink": "App/Course.html", "link": "App/Course.html#method_toAcademicYearPair", "name": "App\\Course::toAcademicYearPair", "doc": "&quot;Format the given UNIX timestamp to an academic year pair [YYYY-YY]&quot;"},
            
            {"type": "Class", "fromName": "App\\Exceptions", "fromLink": "App/Exceptions.html", "link": "App/Exceptions/Handler.html", "name": "App\\Exceptions\\Handler", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Exceptions\\Handler", "fromLink": "App/Exceptions/Handler.html", "link": "App/Exceptions/Handler.html#method_shouldReport", "name": "App\\Exceptions\\Handler::shouldReport", "doc": "&quot;Determine if the exception should be reported.&quot;"},
                    {"type": "Method", "fromName": "App\\Exceptions\\Handler", "fromLink": "App/Exceptions/Handler.html", "link": "App/Exceptions/Handler.html#method_report", "name": "App\\Exceptions\\Handler::report", "doc": "&quot;Report or log an exception.&quot;"},
                    {"type": "Method", "fromName": "App\\Exceptions\\Handler", "fromLink": "App/Exceptions/Handler.html", "link": "App/Exceptions/Handler.html#method_render", "name": "App\\Exceptions\\Handler::render", "doc": "&quot;Render an exception into an HTTP response.&quot;"},
            
            {"type": "Class", "fromName": "App", "fromLink": "App.html", "link": "App/Form.html", "name": "App\\Form", "doc": "&quot;App\\Form&quot;"},
                                                        {"type": "Method", "fromName": "App\\Form", "fromLink": "App/Form.html", "link": "App/Form.html#method_session", "name": "App\\Form::session", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Form", "fromLink": "App/Form.html", "link": "App/Form.html#method_questions", "name": "App\\Form::questions", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Form", "fromLink": "App/Form.html", "link": "App/Form.html#method_find", "name": "App\\Form::find", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "App", "fromLink": "App.html", "link": "App/FormTemplate.html", "name": "App\\FormTemplate", "doc": "&quot;App\\FormTemplate&quot;"},
                                                        {"type": "Method", "fromName": "App\\FormTemplate", "fromLink": "App/FormTemplate.html", "link": "App/FormTemplate.html#method_boot", "name": "App\\FormTemplate::boot", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\FormTemplate", "fromLink": "App/FormTemplate.html", "link": "App/FormTemplate.html#method_questions", "name": "App\\FormTemplate::questions", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "App", "fromLink": "App.html", "link": "App/Group.html", "name": "App\\Group", "doc": "&quot;App\\Group&quot;"},
                                                        {"type": "Method", "fromName": "App\\Group", "fromLink": "App/Group.html", "link": "App/Group.html#method___get", "name": "App\\Group::__get", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Group", "fromLink": "App/Group.html", "link": "App/Group.html#method_session", "name": "App\\Group::session", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Group", "fromLink": "App/Group.html", "link": "App/Group.html#method_students", "name": "App\\Group::students", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Group", "fromLink": "App/Group.html", "link": "App/Group.html#method_marks", "name": "App\\Group::marks", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "App\\Http\\Controllers", "fromLink": "App/Http/Controllers.html", "link": "App/Http/Controllers/ApiController.html", "name": "App\\Http\\Controllers\\ApiController", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Http\\Controllers\\ApiController", "fromLink": "App/Http/Controllers/ApiController.html", "link": "App/Http/Controllers/ApiController.html#method___construct", "name": "App\\Http\\Controllers\\ApiController::__construct", "doc": "&quot;ApiController constructor.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\ApiController", "fromLink": "App/Http/Controllers/ApiController.html", "link": "App/Http/Controllers/ApiController.html#method_rules", "name": "App\\Http\\Controllers\\ApiController::rules", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\ApiController", "fromLink": "App/Http/Controllers/ApiController.html", "link": "App/Http/Controllers/ApiController.html#method_login", "name": "App\\Http\\Controllers\\ApiController::login", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "App\\Http\\Controllers", "fromLink": "App/Http/Controllers.html", "link": "App/Http/Controllers/Controller.html", "name": "App\\Http\\Controllers\\Controller", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Http\\Controllers\\Controller", "fromLink": "App/Http/Controllers/Controller.html", "link": "App/Http/Controllers/Controller.html#method_updateDotEnv", "name": "App\\Http\\Controllers\\Controller::updateDotEnv", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "App\\Http\\Controllers", "fromLink": "App/Http/Controllers.html", "link": "App/Http/Controllers/CourseController.html", "name": "App\\Http\\Controllers\\CourseController", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Http\\Controllers\\CourseController", "fromLink": "App/Http/Controllers/CourseController.html", "link": "App/Http/Controllers/CourseController.html#method___construct", "name": "App\\Http\\Controllers\\CourseController::__construct", "doc": "&quot;Create a new controller instance.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\CourseController", "fromLink": "App/Http/Controllers/CourseController.html", "link": "App/Http/Controllers/CourseController.html#method_index", "name": "App\\Http\\Controllers\\CourseController::index", "doc": "&quot;Display a listing of the resource.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\CourseController", "fromLink": "App/Http/Controllers/CourseController.html", "link": "App/Http/Controllers/CourseController.html#method_create", "name": "App\\Http\\Controllers\\CourseController::create", "doc": "&quot;Show the form for creating a new resource.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\CourseController", "fromLink": "App/Http/Controllers/CourseController.html", "link": "App/Http/Controllers/CourseController.html#method_store", "name": "App\\Http\\Controllers\\CourseController::store", "doc": "&quot;Store a newly created resource in storage.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\CourseController", "fromLink": "App/Http/Controllers/CourseController.html", "link": "App/Http/Controllers/CourseController.html#method_show", "name": "App\\Http\\Controllers\\CourseController::show", "doc": "&quot;Display the specified resource.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\CourseController", "fromLink": "App/Http/Controllers/CourseController.html", "link": "App/Http/Controllers/CourseController.html#method_edit", "name": "App\\Http\\Controllers\\CourseController::edit", "doc": "&quot;Show the form for editing the specified resource.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\CourseController", "fromLink": "App/Http/Controllers/CourseController.html", "link": "App/Http/Controllers/CourseController.html#method_update", "name": "App\\Http\\Controllers\\CourseController::update", "doc": "&quot;Update the specified resource in storage.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\CourseController", "fromLink": "App/Http/Controllers/CourseController.html", "link": "App/Http/Controllers/CourseController.html#method_copy", "name": "App\\Http\\Controllers\\CourseController::copy", "doc": "&quot;Copy the specified Course to the current academic year.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\CourseController", "fromLink": "App/Http/Controllers/CourseController.html", "link": "App/Http/Controllers/CourseController.html#method_destroy", "name": "App\\Http\\Controllers\\CourseController::destroy", "doc": "&quot;Remove the specified resource from storage.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\CourseController", "fromLink": "App/Http/Controllers/CourseController.html", "link": "App/Http/Controllers/CourseController.html#method_students", "name": "App\\Http\\Controllers\\CourseController::students", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\CourseController", "fromLink": "App/Http/Controllers/CourseController.html", "link": "App/Http/Controllers/CourseController.html#method_disenroll", "name": "App\\Http\\Controllers\\CourseController::disenroll", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "App\\Http\\Controllers", "fromLink": "App/Http/Controllers.html", "link": "App/Http/Controllers/FormController.html", "name": "App\\Http\\Controllers\\FormController", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Http\\Controllers\\FormController", "fromLink": "App/Http/Controllers/FormController.html", "link": "App/Http/Controllers/FormController.html#method___construct", "name": "App\\Http\\Controllers\\FormController::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\FormController", "fromLink": "App/Http/Controllers/FormController.html", "link": "App/Http/Controllers/FormController.html#method_rules", "name": "App\\Http\\Controllers\\FormController::rules", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\FormController", "fromLink": "App/Http/Controllers/FormController.html", "link": "App/Http/Controllers/FormController.html#method_messages", "name": "App\\Http\\Controllers\\FormController::messages", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\FormController", "fromLink": "App/Http/Controllers/FormController.html", "link": "App/Http/Controllers/FormController.html#method_create", "name": "App\\Http\\Controllers\\FormController::create", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\FormController", "fromLink": "App/Http/Controllers/FormController.html", "link": "App/Http/Controllers/FormController.html#method_index", "name": "App\\Http\\Controllers\\FormController::index", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\FormController", "fromLink": "App/Http/Controllers/FormController.html", "link": "App/Http/Controllers/FormController.html#method_store", "name": "App\\Http\\Controllers\\FormController::store", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\FormController", "fromLink": "App/Http/Controllers/FormController.html", "link": "App/Http/Controllers/FormController.html#method_edit", "name": "App\\Http\\Controllers\\FormController::edit", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\FormController", "fromLink": "App/Http/Controllers/FormController.html", "link": "App/Http/Controllers/FormController.html#method_update", "name": "App\\Http\\Controllers\\FormController::update", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\FormController", "fromLink": "App/Http/Controllers/FormController.html", "link": "App/Http/Controllers/FormController.html#method_delete", "name": "App\\Http\\Controllers\\FormController::delete", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\FormController", "fromLink": "App/Http/Controllers/FormController.html", "link": "App/Http/Controllers/FormController.html#method_duplicate", "name": "App\\Http\\Controllers\\FormController::duplicate", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "App\\Http\\Controllers", "fromLink": "App/Http/Controllers.html", "link": "App/Http/Controllers/GroupController.html", "name": "App\\Http\\Controllers\\GroupController", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Http\\Controllers\\GroupController", "fromLink": "App/Http/Controllers/GroupController.html", "link": "App/Http/Controllers/GroupController.html#method___construct", "name": "App\\Http\\Controllers\\GroupController::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\GroupController", "fromLink": "App/Http/Controllers/GroupController.html", "link": "App/Http/Controllers/GroupController.html#method_show", "name": "App\\Http\\Controllers\\GroupController::show", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\GroupController", "fromLink": "App/Http/Controllers/GroupController.html", "link": "App/Http/Controllers/GroupController.html#method_edit", "name": "App\\Http\\Controllers\\GroupController::edit", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\GroupController", "fromLink": "App/Http/Controllers/GroupController.html", "link": "App/Http/Controllers/GroupController.html#method_storeMark", "name": "App\\Http\\Controllers\\GroupController::storeMark", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "App\\Http\\Controllers", "fromLink": "App/Http/Controllers.html", "link": "App/Http/Controllers/SessionController.html", "name": "App\\Http\\Controllers\\SessionController", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Http\\Controllers\\SessionController", "fromLink": "App/Http/Controllers/SessionController.html", "link": "App/Http/Controllers/SessionController.html#method___construct", "name": "App\\Http\\Controllers\\SessionController::__construct", "doc": "&quot;Create a new controller instance.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\SessionController", "fromLink": "App/Http/Controllers/SessionController.html", "link": "App/Http/Controllers/SessionController.html#method_rules", "name": "App\\Http\\Controllers\\SessionController::rules", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\SessionController", "fromLink": "App/Http/Controllers/SessionController.html", "link": "App/Http/Controllers/SessionController.html#method_messages", "name": "App\\Http\\Controllers\\SessionController::messages", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\SessionController", "fromLink": "App/Http/Controllers/SessionController.html", "link": "App/Http/Controllers/SessionController.html#method_all", "name": "App\\Http\\Controllers\\SessionController::all", "doc": "&quot;Display a listing of the resource.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\SessionController", "fromLink": "App/Http/Controllers/SessionController.html", "link": "App/Http/Controllers/SessionController.html#method_index", "name": "App\\Http\\Controllers\\SessionController::index", "doc": "&quot;Display a listing of the resource, filtered by the specified Course.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\SessionController", "fromLink": "App/Http/Controllers/SessionController.html", "link": "App/Http/Controllers/SessionController.html#method_create", "name": "App\\Http\\Controllers\\SessionController::create", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\SessionController", "fromLink": "App/Http/Controllers/SessionController.html", "link": "App/Http/Controllers/SessionController.html#method_edit", "name": "App\\Http\\Controllers\\SessionController::edit", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\SessionController", "fromLink": "App/Http/Controllers/SessionController.html", "link": "App/Http/Controllers/SessionController.html#method_store", "name": "App\\Http\\Controllers\\SessionController::store", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\SessionController", "fromLink": "App/Http/Controllers/SessionController.html", "link": "App/Http/Controllers/SessionController.html#method_update", "name": "App\\Http\\Controllers\\SessionController::update", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\SessionController", "fromLink": "App/Http/Controllers/SessionController.html", "link": "App/Http/Controllers/SessionController.html#method_show", "name": "App\\Http\\Controllers\\SessionController::show", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\SessionController", "fromLink": "App/Http/Controllers/SessionController.html", "link": "App/Http/Controllers/SessionController.html#method_delete", "name": "App\\Http\\Controllers\\SessionController::delete", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\SessionController", "fromLink": "App/Http/Controllers/SessionController.html", "link": "App/Http/Controllers/SessionController.html#method_fill", "name": "App\\Http\\Controllers\\SessionController::fill", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\SessionController", "fromLink": "App/Http/Controllers/SessionController.html", "link": "App/Http/Controllers/SessionController.html#method_fillin", "name": "App\\Http\\Controllers\\SessionController::fillin", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\SessionController", "fromLink": "App/Http/Controllers/SessionController.html", "link": "App/Http/Controllers/SessionController.html#method_addGroup", "name": "App\\Http\\Controllers\\SessionController::addGroup", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\SessionController", "fromLink": "App/Http/Controllers/SessionController.html", "link": "App/Http/Controllers/SessionController.html#method_joinGroup", "name": "App\\Http\\Controllers\\SessionController::joinGroup", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\SessionController", "fromLink": "App/Http/Controllers/SessionController.html", "link": "App/Http/Controllers/SessionController.html#method_mark", "name": "App\\Http\\Controllers\\SessionController::mark", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "App\\Http\\Controllers", "fromLink": "App/Http/Controllers.html", "link": "App/Http/Controllers/UserController.html", "name": "App\\Http\\Controllers\\UserController", "doc": "&quot;Class UserController&quot;"},
                                                        {"type": "Method", "fromName": "App\\Http\\Controllers\\UserController", "fromLink": "App/Http/Controllers/UserController.html", "link": "App/Http/Controllers/UserController.html#method___construct", "name": "App\\Http\\Controllers\\UserController::__construct", "doc": "&quot;Create a new controller instance.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\UserController", "fromLink": "App/Http/Controllers/UserController.html", "link": "App/Http/Controllers/UserController.html#method_rules", "name": "App\\Http\\Controllers\\UserController::rules", "doc": "&quot;Get the validation rules that apply to the request.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\UserController", "fromLink": "App/Http/Controllers/UserController.html", "link": "App/Http/Controllers/UserController.html#method_index", "name": "App\\Http\\Controllers\\UserController::index", "doc": "&quot;Display a listing of the resource.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\UserController", "fromLink": "App/Http/Controllers/UserController.html", "link": "App/Http/Controllers/UserController.html#method_create", "name": "App\\Http\\Controllers\\UserController::create", "doc": "&quot;Show the form for creating a new resource.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\UserController", "fromLink": "App/Http/Controllers/UserController.html", "link": "App/Http/Controllers/UserController.html#method_addStudent", "name": "App\\Http\\Controllers\\UserController::addStudent", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\UserController", "fromLink": "App/Http/Controllers/UserController.html", "link": "App/Http/Controllers/UserController.html#method_storeStudent", "name": "App\\Http\\Controllers\\UserController::storeStudent", "doc": "&quot;Store a newly created student \/ user resource in storage, or register an existing student to a course.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\UserController", "fromLink": "App/Http/Controllers/UserController.html", "link": "App/Http/Controllers/UserController.html#method_store", "name": "App\\Http\\Controllers\\UserController::store", "doc": "&quot;Store a newly created resource in storage.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\UserController", "fromLink": "App/Http/Controllers/UserController.html", "link": "App/Http/Controllers/UserController.html#method_login", "name": "App\\Http\\Controllers\\UserController::login", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\UserController", "fromLink": "App/Http/Controllers/UserController.html", "link": "App/Http/Controllers/UserController.html#method_auth", "name": "App\\Http\\Controllers\\UserController::auth", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\UserController", "fromLink": "App/Http/Controllers/UserController.html", "link": "App/Http/Controllers/UserController.html#method_show", "name": "App\\Http\\Controllers\\UserController::show", "doc": "&quot;Display the specified user&#039;s profile.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\UserController", "fromLink": "App/Http/Controllers/UserController.html", "link": "App/Http/Controllers/UserController.html#method_profile", "name": "App\\Http\\Controllers\\UserController::profile", "doc": "&quot;Show the authenticated user&#039;s profile&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\UserController", "fromLink": "App/Http/Controllers/UserController.html", "link": "App/Http/Controllers/UserController.html#method_update", "name": "App\\Http\\Controllers\\UserController::update", "doc": "&quot;Update the specified resource in storage.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\UserController", "fromLink": "App/Http/Controllers/UserController.html", "link": "App/Http/Controllers/UserController.html#method_logout", "name": "App\\Http\\Controllers\\UserController::logout", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\UserController", "fromLink": "App/Http/Controllers/UserController.html", "link": "App/Http/Controllers/UserController.html#method_verify", "name": "App\\Http\\Controllers\\UserController::verify", "doc": "&quot;Verify an email link, and redirect to \/reset or verify user&#039;s email&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\UserController", "fromLink": "App/Http/Controllers/UserController.html", "link": "App/Http/Controllers/UserController.html#method_reset", "name": "App\\Http\\Controllers\\UserController::reset", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\UserController", "fromLink": "App/Http/Controllers/UserController.html", "link": "App/Http/Controllers/UserController.html#method_forgot", "name": "App\\Http\\Controllers\\UserController::forgot", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\UserController", "fromLink": "App/Http/Controllers/UserController.html", "link": "App/Http/Controllers/UserController.html#method_forgotSend", "name": "App\\Http\\Controllers\\UserController::forgotSend", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\UserController", "fromLink": "App/Http/Controllers/UserController.html", "link": "App/Http/Controllers/UserController.html#method_verified", "name": "App\\Http\\Controllers\\UserController::verified", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\UserController", "fromLink": "App/Http/Controllers/UserController.html", "link": "App/Http/Controllers/UserController.html#method_storeConfig", "name": "App\\Http\\Controllers\\UserController::storeConfig", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Controllers\\UserController", "fromLink": "App/Http/Controllers/UserController.html", "link": "App/Http/Controllers/UserController.html#method_attribution", "name": "App\\Http\\Controllers\\UserController::attribution", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "App\\Http", "fromLink": "App/Http.html", "link": "App/Http/Kernel.html", "name": "App\\Http\\Kernel", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Http\\Kernel", "fromLink": "App/Http/Kernel.html", "link": "App/Http/Kernel.html#method___construct", "name": "App\\Http\\Kernel::__construct", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "App\\Http\\Middleware", "fromLink": "App/Http/Middleware.html", "link": "App/Http/Middleware/Authenticate.html", "name": "App\\Http\\Middleware\\Authenticate", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Http\\Middleware\\Authenticate", "fromLink": "App/Http/Middleware/Authenticate.html", "link": "App/Http/Middleware/Authenticate.html#method_redirectTo", "name": "App\\Http\\Middleware\\Authenticate::redirectTo", "doc": "&quot;Get the path the user should be redirected to when they are not authenticated.&quot;"},
            
            {"type": "Class", "fromName": "App\\Http\\Middleware", "fromLink": "App/Http/Middleware.html", "link": "App/Http/Middleware/AuthenticateAPI.html", "name": "App\\Http\\Middleware\\AuthenticateAPI", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Http\\Middleware\\AuthenticateAPI", "fromLink": "App/Http/Middleware/AuthenticateAPI.html", "link": "App/Http/Middleware/AuthenticateAPI.html#method_handle", "name": "App\\Http\\Middleware\\AuthenticateAPI::handle", "doc": "&quot;Handle an incoming request.&quot;"},
            
            {"type": "Class", "fromName": "App\\Http\\Middleware", "fromLink": "App/Http/Middleware.html", "link": "App/Http/Middleware/CheckForMaintenanceMode.html", "name": "App\\Http\\Middleware\\CheckForMaintenanceMode", "doc": "&quot;&quot;"},
                    
            {"type": "Class", "fromName": "App\\Http\\Middleware", "fromLink": "App/Http/Middleware.html", "link": "App/Http/Middleware/CheckRole.html", "name": "App\\Http\\Middleware\\CheckRole", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Http\\Middleware\\CheckRole", "fromLink": "App/Http/Middleware/CheckRole.html", "link": "App/Http/Middleware/CheckRole.html#method_handle", "name": "App\\Http\\Middleware\\CheckRole::handle", "doc": "&quot;Handle an incoming request.&quot;"},
            
            {"type": "Class", "fromName": "App\\Http\\Middleware", "fromLink": "App/Http/Middleware.html", "link": "App/Http/Middleware/EncryptCookies.html", "name": "App\\Http\\Middleware\\EncryptCookies", "doc": "&quot;&quot;"},
                    
            {"type": "Class", "fromName": "App\\Http\\Middleware", "fromLink": "App/Http/Middleware.html", "link": "App/Http/Middleware/EnsureEmailIsVerified.html", "name": "App\\Http\\Middleware\\EnsureEmailIsVerified", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Http\\Middleware\\EnsureEmailIsVerified", "fromLink": "App/Http/Middleware/EnsureEmailIsVerified.html", "link": "App/Http/Middleware/EnsureEmailIsVerified.html#method_handle", "name": "App\\Http\\Middleware\\EnsureEmailIsVerified::handle", "doc": "&quot;Handle an incoming request.&quot;"},
            
            {"type": "Class", "fromName": "App\\Http\\Middleware", "fromLink": "App/Http/Middleware.html", "link": "App/Http/Middleware/RedirectIfAuthenticated.html", "name": "App\\Http\\Middleware\\RedirectIfAuthenticated", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Http\\Middleware\\RedirectIfAuthenticated", "fromLink": "App/Http/Middleware/RedirectIfAuthenticated.html", "link": "App/Http/Middleware/RedirectIfAuthenticated.html#method_handle", "name": "App\\Http\\Middleware\\RedirectIfAuthenticated::handle", "doc": "&quot;Handle an incoming request.&quot;"},
            
            {"type": "Class", "fromName": "App\\Http\\Middleware", "fromLink": "App/Http/Middleware.html", "link": "App/Http/Middleware/ResponseCacheMiddleware.html", "name": "App\\Http\\Middleware\\ResponseCacheMiddleware", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Http\\Middleware\\ResponseCacheMiddleware", "fromLink": "App/Http/Middleware/ResponseCacheMiddleware.html", "link": "App/Http/Middleware/ResponseCacheMiddleware.html#method_handle", "name": "App\\Http\\Middleware\\ResponseCacheMiddleware::handle", "doc": "&quot;Handle an incoming request.&quot;"},
            
            {"type": "Class", "fromName": "App\\Http\\Middleware", "fromLink": "App/Http/Middleware.html", "link": "App/Http/Middleware/TrimStrings.html", "name": "App\\Http\\Middleware\\TrimStrings", "doc": "&quot;&quot;"},
                    
            {"type": "Class", "fromName": "App\\Http\\Middleware", "fromLink": "App/Http/Middleware.html", "link": "App/Http/Middleware/TrustProxies.html", "name": "App\\Http\\Middleware\\TrustProxies", "doc": "&quot;&quot;"},
                    
            {"type": "Class", "fromName": "App\\Http\\Middleware", "fromLink": "App/Http/Middleware.html", "link": "App/Http/Middleware/VerifyCsrfToken.html", "name": "App\\Http\\Middleware\\VerifyCsrfToken", "doc": "&quot;&quot;"},
                    
            {"type": "Class", "fromName": "App\\Http\\Requests", "fromLink": "App/Http/Requests.html", "link": "App/Http/Requests/PaginationRequest.html", "name": "App\\Http\\Requests\\PaginationRequest", "doc": "&quot;Class PaginationRequest&quot;"},
                                                        {"type": "Method", "fromName": "App\\Http\\Requests\\PaginationRequest", "fromLink": "App/Http/Requests/PaginationRequest.html", "link": "App/Http/Requests/PaginationRequest.html#method_authorize", "name": "App\\Http\\Requests\\PaginationRequest::authorize", "doc": "&quot;Determine if the user is authorized to make this request.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Requests\\PaginationRequest", "fromLink": "App/Http/Requests/PaginationRequest.html", "link": "App/Http/Requests/PaginationRequest.html#method_rules", "name": "App\\Http\\Requests\\PaginationRequest::rules", "doc": "&quot;Get the validation rules that apply to the request.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Requests\\PaginationRequest", "fromLink": "App/Http/Requests/PaginationRequest.html", "link": "App/Http/Requests/PaginationRequest.html#method_fullUrl", "name": "App\\Http\\Requests\\PaginationRequest::fullUrl", "doc": "&quot;Get the full URL for the request.&quot;"},
            
            {"type": "Class", "fromName": "App\\Http\\Resources", "fromLink": "App/Http/Resources.html", "link": "App/Http/Resources/Group.html", "name": "App\\Http\\Resources\\Group", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Http\\Resources\\Group", "fromLink": "App/Http/Resources/Group.html", "link": "App/Http/Resources/Group.html#method_toArray", "name": "App\\Http\\Resources\\Group::toArray", "doc": "&quot;Transform the resource into an array.&quot;"},
            
            {"type": "Class", "fromName": "App\\Http\\Resources", "fromLink": "App/Http/Resources.html", "link": "App/Http/Resources/GroupCollection.html", "name": "App\\Http\\Resources\\GroupCollection", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Http\\Resources\\GroupCollection", "fromLink": "App/Http/Resources/GroupCollection.html", "link": "App/Http/Resources/GroupCollection.html#method_toArray", "name": "App\\Http\\Resources\\GroupCollection::toArray", "doc": "&quot;Transform the resource collection into an array.&quot;"},
                    {"type": "Method", "fromName": "App\\Http\\Resources\\GroupCollection", "fromLink": "App/Http/Resources/GroupCollection.html", "link": "App/Http/Resources/GroupCollection.html#method_additional", "name": "App\\Http\\Resources\\GroupCollection::additional", "doc": "&quot;Add additional meta data to the resource response.&quot;"},
            
            {"type": "Class", "fromName": "App\\Http\\Resources", "fromLink": "App/Http/Resources.html", "link": "App/Http/Resources/Session.html", "name": "App\\Http\\Resources\\Session", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Http\\Resources\\Session", "fromLink": "App/Http/Resources/Session.html", "link": "App/Http/Resources/Session.html#method_toArray", "name": "App\\Http\\Resources\\Session::toArray", "doc": "&quot;Transfowrm the resource into an array.&quot;"},
            
            {"type": "Class", "fromName": "App\\Http\\Resources", "fromLink": "App/Http/Resources.html", "link": "App/Http/Resources/SessionCollection.html", "name": "App\\Http\\Resources\\SessionCollection", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Http\\Resources\\SessionCollection", "fromLink": "App/Http/Resources/SessionCollection.html", "link": "App/Http/Resources/SessionCollection.html#method_toArray", "name": "App\\Http\\Resources\\SessionCollection::toArray", "doc": "&quot;Transform the resource collection into an array.&quot;"},
            
            {"type": "Class", "fromName": "App\\Listeners", "fromLink": "App/Listeners.html", "link": "App/Listeners/LogSuccessfulLogin.html", "name": "App\\Listeners\\LogSuccessfulLogin", "doc": "&quot;Class LogSuccessfulLogin&quot;"},
                                                        {"type": "Method", "fromName": "App\\Listeners\\LogSuccessfulLogin", "fromLink": "App/Listeners/LogSuccessfulLogin.html", "link": "App/Listeners/LogSuccessfulLogin.html#method___construct", "name": "App\\Listeners\\LogSuccessfulLogin::__construct", "doc": "&quot;Create the event listener.&quot;"},
                    {"type": "Method", "fromName": "App\\Listeners\\LogSuccessfulLogin", "fromLink": "App/Listeners/LogSuccessfulLogin.html", "link": "App/Listeners/LogSuccessfulLogin.html#method_handle", "name": "App\\Listeners\\LogSuccessfulLogin::handle", "doc": "&quot;Handle the event.&quot;"},
            
            {"type": "Class", "fromName": "App\\Notifications", "fromLink": "App/Notifications.html", "link": "App/Notifications/AppResetPasswordEmail.html", "name": "App\\Notifications\\AppResetPasswordEmail", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Notifications\\AppResetPasswordEmail", "fromLink": "App/Notifications/AppResetPasswordEmail.html", "link": "App/Notifications/AppResetPasswordEmail.html#method___construct", "name": "App\\Notifications\\AppResetPasswordEmail::__construct", "doc": "&quot;AppVerifyEmail constructor.&quot;"},
                    {"type": "Method", "fromName": "App\\Notifications\\AppResetPasswordEmail", "fromLink": "App/Notifications/AppResetPasswordEmail.html", "link": "App/Notifications/AppResetPasswordEmail.html#method_resettingUrl", "name": "App\\Notifications\\AppResetPasswordEmail::resettingUrl", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Notifications\\AppResetPasswordEmail", "fromLink": "App/Notifications/AppResetPasswordEmail.html", "link": "App/Notifications/AppResetPasswordEmail.html#method_build", "name": "App\\Notifications\\AppResetPasswordEmail::build", "doc": "&quot;Build the message.&quot;"},
            
            {"type": "Class", "fromName": "App\\Notifications", "fromLink": "App/Notifications.html", "link": "App/Notifications/AppVerifyEmail.html", "name": "App\\Notifications\\AppVerifyEmail", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Notifications\\AppVerifyEmail", "fromLink": "App/Notifications/AppVerifyEmail.html", "link": "App/Notifications/AppVerifyEmail.html#method___construct", "name": "App\\Notifications\\AppVerifyEmail::__construct", "doc": "&quot;AppVerifyEmail constructor.&quot;"},
                    {"type": "Method", "fromName": "App\\Notifications\\AppVerifyEmail", "fromLink": "App/Notifications/AppVerifyEmail.html", "link": "App/Notifications/AppVerifyEmail.html#method_verificationUrl", "name": "App\\Notifications\\AppVerifyEmail::verificationUrl", "doc": "&quot;Get the verification URL for the given notifiable.&quot;"},
                    {"type": "Method", "fromName": "App\\Notifications\\AppVerifyEmail", "fromLink": "App/Notifications/AppVerifyEmail.html", "link": "App/Notifications/AppVerifyEmail.html#method_build", "name": "App\\Notifications\\AppVerifyEmail::build", "doc": "&quot;Build the message.&quot;"},
            
            {"type": "Class", "fromName": "App\\Notifications", "fromLink": "App/Notifications.html", "link": "App/Notifications/SessionStartEmail.html", "name": "App\\Notifications\\SessionStartEmail", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Notifications\\SessionStartEmail", "fromLink": "App/Notifications/SessionStartEmail.html", "link": "App/Notifications/SessionStartEmail.html#method___construct", "name": "App\\Notifications\\SessionStartEmail::__construct", "doc": "&quot;StudentInviteEmail constructor.&quot;"},
                    {"type": "Method", "fromName": "App\\Notifications\\SessionStartEmail", "fromLink": "App/Notifications/SessionStartEmail.html", "link": "App/Notifications/SessionStartEmail.html#method_build", "name": "App\\Notifications\\SessionStartEmail::build", "doc": "&quot;Build the message.&quot;"},
            
            {"type": "Class", "fromName": "App\\Notifications", "fromLink": "App/Notifications.html", "link": "App/Notifications/StudentEnrollEmail.html", "name": "App\\Notifications\\StudentEnrollEmail", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Notifications\\StudentEnrollEmail", "fromLink": "App/Notifications/StudentEnrollEmail.html", "link": "App/Notifications/StudentEnrollEmail.html#method___construct", "name": "App\\Notifications\\StudentEnrollEmail::__construct", "doc": "&quot;StudentInviteEmail constructor.&quot;"},
                    {"type": "Method", "fromName": "App\\Notifications\\StudentEnrollEmail", "fromLink": "App/Notifications/StudentEnrollEmail.html", "link": "App/Notifications/StudentEnrollEmail.html#method_build", "name": "App\\Notifications\\StudentEnrollEmail::build", "doc": "&quot;Build the message.&quot;"},
            
            {"type": "Class", "fromName": "App\\Notifications", "fromLink": "App/Notifications.html", "link": "App/Notifications/StudentInviteEmail.html", "name": "App\\Notifications\\StudentInviteEmail", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Notifications\\StudentInviteEmail", "fromLink": "App/Notifications/StudentInviteEmail.html", "link": "App/Notifications/StudentInviteEmail.html#method___construct", "name": "App\\Notifications\\StudentInviteEmail::__construct", "doc": "&quot;StudentInviteEmail constructor.&quot;"},
                    {"type": "Method", "fromName": "App\\Notifications\\StudentInviteEmail", "fromLink": "App/Notifications/StudentInviteEmail.html", "link": "App/Notifications/StudentInviteEmail.html#method_build", "name": "App\\Notifications\\StudentInviteEmail::build", "doc": "&quot;Build the message.&quot;"},
            
            {"type": "Class", "fromName": "App\\Providers", "fromLink": "App/Providers.html", "link": "App/Providers/AppServiceProvider.html", "name": "App\\Providers\\AppServiceProvider", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Providers\\AppServiceProvider", "fromLink": "App/Providers/AppServiceProvider.html", "link": "App/Providers/AppServiceProvider.html#method_register", "name": "App\\Providers\\AppServiceProvider::register", "doc": "&quot;Register any application services.&quot;"},
                    {"type": "Method", "fromName": "App\\Providers\\AppServiceProvider", "fromLink": "App/Providers/AppServiceProvider.html", "link": "App/Providers/AppServiceProvider.html#method_boot", "name": "App\\Providers\\AppServiceProvider::boot", "doc": "&quot;Bootstrap any application services.&quot;"},
            
            {"type": "Class", "fromName": "App\\Providers", "fromLink": "App/Providers.html", "link": "App/Providers/AuthServiceProvider.html", "name": "App\\Providers\\AuthServiceProvider", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Providers\\AuthServiceProvider", "fromLink": "App/Providers/AuthServiceProvider.html", "link": "App/Providers/AuthServiceProvider.html#method_boot", "name": "App\\Providers\\AuthServiceProvider::boot", "doc": "&quot;Register any authentication \/ authorization services.&quot;"},
            
            {"type": "Class", "fromName": "App\\Providers", "fromLink": "App/Providers.html", "link": "App/Providers/BroadcastServiceProvider.html", "name": "App\\Providers\\BroadcastServiceProvider", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Providers\\BroadcastServiceProvider", "fromLink": "App/Providers/BroadcastServiceProvider.html", "link": "App/Providers/BroadcastServiceProvider.html#method_boot", "name": "App\\Providers\\BroadcastServiceProvider::boot", "doc": "&quot;Bootstrap any application services.&quot;"},
            
            {"type": "Class", "fromName": "App\\Providers", "fromLink": "App/Providers.html", "link": "App/Providers/EventServiceProvider.html", "name": "App\\Providers\\EventServiceProvider", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Providers\\EventServiceProvider", "fromLink": "App/Providers/EventServiceProvider.html", "link": "App/Providers/EventServiceProvider.html#method_boot", "name": "App\\Providers\\EventServiceProvider::boot", "doc": "&quot;Register any events for your application.&quot;"},
            
            {"type": "Class", "fromName": "App\\Providers", "fromLink": "App/Providers.html", "link": "App/Providers/RouteServiceProvider.html", "name": "App\\Providers\\RouteServiceProvider", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Providers\\RouteServiceProvider", "fromLink": "App/Providers/RouteServiceProvider.html", "link": "App/Providers/RouteServiceProvider.html#method_boot", "name": "App\\Providers\\RouteServiceProvider::boot", "doc": "&quot;Define your route model bindings, pattern filters, etc.&quot;"},
                    {"type": "Method", "fromName": "App\\Providers\\RouteServiceProvider", "fromLink": "App/Providers/RouteServiceProvider.html", "link": "App/Providers/RouteServiceProvider.html#method_map", "name": "App\\Providers\\RouteServiceProvider::map", "doc": "&quot;Define the routes for the application.&quot;"},
                    {"type": "Method", "fromName": "App\\Providers\\RouteServiceProvider", "fromLink": "App/Providers/RouteServiceProvider.html", "link": "App/Providers/RouteServiceProvider.html#method_mapWebRoutes", "name": "App\\Providers\\RouteServiceProvider::mapWebRoutes", "doc": "&quot;Define the \&quot;web\&quot; routes for the application.&quot;"},
                    {"type": "Method", "fromName": "App\\Providers\\RouteServiceProvider", "fromLink": "App/Providers/RouteServiceProvider.html", "link": "App/Providers/RouteServiceProvider.html#method_mapApiRoutes", "name": "App\\Providers\\RouteServiceProvider::mapApiRoutes", "doc": "&quot;Define the \&quot;api\&quot; routes for the application.&quot;"},
            
            {"type": "Class", "fromName": "App", "fromLink": "App.html", "link": "App/Question.html", "name": "App\\Question", "doc": "&quot;App\\Question&quot;"},
                                                        {"type": "Method", "fromName": "App\\Question", "fromLink": "App/Question.html", "link": "App/Question.html#method_extractData", "name": "App\\Question::extractData", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Question", "fromLink": "App/Question.html", "link": "App/Question.html#method___get", "name": "App\\Question::__get", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Question", "fromLink": "App/Question.html", "link": "App/Question.html#method_boot", "name": "App\\Question::boot", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Question", "fromLink": "App/Question.html", "link": "App/Question.html#method_getDataAttribute", "name": "App\\Question::getDataAttribute", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Question", "fromLink": "App/Question.html", "link": "App/Question.html#method_form", "name": "App\\Question::form", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Question", "fromLink": "App/Question.html", "link": "App/Question.html#method_save", "name": "App\\Question::save", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Question", "fromLink": "App/Question.html", "link": "App/Question.html#method_fill", "name": "App\\Question::fill", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Question", "fromLink": "App/Question.html", "link": "App/Question.html#method_find", "name": "App\\Question::find", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "App", "fromLink": "App.html", "link": "App/Review.html", "name": "App\\Review", "doc": "&quot;App\\Review&quot;"},
                                                        {"type": "Method", "fromName": "App\\Review", "fromLink": "App/Review.html", "link": "App/Review.html#method_sender", "name": "App\\Review::sender", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Review", "fromLink": "App/Review.html", "link": "App/Review.html#method_recipient", "name": "App\\Review::recipient", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Review", "fromLink": "App/Review.html", "link": "App/Review.html#method_question", "name": "App\\Review::question", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Review", "fromLink": "App/Review.html", "link": "App/Review.html#method_getTypeFull", "name": "App\\Review::getTypeFull", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "App\\Rules", "fromLink": "App/Rules.html", "link": "App/Rules/DateCompare.html", "name": "App\\Rules\\DateCompare", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Rules\\DateCompare", "fromLink": "App/Rules/DateCompare.html", "link": "App/Rules/DateCompare.html#method___construct", "name": "App\\Rules\\DateCompare::__construct", "doc": "&quot;Create a new rule instance.&quot;"},
                    {"type": "Method", "fromName": "App\\Rules\\DateCompare", "fromLink": "App/Rules/DateCompare.html", "link": "App/Rules/DateCompare.html#method_passes", "name": "App\\Rules\\DateCompare::passes", "doc": "&quot;Determine if the validation rule passes.&quot;"},
                    {"type": "Method", "fromName": "App\\Rules\\DateCompare", "fromLink": "App/Rules/DateCompare.html", "link": "App/Rules/DateCompare.html#method_message", "name": "App\\Rules\\DateCompare::message", "doc": "&quot;Get the validation error message.&quot;"},
            
            {"type": "Class", "fromName": "App\\Rules", "fromLink": "App/Rules.html", "link": "App/Rules/FQDN.html", "name": "App\\Rules\\FQDN", "doc": "&quot;Fully Qualified Domain Name\nClass FQDN&quot;"},
                                                        {"type": "Method", "fromName": "App\\Rules\\FQDN", "fromLink": "App/Rules/FQDN.html", "link": "App/Rules/FQDN.html#method___construct", "name": "App\\Rules\\FQDN::__construct", "doc": "&quot;Create a new rule instance.&quot;"},
                    {"type": "Method", "fromName": "App\\Rules\\FQDN", "fromLink": "App/Rules/FQDN.html", "link": "App/Rules/FQDN.html#method_passes", "name": "App\\Rules\\FQDN::passes", "doc": "&quot;Determine if the validation rule passes.&quot;"},
                    {"type": "Method", "fromName": "App\\Rules\\FQDN", "fromLink": "App/Rules/FQDN.html", "link": "App/Rules/FQDN.html#method_message", "name": "App\\Rules\\FQDN::message", "doc": "&quot;Get the validation error message.&quot;"},
            
            {"type": "Class", "fromName": "App\\Rules", "fromLink": "App/Rules.html", "link": "App/Rules/PrependedEmailExists.html", "name": "App\\Rules\\PrependedEmailExists", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "App\\Rules\\PrependedEmailExists", "fromLink": "App/Rules/PrependedEmailExists.html", "link": "App/Rules/PrependedEmailExists.html#method___construct", "name": "App\\Rules\\PrependedEmailExists::__construct", "doc": "&quot;Create a new rule instance.&quot;"},
                    {"type": "Method", "fromName": "App\\Rules\\PrependedEmailExists", "fromLink": "App/Rules/PrependedEmailExists.html", "link": "App/Rules/PrependedEmailExists.html#method_passes", "name": "App\\Rules\\PrependedEmailExists::passes", "doc": "&quot;Determine if the validation rule passes.&quot;"},
                    {"type": "Method", "fromName": "App\\Rules\\PrependedEmailExists", "fromLink": "App/Rules/PrependedEmailExists.html", "link": "App/Rules/PrependedEmailExists.html#method_message", "name": "App\\Rules\\PrependedEmailExists::message", "doc": "&quot;Get the validation error message.&quot;"},
            
            {"type": "Class", "fromName": "App\\Rules", "fromLink": "App/Rules.html", "link": "App/Rules/UniqueCombo.html", "name": "App\\Rules\\UniqueCombo", "doc": "&quot;Class UniqueCombo&quot;"},
                                                        {"type": "Method", "fromName": "App\\Rules\\UniqueCombo", "fromLink": "App/Rules/UniqueCombo.html", "link": "App/Rules/UniqueCombo.html#method___construct", "name": "App\\Rules\\UniqueCombo::__construct", "doc": "&quot;Create a new rule instance.&quot;"},
                    {"type": "Method", "fromName": "App\\Rules\\UniqueCombo", "fromLink": "App/Rules/UniqueCombo.html", "link": "App/Rules/UniqueCombo.html#method_passes", "name": "App\\Rules\\UniqueCombo::passes", "doc": "&quot;Determine if the validation rule passes.&quot;"},
                    {"type": "Method", "fromName": "App\\Rules\\UniqueCombo", "fromLink": "App/Rules/UniqueCombo.html", "link": "App/Rules/UniqueCombo.html#method_message", "name": "App\\Rules\\UniqueCombo::message", "doc": "&quot;Get the validation error message.&quot;"},
            
            {"type": "Class", "fromName": "App", "fromLink": "App.html", "link": "App/Session.html", "name": "App\\Session", "doc": "&quot;Class Session&quot;"},
                                                        {"type": "Method", "fromName": "App\\Session", "fromLink": "App/Session.html", "link": "App/Session.html#method___get", "name": "App\\Session::__get", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Session", "fromLink": "App/Session.html", "link": "App/Session.html#method_boot", "name": "App\\Session::boot", "doc": "&quot;The \&quot;booting\&quot; method of the model.&quot;"},
                    {"type": "Method", "fromName": "App\\Session", "fromLink": "App/Session.html", "link": "App/Session.html#method_course", "name": "App\\Session::course", "doc": "&quot;Get the associated \\App\\Course&quot;"},
                    {"type": "Method", "fromName": "App\\Session", "fromLink": "App/Session.html", "link": "App/Session.html#method_form", "name": "App\\Session::form", "doc": "&quot;Get the associated \\App\\Form&quot;"},
                    {"type": "Method", "fromName": "App\\Session", "fromLink": "App/Session.html", "link": "App/Session.html#method_sendEmailNotification", "name": "App\\Session::sendEmailNotification", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Session", "fromLink": "App/Session.html", "link": "App/Session.html#method_getAttributes", "name": "App\\Session::getAttributes", "doc": "&quot;Get all of the current attributes on the model, except the guarded ones.&quot;"},
                    {"type": "Method", "fromName": "App\\Session", "fromLink": "App/Session.html", "link": "App/Session.html#method_groups", "name": "App\\Session::groups", "doc": "&quot;Retrieve the Session&#039;s groups&quot;"},
                    {"type": "Method", "fromName": "App\\Session", "fromLink": "App/Session.html", "link": "App/Session.html#method_getUserGroup", "name": "App\\Session::getUserGroup", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Session", "fromLink": "App/Session.html", "link": "App/Session.html#method_studentSession", "name": "App\\Session::studentSession", "doc": "&quot;Retrieve the \\App\\StudentSession record associated with this Session&quot;"},
                    {"type": "Method", "fromName": "App\\Session", "fromLink": "App/Session.html", "link": "App/Session.html#method_hasJoinedGroup", "name": "App\\Session::hasJoinedGroup", "doc": "&quot;Return whether the current user has joined a group.&quot;"},
                    {"type": "Method", "fromName": "App\\Session", "fromLink": "App/Session.html", "link": "App/Session.html#method_hasGroups", "name": "App\\Session::hasGroups", "doc": "&quot;Return whether the Session has any groups&quot;"},
                    {"type": "Method", "fromName": "App\\Session", "fromLink": "App/Session.html", "link": "App/Session.html#method_canAddGroup", "name": "App\\Session::canAddGroup", "doc": "&quot;Returns whether a new group can be added to this session&quot;"},
                    {"type": "Method", "fromName": "App\\Session", "fromLink": "App/Session.html", "link": "App/Session.html#method_hasEnded", "name": "App\\Session::hasEnded", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Session", "fromLink": "App/Session.html", "link": "App/Session.html#method_isOpen", "name": "App\\Session::isOpen", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\Session", "fromLink": "App/Session.html", "link": "App/Session.html#method_checkForClosed", "name": "App\\Session::checkForClosed", "doc": "&quot;Check for closed Sessions&quot;"},
                    {"type": "Method", "fromName": "App\\Session", "fromLink": "App/Session.html", "link": "App/Session.html#method_whereNotIn", "name": "App\\Session::whereNotIn", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "App", "fromLink": "App.html", "link": "App/StudentCourse.html", "name": "App\\StudentCourse", "doc": "&quot;App\\StudentCourse&quot;"},
                    
            {"type": "Class", "fromName": "App", "fromLink": "App.html", "link": "App/StudentGroup.html", "name": "App\\StudentGroup", "doc": "&quot;App\\StudentGroup&quot;"},
                                                        {"type": "Method", "fromName": "App\\StudentGroup", "fromLink": "App/StudentGroup.html", "link": "App/StudentGroup.html#method_student", "name": "App\\StudentGroup::student", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\StudentGroup", "fromLink": "App/StudentGroup.html", "link": "App/StudentGroup.html#method_user", "name": "App\\StudentGroup::user", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\StudentGroup", "fromLink": "App/StudentGroup.html", "link": "App/StudentGroup.html#method_group", "name": "App\\StudentGroup::group", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\StudentGroup", "fromLink": "App/StudentGroup.html", "link": "App/StudentGroup.html#method_students", "name": "App\\StudentGroup::students", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "App", "fromLink": "App.html", "link": "App/StudentSession.html", "name": "App\\StudentSession", "doc": "&quot;App\\StudentSession&quot;"},
                                                        {"type": "Method", "fromName": "App\\StudentSession", "fromLink": "App/StudentSession.html", "link": "App/StudentSession.html#method_group", "name": "App\\StudentSession::group", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\StudentSession", "fromLink": "App/StudentSession.html", "link": "App/StudentSession.html#method_session", "name": "App\\StudentSession::session", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\StudentSession", "fromLink": "App/StudentSession.html", "link": "App/StudentSession.html#method_student", "name": "App\\StudentSession::student", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "App", "fromLink": "App.html", "link": "App/User.html", "name": "App\\User", "doc": "&quot;Class User&quot;"},
                                                        {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_boot", "name": "App\\User::boot", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method___set", "name": "App\\User::__set", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method___get", "name": "App\\User::__get", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_getDepartmentTitle", "name": "App\\User::getDepartmentTitle", "doc": "&quot;Retrieve the department&#039;s title according to the specified abbreviation&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_getDepartmentCode", "name": "App\\User::getDepartmentCode", "doc": "&quot;Retrieve the department&#039;s short code according to the specified abbreviation&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_group", "name": "App\\User::group", "doc": "&quot;Get the StudentGroup record associated with the student.&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_teammates", "name": "App\\User::teammates", "doc": "&quot;Get the student&#039;s teammates from the database&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_courses", "name": "App\\User::courses", "doc": "&quot;Get the course record associated with the instructor.&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_sessions", "name": "App\\User::sessions", "doc": "&quot;Get the Session records associated with the instructor \/ admin.&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_forms", "name": "App\\User::forms", "doc": "&quot;Get the Form records associated with the instructor \/ admin.&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_ownsCourse", "name": "App\\User::ownsCourse", "doc": "&quot;Check if the instructor owns the specified course&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_isRegistered", "name": "App\\User::isRegistered", "doc": "&quot;Check if the student is registered in the specified course&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_studentCourses", "name": "App\\User::studentCourses", "doc": "&quot;Retrieve the courses that this student is registered on&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_studentSessions", "name": "App\\User::studentSessions", "doc": "&quot;Retrieve the Sessions that this student has submitted&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_getUserByEmail", "name": "App\\User::getUserByEmail", "doc": "&quot;Retrieve a user by his email&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_getAllStudents", "name": "App\\User::getAllStudents", "doc": "&quot;Retrieve all verified students&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_getInstructors", "name": "App\\User::getInstructors", "doc": "&quot;Get all the instructors&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_getEmailForPasswordReset", "name": "App\\User::getEmailForPasswordReset", "doc": "&quot;Get the e-mail address where password reset links are sent.&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_isInstructor", "name": "App\\User::isInstructor", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_isStudent", "name": "App\\User::isStudent", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_isAdmin", "name": "App\\User::isAdmin", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_role", "name": "App\\User::role", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_getAuthIdentifierName", "name": "App\\User::getAuthIdentifierName", "doc": "&quot;Get the name of the unique identifier for the user.&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_getAuthIdentifier", "name": "App\\User::getAuthIdentifier", "doc": "&quot;Get the unique identifier for the user.&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_getAuthPassword", "name": "App\\User::getAuthPassword", "doc": "&quot;Get the password for the user.&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_getRememberToken", "name": "App\\User::getRememberToken", "doc": "&quot;Get the token value for the \&quot;remember me\&quot; session.&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_setRememberToken", "name": "App\\User::setRememberToken", "doc": "&quot;Set the token value for the \&quot;remember me\&quot; session.&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_getRememberTokenName", "name": "App\\User::getRememberTokenName", "doc": "&quot;Get the column name for the \&quot;remember me\&quot; token.&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_hasVerifiedEmail", "name": "App\\User::hasVerifiedEmail", "doc": "&quot;Determine if the user has verified their email address.&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_markEmailAsVerified", "name": "App\\User::markEmailAsVerified", "doc": "&quot;Mark the given user&#039;s email as verified.&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_sendEmailVerificationNotification", "name": "App\\User::sendEmailVerificationNotification", "doc": "&quot;Send the email verification notification.&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_getEmailForVerification", "name": "App\\User::getEmailForVerification", "doc": "&quot;Get the email address that should be used for verification.&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_getPasswordResetToken", "name": "App\\User::getPasswordResetToken", "doc": "&quot;Get the password_reset token&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_generatePasswordResetToken", "name": "App\\User::generatePasswordResetToken", "doc": "&quot;Generates and stores a token in the password_resets table&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_can", "name": "App\\User::can", "doc": "&quot;Determine if the entity has a given ability.&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_sendPasswordResetNotification", "name": "App\\User::sendPasswordResetNotification", "doc": "&quot;Send the password reset notification.&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_sendStudentInvitationEmail", "name": "App\\User::sendStudentInvitationEmail", "doc": "&quot;Send an invitational email to the newly created student \/ user, regarding the specified course.&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_sendEnrollmentEmail", "name": "App\\User::sendEnrollmentEmail", "doc": "&quot;Send an enrollment email to the current user, regarding the specified course.&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_generateStudentPassword", "name": "App\\User::generateStudentPassword", "doc": "&quot;Generates a new password for the student.&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_verificationUrl", "name": "App\\User::verificationUrl", "doc": "&quot;Get the verification URL for the given notifiable.&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_generateApiToken", "name": "App\\User::generateApiToken", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_enrolled", "name": "App\\User::enrolled", "doc": "&quot;Retrieve the students of the instructor.&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_getId", "name": "App\\User::getId", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_getMarkFactor", "name": "App\\User::getMarkFactor", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_calculateMark", "name": "App\\User::calculateMark", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_getIndividualMark", "name": "App\\User::getIndividualMark", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_getRole", "name": "App\\User::getRole", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "App\\User", "fromLink": "App/User.html", "link": "App/User.html#method_whereApiToken", "name": "App\\User::whereApiToken", "doc": "&quot;&quot;"},
            
            
                                        // Fix trailing commas in the index
        {}
    ];

    /** Tokenizes strings by namespaces and functions */
    function tokenizer(term) {
        if (!term) {
            return [];
        }

        var tokens = [term];
        var meth = term.indexOf('::');

        // Split tokens into methods if "::" is found.
        if (meth > -1) {
            tokens.push(term.substr(meth + 2));
            term = term.substr(0, meth - 2);
        }

        // Split by namespace or fake namespace.
        if (term.indexOf('\\') > -1) {
            tokens = tokens.concat(term.split('\\'));
        } else if (term.indexOf('_') > 0) {
            tokens = tokens.concat(term.split('_'));
        }

        // Merge in splitting the string by case and return
        tokens = tokens.concat(term.match(/(([A-Z]?[^A-Z]*)|([a-z]?[^a-z]*))/g).slice(0,-1));

        return tokens;
    };

    root.Sami = {
        /**
         * Cleans the provided term. If no term is provided, then one is
         * grabbed from the query string "search" parameter.
         */
        cleanSearchTerm: function(term) {
            // Grab from the query string
            if (typeof term === 'undefined') {
                var name = 'search';
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
                var results = regex.exec(location.search);
                if (results === null) {
                    return null;
                }
                term = decodeURIComponent(results[1].replace(/\+/g, " "));
            }

            return term.replace(/<(?:.|\n)*?>/gm, '');
        },

        /** Searches through the index for a given term */
        search: function(term) {
            // Create a new search index if needed
            if (!bhIndex) {
                bhIndex = new Bloodhound({
                    limit: 500,
                    local: searchIndex,
                    datumTokenizer: function (d) {
                        return tokenizer(d.name);
                    },
                    queryTokenizer: Bloodhound.tokenizers.whitespace
                });
                bhIndex.initialize();
            }

            results = [];
            bhIndex.get(term, function(matches) {
                results = matches;
            });

            if (!rootPath) {
                return results;
            }

            // Fix the element links based on the current page depth.
            return $.map(results, function(ele) {
                if (ele.link.indexOf('..') > -1) {
                    return ele;
                }
                ele.link = rootPath + ele.link;
                if (ele.fromLink) {
                    ele.fromLink = rootPath + ele.fromLink;
                }
                return ele;
            });
        },

        /** Get a search class for a specific type */
        getSearchClass: function(type) {
            return searchTypeClasses[type] || searchTypeClasses['_'];
        },

        /** Add the left-nav tree to the site */
        injectApiTree: function(ele) {
            ele.html(treeHtml);
        }
    };

    $(function() {
        // Modify the HTML to work correctly based on the current depth
        rootPath = $('body').attr('data-root-path');
        treeHtml = treeHtml.replace(/href="/g, 'href="' + rootPath);
        Sami.injectApiTree($('#api-tree'));
    });

    return root.Sami;
})(window);

$(function() {

    // Enable the version switcher
    $('#version-switcher').change(function() {
        window.location = $(this).val()
    });

    
        // Toggle left-nav divs on click
        $('#api-tree .hd span').click(function() {
            $(this).parent().parent().toggleClass('opened');
        });

        // Expand the parent namespaces of the current page.
        var expected = $('body').attr('data-name');

        if (expected) {
            // Open the currently selected node and its parents.
            var container = $('#api-tree');
            var node = $('#api-tree li[data-name="' + expected + '"]');
            // Node might not be found when simulating namespaces
            if (node.length > 0) {
                node.addClass('active').addClass('opened');
                node.parents('li').addClass('opened');
                var scrollPos = node.offset().top - container.offset().top + container.scrollTop();
                // Position the item nearer to the top of the screen.
                scrollPos -= 200;
                container.scrollTop(scrollPos);
            }
        }

    
    
        var form = $('#search-form .typeahead');
        form.typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'search',
            displayKey: 'name',
            source: function (q, cb) {
                cb(Sami.search(q));
            }
        });

        // The selection is direct-linked when the user selects a suggestion.
        form.on('typeahead:selected', function(e, suggestion) {
            window.location = suggestion.link;
        });

        // The form is submitted when the user hits enter.
        form.keypress(function (e) {
            if (e.which == 13) {
                $('#search-form').submit();
                return true;
            }
        });

    
});


