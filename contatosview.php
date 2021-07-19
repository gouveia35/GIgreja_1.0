<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "contatosinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$contatos_view = NULL; // Initialize page object first

class ccontatos_view extends ccontatos {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{2B7992FC-5911-46A7-9310-01F4D4225C49}";

	// Table name
	var $TableName = 'contatos';

	// Page object name
	var $PageObjName = 'contatos_view';

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

		// Table object (contatos)
		if (!isset($GLOBALS["contatos"]) || get_class($GLOBALS["contatos"]) == "ccontatos") {
			$GLOBALS["contatos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["contatos"];
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
			define("EW_TABLE_NAME", 'contatos', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("contatoslist.php"));
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
		global $EW_EXPORT, $contatos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($contatos);
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
				$sReturnUrl = "contatoslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "contatoslist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "contatoslist.php"; // Not page request, return to list
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
		$this->Pessoa_Empresa->setDbValue($rs->fields('Pessoa_Empresa'));
		$this->Telefone_1->setDbValue($rs->fields('Telefone_1'));
		$this->Telefone_2->setDbValue($rs->fields('Telefone_2'));
		$this->Celular_1->setDbValue($rs->fields('Celular_1'));
		$this->Celular_2->setDbValue($rs->fields('Celular_2'));
		$this->EnderecoCompleto->setDbValue($rs->fields('EnderecoCompleto'));
		$this->EmailPessoal->setDbValue($rs->fields('EmailPessoal'));
		$this->EmailComercial->setDbValue($rs->fields('EmailComercial'));
		$this->Anotacoes->setDbValue($rs->fields('Anotacoes'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id->DbValue = $row['Id'];
		$this->Pessoa_Empresa->DbValue = $row['Pessoa_Empresa'];
		$this->Telefone_1->DbValue = $row['Telefone_1'];
		$this->Telefone_2->DbValue = $row['Telefone_2'];
		$this->Celular_1->DbValue = $row['Celular_1'];
		$this->Celular_2->DbValue = $row['Celular_2'];
		$this->EnderecoCompleto->DbValue = $row['EnderecoCompleto'];
		$this->EmailPessoal->DbValue = $row['EmailPessoal'];
		$this->EmailComercial->DbValue = $row['EmailComercial'];
		$this->Anotacoes->DbValue = $row['Anotacoes'];
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
		// Pessoa_Empresa
		// Telefone_1
		// Telefone_2
		// Celular_1
		// Celular_2
		// EnderecoCompleto
		// EmailPessoal
		// EmailComercial
		// Anotacoes

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// Pessoa_Empresa
			$this->Pessoa_Empresa->ViewValue = $this->Pessoa_Empresa->CurrentValue;
			$this->Pessoa_Empresa->ViewCustomAttributes = "";

			// Telefone_1
			$this->Telefone_1->ViewValue = $this->Telefone_1->CurrentValue;
			$this->Telefone_1->ViewCustomAttributes = "";

			// Telefone_2
			$this->Telefone_2->ViewValue = $this->Telefone_2->CurrentValue;
			$this->Telefone_2->ViewCustomAttributes = "";

			// Celular_1
			$this->Celular_1->ViewValue = $this->Celular_1->CurrentValue;
			$this->Celular_1->ViewCustomAttributes = "";

			// Celular_2
			$this->Celular_2->ViewValue = $this->Celular_2->CurrentValue;
			$this->Celular_2->ViewCustomAttributes = "";

			// EnderecoCompleto
			$this->EnderecoCompleto->ViewValue = $this->EnderecoCompleto->CurrentValue;
			if (!is_null($this->EnderecoCompleto->ViewValue)) $this->EnderecoCompleto->ViewValue = str_replace("\n", "<br>", $this->EnderecoCompleto->ViewValue); 
			$this->EnderecoCompleto->ViewCustomAttributes = "";

			// EmailPessoal
			$this->EmailPessoal->ViewValue = $this->EmailPessoal->CurrentValue;
			$this->EmailPessoal->ViewCustomAttributes = "";

			// EmailComercial
			$this->EmailComercial->ViewValue = $this->EmailComercial->CurrentValue;
			$this->EmailComercial->ViewCustomAttributes = "";

			// Anotacoes
			$this->Anotacoes->ViewValue = $this->Anotacoes->CurrentValue;
			if (!is_null($this->Anotacoes->ViewValue)) $this->Anotacoes->ViewValue = str_replace("\n", "<br>", $this->Anotacoes->ViewValue); 
			$this->Anotacoes->ViewCustomAttributes = "";

			// Pessoa_Empresa
			$this->Pessoa_Empresa->LinkCustomAttributes = "";
			$this->Pessoa_Empresa->HrefValue = "";
			$this->Pessoa_Empresa->TooltipValue = "";

			// Telefone_1
			$this->Telefone_1->LinkCustomAttributes = "";
			$this->Telefone_1->HrefValue = "";
			$this->Telefone_1->TooltipValue = "";

			// Telefone_2
			$this->Telefone_2->LinkCustomAttributes = "";
			$this->Telefone_2->HrefValue = "";
			$this->Telefone_2->TooltipValue = "";

			// Celular_1
			$this->Celular_1->LinkCustomAttributes = "";
			$this->Celular_1->HrefValue = "";
			$this->Celular_1->TooltipValue = "";

			// Celular_2
			$this->Celular_2->LinkCustomAttributes = "";
			$this->Celular_2->HrefValue = "";
			$this->Celular_2->TooltipValue = "";

			// EnderecoCompleto
			$this->EnderecoCompleto->LinkCustomAttributes = "";
			$this->EnderecoCompleto->HrefValue = "";
			$this->EnderecoCompleto->TooltipValue = "";

			// EmailPessoal
			$this->EmailPessoal->LinkCustomAttributes = "";
			$this->EmailPessoal->HrefValue = "";
			$this->EmailPessoal->TooltipValue = "";

			// EmailComercial
			$this->EmailComercial->LinkCustomAttributes = "";
			$this->EmailComercial->HrefValue = "";
			$this->EmailComercial->TooltipValue = "";

			// Anotacoes
			$this->Anotacoes->LinkCustomAttributes = "";
			$this->Anotacoes->HrefValue = "";
			$this->Anotacoes->TooltipValue = "";
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
		$Breadcrumb->Add("list", $this->TableVar, "contatoslist.php", "", $this->TableVar, TRUE);
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
if (!isset($contatos_view)) $contatos_view = new ccontatos_view();

// Page init
$contatos_view->Page_Init();

// Page main
$contatos_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$contatos_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var contatos_view = new ew_Page("contatos_view");
contatos_view.PageID = "view"; // Page ID
var EW_PAGE_ID = contatos_view.PageID; // For backward compatibility

// Form object
var fcontatosview = new ew_Form("fcontatosview");

// Form_CustomValidate event
fcontatosview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontatosview.ValidateRequired = true;
<?php } else { ?>
fcontatosview.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
fcontatosview.MultiPage = new ew_MultiPage("fcontatosview",
	[["x_Pessoa_Empresa",1],["x_Telefone_1",1],["x_Telefone_2",1],["x_Celular_1",1],["x_Celular_2",1],["x_EnderecoCompleto",2],["x_EmailPessoal",2],["x_EmailComercial",2],["x_Anotacoes",2]]
);

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $contatos_view->ExportOptions->Render("body") ?>
<?php
	foreach ($contatos_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $contatos_view->ShowPageHeader(); ?>
<?php
$contatos_view->ShowMessage();
?>
<form name="fcontatosview" id="fcontatosview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($contatos_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $contatos_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="contatos">
<?php if ($contatos->Export == "") { ?>
<div>
<div class="tabbable" id="contatos_view">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_contatos1" data-toggle="tab"><?php echo $contatos->PageCaption(1) ?></a></li>
		<li><a href="#tab_contatos2" data-toggle="tab"><?php echo $contatos->PageCaption(2) ?></a></li>
	</ul>
	<div class="tab-content">
<?php } ?>
<?php if ($contatos->Export == "") { ?>
		<div class="tab-pane active" id="tab_contatos1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($contatos->Pessoa_Empresa->Visible) { // Pessoa_Empresa ?>
	<tr id="r_Pessoa_Empresa">
		<td><span id="elh_contatos_Pessoa_Empresa"><?php echo $contatos->Pessoa_Empresa->FldCaption() ?></span></td>
		<td<?php echo $contatos->Pessoa_Empresa->CellAttributes() ?>>
<span id="el_contatos_Pessoa_Empresa" class="form-group">
<span<?php echo $contatos->Pessoa_Empresa->ViewAttributes() ?>>
<?php echo $contatos->Pessoa_Empresa->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contatos->Telefone_1->Visible) { // Telefone_1 ?>
	<tr id="r_Telefone_1">
		<td><span id="elh_contatos_Telefone_1"><?php echo $contatos->Telefone_1->FldCaption() ?></span></td>
		<td<?php echo $contatos->Telefone_1->CellAttributes() ?>>
<span id="el_contatos_Telefone_1" class="form-group">
<span<?php echo $contatos->Telefone_1->ViewAttributes() ?>>
<?php echo $contatos->Telefone_1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contatos->Telefone_2->Visible) { // Telefone_2 ?>
	<tr id="r_Telefone_2">
		<td><span id="elh_contatos_Telefone_2"><?php echo $contatos->Telefone_2->FldCaption() ?></span></td>
		<td<?php echo $contatos->Telefone_2->CellAttributes() ?>>
<span id="el_contatos_Telefone_2" class="form-group">
<span<?php echo $contatos->Telefone_2->ViewAttributes() ?>>
<?php echo $contatos->Telefone_2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contatos->Celular_1->Visible) { // Celular_1 ?>
	<tr id="r_Celular_1">
		<td><span id="elh_contatos_Celular_1"><?php echo $contatos->Celular_1->FldCaption() ?></span></td>
		<td<?php echo $contatos->Celular_1->CellAttributes() ?>>
<span id="el_contatos_Celular_1" class="form-group">
<span<?php echo $contatos->Celular_1->ViewAttributes() ?>>
<?php echo $contatos->Celular_1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contatos->Celular_2->Visible) { // Celular_2 ?>
	<tr id="r_Celular_2">
		<td><span id="elh_contatos_Celular_2"><?php echo $contatos->Celular_2->FldCaption() ?></span></td>
		<td<?php echo $contatos->Celular_2->CellAttributes() ?>>
<span id="el_contatos_Celular_2" class="form-group">
<span<?php echo $contatos->Celular_2->ViewAttributes() ?>>
<?php echo $contatos->Celular_2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($contatos->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($contatos->Export == "") { ?>
		<div class="tab-pane" id="tab_contatos2">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($contatos->EnderecoCompleto->Visible) { // EnderecoCompleto ?>
	<tr id="r_EnderecoCompleto">
		<td><span id="elh_contatos_EnderecoCompleto"><?php echo $contatos->EnderecoCompleto->FldCaption() ?></span></td>
		<td<?php echo $contatos->EnderecoCompleto->CellAttributes() ?>>
<span id="el_contatos_EnderecoCompleto" class="form-group">
<span<?php echo $contatos->EnderecoCompleto->ViewAttributes() ?>>
<?php echo $contatos->EnderecoCompleto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contatos->EmailPessoal->Visible) { // EmailPessoal ?>
	<tr id="r_EmailPessoal">
		<td><span id="elh_contatos_EmailPessoal"><?php echo $contatos->EmailPessoal->FldCaption() ?></span></td>
		<td<?php echo $contatos->EmailPessoal->CellAttributes() ?>>
<span id="el_contatos_EmailPessoal" class="form-group">
<span<?php echo $contatos->EmailPessoal->ViewAttributes() ?>>
<?php echo $contatos->EmailPessoal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contatos->EmailComercial->Visible) { // EmailComercial ?>
	<tr id="r_EmailComercial">
		<td><span id="elh_contatos_EmailComercial"><?php echo $contatos->EmailComercial->FldCaption() ?></span></td>
		<td<?php echo $contatos->EmailComercial->CellAttributes() ?>>
<span id="el_contatos_EmailComercial" class="form-group">
<span<?php echo $contatos->EmailComercial->ViewAttributes() ?>>
<?php echo $contatos->EmailComercial->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contatos->Anotacoes->Visible) { // Anotacoes ?>
	<tr id="r_Anotacoes">
		<td><span id="elh_contatos_Anotacoes"><?php echo $contatos->Anotacoes->FldCaption() ?></span></td>
		<td<?php echo $contatos->Anotacoes->CellAttributes() ?>>
<span id="el_contatos_Anotacoes" class="form-group">
<span<?php echo $contatos->Anotacoes->ViewAttributes() ?>>
<?php echo $contatos->Anotacoes->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($contatos->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($contatos->Export == "") { ?>
	</div>
</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fcontatosview.Init();
</script>
<?php
$contatos_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$contatos_view->Page_Terminate();
?>
