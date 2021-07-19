<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "smtpinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$smtp_list = NULL; // Initialize page object first

class csmtp_list extends csmtp {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{2B7992FC-5911-46A7-9310-01F4D4225C49}";

	// Table name
	var $TableName = 'smtp';

	// Page object name
	var $PageObjName = 'smtp_list';

	// Grid form hidden field names
	var $FormName = 'fsmtplist';
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

		// Table object (smtp)
		if (!isset($GLOBALS["smtp"]) || get_class($GLOBALS["smtp"]) == "csmtp") {
			$GLOBALS["smtp"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["smtp"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "smtpadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "smtpdelete.php";
		$this->MultiUpdateUrl = "smtpupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'smtp', TRUE);

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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

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
		global $EW_EXPORT, $smtp;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($smtp);
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

			// Set up sorting order
			$this->SetUpSortOrder();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

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
			$this->Id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->Id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->SMTP, $bCtrl); // SMTP
			$this->UpdateSort($this->SMTP_Porta, $bCtrl); // SMTP_Porta
			$this->UpdateSort($this->SMTP_Usuario, $bCtrl); // SMTP_Usuario
			$this->UpdateSort($this->SMTP_Senha, $bCtrl); // SMTP_Senha
			$this->UpdateSort($this->Email_de_Envio, $bCtrl); // Email_de_Envio
			$this->UpdateSort($this->Email_de_Recebimento, $bCtrl); // Email_de_Recebimento
			$this->UpdateSort($this->Seguranca, $bCtrl); // Seguranca
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

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->SMTP->setSort("");
				$this->SMTP_Porta->setSort("");
				$this->SMTP_Usuario->setSort("");
				$this->SMTP_Senha->setSort("");
				$this->Email_de_Envio->setSort("");
				$this->Email_de_Recebimento->setSort("");
				$this->Seguranca->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fsmtplist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->SMTP->setDbValue($rs->fields('SMTP'));
		$this->SMTP_Porta->setDbValue($rs->fields('SMTP_Porta'));
		$this->SMTP_Usuario->setDbValue($rs->fields('SMTP_Usuario'));
		$this->SMTP_Senha->setDbValue($rs->fields('SMTP_Senha'));
		$this->Email_de_Envio->setDbValue($rs->fields('Email_de_Envio'));
		$this->Email_de_Recebimento->setDbValue($rs->fields('Email_de_Recebimento'));
		$this->Seguranca->setDbValue($rs->fields('Seguranca'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id->DbValue = $row['Id'];
		$this->SMTP->DbValue = $row['SMTP'];
		$this->SMTP_Porta->DbValue = $row['SMTP_Porta'];
		$this->SMTP_Usuario->DbValue = $row['SMTP_Usuario'];
		$this->SMTP_Senha->DbValue = $row['SMTP_Senha'];
		$this->Email_de_Envio->DbValue = $row['Email_de_Envio'];
		$this->Email_de_Recebimento->DbValue = $row['Email_de_Recebimento'];
		$this->Seguranca->DbValue = $row['Seguranca'];
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

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// Id

		$this->Id->CellCssStyle = "white-space: nowrap;";

		// SMTP
		// SMTP_Porta
		// SMTP_Usuario
		// SMTP_Senha
		// Email_de_Envio
		// Email_de_Recebimento
		// Seguranca

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// SMTP
			$this->SMTP->ViewValue = $this->SMTP->CurrentValue;
			$this->SMTP->ViewCustomAttributes = "";

			// SMTP_Porta
			$this->SMTP_Porta->ViewValue = $this->SMTP_Porta->CurrentValue;
			$this->SMTP_Porta->ViewCustomAttributes = "";

			// SMTP_Usuario
			$this->SMTP_Usuario->ViewValue = $this->SMTP_Usuario->CurrentValue;
			$this->SMTP_Usuario->ViewCustomAttributes = "";

			// SMTP_Senha
			$this->SMTP_Senha->ViewValue = "********";
			$this->SMTP_Senha->ViewCustomAttributes = "";

			// Email_de_Envio
			$this->Email_de_Envio->ViewValue = $this->Email_de_Envio->CurrentValue;
			$this->Email_de_Envio->ViewCustomAttributes = "";

			// Email_de_Recebimento
			$this->Email_de_Recebimento->ViewValue = $this->Email_de_Recebimento->CurrentValue;
			$this->Email_de_Recebimento->ViewCustomAttributes = "";

			// Seguranca
			if (strval($this->Seguranca->CurrentValue) <> "") {
				switch ($this->Seguranca->CurrentValue) {
					case $this->Seguranca->FldTagValue(1):
						$this->Seguranca->ViewValue = $this->Seguranca->FldTagCaption(1) <> "" ? $this->Seguranca->FldTagCaption(1) : $this->Seguranca->CurrentValue;
						break;
					case $this->Seguranca->FldTagValue(2):
						$this->Seguranca->ViewValue = $this->Seguranca->FldTagCaption(2) <> "" ? $this->Seguranca->FldTagCaption(2) : $this->Seguranca->CurrentValue;
						break;
					default:
						$this->Seguranca->ViewValue = $this->Seguranca->CurrentValue;
				}
			} else {
				$this->Seguranca->ViewValue = NULL;
			}
			$this->Seguranca->ViewCustomAttributes = "";

			// SMTP
			$this->SMTP->LinkCustomAttributes = "";
			$this->SMTP->HrefValue = "";
			$this->SMTP->TooltipValue = "";

			// SMTP_Porta
			$this->SMTP_Porta->LinkCustomAttributes = "";
			$this->SMTP_Porta->HrefValue = "";
			$this->SMTP_Porta->TooltipValue = "";

			// SMTP_Usuario
			$this->SMTP_Usuario->LinkCustomAttributes = "";
			$this->SMTP_Usuario->HrefValue = "";
			$this->SMTP_Usuario->TooltipValue = "";

			// SMTP_Senha
			$this->SMTP_Senha->LinkCustomAttributes = "";
			$this->SMTP_Senha->HrefValue = "";
			$this->SMTP_Senha->TooltipValue = "";

			// Email_de_Envio
			$this->Email_de_Envio->LinkCustomAttributes = "";
			$this->Email_de_Envio->HrefValue = "";
			$this->Email_de_Envio->TooltipValue = "";

			// Email_de_Recebimento
			$this->Email_de_Recebimento->LinkCustomAttributes = "";
			$this->Email_de_Recebimento->HrefValue = "";
			$this->Email_de_Recebimento->TooltipValue = "";

			// Seguranca
			$this->Seguranca->LinkCustomAttributes = "";
			$this->Seguranca->HrefValue = "";
			$this->Seguranca->TooltipValue = "";
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
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'smtp';
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

	function Page_Redirecting(&$url) {
		$url = "smtpedit.php?Id=1";
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
if (!isset($smtp_list)) $smtp_list = new csmtp_list();

// Page init
$smtp_list->Page_Init();

// Page main
$smtp_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$smtp_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var smtp_list = new ew_Page("smtp_list");
smtp_list.PageID = "list"; // Page ID
var EW_PAGE_ID = smtp_list.PageID; // For backward compatibility

// Form object
var fsmtplist = new ew_Form("fsmtplist");
fsmtplist.FormKeyCountName = '<?php echo $smtp_list->FormKeyCountName ?>';

// Form_CustomValidate event
fsmtplist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsmtplist.ValidateRequired = true;
<?php } else { ?>
fsmtplist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($smtp_list->TotalRecs > 0 && $smtp_list->ExportOptions->Visible()) { ?>
<?php $smtp_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="ewSearchOption ewListOptionSeparator" style="white-space: nowrap;" data-name="button"><div class="btn-group ewButtonGroup">
<?php $help = ew_ExecuteScalar("Select txt from ajuda where pg = '".ew_CurrentPage()."'") ; 
if (strlen($help)>0){ ?>
	<button class="btn btn-default" type="button" title="" data-original-title="Ajuda desta p&aacute;gina" id="ajuda"><span data-phrase="SearchBtn" class="fa fa-question ewIcon" data-caption="Ajuda"></span></button>		
<?php } ?>	
</div></div>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($smtp_list->TotalRecs <= 0)
			$smtp_list->TotalRecs = $smtp->SelectRecordCount();
	} else {
		if (!$smtp_list->Recordset && ($smtp_list->Recordset = $smtp_list->LoadRecordset()))
			$smtp_list->TotalRecs = $smtp_list->Recordset->RecordCount();
	}
	$smtp_list->StartRec = 1;
	if ($smtp_list->DisplayRecs <= 0 || ($smtp->Export <> "" && $smtp->ExportAll)) // Display all records
		$smtp_list->DisplayRecs = $smtp_list->TotalRecs;
	if (!($smtp->Export <> "" && $smtp->ExportAll))
		$smtp_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$smtp_list->Recordset = $smtp_list->LoadRecordset($smtp_list->StartRec-1, $smtp_list->DisplayRecs);

	// Set no record found message
	if ($smtp->CurrentAction == "" && $smtp_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$smtp_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($smtp_list->SearchWhere == "0=101")
			$smtp_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$smtp_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$smtp_list->RenderOtherOptions();
?>
<?php $smtp_list->ShowPageHeader(); ?>
<?php
$smtp_list->ShowMessage();
?>
<?php if ($smtp_list->TotalRecs > 0 || $smtp->CurrentAction <> "") { ?>
<div class="ewGrid">
<div class="ewGridUpperPanel">
<?php if ($smtp->CurrentAction <> "gridadd" && $smtp->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($smtp_list->Pager)) $smtp_list->Pager = new cPrevNextPager($smtp_list->StartRec, $smtp_list->DisplayRecs, $smtp_list->TotalRecs) ?>
<?php if ($smtp_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($smtp_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $smtp_list->PageUrl() ?>start=<?php echo $smtp_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($smtp_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $smtp_list->PageUrl() ?>start=<?php echo $smtp_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $smtp_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($smtp_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $smtp_list->PageUrl() ?>start=<?php echo $smtp_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($smtp_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $smtp_list->PageUrl() ?>start=<?php echo $smtp_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $smtp_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $smtp_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $smtp_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $smtp_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($smtp_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="fsmtplist" id="fsmtplist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($smtp_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $smtp_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="smtp">
<div id="gmp_smtp" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($smtp_list->TotalRecs > 0) { ?>
<table id="tbl_smtplist" class="table ewTable">
<?php echo $smtp->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$smtp_list->RenderListOptions();

// Render list options (header, left)
$smtp_list->ListOptions->Render("header", "left");
?>
<?php if ($smtp->SMTP->Visible) { // SMTP ?>
	<?php if ($smtp->SortUrl($smtp->SMTP) == "") { ?>
		<th data-name="SMTP"><div id="elh_smtp_SMTP" class="smtp_SMTP"><div class="ewTableHeaderCaption"><?php echo $smtp->SMTP->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="SMTP"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $smtp->SortUrl($smtp->SMTP) ?>',2);"><div id="elh_smtp_SMTP" class="smtp_SMTP">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $smtp->SMTP->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($smtp->SMTP->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($smtp->SMTP->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($smtp->SMTP_Porta->Visible) { // SMTP_Porta ?>
	<?php if ($smtp->SortUrl($smtp->SMTP_Porta) == "") { ?>
		<th data-name="SMTP_Porta"><div id="elh_smtp_SMTP_Porta" class="smtp_SMTP_Porta"><div class="ewTableHeaderCaption"><?php echo $smtp->SMTP_Porta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="SMTP_Porta"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $smtp->SortUrl($smtp->SMTP_Porta) ?>',2);"><div id="elh_smtp_SMTP_Porta" class="smtp_SMTP_Porta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $smtp->SMTP_Porta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($smtp->SMTP_Porta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($smtp->SMTP_Porta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($smtp->SMTP_Usuario->Visible) { // SMTP_Usuario ?>
	<?php if ($smtp->SortUrl($smtp->SMTP_Usuario) == "") { ?>
		<th data-name="SMTP_Usuario"><div id="elh_smtp_SMTP_Usuario" class="smtp_SMTP_Usuario"><div class="ewTableHeaderCaption"><?php echo $smtp->SMTP_Usuario->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="SMTP_Usuario"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $smtp->SortUrl($smtp->SMTP_Usuario) ?>',2);"><div id="elh_smtp_SMTP_Usuario" class="smtp_SMTP_Usuario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $smtp->SMTP_Usuario->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($smtp->SMTP_Usuario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($smtp->SMTP_Usuario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($smtp->SMTP_Senha->Visible) { // SMTP_Senha ?>
	<?php if ($smtp->SortUrl($smtp->SMTP_Senha) == "") { ?>
		<th data-name="SMTP_Senha"><div id="elh_smtp_SMTP_Senha" class="smtp_SMTP_Senha"><div class="ewTableHeaderCaption"><?php echo $smtp->SMTP_Senha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="SMTP_Senha"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $smtp->SortUrl($smtp->SMTP_Senha) ?>',2);"><div id="elh_smtp_SMTP_Senha" class="smtp_SMTP_Senha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $smtp->SMTP_Senha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($smtp->SMTP_Senha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($smtp->SMTP_Senha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($smtp->Email_de_Envio->Visible) { // Email_de_Envio ?>
	<?php if ($smtp->SortUrl($smtp->Email_de_Envio) == "") { ?>
		<th data-name="Email_de_Envio"><div id="elh_smtp_Email_de_Envio" class="smtp_Email_de_Envio"><div class="ewTableHeaderCaption"><?php echo $smtp->Email_de_Envio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Email_de_Envio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $smtp->SortUrl($smtp->Email_de_Envio) ?>',2);"><div id="elh_smtp_Email_de_Envio" class="smtp_Email_de_Envio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $smtp->Email_de_Envio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($smtp->Email_de_Envio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($smtp->Email_de_Envio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($smtp->Email_de_Recebimento->Visible) { // Email_de_Recebimento ?>
	<?php if ($smtp->SortUrl($smtp->Email_de_Recebimento) == "") { ?>
		<th data-name="Email_de_Recebimento"><div id="elh_smtp_Email_de_Recebimento" class="smtp_Email_de_Recebimento"><div class="ewTableHeaderCaption"><?php echo $smtp->Email_de_Recebimento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Email_de_Recebimento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $smtp->SortUrl($smtp->Email_de_Recebimento) ?>',2);"><div id="elh_smtp_Email_de_Recebimento" class="smtp_Email_de_Recebimento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $smtp->Email_de_Recebimento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($smtp->Email_de_Recebimento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($smtp->Email_de_Recebimento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($smtp->Seguranca->Visible) { // Seguranca ?>
	<?php if ($smtp->SortUrl($smtp->Seguranca) == "") { ?>
		<th data-name="Seguranca"><div id="elh_smtp_Seguranca" class="smtp_Seguranca"><div class="ewTableHeaderCaption"><?php echo $smtp->Seguranca->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Seguranca"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $smtp->SortUrl($smtp->Seguranca) ?>',2);"><div id="elh_smtp_Seguranca" class="smtp_Seguranca">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $smtp->Seguranca->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($smtp->Seguranca->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($smtp->Seguranca->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$smtp_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($smtp->ExportAll && $smtp->Export <> "") {
	$smtp_list->StopRec = $smtp_list->TotalRecs;
} else {

	// Set the last record to display
	if ($smtp_list->TotalRecs > $smtp_list->StartRec + $smtp_list->DisplayRecs - 1)
		$smtp_list->StopRec = $smtp_list->StartRec + $smtp_list->DisplayRecs - 1;
	else
		$smtp_list->StopRec = $smtp_list->TotalRecs;
}
$smtp_list->RecCnt = $smtp_list->StartRec - 1;
if ($smtp_list->Recordset && !$smtp_list->Recordset->EOF) {
	$smtp_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $smtp_list->StartRec > 1)
		$smtp_list->Recordset->Move($smtp_list->StartRec - 1);
} elseif (!$smtp->AllowAddDeleteRow && $smtp_list->StopRec == 0) {
	$smtp_list->StopRec = $smtp->GridAddRowCount;
}

// Initialize aggregate
$smtp->RowType = EW_ROWTYPE_AGGREGATEINIT;
$smtp->ResetAttrs();
$smtp_list->RenderRow();
while ($smtp_list->RecCnt < $smtp_list->StopRec) {
	$smtp_list->RecCnt++;
	if (intval($smtp_list->RecCnt) >= intval($smtp_list->StartRec)) {
		$smtp_list->RowCnt++;

		// Set up key count
		$smtp_list->KeyCount = $smtp_list->RowIndex;

		// Init row class and style
		$smtp->ResetAttrs();
		$smtp->CssClass = "";
		if ($smtp->CurrentAction == "gridadd") {
		} else {
			$smtp_list->LoadRowValues($smtp_list->Recordset); // Load row values
		}
		$smtp->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$smtp->RowAttrs = array_merge($smtp->RowAttrs, array('data-rowindex'=>$smtp_list->RowCnt, 'id'=>'r' . $smtp_list->RowCnt . '_smtp', 'data-rowtype'=>$smtp->RowType));

		// Render row
		$smtp_list->RenderRow();

		// Render list options
		$smtp_list->RenderListOptions();
?>
	<tr<?php echo $smtp->RowAttributes() ?>>
<?php

// Render list options (body, left)
$smtp_list->ListOptions->Render("body", "left", $smtp_list->RowCnt);
?>
	<?php if ($smtp->SMTP->Visible) { // SMTP ?>
		<td data-name="SMTP"<?php echo $smtp->SMTP->CellAttributes() ?>>
<span<?php echo $smtp->SMTP->ViewAttributes() ?>>
<?php echo $smtp->SMTP->ListViewValue() ?></span>
<a id="<?php echo $smtp_list->PageObjName . "_row_" . $smtp_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($smtp->SMTP_Porta->Visible) { // SMTP_Porta ?>
		<td data-name="SMTP_Porta"<?php echo $smtp->SMTP_Porta->CellAttributes() ?>>
<span<?php echo $smtp->SMTP_Porta->ViewAttributes() ?>>
<?php echo $smtp->SMTP_Porta->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($smtp->SMTP_Usuario->Visible) { // SMTP_Usuario ?>
		<td data-name="SMTP_Usuario"<?php echo $smtp->SMTP_Usuario->CellAttributes() ?>>
<span<?php echo $smtp->SMTP_Usuario->ViewAttributes() ?>>
<?php echo $smtp->SMTP_Usuario->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($smtp->SMTP_Senha->Visible) { // SMTP_Senha ?>
		<td data-name="SMTP_Senha"<?php echo $smtp->SMTP_Senha->CellAttributes() ?>>
<span<?php echo $smtp->SMTP_Senha->ViewAttributes() ?>>
<?php echo $smtp->SMTP_Senha->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($smtp->Email_de_Envio->Visible) { // Email_de_Envio ?>
		<td data-name="Email_de_Envio"<?php echo $smtp->Email_de_Envio->CellAttributes() ?>>
<span<?php echo $smtp->Email_de_Envio->ViewAttributes() ?>>
<?php echo $smtp->Email_de_Envio->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($smtp->Email_de_Recebimento->Visible) { // Email_de_Recebimento ?>
		<td data-name="Email_de_Recebimento"<?php echo $smtp->Email_de_Recebimento->CellAttributes() ?>>
<span<?php echo $smtp->Email_de_Recebimento->ViewAttributes() ?>>
<?php echo $smtp->Email_de_Recebimento->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($smtp->Seguranca->Visible) { // Seguranca ?>
		<td data-name="Seguranca"<?php echo $smtp->Seguranca->CellAttributes() ?>>
<span<?php echo $smtp->Seguranca->ViewAttributes() ?>>
<?php echo $smtp->Seguranca->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$smtp_list->ListOptions->Render("body", "right", $smtp_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($smtp->CurrentAction <> "gridadd")
		$smtp_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($smtp->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($smtp_list->Recordset)
	$smtp_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($smtp_list->TotalRecs == 0 && $smtp->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($smtp_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fsmtplist.Init();
$(document).ready(function($) {	$("#ajuda").click(function() {	bootbox.dialog({title: "Informações de Ajuda", message: '<?php echo str_replace("\r\n"," ",trim($help)) ?>', buttons: { success: { label: "Fechar" }}}); });});
</script>
<?php
$smtp_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">
$(document).ready(function($) {
	$(".ewGridUpperPanel").remove();
});
</script>
<?php include_once "footer.php" ?>
<?php
$smtp_list->Page_Terminate();
?>
