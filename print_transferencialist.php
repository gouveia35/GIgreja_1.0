<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "print_transferenciainfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$print_transferencia_list = NULL; // Initialize page object first

class cprint_transferencia_list extends cprint_transferencia {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{2B7992FC-5911-46A7-9310-01F4D4225C49}";

	// Table name
	var $TableName = 'print_transferencia';

	// Page object name
	var $PageObjName = 'print_transferencia_list';

	// Grid form hidden field names
	var $FormName = 'fprint_transferencialist';
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
	var $ExportExcelCustom = TRUE;
	var $ExportWordCustom = TRUE;
	var $ExportPdfCustom = TRUE;
	var $ExportEmailCustom = TRUE;

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

		// Table object (print_transferencia)
		if (!isset($GLOBALS["print_transferencia"]) || get_class($GLOBALS["print_transferencia"]) == "cprint_transferencia") {
			$GLOBALS["print_transferencia"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["print_transferencia"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "print_transferenciaadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "print_transferenciadelete.php";
		$this->MultiUpdateUrl = "print_transferenciaupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'print_transferencia', TRUE);

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

		// Custom export (post back from ew_ApplyTemplate), export and terminate page
		if (@$_POST["customexport"] <> "") {
			$this->CustomExport = $_POST["customexport"];
			$this->Export = $this->CustomExport;
			$this->Page_Terminate();
			exit();
		}

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
		if (@$_POST["customexport"] == "") {

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		}

		// Export
		global $EW_EXPORT, $print_transferencia;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
			if (is_array(@$_SESSION[EW_SESSION_TEMP_IMAGES])) // Restore temp images
				$gTmpImages = @$_SESSION[EW_SESSION_TEMP_IMAGES];
			if (@$_POST["data"] <> "")
				$sContent = $_POST["data"];
			$gsExportFile = @$_POST["filename"];
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($print_transferencia);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
	if ($this->CustomExport <> "") { // Save temp images array for custom export
		if (is_array($gTmpImages))
			$_SESSION[EW_SESSION_TEMP_IMAGES] = $gTmpImages;
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
	var $DisplayRecs = 1;
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
			$this->AllowAddDeleteRow = FALSE; // Do not allow add/delete row

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process custom action first
			$this->ProcessCustomAction();

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
			$this->DisplayRecs = 1; // Load default
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
		$this->BuildSearchSql($sWhere, $this->Id_membro, $Default, FALSE); // Id_membro
		$this->BuildSearchSql($sWhere, $this->Sexo, $Default, FALSE); // Sexo
		$this->BuildSearchSql($sWhere, $this->EstadoCivil, $Default, FALSE); // EstadoCivil
		$this->BuildSearchSql($sWhere, $this->CPF, $Default, FALSE); // CPF
		$this->BuildSearchSql($sWhere, $this->RG, $Default, FALSE); // RG
		$this->BuildSearchSql($sWhere, $this->Matricula, $Default, FALSE); // Matricula
		$this->BuildSearchSql($sWhere, $this->Admissao, $Default, FALSE); // Admissao
		$this->BuildSearchSql($sWhere, $this->CargoMinisterial, $Default, FALSE); // CargoMinisterial
		$this->BuildSearchSql($sWhere, $this->Da_Igreja, $Default, FALSE); // Da_Igreja

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->Id_membro->AdvancedSearch->Save(); // Id_membro
			$this->Sexo->AdvancedSearch->Save(); // Sexo
			$this->EstadoCivil->AdvancedSearch->Save(); // EstadoCivil
			$this->CPF->AdvancedSearch->Save(); // CPF
			$this->RG->AdvancedSearch->Save(); // RG
			$this->Matricula->AdvancedSearch->Save(); // Matricula
			$this->Admissao->AdvancedSearch->Save(); // Admissao
			$this->CargoMinisterial->AdvancedSearch->Save(); // CargoMinisterial
			$this->Da_Igreja->AdvancedSearch->Save(); // Da_Igreja
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
		if ($this->Id_membro->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Sexo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->EstadoCivil->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->CPF->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->RG->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Matricula->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Admissao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->CargoMinisterial->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Da_Igreja->AdvancedSearch->IssetSession())
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
		$this->Id_membro->AdvancedSearch->UnsetSession();
		$this->Sexo->AdvancedSearch->UnsetSession();
		$this->EstadoCivil->AdvancedSearch->UnsetSession();
		$this->CPF->AdvancedSearch->UnsetSession();
		$this->RG->AdvancedSearch->UnsetSession();
		$this->Matricula->AdvancedSearch->UnsetSession();
		$this->Admissao->AdvancedSearch->UnsetSession();
		$this->CargoMinisterial->AdvancedSearch->UnsetSession();
		$this->Da_Igreja->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->Id_membro->AdvancedSearch->Load();
		$this->Sexo->AdvancedSearch->Load();
		$this->EstadoCivil->AdvancedSearch->Load();
		$this->CPF->AdvancedSearch->Load();
		$this->RG->AdvancedSearch->Load();
		$this->Matricula->AdvancedSearch->Load();
		$this->Admissao->AdvancedSearch->Load();
		$this->CargoMinisterial->AdvancedSearch->Load();
		$this->Da_Igreja->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->Id_membro, $bCtrl); // Id_membro
			$this->UpdateSort($this->Nome, $bCtrl); // Nome
			$this->UpdateSort($this->Sexo, $bCtrl); // Sexo
			$this->UpdateSort($this->Nacionalidade, $bCtrl); // Nacionalidade
			$this->UpdateSort($this->EstadoCivil, $bCtrl); // EstadoCivil
			$this->UpdateSort($this->CPF, $bCtrl); // CPF
			$this->UpdateSort($this->RG, $bCtrl); // RG
			$this->UpdateSort($this->Matricula, $bCtrl); // Matricula
			$this->UpdateSort($this->Admissao, $bCtrl); // Admissao
			$this->UpdateSort($this->CargoMinisterial, $bCtrl); // CargoMinisterial
			$this->UpdateSort($this->Da_Igreja, $bCtrl); // Da_Igreja
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
				$this->Id_membro->setSort("");
				$this->Nome->setSort("");
				$this->Sexo->setSort("");
				$this->Nacionalidade->setSort("");
				$this->EstadoCivil->setSort("");
				$this->CPF->setSort("");
				$this->RG->setSort("");
				$this->Matricula->setSort("");
				$this->Admissao->setSort("");
				$this->CargoMinisterial->setSort("");
				$this->Da_Igreja->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->Id_membro->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fprint_transferencialist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-warning ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fprint_transferencialistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		// Id_membro

		$this->Id_membro->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Id_membro"]);
		if ($this->Id_membro->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Id_membro->AdvancedSearch->SearchOperator = @$_GET["z_Id_membro"];

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

		// Matricula
		$this->Matricula->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Matricula"]);
		if ($this->Matricula->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Matricula->AdvancedSearch->SearchOperator = @$_GET["z_Matricula"];

		// Admissao
		$this->Admissao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Admissao"]);
		if ($this->Admissao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Admissao->AdvancedSearch->SearchOperator = @$_GET["z_Admissao"];
		$this->Admissao->AdvancedSearch->SearchCondition = @$_GET["v_Admissao"];
		$this->Admissao->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_Admissao"]);
		if ($this->Admissao->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->Admissao->AdvancedSearch->SearchOperator2 = @$_GET["w_Admissao"];

		// CargoMinisterial
		$this->CargoMinisterial->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_CargoMinisterial"]);
		if ($this->CargoMinisterial->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->CargoMinisterial->AdvancedSearch->SearchOperator = @$_GET["z_CargoMinisterial"];

		// Da_Igreja
		$this->Da_Igreja->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Da_Igreja"]);
		if ($this->Da_Igreja->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Da_Igreja->AdvancedSearch->SearchOperator = @$_GET["z_Da_Igreja"];
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
		$this->Nome->setDbValue($rs->fields('Nome'));
		$this->Sexo->setDbValue($rs->fields('Sexo'));
		$this->Nacionalidade->setDbValue($rs->fields('Nacionalidade'));
		$this->EstadoCivil->setDbValue($rs->fields('EstadoCivil'));
		$this->CPF->setDbValue($rs->fields('CPF'));
		$this->RG->setDbValue($rs->fields('RG'));
		$this->Matricula->setDbValue($rs->fields('Matricula'));
		$this->Admissao->setDbValue($rs->fields('Admissao'));
		$this->CargoMinisterial->setDbValue($rs->fields('CargoMinisterial'));
		$this->Da_Igreja->setDbValue($rs->fields('Da_Igreja'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id_membro->DbValue = $row['Id_membro'];
		$this->Nome->DbValue = $row['Nome'];
		$this->Sexo->DbValue = $row['Sexo'];
		$this->Nacionalidade->DbValue = $row['Nacionalidade'];
		$this->EstadoCivil->DbValue = $row['EstadoCivil'];
		$this->CPF->DbValue = $row['CPF'];
		$this->RG->DbValue = $row['RG'];
		$this->Matricula->DbValue = $row['Matricula'];
		$this->Admissao->DbValue = $row['Admissao'];
		$this->CargoMinisterial->DbValue = $row['CargoMinisterial'];
		$this->Da_Igreja->DbValue = $row['Da_Igreja'];
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

		// Nome
		// Sexo
		// Nacionalidade
		// EstadoCivil
		// CPF
		// RG
		// Matricula
		// Admissao
		// CargoMinisterial

		$this->CargoMinisterial->CellCssStyle = "width: 250px;";

		// Da_Igreja
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// Id_membro
			if (strval($this->Id_membro->CurrentValue) <> "") {
				$sFilterWrk = "`Id_membro`" . ew_SearchString("=", $this->Id_membro->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `Id_membro`, `Nome` AS `DispFld`, `CPF` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `membro`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Id_membro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->Id_membro->ViewValue = $rswrk->fields('DispFld');
					$this->Id_membro->ViewValue .= ew_ValueSeparator(1,$this->Id_membro) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->Id_membro->ViewValue = $this->Id_membro->CurrentValue;
				}
			} else {
				$this->Id_membro->ViewValue = NULL;
			}
			$this->Id_membro->ViewCustomAttributes = "";

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

			// Matricula
			$this->Matricula->ViewValue = $this->Matricula->CurrentValue;
			$this->Matricula->ViewCustomAttributes = "";

			// Admissao
			$this->Admissao->ViewValue = $this->Admissao->CurrentValue;
			$this->Admissao->ViewValue = ew_FormatDateTime($this->Admissao->ViewValue, 7);
			$this->Admissao->ViewCustomAttributes = "";

			// CargoMinisterial
			$this->CargoMinisterial->ViewValue = $this->CargoMinisterial->CurrentValue;
			$this->CargoMinisterial->ViewCustomAttributes = "";

			// Da_Igreja
			$this->Da_Igreja->ViewValue = $this->Da_Igreja->CurrentValue;
			$this->Da_Igreja->ViewCustomAttributes = "";

			// Id_membro
			$this->Id_membro->LinkCustomAttributes = "";
			$this->Id_membro->HrefValue = "";
			$this->Id_membro->TooltipValue = "";

			// Nome
			$this->Nome->LinkCustomAttributes = "";
			$this->Nome->HrefValue = "";
			$this->Nome->TooltipValue = "";

			// Sexo
			$this->Sexo->LinkCustomAttributes = "";
			$this->Sexo->HrefValue = "";
			$this->Sexo->TooltipValue = "";

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

			// Matricula
			$this->Matricula->LinkCustomAttributes = "";
			$this->Matricula->HrefValue = "";
			$this->Matricula->TooltipValue = "";

			// Admissao
			$this->Admissao->LinkCustomAttributes = "";
			$this->Admissao->HrefValue = "";
			$this->Admissao->TooltipValue = "";

			// CargoMinisterial
			$this->CargoMinisterial->LinkCustomAttributes = "";
			$this->CargoMinisterial->HrefValue = "";
			$this->CargoMinisterial->TooltipValue = "";

			// Da_Igreja
			$this->Da_Igreja->LinkCustomAttributes = "";
			$this->Da_Igreja->HrefValue = "";
			$this->Da_Igreja->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// Id_membro
			$this->Id_membro->EditAttrs["class"] = "form-control";
			$this->Id_membro->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `Id_membro`, `Nome` AS `DispFld`, `CPF` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `membro`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Id_membro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Id_membro->EditValue = $arwrk;

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

			// Nacionalidade
			$this->Nacionalidade->EditAttrs["class"] = "form-control";
			$this->Nacionalidade->EditCustomAttributes = "";
			$this->Nacionalidade->EditValue = ew_HtmlEncode($this->Nacionalidade->AdvancedSearch->SearchValue);

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

			// RG
			$this->RG->EditAttrs["class"] = "form-control";
			$this->RG->EditCustomAttributes = "";
			$this->RG->EditValue = ew_HtmlEncode($this->RG->AdvancedSearch->SearchValue);

			// Matricula
			$this->Matricula->EditAttrs["class"] = "form-control";
			$this->Matricula->EditCustomAttributes = "";
			$this->Matricula->EditValue = ew_HtmlEncode($this->Matricula->AdvancedSearch->SearchValue);

			// Admissao
			$this->Admissao->EditAttrs["class"] = "form-control";
			$this->Admissao->EditCustomAttributes = "";
			$this->Admissao->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->Admissao->AdvancedSearch->SearchValue, 7), 7));
			$this->Admissao->EditAttrs["class"] = "form-control";
			$this->Admissao->EditCustomAttributes = "";
			$this->Admissao->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->Admissao->AdvancedSearch->SearchValue2, 7), 7));

			// CargoMinisterial
			$this->CargoMinisterial->EditAttrs["class"] = "form-control";
			$this->CargoMinisterial->EditCustomAttributes = "";
			$this->CargoMinisterial->EditValue = ew_HtmlEncode($this->CargoMinisterial->AdvancedSearch->SearchValue);

			// Da_Igreja
			$this->Da_Igreja->EditAttrs["class"] = "form-control";
			$this->Da_Igreja->EditCustomAttributes = "";
			$this->Da_Igreja->EditValue = ew_HtmlEncode($this->Da_Igreja->AdvancedSearch->SearchValue);
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
		$this->Id_membro->AdvancedSearch->Load();
		$this->Sexo->AdvancedSearch->Load();
		$this->EstadoCivil->AdvancedSearch->Load();
		$this->CPF->AdvancedSearch->Load();
		$this->RG->AdvancedSearch->Load();
		$this->Matricula->AdvancedSearch->Load();
		$this->Admissao->AdvancedSearch->Load();
		$this->CargoMinisterial->AdvancedSearch->Load();
		$this->Da_Igreja->AdvancedSearch->Load();
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
		if ($this->ExportExcelCustom)
			$item->Body = "<a href=\"javascript:void(0);\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" onclick=\"ew_Export(document.fprint_transferencialist,'" . $this->ExportExcelUrl . "','excel',true);\">" . $Language->Phrase("ExportToExcel") . "</a>";
		else
			$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = FALSE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		if ($this->ExportWordCustom)
			$item->Body = "<a href=\"javascript:void(0);\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" onclick=\"ew_Export(document.fprint_transferencialist,'" . $this->ExportWordUrl . "','word',true);\">" . $Language->Phrase("ExportToWord") . "</a>";
		else
			$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = FALSE;

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
		if ($this->ExportPdfCustom)
			$item->Body = "<a href=\"javascript:void(0);\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" onclick=\"ew_Export(document.fprint_transferencialist,'" . $this->ExportPdfUrl . "','pdf',true);\">" . $Language->Phrase("ExportToPDF") . "</a>";
		else
			$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = $this->ExportEmailCustom ? ",url:'" . $this->PageUrl() . "export=email&amp;custom=1'" : "";
		$item->Body = "<button id=\"emf_print_transferencia\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_print_transferencia',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fprint_transferencialist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		echo (empty($_GET["export"])) ? '' : "<script>$(document).ready(function($) { $('.ewGrid').removeClass();})</script>";
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
if (!isset($print_transferencia_list)) $print_transferencia_list = new cprint_transferencia_list();

// Page init
$print_transferencia_list->Page_Init();

// Page main
$print_transferencia_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$print_transferencia_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($print_transferencia->Export == "") { ?>
<script type="text/javascript">

// Page object
var print_transferencia_list = new ew_Page("print_transferencia_list");
print_transferencia_list.PageID = "list"; // Page ID
var EW_PAGE_ID = print_transferencia_list.PageID; // For backward compatibility

// Form object
var fprint_transferencialist = new ew_Form("fprint_transferencialist");
fprint_transferencialist.FormKeyCountName = '<?php echo $print_transferencia_list->FormKeyCountName ?>';

// Form_CustomValidate event
fprint_transferencialist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprint_transferencialist.ValidateRequired = true;
<?php } else { ?>
fprint_transferencialist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fprint_transferencialist.Lists["x_Id_membro"] = {"LinkField":"x_Id_membro","Ajax":null,"AutoFill":false,"DisplayFields":["x_Nome","x_CPF","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fprint_transferencialistsrch = new ew_Form("fprint_transferencialistsrch");

// Validate function for search
fprint_transferencialistsrch.Validate = function(fobj) {
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
fprint_transferencialistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprint_transferencialistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fprint_transferencialistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fprint_transferencialistsrch.Lists["x_Id_membro"] = {"LinkField":"x_Id_membro","Ajax":null,"AutoFill":false,"DisplayFields":["x_Nome","x_CPF","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($print_transferencia->Export == "") { ?>
<div class="ewToolbar">
<?php if ($print_transferencia->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($print_transferencia_list->TotalRecs > 0 && $print_transferencia_list->ExportOptions->Visible()) { ?>
<?php $print_transferencia_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($print_transferencia_list->SearchOptions->Visible()) { ?>
<?php $print_transferencia_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($print_transferencia->Export == "") { ?>
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
		if ($print_transferencia_list->TotalRecs <= 0)
			$print_transferencia_list->TotalRecs = $print_transferencia->SelectRecordCount();
	} else {
		if (!$print_transferencia_list->Recordset && ($print_transferencia_list->Recordset = $print_transferencia_list->LoadRecordset()))
			$print_transferencia_list->TotalRecs = $print_transferencia_list->Recordset->RecordCount();
	}
	$print_transferencia_list->StartRec = 1;
	if ($print_transferencia_list->DisplayRecs <= 0 || ($print_transferencia->Export <> "" && $print_transferencia->ExportAll)) // Display all records
		$print_transferencia_list->DisplayRecs = $print_transferencia_list->TotalRecs;
	if (!($print_transferencia->Export <> "" && $print_transferencia->ExportAll))
		$print_transferencia_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$print_transferencia_list->Recordset = $print_transferencia_list->LoadRecordset($print_transferencia_list->StartRec-1, $print_transferencia_list->DisplayRecs);

	// Set no record found message
	if ($print_transferencia->CurrentAction == "" && $print_transferencia_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$print_transferencia_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($print_transferencia_list->SearchWhere == "0=101")
			$print_transferencia_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$print_transferencia_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$print_transferencia_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($print_transferencia->Export == "" && $print_transferencia->CurrentAction == "") { ?>
<form name="fprint_transferencialistsrch" id="fprint_transferencialistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($print_transferencia_list->SearchWhere <> "") ? " in" : ""; ?>
<div id="fprint_transferencialistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="print_transferencia">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$print_transferencia_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$print_transferencia->RowType = EW_ROWTYPE_SEARCH;

// Render row
$print_transferencia->ResetAttrs();
$print_transferencia_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($print_transferencia->Id_membro->Visible) { // Id_membro ?>
	<div id="xsc_Id_membro" class="ewCell form-group">
		<label for="x_Id_membro" class="ewSearchCaption ewLabel"><?php echo $print_transferencia->Id_membro->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Id_membro" id="z_Id_membro" value="="></span>
		<span class="ewSearchField">
<select data-field="x_Id_membro" id="x_Id_membro" name="x_Id_membro"<?php echo $print_transferencia->Id_membro->EditAttributes() ?>>
<?php
if (is_array($print_transferencia->Id_membro->EditValue)) {
	$arwrk = $print_transferencia->Id_membro->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($print_transferencia->Id_membro->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$print_transferencia->Id_membro) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fprint_transferencialistsrch.Lists["x_Id_membro"].Options = <?php echo (is_array($print_transferencia->Id_membro->EditValue)) ? ew_ArrayToJson($print_transferencia->Id_membro->EditValue, 1) : "[]" ?>;
</script>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($print_transferencia->CPF->Visible) { // CPF ?>
	<div id="xsc_CPF" class="ewCell form-group">
		<label for="x_CPF" class="ewSearchCaption ewLabel"><?php echo $print_transferencia->CPF->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_CPF" id="z_CPF" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_CPF" name="x_CPF" id="x_CPF" size="30" maxlength="15" value="<?php echo $print_transferencia->CPF->EditValue ?>"<?php echo $print_transferencia->CPF->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><i class='glyphicon glyphicon-search'></i>&nbsp;<?php echo $Language->Phrase("QuickSearchBtn") ?></button>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $print_transferencia_list->ShowPageHeader(); ?>
<?php
$print_transferencia_list->ShowMessage();
?>
<?php if ($print_transferencia_list->TotalRecs > 0 || $print_transferencia->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($print_transferencia->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($print_transferencia->CurrentAction <> "gridadd" && $print_transferencia->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($print_transferencia_list->Pager)) $print_transferencia_list->Pager = new cPrevNextPager($print_transferencia_list->StartRec, $print_transferencia_list->DisplayRecs, $print_transferencia_list->TotalRecs) ?>
<?php if ($print_transferencia_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($print_transferencia_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $print_transferencia_list->PageUrl() ?>start=<?php echo $print_transferencia_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($print_transferencia_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $print_transferencia_list->PageUrl() ?>start=<?php echo $print_transferencia_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $print_transferencia_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($print_transferencia_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $print_transferencia_list->PageUrl() ?>start=<?php echo $print_transferencia_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($print_transferencia_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $print_transferencia_list->PageUrl() ?>start=<?php echo $print_transferencia_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $print_transferencia_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $print_transferencia_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $print_transferencia_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $print_transferencia_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($print_transferencia_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fprint_transferencialist" id="fprint_transferencialist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($print_transferencia_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $print_transferencia_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="print_transferencia">
<div id="gmp_print_transferencia" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($print_transferencia_list->TotalRecs > 0) { ?>
<table id="tbl_print_transferencialist" class="table ewTable" style="display: none">
<?php echo $print_transferencia->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$print_transferencia_list->RenderListOptions();

// Render list options (header, left)
$print_transferencia_list->ListOptions->Render("header", "", "", "block", $print_transferencia->TableVar, "print_transferencialist");
?>
<?php if ($print_transferencia->Id_membro->Visible) { // Id_membro ?>
	<?php if ($print_transferencia->SortUrl($print_transferencia->Id_membro) == "") { ?>
		<th data-name="Id_membro"><div id="elh_print_transferencia_Id_membro" class="print_transferencia_Id_membro"><div class="ewTableHeaderCaption" style="white-space: nowrap;"><script id="tpc_print_transferencia_Id_membro" class="print_transferencialist" type="text/html"><span><?php echo $print_transferencia->Id_membro->FldCaption() ?></span></script></div></div></th>
	<?php } else { ?>
		<th data-name="Id_membro"><script id="tpc_print_transferencia_Id_membro" class="print_transferencialist" type="text/html"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $print_transferencia->SortUrl($print_transferencia->Id_membro) ?>',2);"><div id="elh_print_transferencia_Id_membro" class="print_transferencia_Id_membro">
			<div class="ewTableHeaderBtn" style="white-space: nowrap;"><span class="ewTableHeaderCaption"><?php echo $print_transferencia->Id_membro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($print_transferencia->Id_membro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($print_transferencia->Id_membro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></script></th>
	<?php } ?>
<?php } ?>		
<?php if ($print_transferencia->Nome->Visible) { // Nome ?>
	<?php if ($print_transferencia->SortUrl($print_transferencia->Nome) == "") { ?>
		<th data-name="Nome"><div id="elh_print_transferencia_Nome" class="print_transferencia_Nome"><div class="ewTableHeaderCaption"><script id="tpc_print_transferencia_Nome" class="print_transferencialist" type="text/html"><span><?php echo $print_transferencia->Nome->FldCaption() ?></span></script></div></div></th>
	<?php } else { ?>
		<th data-name="Nome"><script id="tpc_print_transferencia_Nome" class="print_transferencialist" type="text/html"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $print_transferencia->SortUrl($print_transferencia->Nome) ?>',2);"><div id="elh_print_transferencia_Nome" class="print_transferencia_Nome">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $print_transferencia->Nome->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($print_transferencia->Nome->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($print_transferencia->Nome->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></script></th>
	<?php } ?>
<?php } ?>		
<?php if ($print_transferencia->Sexo->Visible) { // Sexo ?>
	<?php if ($print_transferencia->SortUrl($print_transferencia->Sexo) == "") { ?>
		<th data-name="Sexo"><div id="elh_print_transferencia_Sexo" class="print_transferencia_Sexo"><div class="ewTableHeaderCaption"><script id="tpc_print_transferencia_Sexo" class="print_transferencialist" type="text/html"><span><?php echo $print_transferencia->Sexo->FldCaption() ?></span></script></div></div></th>
	<?php } else { ?>
		<th data-name="Sexo"><script id="tpc_print_transferencia_Sexo" class="print_transferencialist" type="text/html"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $print_transferencia->SortUrl($print_transferencia->Sexo) ?>',2);"><div id="elh_print_transferencia_Sexo" class="print_transferencia_Sexo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $print_transferencia->Sexo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($print_transferencia->Sexo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($print_transferencia->Sexo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></script></th>
	<?php } ?>
<?php } ?>		
<?php if ($print_transferencia->Nacionalidade->Visible) { // Nacionalidade ?>
	<?php if ($print_transferencia->SortUrl($print_transferencia->Nacionalidade) == "") { ?>
		<th data-name="Nacionalidade"><div id="elh_print_transferencia_Nacionalidade" class="print_transferencia_Nacionalidade"><div class="ewTableHeaderCaption"><script id="tpc_print_transferencia_Nacionalidade" class="print_transferencialist" type="text/html"><span><?php echo $print_transferencia->Nacionalidade->FldCaption() ?></span></script></div></div></th>
	<?php } else { ?>
		<th data-name="Nacionalidade"><script id="tpc_print_transferencia_Nacionalidade" class="print_transferencialist" type="text/html"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $print_transferencia->SortUrl($print_transferencia->Nacionalidade) ?>',2);"><div id="elh_print_transferencia_Nacionalidade" class="print_transferencia_Nacionalidade">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $print_transferencia->Nacionalidade->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($print_transferencia->Nacionalidade->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($print_transferencia->Nacionalidade->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></script></th>
	<?php } ?>
<?php } ?>		
<?php if ($print_transferencia->EstadoCivil->Visible) { // EstadoCivil ?>
	<?php if ($print_transferencia->SortUrl($print_transferencia->EstadoCivil) == "") { ?>
		<th data-name="EstadoCivil"><div id="elh_print_transferencia_EstadoCivil" class="print_transferencia_EstadoCivil"><div class="ewTableHeaderCaption"><script id="tpc_print_transferencia_EstadoCivil" class="print_transferencialist" type="text/html"><span><?php echo $print_transferencia->EstadoCivil->FldCaption() ?></span></script></div></div></th>
	<?php } else { ?>
		<th data-name="EstadoCivil"><script id="tpc_print_transferencia_EstadoCivil" class="print_transferencialist" type="text/html"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $print_transferencia->SortUrl($print_transferencia->EstadoCivil) ?>',2);"><div id="elh_print_transferencia_EstadoCivil" class="print_transferencia_EstadoCivil">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $print_transferencia->EstadoCivil->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($print_transferencia->EstadoCivil->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($print_transferencia->EstadoCivil->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></script></th>
	<?php } ?>
<?php } ?>		
<?php if ($print_transferencia->CPF->Visible) { // CPF ?>
	<?php if ($print_transferencia->SortUrl($print_transferencia->CPF) == "") { ?>
		<th data-name="CPF"><div id="elh_print_transferencia_CPF" class="print_transferencia_CPF"><div class="ewTableHeaderCaption"><script id="tpc_print_transferencia_CPF" class="print_transferencialist" type="text/html"><span><?php echo $print_transferencia->CPF->FldCaption() ?></span></script></div></div></th>
	<?php } else { ?>
		<th data-name="CPF"><script id="tpc_print_transferencia_CPF" class="print_transferencialist" type="text/html"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $print_transferencia->SortUrl($print_transferencia->CPF) ?>',2);"><div id="elh_print_transferencia_CPF" class="print_transferencia_CPF">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $print_transferencia->CPF->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($print_transferencia->CPF->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($print_transferencia->CPF->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></script></th>
	<?php } ?>
<?php } ?>		
<?php if ($print_transferencia->RG->Visible) { // RG ?>
	<?php if ($print_transferencia->SortUrl($print_transferencia->RG) == "") { ?>
		<th data-name="RG"><div id="elh_print_transferencia_RG" class="print_transferencia_RG"><div class="ewTableHeaderCaption"><script id="tpc_print_transferencia_RG" class="print_transferencialist" type="text/html"><span><?php echo $print_transferencia->RG->FldCaption() ?></span></script></div></div></th>
	<?php } else { ?>
		<th data-name="RG"><script id="tpc_print_transferencia_RG" class="print_transferencialist" type="text/html"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $print_transferencia->SortUrl($print_transferencia->RG) ?>',2);"><div id="elh_print_transferencia_RG" class="print_transferencia_RG">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $print_transferencia->RG->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($print_transferencia->RG->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($print_transferencia->RG->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></script></th>
	<?php } ?>
<?php } ?>		
<?php if ($print_transferencia->Matricula->Visible) { // Matricula ?>
	<?php if ($print_transferencia->SortUrl($print_transferencia->Matricula) == "") { ?>
		<th data-name="Matricula"><div id="elh_print_transferencia_Matricula" class="print_transferencia_Matricula"><div class="ewTableHeaderCaption"><script id="tpc_print_transferencia_Matricula" class="print_transferencialist" type="text/html"><span><?php echo $print_transferencia->Matricula->FldCaption() ?></span></script></div></div></th>
	<?php } else { ?>
		<th data-name="Matricula"><script id="tpc_print_transferencia_Matricula" class="print_transferencialist" type="text/html"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $print_transferencia->SortUrl($print_transferencia->Matricula) ?>',2);"><div id="elh_print_transferencia_Matricula" class="print_transferencia_Matricula">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $print_transferencia->Matricula->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($print_transferencia->Matricula->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($print_transferencia->Matricula->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></script></th>
	<?php } ?>
<?php } ?>		
<?php if ($print_transferencia->Admissao->Visible) { // Admissao ?>
	<?php if ($print_transferencia->SortUrl($print_transferencia->Admissao) == "") { ?>
		<th data-name="Admissao"><div id="elh_print_transferencia_Admissao" class="print_transferencia_Admissao"><div class="ewTableHeaderCaption"><script id="tpc_print_transferencia_Admissao" class="print_transferencialist" type="text/html"><span><?php echo $print_transferencia->Admissao->FldCaption() ?></span></script></div></div></th>
	<?php } else { ?>
		<th data-name="Admissao"><script id="tpc_print_transferencia_Admissao" class="print_transferencialist" type="text/html"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $print_transferencia->SortUrl($print_transferencia->Admissao) ?>',2);"><div id="elh_print_transferencia_Admissao" class="print_transferencia_Admissao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $print_transferencia->Admissao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($print_transferencia->Admissao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($print_transferencia->Admissao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></script></th>
	<?php } ?>
<?php } ?>		
<?php if ($print_transferencia->CargoMinisterial->Visible) { // CargoMinisterial ?>
	<?php if ($print_transferencia->SortUrl($print_transferencia->CargoMinisterial) == "") { ?>
		<th data-name="CargoMinisterial"><div id="elh_print_transferencia_CargoMinisterial" class="print_transferencia_CargoMinisterial"><div class="ewTableHeaderCaption" style="width: 250px;"><script id="tpc_print_transferencia_CargoMinisterial" class="print_transferencialist" type="text/html"><span><?php echo $print_transferencia->CargoMinisterial->FldCaption() ?></span></script></div></div></th>
	<?php } else { ?>
		<th data-name="CargoMinisterial"><script id="tpc_print_transferencia_CargoMinisterial" class="print_transferencialist" type="text/html"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $print_transferencia->SortUrl($print_transferencia->CargoMinisterial) ?>',2);"><div id="elh_print_transferencia_CargoMinisterial" class="print_transferencia_CargoMinisterial">
			<div class="ewTableHeaderBtn" style="width: 250px;"><span class="ewTableHeaderCaption"><?php echo $print_transferencia->CargoMinisterial->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($print_transferencia->CargoMinisterial->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($print_transferencia->CargoMinisterial->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></script></th>
	<?php } ?>
<?php } ?>		
<?php if ($print_transferencia->Da_Igreja->Visible) { // Da_Igreja ?>
	<?php if ($print_transferencia->SortUrl($print_transferencia->Da_Igreja) == "") { ?>
		<th data-name="Da_Igreja"><div id="elh_print_transferencia_Da_Igreja" class="print_transferencia_Da_Igreja"><div class="ewTableHeaderCaption"><script id="tpc_print_transferencia_Da_Igreja" class="print_transferencialist" type="text/html"><span><?php echo $print_transferencia->Da_Igreja->FldCaption() ?></span></script></div></div></th>
	<?php } else { ?>
		<th data-name="Da_Igreja"><script id="tpc_print_transferencia_Da_Igreja" class="print_transferencialist" type="text/html"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $print_transferencia->SortUrl($print_transferencia->Da_Igreja) ?>',2);"><div id="elh_print_transferencia_Da_Igreja" class="print_transferencia_Da_Igreja">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $print_transferencia->Da_Igreja->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($print_transferencia->Da_Igreja->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($print_transferencia->Da_Igreja->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></script></th>
	<?php } ?>
<?php } ?>		
	</tr>
</thead>
<tbody>
<?php
if ($print_transferencia->ExportAll && $print_transferencia->Export <> "") {
	$print_transferencia_list->StopRec = $print_transferencia_list->TotalRecs;
} else {

	// Set the last record to display
	if ($print_transferencia_list->TotalRecs > $print_transferencia_list->StartRec + $print_transferencia_list->DisplayRecs - 1)
		$print_transferencia_list->StopRec = $print_transferencia_list->StartRec + $print_transferencia_list->DisplayRecs - 1;
	else
		$print_transferencia_list->StopRec = $print_transferencia_list->TotalRecs;
}
$print_transferencia_list->RecCnt = $print_transferencia_list->StartRec - 1;
if ($print_transferencia_list->Recordset && !$print_transferencia_list->Recordset->EOF) {
	$print_transferencia_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $print_transferencia_list->StartRec > 1)
		$print_transferencia_list->Recordset->Move($print_transferencia_list->StartRec - 1);
} elseif (!$print_transferencia->AllowAddDeleteRow && $print_transferencia_list->StopRec == 0) {
	$print_transferencia_list->StopRec = $print_transferencia->GridAddRowCount;
}

// Initialize aggregate
$print_transferencia->RowType = EW_ROWTYPE_AGGREGATEINIT;
$print_transferencia->ResetAttrs();
$print_transferencia_list->RenderRow();
while ($print_transferencia_list->RecCnt < $print_transferencia_list->StopRec) {
	$print_transferencia_list->RecCnt++;
	if (intval($print_transferencia_list->RecCnt) >= intval($print_transferencia_list->StartRec)) {
		$print_transferencia_list->RowCnt++;

		// Set up key count
		$print_transferencia_list->KeyCount = $print_transferencia_list->RowIndex;

		// Init row class and style
		$print_transferencia->ResetAttrs();
		$print_transferencia->CssClass = "";
		if ($print_transferencia->CurrentAction == "gridadd") {
		} else {
			$print_transferencia_list->LoadRowValues($print_transferencia_list->Recordset); // Load row values
		}
		$print_transferencia->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$print_transferencia->RowAttrs = array_merge($print_transferencia->RowAttrs, array('data-rowindex'=>$print_transferencia_list->RowCnt, 'id'=>'r' . $print_transferencia_list->RowCnt . '_print_transferencia', 'data-rowtype'=>$print_transferencia->RowType));

		// Render row
		$print_transferencia_list->RenderRow();

		// Render list options
		$print_transferencia_list->RenderListOptions();

		// Save row and cell attributes
		$print_transferencia_list->Attrs[$print_transferencia_list->RowCnt] = array("row_attrs" => $print_transferencia->RowAttributes(), "cell_attrs" => array());
		foreach ($print_transferencia_list->fields as $fld)
			$print_transferencia_list->Attrs[$print_transferencia_list->RowCnt]["cell_attrs"][substr($fld->FldVar, 2)] = $fld->CellAttributes();
?>
	<tr<?php echo $print_transferencia->RowAttributes() ?>>
<?php

// Render list options (body, left)
$print_transferencia_list->ListOptions->Render("body", "", $print_transferencia_list->RowCnt, "block", $print_transferencia->TableVar, "print_transferencialist");
?>
	<?php if ($print_transferencia->Id_membro->Visible) { // Id_membro ?>
		<td data-name="Id_membro"<?php echo $print_transferencia->Id_membro->CellAttributes() ?>>
<script id="tpx<?php echo $print_transferencia_list->RowCnt ?>_print_transferencia_Id_membro" class="print_transferencialist" type="text/html">
<span<?php echo $print_transferencia->Id_membro->ViewAttributes() ?>>
<?php echo $print_transferencia->Id_membro->ListViewValue() ?></span>
</script>
<a id="<?php echo $print_transferencia_list->PageObjName . "_row_" . $print_transferencia_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($print_transferencia->Nome->Visible) { // Nome ?>
		<td data-name="Nome"<?php echo $print_transferencia->Nome->CellAttributes() ?>>
<script id="tpx<?php echo $print_transferencia_list->RowCnt ?>_print_transferencia_Nome" class="print_transferencialist" type="text/html">
<span<?php echo $print_transferencia->Nome->ViewAttributes() ?>>
<?php echo $print_transferencia->Nome->ListViewValue() ?></span>
</script>
</td>
	<?php } ?>
	<?php if ($print_transferencia->Sexo->Visible) { // Sexo ?>
		<td data-name="Sexo"<?php echo $print_transferencia->Sexo->CellAttributes() ?>>
<script id="tpx<?php echo $print_transferencia_list->RowCnt ?>_print_transferencia_Sexo" class="print_transferencialist" type="text/html">
<span<?php echo $print_transferencia->Sexo->ViewAttributes() ?>>
<?php echo $print_transferencia->Sexo->ListViewValue() ?></span>
</script>
</td>
	<?php } ?>
	<?php if ($print_transferencia->Nacionalidade->Visible) { // Nacionalidade ?>
		<td data-name="Nacionalidade"<?php echo $print_transferencia->Nacionalidade->CellAttributes() ?>>
<script id="tpx<?php echo $print_transferencia_list->RowCnt ?>_print_transferencia_Nacionalidade" class="print_transferencialist" type="text/html">
<span<?php echo $print_transferencia->Nacionalidade->ViewAttributes() ?>>
<?php echo $print_transferencia->Nacionalidade->ListViewValue() ?></span>
</script>
</td>
	<?php } ?>
	<?php if ($print_transferencia->EstadoCivil->Visible) { // EstadoCivil ?>
		<td data-name="EstadoCivil"<?php echo $print_transferencia->EstadoCivil->CellAttributes() ?>>
<script id="tpx<?php echo $print_transferencia_list->RowCnt ?>_print_transferencia_EstadoCivil" class="print_transferencialist" type="text/html">
<span<?php echo $print_transferencia->EstadoCivil->ViewAttributes() ?>>
<?php echo $print_transferencia->EstadoCivil->ListViewValue() ?></span>
</script>
</td>
	<?php } ?>
	<?php if ($print_transferencia->CPF->Visible) { // CPF ?>
		<td data-name="CPF"<?php echo $print_transferencia->CPF->CellAttributes() ?>>
<script id="tpx<?php echo $print_transferencia_list->RowCnt ?>_print_transferencia_CPF" class="print_transferencialist" type="text/html">
<span<?php echo $print_transferencia->CPF->ViewAttributes() ?>>
<?php echo $print_transferencia->CPF->ListViewValue() ?></span>
</script>
</td>
	<?php } ?>
	<?php if ($print_transferencia->RG->Visible) { // RG ?>
		<td data-name="RG"<?php echo $print_transferencia->RG->CellAttributes() ?>>
<script id="tpx<?php echo $print_transferencia_list->RowCnt ?>_print_transferencia_RG" class="print_transferencialist" type="text/html">
<span<?php echo $print_transferencia->RG->ViewAttributes() ?>>
<?php echo $print_transferencia->RG->ListViewValue() ?></span>
</script>
</td>
	<?php } ?>
	<?php if ($print_transferencia->Matricula->Visible) { // Matricula ?>
		<td data-name="Matricula"<?php echo $print_transferencia->Matricula->CellAttributes() ?>>
<script id="tpx<?php echo $print_transferencia_list->RowCnt ?>_print_transferencia_Matricula" class="print_transferencialist" type="text/html">
<span<?php echo $print_transferencia->Matricula->ViewAttributes() ?>>
<?php echo $print_transferencia->Matricula->ListViewValue() ?></span>
</script>
</td>
	<?php } ?>
	<?php if ($print_transferencia->Admissao->Visible) { // Admissao ?>
		<td data-name="Admissao"<?php echo $print_transferencia->Admissao->CellAttributes() ?>>
<script id="tpx<?php echo $print_transferencia_list->RowCnt ?>_print_transferencia_Admissao" class="print_transferencialist" type="text/html">
<span<?php echo $print_transferencia->Admissao->ViewAttributes() ?>>
<?php echo $print_transferencia->Admissao->ListViewValue() ?></span>
</script>
</td>
	<?php } ?>
	<?php if ($print_transferencia->CargoMinisterial->Visible) { // CargoMinisterial ?>
		<td data-name="CargoMinisterial"<?php echo $print_transferencia->CargoMinisterial->CellAttributes() ?>>
<script id="tpx<?php echo $print_transferencia_list->RowCnt ?>_print_transferencia_CargoMinisterial" class="print_transferencialist" type="text/html">
<span<?php echo $print_transferencia->CargoMinisterial->ViewAttributes() ?>>
<?php echo $print_transferencia->CargoMinisterial->ListViewValue() ?></span>
</script>
</td>
	<?php } ?>
	<?php if ($print_transferencia->Da_Igreja->Visible) { // Da_Igreja ?>
		<td data-name="Da_Igreja"<?php echo $print_transferencia->Da_Igreja->CellAttributes() ?>>
<script id="tpx<?php echo $print_transferencia_list->RowCnt ?>_print_transferencia_Da_Igreja" class="print_transferencialist" type="text/html">
<span<?php echo $print_transferencia->Da_Igreja->ViewAttributes() ?>>
<?php echo $print_transferencia->Da_Igreja->ListViewValue() ?></span>
</script>
</td>
	<?php } ?>
	</tr>
<?php
	}
	if ($print_transferencia->CurrentAction <> "gridadd")
		$print_transferencia_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($print_transferencia->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<div id="tpd_print_transferencialist" class="ewCustomTemplate"></div>
<script id="tpm_print_transferencialist" type="text/html">
<div id="ct_print_transferencia_list"><?php if ($print_transferencia_list->RowCnt > 0) { ?>
<?php for ($i = $print_transferencia_list->StartRowCnt; $i <= $print_transferencia_list->RowCnt; $i++) { ?>
<?php
$DEFAULT_TIME_ZONE = "America/Sao_Paulo";
date_default_timezone_set($DEFAULT_TIME_ZONE);
$mes =  array(1=>'Janiero','Fevereiro','Mar�o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');
$im = (int) date('m');
$idvalue = CurrentTable()->Id_membro->CurrentValue;
$sSql		= "SELECT Corpo_TR FROM cartas where Id = 1";
$rs1 		= $conn->Execute($sSql);
$conteudo 	= $rs1->fields[0];
$dia1 = date("d");
$mes1 = $mes[$im];
$ano1 = date("Y");
$nome 			  = CurrentTable()->Nome->CurrentValue;
$sexo 			  = CurrentTable()->Sexo->CurrentValue;
$estadocivil 	  = CurrentTable()->EstadoCivil->CurrentValue;
$nacionalidade 	  = CurrentTable()->Nacionalidade->CurrentValue;
$cpf 			  = CurrentTable()->CPF->CurrentValue;
$cargoministerial = CurrentTable()->CargoMinisterial->CurrentValue;
$daigreja 		  = CurrentTable()->Da_Igreja->CurrentValue;
$rg				  = CurrentTable()->RG->CurrentValue;
$admissao		  = ew_FormatDateTime(CurrentTable()->Admissao->CurrentValue,7);
$matricula		  = CurrentTable()->Matricula->CurrentValue;
$msg = str_replace("[#nome]",$nome,$conteudo);
$msg = str_replace("[#sexo]",$sexo,$msg);
$msg = str_replace("[#estadocivil]",$estadocivil,$msg);
$msg = str_replace("[#nacionalidade]",$nacionalidade,$msg);
$msg = str_replace("[#cpf]",$cpf,$msg);
$msg = str_replace("[#cargoministerial]",$cargoministerial,$msg);
$msg = str_replace("[#daigreja]",$daigreja,$msg);
$msg = str_replace("[#rg]",$rg,$msg);
$msg = str_replace("[#admissao]",$admissao,$msg);
$msg = str_replace("[#matricula]",$matricula,$msg);
$msg = str_replace("[#dia]",$dia1,$msg);
$msg = str_replace("[#mes]",$mes1,$msg);
$msg = str_replace("[#ano]",$ano1,$msg);
echo $msg;
?>
<?php } ?>
<?php } ?>
</div>
</script>
</div>
</form>
<?php

// Close recordset
if ($print_transferencia_list->Recordset)
	$print_transferencia_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($print_transferencia_list->TotalRecs == 0 && $print_transferencia->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($print_transferencia_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
ew_ApplyTemplate("tpd_print_transferencialist", "tpm_print_transferencialist", "print_transferencialist", "<?php echo $print_transferencia->CustomExport ?>");
jQuery("script.print_transferencialist_js").each(function(){ew_AddScript(this.text);});
</script>
<?php if ($print_transferencia->Export == "") { ?>
<script type="text/javascript">
fprint_transferencialistsrch.Init();
fprint_transferencialist.Init();
$(document).ready(function($) {	$("#ajuda").click(function() {	bootbox.dialog({title: "Informações de Ajuda", message: '<?php echo str_replace("\r\n"," ",trim($help)) ?>', buttons: { success: { label: "Fechar" }}}); });});
</script>
<?php } ?>
<?php
$print_transferencia_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($print_transferencia->Export == "") { ?>
<script type="text/javascript">
$(document).ready(function($) { $('.ewGrid').removeClass();
$('.ewGridUpperPanel').remove()
})
</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$print_transferencia_list->Page_Terminate();
?>
