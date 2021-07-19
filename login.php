<?php
if (session_id() == "")
    session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php
//
// Page class
//

$login = NULL; // Initialize page object first - (Inicialize o objeto da página primeiro)

class clogin extends cusuarios {

    // Page ID
    var $PageID = 'login';
    // Project ID
    var $ProjectID = "{2B7992FC-5911-46A7-9310-01F4D4225C49}";
    // Page object name
    var $PageObjName = 'login';

    // Page name
    function PageName() {
        return ew_CurrentPage();
    }

    // Page URL
    function PageUrl() {
        $PageUrl = ew_CurrentPage() . "?";
        return $PageUrl;
    }

    // Message
    function getMessage() {
        return @$_SESSION[EW_SESSION_MESSAGE];
    }

    function setMessage($v) {
        ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
    }

    function getFailureMessage() {
        return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
    }

    function setFailureMessage($v) {
        ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
    }

    function getSuccessMessage() {
        return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
    }

    function setSuccessMessage($v) {
        ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
    }

    function getWarningMessage() {
        return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
    }

    function setWarningMessage($v) {
        ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
    }

    // Show message - (Monstrar mensagem)
    function ShowMessage() {
        $hidden = FALSE;
        $html = "";

        // Message
        $sMessage = $this->getMessage();
        $this->Message_Showing($sMessage, "");
        if ($sMessage <> "") { // Message in Session, display
            if (!$hidden)
                $sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
            $html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
            $_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
        }

        // Warning message
        $sWarningMessage = $this->getWarningMessage();
        $this->Message_Showing($sWarningMessage, "warning");
        if ($sWarningMessage <> "") { // Message in Session, display
            if (!$hidden)
                $sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
            $html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
            $_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
        }

        // Success message
        $sSuccessMessage = $this->getSuccessMessage();
        $this->Message_Showing($sSuccessMessage, "success");
        if ($sSuccessMessage <> "") { // Message in Session, display
            if (!$hidden)
                $sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
            $html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
            $_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
        }

        // Failure message
        $sErrorMessage = $this->getFailureMessage();
        $this->Message_Showing($sErrorMessage, "failure");
        if ($sErrorMessage <> "") { // Message in Session, display
            if (!$hidden)
                $sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
            $html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
            $_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
        }
        echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
    }

    var $PageHeader;
    var $PageFooter;

    // Show Page Header
    function ShowPageHeader() {
        $sHeader = $this->PageHeader;
        $this->Page_DataRendering($sHeader);
        if ($sHeader <> "") { // Header exists, display
            echo "<p>" . $sHeader . "</p>";
        }
    }

    // Show Page Footer
    function ShowPageFooter() {
        $sFooter = $this->PageFooter;
        $this->Page_DataRendered($sFooter);
        if ($sFooter <> "") { // Footer exists, display
            echo "<p>" . $sFooter . "</p>";
        }
    }

    // Validate page request
    function IsPageRequest() {
        return TRUE;
    }

    var $Token = "";
    var $CheckToken = EW_CHECK_TOKEN;
    var $CheckTokenFn = "ew_CheckToken";
    var $CreateTokenFn = "ew_CreateToken";

    // Valid Post
    function ValidPost() {
        if (!$this->CheckToken || !ew_IsHttpPost())
            return TRUE;
        if (!isset($_POST[EW_TOKEN_NAME]))
            return FALSE;
        $fn = $this->CheckTokenFn;
        if (is_callable($fn))
            return $fn($_POST[EW_TOKEN_NAME]);
        return FALSE;
    }

    // Create Token
    function CreateToken() {
        global $gsToken;
        if ($this->CheckToken) {
            $fn = $this->CreateTokenFn;
            if ($this->Token == "" && is_callable($fn)) // Create token
                $this->Token = $fn();
            $gsToken = $this->Token; // Save to global variable
        }
    }

