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

$membro_list = NULL; // Initialize page object first

class cmembro_list extends cmembro {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{2B7992FC-5911-46A7-9310-01F4D4225C49}";

	// Table name
	var $TableName = 'membro';

	// Page object name
	var $PageObjName = 'membro_list';

	// Grid form hidden field names
	var $FormName = 'fmembrolist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "membroadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "membrodelete.php";
		$this->MultiUpdateUrl = "membroupdate.php";

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
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'membro', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();

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

		// Setup other options
		$this->SetupOtherOptions();

		// Set "checkbox" visible
		if (count($this->CustomActions) > 0)
			$this->ListOptions->Items["checkbox"]->Visible = TRUE;
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 10;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process custom action first
			$this->ProcessCustomAction();

			// Set up records per page
			$this->SetUpDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

			// Set up master detail parameters
			$this->SetUpMasterParms();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide export options
			if ($this->Export <> "" || $this->CurrentAction <> "")
				$this->ExportOptions->HideAllOptions();

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 10; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "cargosministeriais") {
			global $cargosministeriais;
			$rsmaster = $cargosministeriais->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("cargosministeriaislist.php"); // Return to master page
			} else {
				$cargosministeriais->LoadListRowValues($rsmaster);
				$cargosministeriais->RowType = EW_ROWTYPE_MASTER; // Master row
				$cargosministeriais->RenderListRow();
				$rsmaster->Close();
			}
		}

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "igrejas") {
			global $igrejas;
			$rsmaster = $igrejas->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("igrejaslist.php"); // Return to master page
			} else {
				$igrejas->LoadListRowValues($rsmaster);
				$igrejas->RowType = EW_ROWTYPE_MASTER; // Master row
				$igrejas->RenderListRow();
				$rsmaster->Close();
			}
		}

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "celulas") {
			global $celulas;
			$rsmaster = $celulas->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("celulaslist.php"); // Return to master page
			} else {
				$celulas->LoadListRowValues($rsmaster);
				$celulas->RowType = EW_ROWTYPE_MASTER; // Master row
				$celulas->RenderListRow();
				$rsmaster->Close();
			}
		}

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = EW_SELECT_LIMIT;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Set up number of records displayed per page
	function SetUpDisplayRecs() {
		$sWrk = @$_GET[EW_TABLE_REC_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayRecs = intval($sWrk);
			} else {
				if (strtolower($sWrk) == "all") { // Display all records
					$this->DisplayRecs = -1;
				} else {
					$this->DisplayRecs = 10; // Non-numeric, load default
				}
			}
			$this->setRecordsPerPage($this->DisplayRecs); // Save to Session

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->Id_membro->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->Id_membro->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->Matricula, $Default, FALSE); // Matricula
		$this->BuildSearchSql($sWhere, $this->Nome, $Default, FALSE); // Nome
		$this->BuildSearchSql($sWhere, $this->Sexo, $Default, FALSE); // Sexo
		$this->BuildSearchSql($sWhere, $this->EstadoCivil, $Default, FALSE); // EstadoCivil
		$this->BuildSearchSql($sWhere, $this->CPF, $Default, FALSE); // CPF
		$this->BuildSearchSql($sWhere, $this->RG, $Default, FALSE); // RG
		$this->BuildSearchSql($sWhere, $this->Profissao, $Default, FALSE); // Profissao
		$this->BuildSearchSql($sWhere, $this->_Email, $Default, FALSE); // Email
		$this->BuildSearchSql($sWhere, $this->Endereco, $Default, FALSE); // Endereco
		$this->BuildSearchSql($sWhere, $this->Bairro, $Default, FALSE); // Bairro
		$this->BuildSearchSql($sWhere, $this->Cidade, $Default, FALSE); // Cidade
		$this->BuildSearchSql($sWhere, $this->UF, $Default, FALSE); // UF
		$this->BuildSearchSql($sWhere, $this->GrauEscolaridade, $Default, FALSE); // GrauEscolaridade
		$this->BuildSearchSql($sWhere, $this->Data_Casamento, $Default, FALSE); // Data_Casamento
		$this->BuildSearchSql($sWhere, $this->Conjuge, $Default, FALSE); // Conjuge
		$this->BuildSearchSql($sWhere, $this->Celula, $Default, FALSE); // Celula
		$this->BuildSearchSql($sWhere, $this->Nome_da_Familia, $Default, FALSE); // Nome_da_Familia
		$this->BuildSearchSql($sWhere, $this->Situacao, $Default, FALSE); // Situacao
		$this->BuildSearchSql($sWhere, $this->Da_Igreja, $Default, FALSE); // Da_Igreja
		$this->BuildSearchSql($sWhere, $this->CargoMinisterial, $Default, FALSE); // CargoMinisterial
		$this->BuildSearchSql($sWhere, $this->Admissao, $Default, FALSE); // Admissao
		$this->BuildSearchSql($sWhere, $this->Tipo_Admissao, $Default, FALSE); // Tipo_Admissao
		$this->BuildSearchSql($sWhere, $this->Funcao, $Default, FALSE); // Funcao
		$this->BuildSearchSql($sWhere, $this->Rede_Ministerial, $Default, FALSE); // Rede_Ministerial

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->Matricula->AdvancedSearch->Save(); // Matricula
			$this->Nome->AdvancedSearch->Save(); // Nome
			$this->Sexo->AdvancedSearch->Save(); // Sexo
			$this->EstadoCivil->AdvancedSearch->Save(); // EstadoCivil
			$this->CPF->AdvancedSearch->Save(); // CPF
			$this->RG->AdvancedSearch->Save(); // RG
			$this->Profissao->AdvancedSearch->Save(); // Profissao
			$this->_Email->AdvancedSearch->Save(); // Email
			$this->Endereco->AdvancedSearch->Save(); // Endereco
			$this->Bairro->AdvancedSearch->Save(); // Bairro
			$this->Cidade->AdvancedSearch->Save(); // Cidade
			$this->UF->AdvancedSearch->Save(); // UF
			$this->GrauEscolaridade->AdvancedSearch->Save(); // GrauEscolaridade
			$this->Data_Casamento->AdvancedSearch->Save(); // Data_Casamento
			$this->Conjuge->AdvancedSearch->Save(); // Conjuge
			$this->Celula->AdvancedSearch->Save(); // Celula
			$this->Nome_da_Familia->AdvancedSearch->Save(); // Nome_da_Familia
			$this->Situacao->AdvancedSearch->Save(); // Situacao
			$this->Da_Igreja->AdvancedSearch->Save(); // Da_Igreja
			$this->CargoMinisterial->AdvancedSearch->Save(); // CargoMinisterial
			$this->Admissao->AdvancedSearch->Save(); // Admissao
			$this->Tipo_Admissao->AdvancedSearch->Save(); // Tipo_Admissao
			$this->Funcao->AdvancedSearch->Save(); // Funcao
			$this->Rede_Ministerial->AdvancedSearch->Save(); // Rede_Ministerial
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Check if search parm exists
	function CheckSearchParms() {
		if ($this->Matricula->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Nome->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Sexo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->EstadoCivil->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->CPF->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->RG->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Profissao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_Email->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Endereco->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Bairro->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Cidade->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->UF->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->GrauEscolaridade->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Data_Casamento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Conjuge->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Celula->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Nome_da_Familia->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Situacao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Da_Igreja->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->CargoMinisterial->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Admissao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Tipo_Admissao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Funcao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Rede_Ministerial->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->Matricula->AdvancedSearch->UnsetSession();
		$this->Nome->AdvancedSearch->UnsetSession();
		$this->Sexo->AdvancedSearch->UnsetSession();
		$this->EstadoCivil->AdvancedSearch->UnsetSession();
		$this->CPF->AdvancedSearch->UnsetSession();
		$this->RG->AdvancedSearch->UnsetSession();
		$this->Profissao->AdvancedSearch->UnsetSession();
		$this->_Email->AdvancedSearch->UnsetSession();
		$this->Endereco->AdvancedSearch->UnsetSession();
		$this->Bairro->AdvancedSearch->UnsetSession();
		$this->Cidade->AdvancedSearch->UnsetSession();
		$this->UF->AdvancedSearch->UnsetSession();
		$this->GrauEscolaridade->AdvancedSearch->UnsetSession();
		$this->Data_Casamento->AdvancedSearch->UnsetSession();
		$this->Conjuge->AdvancedSearch->UnsetSession();
		$this->Celula->AdvancedSearch->UnsetSession();
		$this->Nome_da_Familia->AdvancedSearch->UnsetSession();
		$this->Situacao->AdvancedSearch->UnsetSession();
		$this->Da_Igreja->AdvancedSearch->UnsetSession();
		$this->CargoMinisterial->AdvancedSearch->UnsetSession();
		$this->Admissao->AdvancedSearch->UnsetSession();
		$this->Tipo_Admissao->AdvancedSearch->UnsetSession();
		$this->Funcao->AdvancedSearch->UnsetSession();
		$this->Rede_Ministerial->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->Matricula->AdvancedSearch->Load();
		$this->Nome->AdvancedSearch->Load();
		$this->Sexo->AdvancedSearch->Load();
		$this->EstadoCivil->AdvancedSearch->Load();
		$this->CPF->AdvancedSearch->Load();
		$this->RG->AdvancedSearch->Load();
		$this->Profissao->AdvancedSearch->Load();
		$this->_Email->AdvancedSearch->Load();
		$this->Endereco->AdvancedSearch->Load();
		$this->Bairro->AdvancedSearch->Load();
		$this->Cidade->AdvancedSearch->Load();
		$this->UF->AdvancedSearch->Load();
		$this->GrauEscolaridade->AdvancedSearch->Load();
		$this->Data_Casamento->AdvancedSearch->Load();
		$this->Conjuge->AdvancedSearch->Load();
		$this->Celula->AdvancedSearch->Load();
		$this->Nome_da_Familia->AdvancedSearch->Load();
		$this->Situacao->AdvancedSearch->Load();
		$this->Da_Igreja->AdvancedSearch->Load();
		$this->CargoMinisterial->AdvancedSearch->Load();
		$this->Admissao->AdvancedSearch->Load();
		$this->Tipo_Admissao->AdvancedSearch->Load();
		$this->Funcao->AdvancedSearch->Load();
		$this->Rede_Ministerial->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->Foto, $bCtrl); // Foto
			$this->UpdateSort($this->Matricula, $bCtrl); // Matricula
			$this->UpdateSort($this->Nome, $bCtrl); // Nome
			$this->UpdateSort($this->Sexo, $bCtrl); // Sexo
			$this->UpdateSort($this->EstadoCivil, $bCtrl); // EstadoCivil
			$this->UpdateSort($this->CPF, $bCtrl); // CPF
			$this->UpdateSort($this->CargoMinisterial, $bCtrl); // CargoMinisterial
			$this->UpdateSort($this->Funcao, $bCtrl); // Funcao
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->CargoMinisterial->setSessionValue("");
				$this->Da_Igreja->setSessionValue("");
				$this->Celula->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->Foto->setSort("");
				$this->Matricula->setSort("");
				$this->Nome->setSort("");
				$this->Sexo->setSort("");
				$this->EstadoCivil->setSort("");
				$this->CPF->setSort("");
				$this->CargoMinisterial->setSort("");
				$this->Funcao->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = FALSE;

		// "detail_dizimos"
		$item = &$this->ListOptions->Add("detail_dizimos");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'dizimos') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["dizimos_grid"])) $GLOBALS["dizimos_grid"] = new cdizimos_grid;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$this->ListOptions->Add("details");
			$item->CssStyle = "white-space: nowrap;";
			$item->Visible = $this->ShowMultipleDetails;
			$item->OnLeft = FALSE;
			$item->ShowInButtonGroup = FALSE;
		}

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = TRUE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->CanView())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\" btn-danger ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_dizimos"
		$oListOpt = &$this->ListOptions->Items["detail_dizimos"];
		if ($Security->AllowList(CurrentProjectID() . 'dizimos')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("dizimos", "TblCaption");
			$body = "<a class=\"btn btn-primary btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("dizimoslist.php?" . EW_TABLE_SHOW_MASTER . "=membro&fk_Id_membro=" . urlencode(strval($this->Id_membro->CurrentValue)) . "") . "\"><i class='glyphicon glyphicon-th-list'></i> " . $body . "</a>";
			$links = "";
			if ($GLOBALS["dizimos_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'dizimos')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=dizimos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "dizimos";
			}
			if ($GLOBALS["dizimos_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'dizimos')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=dizimos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "dizimos";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-primary btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}
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
				$body .= "<button class=\"dropdown-toggle btn btn-primary btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$this->ListOptions->Items["details"];
			$oListOpt->Body = $body;
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->Id_membro->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"btn-success ewAddEdit ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["detail"];
		$DetailTableLink = "";
		$item = &$option->Add("detailadd_dizimos");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=dizimos") . "\">" . $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["dizimos"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["dizimos"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'dizimos') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "dizimos";
		}

		// Add multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$option->Add("detailsadd");
			$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=" . $DetailTableLink) . "\">" . $Language->Phrase("AddMasterDetailLink") . "</a>";
			$item->Visible = ($DetailTableLink <> "" && $Security->CanAdd());

			// Hide single master/detail items
			$ar = explode(",", $DetailTableLink);
			$cnt = count($ar);
			for ($i = 0; $i < $cnt; $i++) {
				if ($item = &$option->GetItem("detailadd_" . $ar[$i]))
					$item->Visible = FALSE;
			}
		}
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fmembrolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
			}

			// Hide grid edit, multi-delete and multi-update
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$item = &$option->GetItem("multidelete");
				if ($item) $item->Visible = FALSE;
				$item = &$option->GetItem("multiupdate");
				if ($item) $item->Visible = FALSE;
			}
	}

	// Process custom action
	function ProcessCustomAction() {
		global $conn, $Language, $Security;
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$rsuser = ($rs) ? $rs->GetRows() : array();
			if ($rs)
				$rs->Close();

			// Call row custom action event
			if (count($rsuser) > 0) {
				$conn->BeginTrans();
				foreach ($rsuser as $row) {
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCancelled")));
					}
				}
			}
		}
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : "";
		$item->Body = "<button type=\"button\" class=\"btn btn-warning ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fmembrolistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"membrosrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		$item->Visible = TRUE;

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch())
			$this->SearchOptions->HideAllOptions();
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// Matricula

		$this->Matricula->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Matricula"]);
		if ($this->Matricula->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Matricula->AdvancedSearch->SearchOperator = @$_GET["z_Matricula"];

		// Nome
		$this->Nome->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Nome"]);
		if ($this->Nome->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Nome->AdvancedSearch->SearchOperator = @$_GET["z_Nome"];

		// Sexo
		$this->Sexo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Sexo"]);
		if ($this->Sexo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Sexo->AdvancedSearch->SearchOperator = @$_GET["z_Sexo"];

		// EstadoCivil
		$this->EstadoCivil->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_EstadoCivil"]);
		if ($this->EstadoCivil->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->EstadoCivil->AdvancedSearch->SearchOperator = @$_GET["z_EstadoCivil"];

		// CPF
		$this->CPF->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_CPF"]);
		if ($this->CPF->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->CPF->AdvancedSearch->SearchOperator = @$_GET["z_CPF"];

		// RG
		$this->RG->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_RG"]);
		if ($this->RG->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->RG->AdvancedSearch->SearchOperator = @$_GET["z_RG"];

		// Profissao
		$this->Profissao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Profissao"]);
		if ($this->Profissao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Profissao->AdvancedSearch->SearchOperator = @$_GET["z_Profissao"];

		// Email
		$this->_Email->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__Email"]);
		if ($this->_Email->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_Email->AdvancedSearch->SearchOperator = @$_GET["z__Email"];

		// Endereco
		$this->Endereco->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Endereco"]);
		if ($this->Endereco->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Endereco->AdvancedSearch->SearchOperator = @$_GET["z_Endereco"];

		// Bairro
		$this->Bairro->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Bairro"]);
		if ($this->Bairro->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Bairro->AdvancedSearch->SearchOperator = @$_GET["z_Bairro"];

		// Cidade
		$this->Cidade->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Cidade"]);
		if ($this->Cidade->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Cidade->AdvancedSearch->SearchOperator = @$_GET["z_Cidade"];

		// UF
		$this->UF->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_UF"]);
		if ($this->UF->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->UF->AdvancedSearch->SearchOperator = @$_GET["z_UF"];

		// GrauEscolaridade
		$this->GrauEscolaridade->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_GrauEscolaridade"]);
		if ($this->GrauEscolaridade->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->GrauEscolaridade->AdvancedSearch->SearchOperator = @$_GET["z_GrauEscolaridade"];

		// Data_Casamento
		$this->Data_Casamento->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Data_Casamento"]);
		if ($this->Data_Casamento->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Data_Casamento->AdvancedSearch->SearchOperator = @$_GET["z_Data_Casamento"];
		$this->Data_Casamento->AdvancedSearch->SearchCondition = @$_GET["v_Data_Casamento"];
		$this->Data_Casamento->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_Data_Casamento"]);
		if ($this->Data_Casamento->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->Data_Casamento->AdvancedSearch->SearchOperator2 = @$_GET["w_Data_Casamento"];

		// Conjuge
		$this->Conjuge->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Conjuge"]);
		if ($this->Conjuge->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Conjuge->AdvancedSearch->SearchOperator = @$_GET["z_Conjuge"];

		// Celula
		$this->Celula->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Celula"]);
		if ($this->Celula->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Celula->AdvancedSearch->SearchOperator = @$_GET["z_Celula"];

		// Nome_da_Familia
		$this->Nome_da_Familia->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Nome_da_Familia"]);
		if ($this->Nome_da_Familia->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Nome_da_Familia->AdvancedSearch->SearchOperator = @$_GET["z_Nome_da_Familia"];

		// Situacao
		$this->Situacao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Situacao"]);
		if ($this->Situacao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Situacao->AdvancedSearch->SearchOperator = @$_GET["z_Situacao"];

		// Da_Igreja
		$this->Da_Igreja->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Da_Igreja"]);
		if ($this->Da_Igreja->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Da_Igreja->AdvancedSearch->SearchOperator = @$_GET["z_Da_Igreja"];

		// CargoMinisterial
		$this->CargoMinisterial->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_CargoMinisterial"]);
		if ($this->CargoMinisterial->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->CargoMinisterial->AdvancedSearch->SearchOperator = @$_GET["z_CargoMinisterial"];

		// Admissao
		$this->Admissao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Admissao"]);
		if ($this->Admissao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Admissao->AdvancedSearch->SearchOperator = @$_GET["z_Admissao"];
		$this->Admissao->AdvancedSearch->SearchCondition = @$_GET["v_Admissao"];
		$this->Admissao->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_Admissao"]);
		if ($this->Admissao->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->Admissao->AdvancedSearch->SearchOperator2 = @$_GET["w_Admissao"];

		// Tipo_Admissao
		$this->Tipo_Admissao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Tipo_Admissao"]);
		if ($this->Tipo_Admissao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Tipo_Admissao->AdvancedSearch->SearchOperator = @$_GET["z_Tipo_Admissao"];

		// Funcao
		$this->Funcao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Funcao"]);
		if ($this->Funcao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Funcao->AdvancedSearch->SearchOperator = @$_GET["z_Funcao"];

		// Rede_Ministerial
		$this->Rede_Ministerial->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Rede_Ministerial"]);
		if ($this->Rede_Ministerial->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Rede_Ministerial->AdvancedSearch->SearchOperator = @$_GET["z_Rede_Ministerial"];
	}

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
		$this->Id_membro->setDbValue($rs->fields('Id_membro'));
		$this->Foto->setDbValue($rs->fields('Foto'));
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
		$this->Foto->DbValue = $row['Foto'];
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("Id_membro")) <> "")
			$this->Id_membro->CurrentValue = $this->getKey("Id_membro"); // Id_membro
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// Id_membro

		$this->Id_membro->CellCssStyle = "white-space: nowrap;";

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
			$this->Foto->ViewValue = $this->Foto->CurrentValue;
			$this->Foto->ImageWidth = 30;
			$this->Foto->ImageAlt = $this->Foto->FldAlt();
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
			$this->Foto->HrefValue = "";
			$this->Foto->TooltipValue = "";

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

			// EstadoCivil
			$this->EstadoCivil->LinkCustomAttributes = "";
			$this->EstadoCivil->HrefValue = "";
			$this->EstadoCivil->TooltipValue = "";

			// CPF
			$this->CPF->LinkCustomAttributes = "";
			$this->CPF->HrefValue = "";
			$this->CPF->TooltipValue = "";

			// CargoMinisterial
			$this->CargoMinisterial->LinkCustomAttributes = "";
			$this->CargoMinisterial->HrefValue = "";
			$this->CargoMinisterial->TooltipValue = "";

			// Funcao
			$this->Funcao->LinkCustomAttributes = "";
			$this->Funcao->HrefValue = "";
			$this->Funcao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// Foto
			$this->Foto->EditAttrs["class"] = "form-control";
			$this->Foto->EditCustomAttributes = "readonly";
			$this->Foto->EditValue = ew_HtmlEncode($this->Foto->AdvancedSearch->SearchValue);

			// Matricula
			$this->Matricula->EditAttrs["class"] = "form-control";
			$this->Matricula->EditCustomAttributes = "";
			$this->Matricula->EditValue = ew_HtmlEncode($this->Matricula->AdvancedSearch->SearchValue);

			// Nome
			$this->Nome->EditAttrs["class"] = "form-control";
			$this->Nome->EditCustomAttributes = "";
			$this->Nome->EditValue = ew_HtmlEncode($this->Nome->AdvancedSearch->SearchValue);

			// Sexo
			$this->Sexo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Sexo->FldTagValue(1), $this->Sexo->FldTagCaption(1) <> "" ? $this->Sexo->FldTagCaption(1) : $this->Sexo->FldTagValue(1));
			$arwrk[] = array($this->Sexo->FldTagValue(2), $this->Sexo->FldTagCaption(2) <> "" ? $this->Sexo->FldTagCaption(2) : $this->Sexo->FldTagValue(2));
			$this->Sexo->EditValue = $arwrk;

			// EstadoCivil
			$this->EstadoCivil->EditAttrs["class"] = "form-control";
			$this->EstadoCivil->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->EstadoCivil->FldTagValue(1), $this->EstadoCivil->FldTagCaption(1) <> "" ? $this->EstadoCivil->FldTagCaption(1) : $this->EstadoCivil->FldTagValue(1));
			$arwrk[] = array($this->EstadoCivil->FldTagValue(2), $this->EstadoCivil->FldTagCaption(2) <> "" ? $this->EstadoCivil->FldTagCaption(2) : $this->EstadoCivil->FldTagValue(2));
			$arwrk[] = array($this->EstadoCivil->FldTagValue(3), $this->EstadoCivil->FldTagCaption(3) <> "" ? $this->EstadoCivil->FldTagCaption(3) : $this->EstadoCivil->FldTagValue(3));
			$arwrk[] = array($this->EstadoCivil->FldTagValue(4), $this->EstadoCivil->FldTagCaption(4) <> "" ? $this->EstadoCivil->FldTagCaption(4) : $this->EstadoCivil->FldTagValue(4));
			$arwrk[] = array($this->EstadoCivil->FldTagValue(5), $this->EstadoCivil->FldTagCaption(5) <> "" ? $this->EstadoCivil->FldTagCaption(5) : $this->EstadoCivil->FldTagValue(5));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->EstadoCivil->EditValue = $arwrk;

			// CPF
			$this->CPF->EditAttrs["class"] = "form-control";
			$this->CPF->EditCustomAttributes = "";
			$this->CPF->EditValue = ew_HtmlEncode($this->CPF->AdvancedSearch->SearchValue);

			// CargoMinisterial
			$this->CargoMinisterial->EditAttrs["class"] = "form-control";
			$this->CargoMinisterial->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `id_cgm`, `Cargo_Ministerial` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cargosministeriais`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->CargoMinisterial, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Cargo_Ministerial` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->CargoMinisterial->EditValue = $arwrk;

			// Funcao
			$this->Funcao->EditAttrs["class"] = "form-control";
			$this->Funcao->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `Id`, `Funcao` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `funcoes_exerce`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Funcao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Funcao` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Funcao->EditValue = $arwrk;
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->Matricula->AdvancedSearch->Load();
		$this->Nome->AdvancedSearch->Load();
		$this->Sexo->AdvancedSearch->Load();
		$this->EstadoCivil->AdvancedSearch->Load();
		$this->CPF->AdvancedSearch->Load();
		$this->RG->AdvancedSearch->Load();
		$this->Profissao->AdvancedSearch->Load();
		$this->_Email->AdvancedSearch->Load();
		$this->Endereco->AdvancedSearch->Load();
		$this->Bairro->AdvancedSearch->Load();
		$this->Cidade->AdvancedSearch->Load();
		$this->UF->AdvancedSearch->Load();
		$this->GrauEscolaridade->AdvancedSearch->Load();
		$this->Data_Casamento->AdvancedSearch->Load();
		$this->Conjuge->AdvancedSearch->Load();
		$this->Celula->AdvancedSearch->Load();
		$this->Nome_da_Familia->AdvancedSearch->Load();
		$this->Situacao->AdvancedSearch->Load();
		$this->Da_Igreja->AdvancedSearch->Load();
		$this->CargoMinisterial->AdvancedSearch->Load();
		$this->Admissao->AdvancedSearch->Load();
		$this->Tipo_Admissao->AdvancedSearch->Load();
		$this->Funcao->AdvancedSearch->Load();
		$this->Rede_Ministerial->AdvancedSearch->Load();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = FALSE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_membro\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_membro',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fmembrolist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = FALSE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = EW_SELECT_LIMIT;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";

		// Export master record
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "cargosministeriais") {
			global $cargosministeriais;
			if (!isset($cargosministeriais)) $cargosministeriais = new ccargosministeriais;
			$rsmaster = $cargosministeriais->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$Doc->Table = &$cargosministeriais;
					$cargosministeriais->ExportDocument($Doc, $rsmaster, 1, 1);
					$Doc->ExportEmptyRow();
					$Doc->Table = &$this;
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsmaster->Close();
			}
		}

		// Export master record
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "igrejas") {
			global $igrejas;
			if (!isset($igrejas)) $igrejas = new cigrejas;
			$rsmaster = $igrejas->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$Doc->Table = &$igrejas;
					$igrejas->ExportDocument($Doc, $rsmaster, 1, 1);
					$Doc->ExportEmptyRow();
					$Doc->Table = &$this;
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsmaster->Close();
			}
		}

		// Export master record
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "celulas") {
			global $celulas;
			if (!isset($celulas)) $celulas = new ccelulas;
			$rsmaster = $celulas->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$Doc->Table = &$celulas;
					$celulas->ExportDocument($Doc, $rsmaster, 1, 1);
					$Doc->ExportEmptyRow();
					$Doc->Table = &$this;
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsmaster->Close();
			}
		}
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Call Page Exported server event
		$this->Page_Exported();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED)
			echo ew_DebugMsg();

		// Output data
		$Doc->Export();
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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'membro';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
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
if (!isset($membro_list)) $membro_list = new cmembro_list();

// Page init
$membro_list->Page_Init();

// Page main
$membro_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$membro_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($membro->Export == "") { ?>
<script type="text/javascript">

// Page object
var membro_list = new ew_Page("membro_list");
membro_list.PageID = "list"; // Page ID
var EW_PAGE_ID = membro_list.PageID; // For backward compatibility

// Form object
var fmembrolist = new ew_Form("fmembrolist");
fmembrolist.FormKeyCountName = '<?php echo $membro_list->FormKeyCountName ?>';

// Form_CustomValidate event
fmembrolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmembrolist.ValidateRequired = true;
<?php } else { ?>
fmembrolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmembrolist.Lists["x_CargoMinisterial"] = {"LinkField":"x_id_cgm","Ajax":null,"AutoFill":false,"DisplayFields":["x_Cargo_Ministerial","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembrolist.Lists["x_Funcao"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Funcao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fmembrolistsrch = new ew_Form("fmembrolistsrch");

// Validate function for search
fmembrolistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fmembrolistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmembrolistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fmembrolistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fmembrolistsrch.Lists["x_CargoMinisterial"] = {"LinkField":"x_id_cgm","Ajax":null,"AutoFill":false,"DisplayFields":["x_Cargo_Ministerial","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembrolistsrch.Lists["x_Funcao"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Funcao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Init search panel as collapsed
if (fmembrolistsrch) fmembrolistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($membro->Export == "") { ?>
<div class="ewToolbar">
<?php if ($membro->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($membro_list->TotalRecs > 0 && $membro_list->ExportOptions->Visible()) { ?>
<?php $membro_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($membro_list->SearchOptions->Visible()) { ?>
<?php $membro_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($membro->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="ewSearchOption ewListOptionSeparator" style="white-space: nowrap;" data-name="button"><div class="btn-group ewButtonGroup">
<?php $help = ew_ExecuteScalar("Select txt from ajuda where pg = '".ew_CurrentPage()."'") ; 
if (strlen($help)>0){ ?>
	<button class="btn btn-default" type="button" title="" data-original-title="Ajuda desta p&aacute;gina" id="ajuda"><span data-phrase="SearchBtn" class="fa fa-question ewIcon" data-caption="Ajuda"></span></button>		
<?php } ?>	
</div></div>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if (($membro->Export == "") || (EW_EXPORT_MASTER_RECORD && $membro->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "cargosministeriaislist.php";
if ($membro_list->DbMasterFilter <> "" && $membro->getCurrentMasterTable() == "cargosministeriais") {
	if ($membro_list->MasterRecordExists) {
		if ($membro->getCurrentMasterTable() == $membro->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php include_once "cargosministeriaismaster.php" ?>
<?php
	}
}
?>
<?php
$gsMasterReturnUrl = "igrejaslist.php";
if ($membro_list->DbMasterFilter <> "" && $membro->getCurrentMasterTable() == "igrejas") {
	if ($membro_list->MasterRecordExists) {
		if ($membro->getCurrentMasterTable() == $membro->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php include_once "igrejasmaster.php" ?>
<?php
	}
}
?>
<?php
$gsMasterReturnUrl = "celulaslist.php";
if ($membro_list->DbMasterFilter <> "" && $membro->getCurrentMasterTable() == "celulas") {
	if ($membro_list->MasterRecordExists) {
		if ($membro->getCurrentMasterTable() == $membro->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php include_once "celulasmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($membro_list->TotalRecs <= 0)
			$membro_list->TotalRecs = $membro->SelectRecordCount();
	} else {
		if (!$membro_list->Recordset && ($membro_list->Recordset = $membro_list->LoadRecordset()))
			$membro_list->TotalRecs = $membro_list->Recordset->RecordCount();
	}
	$membro_list->StartRec = 1;
	if ($membro_list->DisplayRecs <= 0 || ($membro->Export <> "" && $membro->ExportAll)) // Display all records
		$membro_list->DisplayRecs = $membro_list->TotalRecs;
	if (!($membro->Export <> "" && $membro->ExportAll))
		$membro_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$membro_list->Recordset = $membro_list->LoadRecordset($membro_list->StartRec-1, $membro_list->DisplayRecs);

	// Set no record found message
	if ($membro->CurrentAction == "" && $membro_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$membro_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($membro_list->SearchWhere == "0=101")
			$membro_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$membro_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$membro_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($membro->Export == "" && $membro->CurrentAction == "") { ?>
<form name="fmembrolistsrch" id="fmembrolistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($membro_list->SearchWhere <> "") ? " in" : ""; ?>
<div id="fmembrolistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="membro">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$membro_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$membro->RowType = EW_ROWTYPE_SEARCH;

// Render row
$membro->ResetAttrs();
$membro_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($membro->Nome->Visible) { // Nome ?>
	<div id="xsc_Nome" class="ewCell form-group">
		<label for="x_Nome" class="ewSearchCaption ewLabel"><?php echo $membro->Nome->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Nome" id="z_Nome" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_Nome" name="x_Nome" id="x_Nome" size="65" maxlength="60" value="<?php echo $membro->Nome->EditValue ?>"<?php echo $membro->Nome->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($membro->CPF->Visible) { // CPF ?>
	<div id="xsc_CPF" class="ewCell form-group">
		<label for="x_CPF" class="ewSearchCaption ewLabel"><?php echo $membro->CPF->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_CPF" id="z_CPF" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_CPF" name="x_CPF" id="x_CPF" size="30" maxlength="15" value="<?php echo $membro->CPF->EditValue ?>"<?php echo $membro->CPF->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($membro->CargoMinisterial->Visible) { // CargoMinisterial ?>
	<div id="xsc_CargoMinisterial" class="ewCell form-group">
		<label for="x_CargoMinisterial" class="ewSearchCaption ewLabel"><?php echo $membro->CargoMinisterial->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_CargoMinisterial" id="z_CargoMinisterial" value="="></span>
		<span class="ewSearchField">
<?php if ($membro->CargoMinisterial->getSessionValue() <> "") { ?>
<span<?php echo $membro->CargoMinisterial->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $membro->CargoMinisterial->ViewValue ?></p></span>
<input type="hidden" id="x_CargoMinisterial" name="x_CargoMinisterial" value="<?php echo ew_HtmlEncode($membro->CargoMinisterial->AdvancedSearch->SearchValue) ?>">
<?php } else { ?>
<select data-field="x_CargoMinisterial" id="x_CargoMinisterial" name="x_CargoMinisterial"<?php echo $membro->CargoMinisterial->EditAttributes() ?>>
<?php
if (is_array($membro->CargoMinisterial->EditValue)) {
	$arwrk = $membro->CargoMinisterial->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($membro->CargoMinisterial->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fmembrolistsrch.Lists["x_CargoMinisterial"].Options = <?php echo (is_array($membro->CargoMinisterial->EditValue)) ? ew_ArrayToJson($membro->CargoMinisterial->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($membro->Funcao->Visible) { // Funcao ?>
	<div id="xsc_Funcao" class="ewCell form-group">
		<label for="x_Funcao" class="ewSearchCaption ewLabel"><?php echo $membro->Funcao->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Funcao" id="z_Funcao" value="="></span>
		<span class="ewSearchField">
<select data-field="x_Funcao" id="x_Funcao" name="x_Funcao"<?php echo $membro->Funcao->EditAttributes() ?>>
<?php
if (is_array($membro->Funcao->EditValue)) {
	$arwrk = $membro->Funcao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($membro->Funcao->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fmembrolistsrch.Lists["x_Funcao"].Options = <?php echo (is_array($membro->Funcao->EditValue)) ? ew_ArrayToJson($membro->Funcao->EditValue, 1) : "[]" ?>;
</script>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><i class='glyphicon glyphicon-search'></i>&nbsp;<?php echo $Language->Phrase("QuickSearchBtn") ?></button>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $membro_list->ShowPageHeader(); ?>
<?php
$membro_list->ShowMessage();
?>
<?php if ($membro_list->TotalRecs > 0 || $membro->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($membro->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($membro->CurrentAction <> "gridadd" && $membro->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($membro_list->Pager)) $membro_list->Pager = new cPrevNextPager($membro_list->StartRec, $membro_list->DisplayRecs, $membro_list->TotalRecs) ?>
<?php if ($membro_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($membro_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $membro_list->PageUrl() ?>start=<?php echo $membro_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($membro_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $membro_list->PageUrl() ?>start=<?php echo $membro_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $membro_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($membro_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $membro_list->PageUrl() ?>start=<?php echo $membro_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($membro_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $membro_list->PageUrl() ?>start=<?php echo $membro_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $membro_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $membro_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $membro_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $membro_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($membro_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="membro">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="10"<?php if ($membro_list->DisplayRecs == 10) { ?> selected="selected"<?php } ?>>10</option>
<option value="20"<?php if ($membro_list->DisplayRecs == 20) { ?> selected="selected"<?php } ?>>20</option>
<option value="50"<?php if ($membro_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($membro_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
<option value="500"<?php if ($membro_list->DisplayRecs == 500) { ?> selected="selected"<?php } ?>>500</option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($membro_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fmembrolist" id="fmembrolist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($membro_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $membro_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="membro">
<div id="gmp_membro" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($membro_list->TotalRecs > 0) { ?>
<table id="tbl_membrolist" class="table ewTable">
<?php echo $membro->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$membro_list->RenderListOptions();

// Render list options (header, left)
$membro_list->ListOptions->Render("header", "left");
?>
<?php if ($membro->Foto->Visible) { // Foto ?>
	<?php if ($membro->SortUrl($membro->Foto) == "") { ?>
		<th data-name="Foto"><div id="elh_membro_Foto" class="membro_Foto"><div class="ewTableHeaderCaption"><?php echo $membro->Foto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Foto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $membro->SortUrl($membro->Foto) ?>',2);"><div id="elh_membro_Foto" class="membro_Foto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membro->Foto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($membro->Foto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membro->Foto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($membro->Matricula->Visible) { // Matricula ?>
	<?php if ($membro->SortUrl($membro->Matricula) == "") { ?>
		<th data-name="Matricula"><div id="elh_membro_Matricula" class="membro_Matricula"><div class="ewTableHeaderCaption"><?php echo $membro->Matricula->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Matricula"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $membro->SortUrl($membro->Matricula) ?>',2);"><div id="elh_membro_Matricula" class="membro_Matricula">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membro->Matricula->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($membro->Matricula->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membro->Matricula->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($membro->Nome->Visible) { // Nome ?>
	<?php if ($membro->SortUrl($membro->Nome) == "") { ?>
		<th data-name="Nome"><div id="elh_membro_Nome" class="membro_Nome"><div class="ewTableHeaderCaption"><?php echo $membro->Nome->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Nome"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $membro->SortUrl($membro->Nome) ?>',2);"><div id="elh_membro_Nome" class="membro_Nome">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membro->Nome->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($membro->Nome->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membro->Nome->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($membro->Sexo->Visible) { // Sexo ?>
	<?php if ($membro->SortUrl($membro->Sexo) == "") { ?>
		<th data-name="Sexo"><div id="elh_membro_Sexo" class="membro_Sexo"><div class="ewTableHeaderCaption"><?php echo $membro->Sexo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Sexo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $membro->SortUrl($membro->Sexo) ?>',2);"><div id="elh_membro_Sexo" class="membro_Sexo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membro->Sexo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($membro->Sexo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membro->Sexo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($membro->EstadoCivil->Visible) { // EstadoCivil ?>
	<?php if ($membro->SortUrl($membro->EstadoCivil) == "") { ?>
		<th data-name="EstadoCivil"><div id="elh_membro_EstadoCivil" class="membro_EstadoCivil"><div class="ewTableHeaderCaption"><?php echo $membro->EstadoCivil->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="EstadoCivil"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $membro->SortUrl($membro->EstadoCivil) ?>',2);"><div id="elh_membro_EstadoCivil" class="membro_EstadoCivil">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membro->EstadoCivil->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($membro->EstadoCivil->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membro->EstadoCivil->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($membro->CPF->Visible) { // CPF ?>
	<?php if ($membro->SortUrl($membro->CPF) == "") { ?>
		<th data-name="CPF"><div id="elh_membro_CPF" class="membro_CPF"><div class="ewTableHeaderCaption"><?php echo $membro->CPF->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="CPF"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $membro->SortUrl($membro->CPF) ?>',2);"><div id="elh_membro_CPF" class="membro_CPF">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membro->CPF->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($membro->CPF->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membro->CPF->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($membro->CargoMinisterial->Visible) { // CargoMinisterial ?>
	<?php if ($membro->SortUrl($membro->CargoMinisterial) == "") { ?>
		<th data-name="CargoMinisterial"><div id="elh_membro_CargoMinisterial" class="membro_CargoMinisterial"><div class="ewTableHeaderCaption"><?php echo $membro->CargoMinisterial->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="CargoMinisterial"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $membro->SortUrl($membro->CargoMinisterial) ?>',2);"><div id="elh_membro_CargoMinisterial" class="membro_CargoMinisterial">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membro->CargoMinisterial->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($membro->CargoMinisterial->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membro->CargoMinisterial->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($membro->Funcao->Visible) { // Funcao ?>
	<?php if ($membro->SortUrl($membro->Funcao) == "") { ?>
		<th data-name="Funcao"><div id="elh_membro_Funcao" class="membro_Funcao"><div class="ewTableHeaderCaption"><?php echo $membro->Funcao->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Funcao"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $membro->SortUrl($membro->Funcao) ?>',2);"><div id="elh_membro_Funcao" class="membro_Funcao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membro->Funcao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($membro->Funcao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membro->Funcao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$membro_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($membro->ExportAll && $membro->Export <> "") {
	$membro_list->StopRec = $membro_list->TotalRecs;
} else {

	// Set the last record to display
	if ($membro_list->TotalRecs > $membro_list->StartRec + $membro_list->DisplayRecs - 1)
		$membro_list->StopRec = $membro_list->StartRec + $membro_list->DisplayRecs - 1;
	else
		$membro_list->StopRec = $membro_list->TotalRecs;
}
$membro_list->RecCnt = $membro_list->StartRec - 1;
if ($membro_list->Recordset && !$membro_list->Recordset->EOF) {
	$membro_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $membro_list->StartRec > 1)
		$membro_list->Recordset->Move($membro_list->StartRec - 1);
} elseif (!$membro->AllowAddDeleteRow && $membro_list->StopRec == 0) {
	$membro_list->StopRec = $membro->GridAddRowCount;
}

// Initialize aggregate
$membro->RowType = EW_ROWTYPE_AGGREGATEINIT;
$membro->ResetAttrs();
$membro_list->RenderRow();
while ($membro_list->RecCnt < $membro_list->StopRec) {
	$membro_list->RecCnt++;
	if (intval($membro_list->RecCnt) >= intval($membro_list->StartRec)) {
		$membro_list->RowCnt++;

		// Set up key count
		$membro_list->KeyCount = $membro_list->RowIndex;

		// Init row class and style
		$membro->ResetAttrs();
		$membro->CssClass = "";
		if ($membro->CurrentAction == "gridadd") {
		} else {
			$membro_list->LoadRowValues($membro_list->Recordset); // Load row values
		}
		$membro->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$membro->RowAttrs = array_merge($membro->RowAttrs, array('data-rowindex'=>$membro_list->RowCnt, 'id'=>'r' . $membro_list->RowCnt . '_membro', 'data-rowtype'=>$membro->RowType));

		// Render row
		$membro_list->RenderRow();

		// Render list options
		$membro_list->RenderListOptions();
?>
	<tr<?php echo $membro->RowAttributes() ?>>
<?php

// Render list options (body, left)
$membro_list->ListOptions->Render("body", "left", $membro_list->RowCnt);
?>
	<?php if ($membro->Foto->Visible) { // Foto ?>
		<td data-name="Foto"<?php echo $membro->Foto->CellAttributes() ?>>
<span>
<?php if (!ew_EmptyStr($membro->Foto->ListViewValue())) { ?><img src="<?php echo $membro->Foto->ListViewValue() ?>" alt=""<?php echo $membro->Foto->ViewAttributes() ?>><?php } ?></span>
<a id="<?php echo $membro_list->PageObjName . "_row_" . $membro_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($membro->Matricula->Visible) { // Matricula ?>
		<td data-name="Matricula"<?php echo $membro->Matricula->CellAttributes() ?>>
<span<?php echo $membro->Matricula->ViewAttributes() ?>>
<?php echo $membro->Matricula->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($membro->Nome->Visible) { // Nome ?>
		<td data-name="Nome"<?php echo $membro->Nome->CellAttributes() ?>>
<span<?php echo $membro->Nome->ViewAttributes() ?>>
<?php echo $membro->Nome->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($membro->Sexo->Visible) { // Sexo ?>
		<td data-name="Sexo"<?php echo $membro->Sexo->CellAttributes() ?>>
<span<?php echo $membro->Sexo->ViewAttributes() ?>>
<?php echo $membro->Sexo->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($membro->EstadoCivil->Visible) { // EstadoCivil ?>
		<td data-name="EstadoCivil"<?php echo $membro->EstadoCivil->CellAttributes() ?>>
<span<?php echo $membro->EstadoCivil->ViewAttributes() ?>>
<?php echo $membro->EstadoCivil->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($membro->CPF->Visible) { // CPF ?>
		<td data-name="CPF"<?php echo $membro->CPF->CellAttributes() ?>>
<span<?php echo $membro->CPF->ViewAttributes() ?>>
<?php echo $membro->CPF->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($membro->CargoMinisterial->Visible) { // CargoMinisterial ?>
		<td data-name="CargoMinisterial"<?php echo $membro->CargoMinisterial->CellAttributes() ?>>
<span<?php echo $membro->CargoMinisterial->ViewAttributes() ?>>
<?php echo $membro->CargoMinisterial->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($membro->Funcao->Visible) { // Funcao ?>
		<td data-name="Funcao"<?php echo $membro->Funcao->CellAttributes() ?>>
<span<?php echo $membro->Funcao->ViewAttributes() ?>>
<?php echo $membro->Funcao->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$membro_list->ListOptions->Render("body", "right", $membro_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($membro->CurrentAction <> "gridadd")
		$membro_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($membro->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($membro_list->Recordset)
	$membro_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($membro_list->TotalRecs == 0 && $membro->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($membro_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($membro->Export == "") { ?>
<script type="text/javascript">
fmembrolistsrch.Init();
fmembrolist.Init();
$(document).ready(function($) {	$("#ajuda").click(function() {	bootbox.dialog({title: "Informações de Ajuda", message: '<?php echo str_replace("\r\n"," ",trim($help)) ?>', buttons: { success: { label: "Fechar" }}}); });});
</script>
<?php } ?>
<?php
$membro_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($membro->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$membro_list->Page_Terminate();
?>
