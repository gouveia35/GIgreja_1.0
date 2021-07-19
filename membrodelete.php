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

$membro_delete = NULL; // Initialize page object first

class cmembro_delete extends cmembro {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{2B7992FC-5911-46A7-9310-01F4D4225C49}";

	// Table name
	var $TableName = 'membro';

	// Page object name
	var $PageObjName = 'membro_delete';

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
	var $AuditTrailOnDelete = TRUE;

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
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
			$this->Page_Terminate("membrolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in membro class, membroinfo.php

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
		if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteBegin")); // Batch delete begin

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
				$sThisKey .= $row['Id_membro'];
				$this->LoadDbValues($row);
				@unlink(ew_UploadPathEx(TRUE, $this->Foto->OldUploadPath) . $row['Foto']);
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
			if ($DeleteRows) {
				foreach ($rsold as $row)
					$this->WriteAuditTrailOnDelete($row);
			}
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
			$conn->RollbackTrans(); // Rollback changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteRollback")); // Batch delete rollback
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
		$Breadcrumb->Add("list", $this->TableVar, "membrolist.php", "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'membro';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		if (!$this->AuditTrailOnDelete) return;
		$table = 'membro';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['Id_membro'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $curUser = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
			}
		}
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
if (!isset($membro_delete)) $membro_delete = new cmembro_delete();

// Page init
$membro_delete->Page_Init();

// Page main
$membro_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$membro_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var membro_delete = new ew_Page("membro_delete");
membro_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = membro_delete.PageID; // For backward compatibility

// Form object
var fmembrodelete = new ew_Form("fmembrodelete");

// Form_CustomValidate event
fmembrodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmembrodelete.ValidateRequired = true;
<?php } else { ?>
fmembrodelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmembrodelete.Lists["x_CargoMinisterial"] = {"LinkField":"x_id_cgm","Ajax":null,"AutoFill":false,"DisplayFields":["x_Cargo_Ministerial","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembrodelete.Lists["x_Funcao"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Funcao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($membro_delete->Recordset = $membro_delete->LoadRecordset())
	$membro_deleteTotalRecs = $membro_delete->Recordset->RecordCount(); // Get record count
if ($membro_deleteTotalRecs <= 0) { // No record found, exit
	if ($membro_delete->Recordset)
		$membro_delete->Recordset->Close();
	$membro_delete->Page_Terminate("membrolist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $membro_delete->ShowPageHeader(); ?>
<?php
$membro_delete->ShowMessage();
?>
<form name="fmembrodelete" id="fmembrodelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($membro_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $membro_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="membro">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($membro_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $membro->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($membro->Foto->Visible) { // Foto ?>
		<th><span id="elh_membro_Foto" class="membro_Foto"><?php echo $membro->Foto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($membro->Matricula->Visible) { // Matricula ?>
		<th><span id="elh_membro_Matricula" class="membro_Matricula"><?php echo $membro->Matricula->FldCaption() ?></span></th>
<?php } ?>
<?php if ($membro->Nome->Visible) { // Nome ?>
		<th><span id="elh_membro_Nome" class="membro_Nome"><?php echo $membro->Nome->FldCaption() ?></span></th>
<?php } ?>
<?php if ($membro->Sexo->Visible) { // Sexo ?>
		<th><span id="elh_membro_Sexo" class="membro_Sexo"><?php echo $membro->Sexo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($membro->EstadoCivil->Visible) { // EstadoCivil ?>
		<th><span id="elh_membro_EstadoCivil" class="membro_EstadoCivil"><?php echo $membro->EstadoCivil->FldCaption() ?></span></th>
<?php } ?>
<?php if ($membro->CPF->Visible) { // CPF ?>
		<th><span id="elh_membro_CPF" class="membro_CPF"><?php echo $membro->CPF->FldCaption() ?></span></th>
<?php } ?>
<?php if ($membro->CargoMinisterial->Visible) { // CargoMinisterial ?>
		<th><span id="elh_membro_CargoMinisterial" class="membro_CargoMinisterial"><?php echo $membro->CargoMinisterial->FldCaption() ?></span></th>
<?php } ?>
<?php if ($membro->Funcao->Visible) { // Funcao ?>
		<th><span id="elh_membro_Funcao" class="membro_Funcao"><?php echo $membro->Funcao->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$membro_delete->RecCnt = 0;
$i = 0;
while (!$membro_delete->Recordset->EOF) {
	$membro_delete->RecCnt++;
	$membro_delete->RowCnt++;

	// Set row properties
	$membro->ResetAttrs();
	$membro->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$membro_delete->LoadRowValues($membro_delete->Recordset);

	// Render row
	$membro_delete->RenderRow();
?>
	<tr<?php echo $membro->RowAttributes() ?>>
<?php if ($membro->Foto->Visible) { // Foto ?>
		<td<?php echo $membro->Foto->CellAttributes() ?>>
<span id="el<?php echo $membro_delete->RowCnt ?>_membro_Foto" class="form-group membro_Foto">
<span>
<?php echo ew_GetFileViewTag($membro->Foto, $membro->Foto->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($membro->Matricula->Visible) { // Matricula ?>
		<td<?php echo $membro->Matricula->CellAttributes() ?>>
<span id="el<?php echo $membro_delete->RowCnt ?>_membro_Matricula" class="form-group membro_Matricula">
<span<?php echo $membro->Matricula->ViewAttributes() ?>>
<?php echo $membro->Matricula->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($membro->Nome->Visible) { // Nome ?>
		<td<?php echo $membro->Nome->CellAttributes() ?>>
<span id="el<?php echo $membro_delete->RowCnt ?>_membro_Nome" class="form-group membro_Nome">
<span<?php echo $membro->Nome->ViewAttributes() ?>>
<?php echo $membro->Nome->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($membro->Sexo->Visible) { // Sexo ?>
		<td<?php echo $membro->Sexo->CellAttributes() ?>>
<span id="el<?php echo $membro_delete->RowCnt ?>_membro_Sexo" class="form-group membro_Sexo">
<span<?php echo $membro->Sexo->ViewAttributes() ?>>
<?php echo $membro->Sexo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($membro->EstadoCivil->Visible) { // EstadoCivil ?>
		<td<?php echo $membro->EstadoCivil->CellAttributes() ?>>
<span id="el<?php echo $membro_delete->RowCnt ?>_membro_EstadoCivil" class="form-group membro_EstadoCivil">
<span<?php echo $membro->EstadoCivil->ViewAttributes() ?>>
<?php echo $membro->EstadoCivil->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($membro->CPF->Visible) { // CPF ?>
		<td<?php echo $membro->CPF->CellAttributes() ?>>
<span id="el<?php echo $membro_delete->RowCnt ?>_membro_CPF" class="form-group membro_CPF">
<span<?php echo $membro->CPF->ViewAttributes() ?>>
<?php echo $membro->CPF->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($membro->CargoMinisterial->Visible) { // CargoMinisterial ?>
		<td<?php echo $membro->CargoMinisterial->CellAttributes() ?>>
<span id="el<?php echo $membro_delete->RowCnt ?>_membro_CargoMinisterial" class="form-group membro_CargoMinisterial">
<span<?php echo $membro->CargoMinisterial->ViewAttributes() ?>>
<?php echo $membro->CargoMinisterial->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($membro->Funcao->Visible) { // Funcao ?>
		<td<?php echo $membro->Funcao->CellAttributes() ?>>
<span id="el<?php echo $membro_delete->RowCnt ?>_membro_Funcao" class="form-group membro_Funcao">
<span<?php echo $membro->Funcao->ViewAttributes() ?>>
<?php echo $membro->Funcao->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$membro_delete->Recordset->MoveNext();
}
$membro_delete->Recordset->Close();
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
fmembrodelete.Init();
</script>
<?php
$membro_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$membro_delete->Page_Terminate();
?>