    //
    // Page class constructor
    //
	function __construct() {
        global $conn, $Language;
        $GLOBALS["Page"] = &$this;

        // Language object
        if (!isset($Language))
            $Language = new cLanguage();

        // Parent constuctor
        parent::__construct();

        // Table object (usuarios)
        if (!isset($GLOBALS["usuarios"]) || get_class($GLOBALS["usuarios"]) == "cusuarios") {
            $GLOBALS["usuarios"] = &$this;
            $GLOBALS["Table"] = &$GLOBALS["usuarios"];
        }
        if (!isset($GLOBALS["usuarios"]))
            $GLOBALS["usuarios"] = &$this;

        // User table object (usuarios)
        if (!isset($GLOBALS["UserTable"]))
            $GLOBALS["UserTable"] = new cusuarios();

        // Page ID
        if (!defined("EW_PAGE_ID"))
            define("EW_PAGE_ID", 'login', TRUE);

        // Start timer
        if (!isset($GLOBALS["gTimer"]))
            $GLOBALS["gTimer"] = new cTimer();

        // Open connection
        if (!isset($conn))
            $conn = ew_Connect();
    }

    // 
    //  Page_Init
    //
	function Page_Init() {
        global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

        // Security
        $Security = new cAdvancedSecurity();
        $this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        $this->Page_Load();

        // Check token
        if (!$this->ValidPost()) {
            echo $Language->Phrase("InvalidPostRequest");
            $this->Page_Terminate();
            exit();
        }

        // Create Token
        $this->CreateToken();
    }

    //
    // Page_Terminate
    //
	function Page_Terminate($url = "") {
        global $conn, $gsExportFile, $gTmpImages;

        // Page Unload event
        $this->Page_Unload();

        // Global Page Unloaded event (in userfn*.php)
        Page_Unloaded();

        // Export
        $this->Page_Redirecting($url);

        // Close connection
        $conn->Close();

        // Go to URL if specified
        if ($url <> "") {
            if (!EW_DEBUG_ENABLED && ob_get_length())
                ob_end_clean();
            header("Location: " . $url);
        }
        exit();
    }

    var $Username;
    var $LoginType;

