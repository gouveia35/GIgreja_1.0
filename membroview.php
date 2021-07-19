<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "membroinfo.php" ?>
<?php include_once "cargosministeriaisinfo.php" ?>
<?php include_once "celulasinfo.php" ?>
<?php include_once "igrejasinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "dizimosgridcls.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$membro_view = NULL; // Initialize page object first

class cmembro_view extends cmembro {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{2B7992FC-5911-46A7-9310-01F4D4225C49}";

	// Table name
	var $TableName = 'membro';

	// Page object name
	var $PageObjName = 'membro_view';

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

		// Table object (membro)
		if (!isset($GLOBALS["membro"]) || get_class($GLOBALS["membro"]) == "cmembro") {
			$GLOBALS["membro"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["membro"];
		}
		$KeyUrl = "";
		if (@$_GET["Id_membro"] <> "") {
			$this->RecKey["Id_membro"] = $_GET["Id_membro"];
			$KeyUrl .= "&amp;Id_membro=" . urlencode($this->RecKey["Id_membro"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (cargosministeriais)
		if (!isset($GLOBALS['cargosministeriais'])) $GLOBALS['cargosministeriais'] = new ccargosministeriais();

		// Table object (celulas)
		if (!isset($GLOBALS['celulas'])) $GLOBALS['celulas'] = new ccelulas();

		// Table object (igrejas)
		if (!isset($GLOBALS['igrejas'])) $GLOBALS['igrejas'] = new cigrejas();

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'membro', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("membrolist.php"));
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

			// Process auto fill for detail table 'dizimos'
			if (@$_POST["grid"] == "fdizimosgrid") {
				if (!isset($GLOBALS["dizimos_grid"])) $GLOBALS["dizimos_grid"] = new cdizimos_grid;
				$GLOBALS["dizimos_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $membro;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($membro);
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

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["Id_membro"] <> "") {
				$this->Id_membro->setQueryStringValue($_GET["Id_membro"]);
				$this->RecKey["Id_membro"] = $this->Id_membro->QueryStringValue;
			} else {
				$sReturnUrl = "membrolist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "membrolist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "membrolist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();

		// Set up detail parameters
		$this->SetUpDetailParms();
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

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());
		$option = &$options["detail"];
		$DetailTableLink = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_dizimos"
		$item = &$option->Add("detail_dizimos");
		$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("dizimos", "TblCaption");
		$body = "<a class=\"btn btn-primary btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("dizimoslist.php?" . EW_TABLE_SHOW_MASTER . "=membro&fk_Id_membro=" . urlencode(strval($this->Id_membro->CurrentValue)) . "") . "\"><i class='glyphicon glyphicon-th-list'></i> " . $body . "</a>";
		$links = "";
		if ($GLOBALS["dizimos_grid"] && $GLOBALS["dizimos_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'dizimos')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=dizimos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "dizimos";
		}
		if ($GLOBALS["dizimos_grid"] && $GLOBALS["dizimos_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'dizimos')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=dizimos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "dizimos";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'dizimos');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "dizimos";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$option->Add("details");
			$oListOpt->Body = $body;
		}

		// Set up detail default
		$option = &$options["detail"];
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$option->UseImageAndText = TRUE;
		$ar = explode(",", $DetailTableLink);
		$cnt = count($ar);
		$option->UseDropDownButton = ($cnt > 1);
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

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
		$this->Id_membro->setDbValue($rs->fields('Id_membro'));
		$this->Foto->Upload->DbValue = $rs->fields('Foto');
		$this->Foto->CurrentValue = $this->Foto->Upload->DbValue;
		$this->Matricula->setDbValue($rs->fields('Matricula'));
		$this->Nome->setDbValue($rs->fields('Nome'));
		$this->Sexo->setDbValue($rs->fields('Sexo'));
		$this->DataNasc->setDbValue($rs->fields('DataNasc'));
		$this->Nacionalidade->setDbValue($rs->fields('Nacionalidade'));
		$this->EstadoCivil->setDbValue($rs->fields('EstadoCivil'));
		$this->CPF->setDbValue($rs->fields('CPF'));
		$this->RG->setDbValue($rs->fields('RG'));
		$this->Profissao->setDbValue($rs->fields('Profissao'));
		$this->_Email->setDbValue($rs->fields('Email'));
		$this->TelefoneRes->setDbValue($rs->fields('TelefoneRes'));
		$this->Celular_1->setDbValue($rs->fields('Celular_1'));
		$this->Celular_2->setDbValue($rs->fields('Celular_2'));
		$this->Endereco->setDbValue($rs->fields('Endereco'));
		$this->Complemento->setDbValue($rs->fields('Complemento'));
		$this->Bairro->setDbValue($rs->fields('Bairro'));
		$this->Cidade->setDbValue($rs->fields('Cidade'));
		$this->UF->setDbValue($rs->fields('UF'));
		$this->CEP->setDbValue($rs->fields('CEP'));
		$this->GrauEscolaridade->setDbValue($rs->fields('GrauEscolaridade'));
		$this->Curso->setDbValue($rs->fields('Curso'));
		$this->Nome_do_Pai->setDbValue($rs->fields('Nome_do_Pai'));
		$this->Nome_da_Mae->setDbValue($rs->fields('Nome_da_Mae'));
		$this->Data_Casamento->setDbValue($rs->fields('Data_Casamento'));
		$this->Conjuge->setDbValue($rs->fields('Conjuge'));
		$this->N_Filhos->setDbValue($rs->fields('N_Filhos'));
		$this->Empresa_trabalha->setDbValue($rs->fields('Empresa_trabalha'));
		$this->Fone_Empresa->setDbValue($rs->fields('Fone_Empresa'));
		$this->Anotacoes->setDbValue($rs->fields('Anotacoes'));
		$this->Celula->setDbValue($rs->fields('Celula'));
		$this->Nome_da_Familia->setDbValue($rs->fields('Nome_da_Familia'));
		$this->Situacao->setDbValue($rs->fields('Situacao'));
		$this->Data_batismo->setDbValue($rs->fields('Data_batismo'));
		$this->Da_Igreja->setDbValue($rs->fields('Da_Igreja'));
		$this->CargoMinisterial->setDbValue($rs->fields('CargoMinisterial'));
		$this->Admissao->setDbValue($rs->fields('Admissao'));
		$this->Tipo_Admissao->setDbValue($rs->fields('Tipo_Admissao'));
		$this->Funcao->setDbValue($rs->fields('Funcao'));
		$this->Rede_Ministerial->setDbValue($rs->fields('Rede_Ministerial'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id_membro->DbValue = $row['Id_membro'];
		$this->Foto->Upload->DbValue = $row['Foto'];
		$this->Matricula->DbValue = $row['Matricula'];
		$this->Nome->DbValue = $row['Nome'];
		$this->Sexo->DbValue = $row['Sexo'];
		$this->DataNasc->DbValue = $row['DataNasc'];
		$this->Nacionalidade->DbValue = $row['Nacionalidade'];
		$this->EstadoCivil->DbValue = $row['EstadoCivil'];
		$this->CPF->DbValue = $row['CPF'];
		$this->RG->DbValue = $row['RG'];
		$this->Profissao->DbValue = $row['Profissao'];
		$this->_Email->DbValue = $row['Email'];
		$this->TelefoneRes->DbValue = $row['TelefoneRes'];
		$this->Celular_1->DbValue = $row['Celular_1'];
		$this->Celular_2->DbValue = $row['Celular_2'];
		$this->Endereco->DbValue = $row['Endereco'];
		$this->Complemento->DbValue = $row['Complemento'];
		$this->Bairro->DbValue = $row['Bairro'];
		$this->Cidade->DbValue = $row['Cidade'];
		$this->UF->DbValue = $row['UF'];
		$this->CEP->DbValue = $row['CEP'];
		$this->GrauEscolaridade->DbValue = $row['GrauEscolaridade'];
		$this->Curso->DbValue = $row['Curso'];
		$this->Nome_do_Pai->DbValue = $row['Nome_do_Pai'];
		$this->Nome_da_Mae->DbValue = $row['Nome_da_Mae'];
		$this->Data_Casamento->DbValue = $row['Data_Casamento'];
		$this->Conjuge->DbValue = $row['Conjuge'];
		$this->N_Filhos->DbValue = $row['N_Filhos'];
		$this->Empresa_trabalha->DbValue = $row['Empresa_trabalha'];
		$this->Fone_Empresa->DbValue = $row['Fone_Empresa'];
		$this->Anotacoes->DbValue = $row['Anotacoes'];
		$this->Celula->DbValue = $row['Celula'];
		$this->Nome_da_Familia->DbValue = $row['Nome_da_Familia'];
		$this->Situacao->DbValue = $row['Situacao'];
		$this->Data_batismo->DbValue = $row['Data_batismo'];
		$this->Da_Igreja->DbValue = $row['Da_Igreja'];
		$this->CargoMinisterial->DbValue = $row['CargoMinisterial'];
		$this->Admissao->DbValue = $row['Admissao'];
		$this->Tipo_Admissao->DbValue = $row['Tipo_Admissao'];
		$this->Funcao->DbValue = $row['Funcao'];
		$this->Rede_Ministerial->DbValue = $row['Rede_Ministerial'];
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
		// Id_membro
		// Foto
		// Matricula
		// Nome
		// Sexo
		// DataNasc
		// Nacionalidade
		// EstadoCivil
		// CPF
		// RG
		// Profissao
		// Email
		// TelefoneRes
		// Celular_1
		// Celular_2
		// Endereco
		// Complemento
		// Bairro
		// Cidade
		// UF
		// CEP
		// GrauEscolaridade
		// Curso
		// Nome_do_Pai
		// Nome_da_Mae
		// Data_Casamento
		// Conjuge
		// N_Filhos
		// Empresa_trabalha
		// Fone_Empresa
		// Anotacoes
		// Celula
		// Nome_da_Familia
		// Situacao
		// Data_batismo
		// Da_Igreja
		// CargoMinisterial
		// Admissao
		// Tipo_Admissao
		// Funcao
		// Rede_Ministerial

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// Foto
			if (!ew_Empty($this->Foto->Upload->DbValue)) {
				$this->Foto->ImageWidth = 30;
				$this->Foto->ImageHeight = 0;
				$this->Foto->ImageAlt = $this->Foto->FldAlt();
				$this->Foto->ViewValue = ew_UploadPathEx(FALSE, $this->Foto->UploadPath) . $this->Foto->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->Foto->ViewValue = ew_UploadPathEx(TRUE, $this->Foto->UploadPath) . $this->Foto->Upload->DbValue;
				}
			} else {
				$this->Foto->ViewValue = "";
			}
			$this->Foto->ViewCustomAttributes = "";

			// Matricula
			$this->Matricula->ViewValue = $this->Matricula->CurrentValue;
			$this->Matricula->ViewCustomAttributes = "";

			// Nome
			$this->Nome->ViewValue = $this->Nome->CurrentValue;
			$this->Nome->ViewCustomAttributes = "";

			// Sexo
			if (strval($this->Sexo->CurrentValue) <> "") {
				switch ($this->Sexo->CurrentValue) {
					case $this->Sexo->FldTagValue(1):
						$this->Sexo->ViewValue = $this->Sexo->FldTagCaption(1) <> "" ? $this->Sexo->FldTagCaption(1) : $this->Sexo->CurrentValue;
						break;
					case $this->Sexo->FldTagValue(2):
						$this->Sexo->ViewValue = $this->Sexo->FldTagCaption(2) <> "" ? $this->Sexo->FldTagCaption(2) : $this->Sexo->CurrentValue;
						break;
					default:
						$this->Sexo->ViewValue = $this->Sexo->CurrentValue;
				}
			} else {
				$this->Sexo->ViewValue = NULL;
			}
			$this->Sexo->ViewCustomAttributes = "";

			// DataNasc
			$this->DataNasc->ViewValue = $this->DataNasc->CurrentValue;
			$this->DataNasc->ViewValue = ew_FormatDateTime($this->DataNasc->ViewValue, 7);
			$this->DataNasc->ViewCustomAttributes = "";

			// Nacionalidade
			$this->Nacionalidade->ViewValue = $this->Nacionalidade->CurrentValue;
			$this->Nacionalidade->ViewCustomAttributes = "";

			// EstadoCivil
			if (strval($this->EstadoCivil->CurrentValue) <> "") {
				switch ($this->EstadoCivil->CurrentValue) {
					case $this->EstadoCivil->FldTagValue(1):
						$this->EstadoCivil->ViewValue = $this->EstadoCivil->FldTagCaption(1) <> "" ? $this->EstadoCivil->FldTagCaption(1) : $this->EstadoCivil->CurrentValue;
						break;
					case $this->EstadoCivil->FldTagValue(2):
						$this->EstadoCivil->ViewValue = $this->EstadoCivil->FldTagCaption(2) <> "" ? $this->EstadoCivil->FldTagCaption(2) : $this->EstadoCivil->CurrentValue;
						break;
					case $this->EstadoCivil->FldTagValue(3):
						$this->EstadoCivil->ViewValue = $this->EstadoCivil->FldTagCaption(3) <> "" ? $this->EstadoCivil->FldTagCaption(3) : $this->EstadoCivil->CurrentValue;
						break;
					case $this->EstadoCivil->FldTagValue(4):
						$this->EstadoCivil->ViewValue = $this->EstadoCivil->FldTagCaption(4) <> "" ? $this->EstadoCivil->FldTagCaption(4) : $this->EstadoCivil->CurrentValue;
						break;
					case $this->EstadoCivil->FldTagValue(5):
						$this->EstadoCivil->ViewValue = $this->EstadoCivil->FldTagCaption(5) <> "" ? $this->EstadoCivil->FldTagCaption(5) : $this->EstadoCivil->CurrentValue;
						break;
					default:
						$this->EstadoCivil->ViewValue = $this->EstadoCivil->CurrentValue;
				}
			} else {
				$this->EstadoCivil->ViewValue = NULL;
			}
			$this->EstadoCivil->ViewCustomAttributes = "";

			// CPF
			$this->CPF->ViewValue = $this->CPF->CurrentValue;
			$this->CPF->ViewCustomAttributes = "";

			// RG
			$this->RG->ViewValue = $this->RG->CurrentValue;
			$this->RG->ViewCustomAttributes = "";

			// Profissao
			$this->Profissao->ViewValue = $this->Profissao->CurrentValue;
			$this->Profissao->ViewCustomAttributes = "";

			// Email
			$this->_Email->ViewValue = $this->_Email->CurrentValue;
			$this->_Email->ViewCustomAttributes = "";

			// TelefoneRes
			$this->TelefoneRes->ViewValue = $this->TelefoneRes->CurrentValue;
			$this->TelefoneRes->ViewCustomAttributes = "";

			// Celular_1
			$this->Celular_1->ViewValue = $this->Celular_1->CurrentValue;
			$this->Celular_1->ViewCustomAttributes = "";

			// Celular_2
			$this->Celular_2->ViewValue = $this->Celular_2->CurrentValue;
			$this->Celular_2->ViewCustomAttributes = "";

			// Endereco
			$this->Endereco->ViewValue = $this->Endereco->CurrentValue;
			$this->Endereco->ViewCustomAttributes = "";

			// Complemento
			$this->Complemento->ViewValue = $this->Complemento->CurrentValue;
			$this->Complemento->ViewCustomAttributes = "";

			// Bairro
			$this->Bairro->ViewValue = $this->Bairro->CurrentValue;
			$this->Bairro->ViewCustomAttributes = "";

			// Cidade
			$this->Cidade->ViewValue = $this->Cidade->CurrentValue;
			$this->Cidade->ViewCustomAttributes = "";

			// UF
			$this->UF->ViewValue = $this->UF->CurrentValue;
			$this->UF->ViewCustomAttributes = "";

			// CEP
			$this->CEP->ViewValue = $this->CEP->CurrentValue;
			$this->CEP->ViewCustomAttributes = "";

			// GrauEscolaridade
			if (strval($this->GrauEscolaridade->CurrentValue) <> "") {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->GrauEscolaridade->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `Id`, `Escolaridade` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `escolaridade`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->GrauEscolaridade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Escolaridade` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->GrauEscolaridade->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->GrauEscolaridade->ViewValue = $this->GrauEscolaridade->CurrentValue;
				}
			} else {
				$this->GrauEscolaridade->ViewValue = NULL;
			}
			$this->GrauEscolaridade->ViewCustomAttributes = "";

			// Curso
			$this->Curso->ViewValue = $this->Curso->CurrentValue;
			$this->Curso->ViewCustomAttributes = "";

			// Nome_do_Pai
			$this->Nome_do_Pai->ViewValue = $this->Nome_do_Pai->CurrentValue;
			$this->Nome_do_Pai->ViewCustomAttributes = "";

			// Nome_da_Mae
			$this->Nome_da_Mae->ViewValue = $this->Nome_da_Mae->CurrentValue;
			$this->Nome_da_Mae->ViewCustomAttributes = "";

			// Data_Casamento
			$this->Data_Casamento->ViewValue = $this->Data_Casamento->CurrentValue;
			$this->Data_Casamento->ViewValue = ew_FormatDateTime($this->Data_Casamento->ViewValue, 7);
			$this->Data_Casamento->ViewCustomAttributes = "";

			// Conjuge
			$this->Conjuge->ViewValue = $this->Conjuge->CurrentValue;
			$this->Conjuge->ViewCustomAttributes = "";

			// N_Filhos
			$this->N_Filhos->ViewValue = $this->N_Filhos->CurrentValue;
			$this->N_Filhos->ViewCustomAttributes = "";

			// Empresa_trabalha
			$this->Empresa_trabalha->ViewValue = $this->Empresa_trabalha->CurrentValue;
			$this->Empresa_trabalha->ViewCustomAttributes = "";

			// Fone_Empresa
			$this->Fone_Empresa->ViewValue = $this->Fone_Empresa->CurrentValue;
			$this->Fone_Empresa->ViewCustomAttributes = "";

			// Anotacoes
			$this->Anotacoes->ViewValue = $this->Anotacoes->CurrentValue;
			if (!is_null($this->Anotacoes->ViewValue)) $this->Anotacoes->ViewValue = str_replace("\n", "<br>", $this->Anotacoes->ViewValue); 
			$this->Anotacoes->ViewCustomAttributes = "";

			// Celula
			if (strval($this->Celula->CurrentValue) <> "") {
				$sFilterWrk = "`Id_celula`" . ew_SearchString("=", $this->Celula->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `Id_celula`, `NomeCelula` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `celulas`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Celula, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `NomeCelula` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->Celula->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->Celula->ViewValue = $this->Celula->CurrentValue;
				}
			} else {
				$this->Celula->ViewValue = NULL;
			}
			$this->Celula->ViewCustomAttributes = "";

			// Nome_da_Familia
			$this->Nome_da_Familia->ViewValue = $this->Nome_da_Familia->CurrentValue;
			$this->Nome_da_Familia->ViewCustomAttributes = "";

			// Situacao
			if (strval($this->Situacao->CurrentValue) <> "") {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->Situacao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `Id`, `Situacao` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `situacao_membro`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Situacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Situacao` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->Situacao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->Situacao->ViewValue = $this->Situacao->CurrentValue;
				}
			} else {
				$this->Situacao->ViewValue = NULL;
			}
			$this->Situacao->ViewCustomAttributes = "";

			// Data_batismo
			$this->Data_batismo->ViewValue = $this->Data_batismo->CurrentValue;
			$this->Data_batismo->ViewValue = ew_FormatDateTime($this->Data_batismo->ViewValue, 7);
			$this->Data_batismo->ViewCustomAttributes = "";

			// Da_Igreja
			if (strval($this->Da_Igreja->CurrentValue) <> "") {
				$sFilterWrk = "`Id_igreja`" . ew_SearchString("=", $this->Da_Igreja->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `Id_igreja`, `Igreja` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `igrejas`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Da_Igreja, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->Da_Igreja->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->Da_Igreja->ViewValue = $this->Da_Igreja->CurrentValue;
				}
			} else {
				$this->Da_Igreja->ViewValue = NULL;
			}
			$this->Da_Igreja->ViewCustomAttributes = "";

			// CargoMinisterial
			if (strval($this->CargoMinisterial->CurrentValue) <> "") {
				$sFilterWrk = "`id_cgm`" . ew_SearchString("=", $this->CargoMinisterial->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_cgm`, `Cargo_Ministerial` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cargosministeriais`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->CargoMinisterial, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Cargo_Ministerial` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->CargoMinisterial->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->CargoMinisterial->ViewValue = $this->CargoMinisterial->CurrentValue;
				}
			} else {
				$this->CargoMinisterial->ViewValue = NULL;
			}
			$this->CargoMinisterial->ViewCustomAttributes = "";

			// Admissao
			$this->Admissao->ViewValue = $this->Admissao->CurrentValue;
			$this->Admissao->ViewValue = ew_FormatDateTime($this->Admissao->ViewValue, 7);
			$this->Admissao->ViewCustomAttributes = "";

			// Tipo_Admissao
			if (strval($this->Tipo_Admissao->CurrentValue) <> "") {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->Tipo_Admissao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `Id`, `Tipo_Admissao` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_admissao`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Tipo_Admissao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Tipo_Admissao` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->Tipo_Admissao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->Tipo_Admissao->ViewValue = $this->Tipo_Admissao->CurrentValue;
				}
			} else {
				$this->Tipo_Admissao->ViewValue = NULL;
			}
			$this->Tipo_Admissao->ViewCustomAttributes = "";

			// Funcao
			if (strval($this->Funcao->CurrentValue) <> "") {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->Funcao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `Id`, `Funcao` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `funcoes_exerce`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Funcao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Funcao` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->Funcao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->Funcao->ViewValue = $this->Funcao->CurrentValue;
				}
			} else {
				$this->Funcao->ViewValue = NULL;
			}
			$this->Funcao->ViewCustomAttributes = "";

			// Rede_Ministerial
			if (strval($this->Rede_Ministerial->CurrentValue) <> "") {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->Rede_Ministerial->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `Id`, `Rede_Ministerial` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `rede_ministerial`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Rede_Ministerial, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Rede_Ministerial` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->Rede_Ministerial->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->Rede_Ministerial->ViewValue = $this->Rede_Ministerial->CurrentValue;
				}
			} else {
				$this->Rede_Ministerial->ViewValue = NULL;
			}
			$this->Rede_Ministerial->ViewCustomAttributes = "";

			// Foto
			$this->Foto->LinkCustomAttributes = "";
			if (!ew_Empty($this->Foto->Upload->DbValue)) {
				$this->Foto->HrefValue = ew_UploadPathEx(FALSE, $this->Foto->UploadPath) . $this->Foto->Upload->DbValue; // Add prefix/suffix
				$this->Foto->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->Foto->HrefValue = ew_ConvertFullUrl($this->Foto->HrefValue);
			} else {
				$this->Foto->HrefValue = "";
			}
			$this->Foto->HrefValue2 = $this->Foto->UploadPath . $this->Foto->Upload->DbValue;
			$this->Foto->TooltipValue = "";
			if ($this->Foto->UseColorbox) {
				$this->Foto->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->Foto->LinkAttrs["data-rel"] = "membro_x_Foto";
				$this->Foto->LinkAttrs["class"] = "ewLightbox";
			}

			// Matricula
			$this->Matricula->LinkCustomAttributes = "";
			$this->Matricula->HrefValue = "";
			$this->Matricula->TooltipValue = "";

			// Nome
			$this->Nome->LinkCustomAttributes = "";
			$this->Nome->HrefValue = "";
			$this->Nome->TooltipValue = "";

			// Sexo
			$this->Sexo->LinkCustomAttributes = "";
			$this->Sexo->HrefValue = "";
			$this->Sexo->TooltipValue = "";

			// DataNasc
			$this->DataNasc->LinkCustomAttributes = "";
			$this->DataNasc->HrefValue = "";
			$this->DataNasc->TooltipValue = "";

			// Nacionalidade
			$this->Nacionalidade->LinkCustomAttributes = "";
			$this->Nacionalidade->HrefValue = "";
			$this->Nacionalidade->TooltipValue = "";

			// EstadoCivil
			$this->EstadoCivil->LinkCustomAttributes = "";
			$this->EstadoCivil->HrefValue = "";
			$this->EstadoCivil->TooltipValue = "";

			// CPF
			$this->CPF->LinkCustomAttributes = "";
			$this->CPF->HrefValue = "";
			$this->CPF->TooltipValue = "";

			// RG
			$this->RG->LinkCustomAttributes = "";
			$this->RG->HrefValue = "";
			$this->RG->TooltipValue = "";

			// Profissao
			$this->Profissao->LinkCustomAttributes = "";
			$this->Profissao->HrefValue = "";
			$this->Profissao->TooltipValue = "";

			// Email
			$this->_Email->LinkCustomAttributes = "";
			$this->_Email->HrefValue = "";
			$this->_Email->TooltipValue = "";

			// TelefoneRes
			$this->TelefoneRes->LinkCustomAttributes = "";
			$this->TelefoneRes->HrefValue = "";
			$this->TelefoneRes->TooltipValue = "";

			// Celular_1
			$this->Celular_1->LinkCustomAttributes = "";
			$this->Celular_1->HrefValue = "";
			$this->Celular_1->TooltipValue = "";

			// Celular_2
			$this->Celular_2->LinkCustomAttributes = "";
			$this->Celular_2->HrefValue = "";
			$this->Celular_2->TooltipValue = "";

			// Endereco
			$this->Endereco->LinkCustomAttributes = "";
			$this->Endereco->HrefValue = "";
			$this->Endereco->TooltipValue = "";

			// Complemento
			$this->Complemento->LinkCustomAttributes = "";
			$this->Complemento->HrefValue = "";
			$this->Complemento->TooltipValue = "";

			// Bairro
			$this->Bairro->LinkCustomAttributes = "";
			$this->Bairro->HrefValue = "";
			$this->Bairro->TooltipValue = "";

			// Cidade
			$this->Cidade->LinkCustomAttributes = "";
			$this->Cidade->HrefValue = "";
			$this->Cidade->TooltipValue = "";

			// UF
			$this->UF->LinkCustomAttributes = "";
			$this->UF->HrefValue = "";
			$this->UF->TooltipValue = "";

			// CEP
			$this->CEP->LinkCustomAttributes = "";
			$this->CEP->HrefValue = "";
			$this->CEP->TooltipValue = "";

			// GrauEscolaridade
			$this->GrauEscolaridade->LinkCustomAttributes = "";
			$this->GrauEscolaridade->HrefValue = "";
			$this->GrauEscolaridade->TooltipValue = "";

			// Curso
			$this->Curso->LinkCustomAttributes = "";
			$this->Curso->HrefValue = "";
			$this->Curso->TooltipValue = "";

			// Nome_do_Pai
			$this->Nome_do_Pai->LinkCustomAttributes = "";
			$this->Nome_do_Pai->HrefValue = "";
			$this->Nome_do_Pai->TooltipValue = "";

			// Nome_da_Mae
			$this->Nome_da_Mae->LinkCustomAttributes = "";
			$this->Nome_da_Mae->HrefValue = "";
			$this->Nome_da_Mae->TooltipValue = "";

			// Data_Casamento
			$this->Data_Casamento->LinkCustomAttributes = "";
			$this->Data_Casamento->HrefValue = "";
			$this->Data_Casamento->TooltipValue = "";

			// Conjuge
			$this->Conjuge->LinkCustomAttributes = "";
			$this->Conjuge->HrefValue = "";
			$this->Conjuge->TooltipValue = "";

			// N_Filhos
			$this->N_Filhos->LinkCustomAttributes = "";
			$this->N_Filhos->HrefValue = "";
			$this->N_Filhos->TooltipValue = "";

			// Empresa_trabalha
			$this->Empresa_trabalha->LinkCustomAttributes = "";
			$this->Empresa_trabalha->HrefValue = "";
			$this->Empresa_trabalha->TooltipValue = "";

			// Fone_Empresa
			$this->Fone_Empresa->LinkCustomAttributes = "";
			$this->Fone_Empresa->HrefValue = "";
			$this->Fone_Empresa->TooltipValue = "";

			// Anotacoes
			$this->Anotacoes->LinkCustomAttributes = "";
			$this->Anotacoes->HrefValue = "";
			$this->Anotacoes->TooltipValue = "";

			// Celula
			$this->Celula->LinkCustomAttributes = "";
			$this->Celula->HrefValue = "";
			$this->Celula->TooltipValue = "";

			// Nome_da_Familia
			$this->Nome_da_Familia->LinkCustomAttributes = "";
			$this->Nome_da_Familia->HrefValue = "";
			$this->Nome_da_Familia->TooltipValue = "";

			// Situacao
			$this->Situacao->LinkCustomAttributes = "";
			$this->Situacao->HrefValue = "";
			$this->Situacao->TooltipValue = "";

			// Data_batismo
			$this->Data_batismo->LinkCustomAttributes = "";
			$this->Data_batismo->HrefValue = "";
			$this->Data_batismo->TooltipValue = "";

			// Da_Igreja
			$this->Da_Igreja->LinkCustomAttributes = "";
			$this->Da_Igreja->HrefValue = "";
			$this->Da_Igreja->TooltipValue = "";

			// CargoMinisterial
			$this->CargoMinisterial->LinkCustomAttributes = "";
			$this->CargoMinisterial->HrefValue = "";
			$this->CargoMinisterial->TooltipValue = "";

			// Admissao
			$this->Admissao->LinkCustomAttributes = "";
			$this->Admissao->HrefValue = "";
			$this->Admissao->TooltipValue = "";

			// Tipo_Admissao
			$this->Tipo_Admissao->LinkCustomAttributes = "";
			$this->Tipo_Admissao->HrefValue = "";
			$this->Tipo_Admissao->TooltipValue = "";

			// Funcao
			$this->Funcao->LinkCustomAttributes = "";
			$this->Funcao->HrefValue = "";
			$this->Funcao->TooltipValue = "";

			// Rede_Ministerial
			$this->Rede_Ministerial->LinkCustomAttributes = "";
			$this->Rede_Ministerial->HrefValue = "";
			$this->Rede_Ministerial->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
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
			if ($sMasterTblVar == "cargosministeriais") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_id_cgm"] <> "") {
					$GLOBALS["cargosministeriais"]->id_cgm->setQueryStringValue($_GET["fk_id_cgm"]);
					$this->CargoMinisterial->setQueryStringValue($GLOBALS["cargosministeriais"]->id_cgm->QueryStringValue);
					$this->CargoMinisterial->setSessionValue($this->CargoMinisterial->QueryStringValue);
					if (!is_numeric($GLOBALS["cargosministeriais"]->id_cgm->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
			if ($sMasterTblVar == "igrejas") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_Id_igreja"] <> "") {
					$GLOBALS["igrejas"]->Id_igreja->setQueryStringValue($_GET["fk_Id_igreja"]);
					$this->Da_Igreja->setQueryStringValue($GLOBALS["igrejas"]->Id_igreja->QueryStringValue);
					$this->Da_Igreja->setSessionValue($this->Da_Igreja->QueryStringValue);
					if (!is_numeric($GLOBALS["igrejas"]->Id_igreja->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
			if ($sMasterTblVar == "celulas") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_Id_celula"] <> "") {
					$GLOBALS["celulas"]->Id_celula->setQueryStringValue($_GET["fk_Id_celula"]);
					$this->Celula->setQueryStringValue($GLOBALS["celulas"]->Id_celula->QueryStringValue);
					$this->Celula->setSessionValue($this->Celula->QueryStringValue);
					if (!is_numeric($GLOBALS["celulas"]->Id_celula->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "cargosministeriais") {
				if ($this->CargoMinisterial->QueryStringValue == "") $this->CargoMinisterial->setSessionValue("");
			}
			if ($sMasterTblVar <> "igrejas") {
				if ($this->Da_Igreja->QueryStringValue == "") $this->Da_Igreja->setSessionValue("");
			}
			if ($sMasterTblVar <> "celulas") {
				if ($this->Celula->QueryStringValue == "") $this->Celula->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("dizimos", $DetailTblVar)) {
				if (!isset($GLOBALS["dizimos_grid"]))
					$GLOBALS["dizimos_grid"] = new cdizimos_grid;
				if ($GLOBALS["dizimos_grid"]->DetailView) {
					$GLOBALS["dizimos_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["dizimos_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["dizimos_grid"]->setStartRecordNumber(1);
					$GLOBALS["dizimos_grid"]->id_discipulo->FldIsDetailKey = TRUE;
					$GLOBALS["dizimos_grid"]->id_discipulo->CurrentValue = $this->Id_membro->CurrentValue;
					$GLOBALS["dizimos_grid"]->id_discipulo->setSessionValue($GLOBALS["dizimos_grid"]->id_discipulo->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "membrolist.php", "", $this->TableVar, TRUE);
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
if (!isset($membro_view)) $membro_view = new cmembro_view();

// Page init
$membro_view->Page_Init();

// Page main
$membro_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$membro_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var membro_view = new ew_Page("membro_view");
membro_view.PageID = "view"; // Page ID
var EW_PAGE_ID = membro_view.PageID; // For backward compatibility

// Form object
var fmembroview = new ew_Form("fmembroview");

// Form_CustomValidate event
fmembroview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmembroview.ValidateRequired = true;
<?php } else { ?>
fmembroview.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
fmembroview.MultiPage = new ew_MultiPage("fmembroview",
	[["x_Foto",5],["x_Matricula",1],["x_Nome",1],["x_Sexo",1],["x_DataNasc",1],["x_Nacionalidade",1],["x_EstadoCivil",1],["x_CPF",1],["x_RG",1],["x_Profissao",1],["x__Email",1],["x_TelefoneRes",1],["x_Celular_1",1],["x_Celular_2",1],["x_Endereco",2],["x_Complemento",2],["x_Bairro",2],["x_Cidade",2],["x_UF",2],["x_CEP",2],["x_GrauEscolaridade",2],["x_Curso",2],["x_Nome_do_Pai",3],["x_Nome_da_Mae",3],["x_Data_Casamento",3],["x_Conjuge",3],["x_N_Filhos",3],["x_Empresa_trabalha",3],["x_Fone_Empresa",3],["x_Anotacoes",3],["x_Celula",4],["x_Nome_da_Familia",4],["x_Situacao",4],["x_Data_batismo",4],["x_Da_Igreja",4],["x_CargoMinisterial",4],["x_Admissao",4],["x_Tipo_Admissao",4],["x_Funcao",4],["x_Rede_Ministerial",4]]
);

// Dynamic selection lists
fmembroview.Lists["x_GrauEscolaridade"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Escolaridade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembroview.Lists["x_Celula"] = {"LinkField":"x_Id_celula","Ajax":null,"AutoFill":false,"DisplayFields":["x_NomeCelula","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembroview.Lists["x_Situacao"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Situacao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembroview.Lists["x_Da_Igreja"] = {"LinkField":"x_Id_igreja","Ajax":null,"AutoFill":false,"DisplayFields":["x_Igreja","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembroview.Lists["x_CargoMinisterial"] = {"LinkField":"x_id_cgm","Ajax":null,"AutoFill":false,"DisplayFields":["x_Cargo_Ministerial","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembroview.Lists["x_Tipo_Admissao"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Tipo_Admissao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembroview.Lists["x_Funcao"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Funcao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembroview.Lists["x_Rede_Ministerial"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Rede_Ministerial","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $membro_view->ExportOptions->Render("body") ?>
<?php
	foreach ($membro_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $membro_view->ShowPageHeader(); ?>
<?php
$membro_view->ShowMessage();
?>
<form name="fmembroview" id="fmembroview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($membro_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $membro_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="membro">
<?php if ($membro->Export == "") { ?>
<div>
<div class="tabbable" id="membro_view">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_membro1" data-toggle="tab"><?php echo $membro->PageCaption(1) ?></a></li>
		<li><a href="#tab_membro2" data-toggle="tab"><?php echo $membro->PageCaption(2) ?></a></li>
		<li><a href="#tab_membro3" data-toggle="tab"><?php echo $membro->PageCaption(3) ?></a></li>
		<li><a href="#tab_membro4" data-toggle="tab"><?php echo $membro->PageCaption(4) ?></a></li>
		<li><a href="#tab_membro5" data-toggle="tab"><?php echo $membro->PageCaption(5) ?></a></li>
	</ul>
	<div class="tab-content">
<?php } ?>
<?php if ($membro->Export == "") { ?>
		<div class="tab-pane active" id="tab_membro1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($membro->Matricula->Visible) { // Matricula ?>
	<tr id="r_Matricula">
		<td><span id="elh_membro_Matricula"><?php echo $membro->Matricula->FldCaption() ?></span></td>
		<td<?php echo $membro->Matricula->CellAttributes() ?>>
<span id="el_membro_Matricula" class="form-group">
<span<?php echo $membro->Matricula->ViewAttributes() ?>>
<?php echo $membro->Matricula->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Nome->Visible) { // Nome ?>
	<tr id="r_Nome">
		<td><span id="elh_membro_Nome"><?php echo $membro->Nome->FldCaption() ?></span></td>
		<td<?php echo $membro->Nome->CellAttributes() ?>>
<span id="el_membro_Nome" class="form-group">
<span<?php echo $membro->Nome->ViewAttributes() ?>>
<?php echo $membro->Nome->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Sexo->Visible) { // Sexo ?>
	<tr id="r_Sexo">
		<td><span id="elh_membro_Sexo"><?php echo $membro->Sexo->FldCaption() ?></span></td>
		<td<?php echo $membro->Sexo->CellAttributes() ?>>
<span id="el_membro_Sexo" class="form-group">
<span<?php echo $membro->Sexo->ViewAttributes() ?>>
<?php echo $membro->Sexo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->DataNasc->Visible) { // DataNasc ?>
	<tr id="r_DataNasc">
		<td><span id="elh_membro_DataNasc"><?php echo $membro->DataNasc->FldCaption() ?></span></td>
		<td<?php echo $membro->DataNasc->CellAttributes() ?>>
<span id="el_membro_DataNasc" class="form-group">
<span<?php echo $membro->DataNasc->ViewAttributes() ?>>
<?php echo $membro->DataNasc->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Nacionalidade->Visible) { // Nacionalidade ?>
	<tr id="r_Nacionalidade">
		<td><span id="elh_membro_Nacionalidade"><?php echo $membro->Nacionalidade->FldCaption() ?></span></td>
		<td<?php echo $membro->Nacionalidade->CellAttributes() ?>>
<span id="el_membro_Nacionalidade" class="form-group">
<span<?php echo $membro->Nacionalidade->ViewAttributes() ?>>
<?php echo $membro->Nacionalidade->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->EstadoCivil->Visible) { // EstadoCivil ?>
	<tr id="r_EstadoCivil">
		<td><span id="elh_membro_EstadoCivil"><?php echo $membro->EstadoCivil->FldCaption() ?></span></td>
		<td<?php echo $membro->EstadoCivil->CellAttributes() ?>>
<span id="el_membro_EstadoCivil" class="form-group">
<span<?php echo $membro->EstadoCivil->ViewAttributes() ?>>
<?php echo $membro->EstadoCivil->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->CPF->Visible) { // CPF ?>
	<tr id="r_CPF">
		<td><span id="elh_membro_CPF"><?php echo $membro->CPF->FldCaption() ?></span></td>
		<td<?php echo $membro->CPF->CellAttributes() ?>>
<span id="el_membro_CPF" class="form-group">
<span<?php echo $membro->CPF->ViewAttributes() ?>>
<?php echo $membro->CPF->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->RG->Visible) { // RG ?>
	<tr id="r_RG">
		<td><span id="elh_membro_RG"><?php echo $membro->RG->FldCaption() ?></span></td>
		<td<?php echo $membro->RG->CellAttributes() ?>>
<span id="el_membro_RG" class="form-group">
<span<?php echo $membro->RG->ViewAttributes() ?>>
<?php echo $membro->RG->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Profissao->Visible) { // Profissao ?>
	<tr id="r_Profissao">
		<td><span id="elh_membro_Profissao"><?php echo $membro->Profissao->FldCaption() ?></span></td>
		<td<?php echo $membro->Profissao->CellAttributes() ?>>
<span id="el_membro_Profissao" class="form-group">
<span<?php echo $membro->Profissao->ViewAttributes() ?>>
<?php echo $membro->Profissao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->_Email->Visible) { // Email ?>
	<tr id="r__Email">
		<td><span id="elh_membro__Email"><?php echo $membro->_Email->FldCaption() ?></span></td>
		<td<?php echo $membro->_Email->CellAttributes() ?>>
<span id="el_membro__Email" class="form-group">
<span<?php echo $membro->_Email->ViewAttributes() ?>>
<?php echo $membro->_Email->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->TelefoneRes->Visible) { // TelefoneRes ?>
	<tr id="r_TelefoneRes">
		<td><span id="elh_membro_TelefoneRes"><?php echo $membro->TelefoneRes->FldCaption() ?></span></td>
		<td<?php echo $membro->TelefoneRes->CellAttributes() ?>>
<span id="el_membro_TelefoneRes" class="form-group">
<span<?php echo $membro->TelefoneRes->ViewAttributes() ?>>
<?php echo $membro->TelefoneRes->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Celular_1->Visible) { // Celular_1 ?>
	<tr id="r_Celular_1">
		<td><span id="elh_membro_Celular_1"><?php echo $membro->Celular_1->FldCaption() ?></span></td>
		<td<?php echo $membro->Celular_1->CellAttributes() ?>>
<span id="el_membro_Celular_1" class="form-group">
<span<?php echo $membro->Celular_1->ViewAttributes() ?>>
<?php echo $membro->Celular_1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Celular_2->Visible) { // Celular_2 ?>
	<tr id="r_Celular_2">
		<td><span id="elh_membro_Celular_2"><?php echo $membro->Celular_2->FldCaption() ?></span></td>
		<td<?php echo $membro->Celular_2->CellAttributes() ?>>
<span id="el_membro_Celular_2" class="form-group">
<span<?php echo $membro->Celular_2->ViewAttributes() ?>>
<?php echo $membro->Celular_2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($membro->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($membro->Export == "") { ?>
		<div class="tab-pane" id="tab_membro2">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($membro->Endereco->Visible) { // Endereco ?>
	<tr id="r_Endereco">
		<td><span id="elh_membro_Endereco"><?php echo $membro->Endereco->FldCaption() ?></span></td>
		<td<?php echo $membro->Endereco->CellAttributes() ?>>
<span id="el_membro_Endereco" class="form-group">
<span<?php echo $membro->Endereco->ViewAttributes() ?>>
<?php echo $membro->Endereco->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Complemento->Visible) { // Complemento ?>
	<tr id="r_Complemento">
		<td><span id="elh_membro_Complemento"><?php echo $membro->Complemento->FldCaption() ?></span></td>
		<td<?php echo $membro->Complemento->CellAttributes() ?>>
<span id="el_membro_Complemento" class="form-group">
<span<?php echo $membro->Complemento->ViewAttributes() ?>>
<?php echo $membro->Complemento->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Bairro->Visible) { // Bairro ?>
	<tr id="r_Bairro">
		<td><span id="elh_membro_Bairro"><?php echo $membro->Bairro->FldCaption() ?></span></td>
		<td<?php echo $membro->Bairro->CellAttributes() ?>>
<span id="el_membro_Bairro" class="form-group">
<span<?php echo $membro->Bairro->ViewAttributes() ?>>
<?php echo $membro->Bairro->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Cidade->Visible) { // Cidade ?>
	<tr id="r_Cidade">
		<td><span id="elh_membro_Cidade"><?php echo $membro->Cidade->FldCaption() ?></span></td>
		<td<?php echo $membro->Cidade->CellAttributes() ?>>
<span id="el_membro_Cidade" class="form-group">
<span<?php echo $membro->Cidade->ViewAttributes() ?>>
<?php echo $membro->Cidade->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->UF->Visible) { // UF ?>
	<tr id="r_UF">
		<td><span id="elh_membro_UF"><?php echo $membro->UF->FldCaption() ?></span></td>
		<td<?php echo $membro->UF->CellAttributes() ?>>
<span id="el_membro_UF" class="form-group">
<span<?php echo $membro->UF->ViewAttributes() ?>>
<?php echo $membro->UF->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->CEP->Visible) { // CEP ?>
	<tr id="r_CEP">
		<td><span id="elh_membro_CEP"><?php echo $membro->CEP->FldCaption() ?></span></td>
		<td<?php echo $membro->CEP->CellAttributes() ?>>
<span id="el_membro_CEP" class="form-group">
<span<?php echo $membro->CEP->ViewAttributes() ?>>
<?php echo $membro->CEP->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->GrauEscolaridade->Visible) { // GrauEscolaridade ?>
	<tr id="r_GrauEscolaridade">
		<td><span id="elh_membro_GrauEscolaridade"><?php echo $membro->GrauEscolaridade->FldCaption() ?></span></td>
		<td<?php echo $membro->GrauEscolaridade->CellAttributes() ?>>
<span id="el_membro_GrauEscolaridade" class="form-group">
<span<?php echo $membro->GrauEscolaridade->ViewAttributes() ?>>
<?php echo $membro->GrauEscolaridade->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Curso->Visible) { // Curso ?>
	<tr id="r_Curso">
		<td><span id="elh_membro_Curso"><?php echo $membro->Curso->FldCaption() ?></span></td>
		<td<?php echo $membro->Curso->CellAttributes() ?>>
<span id="el_membro_Curso" class="form-group">
<span<?php echo $membro->Curso->ViewAttributes() ?>>
<?php echo $membro->Curso->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($membro->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($membro->Export == "") { ?>
		<div class="tab-pane" id="tab_membro3">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($membro->Nome_do_Pai->Visible) { // Nome_do_Pai ?>
	<tr id="r_Nome_do_Pai">
		<td><span id="elh_membro_Nome_do_Pai"><?php echo $membro->Nome_do_Pai->FldCaption() ?></span></td>
		<td<?php echo $membro->Nome_do_Pai->CellAttributes() ?>>
<span id="el_membro_Nome_do_Pai" class="form-group">
<span<?php echo $membro->Nome_do_Pai->ViewAttributes() ?>>
<?php echo $membro->Nome_do_Pai->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Nome_da_Mae->Visible) { // Nome_da_Mae ?>
	<tr id="r_Nome_da_Mae">
		<td><span id="elh_membro_Nome_da_Mae"><?php echo $membro->Nome_da_Mae->FldCaption() ?></span></td>
		<td<?php echo $membro->Nome_da_Mae->CellAttributes() ?>>
<span id="el_membro_Nome_da_Mae" class="form-group">
<span<?php echo $membro->Nome_da_Mae->ViewAttributes() ?>>
<?php echo $membro->Nome_da_Mae->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Data_Casamento->Visible) { // Data_Casamento ?>
	<tr id="r_Data_Casamento">
		<td><span id="elh_membro_Data_Casamento"><?php echo $membro->Data_Casamento->FldCaption() ?></span></td>
		<td<?php echo $membro->Data_Casamento->CellAttributes() ?>>
<span id="el_membro_Data_Casamento" class="form-group">
<span<?php echo $membro->Data_Casamento->ViewAttributes() ?>>
<?php echo $membro->Data_Casamento->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Conjuge->Visible) { // Conjuge ?>
	<tr id="r_Conjuge">
		<td><span id="elh_membro_Conjuge"><?php echo $membro->Conjuge->FldCaption() ?></span></td>
		<td<?php echo $membro->Conjuge->CellAttributes() ?>>
<span id="el_membro_Conjuge" class="form-group">
<span<?php echo $membro->Conjuge->ViewAttributes() ?>>
<?php echo $membro->Conjuge->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->N_Filhos->Visible) { // N_Filhos ?>
	<tr id="r_N_Filhos">
		<td><span id="elh_membro_N_Filhos"><?php echo $membro->N_Filhos->FldCaption() ?></span></td>
		<td<?php echo $membro->N_Filhos->CellAttributes() ?>>
<span id="el_membro_N_Filhos" class="form-group">
<span<?php echo $membro->N_Filhos->ViewAttributes() ?>>
<?php echo $membro->N_Filhos->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Empresa_trabalha->Visible) { // Empresa_trabalha ?>
	<tr id="r_Empresa_trabalha">
		<td><span id="elh_membro_Empresa_trabalha"><?php echo $membro->Empresa_trabalha->FldCaption() ?></span></td>
		<td<?php echo $membro->Empresa_trabalha->CellAttributes() ?>>
<span id="el_membro_Empresa_trabalha" class="form-group">
<span<?php echo $membro->Empresa_trabalha->ViewAttributes() ?>>
<?php echo $membro->Empresa_trabalha->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Fone_Empresa->Visible) { // Fone_Empresa ?>
	<tr id="r_Fone_Empresa">
		<td><span id="elh_membro_Fone_Empresa"><?php echo $membro->Fone_Empresa->FldCaption() ?></span></td>
		<td<?php echo $membro->Fone_Empresa->CellAttributes() ?>>
<span id="el_membro_Fone_Empresa" class="form-group">
<span<?php echo $membro->Fone_Empresa->ViewAttributes() ?>>
<?php echo $membro->Fone_Empresa->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Anotacoes->Visible) { // Anotacoes ?>
	<tr id="r_Anotacoes">
		<td><span id="elh_membro_Anotacoes"><?php echo $membro->Anotacoes->FldCaption() ?></span></td>
		<td<?php echo $membro->Anotacoes->CellAttributes() ?>>
<span id="el_membro_Anotacoes" class="form-group">
<span<?php echo $membro->Anotacoes->ViewAttributes() ?>>
<?php echo $membro->Anotacoes->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($membro->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($membro->Export == "") { ?>
		<div class="tab-pane" id="tab_membro4">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($membro->Celula->Visible) { // Celula ?>
	<tr id="r_Celula">
		<td><span id="elh_membro_Celula"><?php echo $membro->Celula->FldCaption() ?></span></td>
		<td<?php echo $membro->Celula->CellAttributes() ?>>
<span id="el_membro_Celula" class="form-group">
<span<?php echo $membro->Celula->ViewAttributes() ?>>
<?php echo $membro->Celula->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Nome_da_Familia->Visible) { // Nome_da_Familia ?>
	<tr id="r_Nome_da_Familia">
		<td><span id="elh_membro_Nome_da_Familia"><?php echo $membro->Nome_da_Familia->FldCaption() ?></span></td>
		<td<?php echo $membro->Nome_da_Familia->CellAttributes() ?>>
<span id="el_membro_Nome_da_Familia" class="form-group">
<span<?php echo $membro->Nome_da_Familia->ViewAttributes() ?>>
<?php echo $membro->Nome_da_Familia->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Situacao->Visible) { // Situacao ?>
	<tr id="r_Situacao">
		<td><span id="elh_membro_Situacao"><?php echo $membro->Situacao->FldCaption() ?></span></td>
		<td<?php echo $membro->Situacao->CellAttributes() ?>>
<span id="el_membro_Situacao" class="form-group">
<span<?php echo $membro->Situacao->ViewAttributes() ?>>
<?php echo $membro->Situacao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Data_batismo->Visible) { // Data_batismo ?>
	<tr id="r_Data_batismo">
		<td><span id="elh_membro_Data_batismo"><?php echo $membro->Data_batismo->FldCaption() ?></span></td>
		<td<?php echo $membro->Data_batismo->CellAttributes() ?>>
<span id="el_membro_Data_batismo" class="form-group">
<span<?php echo $membro->Data_batismo->ViewAttributes() ?>>
<?php echo $membro->Data_batismo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Da_Igreja->Visible) { // Da_Igreja ?>
	<tr id="r_Da_Igreja">
		<td><span id="elh_membro_Da_Igreja"><?php echo $membro->Da_Igreja->FldCaption() ?></span></td>
		<td<?php echo $membro->Da_Igreja->CellAttributes() ?>>
<span id="el_membro_Da_Igreja" class="form-group">
<span<?php echo $membro->Da_Igreja->ViewAttributes() ?>>
<?php echo $membro->Da_Igreja->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->CargoMinisterial->Visible) { // CargoMinisterial ?>
	<tr id="r_CargoMinisterial">
		<td><span id="elh_membro_CargoMinisterial"><?php echo $membro->CargoMinisterial->FldCaption() ?></span></td>
		<td<?php echo $membro->CargoMinisterial->CellAttributes() ?>>
<span id="el_membro_CargoMinisterial" class="form-group">
<span<?php echo $membro->CargoMinisterial->ViewAttributes() ?>>
<?php echo $membro->CargoMinisterial->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Admissao->Visible) { // Admissao ?>
	<tr id="r_Admissao">
		<td><span id="elh_membro_Admissao"><?php echo $membro->Admissao->FldCaption() ?></span></td>
		<td<?php echo $membro->Admissao->CellAttributes() ?>>
<span id="el_membro_Admissao" class="form-group">
<span<?php echo $membro->Admissao->ViewAttributes() ?>>
<?php echo $membro->Admissao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Tipo_Admissao->Visible) { // Tipo_Admissao ?>
	<tr id="r_Tipo_Admissao">
		<td><span id="elh_membro_Tipo_Admissao"><?php echo $membro->Tipo_Admissao->FldCaption() ?></span></td>
		<td<?php echo $membro->Tipo_Admissao->CellAttributes() ?>>
<span id="el_membro_Tipo_Admissao" class="form-group">
<span<?php echo $membro->Tipo_Admissao->ViewAttributes() ?>>
<?php echo $membro->Tipo_Admissao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Funcao->Visible) { // Funcao ?>
	<tr id="r_Funcao">
		<td><span id="elh_membro_Funcao"><?php echo $membro->Funcao->FldCaption() ?></span></td>
		<td<?php echo $membro->Funcao->CellAttributes() ?>>
<span id="el_membro_Funcao" class="form-group">
<span<?php echo $membro->Funcao->ViewAttributes() ?>>
<?php echo $membro->Funcao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($membro->Rede_Ministerial->Visible) { // Rede_Ministerial ?>
	<tr id="r_Rede_Ministerial">
		<td><span id="elh_membro_Rede_Ministerial"><?php echo $membro->Rede_Ministerial->FldCaption() ?></span></td>
		<td<?php echo $membro->Rede_Ministerial->CellAttributes() ?>>
<span id="el_membro_Rede_Ministerial" class="form-group">
<span<?php echo $membro->Rede_Ministerial->ViewAttributes() ?>>
<?php echo $membro->Rede_Ministerial->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($membro->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($membro->Export == "") { ?>
		<div class="tab-pane" id="tab_membro5">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($membro->Foto->Visible) { // Foto ?>
	<tr id="r_Foto">
		<td><span id="elh_membro_Foto"><?php echo $membro->Foto->FldCaption() ?></span></td>
		<td<?php echo $membro->Foto->CellAttributes() ?>>
<span id="el_membro_Foto" class="form-group">
<span>
<?php echo ew_GetFileViewTag($membro->Foto, $membro->Foto->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($membro->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($membro->Export == "") { ?>
	</div>
</div>
</div>
<?php } ?>
<?php
	if (in_array("dizimos", explode(",", $membro->getCurrentDetailTable())) && $dizimos->DetailView) {
?>
<?php if ($membro->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("dizimos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "dizimosgrid.php" ?>
<?php } ?>
</form>
<script type="text/javascript">
fmembroview.Init();
</script>
<?php
$membro_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$membro_view->Page_Terminate();
?>
