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

$smtp_view = NULL; // Initialize page object first

class csmtp_view extends csmtp {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{2B7992FC-5911-46A7-9310-01F4D4225C49}";

	// Table name
	var $TableName = 'smtp';

	// Page object name
	var $PageObjName = 'smtp_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["Id"] <> "") {
			$this->RecKey["Id"] = $_GET["Id"];
			$KeyUrl .= "&amp;Id=" . urlencode($this->RecKey["Id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'smtp', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("smtplist.php"));
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["Id"] <> "") {
				$this->Id->setQueryStringValue($_GET["Id"]);
				$this->RecKey["Id"] = $this->Id->QueryStringValue;
			} else {
				$sReturnUrl = "smtplist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "smtplist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "smtplist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
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

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "smtplist.php", "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($smtp_view)) $smtp_view = new csmtp_view();

// Page init
$smtp_view->Page_Init();

// Page main
$smtp_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$smtp_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var smtp_view = new ew_Page("smtp_view");
smtp_view.PageID = "view"; // Page ID
var EW_PAGE_ID = smtp_view.PageID; // For backward compatibility

// Form object
var fsmtpview = new ew_Form("fsmtpview");

// Form_CustomValidate event
fsmtpview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsmtpview.ValidateRequired = true;
<?php } else { ?>
fsmtpview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $smtp_view->ExportOptions->Render("body") ?>
<?php
	foreach ($smtp_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $smtp_view->ShowPageHeader(); ?>
<?php
$smtp_view->ShowMessage();
?>
<form name="fsmtpview" id="fsmtpview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($smtp_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $smtp_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="smtp">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($smtp->SMTP->Visible) { // SMTP ?>
	<tr id="r_SMTP">
		<td><span id="elh_smtp_SMTP"><?php echo $smtp->SMTP->FldCaption() ?></span></td>
		<td<?php echo $smtp->SMTP->CellAttributes() ?>>
<span id="el_smtp_SMTP" class="form-group">
<span<?php echo $smtp->SMTP->ViewAttributes() ?>>
<?php echo $smtp->SMTP->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($smtp->SMTP_Porta->Visible) { // SMTP_Porta ?>
	<tr id="r_SMTP_Porta">
		<td><span id="elh_smtp_SMTP_Porta"><?php echo $smtp->SMTP_Porta->FldCaption() ?></span></td>
		<td<?php echo $smtp->SMTP_Porta->CellAttributes() ?>>
<span id="el_smtp_SMTP_Porta" class="form-group">
<span<?php echo $smtp->SMTP_Porta->ViewAttributes() ?>>
<?php echo $smtp->SMTP_Porta->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($smtp->SMTP_Usuario->Visible) { // SMTP_Usuario ?>
	<tr id="r_SMTP_Usuario">
		<td><span id="elh_smtp_SMTP_Usuario"><?php echo $smtp->SMTP_Usuario->FldCaption() ?></span></td>
		<td<?php echo $smtp->SMTP_Usuario->CellAttributes() ?>>
<span id="el_smtp_SMTP_Usuario" class="form-group">
<span<?php echo $smtp->SMTP_Usuario->ViewAttributes() ?>>
<?php echo $smtp->SMTP_Usuario->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($smtp->SMTP_Senha->Visible) { // SMTP_Senha ?>
	<tr id="r_SMTP_Senha">
		<td><span id="elh_smtp_SMTP_Senha"><?php echo $smtp->SMTP_Senha->FldCaption() ?></span></td>
		<td<?php echo $smtp->SMTP_Senha->CellAttributes() ?>>
<span id="el_smtp_SMTP_Senha" class="form-group">
<span<?php echo $smtp->SMTP_Senha->ViewAttributes() ?>>
<?php echo $smtp->SMTP_Senha->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($smtp->Email_de_Envio->Visible) { // Email_de_Envio ?>
	<tr id="r_Email_de_Envio">
		<td><span id="elh_smtp_Email_de_Envio"><?php echo $smtp->Email_de_Envio->FldCaption() ?></span></td>
		<td<?php echo $smtp->Email_de_Envio->CellAttributes() ?>>
<span id="el_smtp_Email_de_Envio" class="form-group">
<span<?php echo $smtp->Email_de_Envio->ViewAttributes() ?>>
<?php echo $smtp->Email_de_Envio->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($smtp->Email_de_Recebimento->Visible) { // Email_de_Recebimento ?>
	<tr id="r_Email_de_Recebimento">
		<td><span id="elh_smtp_Email_de_Recebimento"><?php echo $smtp->Email_de_Recebimento->FldCaption() ?></span></td>
		<td<?php echo $smtp->Email_de_Recebimento->CellAttributes() ?>>
<span id="el_smtp_Email_de_Recebimento" class="form-group">
<span<?php echo $smtp->Email_de_Recebimento->ViewAttributes() ?>>
<?php echo $smtp->Email_de_Recebimento->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($smtp->Seguranca->Visible) { // Seguranca ?>
	<tr id="r_Seguranca">
		<td><span id="elh_smtp_Seguranca"><?php echo $smtp->Seguranca->FldCaption() ?></span></td>
		<td<?php echo $smtp->Seguranca->CellAttributes() ?>>
<span id="el_smtp_Seguranca" class="form-group">
<span<?php echo $smtp->Seguranca->ViewAttributes() ?>>
<?php echo $smtp->Seguranca->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fsmtpview.Init();
</script>
<?php
$smtp_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$smtp_view->Page_Terminate();
?>