    function Page_Main() {
        global $Security, $Language, $UserProfile, $gsFormError;
        global $Breadcrumb;
        $url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/") + 1);
        $Breadcrumb = new cBreadcrumb;
        $Breadcrumb->Add("login", "LoginPage", $url, "", "", TRUE);
        $sPassword = "";
        $sLastUrl = $Security->LastUrl(); // Get last URL
        if ($sLastUrl == "")
            $sLastUrl = "index.php";
        if (IsLoggingIn()) {
            $this->Username = @$_SESSION[EW_SESSION_USER_PROFILE_USER_NAME];
            $sPassword = @$_SESSION[EW_SESSION_USER_PROFILE_PASSWORD];
            $this->LoginType = @$_SESSION[EW_SESSION_USER_PROFILE_LOGIN_TYPE];
            $bValidPwd = $Security->ValidateUser($this->Username, $sPassword, FALSE);
            if ($bValidPwd) {
                $_SESSION[EW_SESSION_USER_PROFILE_USER_NAME] = "";
                $_SESSION[EW_SESSION_USER_PROFILE_PASSWORD] = "";
                $_SESSION[EW_SESSION_USER_PROFILE_LOGIN_TYPE] = "";
            }
        } else {
            if (!$Security->IsLoggedIn())
                $Security->AutoLogin();
            $Security->LoadUserLevel(); /* Load user level */
            $this->Username = ""; /* Initialize */
            if (@$_POST["username"] <> "") {

                /* Setup variables */
                $this->Username = ew_RemoveXSS(ew_StripSlashes(@$_POST["username"]));
                $sPassword = ew_RemoveXSS(ew_StripSlashes(@$_POST["password"]));
                $this->LoginType = strtolower(ew_RemoveXSS(@$_POST["type"]));
            }
            if ($this->Username <> "") {
                $bValidate = $this->ValidateForm($this->Username, $sPassword);
                if (!$bValidate)
                    $this->setFailureMessage($gsFormError);
                $_SESSION[EW_SESSION_USER_PROFILE_USER_NAME] = $this->Username; /* Save login user name */
                $_SESSION[EW_SESSION_USER_PROFILE_LOGIN_TYPE] = $this->LoginType; /* Save login type */
            } else {
                if ($Security->IsLoggedIn()) {
                    if ($this->getFailureMessage() == "")
                        $this->Page_Terminate($sLastUrl); /* Return to last accessed page */
                }
                $bValidate = FALSE;

                // Restore settings
                if (@$_COOKIE[EW_PROJECT_NAME]['Checksum'] == strval(crc32(md5(EW_RANDOM_KEY))))
                    $this->Username = ew_Decrypt(@$_COOKIE[EW_PROJECT_NAME]['Username']);
                if (@$_COOKIE[EW_PROJECT_NAME]['AutoLogin'] == "autologin") {
                    $this->LoginType = "a";
                } elseif (@$_COOKIE[EW_PROJECT_NAME]['AutoLogin'] == "rememberusername") {
                    $this->LoginType = "u";
                } else {
                    $this->LoginType = "";
                }
            }
            $bValidPwd = FALSE;
            if ($bValidate) {

                // Call Logging In event
                $bValidate = $this->User_LoggingIn($this->Username, $sPassword);
                if ($bValidate) {
                    $bValidPwd = $Security->ValidateUser($this->Username, $sPassword, FALSE); // Manual login
                    if (!$bValidPwd) {
                        if ($this->getFailureMessage() == "")
                            $this->setFailureMessage($Language->Phrase("InvalidUidPwd")); // Invalid user id/password
                    }
                } else {
                    if ($this->getFailureMessage() == "")
                        $this->setFailureMessage($Language->Phrase("LoginCancelled")); // Login cancelled
                }
            }
        }
        if ($bValidPwd) {

            // Write cookies
            if ($this->LoginType == "a") { // Auto login
                setcookie(EW_PROJECT_NAME . '[AutoLogin]', "autologin", EW_COOKIE_EXPIRY_TIME); // Set autologin cookie
                setcookie(EW_PROJECT_NAME . '[Username]', ew_Encrypt($this->Username), EW_COOKIE_EXPIRY_TIME); // Set user name cookie
                setcookie(EW_PROJECT_NAME . '[Password]', ew_Encrypt($sPassword), EW_COOKIE_EXPIRY_TIME); // Set password cookie
                setcookie(EW_PROJECT_NAME . '[Checksum]', crc32(md5(EW_RANDOM_KEY)), EW_COOKIE_EXPIRY_TIME);
            } elseif ($this->LoginType == "u") { // Remember user name
                setcookie(EW_PROJECT_NAME . '[AutoLogin]', "rememberusername", EW_COOKIE_EXPIRY_TIME); // Set remember user name cookie
                setcookie(EW_PROJECT_NAME . '[Username]', ew_Encrypt($this->Username), EW_COOKIE_EXPIRY_TIME); // Set user name cookie
                setcookie(EW_PROJECT_NAME . '[Checksum]', crc32(md5(EW_RANDOM_KEY)), EW_COOKIE_EXPIRY_TIME);
            } else {
                setcookie(EW_PROJECT_NAME . '[AutoLogin]', "", EW_COOKIE_EXPIRY_TIME); // Clear auto login cookie
            }

            // Call loggedin event
            $this->User_LoggedIn($this->Username);
            $this->WriteAuditTrailOnLogin($this->Username);
            $this->Page_Terminate($sLastUrl); // Return to last accessed URL
        } elseif ($this->Username <> "" && $sPassword <> "") {

            // Call user login error event
            $this->User_LoginError($this->Username, $sPassword);
        }
    }

    //
    // Validate form
    //
	function ValidateForm($usr, $pwd) {
        global $Language, $gsFormError;

        // Initialize form error message
        $gsFormError = "";

        // Check if validation required
        if (!EW_SERVER_VALIDATE)
            return TRUE;
        if (trim($usr) == "") {
            ew_AddMessage($gsFormError, $Language->Phrase("EnterUid"));
        }
        if (trim($pwd) == "") {
            ew_AddMessage($gsFormError, $Language->Phrase("EnterPwd"));
        }

        // Return validate result
        $ValidateForm = ($gsFormError == "");

        // Call Form Custom Validate event
        $sFormCustomError = "";
        $ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
        if ($sFormCustomError <> "") {
            ew_AddMessage($gsFormError, $sFormCustomError);
        }
        return $ValidateForm;
    }

