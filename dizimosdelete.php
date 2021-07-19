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

$dizimos_delete = NULL; // Initialize page object first

class cdizimos_delete extends cdizimos {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{2B7992FC-5911-46A7-9310-01F4D4225C49}";

	// Table name
	var $TableName = 'dizimos';

	// Page object name
	var $PageObjName = 'dizimos_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("dizimoslist.php"));
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

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("dizimoslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in dizimos class, dizimosinfo.php

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

		$this->Id->CellCssStyle = "white-space: nowrap;";

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

		$this->Centro_de_Custo->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
if (!isset($dizimos_delete)) $dizimos_delete = new cdizimos_delete();

// Page init
$dizimos_delete->Page_Init();

// Page main
$dizimos_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$dizimos_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var dizimos_delete = new ew_Page("dizimos_delete");
dizimos_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = dizimos_delete.PageID; // For backward compatibility

// Form object
var fdizimosdelete = new ew_Form("fdizimosdelete");

// Form_CustomValidate event
fdizimosdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdizimosdelete.ValidateRequired = true;
<?php } else { ?>
fdizimosdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdizimosdelete.Lists["x_FormaPagto"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Forma_Pagto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($dizimos_delete->Recordset = $dizimos_delete->LoadRecordset())
	$dizimos_deleteTotalRecs = $dizimos_delete->Recordset->RecordCount(); // Get record count
if ($dizimos_deleteTotalRecs <= 0) { // No record found, exit
	if ($dizimos_delete->Recordset)
		$dizimos_delete->Recordset->Close();
	$dizimos_delete->Page_Terminate("dizimoslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $dizimos_delete->ShowPageHeader(); ?>
<?php
$dizimos_delete->ShowMessage();
?>
<form name="fdizimosdelete" id="fdizimosdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($dizimos_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $dizimos_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="dizimos">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($dizimos_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $dizimos->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($dizimos->Descricao->Visible) { // Descricao ?>
		<th><span id="elh_dizimos_Descricao" class="dizimos_Descricao"><?php echo $dizimos->Descricao->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dizimos->Receitas->Visible) { // Receitas ?>
		<th><span id="elh_dizimos_Receitas" class="dizimos_Receitas"><?php echo $dizimos->Receitas->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dizimos->FormaPagto->Visible) { // FormaPagto ?>
		<th><span id="elh_dizimos_FormaPagto" class="dizimos_FormaPagto"><?php echo $dizimos->FormaPagto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dizimos->Dt_Lancamento->Visible) { // Dt_Lancamento ?>
		<th><span id="elh_dizimos_Dt_Lancamento" class="dizimos_Dt_Lancamento"><?php echo $dizimos->Dt_Lancamento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dizimos->Vencimento->Visible) { // Vencimento ?>
		<th><span id="elh_dizimos_Vencimento" class="dizimos_Vencimento"><?php echo $dizimos->Vencimento->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$dizimos_delete->RecCnt = 0;
$i = 0;
while (!$dizimos_delete->Recordset->EOF) {
	$dizimos_delete->RecCnt++;
	$dizimos_delete->RowCnt++;

	// Set row properties
	$dizimos->ResetAttrs();
	$dizimos->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$dizimos_delete->LoadRowValues($dizimos_delete->Recordset);

	// Render row
	$dizimos_delete->RenderRow();
?>
	<tr<?php echo $dizimos->RowAttributes() ?>>
<?php if ($dizimos->Descricao->Visible) { // Descricao ?>
		<td<?php echo $dizimos->Descricao->CellAttributes() ?>>
<span id="el<?php echo $dizimos_delete->RowCnt ?>_dizimos_Descricao" class="form-group dizimos_Descricao">
<span<?php echo $dizimos->Descricao->ViewAttributes() ?>>
<?php echo $dizimos->Descricao->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dizimos->Receitas->Visible) { // Receitas ?>
		<td<?php echo $dizimos->Receitas->CellAttributes() ?>>
<span id="el<?php echo $dizimos_delete->RowCnt ?>_dizimos_Receitas" class="form-group dizimos_Receitas">
<span<?php echo $dizimos->Receitas->ViewAttributes() ?>>
<?php echo $dizimos->Receitas->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dizimos->FormaPagto->Visible) { // FormaPagto ?>
		<td<?php echo $dizimos->FormaPagto->CellAttributes() ?>>
<span id="el<?php echo $dizimos_delete->RowCnt ?>_dizimos_FormaPagto" class="form-group dizimos_FormaPagto">
<span<?php echo $dizimos->FormaPagto->ViewAttributes() ?>>
<?php echo $dizimos->FormaPagto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dizimos->Dt_Lancamento->Visible) { // Dt_Lancamento ?>
		<td<?php echo $dizimos->Dt_Lancamento->CellAttributes() ?>>
<span id="el<?php echo $dizimos_delete->RowCnt ?>_dizimos_Dt_Lancamento" class="form-group dizimos_Dt_Lancamento">
<span<?php echo $dizimos->Dt_Lancamento->ViewAttributes() ?>>
<?php echo $dizimos->Dt_Lancamento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dizimos->Vencimento->Visible) { // Vencimento ?>
		<td<?php echo $dizimos->Vencimento->CellAttributes() ?>>
<span id="el<?php echo $dizimos_delete->RowCnt ?>_dizimos_Vencimento" class="form-group dizimos_Vencimento">
<span<?php echo $dizimos->Vencimento->ViewAttributes() ?>>
<?php echo $dizimos->Vencimento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$dizimos_delete->Recordset->MoveNext();
}
$dizimos_delete->Recordset->Close();
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
fdizimosdelete.Init();
</script>
<?php
$dizimos_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$dizimos_delete->Page_Terminate();
?>
