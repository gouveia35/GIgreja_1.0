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
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$membro_search = NULL; // Initialize page object first

class cmembro_search extends cmembro {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{2B7992FC-5911-46A7-9310-01F4D4225C49}";

	// Table name
	var $TableName = 'membro';

	// Page object name
	var $PageObjName = 'membro_search';

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

		// Table object (membro)
		if (!isset($GLOBALS["membro"]) || get_class($GLOBALS["membro"]) == "cmembro") {
			$GLOBALS["membro"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["membro"];
		}

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
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'membro', TRUE);

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
		if (!$Security->CanSearch()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("membrolist.php"));
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Create form object
		$objForm = new cFormObj();
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
	var $IsModal = FALSE;
	var $SearchLabelClass = "col-sm-3 control-label ewLabel";
	var $SearchRightColumnClass = "col-sm-9";

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsSearchError;
		global $gbSkipHeaderFooter;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		if ($this->IsPageRequest()) { // Validate request

			// Get action
			$this->CurrentAction = $objForm->GetValue("a_search");
			switch ($this->CurrentAction) {
				case "S": // Get search criteria

					// Build search string for advanced search, remove blank field
					$this->LoadSearchValues(); // Get search values
					if ($this->ValidateSearch()) {
						$sSrchStr = $this->BuildAdvancedSearch();
					} else {
						$sSrchStr = "";
						$this->setFailureMessage($gsSearchError);
					}
					if ($sSrchStr <> "") {
						$sSrchStr = $this->UrlParm($sSrchStr);
						$sSrchStr = "membrolist.php" . "?" . $sSrchStr;
						if ($this->IsModal) {
							$row = array();
							$row["url"] = $sSrchStr;
							echo ew_ArrayToJson(array($row));
							$this->Page_Terminate();
							exit();
						} else {
							$this->Page_Terminate($sSrchStr); // Go to list page
						}
					}
			}
		}

		// Restore search settings from Session
		if ($gsSearchError == "")
			$this->LoadAdvancedSearch();

		// Render row for search
		$this->RowType = EW_ROWTYPE_SEARCH;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Build advanced search
	function BuildAdvancedSearch() {
		$sSrchUrl = "";
		$this->BuildSearchUrl($sSrchUrl, $this->Matricula); // Matricula
		$this->BuildSearchUrl($sSrchUrl, $this->Sexo); // Sexo
		$this->BuildSearchUrl($sSrchUrl, $this->EstadoCivil); // EstadoCivil
		$this->BuildSearchUrl($sSrchUrl, $this->RG); // RG
		$this->BuildSearchUrl($sSrchUrl, $this->Profissao); // Profissao
		$this->BuildSearchUrl($sSrchUrl, $this->_Email); // Email
		$this->BuildSearchUrl($sSrchUrl, $this->Endereco); // Endereco
		$this->BuildSearchUrl($sSrchUrl, $this->Bairro); // Bairro
		$this->BuildSearchUrl($sSrchUrl, $this->Cidade); // Cidade
		$this->BuildSearchUrl($sSrchUrl, $this->UF); // UF
		$this->BuildSearchUrl($sSrchUrl, $this->GrauEscolaridade); // GrauEscolaridade
		$this->BuildSearchUrl($sSrchUrl, $this->Data_Casamento); // Data_Casamento
		$this->BuildSearchUrl($sSrchUrl, $this->Conjuge); // Conjuge
		$this->BuildSearchUrl($sSrchUrl, $this->Celula); // Celula
		$this->BuildSearchUrl($sSrchUrl, $this->Nome_da_Familia); // Nome_da_Familia
		$this->BuildSearchUrl($sSrchUrl, $this->Situacao); // Situacao
		$this->BuildSearchUrl($sSrchUrl, $this->Da_Igreja); // Da_Igreja
		$this->BuildSearchUrl($sSrchUrl, $this->Admissao); // Admissao
		$this->BuildSearchUrl($sSrchUrl, $this->Tipo_Admissao); // Tipo_Admissao
		$this->BuildSearchUrl($sSrchUrl, $this->Rede_Ministerial); // Rede_Ministerial
		if ($sSrchUrl <> "") $sSrchUrl .= "&";
		$sSrchUrl .= "cmd=search";
		return $sSrchUrl;
	}

