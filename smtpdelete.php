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

$smtp_delete = NULL; // Initialize page object first

class csmtp_delete extends csmtp {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{2B7992FC-5911-46A7-9310-01F4D4225C49}";

	// Table name
	var $TableName = 'smtp';

	// Page object name
	var $PageObjName = 'smtp_delete';

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
	var $AuditTrailOnDelete = TRUE;

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("smtplist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in smtp class, smtpinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Load List page SQL
		$sSql = $this->SelectSQL();

		// Load recordset
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
		$conn->raiseErrorFn = '';

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// Id

		$this->Id->CellCssStyle = "white-space: nowrap;";

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

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();
		if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteBegin")); // Batch delete begin

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['Id'];
				$this->LoadDbValues($row);
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
			if ($DeleteRows) {
				foreach ($rsold as $row)
					$this->WriteAuditTrailOnDelete($row);
			}
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
			$conn->RollbackTrans(); // Rollback changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteRollback")); // Batch delete rollback
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "smtplist.php", "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'smtp';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		if (!$this->AuditTrailOnDelete) return;
		$table = 'smtp';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['Id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $curUser = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
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
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($smtp_delete)) $smtp_delete = new csmtp_delete();

// Page init
$smtp_delete->Page_Init();

// Page main
$smtp_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$smtp_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var smtp_delete = new ew_Page("smtp_delete");
smtp_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = smtp_delete.PageID; // For backward compatibility

// Form object
var fsmtpdelete = new ew_Form("fsmtpdelete");

// Form_CustomValidate event
fsmtpdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsmtpdelete.ValidateRequired = true;
<?php } else { ?>
fsmtpdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($smtp_delete->Recordset = $smtp_delete->LoadRecordset())
	$smtp_deleteTotalRecs = $smtp_delete->Recordset->RecordCount(); // Get record count
if ($smtp_deleteTotalRecs <= 0) { // No record found, exit
	if ($smtp_delete->Recordset)
		$smtp_delete->Recordset->Close();
	$smtp_delete->Page_Terminate("smtplist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $smtp_delete->ShowPageHeader(); ?>
<?php
$smtp_delete->ShowMessage();
?>
<form name="fsmtpdelete" id="fsmtpdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($smtp_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $smtp_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="smtp">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($smtp_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $smtp->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($smtp->SMTP->Visible) { // SMTP ?>
		<th><span id="elh_smtp_SMTP" class="smtp_SMTP"><?php echo $smtp->SMTP->FldCaption() ?></span></th>
<?php } ?>
<?php if ($smtp->SMTP_Porta->Visible) { // SMTP_Porta ?>
		<th><span id="elh_smtp_SMTP_Porta" class="smtp_SMTP_Porta"><?php echo $smtp->SMTP_Porta->FldCaption() ?></span></th>
<?php } ?>
<?php if ($smtp->SMTP_Usuario->Visible) { // SMTP_Usuario ?>
		<th><span id="elh_smtp_SMTP_Usuario" class="smtp_SMTP_Usuario"><?php echo $smtp->SMTP_Usuario->FldCaption() ?></span></th>
<?php } ?>
<?php if ($smtp->SMTP_Senha->Visible) { // SMTP_Senha ?>
		<th><span id="elh_smtp_SMTP_Senha" class="smtp_SMTP_Senha"><?php echo $smtp->SMTP_Senha->FldCaption() ?></span></th>
<?php } ?>
<?php if ($smtp->Email_de_Envio->Visible) { // Email_de_Envio ?>
		<th><span id="elh_smtp_Email_de_Envio" class="smtp_Email_de_Envio"><?php echo $smtp->Email_de_Envio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($smtp->Email_de_Recebimento->Visible) { // Email_de_Recebimento ?>
		<th><span id="elh_smtp_Email_de_Recebimento" class="smtp_Email_de_Recebimento"><?php echo $smtp->Email_de_Recebimento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($smtp->Seguranca->Visible) { // Seguranca ?>
		<th><span id="elh_smtp_Seguranca" class="smtp_Seguranca"><?php echo $smtp->Seguranca->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$smtp_delete->RecCnt = 0;
$i = 0;
while (!$smtp_delete->Recordset->EOF) {
	$smtp_delete->RecCnt++;
	$smtp_delete->RowCnt++;

	// Set row properties
	$smtp->ResetAttrs();
	$smtp->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$smtp_delete->LoadRowValues($smtp_delete->Recordset);

	// Render row
	$smtp_delete->RenderRow();
?>
	<tr<?php echo $smtp->RowAttributes() ?>>
<?php if ($smtp->SMTP->Visible) { // SMTP ?>
		<td<?php echo $smtp->SMTP->CellAttributes() ?>>
<span id="el<?php echo $smtp_delete->RowCnt ?>_smtp_SMTP" class="form-group smtp_SMTP">
<span<?php echo $smtp->SMTP->ViewAttributes() ?>>
<?php echo $smtp->SMTP->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($smtp->SMTP_Porta->Visible) { // SMTP_Porta ?>
		<td<?php echo $smtp->SMTP_Porta->CellAttributes() ?>>
<span id="el<?php echo $smtp_delete->RowCnt ?>_smtp_SMTP_Porta" class="form-group smtp_SMTP_Porta">
<span<?php echo $smtp->SMTP_Porta->ViewAttributes() ?>>
<?php echo $smtp->SMTP_Porta->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($smtp->SMTP_Usuario->Visible) { // SMTP_Usuario ?>
		<td<?php echo $smtp->SMTP_Usuario->CellAttributes() ?>>
<span id="el<?php echo $smtp_delete->RowCnt ?>_smtp_SMTP_Usuario" class="form-group smtp_SMTP_Usuario">
<span<?php echo $smtp->SMTP_Usuario->ViewAttributes() ?>>
<?php echo $smtp->SMTP_Usuario->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($smtp->SMTP_Senha->Visible) { // SMTP_Senha ?>
		<td<?php echo $smtp->SMTP_Senha->CellAttributes() ?>>
<span id="el<?php echo $smtp_delete->RowCnt ?>_smtp_SMTP_Senha" class="form-group smtp_SMTP_Senha">
<span<?php echo $smtp->SMTP_Senha->ViewAttributes() ?>>
<?php echo $smtp->SMTP_Senha->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($smtp->Email_de_Envio->Visible) { // Email_de_Envio ?>
		<td<?php echo $smtp->Email_de_Envio->CellAttributes() ?>>
<span id="el<?php echo $smtp_delete->RowCnt ?>_smtp_Email_de_Envio" class="form-group smtp_Email_de_Envio">
<span<?php echo $smtp->Email_de_Envio->ViewAttributes() ?>>
<?php echo $smtp->Email_de_Envio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($smtp->Email_de_Recebimento->Visible) { // Email_de_Recebimento ?>
		<td<?php echo $smtp->Email_de_Recebimento->CellAttributes() ?>>
<span id="el<?php echo $smtp_delete->RowCnt ?>_smtp_Email_de_Recebimento" class="form-group smtp_Email_de_Recebimento">
<span<?php echo $smtp->Email_de_Recebimento->ViewAttributes() ?>>
<?php echo $smtp->Email_de_Recebimento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($smtp->Seguranca->Visible) { // Seguranca ?>
		<td<?php echo $smtp->Seguranca->CellAttributes() ?>>
<span id="el<?php echo $smtp_delete->RowCnt ?>_smtp_Seguranca" class="form-group smtp_Seguranca">
<span<?php echo $smtp->Seguranca->ViewAttributes() ?>>
<?php echo $smtp->Seguranca->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$smtp_delete->Recordset->MoveNext();
}
$smtp_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton btn-danger" name="btnAction" id="btnAction" type="submit"><i class="glyphicon glyphicon-trash"></i>&nbsp;<?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fsmtpdelete.Init();
</script>
<?php
$smtp_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$smtp_delete->Page_Terminate();
?>
