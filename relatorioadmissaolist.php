<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "relatorioadmissaoinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$RelatorioAdmissao_list = NULL; // Initialize page object first

class cRelatorioAdmissao_list extends cRelatorioAdmissao {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{2B7992FC-5911-46A7-9310-01F4D4225C49}";

	// Table name
	var $TableName = 'RelatorioAdmissao';

	// Page object name
	var $PageObjName = 'RelatorioAdmissao_list';

	// Grid form hidden field names
	var $FormName = 'fRelatorioAdmissaolist';
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

		// Table object (RelatorioAdmissao)
		if (!isset($GLOBALS["RelatorioAdmissao"]) || get_class($GLOBALS["RelatorioAdmissao"]) == "cRelatorioAdmissao") {
			$GLOBALS["RelatorioAdmissao"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["RelatorioAdmissao"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "relatorioadmissaoadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "relatorioadmissaodelete.php";
		$this->MultiUpdateUrl = "relatorioadmissaoupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'RelatorioAdmissao', TRUE);

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
		global $EW_EXPORT, $RelatorioAdmissao;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($RelatorioAdmissao);
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
	var $DisplayRecs = 20;
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
			$this->DisplayRecs = 20; // Load default
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
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

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
					$this->DisplayRecs = 20; // Non-numeric, load default
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
		if (count($arrKeyFlds) >= 0) {
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->Nome, $Default, FALSE); // Nome
		$this->BuildSearchSql($sWhere, $this->Sexo, $Default, FALSE); // Sexo
		$this->BuildSearchSql($sWhere, $this->EstadoCivil, $Default, FALSE); // EstadoCivil
		$this->BuildSearchSql($sWhere, $this->Tipo_Admissao, $Default, FALSE); // Tipo_Admissao
		$this->BuildSearchSql($sWhere, $this->Admissao, $Default, FALSE); // Admissao

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->Nome->AdvancedSearch->Save(); // Nome
			$this->Sexo->AdvancedSearch->Save(); // Sexo
			$this->EstadoCivil->AdvancedSearch->Save(); // EstadoCivil
			$this->Tipo_Admissao->AdvancedSearch->Save(); // Tipo_Admissao
			$this->Admissao->AdvancedSearch->Save(); // Admissao
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
		if ($this->Nome->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Sexo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->EstadoCivil->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Tipo_Admissao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Admissao->AdvancedSearch->IssetSession())
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
		$this->Nome->AdvancedSearch->UnsetSession();
		$this->Sexo->AdvancedSearch->UnsetSession();
		$this->EstadoCivil->AdvancedSearch->UnsetSession();
		$this->Tipo_Admissao->AdvancedSearch->UnsetSession();
		$this->Admissao->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->Nome->AdvancedSearch->Load();
		$this->Sexo->AdvancedSearch->Load();
		$this->EstadoCivil->AdvancedSearch->Load();
		$this->Tipo_Admissao->AdvancedSearch->Load();
		$this->Admissao->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->Nome, $bCtrl); // Nome
			$this->UpdateSort($this->Sexo, $bCtrl); // Sexo
			$this->UpdateSort($this->EstadoCivil, $bCtrl); // EstadoCivil
			$this->UpdateSort($this->Tipo_Admissao, $bCtrl); // Tipo_Admissao
			$this->UpdateSort($this->Admissao, $bCtrl); // Admissao
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
				$this->Admissao->setSort("ASC");
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

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->Nome->setSort("");
				$this->Sexo->setSort("");
				$this->EstadoCivil->setSort("");
				$this->Tipo_Admissao->setSort("");
				$this->Admissao->setSort("");
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

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fRelatorioAdmissaolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-warning ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fRelatorioAdmissaolistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

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

		// Tipo_Admissao
		$this->Tipo_Admissao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Tipo_Admissao"]);
		if ($this->Tipo_Admissao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Tipo_Admissao->AdvancedSearch->SearchOperator = @$_GET["z_Tipo_Admissao"];

		// Admissao
		$this->Admissao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Admissao"]);
		if ($this->Admissao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Admissao->AdvancedSearch->SearchOperator = @$_GET["z_Admissao"];
		$this->Admissao->AdvancedSearch->SearchCondition = @$_GET["v_Admissao"];
		$this->Admissao->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_Admissao"]);
		if ($this->Admissao->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->Admissao->AdvancedSearch->SearchOperator2 = @$_GET["w_Admissao"];
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
		$this->Nome->setDbValue($rs->fields('Nome'));
		$this->Sexo->setDbValue($rs->fields('Sexo'));
		$this->EstadoCivil->setDbValue($rs->fields('EstadoCivil'));
		$this->Tipo_Admissao->setDbValue($rs->fields('Tipo_Admissao'));
		$this->Admissao->setDbValue($rs->fields('Admissao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Nome->DbValue = $row['Nome'];
		$this->Sexo->DbValue = $row['Sexo'];
		$this->EstadoCivil->DbValue = $row['EstadoCivil'];
		$this->Tipo_Admissao->DbValue = $row['Tipo_Admissao'];
		$this->Admissao->DbValue = $row['Admissao'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;

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
		// Nome
		// Sexo
		// EstadoCivil
		// Tipo_Admissao
		// Admissao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

			// Admissao
			$this->Admissao->ViewValue = $this->Admissao->CurrentValue;
			$this->Admissao->ViewValue = ew_FormatDateTime($this->Admissao->ViewValue, 7);
			$this->Admissao->ViewCustomAttributes = "";

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

			// Tipo_Admissao
			$this->Tipo_Admissao->LinkCustomAttributes = "";
			$this->Tipo_Admissao->HrefValue = "";
			$this->Tipo_Admissao->TooltipValue = "";

			// Admissao
			$this->Admissao->LinkCustomAttributes = "";
			$this->Admissao->HrefValue = "";
			$this->Admissao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

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

			// Tipo_Admissao
			$this->Tipo_Admissao->EditAttrs["class"] = "form-control";
			$this->Tipo_Admissao->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `Id`, `Tipo_Admissao` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipo_admissao`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Tipo_Admissao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Tipo_Admissao` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Tipo_Admissao->EditValue = $arwrk;

			// Admissao
			$this->Admissao->EditAttrs["class"] = "form-control";
			$this->Admissao->EditCustomAttributes = "";
			$this->Admissao->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->Admissao->AdvancedSearch->SearchValue, 7), 7));
			$this->Admissao->EditAttrs["class"] = "form-control";
			$this->Admissao->EditCustomAttributes = "";
			$this->Admissao->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->Admissao->AdvancedSearch->SearchValue2, 7), 7));
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
		if (!ew_CheckEuroDate($this->Admissao->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->Admissao->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->Admissao->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->Admissao->FldErrMsg());
		}

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
		$this->Nome->AdvancedSearch->Load();
		$this->Sexo->AdvancedSearch->Load();
		$this->EstadoCivil->AdvancedSearch->Load();
		$this->Tipo_Admissao->AdvancedSearch->Load();
		$this->Admissao->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_RelatorioAdmissao\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_RelatorioAdmissao',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fRelatorioAdmissaolist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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
if (!isset($RelatorioAdmissao_list)) $RelatorioAdmissao_list = new cRelatorioAdmissao_list();

// Page init
$RelatorioAdmissao_list->Page_Init();

// Page main
$RelatorioAdmissao_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$RelatorioAdmissao_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($RelatorioAdmissao->Export == "") { ?>
<script type="text/javascript">

// Page object
var RelatorioAdmissao_list = new ew_Page("RelatorioAdmissao_list");
RelatorioAdmissao_list.PageID = "list"; // Page ID
var EW_PAGE_ID = RelatorioAdmissao_list.PageID; // For backward compatibility

// Form object
var fRelatorioAdmissaolist = new ew_Form("fRelatorioAdmissaolist");
fRelatorioAdmissaolist.FormKeyCountName = '<?php echo $RelatorioAdmissao_list->FormKeyCountName ?>';

// Form_CustomValidate event
fRelatorioAdmissaolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fRelatorioAdmissaolist.ValidateRequired = true;
<?php } else { ?>
fRelatorioAdmissaolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fRelatorioAdmissaolist.Lists["x_Tipo_Admissao"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Tipo_Admissao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fRelatorioAdmissaolistsrch = new ew_Form("fRelatorioAdmissaolistsrch");

// Validate function for search
fRelatorioAdmissaolistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_Admissao");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($RelatorioAdmissao->Admissao->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fRelatorioAdmissaolistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fRelatorioAdmissaolistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fRelatorioAdmissaolistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fRelatorioAdmissaolistsrch.Lists["x_Tipo_Admissao"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Tipo_Admissao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Init search panel as collapsed
if (fRelatorioAdmissaolistsrch) fRelatorioAdmissaolistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($RelatorioAdmissao->Export == "") { ?>
<div class="ewToolbar">
<?php if ($RelatorioAdmissao->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($RelatorioAdmissao_list->TotalRecs > 0 && $RelatorioAdmissao_list->ExportOptions->Visible()) { ?>
<?php $RelatorioAdmissao_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($RelatorioAdmissao_list->SearchOptions->Visible()) { ?>
<?php $RelatorioAdmissao_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($RelatorioAdmissao->Export == "") { ?>
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
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($RelatorioAdmissao_list->TotalRecs <= 0)
			$RelatorioAdmissao_list->TotalRecs = $RelatorioAdmissao->SelectRecordCount();
	} else {
		if (!$RelatorioAdmissao_list->Recordset && ($RelatorioAdmissao_list->Recordset = $RelatorioAdmissao_list->LoadRecordset()))
			$RelatorioAdmissao_list->TotalRecs = $RelatorioAdmissao_list->Recordset->RecordCount();
	}
	$RelatorioAdmissao_list->StartRec = 1;
	if ($RelatorioAdmissao_list->DisplayRecs <= 0 || ($RelatorioAdmissao->Export <> "" && $RelatorioAdmissao->ExportAll)) // Display all records
		$RelatorioAdmissao_list->DisplayRecs = $RelatorioAdmissao_list->TotalRecs;
	if (!($RelatorioAdmissao->Export <> "" && $RelatorioAdmissao->ExportAll))
		$RelatorioAdmissao_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$RelatorioAdmissao_list->Recordset = $RelatorioAdmissao_list->LoadRecordset($RelatorioAdmissao_list->StartRec-1, $RelatorioAdmissao_list->DisplayRecs);

	// Set no record found message
	if ($RelatorioAdmissao->CurrentAction == "" && $RelatorioAdmissao_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$RelatorioAdmissao_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($RelatorioAdmissao_list->SearchWhere == "0=101")
			$RelatorioAdmissao_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$RelatorioAdmissao_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$RelatorioAdmissao_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($RelatorioAdmissao->Export == "" && $RelatorioAdmissao->CurrentAction == "") { ?>
<form name="fRelatorioAdmissaolistsrch" id="fRelatorioAdmissaolistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($RelatorioAdmissao_list->SearchWhere <> "") ? " in" : ""; ?>
<div id="fRelatorioAdmissaolistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="RelatorioAdmissao">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$RelatorioAdmissao_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$RelatorioAdmissao->RowType = EW_ROWTYPE_SEARCH;

// Render row
$RelatorioAdmissao->ResetAttrs();
$RelatorioAdmissao_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($RelatorioAdmissao->Nome->Visible) { // Nome ?>
	<div id="xsc_Nome" class="ewCell form-group">
		<label for="x_Nome" class="ewSearchCaption ewLabel"><?php echo $RelatorioAdmissao->Nome->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Nome" id="z_Nome" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_Nome" name="x_Nome" id="x_Nome" size="65" maxlength="60" value="<?php echo $RelatorioAdmissao->Nome->EditValue ?>"<?php echo $RelatorioAdmissao->Nome->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($RelatorioAdmissao->Tipo_Admissao->Visible) { // Tipo_Admissao ?>
	<div id="xsc_Tipo_Admissao" class="ewCell form-group">
		<label for="x_Tipo_Admissao" class="ewSearchCaption ewLabel"><?php echo $RelatorioAdmissao->Tipo_Admissao->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Tipo_Admissao" id="z_Tipo_Admissao" value="="></span>
		<span class="ewSearchField">
<select data-field="x_Tipo_Admissao" id="x_Tipo_Admissao" name="x_Tipo_Admissao"<?php echo $RelatorioAdmissao->Tipo_Admissao->EditAttributes() ?>>
<?php
if (is_array($RelatorioAdmissao->Tipo_Admissao->EditValue)) {
	$arwrk = $RelatorioAdmissao->Tipo_Admissao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($RelatorioAdmissao->Tipo_Admissao->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fRelatorioAdmissaolistsrch.Lists["x_Tipo_Admissao"].Options = <?php echo (is_array($RelatorioAdmissao->Tipo_Admissao->EditValue)) ? ew_ArrayToJson($RelatorioAdmissao->Tipo_Admissao->EditValue, 1) : "[]" ?>;
</script>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($RelatorioAdmissao->Admissao->Visible) { // Admissao ?>
	<div id="xsc_Admissao" class="ewCell form-group">
		<label for="x_Admissao" class="ewSearchCaption ewLabel"><?php echo $RelatorioAdmissao->Admissao->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_Admissao" id="z_Admissao" value="BETWEEN"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_Admissao" name="x_Admissao" id="x_Admissao" size="15" value="<?php echo $RelatorioAdmissao->Admissao->EditValue ?>"<?php echo $RelatorioAdmissao->Admissao->EditAttributes() ?>>
<?php if (!$RelatorioAdmissao->Admissao->ReadOnly && !$RelatorioAdmissao->Admissao->Disabled && @$RelatorioAdmissao->Admissao->EditAttrs["readonly"] == "" && @$RelatorioAdmissao->Admissao->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fRelatorioAdmissaolistsrch", "x_Admissao", "%d/%m/%Y");
</script>
<?php } ?>
</span>
		<span class="ewSearchCond btw1_Admissao">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="ewSearchField btw1_Admissao">
<input type="text" data-field="x_Admissao" name="y_Admissao" id="y_Admissao" size="15" value="<?php echo $RelatorioAdmissao->Admissao->EditValue2 ?>"<?php echo $RelatorioAdmissao->Admissao->EditAttributes() ?>>
<?php if (!$RelatorioAdmissao->Admissao->ReadOnly && !$RelatorioAdmissao->Admissao->Disabled && @$RelatorioAdmissao->Admissao->EditAttrs["readonly"] == "" && @$RelatorioAdmissao->Admissao->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fRelatorioAdmissaolistsrch", "y_Admissao", "%d/%m/%Y");
</script>
<?php } ?>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><i class='glyphicon glyphicon-search'></i>&nbsp;<?php echo $Language->Phrase("QuickSearchBtn") ?></button>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $RelatorioAdmissao_list->ShowPageHeader(); ?>
<?php
$RelatorioAdmissao_list->ShowMessage();
?>
<?php if ($RelatorioAdmissao_list->TotalRecs > 0 || $RelatorioAdmissao->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($RelatorioAdmissao->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($RelatorioAdmissao->CurrentAction <> "gridadd" && $RelatorioAdmissao->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($RelatorioAdmissao_list->Pager)) $RelatorioAdmissao_list->Pager = new cPrevNextPager($RelatorioAdmissao_list->StartRec, $RelatorioAdmissao_list->DisplayRecs, $RelatorioAdmissao_list->TotalRecs) ?>
<?php if ($RelatorioAdmissao_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($RelatorioAdmissao_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $RelatorioAdmissao_list->PageUrl() ?>start=<?php echo $RelatorioAdmissao_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($RelatorioAdmissao_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $RelatorioAdmissao_list->PageUrl() ?>start=<?php echo $RelatorioAdmissao_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $RelatorioAdmissao_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($RelatorioAdmissao_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $RelatorioAdmissao_list->PageUrl() ?>start=<?php echo $RelatorioAdmissao_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($RelatorioAdmissao_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $RelatorioAdmissao_list->PageUrl() ?>start=<?php echo $RelatorioAdmissao_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $RelatorioAdmissao_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $RelatorioAdmissao_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $RelatorioAdmissao_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $RelatorioAdmissao_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($RelatorioAdmissao_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="RelatorioAdmissao">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="20"<?php if ($RelatorioAdmissao_list->DisplayRecs == 20) { ?> selected="selected"<?php } ?>>20</option>
<option value="50"<?php if ($RelatorioAdmissao_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($RelatorioAdmissao_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($RelatorioAdmissao_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fRelatorioAdmissaolist" id="fRelatorioAdmissaolist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($RelatorioAdmissao_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $RelatorioAdmissao_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="RelatorioAdmissao">
<div id="gmp_RelatorioAdmissao" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($RelatorioAdmissao_list->TotalRecs > 0) { ?>
<table id="tbl_RelatorioAdmissaolist" class="table ewTable">
<?php echo $RelatorioAdmissao->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$RelatorioAdmissao_list->RenderListOptions();

// Render list options (header, left)
$RelatorioAdmissao_list->ListOptions->Render("header", "left");
?>
<?php if ($RelatorioAdmissao->Nome->Visible) { // Nome ?>
	<?php if ($RelatorioAdmissao->SortUrl($RelatorioAdmissao->Nome) == "") { ?>
		<th data-name="Nome"><div id="elh_RelatorioAdmissao_Nome" class="RelatorioAdmissao_Nome"><div class="ewTableHeaderCaption"><?php echo $RelatorioAdmissao->Nome->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Nome"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $RelatorioAdmissao->SortUrl($RelatorioAdmissao->Nome) ?>',2);"><div id="elh_RelatorioAdmissao_Nome" class="RelatorioAdmissao_Nome">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $RelatorioAdmissao->Nome->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($RelatorioAdmissao->Nome->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($RelatorioAdmissao->Nome->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($RelatorioAdmissao->Sexo->Visible) { // Sexo ?>
	<?php if ($RelatorioAdmissao->SortUrl($RelatorioAdmissao->Sexo) == "") { ?>
		<th data-name="Sexo"><div id="elh_RelatorioAdmissao_Sexo" class="RelatorioAdmissao_Sexo"><div class="ewTableHeaderCaption"><?php echo $RelatorioAdmissao->Sexo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Sexo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $RelatorioAdmissao->SortUrl($RelatorioAdmissao->Sexo) ?>',2);"><div id="elh_RelatorioAdmissao_Sexo" class="RelatorioAdmissao_Sexo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $RelatorioAdmissao->Sexo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($RelatorioAdmissao->Sexo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($RelatorioAdmissao->Sexo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($RelatorioAdmissao->EstadoCivil->Visible) { // EstadoCivil ?>
	<?php if ($RelatorioAdmissao->SortUrl($RelatorioAdmissao->EstadoCivil) == "") { ?>
		<th data-name="EstadoCivil"><div id="elh_RelatorioAdmissao_EstadoCivil" class="RelatorioAdmissao_EstadoCivil"><div class="ewTableHeaderCaption"><?php echo $RelatorioAdmissao->EstadoCivil->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="EstadoCivil"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $RelatorioAdmissao->SortUrl($RelatorioAdmissao->EstadoCivil) ?>',2);"><div id="elh_RelatorioAdmissao_EstadoCivil" class="RelatorioAdmissao_EstadoCivil">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $RelatorioAdmissao->EstadoCivil->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($RelatorioAdmissao->EstadoCivil->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($RelatorioAdmissao->EstadoCivil->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($RelatorioAdmissao->Tipo_Admissao->Visible) { // Tipo_Admissao ?>
	<?php if ($RelatorioAdmissao->SortUrl($RelatorioAdmissao->Tipo_Admissao) == "") { ?>
		<th data-name="Tipo_Admissao"><div id="elh_RelatorioAdmissao_Tipo_Admissao" class="RelatorioAdmissao_Tipo_Admissao"><div class="ewTableHeaderCaption"><?php echo $RelatorioAdmissao->Tipo_Admissao->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Tipo_Admissao"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $RelatorioAdmissao->SortUrl($RelatorioAdmissao->Tipo_Admissao) ?>',2);"><div id="elh_RelatorioAdmissao_Tipo_Admissao" class="RelatorioAdmissao_Tipo_Admissao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $RelatorioAdmissao->Tipo_Admissao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($RelatorioAdmissao->Tipo_Admissao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($RelatorioAdmissao->Tipo_Admissao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($RelatorioAdmissao->Admissao->Visible) { // Admissao ?>
	<?php if ($RelatorioAdmissao->SortUrl($RelatorioAdmissao->Admissao) == "") { ?>
		<th data-name="Admissao"><div id="elh_RelatorioAdmissao_Admissao" class="RelatorioAdmissao_Admissao"><div class="ewTableHeaderCaption"><?php echo $RelatorioAdmissao->Admissao->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Admissao"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $RelatorioAdmissao->SortUrl($RelatorioAdmissao->Admissao) ?>',2);"><div id="elh_RelatorioAdmissao_Admissao" class="RelatorioAdmissao_Admissao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $RelatorioAdmissao->Admissao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($RelatorioAdmissao->Admissao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($RelatorioAdmissao->Admissao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$RelatorioAdmissao_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($RelatorioAdmissao->ExportAll && $RelatorioAdmissao->Export <> "") {
	$RelatorioAdmissao_list->StopRec = $RelatorioAdmissao_list->TotalRecs;
} else {

	// Set the last record to display
	if ($RelatorioAdmissao_list->TotalRecs > $RelatorioAdmissao_list->StartRec + $RelatorioAdmissao_list->DisplayRecs - 1)
		$RelatorioAdmissao_list->StopRec = $RelatorioAdmissao_list->StartRec + $RelatorioAdmissao_list->DisplayRecs - 1;
	else
		$RelatorioAdmissao_list->StopRec = $RelatorioAdmissao_list->TotalRecs;
}
$RelatorioAdmissao_list->RecCnt = $RelatorioAdmissao_list->StartRec - 1;
if ($RelatorioAdmissao_list->Recordset && !$RelatorioAdmissao_list->Recordset->EOF) {
	$RelatorioAdmissao_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $RelatorioAdmissao_list->StartRec > 1)
		$RelatorioAdmissao_list->Recordset->Move($RelatorioAdmissao_list->StartRec - 1);
} elseif (!$RelatorioAdmissao->AllowAddDeleteRow && $RelatorioAdmissao_list->StopRec == 0) {
	$RelatorioAdmissao_list->StopRec = $RelatorioAdmissao->GridAddRowCount;
}

// Initialize aggregate
$RelatorioAdmissao->RowType = EW_ROWTYPE_AGGREGATEINIT;
$RelatorioAdmissao->ResetAttrs();
$RelatorioAdmissao_list->RenderRow();
while ($RelatorioAdmissao_list->RecCnt < $RelatorioAdmissao_list->StopRec) {
	$RelatorioAdmissao_list->RecCnt++;
	if (intval($RelatorioAdmissao_list->RecCnt) >= intval($RelatorioAdmissao_list->StartRec)) {
		$RelatorioAdmissao_list->RowCnt++;

		// Set up key count
		$RelatorioAdmissao_list->KeyCount = $RelatorioAdmissao_list->RowIndex;

		// Init row class and style
		$RelatorioAdmissao->ResetAttrs();
		$RelatorioAdmissao->CssClass = "";
		if ($RelatorioAdmissao->CurrentAction == "gridadd") {
		} else {
			$RelatorioAdmissao_list->LoadRowValues($RelatorioAdmissao_list->Recordset); // Load row values
		}
		$RelatorioAdmissao->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$RelatorioAdmissao->RowAttrs = array_merge($RelatorioAdmissao->RowAttrs, array('data-rowindex'=>$RelatorioAdmissao_list->RowCnt, 'id'=>'r' . $RelatorioAdmissao_list->RowCnt . '_RelatorioAdmissao', 'data-rowtype'=>$RelatorioAdmissao->RowType));

		// Render row
		$RelatorioAdmissao_list->RenderRow();

		// Render list options
		$RelatorioAdmissao_list->RenderListOptions();
?>
	<tr<?php echo $RelatorioAdmissao->RowAttributes() ?>>
<?php

// Render list options (body, left)
$RelatorioAdmissao_list->ListOptions->Render("body", "left", $RelatorioAdmissao_list->RowCnt);
?>
	<?php if ($RelatorioAdmissao->Nome->Visible) { // Nome ?>
		<td data-name="Nome"<?php echo $RelatorioAdmissao->Nome->CellAttributes() ?>>
<span<?php echo $RelatorioAdmissao->Nome->ViewAttributes() ?>>
<?php echo $RelatorioAdmissao->Nome->ListViewValue() ?></span>
<a id="<?php echo $RelatorioAdmissao_list->PageObjName . "_row_" . $RelatorioAdmissao_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($RelatorioAdmissao->Sexo->Visible) { // Sexo ?>
		<td data-name="Sexo"<?php echo $RelatorioAdmissao->Sexo->CellAttributes() ?>>
<span<?php echo $RelatorioAdmissao->Sexo->ViewAttributes() ?>>
<?php echo $RelatorioAdmissao->Sexo->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($RelatorioAdmissao->EstadoCivil->Visible) { // EstadoCivil ?>
		<td data-name="EstadoCivil"<?php echo $RelatorioAdmissao->EstadoCivil->CellAttributes() ?>>
<span<?php echo $RelatorioAdmissao->EstadoCivil->ViewAttributes() ?>>
<?php echo $RelatorioAdmissao->EstadoCivil->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($RelatorioAdmissao->Tipo_Admissao->Visible) { // Tipo_Admissao ?>
		<td data-name="Tipo_Admissao"<?php echo $RelatorioAdmissao->Tipo_Admissao->CellAttributes() ?>>
<span<?php echo $RelatorioAdmissao->Tipo_Admissao->ViewAttributes() ?>>
<?php echo $RelatorioAdmissao->Tipo_Admissao->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($RelatorioAdmissao->Admissao->Visible) { // Admissao ?>
		<td data-name="Admissao"<?php echo $RelatorioAdmissao->Admissao->CellAttributes() ?>>
<span<?php echo $RelatorioAdmissao->Admissao->ViewAttributes() ?>>
<?php echo $RelatorioAdmissao->Admissao->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$RelatorioAdmissao_list->ListOptions->Render("body", "right", $RelatorioAdmissao_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($RelatorioAdmissao->CurrentAction <> "gridadd")
		$RelatorioAdmissao_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($RelatorioAdmissao->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($RelatorioAdmissao_list->Recordset)
	$RelatorioAdmissao_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($RelatorioAdmissao_list->TotalRecs == 0 && $RelatorioAdmissao->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($RelatorioAdmissao_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($RelatorioAdmissao->Export == "") { ?>
<script type="text/javascript">
fRelatorioAdmissaolistsrch.Init();
fRelatorioAdmissaolist.Init();
$(document).ready(function($) {	$("#ajuda").click(function() {	bootbox.dialog({title: "Informações de Ajuda", message: '<?php echo str_replace("\r\n"," ",trim($help)) ?>', buttons: { success: { label: "Fechar" }}}); });});
</script>
<?php } ?>
<?php
$RelatorioAdmissao_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($RelatorioAdmissao->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$RelatorioAdmissao_list->Page_Terminate();
?>