    function WriteAuditTrailOnLogin($usr) {
        global $Language;
        ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $Language->Phrase("AuditTrailLogin"), ew_CurrentUserIP(), "", "", "", "");
    }

    // Page Load event
    function Page_Load() {

        //echo "Page Load";
    }

    // Page Unload event
    function Page_Unload() {

        //echo "Page Unload";
    }

    // Page Redirecting event
    function Page_Redirecting(&$url) {

        // Example:
        //$url = "your URL";
    }

    // Message Showing event
    // $type = ''|'success'|'failure'
    function Message_Showing(&$msg, $type) {

        // Example:
        //if ($type == 'success') $msg = "your success message";
    }

    // Page Render event
    function Page_Render() {

        //echo "Page Render";
    }

    // Page Data Rendering event
    function Page_DataRendering(&$header) {

        // Example:
        //$header = "your header";
    }

    // Page Data Rendered event
    function Page_DataRendered(&$footer) {

        // Example:
        //$footer = "your footer";
    }

    // User Logging In event
    function User_LoggingIn($usr, &$pwd) {

        // Enter your code here
        // To cancel, set return value to FALSE

        return TRUE;
    }

    // User Logged In event
    function User_LoggedIn($usr) {

        //echo "User Logged In";
    }

    // User Login Error event
    function User_LoginError($usr, $pwd) {

        //echo "User Login Error";
    }

    // Form Custom Validate event
    function Form_CustomValidate(&$CustomError) {

        // Return error message in CustomError
        return TRUE;
    }

}
?>
<?php ew_Header(TRUE) ?>
<?php
// Create page object
if (!isset($login))
    $login = new clogin();

// Page init
$login->Page_Init();

// Page main
$login->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$login->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<script type="text/javascript">
    var flogin = new ew_Form("flogin");

// Validate function
    flogin.Validate = function ()
    {
        var fobj = this.Form;
        if (!this.ValidateRequired)
            return true; // Ignore validation
        if (!ew_HasValue(fobj.username))
            return this.OnError(fobj.username, ewLanguage.Phrase("EnterUid"));
        if (!ew_HasValue(fobj.password))
            return this.OnError(fobj.password, ewLanguage.Phrase("EnterPwd"));

        // Call Form Custom Validate event
        if (!this.Form_CustomValidate(fobj))
            return false;
        return true;
    }

// Form_CustomValidate function
    flogin.Form_CustomValidate =
            function (fobj) { // DO NOT CHANGE THIS LINE!

                // Your custom validation code here, return false if invalid. 
                return true;
            }

// Requires js validation
<?php if (EW_CLIENT_VALIDATE) { ?>
        flogin.ValidateRequired = true;
<?php } else { ?>
        flogin.ValidateRequired = false;
    <?php } ?>
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
    <div class="clearfix"></div>
</div>
<?php $login->ShowPageHeader(); ?>
    <?php
    $login->ShowMessage();
    ?>
<form name="flogin" id="flogin" class="form-horizontal ewForm ewLoginForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($login->CheckToken) { ?>
        <input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $login->Token ?>">
<?php } ?>
    <div class="form-group">
        <label class="col-sm-2 control-label ewLabel" for="username"><?php echo $Language->Phrase("Username") ?></label>
        <div class="col-sm-10 input-group"><div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" name="username" id="username" class="form-control ewControl" value="<?php echo ew_HtmlEncode($login->Username) ?>"></div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label ewLabel" for="password"><?php echo $Language->Phrase("Password") ?></label>
        <div class="col-sm-10 input-group"><div class="input-group-addon"><i class="fa fa-lock"></i></div><input type="password" name="password" id="password" class="form-control ewControl"></div>
    </div>
    <div class="form-group">

    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button class="btn btn-primary ewButton btn-success" name="btnsubmit" id="btnsubmit" type="submit"><i class="fa fa-arrow-right"></i>&nbsp;Entrar</button>
        </div>
    </div>
</form>
<br>
<a class="ewLink ewLinkSeparator" href="forgotpwd.php"><?php echo $Language->Phrase("ForgotPwd") ?></a>
<script type="text/javascript">
    flogin.Init();
</script>
<?php
$login->ShowPageFooter();
if (EW_DEBUG_ENABLED)
    echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$login->Page_Terminate();
?>
