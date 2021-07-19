<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "plano_oracaoinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$plano_oracao_add = NULL; // Initialize page object first

class cplano_oracao_add extends cplano_oracao {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{2B7992FC-5911-46A7-9310-01F4D4225C49}";

	// Table name
	var $TableName = 'plano_oracao';

	// Page object name
	var $PageObjName = 'plano_oracao_add';

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

		// Table object (plano_oracao)
		if (!isset($GLOBALS["plano_oracao"]) || get_class($GLOBALS["plano_oracao"]) == "cplano_oracao") {
			$GLOBALS["plano_oracao"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["plano_oracao"];
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
			define("EW_TABLE_NAME", 'plano_oracao', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("plano_oracaolist.php"));
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
		global $EW_EXPORT, $plano_oracao;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($plano_oracao);
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
			if (@$_GET["Id_ora"] != "") {
				$this->Id_ora->setQueryStringValue($_GET["Id_ora"]);
				$this->setKey("Id_ora", $this->Id_ora->CurrentValue); // Set up key
			} else {
				$this->setKey("Id_ora", ""); // Clear key
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
					$this->Page_Terminate("plano_oracaolist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "plano_oracaoview.php")
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
		$this->Motivo_da_Oracao->CurrentValue = NULL;
		$this->Motivo_da_Oracao->OldValue = $this->Motivo_da_Oracao->CurrentValue;
		$this->Anotacoes->CurrentValue = NULL;
		$this->Anotacoes->OldValue = $this->Anotacoes->CurrentValue;
		$this->Prioridade->CurrentValue = NULL;
		$this->Prioridade->OldValue = $this->Prioridade->CurrentValue;
		$this->Plano_p_todos->CurrentValue = NULL;
		$this->Plano_p_todos->OldValue = $this->Plano_p_todos->CurrentValue;
		$this->Oracao_feita->CurrentValue = NULL;
		$this->Oracao_feita->OldValue = $this->Oracao_feita->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->Motivo_da_Oracao->FldIsDetailKey) {
			$this->Motivo_da_Oracao->setFormValue($objForm->GetValue("x_Motivo_da_Oracao"));
		}
		if (!$this->Anotacoes->FldIsDetailKey) {
			$this->Anotacoes->setFormValue($objForm->GetValue("x_Anotacoes"));
		}
		if (!$this->Prioridade->FldIsDetailKey) {
			$this->Prioridade->setFormValue($objForm->GetValue("x_Prioridade"));
		}
		if (!$this->Plano_p_todos->FldIsDetailKey) {
			$this->Plano_p_todos->setFormValue($objForm->GetValue("x_Plano_p_todos"));
		}
		if (!$this->Oracao_feita->FldIsDetailKey) {
			$this->Oracao_feita->setFormValue($objForm->GetValue("x_Oracao_feita"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->Motivo_da_Oracao->CurrentValue = $this->Motivo_da_Oracao->FormValue;
		$this->Anotacoes->CurrentValue = $this->Anotacoes->FormValue;
		$this->Prioridade->CurrentValue = $this->Prioridade->FormValue;
		$this->Plano_p_todos->CurrentValue = $this->Plano_p_todos->FormValue;
		$this->Oracao_feita->CurrentValue = $this->Oracao_feita->FormValue;
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
		$this->Id_ora->setDbValue($rs->fields('Id_ora'));
		$this->Motivo_da_Oracao->setDbValue($rs->fields('Motivo_da_Oracao'));
		$this->Anotacoes->setDbValue($rs->fields('Anotacoes'));
		$this->Prioridade->setDbValue($rs->fields('Prioridade'));
		$this->Plano_p_todos->setDbValue($rs->fields('Plano_p_todos'));
		$this->Oracao_feita->setDbValue($rs->fields('Oracao_feita'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id_ora->DbValue = $row['Id_ora'];
		$this->Motivo_da_Oracao->DbValue = $row['Motivo_da_Oracao'];
		$this->Anotacoes->DbValue = $row['Anotacoes'];
		$this->Prioridade->DbValue = $row['Prioridade'];
		$this->Plano_p_todos->DbValue = $row['Plano_p_todos'];
		$this->Oracao_feita->DbValue = $row['Oracao_feita'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("Id_ora")) <> "")
			$this->Id_ora->CurrentValue = $this->getKey("Id_ora"); // Id_ora
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
		// Id_ora
		// Motivo_da_Oracao
		// Anotacoes
		// Prioridade
		// Plano_p_todos
		// Oracao_feita

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// Motivo_da_Oracao
			$this->Motivo_da_Oracao->ViewValue = $this->Motivo_da_Oracao->CurrentValue;
			$this->Motivo_da_Oracao->ViewCustomAttributes = "";

			// Anotacoes
			$this->Anotacoes->ViewValue = $this->Anotacoes->CurrentValue;
			$this->Anotacoes->ViewCustomAttributes = "";

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

			// Plano_p_todos
			if (strval($this->Plano_p_todos->CurrentValue) <> "") {
				$this->Plano_p_todos->ViewValue = "";
				$arwrk = explode(",", strval($this->Plano_p_todos->CurrentValue));
				$cnt = count($arwrk);
				for ($ari = 0; $ari < $cnt; $ari++) {
					switch (trim($arwrk[$ari])) {
						case $this->Plano_p_todos->FldTagValue(1):
							$this->Plano_p_todos->ViewValue .= $this->Plano_p_todos->FldTagCaption(1) <> "" ? $this->Plano_p_todos->FldTagCaption(1) : trim($arwrk[$ari]);
							break;
						default:
							$this->Plano_p_todos->ViewValue .= trim($arwrk[$ari]);
					}
					if ($ari < $cnt-1) $this->Plano_p_todos->ViewValue .= ew_ViewOptionSeparator($ari);
				}
			} else {
				$this->Plano_p_todos->ViewValue = NULL;
			}
			$this->Plano_p_todos->ViewCustomAttributes = "";

			// Oracao_feita
			if (strval($this->Oracao_feita->CurrentValue) <> "") {
				$this->Oracao_feita->ViewValue = "";
				$arwrk = explode(",", strval($this->Oracao_feita->CurrentValue));
				$cnt = count($arwrk);
				for ($ari = 0; $ari < $cnt; $ari++) {
					switch (trim($arwrk[$ari])) {
						case $this->Oracao_feita->FldTagValue(1):
							$this->Oracao_feita->ViewValue .= $this->Oracao_feita->FldTagCaption(1) <> "" ? $this->Oracao_feita->FldTagCaption(1) : trim($arwrk[$ari]);
							break;
						default:
							$this->Oracao_feita->ViewValue .= trim($arwrk[$ari]);
					}
					if ($ari < $cnt-1) $this->Oracao_feita->ViewValue .= ew_ViewOptionSeparator($ari);
				}
			} else {
				$this->Oracao_feita->ViewValue = NULL;
			}
			$this->Oracao_feita->ViewCustomAttributes = "";

			// Motivo_da_Oracao
			$this->Motivo_da_Oracao->LinkCustomAttributes = "";
			$this->Motivo_da_Oracao->HrefValue = "";
			$this->Motivo_da_Oracao->TooltipValue = "";

			// Anotacoes
			$this->Anotacoes->LinkCustomAttributes = "";
			$this->Anotacoes->HrefValue = "";
			$this->Anotacoes->TooltipValue = "";

			// Prioridade
			$this->Prioridade->LinkCustomAttributes = "";
			$this->Prioridade->HrefValue = "";
			$this->Prioridade->TooltipValue = "";

			// Plano_p_todos
			$this->Plano_p_todos->LinkCustomAttributes = "";
			$this->Plano_p_todos->HrefValue = "";
			$this->Plano_p_todos->TooltipValue = "";

			// Oracao_feita
			$this->Oracao_feita->LinkCustomAttributes = "";
			$this->Oracao_feita->HrefValue = "";
			$this->Oracao_feita->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// Motivo_da_Oracao
			$this->Motivo_da_Oracao->EditAttrs["class"] = "form-control";
			$this->Motivo_da_Oracao->EditCustomAttributes = "";
			$this->Motivo_da_Oracao->EditValue = ew_HtmlEncode($this->Motivo_da_Oracao->CurrentValue);

			// Anotacoes
			$this->Anotacoes->EditAttrs["class"] = "form-control";
			$this->Anotacoes->EditCustomAttributes = "";
			$this->Anotacoes->EditValue = ew_HtmlEncode($this->Anotacoes->CurrentValue);

			// Prioridade
			$this->Prioridade->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `Id_prior`, `Prioridade` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `prioridade`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Prioridade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Prioridade->EditValue = $arwrk;

			// Plano_p_todos
			$this->Plano_p_todos->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Plano_p_todos->FldTagValue(1), $this->Plano_p_todos->FldTagCaption(1) <> "" ? $this->Plano_p_todos->FldTagCaption(1) : $this->Plano_p_todos->FldTagValue(1));
			$this->Plano_p_todos->EditValue = $arwrk;

			// Oracao_feita
			$this->Oracao_feita->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Oracao_feita->FldTagValue(1), $this->Oracao_feita->FldTagCaption(1) <> "" ? $this->Oracao_feita->FldTagCaption(1) : $this->Oracao_feita->FldTagValue(1));
			$this->Oracao_feita->EditValue = $arwrk;

			// Edit refer script
			// Motivo_da_Oracao

			$this->Motivo_da_Oracao->HrefValue = "";

			// Anotacoes
			$this->Anotacoes->HrefValue = "";

			// Prioridade
			$this->Prioridade->HrefValue = "";

			// Plano_p_todos
			$this->Plano_p_todos->HrefValue = "";

			// Oracao_feita
			$this->Oracao_feita->HrefValue = "";
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
		if (!$this->Motivo_da_Oracao->FldIsDetailKey && !is_null($this->Motivo_da_Oracao->FormValue) && $this->Motivo_da_Oracao->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Motivo_da_Oracao->FldCaption(), $this->Motivo_da_Oracao->ReqErrMsg));
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

		// Motivo_da_Oracao
		$this->Motivo_da_Oracao->SetDbValueDef($rsnew, $this->Motivo_da_Oracao->CurrentValue, NULL, FALSE);

		// Anotacoes
		$this->Anotacoes->SetDbValueDef($rsnew, $this->Anotacoes->CurrentValue, NULL, FALSE);

		// Prioridade
		$this->Prioridade->SetDbValueDef($rsnew, $this->Prioridade->CurrentValue, NULL, FALSE);

		// Plano_p_todos
		$this->Plano_p_todos->SetDbValueDef($rsnew, $this->Plano_p_todos->CurrentValue, NULL, FALSE);

		// Oracao_feita
		$this->Oracao_feita->SetDbValueDef($rsnew, $this->Oracao_feita->CurrentValue, NULL, FALSE);

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
			$this->Id_ora->setDbValue($conn->Insert_ID());
			$rsnew['Id_ora'] = $this->Id_ora->DbValue;
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
		$Breadcrumb->Add("list", $this->TableVar, "plano_oracaolist.php", "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'plano_oracao';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'plano_oracao';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['Id_ora'];

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
if (!isset($plano_oracao_add)) $plano_oracao_add = new cplano_oracao_add();

// Page init
$plano_oracao_add->Page_Init();

// Page main
$plano_oracao_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$plano_oracao_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var plano_oracao_add = new ew_Page("plano_oracao_add");
plano_oracao_add.PageID = "add"; // Page ID
var EW_PAGE_ID = plano_oracao_add.PageID; // For backward compatibility

// Form object
var fplano_oracaoadd = new ew_Form("fplano_oracaoadd");

// Validate form
fplano_oracaoadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_Motivo_da_Oracao");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $plano_oracao->Motivo_da_Oracao->FldCaption(), $plano_oracao->Motivo_da_Oracao->ReqErrMsg)) ?>");

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
fplano_oracaoadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fplano_oracaoadd.ValidateRequired = true;
<?php } else { ?>
fplano_oracaoadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fplano_oracaoadd.Lists["x_Prioridade"] = {"LinkField":"x_Id_prior","Ajax":null,"AutoFill":false,"DisplayFields":["x_Prioridade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $plano_oracao_add->ShowPageHeader(); ?>
<?php
$plano_oracao_add->ShowMessage();
?>
<form name="fplano_oracaoadd" id="fplano_oracaoadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($plano_oracao_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $plano_oracao_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="plano_oracao">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($plano_oracao->Motivo_da_Oracao->Visible) { // Motivo_da_Oracao ?>
	<div id="r_Motivo_da_Oracao" class="form-group">
		<label id="elh_plano_oracao_Motivo_da_Oracao" for="x_Motivo_da_Oracao" class="col-sm-2 control-label ewLabel"><?php echo $plano_oracao->Motivo_da_Oracao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $plano_oracao->Motivo_da_Oracao->CellAttributes() ?>>
<span id="el_plano_oracao_Motivo_da_Oracao">
<input type="text" data-field="x_Motivo_da_Oracao" name="x_Motivo_da_Oracao" id="x_Motivo_da_Oracao" size="65" maxlength="100" value="<?php echo $plano_oracao->Motivo_da_Oracao->EditValue ?>"<?php echo $plano_oracao->Motivo_da_Oracao->EditAttributes() ?>>
</span>
<?php echo $plano_oracao->Motivo_da_Oracao->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($plano_oracao->Anotacoes->Visible) { // Anotacoes ?>
	<div id="r_Anotacoes" class="form-group">
		<label id="elh_plano_oracao_Anotacoes" for="x_Anotacoes" class="col-sm-2 control-label ewLabel"><?php echo $plano_oracao->Anotacoes->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $plano_oracao->Anotacoes->CellAttributes() ?>>
<span id="el_plano_oracao_Anotacoes">
<textarea data-field="x_Anotacoes" name="x_Anotacoes" id="x_Anotacoes" cols="70" rows="5"<?php echo $plano_oracao->Anotacoes->EditAttributes() ?>><?php echo $plano_oracao->Anotacoes->EditValue ?></textarea>
</span>
<?php echo $plano_oracao->Anotacoes->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($plano_oracao->Prioridade->Visible) { // Prioridade ?>
	<div id="r_Prioridade" class="form-group">
		<label id="elh_plano_oracao_Prioridade" class="col-sm-2 control-label ewLabel"><?php echo $plano_oracao->Prioridade->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $plano_oracao->Prioridade->CellAttributes() ?>>
<span id="el_plano_oracao_Prioridade">
<div id="tp_x_Prioridade" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_Prioridade" id="x_Prioridade" value="{value}"<?php echo $plano_oracao->Prioridade->EditAttributes() ?>></div>
<div id="dsl_x_Prioridade" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $plano_oracao->Prioridade->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($plano_oracao->Prioridade->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_Prioridade" name="x_Prioridade" id="x_Prioridade_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $plano_oracao->Prioridade->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
<script type="text/javascript">
fplano_oracaoadd.Lists["x_Prioridade"].Options = <?php echo (is_array($plano_oracao->Prioridade->EditValue)) ? ew_ArrayToJson($plano_oracao->Prioridade->EditValue, 0) : "[]" ?>;
</script>
</span>
<?php echo $plano_oracao->Prioridade->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($plano_oracao->Plano_p_todos->Visible) { // Plano_p_todos ?>
	<div id="r_Plano_p_todos" class="form-group">
		<label id="elh_plano_oracao_Plano_p_todos" class="col-sm-2 control-label ewLabel"><?php echo $plano_oracao->Plano_p_todos->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $plano_oracao->Plano_p_todos->CellAttributes() ?>>
<span id="el_plano_oracao_Plano_p_todos">
<div id="tp_x_Plano_p_todos" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x_Plano_p_todos[]" id="x_Plano_p_todos[]" value="{value}"<?php echo $plano_oracao->Plano_p_todos->EditAttributes() ?>></div>
<div id="dsl_x_Plano_p_todos" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $plano_oracao->Plano_p_todos->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($plano_oracao->Plano_p_todos->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				$selwrk = " checked=\"checked\"";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox-inline"><input type="checkbox" data-field="x_Plano_p_todos" name="x_Plano_p_todos[]" id="x_Plano_p_todos_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $plano_oracao->Plano_p_todos->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $plano_oracao->Plano_p_todos->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($plano_oracao->Oracao_feita->Visible) { // Oracao_feita ?>
	<div id="r_Oracao_feita" class="form-group">
		<label id="elh_plano_oracao_Oracao_feita" class="col-sm-2 control-label ewLabel"><?php echo $plano_oracao->Oracao_feita->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $plano_oracao->Oracao_feita->CellAttributes() ?>>
<span id="el_plano_oracao_Oracao_feita">
<div id="tp_x_Oracao_feita" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x_Oracao_feita[]" id="x_Oracao_feita[]" value="{value}"<?php echo $plano_oracao->Oracao_feita->EditAttributes() ?>></div>
<div id="dsl_x_Oracao_feita" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $plano_oracao->Oracao_feita->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($plano_oracao->Oracao_feita->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				$selwrk = " checked=\"checked\"";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox-inline"><input type="checkbox" data-field="x_Oracao_feita" name="x_Oracao_feita[]" id="x_Oracao_feita_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $plano_oracao->Oracao_feita->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $plano_oracao->Oracao_feita->CustomMsg ?></div></div>
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
fplano_oracaoadd.Init();
</script>
<?php
$plano_oracao_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$plano_oracao_add->Page_Terminate();
?>
