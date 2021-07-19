<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "dizimosporcriterioinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$dizimosporcriterio_list = NULL; // Initialize page object first

class cdizimosporcriterio_list extends cdizimosporcriterio {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{2B7992FC-5911-46A7-9310-01F4D4225C49}";

	// Table name
	var $TableName = 'dizimosporcriterio';

	// Page object name
	var $PageObjName = 'dizimosporcriterio_list';

	// Grid form hidden field names
	var $FormName = 'fdizimosporcriteriolist';
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

		// Table object (dizimosporcriterio)
		if (!isset($GLOBALS["dizimosporcriterio"]) || get_class($GLOBALS["dizimosporcriterio"]) == "cdizimosporcriterio") {
			$GLOBALS["dizimosporcriterio"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["dizimosporcriterio"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "dizimosporcriterioadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "dizimosporcriteriodelete.php";
		$this->MultiUpdateUrl = "dizimosporcriterioupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'dizimosporcriterio', TRUE);

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
		global $EW_EXPORT, $dizimosporcriterio;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($dizimosporcriterio);
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
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);
		if ($sFilter == "") {
			$sFilter = "0=101";
			$this->SearchWhere = $sFilter;
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
			$this->Id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->Id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->Id, $Default, FALSE); // Id
		$this->BuildSearchSql($sWhere, $this->id_discipulo, $Default, FALSE); // id_discipulo
		$this->BuildSearchSql($sWhere, $this->Mes, $Default, FALSE); // Mes
		$this->BuildSearchSql($sWhere, $this->Tipo, $Default, FALSE); // Tipo
		$this->BuildSearchSql($sWhere, $this->Conta_Caixa, $Default, FALSE); // Conta_Caixa
		$this->BuildSearchSql($sWhere, $this->Situacao, $Default, FALSE); // Situacao
		$this->BuildSearchSql($sWhere, $this->Descricao, $Default, FALSE); // Descricao
		$this->BuildSearchSql($sWhere, $this->Receitas, $Default, FALSE); // Receitas
		$this->BuildSearchSql($sWhere, $this->FormaPagto, $Default, FALSE); // FormaPagto
		$this->BuildSearchSql($sWhere, $this->Dt_Lancamento, $Default, FALSE); // Dt_Lancamento
		$this->BuildSearchSql($sWhere, $this->Vencimento, $Default, FALSE); // Vencimento
		$this->BuildSearchSql($sWhere, $this->Centro_de_Custo, $Default, FALSE); // Centro_de_Custo

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->Id->AdvancedSearch->Save(); // Id
			$this->id_discipulo->AdvancedSearch->Save(); // id_discipulo
			$this->Mes->AdvancedSearch->Save(); // Mes
			$this->Tipo->AdvancedSearch->Save(); // Tipo
			$this->Conta_Caixa->AdvancedSearch->Save(); // Conta_Caixa
			$this->Situacao->AdvancedSearch->Save(); // Situacao
			$this->Descricao->AdvancedSearch->Save(); // Descricao
			$this->Receitas->AdvancedSearch->Save(); // Receitas
			$this->FormaPagto->AdvancedSearch->Save(); // FormaPagto
			$this->Dt_Lancamento->AdvancedSearch->Save(); // Dt_Lancamento
			$this->Vencimento->AdvancedSearch->Save(); // Vencimento
			$this->Centro_de_Custo->AdvancedSearch->Save(); // Centro_de_Custo
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
		if ($this->Id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_discipulo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Mes->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Tipo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Conta_Caixa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Situacao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Descricao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Receitas->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->FormaPagto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Dt_Lancamento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Vencimento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Centro_de_Custo->AdvancedSearch->IssetSession())
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
		$this->Id->AdvancedSearch->UnsetSession();
		$this->id_discipulo->AdvancedSearch->UnsetSession();
		$this->Mes->AdvancedSearch->UnsetSession();
		$this->Tipo->AdvancedSearch->UnsetSession();
		$this->Conta_Caixa->AdvancedSearch->UnsetSession();
		$this->Situacao->AdvancedSearch->UnsetSession();
		$this->Descricao->AdvancedSearch->UnsetSession();
		$this->Receitas->AdvancedSearch->UnsetSession();
		$this->FormaPagto->AdvancedSearch->UnsetSession();
		$this->Dt_Lancamento->AdvancedSearch->UnsetSession();
		$this->Vencimento->AdvancedSearch->UnsetSession();
		$this->Centro_de_Custo->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->Id->AdvancedSearch->Load();
		$this->id_discipulo->AdvancedSearch->Load();
		$this->Mes->AdvancedSearch->Load();
		$this->Tipo->AdvancedSearch->Load();
		$this->Conta_Caixa->AdvancedSearch->Load();
		$this->Situacao->AdvancedSearch->Load();
		$this->Descricao->AdvancedSearch->Load();
		$this->Receitas->AdvancedSearch->Load();
		$this->FormaPagto->AdvancedSearch->Load();
		$this->Dt_Lancamento->AdvancedSearch->Load();
		$this->Vencimento->AdvancedSearch->Load();
		$this->Centro_de_Custo->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->Mes, $bCtrl); // Mes
			$this->UpdateSort($this->Tipo, $bCtrl); // Tipo
			$this->UpdateSort($this->Conta_Caixa, $bCtrl); // Conta_Caixa
			$this->UpdateSort($this->Situacao, $bCtrl); // Situacao
			$this->UpdateSort($this->Descricao, $bCtrl); // Descricao
			$this->UpdateSort($this->Receitas, $bCtrl); // Receitas
			$this->UpdateSort($this->FormaPagto, $bCtrl); // FormaPagto
			$this->UpdateSort($this->Dt_Lancamento, $bCtrl); // Dt_Lancamento
			$this->UpdateSort($this->Vencimento, $bCtrl); // Vencimento
			$this->UpdateSort($this->Centro_de_Custo, $bCtrl); // Centro_de_Custo
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

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->Mes->setSort("");
				$this->Tipo->setSort("");
				$this->Conta_Caixa->setSort("");
				$this->Situacao->setSort("");
				$this->Descricao->setSort("");
				$this->Receitas->setSort("");
				$this->FormaPagto->setSort("");
				$this->Dt_Lancamento->setSort("");
				$this->Vencimento->setSort("");
				$this->Centro_de_Custo->setSort("");
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

		// "sequence"
		$item = &$this->ListOptions->Add("sequence");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = TRUE; // Always on left
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

		// "sequence"
		$oListOpt = &$this->ListOptions->Items["sequence"];
		$oListOpt->Body = ew_FormatSeqNo($this->RecCnt);

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->Id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fdizimosporcriteriolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-warning ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fdizimosporcriteriolistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ResetSearch") . "\" data-caption=\"" . $Language->Phrase("ResetSearch") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ResetSearchBtn") . "</a>";
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
		// Id

		$this->Id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Id"]);
		if ($this->Id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Id->AdvancedSearch->SearchOperator = @$_GET["z_Id"];

		// id_discipulo
		$this->id_discipulo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_discipulo"]);
		if ($this->id_discipulo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_discipulo->AdvancedSearch->SearchOperator = @$_GET["z_id_discipulo"];

		// Mes
		$this->Mes->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Mes"]);
		if ($this->Mes->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Mes->AdvancedSearch->SearchOperator = @$_GET["z_Mes"];

		// Tipo
		$this->Tipo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Tipo"]);
		if ($this->Tipo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Tipo->AdvancedSearch->SearchOperator = @$_GET["z_Tipo"];

		// Conta_Caixa
		$this->Conta_Caixa->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Conta_Caixa"]);
		if ($this->Conta_Caixa->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Conta_Caixa->AdvancedSearch->SearchOperator = @$_GET["z_Conta_Caixa"];

		// Situacao
		$this->Situacao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Situacao"]);
		if ($this->Situacao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Situacao->AdvancedSearch->SearchOperator = @$_GET["z_Situacao"];

		// Descricao
		$this->Descricao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Descricao"]);
		if ($this->Descricao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Descricao->AdvancedSearch->SearchOperator = @$_GET["z_Descricao"];

		// Receitas
		$this->Receitas->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Receitas"]);
		if ($this->Receitas->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Receitas->AdvancedSearch->SearchOperator = @$_GET["z_Receitas"];

		// FormaPagto
		$this->FormaPagto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_FormaPagto"]);
		if ($this->FormaPagto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->FormaPagto->AdvancedSearch->SearchOperator = @$_GET["z_FormaPagto"];

		// Dt_Lancamento
		$this->Dt_Lancamento->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Dt_Lancamento"]);
		if ($this->Dt_Lancamento->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Dt_Lancamento->AdvancedSearch->SearchOperator = @$_GET["z_Dt_Lancamento"];
		$this->Dt_Lancamento->AdvancedSearch->SearchCondition = @$_GET["v_Dt_Lancamento"];
		$this->Dt_Lancamento->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_Dt_Lancamento"]);
		if ($this->Dt_Lancamento->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->Dt_Lancamento->AdvancedSearch->SearchOperator2 = @$_GET["w_Dt_Lancamento"];

		// Vencimento
		$this->Vencimento->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Vencimento"]);
		if ($this->Vencimento->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Vencimento->AdvancedSearch->SearchOperator = @$_GET["z_Vencimento"];
		$this->Vencimento->AdvancedSearch->SearchCondition = @$_GET["v_Vencimento"];
		$this->Vencimento->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_Vencimento"]);
		if ($this->Vencimento->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->Vencimento->AdvancedSearch->SearchOperator2 = @$_GET["w_Vencimento"];

		// Centro_de_Custo
		$this->Centro_de_Custo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Centro_de_Custo"]);
		if ($this->Centro_de_Custo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Centro_de_Custo->AdvancedSearch->SearchOperator = @$_GET["z_Centro_de_Custo"];
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
		$this->Id->setDbValue($rs->fields('Id'));
		$this->id_discipulo->setDbValue($rs->fields('id_discipulo'));
		$this->Mes->setDbValue($rs->fields('Mes'));
		$this->Tipo->setDbValue($rs->fields('Tipo'));
		$this->Conta_Caixa->setDbValue($rs->fields('Conta_Caixa'));
		$this->Situacao->setDbValue($rs->fields('Situacao'));
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
		$this->Mes->DbValue = $row['Mes'];
		$this->Tipo->DbValue = $row['Tipo'];
		$this->Conta_Caixa->DbValue = $row['Conta_Caixa'];
		$this->Situacao->DbValue = $row['Situacao'];
		$this->Descricao->DbValue = $row['Descricao'];
		$this->Receitas->DbValue = $row['Receitas'];
		$this->FormaPagto->DbValue = $row['FormaPagto'];
		$this->Dt_Lancamento->DbValue = $row['Dt_Lancamento'];
		$this->Vencimento->DbValue = $row['Vencimento'];
		$this->Centro_de_Custo->DbValue = $row['Centro_de_Custo'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("Id")) <> "")
			$this->Id->CurrentValue = $this->getKey("Id"); // Id
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

		// Convert decimal values if posted back
		if ($this->Receitas->FormValue == $this->Receitas->CurrentValue && is_numeric(ew_StrToFloat($this->Receitas->CurrentValue)))
			$this->Receitas->CurrentValue = ew_StrToFloat($this->Receitas->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// Id

		$this->Id->CellCssStyle = "white-space: nowrap;";

		// id_discipulo
		$this->id_discipulo->CellCssStyle = "white-space: nowrap;";

		// Mes
		// Tipo
		// Conta_Caixa
		// Situacao
		// Descricao
		// Receitas
		// FormaPagto
		// Dt_Lancamento
		// Vencimento
		// Centro_de_Custo
		// Accumulate aggregate value

		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT && $this->RowType <> EW_ROWTYPE_AGGREGATE) {
			if (is_numeric($this->Receitas->CurrentValue))
				$this->Receitas->Total += $this->Receitas->CurrentValue; // Accumulate total
		}
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// Mes
			if (strval($this->Mes->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->Mes->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id`, `Mes_abrev` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `meses`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Mes, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->Mes->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->Mes->ViewValue = $this->Mes->CurrentValue;
				}
			} else {
				$this->Mes->ViewValue = NULL;
			}
			$this->Mes->ViewCustomAttributes = "";

			// Tipo
			if (strval($this->Tipo->CurrentValue) <> "") {
				switch ($this->Tipo->CurrentValue) {
					case $this->Tipo->FldTagValue(1):
						$this->Tipo->ViewValue = $this->Tipo->FldTagCaption(1) <> "" ? $this->Tipo->FldTagCaption(1) : $this->Tipo->CurrentValue;
						break;
					default:
						$this->Tipo->ViewValue = $this->Tipo->CurrentValue;
				}
			} else {
				$this->Tipo->ViewValue = NULL;
			}
			$this->Tipo->ViewCustomAttributes = "";

			// Conta_Caixa
			if (strval($this->Conta_Caixa->CurrentValue) <> "") {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->Conta_Caixa->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `Id`, `Conta_Caixa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `fin_conta_caixa`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Conta_Caixa, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->Conta_Caixa->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->Conta_Caixa->ViewValue = $this->Conta_Caixa->CurrentValue;
				}
			} else {
				$this->Conta_Caixa->ViewValue = NULL;
			}
			$this->Conta_Caixa->ViewCustomAttributes = "";

			// Situacao
			if (strval($this->Situacao->CurrentValue) <> "") {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->Situacao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `Id`, `Situacao` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `fin_situacao`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Situacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
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
			$this->Dt_Lancamento->ViewCustomAttributes = "";

			// Vencimento
			$this->Vencimento->ViewValue = $this->Vencimento->CurrentValue;
			$this->Vencimento->ViewValue = ew_FormatDateTime($this->Vencimento->ViewValue, 7);
			$this->Vencimento->ViewCustomAttributes = "";

			// Centro_de_Custo
			if (strval($this->Centro_de_Custo->CurrentValue) <> "") {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->Centro_de_Custo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `Id`, `Conta` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `fin_centro_de_custo`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Centro_de_Custo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->Centro_de_Custo->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->Centro_de_Custo->ViewValue = $this->Centro_de_Custo->CurrentValue;
				}
			} else {
				$this->Centro_de_Custo->ViewValue = NULL;
			}
			$this->Centro_de_Custo->ViewCustomAttributes = "";

			// Mes
			$this->Mes->LinkCustomAttributes = "";
			$this->Mes->HrefValue = "";
			$this->Mes->TooltipValue = "";

			// Tipo
			$this->Tipo->LinkCustomAttributes = "";
			$this->Tipo->HrefValue = "";
			$this->Tipo->TooltipValue = "";

			// Conta_Caixa
			$this->Conta_Caixa->LinkCustomAttributes = "";
			$this->Conta_Caixa->HrefValue = "";
			$this->Conta_Caixa->TooltipValue = "";

			// Situacao
			$this->Situacao->LinkCustomAttributes = "";
			$this->Situacao->HrefValue = "";
			$this->Situacao->TooltipValue = "";

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

			// Centro_de_Custo
			$this->Centro_de_Custo->LinkCustomAttributes = "";
			$this->Centro_de_Custo->HrefValue = "";
			$this->Centro_de_Custo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// Mes
			$this->Mes->EditAttrs["class"] = "form-control";
			$this->Mes->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `id`, `Mes_abrev` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `meses`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Mes, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Mes->EditValue = $arwrk;

			// Tipo
			$this->Tipo->EditAttrs["class"] = "form-control";
			$this->Tipo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Tipo->FldTagValue(1), $this->Tipo->FldTagCaption(1) <> "" ? $this->Tipo->FldTagCaption(1) : $this->Tipo->FldTagValue(1));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->Tipo->EditValue = $arwrk;

			// Conta_Caixa
			$this->Conta_Caixa->EditAttrs["class"] = "form-control";
			$this->Conta_Caixa->EditCustomAttributes = "";

			// Situacao
			$this->Situacao->EditAttrs["class"] = "form-control";
			$this->Situacao->EditCustomAttributes = "";

			// Descricao
			$this->Descricao->EditAttrs["class"] = "form-control";
			$this->Descricao->EditCustomAttributes = "";
			$this->Descricao->EditValue = ew_HtmlEncode($this->Descricao->AdvancedSearch->SearchValue);

			// Receitas
			$this->Receitas->EditAttrs["class"] = "form-control";
			$this->Receitas->EditCustomAttributes = "";
			$this->Receitas->EditValue = ew_HtmlEncode($this->Receitas->AdvancedSearch->SearchValue);

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
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->FormaPagto->EditValue = $arwrk;

			// Dt_Lancamento
			$this->Dt_Lancamento->EditAttrs["class"] = "form-control";
			$this->Dt_Lancamento->EditCustomAttributes = "";
			$this->Dt_Lancamento->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->Dt_Lancamento->AdvancedSearch->SearchValue, 7), 7));
			$this->Dt_Lancamento->EditAttrs["class"] = "form-control";
			$this->Dt_Lancamento->EditCustomAttributes = "";
			$this->Dt_Lancamento->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->Dt_Lancamento->AdvancedSearch->SearchValue2, 7), 7));

			// Vencimento
			$this->Vencimento->EditAttrs["class"] = "form-control";
			$this->Vencimento->EditCustomAttributes = "";
			$this->Vencimento->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->Vencimento->AdvancedSearch->SearchValue, 7), 7));
			$this->Vencimento->EditAttrs["class"] = "form-control";
			$this->Vencimento->EditCustomAttributes = "";
			$this->Vencimento->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->Vencimento->AdvancedSearch->SearchValue2, 7), 7));

			// Centro_de_Custo
			$this->Centro_de_Custo->EditAttrs["class"] = "form-control";
			$this->Centro_de_Custo->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `Id`, `Conta` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `fin_centro_de_custo`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Centro_de_Custo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Centro_de_Custo->EditValue = $arwrk;
		} elseif ($this->RowType == EW_ROWTYPE_AGGREGATEINIT) { // Initialize aggregate row
			$this->Receitas->Total = 0; // Initialize total
		} elseif ($this->RowType == EW_ROWTYPE_AGGREGATE) { // Aggregate row
			$this->Receitas->CurrentValue = $this->Receitas->Total;
			$this->Receitas->ViewValue = $this->Receitas->CurrentValue;
			$this->Receitas->ViewValue = ew_FormatNumber($this->Receitas->ViewValue, 2, -2, -2, -2);
			$this->Receitas->CellCssStyle .= "text-align: right;";
			$this->Receitas->ViewCustomAttributes = "";
			$this->Receitas->HrefValue = ""; // Clear href value
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
		if (!ew_CheckNumber($this->Receitas->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->Receitas->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->Dt_Lancamento->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->Dt_Lancamento->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->Dt_Lancamento->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->Dt_Lancamento->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->Vencimento->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->Vencimento->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->Vencimento->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->Vencimento->FldErrMsg());
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
		$this->Id->AdvancedSearch->Load();
		$this->id_discipulo->AdvancedSearch->Load();
		$this->Mes->AdvancedSearch->Load();
		$this->Tipo->AdvancedSearch->Load();
		$this->Conta_Caixa->AdvancedSearch->Load();
		$this->Situacao->AdvancedSearch->Load();
		$this->Descricao->AdvancedSearch->Load();
		$this->Receitas->AdvancedSearch->Load();
		$this->FormaPagto->AdvancedSearch->Load();
		$this->Dt_Lancamento->AdvancedSearch->Load();
		$this->Vencimento->AdvancedSearch->Load();
		$this->Centro_de_Custo->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_dizimosporcriterio\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_dizimosporcriterio',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fdizimosporcriteriolist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($dizimosporcriterio_list)) $dizimosporcriterio_list = new cdizimosporcriterio_list();

// Page init
$dizimosporcriterio_list->Page_Init();

// Page main
$dizimosporcriterio_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$dizimosporcriterio_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($dizimosporcriterio->Export == "") { ?>
<script type="text/javascript">

// Page object
var dizimosporcriterio_list = new ew_Page("dizimosporcriterio_list");
dizimosporcriterio_list.PageID = "list"; // Page ID
var EW_PAGE_ID = dizimosporcriterio_list.PageID; // For backward compatibility

// Form object
var fdizimosporcriteriolist = new ew_Form("fdizimosporcriteriolist");
fdizimosporcriteriolist.FormKeyCountName = '<?php echo $dizimosporcriterio_list->FormKeyCountName ?>';

// Form_CustomValidate event
fdizimosporcriteriolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdizimosporcriteriolist.ValidateRequired = true;
<?php } else { ?>
fdizimosporcriteriolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdizimosporcriteriolist.Lists["x_Mes"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Mes_abrev","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fdizimosporcriteriolist.Lists["x_Conta_Caixa"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Conta_Caixa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fdizimosporcriteriolist.Lists["x_Situacao"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Situacao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fdizimosporcriteriolist.Lists["x_FormaPagto"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Forma_Pagto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fdizimosporcriteriolist.Lists["x_Centro_de_Custo"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Conta","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fdizimosporcriteriolistsrch = new ew_Form("fdizimosporcriteriolistsrch");

// Validate function for search
fdizimosporcriteriolistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_Receitas");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($dizimosporcriterio->Receitas->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_Dt_Lancamento");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($dizimosporcriterio->Dt_Lancamento->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_Vencimento");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($dizimosporcriterio->Vencimento->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fdizimosporcriteriolistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdizimosporcriteriolistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fdizimosporcriteriolistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fdizimosporcriteriolistsrch.Lists["x_Mes"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Mes_abrev","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fdizimosporcriteriolistsrch.Lists["x_FormaPagto"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Forma_Pagto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fdizimosporcriteriolistsrch.Lists["x_Centro_de_Custo"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Conta","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($dizimosporcriterio->Export == "") { ?>
<div class="ewToolbar">
<?php if ($dizimosporcriterio->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($dizimosporcriterio_list->TotalRecs > 0 && $dizimosporcriterio_list->ExportOptions->Visible()) { ?>
<?php $dizimosporcriterio_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($dizimosporcriterio_list->SearchOptions->Visible()) { ?>
<?php $dizimosporcriterio_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($dizimosporcriterio->Export == "") { ?>
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
		if ($dizimosporcriterio_list->TotalRecs <= 0)
			$dizimosporcriterio_list->TotalRecs = $dizimosporcriterio->SelectRecordCount();
	} else {
		if (!$dizimosporcriterio_list->Recordset && ($dizimosporcriterio_list->Recordset = $dizimosporcriterio_list->LoadRecordset()))
			$dizimosporcriterio_list->TotalRecs = $dizimosporcriterio_list->Recordset->RecordCount();
	}
	$dizimosporcriterio_list->StartRec = 1;
	if ($dizimosporcriterio_list->DisplayRecs <= 0 || ($dizimosporcriterio->Export <> "" && $dizimosporcriterio->ExportAll)) // Display all records
		$dizimosporcriterio_list->DisplayRecs = $dizimosporcriterio_list->TotalRecs;
	if (!($dizimosporcriterio->Export <> "" && $dizimosporcriterio->ExportAll))
		$dizimosporcriterio_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$dizimosporcriterio_list->Recordset = $dizimosporcriterio_list->LoadRecordset($dizimosporcriterio_list->StartRec-1, $dizimosporcriterio_list->DisplayRecs);

	// Set no record found message
	if ($dizimosporcriterio->CurrentAction == "" && $dizimosporcriterio_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$dizimosporcriterio_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($dizimosporcriterio_list->SearchWhere == "0=101")
			$dizimosporcriterio_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$dizimosporcriterio_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$dizimosporcriterio_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($dizimosporcriterio->Export == "" && $dizimosporcriterio->CurrentAction == "") { ?>
<form name="fdizimosporcriteriolistsrch" id="fdizimosporcriteriolistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($dizimosporcriterio_list->SearchWhere <> "") ? " in" : ""; ?>
<div id="fdizimosporcriteriolistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="dizimosporcriterio">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$dizimosporcriterio_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$dizimosporcriterio->RowType = EW_ROWTYPE_SEARCH;

// Render row
$dizimosporcriterio->ResetAttrs();
$dizimosporcriterio_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($dizimosporcriterio->Mes->Visible) { // Mes ?>
	<div id="xsc_Mes" class="ewCell form-group">
		<label for="x_Mes" class="ewSearchCaption ewLabel"><?php echo $dizimosporcriterio->Mes->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Mes" id="z_Mes" value="="></span>
		<span class="ewSearchField">
<select data-field="x_Mes" id="x_Mes" name="x_Mes"<?php echo $dizimosporcriterio->Mes->EditAttributes() ?>>
<?php
if (is_array($dizimosporcriterio->Mes->EditValue)) {
	$arwrk = $dizimosporcriterio->Mes->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($dizimosporcriterio->Mes->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fdizimosporcriteriolistsrch.Lists["x_Mes"].Options = <?php echo (is_array($dizimosporcriterio->Mes->EditValue)) ? ew_ArrayToJson($dizimosporcriterio->Mes->EditValue, 1) : "[]" ?>;
</script>
</span>
	</div>
<?php } ?>
<?php if ($dizimosporcriterio->Descricao->Visible) { // Descricao ?>
	<div id="xsc_Descricao" class="ewCell form-group">
		<label for="x_Descricao" class="ewSearchCaption ewLabel"><?php echo $dizimosporcriterio->Descricao->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Descricao" id="z_Descricao" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_Descricao" name="x_Descricao" id="x_Descricao" size="30" maxlength="60" value="<?php echo $dizimosporcriterio->Descricao->EditValue ?>"<?php echo $dizimosporcriterio->Descricao->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($dizimosporcriterio->Receitas->Visible) { // Receitas ?>
	<div id="xsc_Receitas" class="ewCell form-group">
		<label for="x_Receitas" class="ewSearchCaption ewLabel"><?php echo $dizimosporcriterio->Receitas->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Receitas" id="z_Receitas" value="="></span>
		<span class="ewSearchField">
<input type="text" data-field="x_Receitas" name="x_Receitas" id="x_Receitas" size="30" value="<?php echo $dizimosporcriterio->Receitas->EditValue ?>"<?php echo $dizimosporcriterio->Receitas->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
<?php if ($dizimosporcriterio->FormaPagto->Visible) { // FormaPagto ?>
	<div id="xsc_FormaPagto" class="ewCell form-group">
		<label for="x_FormaPagto" class="ewSearchCaption ewLabel"><?php echo $dizimosporcriterio->FormaPagto->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_FormaPagto" id="z_FormaPagto" value="="></span>
		<span class="ewSearchField">
<select data-field="x_FormaPagto" id="x_FormaPagto" name="x_FormaPagto"<?php echo $dizimosporcriterio->FormaPagto->EditAttributes() ?>>
<?php
if (is_array($dizimosporcriterio->FormaPagto->EditValue)) {
	$arwrk = $dizimosporcriterio->FormaPagto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($dizimosporcriterio->FormaPagto->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fdizimosporcriteriolistsrch.Lists["x_FormaPagto"].Options = <?php echo (is_array($dizimosporcriterio->FormaPagto->EditValue)) ? ew_ArrayToJson($dizimosporcriterio->FormaPagto->EditValue, 1) : "[]" ?>;
</script>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($dizimosporcriterio->Dt_Lancamento->Visible) { // Dt_Lancamento ?>
	<div id="xsc_Dt_Lancamento" class="ewCell form-group">
		<label for="x_Dt_Lancamento" class="ewSearchCaption ewLabel"><?php echo $dizimosporcriterio->Dt_Lancamento->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_Dt_Lancamento" id="z_Dt_Lancamento" value="BETWEEN"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_Dt_Lancamento" name="x_Dt_Lancamento" id="x_Dt_Lancamento" size="10" value="<?php echo $dizimosporcriterio->Dt_Lancamento->EditValue ?>"<?php echo $dizimosporcriterio->Dt_Lancamento->EditAttributes() ?>>
<?php if (!$dizimosporcriterio->Dt_Lancamento->ReadOnly && !$dizimosporcriterio->Dt_Lancamento->Disabled && @$dizimosporcriterio->Dt_Lancamento->EditAttrs["readonly"] == "" && @$dizimosporcriterio->Dt_Lancamento->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fdizimosporcriteriolistsrch", "x_Dt_Lancamento", "%d/%m/%Y");
</script>
<?php } ?>
</span>
		<span class="ewSearchCond btw1_Dt_Lancamento">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="ewSearchField btw1_Dt_Lancamento">
<input type="text" data-field="x_Dt_Lancamento" name="y_Dt_Lancamento" id="y_Dt_Lancamento" size="10" value="<?php echo $dizimosporcriterio->Dt_Lancamento->EditValue2 ?>"<?php echo $dizimosporcriterio->Dt_Lancamento->EditAttributes() ?>>
<?php if (!$dizimosporcriterio->Dt_Lancamento->ReadOnly && !$dizimosporcriterio->Dt_Lancamento->Disabled && @$dizimosporcriterio->Dt_Lancamento->EditAttrs["readonly"] == "" && @$dizimosporcriterio->Dt_Lancamento->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fdizimosporcriteriolistsrch", "y_Dt_Lancamento", "%d/%m/%Y");
</script>
<?php } ?>
</span>
	</div>
<?php } ?>
<?php if ($dizimosporcriterio->Vencimento->Visible) { // Vencimento ?>
	<div id="xsc_Vencimento" class="ewCell form-group">
		<label for="x_Vencimento" class="ewSearchCaption ewLabel"><?php echo $dizimosporcriterio->Vencimento->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_Vencimento" id="z_Vencimento" value="BETWEEN"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_Vencimento" name="x_Vencimento" id="x_Vencimento" size="10" value="<?php echo $dizimosporcriterio->Vencimento->EditValue ?>"<?php echo $dizimosporcriterio->Vencimento->EditAttributes() ?>>
<?php if (!$dizimosporcriterio->Vencimento->ReadOnly && !$dizimosporcriterio->Vencimento->Disabled && @$dizimosporcriterio->Vencimento->EditAttrs["readonly"] == "" && @$dizimosporcriterio->Vencimento->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fdizimosporcriteriolistsrch", "x_Vencimento", "%d/%m/%Y");
</script>
<?php } ?>
</span>
		<span class="ewSearchCond btw1_Vencimento">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="ewSearchField btw1_Vencimento">
<input type="text" data-field="x_Vencimento" name="y_Vencimento" id="y_Vencimento" size="10" value="<?php echo $dizimosporcriterio->Vencimento->EditValue2 ?>"<?php echo $dizimosporcriterio->Vencimento->EditAttributes() ?>>
<?php if (!$dizimosporcriterio->Vencimento->ReadOnly && !$dizimosporcriterio->Vencimento->Disabled && @$dizimosporcriterio->Vencimento->EditAttrs["readonly"] == "" && @$dizimosporcriterio->Vencimento->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fdizimosporcriteriolistsrch", "y_Vencimento", "%d/%m/%Y");
</script>
<?php } ?>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($dizimosporcriterio->Centro_de_Custo->Visible) { // Centro_de_Custo ?>
	<div id="xsc_Centro_de_Custo" class="ewCell form-group">
		<label for="x_Centro_de_Custo" class="ewSearchCaption ewLabel"><?php echo $dizimosporcriterio->Centro_de_Custo->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Centro_de_Custo" id="z_Centro_de_Custo" value="="></span>
		<span class="ewSearchField">
<select data-field="x_Centro_de_Custo" id="x_Centro_de_Custo" name="x_Centro_de_Custo"<?php echo $dizimosporcriterio->Centro_de_Custo->EditAttributes() ?>>
<?php
if (is_array($dizimosporcriterio->Centro_de_Custo->EditValue)) {
	$arwrk = $dizimosporcriterio->Centro_de_Custo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($dizimosporcriterio->Centro_de_Custo->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fdizimosporcriteriolistsrch.Lists["x_Centro_de_Custo"].Options = <?php echo (is_array($dizimosporcriterio->Centro_de_Custo->EditValue)) ? ew_ArrayToJson($dizimosporcriterio->Centro_de_Custo->EditValue, 1) : "[]" ?>;
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
<?php $dizimosporcriterio_list->ShowPageHeader(); ?>
<?php
$dizimosporcriterio_list->ShowMessage();
?>
<?php if ($dizimosporcriterio_list->TotalRecs > 0 || $dizimosporcriterio->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($dizimosporcriterio->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($dizimosporcriterio->CurrentAction <> "gridadd" && $dizimosporcriterio->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($dizimosporcriterio_list->Pager)) $dizimosporcriterio_list->Pager = new cPrevNextPager($dizimosporcriterio_list->StartRec, $dizimosporcriterio_list->DisplayRecs, $dizimosporcriterio_list->TotalRecs) ?>
<?php if ($dizimosporcriterio_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($dizimosporcriterio_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $dizimosporcriterio_list->PageUrl() ?>start=<?php echo $dizimosporcriterio_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($dizimosporcriterio_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $dizimosporcriterio_list->PageUrl() ?>start=<?php echo $dizimosporcriterio_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $dizimosporcriterio_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($dizimosporcriterio_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $dizimosporcriterio_list->PageUrl() ?>start=<?php echo $dizimosporcriterio_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($dizimosporcriterio_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $dizimosporcriterio_list->PageUrl() ?>start=<?php echo $dizimosporcriterio_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $dizimosporcriterio_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $dizimosporcriterio_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $dizimosporcriterio_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $dizimosporcriterio_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($dizimosporcriterio_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="dizimosporcriterio">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="10"<?php if ($dizimosporcriterio_list->DisplayRecs == 10) { ?> selected="selected"<?php } ?>>10</option>
<option value="20"<?php if ($dizimosporcriterio_list->DisplayRecs == 20) { ?> selected="selected"<?php } ?>>20</option>
<option value="50"<?php if ($dizimosporcriterio_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($dizimosporcriterio_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
<option value="500"<?php if ($dizimosporcriterio_list->DisplayRecs == 500) { ?> selected="selected"<?php } ?>>500</option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($dizimosporcriterio_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fdizimosporcriteriolist" id="fdizimosporcriteriolist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($dizimosporcriterio_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $dizimosporcriterio_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="dizimosporcriterio">
<div id="gmp_dizimosporcriterio" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($dizimosporcriterio_list->TotalRecs > 0) { ?>
<table id="tbl_dizimosporcriteriolist" class="table ewTable">
<?php echo $dizimosporcriterio->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$dizimosporcriterio_list->RenderListOptions();

// Render list options (header, left)
$dizimosporcriterio_list->ListOptions->Render("header", "left");
?>
<?php if ($dizimosporcriterio->Mes->Visible) { // Mes ?>
	<?php if ($dizimosporcriterio->SortUrl($dizimosporcriterio->Mes) == "") { ?>
		<th data-name="Mes"><div id="elh_dizimosporcriterio_Mes" class="dizimosporcriterio_Mes"><div class="ewTableHeaderCaption"><?php echo $dizimosporcriterio->Mes->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Mes"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dizimosporcriterio->SortUrl($dizimosporcriterio->Mes) ?>',2);"><div id="elh_dizimosporcriterio_Mes" class="dizimosporcriterio_Mes">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dizimosporcriterio->Mes->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dizimosporcriterio->Mes->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dizimosporcriterio->Mes->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($dizimosporcriterio->Tipo->Visible) { // Tipo ?>
	<?php if ($dizimosporcriterio->SortUrl($dizimosporcriterio->Tipo) == "") { ?>
		<th data-name="Tipo"><div id="elh_dizimosporcriterio_Tipo" class="dizimosporcriterio_Tipo"><div class="ewTableHeaderCaption"><?php echo $dizimosporcriterio->Tipo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Tipo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dizimosporcriterio->SortUrl($dizimosporcriterio->Tipo) ?>',2);"><div id="elh_dizimosporcriterio_Tipo" class="dizimosporcriterio_Tipo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dizimosporcriterio->Tipo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dizimosporcriterio->Tipo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dizimosporcriterio->Tipo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($dizimosporcriterio->Conta_Caixa->Visible) { // Conta_Caixa ?>
	<?php if ($dizimosporcriterio->SortUrl($dizimosporcriterio->Conta_Caixa) == "") { ?>
		<th data-name="Conta_Caixa"><div id="elh_dizimosporcriterio_Conta_Caixa" class="dizimosporcriterio_Conta_Caixa"><div class="ewTableHeaderCaption"><?php echo $dizimosporcriterio->Conta_Caixa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Conta_Caixa"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dizimosporcriterio->SortUrl($dizimosporcriterio->Conta_Caixa) ?>',2);"><div id="elh_dizimosporcriterio_Conta_Caixa" class="dizimosporcriterio_Conta_Caixa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dizimosporcriterio->Conta_Caixa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dizimosporcriterio->Conta_Caixa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dizimosporcriterio->Conta_Caixa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($dizimosporcriterio->Situacao->Visible) { // Situacao ?>
	<?php if ($dizimosporcriterio->SortUrl($dizimosporcriterio->Situacao) == "") { ?>
		<th data-name="Situacao"><div id="elh_dizimosporcriterio_Situacao" class="dizimosporcriterio_Situacao"><div class="ewTableHeaderCaption"><?php echo $dizimosporcriterio->Situacao->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Situacao"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dizimosporcriterio->SortUrl($dizimosporcriterio->Situacao) ?>',2);"><div id="elh_dizimosporcriterio_Situacao" class="dizimosporcriterio_Situacao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dizimosporcriterio->Situacao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dizimosporcriterio->Situacao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dizimosporcriterio->Situacao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($dizimosporcriterio->Descricao->Visible) { // Descricao ?>
	<?php if ($dizimosporcriterio->SortUrl($dizimosporcriterio->Descricao) == "") { ?>
		<th data-name="Descricao"><div id="elh_dizimosporcriterio_Descricao" class="dizimosporcriterio_Descricao"><div class="ewTableHeaderCaption"><?php echo $dizimosporcriterio->Descricao->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Descricao"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dizimosporcriterio->SortUrl($dizimosporcriterio->Descricao) ?>',2);"><div id="elh_dizimosporcriterio_Descricao" class="dizimosporcriterio_Descricao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dizimosporcriterio->Descricao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dizimosporcriterio->Descricao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dizimosporcriterio->Descricao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($dizimosporcriterio->Receitas->Visible) { // Receitas ?>
	<?php if ($dizimosporcriterio->SortUrl($dizimosporcriterio->Receitas) == "") { ?>
		<th data-name="Receitas"><div id="elh_dizimosporcriterio_Receitas" class="dizimosporcriterio_Receitas"><div class="ewTableHeaderCaption"><?php echo $dizimosporcriterio->Receitas->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Receitas"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dizimosporcriterio->SortUrl($dizimosporcriterio->Receitas) ?>',2);"><div id="elh_dizimosporcriterio_Receitas" class="dizimosporcriterio_Receitas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dizimosporcriterio->Receitas->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dizimosporcriterio->Receitas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dizimosporcriterio->Receitas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($dizimosporcriterio->FormaPagto->Visible) { // FormaPagto ?>
	<?php if ($dizimosporcriterio->SortUrl($dizimosporcriterio->FormaPagto) == "") { ?>
		<th data-name="FormaPagto"><div id="elh_dizimosporcriterio_FormaPagto" class="dizimosporcriterio_FormaPagto"><div class="ewTableHeaderCaption"><?php echo $dizimosporcriterio->FormaPagto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="FormaPagto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dizimosporcriterio->SortUrl($dizimosporcriterio->FormaPagto) ?>',2);"><div id="elh_dizimosporcriterio_FormaPagto" class="dizimosporcriterio_FormaPagto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dizimosporcriterio->FormaPagto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dizimosporcriterio->FormaPagto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dizimosporcriterio->FormaPagto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($dizimosporcriterio->Dt_Lancamento->Visible) { // Dt_Lancamento ?>
	<?php if ($dizimosporcriterio->SortUrl($dizimosporcriterio->Dt_Lancamento) == "") { ?>
		<th data-name="Dt_Lancamento"><div id="elh_dizimosporcriterio_Dt_Lancamento" class="dizimosporcriterio_Dt_Lancamento"><div class="ewTableHeaderCaption"><?php echo $dizimosporcriterio->Dt_Lancamento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Dt_Lancamento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dizimosporcriterio->SortUrl($dizimosporcriterio->Dt_Lancamento) ?>',2);"><div id="elh_dizimosporcriterio_Dt_Lancamento" class="dizimosporcriterio_Dt_Lancamento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dizimosporcriterio->Dt_Lancamento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dizimosporcriterio->Dt_Lancamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dizimosporcriterio->Dt_Lancamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($dizimosporcriterio->Vencimento->Visible) { // Vencimento ?>
	<?php if ($dizimosporcriterio->SortUrl($dizimosporcriterio->Vencimento) == "") { ?>
		<th data-name="Vencimento"><div id="elh_dizimosporcriterio_Vencimento" class="dizimosporcriterio_Vencimento"><div class="ewTableHeaderCaption"><?php echo $dizimosporcriterio->Vencimento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Vencimento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dizimosporcriterio->SortUrl($dizimosporcriterio->Vencimento) ?>',2);"><div id="elh_dizimosporcriterio_Vencimento" class="dizimosporcriterio_Vencimento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dizimosporcriterio->Vencimento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dizimosporcriterio->Vencimento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dizimosporcriterio->Vencimento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($dizimosporcriterio->Centro_de_Custo->Visible) { // Centro_de_Custo ?>
	<?php if ($dizimosporcriterio->SortUrl($dizimosporcriterio->Centro_de_Custo) == "") { ?>
		<th data-name="Centro_de_Custo"><div id="elh_dizimosporcriterio_Centro_de_Custo" class="dizimosporcriterio_Centro_de_Custo"><div class="ewTableHeaderCaption"><?php echo $dizimosporcriterio->Centro_de_Custo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Centro_de_Custo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dizimosporcriterio->SortUrl($dizimosporcriterio->Centro_de_Custo) ?>',2);"><div id="elh_dizimosporcriterio_Centro_de_Custo" class="dizimosporcriterio_Centro_de_Custo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dizimosporcriterio->Centro_de_Custo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dizimosporcriterio->Centro_de_Custo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dizimosporcriterio->Centro_de_Custo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$dizimosporcriterio_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($dizimosporcriterio->ExportAll && $dizimosporcriterio->Export <> "") {
	$dizimosporcriterio_list->StopRec = $dizimosporcriterio_list->TotalRecs;
} else {

	// Set the last record to display
	if ($dizimosporcriterio_list->TotalRecs > $dizimosporcriterio_list->StartRec + $dizimosporcriterio_list->DisplayRecs - 1)
		$dizimosporcriterio_list->StopRec = $dizimosporcriterio_list->StartRec + $dizimosporcriterio_list->DisplayRecs - 1;
	else
		$dizimosporcriterio_list->StopRec = $dizimosporcriterio_list->TotalRecs;
}
$dizimosporcriterio_list->RecCnt = $dizimosporcriterio_list->StartRec - 1;
if ($dizimosporcriterio_list->Recordset && !$dizimosporcriterio_list->Recordset->EOF) {
	$dizimosporcriterio_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $dizimosporcriterio_list->StartRec > 1)
		$dizimosporcriterio_list->Recordset->Move($dizimosporcriterio_list->StartRec - 1);
} elseif (!$dizimosporcriterio->AllowAddDeleteRow && $dizimosporcriterio_list->StopRec == 0) {
	$dizimosporcriterio_list->StopRec = $dizimosporcriterio->GridAddRowCount;
}

// Initialize aggregate
$dizimosporcriterio->RowType = EW_ROWTYPE_AGGREGATEINIT;
$dizimosporcriterio->ResetAttrs();
$dizimosporcriterio_list->RenderRow();
while ($dizimosporcriterio_list->RecCnt < $dizimosporcriterio_list->StopRec) {
	$dizimosporcriterio_list->RecCnt++;
	if (intval($dizimosporcriterio_list->RecCnt) >= intval($dizimosporcriterio_list->StartRec)) {
		$dizimosporcriterio_list->RowCnt++;

		// Set up key count
		$dizimosporcriterio_list->KeyCount = $dizimosporcriterio_list->RowIndex;

		// Init row class and style
		$dizimosporcriterio->ResetAttrs();
		$dizimosporcriterio->CssClass = "";
		if ($dizimosporcriterio->CurrentAction == "gridadd") {
		} else {
			$dizimosporcriterio_list->LoadRowValues($dizimosporcriterio_list->Recordset); // Load row values
		}
		$dizimosporcriterio->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$dizimosporcriterio->RowAttrs = array_merge($dizimosporcriterio->RowAttrs, array('data-rowindex'=>$dizimosporcriterio_list->RowCnt, 'id'=>'r' . $dizimosporcriterio_list->RowCnt . '_dizimosporcriterio', 'data-rowtype'=>$dizimosporcriterio->RowType));

		// Render row
		$dizimosporcriterio_list->RenderRow();

		// Render list options
		$dizimosporcriterio_list->RenderListOptions();
?>
	<tr<?php echo $dizimosporcriterio->RowAttributes() ?>>
<?php

// Render list options (body, left)
$dizimosporcriterio_list->ListOptions->Render("body", "left", $dizimosporcriterio_list->RowCnt);
?>
	<?php if ($dizimosporcriterio->Mes->Visible) { // Mes ?>
		<td data-name="Mes"<?php echo $dizimosporcriterio->Mes->CellAttributes() ?>>
<span<?php echo $dizimosporcriterio->Mes->ViewAttributes() ?>>
<?php echo $dizimosporcriterio->Mes->ListViewValue() ?></span>
<a id="<?php echo $dizimosporcriterio_list->PageObjName . "_row_" . $dizimosporcriterio_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($dizimosporcriterio->Tipo->Visible) { // Tipo ?>
		<td data-name="Tipo"<?php echo $dizimosporcriterio->Tipo->CellAttributes() ?>>
<span<?php echo $dizimosporcriterio->Tipo->ViewAttributes() ?>>
<?php echo $dizimosporcriterio->Tipo->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($dizimosporcriterio->Conta_Caixa->Visible) { // Conta_Caixa ?>
		<td data-name="Conta_Caixa"<?php echo $dizimosporcriterio->Conta_Caixa->CellAttributes() ?>>
<span<?php echo $dizimosporcriterio->Conta_Caixa->ViewAttributes() ?>>
<?php echo $dizimosporcriterio->Conta_Caixa->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($dizimosporcriterio->Situacao->Visible) { // Situacao ?>
		<td data-name="Situacao"<?php echo $dizimosporcriterio->Situacao->CellAttributes() ?>>
<span<?php echo $dizimosporcriterio->Situacao->ViewAttributes() ?>>
<?php echo $dizimosporcriterio->Situacao->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($dizimosporcriterio->Descricao->Visible) { // Descricao ?>
		<td data-name="Descricao"<?php echo $dizimosporcriterio->Descricao->CellAttributes() ?>>
<span<?php echo $dizimosporcriterio->Descricao->ViewAttributes() ?>>
<?php echo $dizimosporcriterio->Descricao->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($dizimosporcriterio->Receitas->Visible) { // Receitas ?>
		<td data-name="Receitas"<?php echo $dizimosporcriterio->Receitas->CellAttributes() ?>>
<span<?php echo $dizimosporcriterio->Receitas->ViewAttributes() ?>>
<?php echo $dizimosporcriterio->Receitas->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($dizimosporcriterio->FormaPagto->Visible) { // FormaPagto ?>
		<td data-name="FormaPagto"<?php echo $dizimosporcriterio->FormaPagto->CellAttributes() ?>>
<span<?php echo $dizimosporcriterio->FormaPagto->ViewAttributes() ?>>
<?php echo $dizimosporcriterio->FormaPagto->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($dizimosporcriterio->Dt_Lancamento->Visible) { // Dt_Lancamento ?>
		<td data-name="Dt_Lancamento"<?php echo $dizimosporcriterio->Dt_Lancamento->CellAttributes() ?>>
<span<?php echo $dizimosporcriterio->Dt_Lancamento->ViewAttributes() ?>>
<?php echo $dizimosporcriterio->Dt_Lancamento->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($dizimosporcriterio->Vencimento->Visible) { // Vencimento ?>
		<td data-name="Vencimento"<?php echo $dizimosporcriterio->Vencimento->CellAttributes() ?>>
<span<?php echo $dizimosporcriterio->Vencimento->ViewAttributes() ?>>
<?php echo $dizimosporcriterio->Vencimento->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($dizimosporcriterio->Centro_de_Custo->Visible) { // Centro_de_Custo ?>
		<td data-name="Centro_de_Custo"<?php echo $dizimosporcriterio->Centro_de_Custo->CellAttributes() ?>>
<span<?php echo $dizimosporcriterio->Centro_de_Custo->ViewAttributes() ?>>
<?php echo $dizimosporcriterio->Centro_de_Custo->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$dizimosporcriterio_list->ListOptions->Render("body", "right", $dizimosporcriterio_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($dizimosporcriterio->CurrentAction <> "gridadd")
		$dizimosporcriterio_list->Recordset->MoveNext();
}
?>
</tbody>
<?php

// Render aggregate row
$dizimosporcriterio->RowType = EW_ROWTYPE_AGGREGATE;
$dizimosporcriterio->ResetAttrs();
$dizimosporcriterio_list->RenderRow();
?>
<?php if ($dizimosporcriterio_list->TotalRecs > 0 && ($dizimosporcriterio->CurrentAction <> "gridadd" && $dizimosporcriterio->CurrentAction <> "gridedit")) { ?>
<tfoot><!-- Table footer -->
	<tr class="ewTableFooter">
<?php

// Render list options
$dizimosporcriterio_list->RenderListOptions();

// Render list options (footer, left)
$dizimosporcriterio_list->ListOptions->Render("footer", "left");
?>
	<?php if ($dizimosporcriterio->Mes->Visible) { // Mes ?>
		<td data-name="Mes"><span id="elf_dizimosporcriterio_Mes" class="dizimosporcriterio_Mes">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($dizimosporcriterio->Tipo->Visible) { // Tipo ?>
		<td data-name="Tipo"><span id="elf_dizimosporcriterio_Tipo" class="dizimosporcriterio_Tipo">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($dizimosporcriterio->Conta_Caixa->Visible) { // Conta_Caixa ?>
		<td data-name="Conta_Caixa"><span id="elf_dizimosporcriterio_Conta_Caixa" class="dizimosporcriterio_Conta_Caixa">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($dizimosporcriterio->Situacao->Visible) { // Situacao ?>
		<td data-name="Situacao"><span id="elf_dizimosporcriterio_Situacao" class="dizimosporcriterio_Situacao">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($dizimosporcriterio->Descricao->Visible) { // Descricao ?>
		<td data-name="Descricao"><span id="elf_dizimosporcriterio_Descricao" class="dizimosporcriterio_Descricao">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($dizimosporcriterio->Receitas->Visible) { // Receitas ?>
		<td data-name="Receitas"><span id="elf_dizimosporcriterio_Receitas" class="dizimosporcriterio_Receitas">
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?></span>
<?php echo $dizimosporcriterio->Receitas->ViewValue ?>
		</span></td>
	<?php } ?>
	<?php if ($dizimosporcriterio->FormaPagto->Visible) { // FormaPagto ?>
		<td data-name="FormaPagto"><span id="elf_dizimosporcriterio_FormaPagto" class="dizimosporcriterio_FormaPagto">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($dizimosporcriterio->Dt_Lancamento->Visible) { // Dt_Lancamento ?>
		<td data-name="Dt_Lancamento"><span id="elf_dizimosporcriterio_Dt_Lancamento" class="dizimosporcriterio_Dt_Lancamento">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($dizimosporcriterio->Vencimento->Visible) { // Vencimento ?>
		<td data-name="Vencimento"><span id="elf_dizimosporcriterio_Vencimento" class="dizimosporcriterio_Vencimento">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($dizimosporcriterio->Centro_de_Custo->Visible) { // Centro_de_Custo ?>
		<td data-name="Centro_de_Custo"><span id="elf_dizimosporcriterio_Centro_de_Custo" class="dizimosporcriterio_Centro_de_Custo">
		&nbsp;
		</span></td>
	<?php } ?>
<?php

// Render list options (footer, right)
$dizimosporcriterio_list->ListOptions->Render("footer", "right");
?>
	</tr>
</tfoot>	
<?php } ?>
</table>
<?php } ?>
<?php if ($dizimosporcriterio->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($dizimosporcriterio_list->Recordset)
	$dizimosporcriterio_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($dizimosporcriterio_list->TotalRecs == 0 && $dizimosporcriterio->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($dizimosporcriterio_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($dizimosporcriterio->Export == "") { ?>
<script type="text/javascript">
fdizimosporcriteriolistsrch.Init();
fdizimosporcriteriolist.Init();
$(document).ready(function($) {	$("#ajuda").click(function() {	bootbox.dialog({title: "Informações de Ajuda", message: '<?php echo str_replace("\r\n"," ",trim($help)) ?>', buttons: { success: { label: "Fechar" }}}); });});
</script>
<?php } ?>
<?php
$dizimosporcriterio_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($dizimosporcriterio->Export == "") { ?>
<script type="text/javascript">
$(document).ready(function($) {
	$("#elf_dizimosporcriterio_Receitas").addClass("badge bg-dark");
});
</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$dizimosporcriterio_list->Page_Terminate();
?>