	// Build search URL
	function BuildSearchUrl(&$Url, &$Fld, $OprOnly=FALSE) {
		global $objForm;
		$sWrk = "";
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $objForm->GetValue("x_$FldParm");
		$FldOpr = $objForm->GetValue("z_$FldParm");
		$FldCond = $objForm->GetValue("v_$FldParm");
		$FldVal2 = $objForm->GetValue("y_$FldParm");
		$FldOpr2 = $objForm->GetValue("w_$FldParm");
		$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		$lFldDataType = ($Fld->FldIsVirtual) ? EW_DATATYPE_STRING : $Fld->FldDataType;
		if ($FldOpr == "BETWEEN") {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal) && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			}
		} else {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal));
			if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $lFldDataType)) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			} elseif ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL" || ($FldOpr <> "" && $OprOnly && ew_IsValidOpr($FldOpr, $lFldDataType))) {
				$sWrk = "z_" . $FldParm . "=" . urlencode($FldOpr);
			}
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $lFldDataType)) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&w_" . $FldParm . "=" . urlencode($FldOpr2);
			} elseif ($FldOpr2 == "IS NULL" || $FldOpr2 == "IS NOT NULL" || ($FldOpr2 <> "" && $OprOnly && ew_IsValidOpr($FldOpr2, $lFldDataType))) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "w_" . $FldParm . "=" . urlencode($FldOpr2);
			}
		}
		if ($sWrk <> "") {
			if ($Url <> "") $Url .= "&";
			$Url .= $sWrk;
		}
	}

	function SearchValueIsNumeric($Fld, $Value) {
		if (ew_IsFloatFormat($Fld->FldType)) $Value = ew_StrToFloat($Value);
		return is_numeric($Value);
	}

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// Matricula

		$this->Matricula->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Matricula"));
		$this->Matricula->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Matricula");

		// Nome
		$this->Nome->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Nome"));
		$this->Nome->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Nome");

		// Sexo
		$this->Sexo->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Sexo"));
		$this->Sexo->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Sexo");

		// EstadoCivil
		$this->EstadoCivil->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_EstadoCivil"));
		$this->EstadoCivil->AdvancedSearch->SearchOperator = $objForm->GetValue("z_EstadoCivil");

		// CPF
		$this->CPF->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_CPF"));
		$this->CPF->AdvancedSearch->SearchOperator = $objForm->GetValue("z_CPF");

		// RG
		$this->RG->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_RG"));
		$this->RG->AdvancedSearch->SearchOperator = $objForm->GetValue("z_RG");

		// Profissao
		$this->Profissao->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Profissao"));
		$this->Profissao->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Profissao");

		// Email
		$this->_Email->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x__Email"));
		$this->_Email->AdvancedSearch->SearchOperator = $objForm->GetValue("z__Email");

		// Endereco
		$this->Endereco->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Endereco"));
		$this->Endereco->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Endereco");

		// Bairro
		$this->Bairro->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Bairro"));
		$this->Bairro->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Bairro");

		// Cidade
		$this->Cidade->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Cidade"));
		$this->Cidade->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Cidade");

		// UF
		$this->UF->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_UF"));
		$this->UF->AdvancedSearch->SearchOperator = $objForm->GetValue("z_UF");

		// GrauEscolaridade
		$this->GrauEscolaridade->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_GrauEscolaridade"));
		$this->GrauEscolaridade->AdvancedSearch->SearchOperator = $objForm->GetValue("z_GrauEscolaridade");

		// Data_Casamento
		$this->Data_Casamento->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Data_Casamento"));
		$this->Data_Casamento->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Data_Casamento");
		$this->Data_Casamento->AdvancedSearch->SearchCondition = $objForm->GetValue("v_Data_Casamento");
		$this->Data_Casamento->AdvancedSearch->SearchValue2 = ew_StripSlashes($objForm->GetValue("y_Data_Casamento"));
		$this->Data_Casamento->AdvancedSearch->SearchOperator2 = $objForm->GetValue("w_Data_Casamento");

		// Conjuge
		$this->Conjuge->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Conjuge"));
		$this->Conjuge->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Conjuge");

		// Celula
		$this->Celula->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Celula"));
		$this->Celula->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Celula");

		// Nome_da_Familia
		$this->Nome_da_Familia->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Nome_da_Familia"));
		$this->Nome_da_Familia->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Nome_da_Familia");

		// Situacao
		$this->Situacao->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Situacao"));
		$this->Situacao->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Situacao");

		// Da_Igreja
		$this->Da_Igreja->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Da_Igreja"));
		$this->Da_Igreja->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Da_Igreja");

		// CargoMinisterial
		$this->CargoMinisterial->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_CargoMinisterial"));
		$this->CargoMinisterial->AdvancedSearch->SearchOperator = $objForm->GetValue("z_CargoMinisterial");

		// Admissao
		$this->Admissao->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Admissao"));
		$this->Admissao->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Admissao");
		$this->Admissao->AdvancedSearch->SearchCondition = $objForm->GetValue("v_Admissao");
		$this->Admissao->AdvancedSearch->SearchValue2 = ew_StripSlashes($objForm->GetValue("y_Admissao"));
		$this->Admissao->AdvancedSearch->SearchOperator2 = $objForm->GetValue("w_Admissao");

		// Tipo_Admissao
		$this->Tipo_Admissao->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Tipo_Admissao"));
		$this->Tipo_Admissao->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Tipo_Admissao");

		// Funcao
		$this->Funcao->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Funcao"));
		$this->Funcao->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Funcao");

		// Rede_Ministerial
		$this->Rede_Ministerial->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Rede_Ministerial"));
		$this->Rede_Ministerial->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Rede_Ministerial");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
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

			// Matricula
			$this->Matricula->LinkCustomAttributes = "";
			$this->Matricula->HrefValue = "";
			$this->Matricula->TooltipValue = "";

			// Sexo
			$this->Sexo->LinkCustomAttributes = "";
			$this->Sexo->HrefValue = "";
			$this->Sexo->TooltipValue = "";

			// EstadoCivil
			$this->EstadoCivil->LinkCustomAttributes = "";
			$this->EstadoCivil->HrefValue = "";
			$this->EstadoCivil->TooltipValue = "";

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

			// Endereco
			$this->Endereco->LinkCustomAttributes = "";
			$this->Endereco->HrefValue = "";
			$this->Endereco->TooltipValue = "";

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

			// GrauEscolaridade
			$this->GrauEscolaridade->LinkCustomAttributes = "";
			$this->GrauEscolaridade->HrefValue = "";
			$this->GrauEscolaridade->TooltipValue = "";

			// Data_Casamento
			$this->Data_Casamento->LinkCustomAttributes = "";
			$this->Data_Casamento->HrefValue = "";
			$this->Data_Casamento->TooltipValue = "";

			// Conjuge
			$this->Conjuge->LinkCustomAttributes = "";
			$this->Conjuge->HrefValue = "";
			$this->Conjuge->TooltipValue = "";

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

			// Da_Igreja
			$this->Da_Igreja->LinkCustomAttributes = "";
			$this->Da_Igreja->HrefValue = "";
			$this->Da_Igreja->TooltipValue = "";

			// Admissao
			$this->Admissao->LinkCustomAttributes = "";
			$this->Admissao->HrefValue = "";
			$this->Admissao->TooltipValue = "";

			// Tipo_Admissao
			$this->Tipo_Admissao->LinkCustomAttributes = "";
			$this->Tipo_Admissao->HrefValue = "";
			$this->Tipo_Admissao->TooltipValue = "";

			// Rede_Ministerial
			$this->Rede_Ministerial->LinkCustomAttributes = "";
			$this->Rede_Ministerial->HrefValue = "";
			$this->Rede_Ministerial->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// Matricula
			$this->Matricula->EditAttrs["class"] = "form-control";
			$this->Matricula->EditCustomAttributes = "";
			$this->Matricula->EditValue = ew_HtmlEncode($this->Matricula->AdvancedSearch->SearchValue);

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

			// RG
			$this->RG->EditAttrs["class"] = "form-control";
			$this->RG->EditCustomAttributes = "";
			$this->RG->EditValue = ew_HtmlEncode($this->RG->AdvancedSearch->SearchValue);

			// Profissao
			$this->Profissao->EditAttrs["class"] = "form-control";
			$this->Profissao->EditCustomAttributes = "";
			$this->Profissao->EditValue = ew_HtmlEncode($this->Profissao->AdvancedSearch->SearchValue);

			// Email
			$this->_Email->EditAttrs["class"] = "form-control";
			$this->_Email->EditCustomAttributes = "";
			$this->_Email->EditValue = ew_HtmlEncode($this->_Email->AdvancedSearch->SearchValue);

			// Endereco
			$this->Endereco->EditAttrs["class"] = "form-control";
			$this->Endereco->EditCustomAttributes = "";
			$this->Endereco->EditValue = ew_HtmlEncode($this->Endereco->AdvancedSearch->SearchValue);

			// Bairro
			$this->Bairro->EditAttrs["class"] = "form-control";
			$this->Bairro->EditCustomAttributes = "";
			$this->Bairro->EditValue = ew_HtmlEncode($this->Bairro->AdvancedSearch->SearchValue);

			// Cidade
			$this->Cidade->EditAttrs["class"] = "form-control";
			$this->Cidade->EditCustomAttributes = "";
			$this->Cidade->EditValue = ew_HtmlEncode($this->Cidade->AdvancedSearch->SearchValue);

			// UF
			$this->UF->EditAttrs["class"] = "form-control";
			$this->UF->EditCustomAttributes = "";
			$this->UF->EditValue = ew_HtmlEncode($this->UF->AdvancedSearch->SearchValue);

			// GrauEscolaridade
			$this->GrauEscolaridade->EditAttrs["class"] = "form-control";
			$this->GrauEscolaridade->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `Id`, `Escolaridade` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `escolaridade`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->GrauEscolaridade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Escolaridade` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->GrauEscolaridade->EditValue = $arwrk;

			// Data_Casamento
			$this->Data_Casamento->EditAttrs["class"] = "form-control";
			$this->Data_Casamento->EditCustomAttributes = "";
			$this->Data_Casamento->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->Data_Casamento->AdvancedSearch->SearchValue, 7), 7));
			$this->Data_Casamento->EditAttrs["class"] = "form-control";
			$this->Data_Casamento->EditCustomAttributes = "";
			$this->Data_Casamento->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->Data_Casamento->AdvancedSearch->SearchValue2, 7), 7));

			// Conjuge
			$this->Conjuge->EditAttrs["class"] = "form-control";
			$this->Conjuge->EditCustomAttributes = "";
			$this->Conjuge->EditValue = ew_HtmlEncode($this->Conjuge->AdvancedSearch->SearchValue);

			// Celula
			$this->Celula->EditAttrs["class"] = "form-control";
			$this->Celula->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `Id_celula`, `NomeCelula` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `celulas`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Celula, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `NomeCelula` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Celula->EditValue = $arwrk;

			// Nome_da_Familia
			$this->Nome_da_Familia->EditAttrs["class"] = "form-control";
			$this->Nome_da_Familia->EditCustomAttributes = "";
			$this->Nome_da_Familia->EditValue = ew_HtmlEncode($this->Nome_da_Familia->AdvancedSearch->SearchValue);

			// Situacao
			$this->Situacao->EditAttrs["class"] = "form-control";
			$this->Situacao->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `Id`, `Situacao` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `situacao_membro`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Situacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Situacao` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Situacao->EditValue = $arwrk;

			// Da_Igreja
			$this->Da_Igreja->EditAttrs["class"] = "form-control";
			$this->Da_Igreja->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `Id_igreja`, `Igreja` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `igrejas`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Da_Igreja, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Da_Igreja->EditValue = $arwrk;

			// Admissao
			$this->Admissao->EditAttrs["class"] = "form-control";
			$this->Admissao->EditCustomAttributes = "";
			$this->Admissao->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->Admissao->AdvancedSearch->SearchValue, 7), 7));
			$this->Admissao->EditAttrs["class"] = "form-control";
			$this->Admissao->EditCustomAttributes = "";
			$this->Admissao->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->Admissao->AdvancedSearch->SearchValue2, 7), 7));

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

			// Rede_Ministerial
			$this->Rede_Ministerial->EditAttrs["class"] = "form-control";
			$this->Rede_Ministerial->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `Id`, `Rede_Ministerial` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `rede_ministerial`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Rede_Ministerial, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Rede_Ministerial` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Rede_Ministerial->EditValue = $arwrk;
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
		if (!ew_CheckEuroDate($this->Data_Casamento->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->Data_Casamento->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->Data_Casamento->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->Data_Casamento->FldErrMsg());
		}
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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "membrolist.php", "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
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
if (!isset($membro_search)) $membro_search = new cmembro_search();

// Page init
$membro_search->Page_Init();

// Page main
$membro_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$membro_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var membro_search = new ew_Page("membro_search");
membro_search.PageID = "search"; // Page ID
var EW_PAGE_ID = membro_search.PageID; // For backward compatibility

// Form object
var fmembrosearch = new ew_Form("fmembrosearch");

// Form_CustomValidate event
fmembrosearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmembrosearch.ValidateRequired = true;
<?php } else { ?>
fmembrosearch.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
fmembrosearch.MultiPage = new ew_MultiPage("fmembrosearch",
	[["x_Matricula",1],["x_Sexo",1],["x_EstadoCivil",1],["x_RG",1],["x_Profissao",1],["x__Email",1],["x_Endereco",2],["x_Bairro",2],["x_Cidade",2],["x_UF",2],["x_GrauEscolaridade",2],["x_Data_Casamento",3],["x_Conjuge",3],["x_Celula",4],["x_Nome_da_Familia",4],["x_Situacao",4],["x_Da_Igreja",4],["x_Admissao",4],["x_Tipo_Admissao",4],["x_Rede_Ministerial",4]]
);

// Dynamic selection lists
fmembrosearch.Lists["x_GrauEscolaridade"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Escolaridade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembrosearch.Lists["x_Celula"] = {"LinkField":"x_Id_celula","Ajax":null,"AutoFill":false,"DisplayFields":["x_NomeCelula","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembrosearch.Lists["x_Situacao"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Situacao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembrosearch.Lists["x_Da_Igreja"] = {"LinkField":"x_Id_igreja","Ajax":null,"AutoFill":false,"DisplayFields":["x_Igreja","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembrosearch.Lists["x_Tipo_Admissao"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Tipo_Admissao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembrosearch.Lists["x_Rede_Ministerial"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Rede_Ministerial","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
// Validate function for search

fmembrosearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_Data_Casamento");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($membro->Data_Casamento->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_Admissao");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($membro->Admissao->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$membro_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $membro_search->ShowPageHeader(); ?>
<?php
$membro_search->ShowMessage();
?>
<form name="fmembrosearch" id="fmembrosearch" class="form-horizontal ewForm ewSearchForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($membro_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $membro_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="membro">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($membro_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div >
<div class="tabbable" id="membro_search">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_membro1" data-toggle="tab"><?php echo $membro->PageCaption(1) ?></a></li>
		<li><a href="#tab_membro2" data-toggle="tab"><?php echo $membro->PageCaption(2) ?></a></li>
		<li><a href="#tab_membro3" data-toggle="tab"><?php echo $membro->PageCaption(3) ?></a></li>
		<li><a href="#tab_membro4" data-toggle="tab"><?php echo $membro->PageCaption(4) ?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_membro1">
<div>
<?php if ($membro->Matricula->Visible) { // Matricula ?>
	<div id="r_Matricula" class="form-group">
		<label for="x_Matricula" class="<?php echo $membro_search->SearchLabelClass ?>"><span id="elh_membro_Matricula"><?php echo $membro->Matricula->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Matricula" id="z_Matricula" value="LIKE"></p>
		</label>
		<div class="<?php echo $membro_search->SearchRightColumnClass ?>"><div<?php echo $membro->Matricula->CellAttributes() ?>>
			<span id="el_membro_Matricula">
<input type="text" data-field="x_Matricula" name="x_Matricula" id="x_Matricula" size="30" maxlength="20" value="<?php echo $membro->Matricula->EditValue ?>"<?php echo $membro->Matricula->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($membro->Sexo->Visible) { // Sexo ?>
	<div id="r_Sexo" class="form-group">
		<label class="<?php echo $membro_search->SearchLabelClass ?>"><span id="elh_membro_Sexo"><?php echo $membro->Sexo->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Sexo" id="z_Sexo" value="="></p>
		</label>
		<div class="<?php echo $membro_search->SearchRightColumnClass ?>"><div<?php echo $membro->Sexo->CellAttributes() ?>>
			<span id="el_membro_Sexo">
<div id="tp_x_Sexo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_Sexo" id="x_Sexo" value="{value}"<?php echo $membro->Sexo->EditAttributes() ?>></div>
<div id="dsl_x_Sexo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $membro->Sexo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($membro->Sexo->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_Sexo" name="x_Sexo" id="x_Sexo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $membro->Sexo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($membro->EstadoCivil->Visible) { // EstadoCivil ?>
	<div id="r_EstadoCivil" class="form-group">
		<label for="x_EstadoCivil" class="<?php echo $membro_search->SearchLabelClass ?>"><span id="elh_membro_EstadoCivil"><?php echo $membro->EstadoCivil->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_EstadoCivil" id="z_EstadoCivil" value="="></p>
		</label>
		<div class="<?php echo $membro_search->SearchRightColumnClass ?>"><div<?php echo $membro->EstadoCivil->CellAttributes() ?>>
			<span id="el_membro_EstadoCivil">
<select data-field="x_EstadoCivil" id="x_EstadoCivil" name="x_EstadoCivil"<?php echo $membro->EstadoCivil->EditAttributes() ?>>
<?php
if (is_array($membro->EstadoCivil->EditValue)) {
	$arwrk = $membro->EstadoCivil->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($membro->EstadoCivil->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($membro->RG->Visible) { // RG ?>
	<div id="r_RG" class="form-group">
		<label for="x_RG" class="<?php echo $membro_search->SearchLabelClass ?>"><span id="elh_membro_RG"><?php echo $membro->RG->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_RG" id="z_RG" value="LIKE"></p>
		</label>
		<div class="<?php echo $membro_search->SearchRightColumnClass ?>"><div<?php echo $membro->RG->CellAttributes() ?>>
			<span id="el_membro_RG">
<input type="text" data-field="x_RG" name="x_RG" id="x_RG" size="30" maxlength="15" value="<?php echo $membro->RG->EditValue ?>"<?php echo $membro->RG->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($membro->Profissao->Visible) { // Profissao ?>
	<div id="r_Profissao" class="form-group">
		<label for="x_Profissao" class="<?php echo $membro_search->SearchLabelClass ?>"><span id="elh_membro_Profissao"><?php echo $membro->Profissao->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Profissao" id="z_Profissao" value="LIKE"></p>
		</label>
		<div class="<?php echo $membro_search->SearchRightColumnClass ?>"><div<?php echo $membro->Profissao->CellAttributes() ?>>
			<span id="el_membro_Profissao">
<input type="text" data-field="x_Profissao" name="x_Profissao" id="x_Profissao" size="30" maxlength="60" value="<?php echo $membro->Profissao->EditValue ?>"<?php echo $membro->Profissao->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($membro->_Email->Visible) { // Email ?>
	<div id="r__Email" class="form-group">
		<label for="x__Email" class="<?php echo $membro_search->SearchLabelClass ?>"><span id="elh_membro__Email"><?php echo $membro->_Email->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z__Email" id="z__Email" value="LIKE"></p>
		</label>
		<div class="<?php echo $membro_search->SearchRightColumnClass ?>"><div<?php echo $membro->_Email->CellAttributes() ?>>
			<span id="el_membro__Email">
<input type="text" data-field="x__Email" name="x__Email" id="x__Email" size="30" maxlength="50" value="<?php echo $membro->_Email->EditValue ?>"<?php echo $membro->_Email->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
</div>
		</div>
		<div class="tab-pane" id="tab_membro2">
<div>
<?php if ($membro->Endereco->Visible) { // Endereco ?>
	<div id="r_Endereco" class="form-group">
		<label for="x_Endereco" class="<?php echo $membro_search->SearchLabelClass ?>"><span id="elh_membro_Endereco"><?php echo $membro->Endereco->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Endereco" id="z_Endereco" value="LIKE"></p>
		</label>
		<div class="<?php echo $membro_search->SearchRightColumnClass ?>"><div<?php echo $membro->Endereco->CellAttributes() ?>>
			<span id="el_membro_Endereco">
<input type="text" data-field="x_Endereco" name="x_Endereco" id="x_Endereco" size="70" maxlength="60" value="<?php echo $membro->Endereco->EditValue ?>"<?php echo $membro->Endereco->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($membro->Bairro->Visible) { // Bairro ?>
	<div id="r_Bairro" class="form-group">
		<label for="x_Bairro" class="<?php echo $membro_search->SearchLabelClass ?>"><span id="elh_membro_Bairro"><?php echo $membro->Bairro->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Bairro" id="z_Bairro" value="LIKE"></p>
		</label>
		<div class="<?php echo $membro_search->SearchRightColumnClass ?>"><div<?php echo $membro->Bairro->CellAttributes() ?>>
			<span id="el_membro_Bairro">
<input type="text" data-field="x_Bairro" name="x_Bairro" id="x_Bairro" size="30" maxlength="30" value="<?php echo $membro->Bairro->EditValue ?>"<?php echo $membro->Bairro->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($membro->Cidade->Visible) { // Cidade ?>
	<div id="r_Cidade" class="form-group">
		<label for="x_Cidade" class="<?php echo $membro_search->SearchLabelClass ?>"><span id="elh_membro_Cidade"><?php echo $membro->Cidade->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Cidade" id="z_Cidade" value="LIKE"></p>
		</label>
		<div class="<?php echo $membro_search->SearchRightColumnClass ?>"><div<?php echo $membro->Cidade->CellAttributes() ?>>
			<span id="el_membro_Cidade">
<input type="text" data-field="x_Cidade" name="x_Cidade" id="x_Cidade" size="30" maxlength="30" value="<?php echo $membro->Cidade->EditValue ?>"<?php echo $membro->Cidade->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($membro->UF->Visible) { // UF ?>
	<div id="r_UF" class="form-group">
		<label for="x_UF" class="<?php echo $membro_search->SearchLabelClass ?>"><span id="elh_membro_UF"><?php echo $membro->UF->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_UF" id="z_UF" value="LIKE"></p>
		</label>
		<div class="<?php echo $membro_search->SearchRightColumnClass ?>"><div<?php echo $membro->UF->CellAttributes() ?>>
			<span id="el_membro_UF">
<input type="text" data-field="x_UF" name="x_UF" id="x_UF" size="5" maxlength="2" value="<?php echo $membro->UF->EditValue ?>"<?php echo $membro->UF->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($membro->GrauEscolaridade->Visible) { // GrauEscolaridade ?>
	<div id="r_GrauEscolaridade" class="form-group">
		<label for="x_GrauEscolaridade" class="<?php echo $membro_search->SearchLabelClass ?>"><span id="elh_membro_GrauEscolaridade"><?php echo $membro->GrauEscolaridade->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_GrauEscolaridade" id="z_GrauEscolaridade" value="="></p>
		</label>
		<div class="<?php echo $membro_search->SearchRightColumnClass ?>"><div<?php echo $membro->GrauEscolaridade->CellAttributes() ?>>
			<span id="el_membro_GrauEscolaridade">
<select data-field="x_GrauEscolaridade" id="x_GrauEscolaridade" name="x_GrauEscolaridade"<?php echo $membro->GrauEscolaridade->EditAttributes() ?>>
<?php
if (is_array($membro->GrauEscolaridade->EditValue)) {
	$arwrk = $membro->GrauEscolaridade->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($membro->GrauEscolaridade->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fmembrosearch.Lists["x_GrauEscolaridade"].Options = <?php echo (is_array($membro->GrauEscolaridade->EditValue)) ? ew_ArrayToJson($membro->GrauEscolaridade->EditValue, 1) : "[]" ?>;
</script>
</span>
		</div></div>
	</div>
<?php } ?>
</div>
		</div>
		<div class="tab-pane" id="tab_membro3">
<div>
<?php if ($membro->Data_Casamento->Visible) { // Data_Casamento ?>
	<div id="r_Data_Casamento" class="form-group">
		<label for="x_Data_Casamento" class="<?php echo $membro_search->SearchLabelClass ?>"><span id="elh_membro_Data_Casamento"><?php echo $membro->Data_Casamento->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_Data_Casamento" id="z_Data_Casamento" value="BETWEEN"></p>
		</label>
		<div class="<?php echo $membro_search->SearchRightColumnClass ?>"><div<?php echo $membro->Data_Casamento->CellAttributes() ?>>
			<span id="el_membro_Data_Casamento">
<input type="text" data-field="x_Data_Casamento" name="x_Data_Casamento" id="x_Data_Casamento" size="15" value="<?php echo $membro->Data_Casamento->EditValue ?>"<?php echo $membro->Data_Casamento->EditAttributes() ?>>
<?php if (!$membro->Data_Casamento->ReadOnly && !$membro->Data_Casamento->Disabled && @$membro->Data_Casamento->EditAttrs["readonly"] == "" && @$membro->Data_Casamento->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fmembrosearch", "x_Data_Casamento", "%d/%m/%Y");
</script>
<?php } ?>
</span>
			<span class="ewSearchCond btw1_Data_Casamento">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
			<span id="e2_membro_Data_Casamento" class="btw1_Data_Casamento">
<input type="text" data-field="x_Data_Casamento" name="y_Data_Casamento" id="y_Data_Casamento" size="15" value="<?php echo $membro->Data_Casamento->EditValue2 ?>"<?php echo $membro->Data_Casamento->EditAttributes() ?>>
<?php if (!$membro->Data_Casamento->ReadOnly && !$membro->Data_Casamento->Disabled && @$membro->Data_Casamento->EditAttrs["readonly"] == "" && @$membro->Data_Casamento->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fmembrosearch", "y_Data_Casamento", "%d/%m/%Y");
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($membro->Conjuge->Visible) { // Conjuge ?>
	<div id="r_Conjuge" class="form-group">
		<label for="x_Conjuge" class="<?php echo $membro_search->SearchLabelClass ?>"><span id="elh_membro_Conjuge"><?php echo $membro->Conjuge->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Conjuge" id="z_Conjuge" value="LIKE"></p>
		</label>
		<div class="<?php echo $membro_search->SearchRightColumnClass ?>"><div<?php echo $membro->Conjuge->CellAttributes() ?>>
			<span id="el_membro_Conjuge">
<input type="text" data-field="x_Conjuge" name="x_Conjuge" id="x_Conjuge" size="55" maxlength="80" value="<?php echo $membro->Conjuge->EditValue ?>"<?php echo $membro->Conjuge->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
</div>
		</div>
		<div class="tab-pane" id="tab_membro4">
<div>
<?php if ($membro->Celula->Visible) { // Celula ?>
	<div id="r_Celula" class="form-group">
		<label for="x_Celula" class="<?php echo $membro_search->SearchLabelClass ?>"><span id="elh_membro_Celula"><?php echo $membro->Celula->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Celula" id="z_Celula" value="LIKE"></p>
		</label>
		<div class="<?php echo $membro_search->SearchRightColumnClass ?>"><div<?php echo $membro->Celula->CellAttributes() ?>>
			<span id="el_membro_Celula">
<select data-field="x_Celula" id="x_Celula" name="x_Celula"<?php echo $membro->Celula->EditAttributes() ?>>
<?php
if (is_array($membro->Celula->EditValue)) {
	$arwrk = $membro->Celula->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($membro->Celula->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fmembrosearch.Lists["x_Celula"].Options = <?php echo (is_array($membro->Celula->EditValue)) ? ew_ArrayToJson($membro->Celula->EditValue, 1) : "[]" ?>;
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($membro->Nome_da_Familia->Visible) { // Nome_da_Familia ?>
	<div id="r_Nome_da_Familia" class="form-group">
		<label for="x_Nome_da_Familia" class="<?php echo $membro_search->SearchLabelClass ?>"><span id="elh_membro_Nome_da_Familia"><?php echo $membro->Nome_da_Familia->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Nome_da_Familia" id="z_Nome_da_Familia" value="LIKE"></p>
		</label>
		<div class="<?php echo $membro_search->SearchRightColumnClass ?>"><div<?php echo $membro->Nome_da_Familia->CellAttributes() ?>>
			<span id="el_membro_Nome_da_Familia">
<input type="text" data-field="x_Nome_da_Familia" name="x_Nome_da_Familia" id="x_Nome_da_Familia" size="60" maxlength="100" value="<?php echo $membro->Nome_da_Familia->EditValue ?>"<?php echo $membro->Nome_da_Familia->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($membro->Situacao->Visible) { // Situacao ?>
	<div id="r_Situacao" class="form-group">
		<label for="x_Situacao" class="<?php echo $membro_search->SearchLabelClass ?>"><span id="elh_membro_Situacao"><?php echo $membro->Situacao->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Situacao" id="z_Situacao" value="="></p>
		</label>
		<div class="<?php echo $membro_search->SearchRightColumnClass ?>"><div<?php echo $membro->Situacao->CellAttributes() ?>>
			<span id="el_membro_Situacao">
<select data-field="x_Situacao" id="x_Situacao" name="x_Situacao"<?php echo $membro->Situacao->EditAttributes() ?>>
<?php
if (is_array($membro->Situacao->EditValue)) {
	$arwrk = $membro->Situacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($membro->Situacao->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fmembrosearch.Lists["x_Situacao"].Options = <?php echo (is_array($membro->Situacao->EditValue)) ? ew_ArrayToJson($membro->Situacao->EditValue, 1) : "[]" ?>;
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($membro->Da_Igreja->Visible) { // Da_Igreja ?>
	<div id="r_Da_Igreja" class="form-group">
		<label for="x_Da_Igreja" class="<?php echo $membro_search->SearchLabelClass ?>"><span id="elh_membro_Da_Igreja"><?php echo $membro->Da_Igreja->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Da_Igreja" id="z_Da_Igreja" value="="></p>
		</label>
		<div class="<?php echo $membro_search->SearchRightColumnClass ?>"><div<?php echo $membro->Da_Igreja->CellAttributes() ?>>
			<span id="el_membro_Da_Igreja">
<select data-field="x_Da_Igreja" id="x_Da_Igreja" name="x_Da_Igreja"<?php echo $membro->Da_Igreja->EditAttributes() ?>>
<?php
if (is_array($membro->Da_Igreja->EditValue)) {
	$arwrk = $membro->Da_Igreja->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($membro->Da_Igreja->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fmembrosearch.Lists["x_Da_Igreja"].Options = <?php echo (is_array($membro->Da_Igreja->EditValue)) ? ew_ArrayToJson($membro->Da_Igreja->EditValue, 1) : "[]" ?>;
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($membro->Admissao->Visible) { // Admissao ?>
	<div id="r_Admissao" class="form-group">
		<label for="x_Admissao" class="<?php echo $membro_search->SearchLabelClass ?>"><span id="elh_membro_Admissao"><?php echo $membro->Admissao->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_Admissao" id="z_Admissao" value="BETWEEN"></p>
		</label>
		<div class="<?php echo $membro_search->SearchRightColumnClass ?>"><div<?php echo $membro->Admissao->CellAttributes() ?>>
			<span id="el_membro_Admissao">
<input type="text" data-field="x_Admissao" name="x_Admissao" id="x_Admissao" size="15" value="<?php echo $membro->Admissao->EditValue ?>"<?php echo $membro->Admissao->EditAttributes() ?>>
<?php if (!$membro->Admissao->ReadOnly && !$membro->Admissao->Disabled && @$membro->Admissao->EditAttrs["readonly"] == "" && @$membro->Admissao->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fmembrosearch", "x_Admissao", "%d/%m/%Y");
</script>
<?php } ?>
</span>
			<span class="ewSearchCond btw1_Admissao">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
			<span id="e2_membro_Admissao" class="btw1_Admissao">
<input type="text" data-field="x_Admissao" name="y_Admissao" id="y_Admissao" size="15" value="<?php echo $membro->Admissao->EditValue2 ?>"<?php echo $membro->Admissao->EditAttributes() ?>>
<?php if (!$membro->Admissao->ReadOnly && !$membro->Admissao->Disabled && @$membro->Admissao->EditAttrs["readonly"] == "" && @$membro->Admissao->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fmembrosearch", "y_Admissao", "%d/%m/%Y");
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($membro->Tipo_Admissao->Visible) { // Tipo_Admissao ?>
	<div id="r_Tipo_Admissao" class="form-group">
		<label for="x_Tipo_Admissao" class="<?php echo $membro_search->SearchLabelClass ?>"><span id="elh_membro_Tipo_Admissao"><?php echo $membro->Tipo_Admissao->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Tipo_Admissao" id="z_Tipo_Admissao" value="="></p>
		</label>
		<div class="<?php echo $membro_search->SearchRightColumnClass ?>"><div<?php echo $membro->Tipo_Admissao->CellAttributes() ?>>
			<span id="el_membro_Tipo_Admissao">
<select data-field="x_Tipo_Admissao" id="x_Tipo_Admissao" name="x_Tipo_Admissao"<?php echo $membro->Tipo_Admissao->EditAttributes() ?>>
<?php
if (is_array($membro->Tipo_Admissao->EditValue)) {
	$arwrk = $membro->Tipo_Admissao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($membro->Tipo_Admissao->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fmembrosearch.Lists["x_Tipo_Admissao"].Options = <?php echo (is_array($membro->Tipo_Admissao->EditValue)) ? ew_ArrayToJson($membro->Tipo_Admissao->EditValue, 1) : "[]" ?>;
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($membro->Rede_Ministerial->Visible) { // Rede_Ministerial ?>
	<div id="r_Rede_Ministerial" class="form-group">
		<label for="x_Rede_Ministerial" class="<?php echo $membro_search->SearchLabelClass ?>"><span id="elh_membro_Rede_Ministerial"><?php echo $membro->Rede_Ministerial->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Rede_Ministerial" id="z_Rede_Ministerial" value="="></p>
		</label>
		<div class="<?php echo $membro_search->SearchRightColumnClass ?>"><div<?php echo $membro->Rede_Ministerial->CellAttributes() ?>>
			<span id="el_membro_Rede_Ministerial">
<select data-field="x_Rede_Ministerial" id="x_Rede_Ministerial" name="x_Rede_Ministerial"<?php echo $membro->Rede_Ministerial->EditAttributes() ?>>
<?php
if (is_array($membro->Rede_Ministerial->EditValue)) {
	$arwrk = $membro->Rede_Ministerial->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($membro->Rede_Ministerial->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fmembrosearch.Lists["x_Rede_Ministerial"].Options = <?php echo (is_array($membro->Rede_Ministerial->EditValue)) ? ew_ArrayToJson($membro->Rede_Ministerial->EditValue, 1) : "[]" ?>;
</script>
</span>
		</div></div>
	</div>
<?php } ?>
</div>
		</div>
	</div>
</div>
</div>
<?php if (!$membro_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fmembrosearch.Init();
</script>
<?php
$membro_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$membro_search->Page_Terminate();
?>
