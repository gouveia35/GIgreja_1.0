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

$controle_tarefas_edit = NULL; // Initialize page object first

class ccontrole_tarefas_edit extends ccontrole_tarefas {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{2B7992FC-5911-46A7-9310-01F4D4225C49}";

	// Table name
	var $TableName = 'controle_tarefas';

	// Page object name
	var $PageObjName = 'controle_tarefas_edit';

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
	var $AuditTrailOnEdit = TRUE;

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("controle_tarefaslist.php"));
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->Id_tarefas->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["Id_tarefas"] <> "") {
			$this->Id_tarefas->setQueryStringValue($_GET["Id_tarefas"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->Id_tarefas->CurrentValue == "")
			$this->Page_Terminate("controle_tarefaslist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("controle_tarefaslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->Id_tarefas->FldIsDetailKey)
			$this->Id_tarefas->setFormValue($objForm->GetValue("x_Id_tarefas"));
		if (!$this->Descricao->FldIsDetailKey) {
			$this->Descricao->setFormValue($objForm->GetValue("x_Descricao"));
		}
		if (!$this->DuracaoEstimada->FldIsDetailKey) {
			$this->DuracaoEstimada->setFormValue($objForm->GetValue("x_DuracaoEstimada"));
		}
		if (!$this->Prioridade->FldIsDetailKey) {
			$this->Prioridade->setFormValue($objForm->GetValue("x_Prioridade"));
		}
		if (!$this->Anotacoes->FldIsDetailKey) {
			$this->Anotacoes->setFormValue($objForm->GetValue("x_Anotacoes"));
		}
		if (!$this->Concluida_em->FldIsDetailKey) {
			$this->Concluida_em->setFormValue($objForm->GetValue("x_Concluida_em"));
			$this->Concluida_em->CurrentValue = ew_UnFormatDateTime($this->Concluida_em->CurrentValue, 7);
		}
		if (!$this->Completa->FldIsDetailKey) {
			$this->Completa->setFormValue($objForm->GetValue("x_Completa"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->Id_tarefas->CurrentValue = $this->Id_tarefas->FormValue;
		$this->Descricao->CurrentValue = $this->Descricao->FormValue;
		$this->DuracaoEstimada->CurrentValue = $this->DuracaoEstimada->FormValue;
		$this->Prioridade->CurrentValue = $this->Prioridade->FormValue;
		$this->Anotacoes->CurrentValue = $this->Anotacoes->FormValue;
		$this->Concluida_em->CurrentValue = $this->Concluida_em->FormValue;
		$this->Concluida_em->CurrentValue = ew_UnFormatDateTime($this->Concluida_em->CurrentValue, 7);
		$this->Completa->CurrentValue = $this->Completa->FormValue;
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
		// Descricao
		// DuracaoEstimada
		// Prioridade
		// Anotacoes
		// Concluida_em
		// Completa

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// Id_tarefas
			$this->Id_tarefas->ViewValue = $this->Id_tarefas->CurrentValue;
			$this->Id_tarefas->ViewCustomAttributes = "";

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

			// Id_tarefas
			$this->Id_tarefas->LinkCustomAttributes = "";
			$this->Id_tarefas->HrefValue = "";
			$this->Id_tarefas->TooltipValue = "";

			// Descricao
			$this->Descricao->LinkCustomAttributes = "";
			$this->Descricao->HrefValue = "";
			$this->Descricao->TooltipValue = "";

			// DuracaoEstimada
			$this->DuracaoEstimada->LinkCustomAttributes = "";
			$this->DuracaoEstimada->HrefValue = "";
			$this->DuracaoEstimada->TooltipValue = "";

			// Prioridade
			$this->Prioridade->LinkCustomAttributes = "";
			$this->Prioridade->HrefValue = "";
			$this->Prioridade->TooltipValue = "";

			// Anotacoes
			$this->Anotacoes->LinkCustomAttributes = "";
			$this->Anotacoes->HrefValue = "";
			$this->Anotacoes->TooltipValue = "";

			// Concluida_em
			$this->Concluida_em->LinkCustomAttributes = "";
			$this->Concluida_em->HrefValue = "";
			$this->Concluida_em->TooltipValue = "";

			// Completa
			$this->Completa->LinkCustomAttributes = "";
			$this->Completa->HrefValue = "";
			$this->Completa->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// Id_tarefas
			$this->Id_tarefas->EditAttrs["class"] = "form-control";
			$this->Id_tarefas->EditCustomAttributes = "";

			// Descricao
			$this->Descricao->EditAttrs["class"] = "form-control";
			$this->Descricao->EditCustomAttributes = "";
			$this->Descricao->EditValue = ew_HtmlEncode($this->Descricao->CurrentValue);

			// DuracaoEstimada
			$this->DuracaoEstimada->EditAttrs["class"] = "form-control";
			$this->DuracaoEstimada->EditCustomAttributes = "";
			$this->DuracaoEstimada->EditValue = ew_HtmlEncode($this->DuracaoEstimada->CurrentValue);

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

			// Anotacoes
			$this->Anotacoes->EditAttrs["class"] = "form-control";
			$this->Anotacoes->EditCustomAttributes = "";
			$this->Anotacoes->EditValue = ew_HtmlEncode($this->Anotacoes->CurrentValue);

			// Concluida_em
			$this->Concluida_em->EditAttrs["class"] = "form-control";
			$this->Concluida_em->EditCustomAttributes = "";
			$this->Concluida_em->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->Concluida_em->CurrentValue, 7));

			// Completa
			$this->Completa->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Completa->FldTagValue(1), $this->Completa->FldTagCaption(1) <> "" ? $this->Completa->FldTagCaption(1) : $this->Completa->FldTagValue(1));
			$this->Completa->EditValue = $arwrk;

			// Edit refer script
			// Id_tarefas

			$this->Id_tarefas->HrefValue = "";

			// Descricao
			$this->Descricao->HrefValue = "";

			// DuracaoEstimada
			$this->DuracaoEstimada->HrefValue = "";

			// Prioridade
			$this->Prioridade->HrefValue = "";

			// Anotacoes
			$this->Anotacoes->HrefValue = "";

			// Concluida_em
			$this->Concluida_em->HrefValue = "";

			// Completa
			$this->Completa->HrefValue = "";
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
		if (!$this->Descricao->FldIsDetailKey && !is_null($this->Descricao->FormValue) && $this->Descricao->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Descricao->FldCaption(), $this->Descricao->ReqErrMsg));
		}
		if (!$this->DuracaoEstimada->FldIsDetailKey && !is_null($this->DuracaoEstimada->FormValue) && $this->DuracaoEstimada->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->DuracaoEstimada->FldCaption(), $this->DuracaoEstimada->ReqErrMsg));
		}
		if ($this->Prioridade->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Prioridade->FldCaption(), $this->Prioridade->ReqErrMsg));
		}
		if (!$this->Anotacoes->FldIsDetailKey && !is_null($this->Anotacoes->FormValue) && $this->Anotacoes->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Anotacoes->FldCaption(), $this->Anotacoes->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->Concluida_em->FormValue)) {
			ew_AddMessage($gsFormError, $this->Concluida_em->FldErrMsg());
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

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// Descricao
			$this->Descricao->SetDbValueDef($rsnew, $this->Descricao->CurrentValue, NULL, $this->Descricao->ReadOnly);

			// DuracaoEstimada
			$this->DuracaoEstimada->SetDbValueDef($rsnew, $this->DuracaoEstimada->CurrentValue, NULL, $this->DuracaoEstimada->ReadOnly);

			// Prioridade
			$this->Prioridade->SetDbValueDef($rsnew, $this->Prioridade->CurrentValue, NULL, $this->Prioridade->ReadOnly);

			// Anotacoes
			$this->Anotacoes->SetDbValueDef($rsnew, $this->Anotacoes->CurrentValue, NULL, $this->Anotacoes->ReadOnly);

			// Concluida_em
			$this->Concluida_em->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->Concluida_em->CurrentValue, 7), NULL, $this->Concluida_em->ReadOnly);

			// Completa
			$this->Completa->SetDbValueDef($rsnew, $this->Completa->CurrentValue, NULL, $this->Completa->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		if ($EditRow) {
			$this->WriteAuditTrailOnEdit($rsold, $rsnew);
		}
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "controle_tarefaslist.php", "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'controle_tarefas';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'controle_tarefas';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['Id_tarefas'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $usr = CurrentUserID();
		foreach (array_keys($rsnew) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
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
if (!isset($controle_tarefas_edit)) $controle_tarefas_edit = new ccontrole_tarefas_edit();

// Page init
$controle_tarefas_edit->Page_Init();

// Page main
$controle_tarefas_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$controle_tarefas_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var controle_tarefas_edit = new ew_Page("controle_tarefas_edit");
controle_tarefas_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = controle_tarefas_edit.PageID; // For backward compatibility

// Form object
var fcontrole_tarefasedit = new ew_Form("fcontrole_tarefasedit");

// Validate form
fcontrole_tarefasedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_Descricao");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $controle_tarefas->Descricao->FldCaption(), $controle_tarefas->Descricao->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_DuracaoEstimada");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $controle_tarefas->DuracaoEstimada->FldCaption(), $controle_tarefas->DuracaoEstimada->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Prioridade");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $controle_tarefas->Prioridade->FldCaption(), $controle_tarefas->Prioridade->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Anotacoes");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $controle_tarefas->Anotacoes->FldCaption(), $controle_tarefas->Anotacoes->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Concluida_em");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($controle_tarefas->Concluida_em->FldErrMsg()) ?>");

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
fcontrole_tarefasedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontrole_tarefasedit.ValidateRequired = true;
<?php } else { ?>
fcontrole_tarefasedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcontrole_tarefasedit.Lists["x_Prioridade"] = {"LinkField":"x_Id_prior","Ajax":null,"AutoFill":false,"DisplayFields":["x_Prioridade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $controle_tarefas_edit->ShowPageHeader(); ?>
<?php
$controle_tarefas_edit->ShowMessage();
?>
<form name="fcontrole_tarefasedit" id="fcontrole_tarefasedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($controle_tarefas_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $controle_tarefas_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="controle_tarefas">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($controle_tarefas->Descricao->Visible) { // Descricao ?>
	<div id="r_Descricao" class="form-group">
		<label id="elh_controle_tarefas_Descricao" for="x_Descricao" class="col-sm-2 control-label ewLabel"><?php echo $controle_tarefas->Descricao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $controle_tarefas->Descricao->CellAttributes() ?>>
<span id="el_controle_tarefas_Descricao">
<input type="text" data-field="x_Descricao" name="x_Descricao" id="x_Descricao" size="80" maxlength="100" value="<?php echo $controle_tarefas->Descricao->EditValue ?>"<?php echo $controle_tarefas->Descricao->EditAttributes() ?>>
</span>
<?php echo $controle_tarefas->Descricao->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($controle_tarefas->DuracaoEstimada->Visible) { // DuracaoEstimada ?>
	<div id="r_DuracaoEstimada" class="form-group">
		<label id="elh_controle_tarefas_DuracaoEstimada" for="x_DuracaoEstimada" class="col-sm-2 control-label ewLabel"><?php echo $controle_tarefas->DuracaoEstimada->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $controle_tarefas->DuracaoEstimada->CellAttributes() ?>>
<span id="el_controle_tarefas_DuracaoEstimada">
<input type="text" data-field="x_DuracaoEstimada" name="x_DuracaoEstimada" id="x_DuracaoEstimada" size="30" maxlength="50" value="<?php echo $controle_tarefas->DuracaoEstimada->EditValue ?>"<?php echo $controle_tarefas->DuracaoEstimada->EditAttributes() ?>>
</span>
<?php echo $controle_tarefas->DuracaoEstimada->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($controle_tarefas->Prioridade->Visible) { // Prioridade ?>
	<div id="r_Prioridade" class="form-group">
		<label id="elh_controle_tarefas_Prioridade" class="col-sm-2 control-label ewLabel"><?php echo $controle_tarefas->Prioridade->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $controle_tarefas->Prioridade->CellAttributes() ?>>
<span id="el_controle_tarefas_Prioridade">
<div id="tp_x_Prioridade" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_Prioridade" id="x_Prioridade" value="{value}"<?php echo $controle_tarefas->Prioridade->EditAttributes() ?>></div>
<div id="dsl_x_Prioridade" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $controle_tarefas->Prioridade->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($controle_tarefas->Prioridade->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_Prioridade" name="x_Prioridade" id="x_Prioridade_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $controle_tarefas->Prioridade->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
<script type="text/javascript">
fcontrole_tarefasedit.Lists["x_Prioridade"].Options = <?php echo (is_array($controle_tarefas->Prioridade->EditValue)) ? ew_ArrayToJson($controle_tarefas->Prioridade->EditValue, 0) : "[]" ?>;
</script>
</span>
<?php echo $controle_tarefas->Prioridade->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($controle_tarefas->Anotacoes->Visible) { // Anotacoes ?>
	<div id="r_Anotacoes" class="form-group">
		<label id="elh_controle_tarefas_Anotacoes" for="x_Anotacoes" class="col-sm-2 control-label ewLabel"><?php echo $controle_tarefas->Anotacoes->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $controle_tarefas->Anotacoes->CellAttributes() ?>>
<span id="el_controle_tarefas_Anotacoes">
<textarea data-field="x_Anotacoes" name="x_Anotacoes" id="x_Anotacoes" cols="70" rows="4"<?php echo $controle_tarefas->Anotacoes->EditAttributes() ?>><?php echo $controle_tarefas->Anotacoes->EditValue ?></textarea>
</span>
<?php echo $controle_tarefas->Anotacoes->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($controle_tarefas->Concluida_em->Visible) { // Concluida_em ?>
	<div id="r_Concluida_em" class="form-group">
		<label id="elh_controle_tarefas_Concluida_em" for="x_Concluida_em" class="col-sm-2 control-label ewLabel"><?php echo $controle_tarefas->Concluida_em->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $controle_tarefas->Concluida_em->CellAttributes() ?>>
<span id="el_controle_tarefas_Concluida_em">
<input type="text" data-field="x_Concluida_em" name="x_Concluida_em" id="x_Concluida_em" size="12" value="<?php echo $controle_tarefas->Concluida_em->EditValue ?>"<?php echo $controle_tarefas->Concluida_em->EditAttributes() ?>>
<?php if (!$controle_tarefas->Concluida_em->ReadOnly && !$controle_tarefas->Concluida_em->Disabled && @$controle_tarefas->Concluida_em->EditAttrs["readonly"] == "" && @$controle_tarefas->Concluida_em->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fcontrole_tarefasedit", "x_Concluida_em", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $controle_tarefas->Concluida_em->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($controle_tarefas->Completa->Visible) { // Completa ?>
	<div id="r_Completa" class="form-group">
		<label id="elh_controle_tarefas_Completa" class="col-sm-2 control-label ewLabel"><?php echo $controle_tarefas->Completa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $controle_tarefas->Completa->CellAttributes() ?>>
<span id="el_controle_tarefas_Completa">
<div id="tp_x_Completa" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x_Completa[]" id="x_Completa[]" value="{value}"<?php echo $controle_tarefas->Completa->EditAttributes() ?>></div>
<div id="dsl_x_Completa" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $controle_tarefas->Completa->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($controle_tarefas->Completa->CurrentValue));
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
<label class="checkbox-inline"><input type="checkbox" data-field="x_Completa" name="x_Completa[]" id="x_Completa_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $controle_tarefas->Completa->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $controle_tarefas->Completa->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<span id="el_controle_tarefas_Id_tarefas">
<input type="hidden" data-field="x_Id_tarefas" name="x_Id_tarefas" id="x_Id_tarefas" value="<?php echo ew_HtmlEncode($controle_tarefas->Id_tarefas->CurrentValue) ?>">
</span>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><i class="fa fa-floppy-o"></i>&nbsp;<?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fcontrole_tarefasedit.Init();
</script>
<?php
$controle_tarefas_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$controle_tarefas_edit->Page_Terminate();
?>
