<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "smtpinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$smtp_add = NULL; // Initialize page object first

class csmtp_add extends csmtp {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{2B7992FC-5911-46A7-9310-01F4D4225C49}";

	// Table name
	var $TableName = 'smtp';

	// Page object name
	var $PageObjName = 'smtp_add';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}
	var $AuditTrailOnAdd = TRUE;

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

	// Show message
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
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
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
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (smtp)
		if (!isset($GLOBALS["smtp"]) || get_class($GLOBALS["smtp"]) == "csmtp") {
			$GLOBALS["smtp"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["smtp"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'smtp', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("smtplist.php"));
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Create form object
		$objForm = new cFormObj();
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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $smtp;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($smtp);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["Id"] != "") {
				$this->Id->setQueryStringValue($_GET["Id"]);
				$this->setKey("Id", $this->Id->CurrentValue); // Set up key
			} else {
				$this->setKey("Id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("smtplist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "smtpview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->SMTP->CurrentValue = NULL;
		$this->SMTP->OldValue = $this->SMTP->CurrentValue;
		$this->SMTP_Porta->CurrentValue = NULL;
		$this->SMTP_Porta->OldValue = $this->SMTP_Porta->CurrentValue;
		$this->SMTP_Usuario->CurrentValue = NULL;
		$this->SMTP_Usuario->OldValue = $this->SMTP_Usuario->CurrentValue;
		$this->SMTP_Senha->CurrentValue = NULL;
		$this->SMTP_Senha->OldValue = $this->SMTP_Senha->CurrentValue;
		$this->Email_de_Envio->CurrentValue = NULL;
		$this->Email_de_Envio->OldValue = $this->Email_de_Envio->CurrentValue;
		$this->Email_de_Recebimento->CurrentValue = NULL;
		$this->Email_de_Recebimento->OldValue = $this->Email_de_Recebimento->CurrentValue;
		$this->Seguranca->CurrentValue = NULL;
		$this->Seguranca->OldValue = $this->Seguranca->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->SMTP->FldIsDetailKey) {
			$this->SMTP->setFormValue($objForm->GetValue("x_SMTP"));
		}
		if (!$this->SMTP_Porta->FldIsDetailKey) {
			$this->SMTP_Porta->setFormValue($objForm->GetValue("x_SMTP_Porta"));
		}
		if (!$this->SMTP_Usuario->FldIsDetailKey) {
			$this->SMTP_Usuario->setFormValue($objForm->GetValue("x_SMTP_Usuario"));
		}
		if (!$this->SMTP_Senha->FldIsDetailKey) {
			$this->SMTP_Senha->setFormValue($objForm->GetValue("x_SMTP_Senha"));
		}
		if (!$this->Email_de_Envio->FldIsDetailKey) {
			$this->Email_de_Envio->setFormValue($objForm->GetValue("x_Email_de_Envio"));
		}
		if (!$this->Email_de_Recebimento->FldIsDetailKey) {
			$this->Email_de_Recebimento->setFormValue($objForm->GetValue("x_Email_de_Recebimento"));
		}
		if (!$this->Seguranca->FldIsDetailKey) {
			$this->Seguranca->setFormValue($objForm->GetValue("x_Seguranca"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->SMTP->CurrentValue = $this->SMTP->FormValue;
		$this->SMTP_Porta->CurrentValue = $this->SMTP_Porta->FormValue;
		$this->SMTP_Usuario->CurrentValue = $this->SMTP_Usuario->FormValue;
		$this->SMTP_Senha->CurrentValue = $this->SMTP_Senha->FormValue;
		$this->Email_de_Envio->CurrentValue = $this->Email_de_Envio->FormValue;
		$this->Email_de_Recebimento->CurrentValue = $this->Email_de_Recebimento->FormValue;
		$this->Seguranca->CurrentValue = $this->Seguranca->FormValue;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->Id->setDbValue($rs->fields('Id'));
		$this->SMTP->setDbValue($rs->fields('SMTP'));
		$this->SMTP_Porta->setDbValue($rs->fields('SMTP_Porta'));
		$this->SMTP_Usuario->setDbValue($rs->fields('SMTP_Usuario'));
		$this->SMTP_Senha->setDbValue($rs->fields('SMTP_Senha'));
		$this->Email_de_Envio->setDbValue($rs->fields('Email_de_Envio'));
		$this->Email_de_Recebimento->setDbValue($rs->fields('Email_de_Recebimento'));
		$this->Seguranca->setDbValue($rs->fields('Seguranca'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id->DbValue = $row['Id'];
		$this->SMTP->DbValue = $row['SMTP'];
		$this->SMTP_Porta->DbValue = $row['SMTP_Porta'];
		$this->SMTP_Usuario->DbValue = $row['SMTP_Usuario'];
		$this->SMTP_Senha->DbValue = $row['SMTP_Senha'];
		$this->Email_de_Envio->DbValue = $row['Email_de_Envio'];
		$this->Email_de_Recebimento->DbValue = $row['Email_de_Recebimento'];
		$this->Seguranca->DbValue = $row['Seguranca'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("Id")) <> "")
			$this->Id->CurrentValue = $this->getKey("Id"); // Id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// Id
		// SMTP
		// SMTP_Porta
		// SMTP_Usuario
		// SMTP_Senha
		// Email_de_Envio
		// Email_de_Recebimento
		// Seguranca

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// SMTP
			$this->SMTP->ViewValue = $this->SMTP->CurrentValue;
			$this->SMTP->ViewCustomAttributes = "";

			// SMTP_Porta
			$this->SMTP_Porta->ViewValue = $this->SMTP_Porta->CurrentValue;
			$this->SMTP_Porta->ViewCustomAttributes = "";

			// SMTP_Usuario
			$this->SMTP_Usuario->ViewValue = $this->SMTP_Usuario->CurrentValue;
			$this->SMTP_Usuario->ViewCustomAttributes = "";

			// SMTP_Senha
			$this->SMTP_Senha->ViewValue = "********";
			$this->SMTP_Senha->ViewCustomAttributes = "";

			// Email_de_Envio
			$this->Email_de_Envio->ViewValue = $this->Email_de_Envio->CurrentValue;
			$this->Email_de_Envio->ViewCustomAttributes = "";

			// Email_de_Recebimento
			$this->Email_de_Recebimento->ViewValue = $this->Email_de_Recebimento->CurrentValue;
			$this->Email_de_Recebimento->ViewCustomAttributes = "";

			// Seguranca
			if (strval($this->Seguranca->CurrentValue) <> "") {
				switch ($this->Seguranca->CurrentValue) {
					case $this->Seguranca->FldTagValue(1):
						$this->Seguranca->ViewValue = $this->Seguranca->FldTagCaption(1) <> "" ? $this->Seguranca->FldTagCaption(1) : $this->Seguranca->CurrentValue;
						break;
					case $this->Seguranca->FldTagValue(2):
						$this->Seguranca->ViewValue = $this->Seguranca->FldTagCaption(2) <> "" ? $this->Seguranca->FldTagCaption(2) : $this->Seguranca->CurrentValue;
						break;
					default:
						$this->Seguranca->ViewValue = $this->Seguranca->CurrentValue;
				}
			} else {
				$this->Seguranca->ViewValue = NULL;
			}
			$this->Seguranca->ViewCustomAttributes = "";

			// SMTP
			$this->SMTP->LinkCustomAttributes = "";
			$this->SMTP->HrefValue = "";
			$this->SMTP->TooltipValue = "";

			// SMTP_Porta
			$this->SMTP_Porta->LinkCustomAttributes = "";
			$this->SMTP_Porta->HrefValue = "";
			$this->SMTP_Porta->TooltipValue = "";

			// SMTP_Usuario
			$this->SMTP_Usuario->LinkCustomAttributes = "";
			$this->SMTP_Usuario->HrefValue = "";
			$this->SMTP_Usuario->TooltipValue = "";

			// SMTP_Senha
			$this->SMTP_Senha->LinkCustomAttributes = "";
			$this->SMTP_Senha->HrefValue = "";
			$this->SMTP_Senha->TooltipValue = "";

			// Email_de_Envio
			$this->Email_de_Envio->LinkCustomAttributes = "";
			$this->Email_de_Envio->HrefValue = "";
			$this->Email_de_Envio->TooltipValue = "";

			// Email_de_Recebimento
			$this->Email_de_Recebimento->LinkCustomAttributes = "";
			$this->Email_de_Recebimento->HrefValue = "";
			$this->Email_de_Recebimento->TooltipValue = "";

			// Seguranca
			$this->Seguranca->LinkCustomAttributes = "";
			$this->Seguranca->HrefValue = "";
			$this->Seguranca->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// SMTP
			$this->SMTP->EditAttrs["class"] = "form-control";
			$this->SMTP->EditCustomAttributes = "";
			$this->SMTP->EditValue = ew_HtmlEncode($this->SMTP->CurrentValue);

			// SMTP_Porta
			$this->SMTP_Porta->EditAttrs["class"] = "form-control";
			$this->SMTP_Porta->EditCustomAttributes = "";
			$this->SMTP_Porta->EditValue = ew_HtmlEncode($this->SMTP_Porta->CurrentValue);

			// SMTP_Usuario
			$this->SMTP_Usuario->EditAttrs["class"] = "form-control";
			$this->SMTP_Usuario->EditCustomAttributes = "";
			$this->SMTP_Usuario->EditValue = ew_HtmlEncode($this->SMTP_Usuario->CurrentValue);

			// SMTP_Senha
			$this->SMTP_Senha->EditAttrs["class"] = "form-control";
			$this->SMTP_Senha->EditCustomAttributes = "";
			$this->SMTP_Senha->EditValue = ew_HtmlEncode($this->SMTP_Senha->CurrentValue);

			// Email_de_Envio
			$this->Email_de_Envio->EditAttrs["class"] = "form-control";
			$this->Email_de_Envio->EditCustomAttributes = "";
			$this->Email_de_Envio->EditValue = ew_HtmlEncode($this->Email_de_Envio->CurrentValue);

			// Email_de_Recebimento
			$this->Email_de_Recebimento->EditAttrs["class"] = "form-control";
			$this->Email_de_Recebimento->EditCustomAttributes = "";
			$this->Email_de_Recebimento->EditValue = ew_HtmlEncode($this->Email_de_Recebimento->CurrentValue);

			// Seguranca
			$this->Seguranca->EditAttrs["class"] = "form-control";
			$this->Seguranca->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Seguranca->FldTagValue(1), $this->Seguranca->FldTagCaption(1) <> "" ? $this->Seguranca->FldTagCaption(1) : $this->Seguranca->FldTagValue(1));
			$arwrk[] = array($this->Seguranca->FldTagValue(2), $this->Seguranca->FldTagCaption(2) <> "" ? $this->Seguranca->FldTagCaption(2) : $this->Seguranca->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->Seguranca->EditValue = $arwrk;

			// Edit refer script
			// SMTP

			$this->SMTP->HrefValue = "";

			// SMTP_Porta
			$this->SMTP_Porta->HrefValue = "";

			// SMTP_Usuario
			$this->SMTP_Usuario->HrefValue = "";

			// SMTP_Senha
			$this->SMTP_Senha->HrefValue = "";

			// Email_de_Envio
			$this->Email_de_Envio->HrefValue = "";

			// Email_de_Recebimento
			$this->Email_de_Recebimento->HrefValue = "";

			// Seguranca
			$this->Seguranca->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->SMTP->FldIsDetailKey && !is_null($this->SMTP->FormValue) && $this->SMTP->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->SMTP->FldCaption(), $this->SMTP->ReqErrMsg));
		}
		if (!$this->SMTP_Porta->FldIsDetailKey && !is_null($this->SMTP_Porta->FormValue) && $this->SMTP_Porta->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->SMTP_Porta->FldCaption(), $this->SMTP_Porta->ReqErrMsg));
		}
		if (!$this->SMTP_Usuario->FldIsDetailKey && !is_null($this->SMTP_Usuario->FormValue) && $this->SMTP_Usuario->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->SMTP_Usuario->FldCaption(), $this->SMTP_Usuario->ReqErrMsg));
		}
		if (!$this->SMTP_Senha->FldIsDetailKey && !is_null($this->SMTP_Senha->FormValue) && $this->SMTP_Senha->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->SMTP_Senha->FldCaption(), $this->SMTP_Senha->ReqErrMsg));
		}
		if (!$this->Email_de_Envio->FldIsDetailKey && !is_null($this->Email_de_Envio->FormValue) && $this->Email_de_Envio->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Email_de_Envio->FldCaption(), $this->Email_de_Envio->ReqErrMsg));
		}
		if (!$this->Email_de_Recebimento->FldIsDetailKey && !is_null($this->Email_de_Recebimento->FormValue) && $this->Email_de_Recebimento->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Email_de_Recebimento->FldCaption(), $this->Email_de_Recebimento->ReqErrMsg));
		}
		if (!$this->Seguranca->FldIsDetailKey && !is_null($this->Seguranca->FormValue) && $this->Seguranca->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Seguranca->FldCaption(), $this->Seguranca->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// SMTP
		$this->SMTP->SetDbValueDef($rsnew, $this->SMTP->CurrentValue, NULL, FALSE);

		// SMTP_Porta
		$this->SMTP_Porta->SetDbValueDef($rsnew, $this->SMTP_Porta->CurrentValue, NULL, FALSE);

		// SMTP_Usuario
		$this->SMTP_Usuario->SetDbValueDef($rsnew, $this->SMTP_Usuario->CurrentValue, NULL, FALSE);

		// SMTP_Senha
		$this->SMTP_Senha->SetDbValueDef($rsnew, $this->SMTP_Senha->CurrentValue, NULL, FALSE);

		// Email_de_Envio
		$this->Email_de_Envio->SetDbValueDef($rsnew, $this->Email_de_Envio->CurrentValue, NULL, FALSE);

		// Email_de_Recebimento
		$this->Email_de_Recebimento->SetDbValueDef($rsnew, $this->Email_de_Recebimento->CurrentValue, NULL, FALSE);

		// Seguranca
		$this->Seguranca->SetDbValueDef($rsnew, $this->Seguranca->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->Id->setDbValue($conn->Insert_ID());
			$rsnew['Id'] = $this->Id->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
			$this->WriteAuditTrailOnAdd($rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "smtplist.php", "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'smtp';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'smtp';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['Id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $usr = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
			}
		}
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
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	function Page_DataRendering(&$header) {

		//$header = $this->setMessage("your header");
	}

	function Page_DataRendered(&$footer) {

		//$footer = $this->setMessage("your footer");
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
if (!isset($smtp_add)) $smtp_add = new csmtp_add();

// Page init
$smtp_add->Page_Init();

// Page main
$smtp_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$smtp_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var smtp_add = new ew_Page("smtp_add");
smtp_add.PageID = "add"; // Page ID
var EW_PAGE_ID = smtp_add.PageID; // For backward compatibility

// Form object
var fsmtpadd = new ew_Form("fsmtpadd");

// Validate form
fsmtpadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_SMTP");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $smtp->SMTP->FldCaption(), $smtp->SMTP->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_SMTP_Porta");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $smtp->SMTP_Porta->FldCaption(), $smtp->SMTP_Porta->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_SMTP_Usuario");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $smtp->SMTP_Usuario->FldCaption(), $smtp->SMTP_Usuario->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_SMTP_Senha");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $smtp->SMTP_Senha->FldCaption(), $smtp->SMTP_Senha->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Email_de_Envio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $smtp->Email_de_Envio->FldCaption(), $smtp->Email_de_Envio->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Email_de_Recebimento");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $smtp->Email_de_Recebimento->FldCaption(), $smtp->Email_de_Recebimento->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Seguranca");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $smtp->Seguranca->FldCaption(), $smtp->Seguranca->ReqErrMsg)) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fsmtpadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsmtpadd.ValidateRequired = true;
<?php } else { ?>
fsmtpadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $smtp_add->ShowPageHeader(); ?>
<?php
$smtp_add->ShowMessage();
?>
<form name="fsmtpadd" id="fsmtpadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($smtp_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $smtp_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="smtp">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($smtp->SMTP->Visible) { // SMTP ?>
	<div id="r_SMTP" class="form-group">
		<label id="elh_smtp_SMTP" for="x_SMTP" class="col-sm-2 control-label ewLabel"><?php echo $smtp->SMTP->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $smtp->SMTP->CellAttributes() ?>>
<span id="el_smtp_SMTP">
<input type="text" data-field="x_SMTP" name="x_SMTP" id="x_SMTP" size="40" maxlength="40" value="<?php echo $smtp->SMTP->EditValue ?>"<?php echo $smtp->SMTP->EditAttributes() ?>>
</span>
<?php echo $smtp->SMTP->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($smtp->SMTP_Porta->Visible) { // SMTP_Porta ?>
	<div id="r_SMTP_Porta" class="form-group">
		<label id="elh_smtp_SMTP_Porta" for="x_SMTP_Porta" class="col-sm-2 control-label ewLabel"><?php echo $smtp->SMTP_Porta->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $smtp->SMTP_Porta->CellAttributes() ?>>
<span id="el_smtp_SMTP_Porta">
<input type="text" data-field="x_SMTP_Porta" name="x_SMTP_Porta" id="x_SMTP_Porta" size="5" maxlength="10" value="<?php echo $smtp->SMTP_Porta->EditValue ?>"<?php echo $smtp->SMTP_Porta->EditAttributes() ?>>
</span>
<?php echo $smtp->SMTP_Porta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($smtp->SMTP_Usuario->Visible) { // SMTP_Usuario ?>
	<div id="r_SMTP_Usuario" class="form-group">
		<label id="elh_smtp_SMTP_Usuario" for="x_SMTP_Usuario" class="col-sm-2 control-label ewLabel"><?php echo $smtp->SMTP_Usuario->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $smtp->SMTP_Usuario->CellAttributes() ?>>
<span id="el_smtp_SMTP_Usuario">
<input type="text" data-field="x_SMTP_Usuario" name="x_SMTP_Usuario" id="x_SMTP_Usuario" size="20" maxlength="60" value="<?php echo $smtp->SMTP_Usuario->EditValue ?>"<?php echo $smtp->SMTP_Usuario->EditAttributes() ?>>
</span>
<?php echo $smtp->SMTP_Usuario->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($smtp->SMTP_Senha->Visible) { // SMTP_Senha ?>
	<div id="r_SMTP_Senha" class="form-group">
		<label id="elh_smtp_SMTP_Senha" for="x_SMTP_Senha" class="col-sm-2 control-label ewLabel"><?php echo $smtp->SMTP_Senha->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $smtp->SMTP_Senha->CellAttributes() ?>>
<span id="el_smtp_SMTP_Senha">
<input type="password" data-field="x_SMTP_Senha" name="x_SMTP_Senha" id="x_SMTP_Senha" size="20" maxlength="50"<?php echo $smtp->SMTP_Senha->EditAttributes() ?>>
</span>
<?php echo $smtp->SMTP_Senha->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($smtp->Email_de_Envio->Visible) { // Email_de_Envio ?>
	<div id="r_Email_de_Envio" class="form-group">
		<label id="elh_smtp_Email_de_Envio" for="x_Email_de_Envio" class="col-sm-2 control-label ewLabel"><?php echo $smtp->Email_de_Envio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $smtp->Email_de_Envio->CellAttributes() ?>>
<span id="el_smtp_Email_de_Envio">
<input type="text" data-field="x_Email_de_Envio" name="x_Email_de_Envio" id="x_Email_de_Envio" size="40" maxlength="65" value="<?php echo $smtp->Email_de_Envio->EditValue ?>"<?php echo $smtp->Email_de_Envio->EditAttributes() ?>>
</span>
<?php echo $smtp->Email_de_Envio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($smtp->Email_de_Recebimento->Visible) { // Email_de_Recebimento ?>
	<div id="r_Email_de_Recebimento" class="form-group">
		<label id="elh_smtp_Email_de_Recebimento" for="x_Email_de_Recebimento" class="col-sm-2 control-label ewLabel"><?php echo $smtp->Email_de_Recebimento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $smtp->Email_de_Recebimento->CellAttributes() ?>>
<span id="el_smtp_Email_de_Recebimento">
<input type="text" data-field="x_Email_de_Recebimento" name="x_Email_de_Recebimento" id="x_Email_de_Recebimento" size="40" maxlength="65" value="<?php echo $smtp->Email_de_Recebimento->EditValue ?>"<?php echo $smtp->Email_de_Recebimento->EditAttributes() ?>>
</span>
<?php echo $smtp->Email_de_Recebimento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($smtp->Seguranca->Visible) { // Seguranca ?>
	<div id="r_Seguranca" class="form-group">
		<label id="elh_smtp_Seguranca" for="x_Seguranca" class="col-sm-2 control-label ewLabel"><?php echo $smtp->Seguranca->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $smtp->Seguranca->CellAttributes() ?>>
<span id="el_smtp_Seguranca">
<select data-field="x_Seguranca" id="x_Seguranca" name="x_Seguranca"<?php echo $smtp->Seguranca->EditAttributes() ?>>
<?php
if (is_array($smtp->Seguranca->EditValue)) {
	$arwrk = $smtp->Seguranca->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($smtp->Seguranca->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span>
<?php echo $smtp->Seguranca->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton btn-success" name="btnAction" id="btnAction" type="submit"><i class="fa fa-floppy-o"></i>&nbsp;<?php echo $Language->Phrase("AddBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fsmtpadd.Init();
</script>
<?php
$smtp_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$smtp_add->Page_Terminate();
?>
