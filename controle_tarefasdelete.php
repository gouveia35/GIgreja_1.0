<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "controle_tarefasinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$controle_tarefas_delete = NULL; // Initialize page object first

class ccontrole_tarefas_delete extends ccontrole_tarefas {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{2B7992FC-5911-46A7-9310-01F4D4225C49}";

	// Table name
	var $TableName = 'controle_tarefas';

	// Page object name
	var $PageObjName = 'controle_tarefas_delete';

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

		// Table object (controle_tarefas)
		if (!isset($GLOBALS["controle_tarefas"]) || get_class($GLOBALS["controle_tarefas"]) == "ccontrole_tarefas") {
			$GLOBALS["controle_tarefas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["controle_tarefas"];
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
			define("EW_TABLE_NAME", 'controle_tarefas', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("controle_tarefaslist.php"));
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
		global $EW_EXPORT, $controle_tarefas;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($controle_tarefas);
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
			$this->Page_Terminate("controle_tarefaslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in controle_tarefas class, controle_tarefasinfo.php

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
		$this->Id_tarefas->setDbValue($rs->fields('Id_tarefas'));
		$this->Descricao->setDbValue($rs->fields('Descricao'));
		$this->DuracaoEstimada->setDbValue($rs->fields('DuracaoEstimada'));
		$this->Prioridade->setDbValue($rs->fields('Prioridade'));
		$this->Anotacoes->setDbValue($rs->fields('Anotacoes'));
		$this->Concluida_em->setDbValue($rs->fields('Concluida_em'));
		$this->Completa->setDbValue($rs->fields('Completa'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id_tarefas->DbValue = $row['Id_tarefas'];
		$this->Descricao->DbValue = $row['Descricao'];
		$this->DuracaoEstimada->DbValue = $row['DuracaoEstimada'];
		$this->Prioridade->DbValue = $row['Prioridade'];
		$this->Anotacoes->DbValue = $row['Anotacoes'];
		$this->Concluida_em->DbValue = $row['Concluida_em'];
		$this->Completa->DbValue = $row['Completa'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// Id_tarefas

		$this->Id_tarefas->CellCssStyle = "white-space: nowrap;";

		// Descricao
		// DuracaoEstimada
		// Prioridade
		// Anotacoes
		// Concluida_em
		// Completa

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// Descricao
			$this->Descricao->ViewValue = $this->Descricao->CurrentValue;
			$this->Descricao->ViewCustomAttributes = "";

			// DuracaoEstimada
			$this->DuracaoEstimada->ViewValue = $this->DuracaoEstimada->CurrentValue;
			$this->DuracaoEstimada->ViewCustomAttributes = "";

			// Prioridade
			if (strval($this->Prioridade->CurrentValue) <> "") {
				$sFilterWrk = "`Id_prior`" . ew_SearchString("=", $this->Prioridade->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `Id_prior`, `Prioridade` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `prioridade`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Prioridade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->Prioridade->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->Prioridade->ViewValue = $this->Prioridade->CurrentValue;
				}
			} else {
				$this->Prioridade->ViewValue = NULL;
			}
			$this->Prioridade->ViewCustomAttributes = "";

			// Anotacoes
			$this->Anotacoes->ViewValue = $this->Anotacoes->CurrentValue;
			$this->Anotacoes->ViewCustomAttributes = "";

			// Concluida_em
			$this->Concluida_em->ViewValue = $this->Concluida_em->CurrentValue;
			$this->Concluida_em->ViewValue = ew_FormatDateTime($this->Concluida_em->ViewValue, 7);
			$this->Concluida_em->ViewCustomAttributes = "";

			// Completa
			if (strval($this->Completa->CurrentValue) <> "") {
				$this->Completa->ViewValue = "";
				$arwrk = explode(",", strval($this->Completa->CurrentValue));
				$cnt = count($arwrk);
				for ($ari = 0; $ari < $cnt; $ari++) {
					switch (trim($arwrk[$ari])) {
						case $this->Completa->FldTagValue(1):
							$this->Completa->ViewValue .= $this->Completa->FldTagCaption(1) <> "" ? $this->Completa->FldTagCaption(1) : trim($arwrk[$ari]);
							break;
						default:
							$this->Completa->ViewValue .= trim($arwrk[$ari]);
					}
					if ($ari < $cnt-1) $this->Completa->ViewValue .= ew_ViewOptionSeparator($ari);
				}
			} else {
				$this->Completa->ViewValue = NULL;
			}
			$this->Completa->ViewCustomAttributes = "";

			// Descricao
			$this->Descricao->LinkCustomAttributes = "";
			$this->Descricao->HrefValue = "";
			$this->Descricao->TooltipValue = "";

			// Prioridade
			$this->Prioridade->LinkCustomAttributes = "";
			$this->Prioridade->HrefValue = "";
			$this->Prioridade->TooltipValue = "";

			// Concluida_em
			$this->Concluida_em->LinkCustomAttributes = "";
			$this->Concluida_em->HrefValue = "";
			$this->Concluida_em->TooltipValue = "";

			// Completa
			$this->Completa->LinkCustomAttributes = "";
			$this->Completa->HrefValue = "";
			$this->Completa->TooltipValue = "";
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
				$sThisKey .= $row['Id_tarefas'];
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
		$Breadcrumb->Add("list", $this->TableVar, "controle_tarefaslist.php", "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'controle_tarefas';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		if (!$this->AuditTrailOnDelete) return;
		$table = 'controle_tarefas';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['Id_tarefas'];

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
if (!isset($controle_tarefas_delete)) $controle_tarefas_delete = new ccontrole_tarefas_delete();

// Page init
$controle_tarefas_delete->Page_Init();

// Page main
$controle_tarefas_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$controle_tarefas_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var controle_tarefas_delete = new ew_Page("controle_tarefas_delete");
controle_tarefas_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = controle_tarefas_delete.PageID; // For backward compatibility

// Form object
var fcontrole_tarefasdelete = new ew_Form("fcontrole_tarefasdelete");

// Form_CustomValidate event
fcontrole_tarefasdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontrole_tarefasdelete.ValidateRequired = true;
<?php } else { ?>
fcontrole_tarefasdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcontrole_tarefasdelete.Lists["x_Prioridade"] = {"LinkField":"x_Id_prior","Ajax":null,"AutoFill":false,"DisplayFields":["x_Prioridade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($controle_tarefas_delete->Recordset = $controle_tarefas_delete->LoadRecordset())
	$controle_tarefas_deleteTotalRecs = $controle_tarefas_delete->Recordset->RecordCount(); // Get record count
if ($controle_tarefas_deleteTotalRecs <= 0) { // No record found, exit
	if ($controle_tarefas_delete->Recordset)
		$controle_tarefas_delete->Recordset->Close();
	$controle_tarefas_delete->Page_Terminate("controle_tarefaslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $controle_tarefas_delete->ShowPageHeader(); ?>
<?php
$controle_tarefas_delete->ShowMessage();
?>
<form name="fcontrole_tarefasdelete" id="fcontrole_tarefasdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($controle_tarefas_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $controle_tarefas_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="controle_tarefas">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($controle_tarefas_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $controle_tarefas->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($controle_tarefas->Descricao->Visible) { // Descricao ?>
		<th><span id="elh_controle_tarefas_Descricao" class="controle_tarefas_Descricao"><?php echo $controle_tarefas->Descricao->FldCaption() ?></span></th>
<?php } ?>
<?php if ($controle_tarefas->Prioridade->Visible) { // Prioridade ?>
		<th><span id="elh_controle_tarefas_Prioridade" class="controle_tarefas_Prioridade"><?php echo $controle_tarefas->Prioridade->FldCaption() ?></span></th>
<?php } ?>
<?php if ($controle_tarefas->Concluida_em->Visible) { // Concluida_em ?>
		<th><span id="elh_controle_tarefas_Concluida_em" class="controle_tarefas_Concluida_em"><?php echo $controle_tarefas->Concluida_em->FldCaption() ?></span></th>
<?php } ?>
<?php if ($controle_tarefas->Completa->Visible) { // Completa ?>
		<th><span id="elh_controle_tarefas_Completa" class="controle_tarefas_Completa"><?php echo $controle_tarefas->Completa->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$controle_tarefas_delete->RecCnt = 0;
$i = 0;
while (!$controle_tarefas_delete->Recordset->EOF) {
	$controle_tarefas_delete->RecCnt++;
	$controle_tarefas_delete->RowCnt++;

	// Set row properties
	$controle_tarefas->ResetAttrs();
	$controle_tarefas->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$controle_tarefas_delete->LoadRowValues($controle_tarefas_delete->Recordset);

	// Render row
	$controle_tarefas_delete->RenderRow();
?>
	<tr<?php echo $controle_tarefas->RowAttributes() ?>>
<?php if ($controle_tarefas->Descricao->Visible) { // Descricao ?>
		<td<?php echo $controle_tarefas->Descricao->CellAttributes() ?>>
<span id="el<?php echo $controle_tarefas_delete->RowCnt ?>_controle_tarefas_Descricao" class="form-group controle_tarefas_Descricao">
<span<?php echo $controle_tarefas->Descricao->ViewAttributes() ?>>
<?php echo $controle_tarefas->Descricao->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($controle_tarefas->Prioridade->Visible) { // Prioridade ?>
		<td<?php echo $controle_tarefas->Prioridade->CellAttributes() ?>>
<span id="el<?php echo $controle_tarefas_delete->RowCnt ?>_controle_tarefas_Prioridade" class="form-group controle_tarefas_Prioridade">
<span<?php echo $controle_tarefas->Prioridade->ViewAttributes() ?>>
<?php echo $controle_tarefas->Prioridade->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($controle_tarefas->Concluida_em->Visible) { // Concluida_em ?>
		<td<?php echo $controle_tarefas->Concluida_em->CellAttributes() ?>>
<span id="el<?php echo $controle_tarefas_delete->RowCnt ?>_controle_tarefas_Concluida_em" class="form-group controle_tarefas_Concluida_em">
<span<?php echo $controle_tarefas->Concluida_em->ViewAttributes() ?>>
<?php echo $controle_tarefas->Concluida_em->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($controle_tarefas->Completa->Visible) { // Completa ?>
		<td<?php echo $controle_tarefas->Completa->CellAttributes() ?>>
<span id="el<?php echo $controle_tarefas_delete->RowCnt ?>_controle_tarefas_Completa" class="form-group controle_tarefas_Completa">
<span<?php echo $controle_tarefas->Completa->ViewAttributes() ?>>
<?php echo $controle_tarefas->Completa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$controle_tarefas_delete->Recordset->MoveNext();
}
$controle_tarefas_delete->Recordset->Close();
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
fcontrole_tarefasdelete.Init();
</script>
<?php
$controle_tarefas_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$controle_tarefas_delete->Page_Terminate();
?>
