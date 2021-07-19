<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "dizimosinfo.php" ?>
<?php include_once "membroinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$dizimos_edit = NULL; // Initialize page object first

class cdizimos_edit extends cdizimos {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{2B7992FC-5911-46A7-9310-01F4D4225C49}";

	// Table name
	var $TableName = 'dizimos';

	// Page object name
	var $PageObjName = 'dizimos_edit';

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

		// Table object (dizimos)
		if (!isset($GLOBALS["dizimos"]) || get_class($GLOBALS["dizimos"]) == "cdizimos") {
			$GLOBALS["dizimos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["dizimos"];
		}

		// Table object (membro)
		if (!isset($GLOBALS['membro'])) $GLOBALS['membro'] = new cmembro();

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'dizimos', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("dizimoslist.php"));
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->Id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $dizimos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($dizimos);
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
		if (@$_GET["Id"] <> "") {
			$this->Id->setQueryStringValue($_GET["Id"]);
		}

		// Set up master detail parameters
		$this->SetUpMasterParms();

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
		if ($this->Id->CurrentValue == "")
			$this->Page_Terminate("dizimoslist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("dizimoslist.php"); // No matching record, return to list
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
		if (!$this->Id->FldIsDetailKey)
			$this->Id->setFormValue($objForm->GetValue("x_Id"));
		if (!$this->id_discipulo->FldIsDetailKey) {
			$this->id_discipulo->setFormValue($objForm->GetValue("x_id_discipulo"));
		}
		if (!$this->Tipo->FldIsDetailKey) {
			$this->Tipo->setFormValue($objForm->GetValue("x_Tipo"));
		}
		if (!$this->Situacao->FldIsDetailKey) {
			$this->Situacao->setFormValue($objForm->GetValue("x_Situacao"));
		}
		if (!$this->Conta_Caixa->FldIsDetailKey) {
			$this->Conta_Caixa->setFormValue($objForm->GetValue("x_Conta_Caixa"));
		}
		if (!$this->Descricao->FldIsDetailKey) {
			$this->Descricao->setFormValue($objForm->GetValue("x_Descricao"));
		}
		if (!$this->Receitas->FldIsDetailKey) {
			$this->Receitas->setFormValue($objForm->GetValue("x_Receitas"));
		}
		if (!$this->FormaPagto->FldIsDetailKey) {
			$this->FormaPagto->setFormValue($objForm->GetValue("x_FormaPagto"));
		}
		if (!$this->Dt_Lancamento->FldIsDetailKey) {
			$this->Dt_Lancamento->setFormValue($objForm->GetValue("x_Dt_Lancamento"));
			$this->Dt_Lancamento->CurrentValue = ew_UnFormatDateTime($this->Dt_Lancamento->CurrentValue, 7);
		}
		if (!$this->Vencimento->FldIsDetailKey) {
			$this->Vencimento->setFormValue($objForm->GetValue("x_Vencimento"));
			$this->Vencimento->CurrentValue = ew_UnFormatDateTime($this->Vencimento->CurrentValue, 7);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->Id->CurrentValue = $this->Id->FormValue;
		$this->id_discipulo->CurrentValue = $this->id_discipulo->FormValue;
		$this->Tipo->CurrentValue = $this->Tipo->FormValue;
		$this->Situacao->CurrentValue = $this->Situacao->FormValue;
		$this->Conta_Caixa->CurrentValue = $this->Conta_Caixa->FormValue;
		$this->Descricao->CurrentValue = $this->Descricao->FormValue;
		$this->Receitas->CurrentValue = $this->Receitas->FormValue;
		$this->FormaPagto->CurrentValue = $this->FormaPagto->FormValue;
		$this->Dt_Lancamento->CurrentValue = $this->Dt_Lancamento->FormValue;
		$this->Dt_Lancamento->CurrentValue = ew_UnFormatDateTime($this->Dt_Lancamento->CurrentValue, 7);
		$this->Vencimento->CurrentValue = $this->Vencimento->FormValue;
		$this->Vencimento->CurrentValue = ew_UnFormatDateTime($this->Vencimento->CurrentValue, 7);
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
		$this->id_discipulo->setDbValue($rs->fields('id_discipulo'));
		$this->Tipo->setDbValue($rs->fields('Tipo'));
		$this->Situacao->setDbValue($rs->fields('Situacao'));
		$this->Conta_Caixa->setDbValue($rs->fields('Conta_Caixa'));
		$this->Descricao->setDbValue($rs->fields('Descricao'));
		$this->Receitas->setDbValue($rs->fields('Receitas'));
		$this->FormaPagto->setDbValue($rs->fields('FormaPagto'));
		$this->Dt_Lancamento->setDbValue($rs->fields('Dt_Lancamento'));
		$this->Vencimento->setDbValue($rs->fields('Vencimento'));
		$this->Centro_de_Custo->setDbValue($rs->fields('Centro_de_Custo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id->DbValue = $row['Id'];
		$this->id_discipulo->DbValue = $row['id_discipulo'];
		$this->Tipo->DbValue = $row['Tipo'];
		$this->Situacao->DbValue = $row['Situacao'];
		$this->Conta_Caixa->DbValue = $row['Conta_Caixa'];
		$this->Descricao->DbValue = $row['Descricao'];
		$this->Receitas->DbValue = $row['Receitas'];
		$this->FormaPagto->DbValue = $row['FormaPagto'];
		$this->Dt_Lancamento->DbValue = $row['Dt_Lancamento'];
		$this->Vencimento->DbValue = $row['Vencimento'];
		$this->Centro_de_Custo->DbValue = $row['Centro_de_Custo'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->Receitas->FormValue == $this->Receitas->CurrentValue && is_numeric(ew_StrToFloat($this->Receitas->CurrentValue)))
			$this->Receitas->CurrentValue = ew_StrToFloat($this->Receitas->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// Id
		// id_discipulo
		// Tipo
		// Situacao
		// Conta_Caixa
		// Descricao
		// Receitas
		// FormaPagto
		// Dt_Lancamento
		// Vencimento
		// Centro_de_Custo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// Id
			$this->Id->ViewValue = $this->Id->CurrentValue;
			$this->Id->ViewCustomAttributes = "";

			// id_discipulo
			if (strval($this->id_discipulo->CurrentValue) <> "") {
				$sFilterWrk = "`Id_membro`" . ew_SearchString("=", $this->id_discipulo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `Id_membro`, `Nome` AS `DispFld`, `CPF` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `membro`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_discipulo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Nome` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_discipulo->ViewValue = $rswrk->fields('DispFld');
					$this->id_discipulo->ViewValue .= ew_ValueSeparator(1,$this->id_discipulo) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_discipulo->ViewValue = $this->id_discipulo->CurrentValue;
				}
			} else {
				$this->id_discipulo->ViewValue = NULL;
			}
			$this->id_discipulo->ViewCustomAttributes = "";

			// Tipo
			$this->Tipo->ViewValue = $this->Tipo->CurrentValue;
			$this->Tipo->ViewCustomAttributes = "";

			// Situacao
			$this->Situacao->ViewValue = $this->Situacao->CurrentValue;
			$this->Situacao->ViewCustomAttributes = "";

			// Conta_Caixa
			$this->Conta_Caixa->ViewValue = $this->Conta_Caixa->CurrentValue;
			$this->Conta_Caixa->ViewCustomAttributes = "";

			// Descricao
			$this->Descricao->ViewValue = $this->Descricao->CurrentValue;
			$this->Descricao->ViewCustomAttributes = "";

			// Receitas
			$this->Receitas->ViewValue = $this->Receitas->CurrentValue;
			$this->Receitas->ViewValue = ew_FormatNumber($this->Receitas->ViewValue, 2, -2, -2, -2);
			$this->Receitas->CellCssStyle .= "text-align: right;";
			$this->Receitas->ViewCustomAttributes = "";

			// FormaPagto
			if (strval($this->FormaPagto->CurrentValue) <> "") {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->FormaPagto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `Id`, `Forma_Pagto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `fin_forma_pgto`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->FormaPagto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Forma_Pagto` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->FormaPagto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->FormaPagto->ViewValue = $this->FormaPagto->CurrentValue;
				}
			} else {
				$this->FormaPagto->ViewValue = NULL;
			}
			$this->FormaPagto->ViewCustomAttributes = "";

			// Dt_Lancamento
			$this->Dt_Lancamento->ViewValue = $this->Dt_Lancamento->CurrentValue;
			$this->Dt_Lancamento->ViewValue = ew_FormatDateTime($this->Dt_Lancamento->ViewValue, 7);
			$this->Dt_Lancamento->CellCssStyle .= "text-align: right;";
			$this->Dt_Lancamento->ViewCustomAttributes = "";

			// Vencimento
			$this->Vencimento->ViewValue = $this->Vencimento->CurrentValue;
			$this->Vencimento->ViewValue = ew_FormatDateTime($this->Vencimento->ViewValue, 7);
			$this->Vencimento->CellCssStyle .= "text-align: right;";
			$this->Vencimento->ViewCustomAttributes = "";

			// Id
			$this->Id->LinkCustomAttributes = "";
			$this->Id->HrefValue = "";
			$this->Id->TooltipValue = "";

			// id_discipulo
			$this->id_discipulo->LinkCustomAttributes = "";
			$this->id_discipulo->HrefValue = "";
			$this->id_discipulo->TooltipValue = "";

			// Tipo
			$this->Tipo->LinkCustomAttributes = "";
			$this->Tipo->HrefValue = "";
			$this->Tipo->TooltipValue = "";

			// Situacao
			$this->Situacao->LinkCustomAttributes = "";
			$this->Situacao->HrefValue = "";
			$this->Situacao->TooltipValue = "";

			// Conta_Caixa
			$this->Conta_Caixa->LinkCustomAttributes = "";
			$this->Conta_Caixa->HrefValue = "";
			$this->Conta_Caixa->TooltipValue = "";

			// Descricao
			$this->Descricao->LinkCustomAttributes = "";
			$this->Descricao->HrefValue = "";
			$this->Descricao->TooltipValue = "";

			// Receitas
			$this->Receitas->LinkCustomAttributes = "";
			$this->Receitas->HrefValue = "";
			$this->Receitas->TooltipValue = "";

			// FormaPagto
			$this->FormaPagto->LinkCustomAttributes = "";
			$this->FormaPagto->HrefValue = "";
			$this->FormaPagto->TooltipValue = "";

			// Dt_Lancamento
			$this->Dt_Lancamento->LinkCustomAttributes = "";
			$this->Dt_Lancamento->HrefValue = "";
			$this->Dt_Lancamento->TooltipValue = "";

			// Vencimento
			$this->Vencimento->LinkCustomAttributes = "";
			$this->Vencimento->HrefValue = "";
			$this->Vencimento->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// Id
			$this->Id->EditAttrs["class"] = "form-control";
			$this->Id->EditCustomAttributes = "";

			// id_discipulo
			$this->id_discipulo->EditAttrs["class"] = "form-control";
			$this->id_discipulo->EditCustomAttributes = "";
			if ($this->id_discipulo->getSessionValue() <> "") {
				$this->id_discipulo->CurrentValue = $this->id_discipulo->getSessionValue();
			if (strval($this->id_discipulo->CurrentValue) <> "") {
				$sFilterWrk = "`Id_membro`" . ew_SearchString("=", $this->id_discipulo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `Id_membro`, `Nome` AS `DispFld`, `CPF` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `membro`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_discipulo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Nome` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_discipulo->ViewValue = $rswrk->fields('DispFld');
					$this->id_discipulo->ViewValue .= ew_ValueSeparator(1,$this->id_discipulo) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_discipulo->ViewValue = $this->id_discipulo->CurrentValue;
				}
			} else {
				$this->id_discipulo->ViewValue = NULL;
			}
			$this->id_discipulo->ViewCustomAttributes = "";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `Id_membro`, `Nome` AS `DispFld`, `CPF` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `membro`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_discipulo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Nome` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_discipulo->EditValue = $arwrk;
			}

			// Tipo
			$this->Tipo->EditAttrs["class"] = "form-control";
			$this->Tipo->EditCustomAttributes = "";

			// Situacao
			$this->Situacao->EditAttrs["class"] = "form-control";
			$this->Situacao->EditCustomAttributes = "";

			// Conta_Caixa
			$this->Conta_Caixa->EditAttrs["class"] = "form-control";
			$this->Conta_Caixa->EditCustomAttributes = "";

			// Descricao
			$this->Descricao->EditAttrs["class"] = "form-control";
			$this->Descricao->EditCustomAttributes = "";
			$this->Descricao->EditValue = ew_HtmlEncode($this->Descricao->CurrentValue);

			// Receitas
			$this->Receitas->EditAttrs["class"] = "form-control";
			$this->Receitas->EditCustomAttributes = "";
			$this->Receitas->EditValue = ew_HtmlEncode($this->Receitas->CurrentValue);
			if (strval($this->Receitas->EditValue) <> "" && is_numeric($this->Receitas->EditValue)) $this->Receitas->EditValue = ew_FormatNumber($this->Receitas->EditValue, -2, -2, -2, -2);

			// FormaPagto
			$this->FormaPagto->EditAttrs["class"] = "form-control";
			$this->FormaPagto->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `Id`, `Forma_Pagto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `fin_forma_pgto`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->FormaPagto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Forma_Pagto` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->FormaPagto->EditValue = $arwrk;

			// Dt_Lancamento
			$this->Dt_Lancamento->EditAttrs["class"] = "form-control";
			$this->Dt_Lancamento->EditCustomAttributes = "";
			$this->Dt_Lancamento->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->Dt_Lancamento->CurrentValue, 7));

			// Vencimento
			$this->Vencimento->EditAttrs["class"] = "form-control";
			$this->Vencimento->EditCustomAttributes = "";
			$this->Vencimento->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->Vencimento->CurrentValue, 7));

			// Edit refer script
			// Id

			$this->Id->HrefValue = "";

			// id_discipulo
			$this->id_discipulo->HrefValue = "";

			// Tipo
			$this->Tipo->HrefValue = "";

			// Situacao
			$this->Situacao->HrefValue = "";

			// Conta_Caixa
			$this->Conta_Caixa->HrefValue = "";

			// Descricao
			$this->Descricao->HrefValue = "";

			// Receitas
			$this->Receitas->HrefValue = "";

			// FormaPagto
			$this->FormaPagto->HrefValue = "";

			// Dt_Lancamento
			$this->Dt_Lancamento->HrefValue = "";

			// Vencimento
			$this->Vencimento->HrefValue = "";
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
		if (!$this->id_discipulo->FldIsDetailKey && !is_null($this->id_discipulo->FormValue) && $this->id_discipulo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_discipulo->FldCaption(), $this->id_discipulo->ReqErrMsg));
		}
		if (!$this->Descricao->FldIsDetailKey && !is_null($this->Descricao->FormValue) && $this->Descricao->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Descricao->FldCaption(), $this->Descricao->ReqErrMsg));
		}
		if (!$this->Receitas->FldIsDetailKey && !is_null($this->Receitas->FormValue) && $this->Receitas->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Receitas->FldCaption(), $this->Receitas->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->Receitas->FormValue)) {
			ew_AddMessage($gsFormError, $this->Receitas->FldErrMsg());
		}
		if (!$this->FormaPagto->FldIsDetailKey && !is_null($this->FormaPagto->FormValue) && $this->FormaPagto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->FormaPagto->FldCaption(), $this->FormaPagto->ReqErrMsg));
		}
		if (!$this->Dt_Lancamento->FldIsDetailKey && !is_null($this->Dt_Lancamento->FormValue) && $this->Dt_Lancamento->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Dt_Lancamento->FldCaption(), $this->Dt_Lancamento->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->Dt_Lancamento->FormValue)) {
			ew_AddMessage($gsFormError, $this->Dt_Lancamento->FldErrMsg());
		}
		if (!$this->Vencimento->FldIsDetailKey && !is_null($this->Vencimento->FormValue) && $this->Vencimento->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Vencimento->FldCaption(), $this->Vencimento->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->Vencimento->FormValue)) {
			ew_AddMessage($gsFormError, $this->Vencimento->FldErrMsg());
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

			// id_discipulo
			$this->id_discipulo->SetDbValueDef($rsnew, $this->id_discipulo->CurrentValue, NULL, $this->id_discipulo->ReadOnly);

			// Tipo
			$this->Tipo->SetDbValueDef($rsnew, $this->Tipo->CurrentValue, NULL, $this->Tipo->ReadOnly);

			// Situacao
			$this->Situacao->SetDbValueDef($rsnew, $this->Situacao->CurrentValue, NULL, $this->Situacao->ReadOnly);

			// Conta_Caixa
			$this->Conta_Caixa->SetDbValueDef($rsnew, $this->Conta_Caixa->CurrentValue, NULL, $this->Conta_Caixa->ReadOnly);

			// Descricao
			$this->Descricao->SetDbValueDef($rsnew, $this->Descricao->CurrentValue, NULL, $this->Descricao->ReadOnly);

			// Receitas
			$this->Receitas->SetDbValueDef($rsnew, $this->Receitas->CurrentValue, NULL, $this->Receitas->ReadOnly);

			// FormaPagto
			$this->FormaPagto->SetDbValueDef($rsnew, $this->FormaPagto->CurrentValue, NULL, $this->FormaPagto->ReadOnly);

			// Dt_Lancamento
			$this->Dt_Lancamento->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->Dt_Lancamento->CurrentValue, 7), NULL, $this->Dt_Lancamento->ReadOnly);

			// Vencimento
			$this->Vencimento->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->Vencimento->CurrentValue, 7), NULL, $this->Vencimento->ReadOnly);

			// Check referential integrity for master table 'membro'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_membro();
			$KeyValue = isset($rsnew['id_discipulo']) ? $rsnew['id_discipulo'] : $rsold['id_discipulo'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@Id_membro@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				$rsmaster = $GLOBALS["membro"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "membro", $Language->Phrase("RelatedRecordRequired"));
				$this->setFailureMessage($sRelatedRecordMsg);
				$rs->Close();
				return FALSE;
			}

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
		$rs->Close();
		return $EditRow;
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "membro") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_Id_membro"] <> "") {
					$GLOBALS["membro"]->Id_membro->setQueryStringValue($_GET["fk_Id_membro"]);
					$this->id_discipulo->setQueryStringValue($GLOBALS["membro"]->Id_membro->QueryStringValue);
					$this->id_discipulo->setSessionValue($this->id_discipulo->QueryStringValue);
					if (!is_numeric($GLOBALS["membro"]->Id_membro->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);
			$this->setSessionWhere($this->GetDetailFilter());

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "membro") {
				if ($this->id_discipulo->QueryStringValue == "") $this->id_discipulo->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "dizimoslist.php", "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($dizimos_edit)) $dizimos_edit = new cdizimos_edit();

// Page init
$dizimos_edit->Page_Init();

// Page main
$dizimos_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$dizimos_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var dizimos_edit = new ew_Page("dizimos_edit");
dizimos_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = dizimos_edit.PageID; // For backward compatibility

// Form object
var fdizimosedit = new ew_Form("fdizimosedit");

// Validate form
fdizimosedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id_discipulo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dizimos->id_discipulo->FldCaption(), $dizimos->id_discipulo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Descricao");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dizimos->Descricao->FldCaption(), $dizimos->Descricao->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Receitas");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dizimos->Receitas->FldCaption(), $dizimos->Receitas->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Receitas");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($dizimos->Receitas->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_FormaPagto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dizimos->FormaPagto->FldCaption(), $dizimos->FormaPagto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Dt_Lancamento");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dizimos->Dt_Lancamento->FldCaption(), $dizimos->Dt_Lancamento->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Dt_Lancamento");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($dizimos->Dt_Lancamento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Vencimento");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dizimos->Vencimento->FldCaption(), $dizimos->Vencimento->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Vencimento");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($dizimos->Vencimento->FldErrMsg()) ?>");

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
fdizimosedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdizimosedit.ValidateRequired = true;
<?php } else { ?>
fdizimosedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdizimosedit.Lists["x_id_discipulo"] = {"LinkField":"x_Id_membro","Ajax":null,"AutoFill":false,"DisplayFields":["x_Nome","x_CPF","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fdizimosedit.Lists["x_FormaPagto"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Forma_Pagto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $dizimos_edit->ShowPageHeader(); ?>
<?php
$dizimos_edit->ShowMessage();
?>
<form name="fdizimosedit" id="fdizimosedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($dizimos_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $dizimos_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="dizimos">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($dizimos->id_discipulo->Visible) { // id_discipulo ?>
	<div id="r_id_discipulo" class="form-group">
		<label id="elh_dizimos_id_discipulo" for="x_id_discipulo" class="col-sm-2 control-label ewLabel"><?php echo $dizimos->id_discipulo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $dizimos->id_discipulo->CellAttributes() ?>>
<?php if ($dizimos->id_discipulo->getSessionValue() <> "") { ?>
<span id="el_dizimos_id_discipulo">
<span<?php echo $dizimos->id_discipulo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $dizimos->id_discipulo->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_id_discipulo" name="x_id_discipulo" value="<?php echo ew_HtmlEncode($dizimos->id_discipulo->CurrentValue) ?>">
<?php } else { ?>
<span id="el_dizimos_id_discipulo">
<select data-field="x_id_discipulo" id="x_id_discipulo" name="x_id_discipulo"<?php echo $dizimos->id_discipulo->EditAttributes() ?>>
<?php
if (is_array($dizimos->id_discipulo->EditValue)) {
	$arwrk = $dizimos->id_discipulo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($dizimos->id_discipulo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$dizimos->id_discipulo) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fdizimosedit.Lists["x_id_discipulo"].Options = <?php echo (is_array($dizimos->id_discipulo->EditValue)) ? ew_ArrayToJson($dizimos->id_discipulo->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php echo $dizimos->id_discipulo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dizimos->Descricao->Visible) { // Descricao ?>
	<div id="r_Descricao" class="form-group">
		<label id="elh_dizimos_Descricao" for="x_Descricao" class="col-sm-2 control-label ewLabel"><?php echo $dizimos->Descricao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $dizimos->Descricao->CellAttributes() ?>>
<span id="el_dizimos_Descricao">
<input type="text" data-field="x_Descricao" name="x_Descricao" id="x_Descricao" size="60" maxlength="60" value="<?php echo $dizimos->Descricao->EditValue ?>"<?php echo $dizimos->Descricao->EditAttributes() ?>>
</span>
<?php echo $dizimos->Descricao->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dizimos->Receitas->Visible) { // Receitas ?>
	<div id="r_Receitas" class="form-group">
		<label id="elh_dizimos_Receitas" for="x_Receitas" class="col-sm-2 control-label ewLabel"><?php echo $dizimos->Receitas->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $dizimos->Receitas->CellAttributes() ?>>
<span id="el_dizimos_Receitas">
<input type="text" data-field="x_Receitas" name="x_Receitas" id="x_Receitas" size="30" value="<?php echo $dizimos->Receitas->EditValue ?>"<?php echo $dizimos->Receitas->EditAttributes() ?>>
</span>
<?php echo $dizimos->Receitas->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dizimos->FormaPagto->Visible) { // FormaPagto ?>
	<div id="r_FormaPagto" class="form-group">
		<label id="elh_dizimos_FormaPagto" for="x_FormaPagto" class="col-sm-2 control-label ewLabel"><?php echo $dizimos->FormaPagto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $dizimos->FormaPagto->CellAttributes() ?>>
<span id="el_dizimos_FormaPagto">
<select data-field="x_FormaPagto" id="x_FormaPagto" name="x_FormaPagto"<?php echo $dizimos->FormaPagto->EditAttributes() ?>>
<?php
if (is_array($dizimos->FormaPagto->EditValue)) {
	$arwrk = $dizimos->FormaPagto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($dizimos->FormaPagto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<script type="text/javascript">
fdizimosedit.Lists["x_FormaPagto"].Options = <?php echo (is_array($dizimos->FormaPagto->EditValue)) ? ew_ArrayToJson($dizimos->FormaPagto->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $dizimos->FormaPagto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dizimos->Dt_Lancamento->Visible) { // Dt_Lancamento ?>
	<div id="r_Dt_Lancamento" class="form-group">
		<label id="elh_dizimos_Dt_Lancamento" for="x_Dt_Lancamento" class="col-sm-2 control-label ewLabel"><?php echo $dizimos->Dt_Lancamento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $dizimos->Dt_Lancamento->CellAttributes() ?>>
<span id="el_dizimos_Dt_Lancamento">
<input type="text" data-field="x_Dt_Lancamento" name="x_Dt_Lancamento" id="x_Dt_Lancamento" size="15" value="<?php echo $dizimos->Dt_Lancamento->EditValue ?>"<?php echo $dizimos->Dt_Lancamento->EditAttributes() ?>>
<?php if (!$dizimos->Dt_Lancamento->ReadOnly && !$dizimos->Dt_Lancamento->Disabled && @$dizimos->Dt_Lancamento->EditAttrs["readonly"] == "" && @$dizimos->Dt_Lancamento->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fdizimosedit", "x_Dt_Lancamento", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $dizimos->Dt_Lancamento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dizimos->Vencimento->Visible) { // Vencimento ?>
	<div id="r_Vencimento" class="form-group">
		<label id="elh_dizimos_Vencimento" for="x_Vencimento" class="col-sm-2 control-label ewLabel"><?php echo $dizimos->Vencimento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $dizimos->Vencimento->CellAttributes() ?>>
<span id="el_dizimos_Vencimento">
<input type="text" data-field="x_Vencimento" name="x_Vencimento" id="x_Vencimento" size="15" value="<?php echo $dizimos->Vencimento->EditValue ?>"<?php echo $dizimos->Vencimento->EditAttributes() ?>>
<?php if (!$dizimos->Vencimento->ReadOnly && !$dizimos->Vencimento->Disabled && @$dizimos->Vencimento->EditAttrs["readonly"] == "" && @$dizimos->Vencimento->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fdizimosedit", "x_Vencimento", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $dizimos->Vencimento->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<span id="el_dizimos_Id">
<input type="hidden" data-field="x_Id" name="x_Id" id="x_Id" value="<?php echo ew_HtmlEncode($dizimos->Id->CurrentValue) ?>">
</span>
<span id="el_dizimos_Tipo">
<input type="hidden" data-field="x_Tipo" name="x_Tipo" id="x_Tipo" value="<?php echo ew_HtmlEncode($dizimos->Tipo->CurrentValue) ?>">
</span>
<span id="el_dizimos_Situacao">
<input type="hidden" data-field="x_Situacao" name="x_Situacao" id="x_Situacao" value="<?php echo ew_HtmlEncode($dizimos->Situacao->CurrentValue) ?>">
</span>
<span id="el_dizimos_Conta_Caixa">
<input type="hidden" data-field="x_Conta_Caixa" name="x_Conta_Caixa" id="x_Conta_Caixa" value="<?php echo ew_HtmlEncode($dizimos->Conta_Caixa->CurrentValue) ?>">
</span>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><i class="fa fa-floppy-o"></i>&nbsp;<?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fdizimosedit.Init();
</script>
<?php
$dizimos_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$dizimos_edit->Page_Terminate();
?>
