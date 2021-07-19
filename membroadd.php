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

$membro_add = NULL; // Initialize page object first

class cmembro_add extends cmembro {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{2B7992FC-5911-46A7-9310-01F4D4225C49}";

	// Table name
	var $TableName = 'membro';

	// Page object name
	var $PageObjName = 'membro_add';

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
	var $AuditTrailOnAdd = TRUE;

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["Id_membro"] != "") {
				$this->Id_membro->setQueryStringValue($_GET["Id_membro"]);
				$this->setKey("Id_membro", $this->Id_membro->CurrentValue); // Set up key
			} else {
				$this->setKey("Id_membro", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Set up detail parameters
		$this->SetUpDetailParms();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("membrolist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					if ($this->getCurrentDetailTable() <> "") // Master/detail add
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "membroview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetUpDetailParms();
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->Foto->Upload->Index = $objForm->Index;
		$this->Foto->Upload->UploadFile();
		$this->Foto->CurrentValue = $this->Foto->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->Foto->Upload->DbValue = NULL;
		$this->Foto->OldValue = $this->Foto->Upload->DbValue;
		$this->Foto->CurrentValue = NULL; // Clear file related field
		$this->Matricula->CurrentValue = NULL;
		$this->Matricula->OldValue = $this->Matricula->CurrentValue;
		$this->Nome->CurrentValue = NULL;
		$this->Nome->OldValue = $this->Nome->CurrentValue;
		$this->Sexo->CurrentValue = "Masculino";
		$this->DataNasc->CurrentValue = NULL;
		$this->DataNasc->OldValue = $this->DataNasc->CurrentValue;
		$this->Nacionalidade->CurrentValue = "Brasileiro";
		$this->EstadoCivil->CurrentValue = "Solteiro(a)";
		$this->CPF->CurrentValue = NULL;
		$this->CPF->OldValue = $this->CPF->CurrentValue;
		$this->RG->CurrentValue = NULL;
		$this->RG->OldValue = $this->RG->CurrentValue;
		$this->Profissao->CurrentValue = NULL;
		$this->Profissao->OldValue = $this->Profissao->CurrentValue;
		$this->_Email->CurrentValue = NULL;
		$this->_Email->OldValue = $this->_Email->CurrentValue;
		$this->TelefoneRes->CurrentValue = NULL;
		$this->TelefoneRes->OldValue = $this->TelefoneRes->CurrentValue;
		$this->Celular_1->CurrentValue = NULL;
		$this->Celular_1->OldValue = $this->Celular_1->CurrentValue;
		$this->Celular_2->CurrentValue = NULL;
		$this->Celular_2->OldValue = $this->Celular_2->CurrentValue;
		$this->Endereco->CurrentValue = NULL;
		$this->Endereco->OldValue = $this->Endereco->CurrentValue;
		$this->Complemento->CurrentValue = NULL;
		$this->Complemento->OldValue = $this->Complemento->CurrentValue;
		$this->Bairro->CurrentValue = NULL;
		$this->Bairro->OldValue = $this->Bairro->CurrentValue;
		$this->Cidade->CurrentValue = NULL;
		$this->Cidade->OldValue = $this->Cidade->CurrentValue;
		$this->UF->CurrentValue = NULL;
		$this->UF->OldValue = $this->UF->CurrentValue;
		$this->CEP->CurrentValue = NULL;
		$this->CEP->OldValue = $this->CEP->CurrentValue;
		$this->GrauEscolaridade->CurrentValue = NULL;
		$this->GrauEscolaridade->OldValue = $this->GrauEscolaridade->CurrentValue;
		$this->Curso->CurrentValue = NULL;
		$this->Curso->OldValue = $this->Curso->CurrentValue;
		$this->Nome_do_Pai->CurrentValue = NULL;
		$this->Nome_do_Pai->OldValue = $this->Nome_do_Pai->CurrentValue;
		$this->Nome_da_Mae->CurrentValue = NULL;
		$this->Nome_da_Mae->OldValue = $this->Nome_da_Mae->CurrentValue;
		$this->Data_Casamento->CurrentValue = NULL;
		$this->Data_Casamento->OldValue = $this->Data_Casamento->CurrentValue;
		$this->Conjuge->CurrentValue = NULL;
		$this->Conjuge->OldValue = $this->Conjuge->CurrentValue;
		$this->N_Filhos->CurrentValue = NULL;
		$this->N_Filhos->OldValue = $this->N_Filhos->CurrentValue;
		$this->Empresa_trabalha->CurrentValue = NULL;
		$this->Empresa_trabalha->OldValue = $this->Empresa_trabalha->CurrentValue;
		$this->Fone_Empresa->CurrentValue = NULL;
		$this->Fone_Empresa->OldValue = $this->Fone_Empresa->CurrentValue;
		$this->Anotacoes->CurrentValue = NULL;
		$this->Anotacoes->OldValue = $this->Anotacoes->CurrentValue;
		$this->Celula->CurrentValue = NULL;
		$this->Celula->OldValue = $this->Celula->CurrentValue;
		$this->Nome_da_Familia->CurrentValue = NULL;
		$this->Nome_da_Familia->OldValue = $this->Nome_da_Familia->CurrentValue;
		$this->Situacao->CurrentValue = NULL;
		$this->Situacao->OldValue = $this->Situacao->CurrentValue;
		$this->Data_batismo->CurrentValue = NULL;
		$this->Data_batismo->OldValue = $this->Data_batismo->CurrentValue;
		$this->Da_Igreja->CurrentValue = NULL;
		$this->Da_Igreja->OldValue = $this->Da_Igreja->CurrentValue;
		$this->CargoMinisterial->CurrentValue = NULL;
		$this->CargoMinisterial->OldValue = $this->CargoMinisterial->CurrentValue;
		$this->Admissao->CurrentValue = NULL;
		$this->Admissao->OldValue = $this->Admissao->CurrentValue;
		$this->Tipo_Admissao->CurrentValue = NULL;
		$this->Tipo_Admissao->OldValue = $this->Tipo_Admissao->CurrentValue;
		$this->Funcao->CurrentValue = NULL;
		$this->Funcao->OldValue = $this->Funcao->CurrentValue;
		$this->Rede_Ministerial->CurrentValue = NULL;
		$this->Rede_Ministerial->OldValue = $this->Rede_Ministerial->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->Matricula->FldIsDetailKey) {
			$this->Matricula->setFormValue($objForm->GetValue("x_Matricula"));
		}
		if (!$this->Nome->FldIsDetailKey) {
			$this->Nome->setFormValue($objForm->GetValue("x_Nome"));
		}
		if (!$this->Sexo->FldIsDetailKey) {
			$this->Sexo->setFormValue($objForm->GetValue("x_Sexo"));
		}
		if (!$this->DataNasc->FldIsDetailKey) {
			$this->DataNasc->setFormValue($objForm->GetValue("x_DataNasc"));
			$this->DataNasc->CurrentValue = ew_UnFormatDateTime($this->DataNasc->CurrentValue, 7);
		}
		if (!$this->Nacionalidade->FldIsDetailKey) {
			$this->Nacionalidade->setFormValue($objForm->GetValue("x_Nacionalidade"));
		}
		if (!$this->EstadoCivil->FldIsDetailKey) {
			$this->EstadoCivil->setFormValue($objForm->GetValue("x_EstadoCivil"));
		}
		if (!$this->CPF->FldIsDetailKey) {
			$this->CPF->setFormValue($objForm->GetValue("x_CPF"));
		}
		if (!$this->RG->FldIsDetailKey) {
			$this->RG->setFormValue($objForm->GetValue("x_RG"));
		}
		if (!$this->Profissao->FldIsDetailKey) {
			$this->Profissao->setFormValue($objForm->GetValue("x_Profissao"));
		}
		if (!$this->_Email->FldIsDetailKey) {
			$this->_Email->setFormValue($objForm->GetValue("x__Email"));
		}
		if (!$this->TelefoneRes->FldIsDetailKey) {
			$this->TelefoneRes->setFormValue($objForm->GetValue("x_TelefoneRes"));
		}
		if (!$this->Celular_1->FldIsDetailKey) {
			$this->Celular_1->setFormValue($objForm->GetValue("x_Celular_1"));
		}
		if (!$this->Celular_2->FldIsDetailKey) {
			$this->Celular_2->setFormValue($objForm->GetValue("x_Celular_2"));
		}
		if (!$this->Endereco->FldIsDetailKey) {
			$this->Endereco->setFormValue($objForm->GetValue("x_Endereco"));
		}
		if (!$this->Complemento->FldIsDetailKey) {
			$this->Complemento->setFormValue($objForm->GetValue("x_Complemento"));
		}
		if (!$this->Bairro->FldIsDetailKey) {
			$this->Bairro->setFormValue($objForm->GetValue("x_Bairro"));
		}
		if (!$this->Cidade->FldIsDetailKey) {
			$this->Cidade->setFormValue($objForm->GetValue("x_Cidade"));
		}
		if (!$this->UF->FldIsDetailKey) {
			$this->UF->setFormValue($objForm->GetValue("x_UF"));
		}
		if (!$this->CEP->FldIsDetailKey) {
			$this->CEP->setFormValue($objForm->GetValue("x_CEP"));
		}
		if (!$this->GrauEscolaridade->FldIsDetailKey) {
			$this->GrauEscolaridade->setFormValue($objForm->GetValue("x_GrauEscolaridade"));
		}
		if (!$this->Curso->FldIsDetailKey) {
			$this->Curso->setFormValue($objForm->GetValue("x_Curso"));
		}
		if (!$this->Nome_do_Pai->FldIsDetailKey) {
			$this->Nome_do_Pai->setFormValue($objForm->GetValue("x_Nome_do_Pai"));
		}
		if (!$this->Nome_da_Mae->FldIsDetailKey) {
			$this->Nome_da_Mae->setFormValue($objForm->GetValue("x_Nome_da_Mae"));
		}
		if (!$this->Data_Casamento->FldIsDetailKey) {
			$this->Data_Casamento->setFormValue($objForm->GetValue("x_Data_Casamento"));
			$this->Data_Casamento->CurrentValue = ew_UnFormatDateTime($this->Data_Casamento->CurrentValue, 7);
		}
		if (!$this->Conjuge->FldIsDetailKey) {
			$this->Conjuge->setFormValue($objForm->GetValue("x_Conjuge"));
		}
		if (!$this->N_Filhos->FldIsDetailKey) {
			$this->N_Filhos->setFormValue($objForm->GetValue("x_N_Filhos"));
		}
		if (!$this->Empresa_trabalha->FldIsDetailKey) {
			$this->Empresa_trabalha->setFormValue($objForm->GetValue("x_Empresa_trabalha"));
		}
		if (!$this->Fone_Empresa->FldIsDetailKey) {
			$this->Fone_Empresa->setFormValue($objForm->GetValue("x_Fone_Empresa"));
		}
		if (!$this->Anotacoes->FldIsDetailKey) {
			$this->Anotacoes->setFormValue($objForm->GetValue("x_Anotacoes"));
		}
		if (!$this->Celula->FldIsDetailKey) {
			$this->Celula->setFormValue($objForm->GetValue("x_Celula"));
		}
		if (!$this->Nome_da_Familia->FldIsDetailKey) {
			$this->Nome_da_Familia->setFormValue($objForm->GetValue("x_Nome_da_Familia"));
		}
		if (!$this->Situacao->FldIsDetailKey) {
			$this->Situacao->setFormValue($objForm->GetValue("x_Situacao"));
		}
		if (!$this->Data_batismo->FldIsDetailKey) {
			$this->Data_batismo->setFormValue($objForm->GetValue("x_Data_batismo"));
			$this->Data_batismo->CurrentValue = ew_UnFormatDateTime($this->Data_batismo->CurrentValue, 7);
		}
		if (!$this->Da_Igreja->FldIsDetailKey) {
			$this->Da_Igreja->setFormValue($objForm->GetValue("x_Da_Igreja"));
		}
		if (!$this->CargoMinisterial->FldIsDetailKey) {
			$this->CargoMinisterial->setFormValue($objForm->GetValue("x_CargoMinisterial"));
		}
		if (!$this->Admissao->FldIsDetailKey) {
			$this->Admissao->setFormValue($objForm->GetValue("x_Admissao"));
			$this->Admissao->CurrentValue = ew_UnFormatDateTime($this->Admissao->CurrentValue, 7);
		}
		if (!$this->Tipo_Admissao->FldIsDetailKey) {
			$this->Tipo_Admissao->setFormValue($objForm->GetValue("x_Tipo_Admissao"));
		}
		if (!$this->Funcao->FldIsDetailKey) {
			$this->Funcao->setFormValue($objForm->GetValue("x_Funcao"));
		}
		if (!$this->Rede_Ministerial->FldIsDetailKey) {
			$this->Rede_Ministerial->setFormValue($objForm->GetValue("x_Rede_Ministerial"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->Matricula->CurrentValue = $this->Matricula->FormValue;
		$this->Nome->CurrentValue = $this->Nome->FormValue;
		$this->Sexo->CurrentValue = $this->Sexo->FormValue;
		$this->DataNasc->CurrentValue = $this->DataNasc->FormValue;
		$this->DataNasc->CurrentValue = ew_UnFormatDateTime($this->DataNasc->CurrentValue, 7);
		$this->Nacionalidade->CurrentValue = $this->Nacionalidade->FormValue;
		$this->EstadoCivil->CurrentValue = $this->EstadoCivil->FormValue;
		$this->CPF->CurrentValue = $this->CPF->FormValue;
		$this->RG->CurrentValue = $this->RG->FormValue;
		$this->Profissao->CurrentValue = $this->Profissao->FormValue;
		$this->_Email->CurrentValue = $this->_Email->FormValue;
		$this->TelefoneRes->CurrentValue = $this->TelefoneRes->FormValue;
		$this->Celular_1->CurrentValue = $this->Celular_1->FormValue;
		$this->Celular_2->CurrentValue = $this->Celular_2->FormValue;
		$this->Endereco->CurrentValue = $this->Endereco->FormValue;
		$this->Complemento->CurrentValue = $this->Complemento->FormValue;
		$this->Bairro->CurrentValue = $this->Bairro->FormValue;
		$this->Cidade->CurrentValue = $this->Cidade->FormValue;
		$this->UF->CurrentValue = $this->UF->FormValue;
		$this->CEP->CurrentValue = $this->CEP->FormValue;
		$this->GrauEscolaridade->CurrentValue = $this->GrauEscolaridade->FormValue;
		$this->Curso->CurrentValue = $this->Curso->FormValue;
		$this->Nome_do_Pai->CurrentValue = $this->Nome_do_Pai->FormValue;
		$this->Nome_da_Mae->CurrentValue = $this->Nome_da_Mae->FormValue;
		$this->Data_Casamento->CurrentValue = $this->Data_Casamento->FormValue;
		$this->Data_Casamento->CurrentValue = ew_UnFormatDateTime($this->Data_Casamento->CurrentValue, 7);
		$this->Conjuge->CurrentValue = $this->Conjuge->FormValue;
		$this->N_Filhos->CurrentValue = $this->N_Filhos->FormValue;
		$this->Empresa_trabalha->CurrentValue = $this->Empresa_trabalha->FormValue;
		$this->Fone_Empresa->CurrentValue = $this->Fone_Empresa->FormValue;
		$this->Anotacoes->CurrentValue = $this->Anotacoes->FormValue;
		$this->Celula->CurrentValue = $this->Celula->FormValue;
		$this->Nome_da_Familia->CurrentValue = $this->Nome_da_Familia->FormValue;
		$this->Situacao->CurrentValue = $this->Situacao->FormValue;
		$this->Data_batismo->CurrentValue = $this->Data_batismo->FormValue;
		$this->Data_batismo->CurrentValue = ew_UnFormatDateTime($this->Data_batismo->CurrentValue, 7);
		$this->Da_Igreja->CurrentValue = $this->Da_Igreja->FormValue;
		$this->CargoMinisterial->CurrentValue = $this->CargoMinisterial->FormValue;
		$this->Admissao->CurrentValue = $this->Admissao->FormValue;
		$this->Admissao->CurrentValue = ew_UnFormatDateTime($this->Admissao->CurrentValue, 7);
		$this->Tipo_Admissao->CurrentValue = $this->Tipo_Admissao->FormValue;
		$this->Funcao->CurrentValue = $this->Funcao->FormValue;
		$this->Rede_Ministerial->CurrentValue = $this->Rede_Ministerial->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// Foto
			$this->Foto->EditAttrs["class"] = "form-control";
			$this->Foto->EditCustomAttributes = "readonly";
			if (!ew_Empty($this->Foto->Upload->DbValue)) {
				$this->Foto->ImageWidth = 30;
				$this->Foto->ImageHeight = 0;
				$this->Foto->ImageAlt = $this->Foto->FldAlt();
				$this->Foto->EditValue = ew_UploadPathEx(FALSE, $this->Foto->UploadPath) . $this->Foto->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->Foto->EditValue = ew_UploadPathEx(TRUE, $this->Foto->UploadPath) . $this->Foto->Upload->DbValue;
				}
			} else {
				$this->Foto->EditValue = "";
			}
			if (!ew_Empty($this->Foto->CurrentValue))
				$this->Foto->Upload->FileName = $this->Foto->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->Foto);

			// Matricula
			$this->Matricula->EditAttrs["class"] = "form-control";
			$this->Matricula->EditCustomAttributes = "";
			$this->Matricula->EditValue = ew_HtmlEncode($this->Matricula->CurrentValue);

			// Nome
			$this->Nome->EditAttrs["class"] = "form-control";
			$this->Nome->EditCustomAttributes = "";
			$this->Nome->EditValue = ew_HtmlEncode($this->Nome->CurrentValue);

			// Sexo
			$this->Sexo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Sexo->FldTagValue(1), $this->Sexo->FldTagCaption(1) <> "" ? $this->Sexo->FldTagCaption(1) : $this->Sexo->FldTagValue(1));
			$arwrk[] = array($this->Sexo->FldTagValue(2), $this->Sexo->FldTagCaption(2) <> "" ? $this->Sexo->FldTagCaption(2) : $this->Sexo->FldTagValue(2));
			$this->Sexo->EditValue = $arwrk;

			// DataNasc
			$this->DataNasc->EditAttrs["class"] = "form-control";
			$this->DataNasc->EditCustomAttributes = "";
			$this->DataNasc->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->DataNasc->CurrentValue, 7));

			// Nacionalidade
			$this->Nacionalidade->EditAttrs["class"] = "form-control";
			$this->Nacionalidade->EditCustomAttributes = "";
			$this->Nacionalidade->EditValue = ew_HtmlEncode($this->Nacionalidade->CurrentValue);

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
			$this->CPF->EditValue = ew_HtmlEncode($this->CPF->CurrentValue);

			// RG
			$this->RG->EditAttrs["class"] = "form-control";
			$this->RG->EditCustomAttributes = "";
			$this->RG->EditValue = ew_HtmlEncode($this->RG->CurrentValue);

			// Profissao
			$this->Profissao->EditAttrs["class"] = "form-control";
			$this->Profissao->EditCustomAttributes = "";
			$this->Profissao->EditValue = ew_HtmlEncode($this->Profissao->CurrentValue);

			// Email
			$this->_Email->EditAttrs["class"] = "form-control";
			$this->_Email->EditCustomAttributes = "";
			$this->_Email->EditValue = ew_HtmlEncode($this->_Email->CurrentValue);

			// TelefoneRes
			$this->TelefoneRes->EditAttrs["class"] = "form-control";
			$this->TelefoneRes->EditCustomAttributes = "";
			$this->TelefoneRes->EditValue = ew_HtmlEncode($this->TelefoneRes->CurrentValue);

			// Celular_1
			$this->Celular_1->EditAttrs["class"] = "form-control";
			$this->Celular_1->EditCustomAttributes = "";
			$this->Celular_1->EditValue = ew_HtmlEncode($this->Celular_1->CurrentValue);

			// Celular_2
			$this->Celular_2->EditAttrs["class"] = "form-control";
			$this->Celular_2->EditCustomAttributes = "";
			$this->Celular_2->EditValue = ew_HtmlEncode($this->Celular_2->CurrentValue);

			// Endereco
			$this->Endereco->EditAttrs["class"] = "form-control";
			$this->Endereco->EditCustomAttributes = "";
			$this->Endereco->EditValue = ew_HtmlEncode($this->Endereco->CurrentValue);

			// Complemento
			$this->Complemento->EditAttrs["class"] = "form-control";
			$this->Complemento->EditCustomAttributes = "";
			$this->Complemento->EditValue = ew_HtmlEncode($this->Complemento->CurrentValue);

			// Bairro
			$this->Bairro->EditAttrs["class"] = "form-control";
			$this->Bairro->EditCustomAttributes = "";
			$this->Bairro->EditValue = ew_HtmlEncode($this->Bairro->CurrentValue);

			// Cidade
			$this->Cidade->EditAttrs["class"] = "form-control";
			$this->Cidade->EditCustomAttributes = "";
			$this->Cidade->EditValue = ew_HtmlEncode($this->Cidade->CurrentValue);

			// UF
			$this->UF->EditAttrs["class"] = "form-control";
			$this->UF->EditCustomAttributes = "";
			$this->UF->EditValue = ew_HtmlEncode($this->UF->CurrentValue);

			// CEP
			$this->CEP->EditAttrs["class"] = "form-control";
			$this->CEP->EditCustomAttributes = "";
			$this->CEP->EditValue = ew_HtmlEncode($this->CEP->CurrentValue);

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

			// Curso
			$this->Curso->EditAttrs["class"] = "form-control";
			$this->Curso->EditCustomAttributes = "";
			$this->Curso->EditValue = ew_HtmlEncode($this->Curso->CurrentValue);

			// Nome_do_Pai
			$this->Nome_do_Pai->EditAttrs["class"] = "form-control";
			$this->Nome_do_Pai->EditCustomAttributes = "";
			$this->Nome_do_Pai->EditValue = ew_HtmlEncode($this->Nome_do_Pai->CurrentValue);

			// Nome_da_Mae
			$this->Nome_da_Mae->EditAttrs["class"] = "form-control";
			$this->Nome_da_Mae->EditCustomAttributes = "";
			$this->Nome_da_Mae->EditValue = ew_HtmlEncode($this->Nome_da_Mae->CurrentValue);

			// Data_Casamento
			$this->Data_Casamento->EditAttrs["class"] = "form-control";
			$this->Data_Casamento->EditCustomAttributes = "";
			$this->Data_Casamento->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->Data_Casamento->CurrentValue, 7));

			// Conjuge
			$this->Conjuge->EditAttrs["class"] = "form-control";
			$this->Conjuge->EditCustomAttributes = "";
			$this->Conjuge->EditValue = ew_HtmlEncode($this->Conjuge->CurrentValue);

			// N_Filhos
			$this->N_Filhos->EditAttrs["class"] = "form-control";
			$this->N_Filhos->EditCustomAttributes = "";
			$this->N_Filhos->EditValue = ew_HtmlEncode($this->N_Filhos->CurrentValue);

			// Empresa_trabalha
			$this->Empresa_trabalha->EditAttrs["class"] = "form-control";
			$this->Empresa_trabalha->EditCustomAttributes = "";
			$this->Empresa_trabalha->EditValue = ew_HtmlEncode($this->Empresa_trabalha->CurrentValue);

			// Fone_Empresa
			$this->Fone_Empresa->EditAttrs["class"] = "form-control";
			$this->Fone_Empresa->EditCustomAttributes = "";
			$this->Fone_Empresa->EditValue = ew_HtmlEncode($this->Fone_Empresa->CurrentValue);

			// Anotacoes
			$this->Anotacoes->EditAttrs["class"] = "form-control";
			$this->Anotacoes->EditCustomAttributes = "";
			$this->Anotacoes->EditValue = ew_HtmlEncode($this->Anotacoes->CurrentValue);

			// Celula
			$this->Celula->EditAttrs["class"] = "form-control";
			$this->Celula->EditCustomAttributes = "";
			if ($this->Celula->getSessionValue() <> "") {
				$this->Celula->CurrentValue = $this->Celula->getSessionValue();
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
			} else {
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
			}

			// Nome_da_Familia
			$this->Nome_da_Familia->EditAttrs["class"] = "form-control";
			$this->Nome_da_Familia->EditCustomAttributes = "";
			$this->Nome_da_Familia->EditValue = ew_HtmlEncode($this->Nome_da_Familia->CurrentValue);

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

			// Data_batismo
			$this->Data_batismo->EditAttrs["class"] = "form-control";
			$this->Data_batismo->EditCustomAttributes = "";
			$this->Data_batismo->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->Data_batismo->CurrentValue, 7));

			// Da_Igreja
			$this->Da_Igreja->EditAttrs["class"] = "form-control";
			$this->Da_Igreja->EditCustomAttributes = "";
			if ($this->Da_Igreja->getSessionValue() <> "") {
				$this->Da_Igreja->CurrentValue = $this->Da_Igreja->getSessionValue();
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
			} else {
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
			}

			// CargoMinisterial
			$this->CargoMinisterial->EditAttrs["class"] = "form-control";
			$this->CargoMinisterial->EditCustomAttributes = "";
			if ($this->CargoMinisterial->getSessionValue() <> "") {
				$this->CargoMinisterial->CurrentValue = $this->CargoMinisterial->getSessionValue();
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
			} else {
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
			}

			// Admissao
			$this->Admissao->EditAttrs["class"] = "form-control";
			$this->Admissao->EditCustomAttributes = "";
			$this->Admissao->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->Admissao->CurrentValue, 7));

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

			// Edit refer script
			// Foto

			if (!ew_Empty($this->Foto->Upload->DbValue)) {
				$this->Foto->HrefValue = ew_UploadPathEx(FALSE, $this->Foto->UploadPath) . $this->Foto->Upload->DbValue; // Add prefix/suffix
				$this->Foto->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->Foto->HrefValue = ew_ConvertFullUrl($this->Foto->HrefValue);
			} else {
				$this->Foto->HrefValue = "";
			}
			$this->Foto->HrefValue2 = $this->Foto->UploadPath . $this->Foto->Upload->DbValue;

			// Matricula
			$this->Matricula->HrefValue = "";

			// Nome
			$this->Nome->HrefValue = "";

			// Sexo
			$this->Sexo->HrefValue = "";

			// DataNasc
			$this->DataNasc->HrefValue = "";

			// Nacionalidade
			$this->Nacionalidade->HrefValue = "";

			// EstadoCivil
			$this->EstadoCivil->HrefValue = "";

			// CPF
			$this->CPF->HrefValue = "";

			// RG
			$this->RG->HrefValue = "";

			// Profissao
			$this->Profissao->HrefValue = "";

			// Email
			$this->_Email->HrefValue = "";

			// TelefoneRes
			$this->TelefoneRes->HrefValue = "";

			// Celular_1
			$this->Celular_1->HrefValue = "";

			// Celular_2
			$this->Celular_2->HrefValue = "";

			// Endereco
			$this->Endereco->HrefValue = "";

			// Complemento
			$this->Complemento->HrefValue = "";

			// Bairro
			$this->Bairro->HrefValue = "";

			// Cidade
			$this->Cidade->HrefValue = "";

			// UF
			$this->UF->HrefValue = "";

			// CEP
			$this->CEP->HrefValue = "";

			// GrauEscolaridade
			$this->GrauEscolaridade->HrefValue = "";

			// Curso
			$this->Curso->HrefValue = "";

			// Nome_do_Pai
			$this->Nome_do_Pai->HrefValue = "";

			// Nome_da_Mae
			$this->Nome_da_Mae->HrefValue = "";

			// Data_Casamento
			$this->Data_Casamento->HrefValue = "";

			// Conjuge
			$this->Conjuge->HrefValue = "";

			// N_Filhos
			$this->N_Filhos->HrefValue = "";

			// Empresa_trabalha
			$this->Empresa_trabalha->HrefValue = "";

			// Fone_Empresa
			$this->Fone_Empresa->HrefValue = "";

			// Anotacoes
			$this->Anotacoes->HrefValue = "";

			// Celula
			$this->Celula->HrefValue = "";

			// Nome_da_Familia
			$this->Nome_da_Familia->HrefValue = "";

			// Situacao
			$this->Situacao->HrefValue = "";

			// Data_batismo
			$this->Data_batismo->HrefValue = "";

			// Da_Igreja
			$this->Da_Igreja->HrefValue = "";

			// CargoMinisterial
			$this->CargoMinisterial->HrefValue = "";

			// Admissao
			$this->Admissao->HrefValue = "";

			// Tipo_Admissao
			$this->Tipo_Admissao->HrefValue = "";

			// Funcao
			$this->Funcao->HrefValue = "";

			// Rede_Ministerial
			$this->Rede_Ministerial->HrefValue = "";
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

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->Nome->FldIsDetailKey && !is_null($this->Nome->FormValue) && $this->Nome->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Nome->FldCaption(), $this->Nome->ReqErrMsg));
		}
		if ($this->Sexo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Sexo->FldCaption(), $this->Sexo->ReqErrMsg));
		}
		if (!$this->DataNasc->FldIsDetailKey && !is_null($this->DataNasc->FormValue) && $this->DataNasc->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->DataNasc->FldCaption(), $this->DataNasc->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->DataNasc->FormValue)) {
			ew_AddMessage($gsFormError, $this->DataNasc->FldErrMsg());
		}
		if (!$this->Nacionalidade->FldIsDetailKey && !is_null($this->Nacionalidade->FormValue) && $this->Nacionalidade->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Nacionalidade->FldCaption(), $this->Nacionalidade->ReqErrMsg));
		}
		if (!$this->EstadoCivil->FldIsDetailKey && !is_null($this->EstadoCivil->FormValue) && $this->EstadoCivil->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->EstadoCivil->FldCaption(), $this->EstadoCivil->ReqErrMsg));
		}
		if (!$this->CPF->FldIsDetailKey && !is_null($this->CPF->FormValue) && $this->CPF->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->CPF->FldCaption(), $this->CPF->ReqErrMsg));
		}
		if (!$this->Endereco->FldIsDetailKey && !is_null($this->Endereco->FormValue) && $this->Endereco->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Endereco->FldCaption(), $this->Endereco->ReqErrMsg));
		}
		if (!$this->Bairro->FldIsDetailKey && !is_null($this->Bairro->FormValue) && $this->Bairro->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Bairro->FldCaption(), $this->Bairro->ReqErrMsg));
		}
		if (!$this->Cidade->FldIsDetailKey && !is_null($this->Cidade->FormValue) && $this->Cidade->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Cidade->FldCaption(), $this->Cidade->ReqErrMsg));
		}
		if (!$this->UF->FldIsDetailKey && !is_null($this->UF->FormValue) && $this->UF->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->UF->FldCaption(), $this->UF->ReqErrMsg));
		}
		if (!$this->CEP->FldIsDetailKey && !is_null($this->CEP->FormValue) && $this->CEP->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->CEP->FldCaption(), $this->CEP->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->Data_Casamento->FormValue)) {
			ew_AddMessage($gsFormError, $this->Data_Casamento->FldErrMsg());
		}
		if (!$this->Situacao->FldIsDetailKey && !is_null($this->Situacao->FormValue) && $this->Situacao->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Situacao->FldCaption(), $this->Situacao->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->Data_batismo->FormValue)) {
			ew_AddMessage($gsFormError, $this->Data_batismo->FldErrMsg());
		}
		if (!$this->Da_Igreja->FldIsDetailKey && !is_null($this->Da_Igreja->FormValue) && $this->Da_Igreja->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Da_Igreja->FldCaption(), $this->Da_Igreja->ReqErrMsg));
		}
		if (!$this->CargoMinisterial->FldIsDetailKey && !is_null($this->CargoMinisterial->FormValue) && $this->CargoMinisterial->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->CargoMinisterial->FldCaption(), $this->CargoMinisterial->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->Admissao->FormValue)) {
			ew_AddMessage($gsFormError, $this->Admissao->FldErrMsg());
		}
		if (!$this->Tipo_Admissao->FldIsDetailKey && !is_null($this->Tipo_Admissao->FormValue) && $this->Tipo_Admissao->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Tipo_Admissao->FldCaption(), $this->Tipo_Admissao->ReqErrMsg));
		}
		if (!$this->Funcao->FldIsDetailKey && !is_null($this->Funcao->FormValue) && $this->Funcao->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Funcao->FldCaption(), $this->Funcao->ReqErrMsg));
		}
		if (!$this->Rede_Ministerial->FldIsDetailKey && !is_null($this->Rede_Ministerial->FormValue) && $this->Rede_Ministerial->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Rede_Ministerial->FldCaption(), $this->Rede_Ministerial->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("dizimos", $DetailTblVar) && $GLOBALS["dizimos"]->DetailAdd) {
			if (!isset($GLOBALS["dizimos_grid"])) $GLOBALS["dizimos_grid"] = new cdizimos_grid(); // get detail page object
			$GLOBALS["dizimos_grid"]->ValidateGridForm();
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;
		if ($this->CPF->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(CPF = '" . ew_AdjustSql($this->CPF->CurrentValue) . "')";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->CPF->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->CPF->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}
		if ($this->_Email->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(Email = '" . ew_AdjustSql($this->_Email->CurrentValue) . "')";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->_Email->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->_Email->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}

		// Check referential integrity for master table 'cargosministeriais'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_cargosministeriais();
		if (strval($this->CargoMinisterial->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@id_cgm@", ew_AdjustSql($this->CargoMinisterial->CurrentValue), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			$rsmaster = $GLOBALS["cargosministeriais"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "cargosministeriais", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}

		// Check referential integrity for master table 'igrejas'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_igrejas();
		if (strval($this->Da_Igreja->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@Id_igreja@", ew_AdjustSql($this->Da_Igreja->CurrentValue), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			$rsmaster = $GLOBALS["igrejas"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "igrejas", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// Foto
		if (!$this->Foto->Upload->KeepFile) {
			$this->Foto->Upload->DbValue = ""; // No need to delete old file
			if ($this->Foto->Upload->FileName == "") {
				$rsnew['Foto'] = NULL;
			} else {
				$rsnew['Foto'] = $this->Foto->Upload->FileName;
			}
			$this->Foto->ImageWidth = 300; // Resize width
			$this->Foto->ImageHeight = 400; // Resize height
		}

		// Matricula
		$this->Matricula->SetDbValueDef($rsnew, $this->Matricula->CurrentValue, NULL, FALSE);

		// Nome
		$this->Nome->SetDbValueDef($rsnew, $this->Nome->CurrentValue, NULL, FALSE);

		// Sexo
		$this->Sexo->SetDbValueDef($rsnew, $this->Sexo->CurrentValue, NULL, strval($this->Sexo->CurrentValue) == "");

		// DataNasc
		$this->DataNasc->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->DataNasc->CurrentValue, 7), NULL, FALSE);

		// Nacionalidade
		$this->Nacionalidade->SetDbValueDef($rsnew, $this->Nacionalidade->CurrentValue, NULL, strval($this->Nacionalidade->CurrentValue) == "");

		// EstadoCivil
		$this->EstadoCivil->SetDbValueDef($rsnew, $this->EstadoCivil->CurrentValue, NULL, strval($this->EstadoCivil->CurrentValue) == "");

		// CPF
		$this->CPF->SetDbValueDef($rsnew, $this->CPF->CurrentValue, NULL, FALSE);

		// RG
		$this->RG->SetDbValueDef($rsnew, $this->RG->CurrentValue, NULL, FALSE);

		// Profissao
		$this->Profissao->SetDbValueDef($rsnew, $this->Profissao->CurrentValue, NULL, FALSE);

		// Email
		$this->_Email->SetDbValueDef($rsnew, $this->_Email->CurrentValue, NULL, FALSE);

		// TelefoneRes
		$this->TelefoneRes->SetDbValueDef($rsnew, $this->TelefoneRes->CurrentValue, NULL, FALSE);

		// Celular_1
		$this->Celular_1->SetDbValueDef($rsnew, $this->Celular_1->CurrentValue, NULL, FALSE);

		// Celular_2
		$this->Celular_2->SetDbValueDef($rsnew, $this->Celular_2->CurrentValue, NULL, FALSE);

		// Endereco
		$this->Endereco->SetDbValueDef($rsnew, $this->Endereco->CurrentValue, NULL, FALSE);

		// Complemento
		$this->Complemento->SetDbValueDef($rsnew, $this->Complemento->CurrentValue, NULL, FALSE);

		// Bairro
		$this->Bairro->SetDbValueDef($rsnew, $this->Bairro->CurrentValue, NULL, FALSE);

		// Cidade
		$this->Cidade->SetDbValueDef($rsnew, $this->Cidade->CurrentValue, NULL, FALSE);

		// UF
		$this->UF->SetDbValueDef($rsnew, $this->UF->CurrentValue, NULL, FALSE);

		// CEP
		$this->CEP->SetDbValueDef($rsnew, $this->CEP->CurrentValue, NULL, FALSE);

		// GrauEscolaridade
		$this->GrauEscolaridade->SetDbValueDef($rsnew, $this->GrauEscolaridade->CurrentValue, NULL, FALSE);

		// Curso
		$this->Curso->SetDbValueDef($rsnew, $this->Curso->CurrentValue, NULL, FALSE);

		// Nome_do_Pai
		$this->Nome_do_Pai->SetDbValueDef($rsnew, $this->Nome_do_Pai->CurrentValue, NULL, FALSE);

		// Nome_da_Mae
		$this->Nome_da_Mae->SetDbValueDef($rsnew, $this->Nome_da_Mae->CurrentValue, NULL, FALSE);

		// Data_Casamento
		$this->Data_Casamento->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->Data_Casamento->CurrentValue, 7), NULL, FALSE);

		// Conjuge
		$this->Conjuge->SetDbValueDef($rsnew, $this->Conjuge->CurrentValue, NULL, FALSE);

		// N_Filhos
		$this->N_Filhos->SetDbValueDef($rsnew, $this->N_Filhos->CurrentValue, NULL, FALSE);

		// Empresa_trabalha
		$this->Empresa_trabalha->SetDbValueDef($rsnew, $this->Empresa_trabalha->CurrentValue, NULL, FALSE);

		// Fone_Empresa
		$this->Fone_Empresa->SetDbValueDef($rsnew, $this->Fone_Empresa->CurrentValue, NULL, FALSE);

		// Anotacoes
		$this->Anotacoes->SetDbValueDef($rsnew, $this->Anotacoes->CurrentValue, NULL, FALSE);

		// Celula
		$this->Celula->SetDbValueDef($rsnew, $this->Celula->CurrentValue, NULL, FALSE);

		// Nome_da_Familia
		$this->Nome_da_Familia->SetDbValueDef($rsnew, $this->Nome_da_Familia->CurrentValue, NULL, FALSE);

		// Situacao
		$this->Situacao->SetDbValueDef($rsnew, $this->Situacao->CurrentValue, NULL, FALSE);

		// Data_batismo
		$this->Data_batismo->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->Data_batismo->CurrentValue, 7), NULL, FALSE);

		// Da_Igreja
		$this->Da_Igreja->SetDbValueDef($rsnew, $this->Da_Igreja->CurrentValue, NULL, FALSE);

		// CargoMinisterial
		$this->CargoMinisterial->SetDbValueDef($rsnew, $this->CargoMinisterial->CurrentValue, NULL, FALSE);

		// Admissao
		$this->Admissao->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->Admissao->CurrentValue, 7), NULL, FALSE);

		// Tipo_Admissao
		$this->Tipo_Admissao->SetDbValueDef($rsnew, $this->Tipo_Admissao->CurrentValue, NULL, FALSE);

		// Funcao
		$this->Funcao->SetDbValueDef($rsnew, $this->Funcao->CurrentValue, NULL, FALSE);

		// Rede_Ministerial
		$this->Rede_Ministerial->SetDbValueDef($rsnew, $this->Rede_Ministerial->CurrentValue, NULL, FALSE);
		if (!$this->Foto->Upload->KeepFile) {
			if (!ew_Empty($this->Foto->Upload->Value)) {
				if ($this->Foto->Upload->FileName == $this->Foto->Upload->DbValue) { // Overwrite if same file name
					$this->Foto->Upload->DbValue = ""; // No need to delete any more
				} else {
					$rsnew['Foto'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->Foto->UploadPath), $rsnew['Foto']); // Get new file name
				}
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if (!$this->Foto->Upload->KeepFile) {
					if (!ew_Empty($this->Foto->Upload->Value)) {
						$this->Foto->Upload->Resize($this->Foto->ImageWidth, $this->Foto->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY);
						$this->Foto->Upload->SaveToFile($this->Foto->UploadPath, $rsnew['Foto'], TRUE);
					}
					if ($this->Foto->Upload->DbValue <> "")
						@unlink(ew_UploadPathEx(TRUE, $this->Foto->OldUploadPath) . $this->Foto->Upload->DbValue);
				}
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->Id_membro->setDbValue($conn->Insert_ID());
			$rsnew['Id_membro'] = $this->Id_membro->DbValue;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("dizimos", $DetailTblVar) && $GLOBALS["dizimos"]->DetailAdd) {
				$GLOBALS["dizimos"]->id_discipulo->setSessionValue($this->Id_membro->CurrentValue); // Set master key
				if (!isset($GLOBALS["dizimos_grid"])) $GLOBALS["dizimos_grid"] = new cdizimos_grid(); // Get detail page object
				$AddRow = $GLOBALS["dizimos_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["dizimos"]->id_discipulo->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
			$this->WriteAuditTrailOnAdd($rsnew);
		}

		// Foto
		ew_CleanUploadTempPath($this->Foto, $this->Foto->Upload->Index);
		return $AddRow;
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
				if ($GLOBALS["dizimos_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["dizimos_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["dizimos_grid"]->CurrentMode = "add";
					$GLOBALS["dizimos_grid"]->CurrentAction = "gridadd";

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
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'membro';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'membro';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['Id_membro'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $usr = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
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

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		$header = utf8_encode("<div class='alert alert-warning'><div class='label bg-red'>Atenção</div> Ao Adicionar um novo cadastro, é possivel inserir a foto por upload, e ao Editar o cadastro a foto só poderá ser inserida por WebCam.</div>");
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
if (!isset($membro_add)) $membro_add = new cmembro_add();

// Page init
$membro_add->Page_Init();

// Page main
$membro_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$membro_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var membro_add = new ew_Page("membro_add");
membro_add.PageID = "add"; // Page ID
var EW_PAGE_ID = membro_add.PageID; // For backward compatibility

// Form object
var fmembroadd = new ew_Form("fmembroadd");

// Validate form
fmembroadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_Nome");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->Nome->FldCaption(), $membro->Nome->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Sexo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->Sexo->FldCaption(), $membro->Sexo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_DataNasc");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->DataNasc->FldCaption(), $membro->DataNasc->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_DataNasc");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($membro->DataNasc->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Nacionalidade");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->Nacionalidade->FldCaption(), $membro->Nacionalidade->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_EstadoCivil");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->EstadoCivil->FldCaption(), $membro->EstadoCivil->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_CPF");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->CPF->FldCaption(), $membro->CPF->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Endereco");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->Endereco->FldCaption(), $membro->Endereco->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Bairro");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->Bairro->FldCaption(), $membro->Bairro->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Cidade");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->Cidade->FldCaption(), $membro->Cidade->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_UF");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->UF->FldCaption(), $membro->UF->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_CEP");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->CEP->FldCaption(), $membro->CEP->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Data_Casamento");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($membro->Data_Casamento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Situacao");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->Situacao->FldCaption(), $membro->Situacao->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Data_batismo");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($membro->Data_batismo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Da_Igreja");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->Da_Igreja->FldCaption(), $membro->Da_Igreja->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_CargoMinisterial");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->CargoMinisterial->FldCaption(), $membro->CargoMinisterial->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Admissao");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($membro->Admissao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Tipo_Admissao");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->Tipo_Admissao->FldCaption(), $membro->Tipo_Admissao->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Funcao");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->Funcao->FldCaption(), $membro->Funcao->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Rede_Ministerial");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->Rede_Ministerial->FldCaption(), $membro->Rede_Ministerial->ReqErrMsg)) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fmembroadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmembroadd.ValidateRequired = true;
<?php } else { ?>
fmembroadd.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
fmembroadd.MultiPage = new ew_MultiPage("fmembroadd",
	[["x_Foto",5],["x_Matricula",1],["x_Nome",1],["x_Sexo",1],["x_DataNasc",1],["x_Nacionalidade",1],["x_EstadoCivil",1],["x_CPF",1],["x_RG",1],["x_Profissao",1],["x__Email",1],["x_TelefoneRes",1],["x_Celular_1",1],["x_Celular_2",1],["x_Endereco",2],["x_Complemento",2],["x_Bairro",2],["x_Cidade",2],["x_UF",2],["x_CEP",2],["x_GrauEscolaridade",2],["x_Curso",2],["x_Nome_do_Pai",3],["x_Nome_da_Mae",3],["x_Data_Casamento",3],["x_Conjuge",3],["x_N_Filhos",3],["x_Empresa_trabalha",3],["x_Fone_Empresa",3],["x_Anotacoes",3],["x_Celula",4],["x_Nome_da_Familia",4],["x_Situacao",4],["x_Data_batismo",4],["x_Da_Igreja",4],["x_CargoMinisterial",4],["x_Admissao",4],["x_Tipo_Admissao",4],["x_Funcao",4],["x_Rede_Ministerial",4]]
);

// Dynamic selection lists
fmembroadd.Lists["x_GrauEscolaridade"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Escolaridade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembroadd.Lists["x_Celula"] = {"LinkField":"x_Id_celula","Ajax":null,"AutoFill":false,"DisplayFields":["x_NomeCelula","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembroadd.Lists["x_Situacao"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Situacao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembroadd.Lists["x_Da_Igreja"] = {"LinkField":"x_Id_igreja","Ajax":null,"AutoFill":false,"DisplayFields":["x_Igreja","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembroadd.Lists["x_CargoMinisterial"] = {"LinkField":"x_id_cgm","Ajax":null,"AutoFill":false,"DisplayFields":["x_Cargo_Ministerial","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembroadd.Lists["x_Tipo_Admissao"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Tipo_Admissao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembroadd.Lists["x_Funcao"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Funcao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembroadd.Lists["x_Rede_Ministerial"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Rede_Ministerial","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $membro_add->ShowPageHeader(); ?>
<?php
$membro_add->ShowMessage();
?>
<form name="fmembroadd" id="fmembroadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($membro_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $membro_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="membro">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<div class="tabbable" id="membro_add">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_membro1" data-toggle="tab"><?php echo $membro->PageCaption(1) ?></a></li>
		<li><a href="#tab_membro2" data-toggle="tab"><?php echo $membro->PageCaption(2) ?></a></li>
		<li><a href="#tab_membro3" data-toggle="tab"><?php echo $membro->PageCaption(3) ?></a></li>
		<li><a href="#tab_membro4" data-toggle="tab"><?php echo $membro->PageCaption(4) ?></a></li>
		<li><a href="#tab_membro5" data-toggle="tab"><?php echo $membro->PageCaption(5) ?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_membro1">
<div>
<?php if ($membro->Matricula->Visible) { // Matricula ?>
	<div id="r_Matricula" class="form-group">
		<label id="elh_membro_Matricula" for="x_Matricula" class="col-sm-2 control-label ewLabel"><?php echo $membro->Matricula->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Matricula->CellAttributes() ?>>
<span id="el_membro_Matricula">
<input type="text" data-field="x_Matricula" name="x_Matricula" id="x_Matricula" size="30" maxlength="20" value="<?php echo $membro->Matricula->EditValue ?>"<?php echo $membro->Matricula->EditAttributes() ?>>
</span>
<?php echo $membro->Matricula->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Nome->Visible) { // Nome ?>
	<div id="r_Nome" class="form-group">
		<label id="elh_membro_Nome" for="x_Nome" class="col-sm-2 control-label ewLabel"><?php echo $membro->Nome->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Nome->CellAttributes() ?>>
<span id="el_membro_Nome">
<input type="text" data-field="x_Nome" name="x_Nome" id="x_Nome" size="65" maxlength="60" value="<?php echo $membro->Nome->EditValue ?>"<?php echo $membro->Nome->EditAttributes() ?>>
</span>
<?php echo $membro->Nome->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Sexo->Visible) { // Sexo ?>
	<div id="r_Sexo" class="form-group">
		<label id="elh_membro_Sexo" class="col-sm-2 control-label ewLabel"><?php echo $membro->Sexo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Sexo->CellAttributes() ?>>
<span id="el_membro_Sexo">
<div id="tp_x_Sexo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_Sexo" id="x_Sexo" value="{value}"<?php echo $membro->Sexo->EditAttributes() ?>></div>
<div id="dsl_x_Sexo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $membro->Sexo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($membro->Sexo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
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
<?php echo $membro->Sexo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->DataNasc->Visible) { // DataNasc ?>
	<div id="r_DataNasc" class="form-group">
		<label id="elh_membro_DataNasc" for="x_DataNasc" class="col-sm-2 control-label ewLabel"><?php echo $membro->DataNasc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $membro->DataNasc->CellAttributes() ?>>
<span id="el_membro_DataNasc">
<input type="text" data-field="x_DataNasc" name="x_DataNasc" id="x_DataNasc" size="14" value="<?php echo $membro->DataNasc->EditValue ?>"<?php echo $membro->DataNasc->EditAttributes() ?>>
</span>
<?php echo $membro->DataNasc->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Nacionalidade->Visible) { // Nacionalidade ?>
	<div id="r_Nacionalidade" class="form-group">
		<label id="elh_membro_Nacionalidade" for="x_Nacionalidade" class="col-sm-2 control-label ewLabel"><?php echo $membro->Nacionalidade->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Nacionalidade->CellAttributes() ?>>
<span id="el_membro_Nacionalidade">
<input type="text" data-field="x_Nacionalidade" name="x_Nacionalidade" id="x_Nacionalidade" size="15" maxlength="30" value="<?php echo $membro->Nacionalidade->EditValue ?>"<?php echo $membro->Nacionalidade->EditAttributes() ?>>
</span>
<?php echo $membro->Nacionalidade->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->EstadoCivil->Visible) { // EstadoCivil ?>
	<div id="r_EstadoCivil" class="form-group">
		<label id="elh_membro_EstadoCivil" for="x_EstadoCivil" class="col-sm-2 control-label ewLabel"><?php echo $membro->EstadoCivil->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $membro->EstadoCivil->CellAttributes() ?>>
<span id="el_membro_EstadoCivil">
<select data-field="x_EstadoCivil" id="x_EstadoCivil" name="x_EstadoCivil"<?php echo $membro->EstadoCivil->EditAttributes() ?>>
<?php
if (is_array($membro->EstadoCivil->EditValue)) {
	$arwrk = $membro->EstadoCivil->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($membro->EstadoCivil->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $membro->EstadoCivil->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->CPF->Visible) { // CPF ?>
	<div id="r_CPF" class="form-group">
		<label id="elh_membro_CPF" for="x_CPF" class="col-sm-2 control-label ewLabel"><?php echo $membro->CPF->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $membro->CPF->CellAttributes() ?>>
<span id="el_membro_CPF">
<input type="text" data-field="x_CPF" name="x_CPF" id="x_CPF" size="30" maxlength="15" value="<?php echo $membro->CPF->EditValue ?>"<?php echo $membro->CPF->EditAttributes() ?>>
</span>
<?php echo $membro->CPF->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->RG->Visible) { // RG ?>
	<div id="r_RG" class="form-group">
		<label id="elh_membro_RG" for="x_RG" class="col-sm-2 control-label ewLabel"><?php echo $membro->RG->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->RG->CellAttributes() ?>>
<span id="el_membro_RG">
<input type="text" data-field="x_RG" name="x_RG" id="x_RG" size="30" maxlength="15" value="<?php echo $membro->RG->EditValue ?>"<?php echo $membro->RG->EditAttributes() ?>>
</span>
<?php echo $membro->RG->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Profissao->Visible) { // Profissao ?>
	<div id="r_Profissao" class="form-group">
		<label id="elh_membro_Profissao" for="x_Profissao" class="col-sm-2 control-label ewLabel"><?php echo $membro->Profissao->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Profissao->CellAttributes() ?>>
<span id="el_membro_Profissao">
<input type="text" data-field="x_Profissao" name="x_Profissao" id="x_Profissao" size="30" maxlength="60" value="<?php echo $membro->Profissao->EditValue ?>"<?php echo $membro->Profissao->EditAttributes() ?>>
</span>
<?php echo $membro->Profissao->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->_Email->Visible) { // Email ?>
	<div id="r__Email" class="form-group">
		<label id="elh_membro__Email" for="x__Email" class="col-sm-2 control-label ewLabel"><?php echo $membro->_Email->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->_Email->CellAttributes() ?>>
<span id="el_membro__Email">
<input type="text" data-field="x__Email" name="x__Email" id="x__Email" size="30" maxlength="50" value="<?php echo $membro->_Email->EditValue ?>"<?php echo $membro->_Email->EditAttributes() ?>>
</span>
<?php echo $membro->_Email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->TelefoneRes->Visible) { // TelefoneRes ?>
	<div id="r_TelefoneRes" class="form-group">
		<label id="elh_membro_TelefoneRes" for="x_TelefoneRes" class="col-sm-2 control-label ewLabel"><?php echo $membro->TelefoneRes->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->TelefoneRes->CellAttributes() ?>>
<span id="el_membro_TelefoneRes">
<input type="text" data-field="x_TelefoneRes" name="x_TelefoneRes" id="x_TelefoneRes" size="30" maxlength="15" value="<?php echo $membro->TelefoneRes->EditValue ?>"<?php echo $membro->TelefoneRes->EditAttributes() ?>>
</span>
<?php echo $membro->TelefoneRes->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Celular_1->Visible) { // Celular_1 ?>
	<div id="r_Celular_1" class="form-group">
		<label id="elh_membro_Celular_1" for="x_Celular_1" class="col-sm-2 control-label ewLabel"><?php echo $membro->Celular_1->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Celular_1->CellAttributes() ?>>
<span id="el_membro_Celular_1">
<input type="text" data-field="x_Celular_1" name="x_Celular_1" id="x_Celular_1" size="30" maxlength="15" value="<?php echo $membro->Celular_1->EditValue ?>"<?php echo $membro->Celular_1->EditAttributes() ?>>
</span>
<?php echo $membro->Celular_1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Celular_2->Visible) { // Celular_2 ?>
	<div id="r_Celular_2" class="form-group">
		<label id="elh_membro_Celular_2" for="x_Celular_2" class="col-sm-2 control-label ewLabel"><?php echo $membro->Celular_2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Celular_2->CellAttributes() ?>>
<span id="el_membro_Celular_2">
<input type="text" data-field="x_Celular_2" name="x_Celular_2" id="x_Celular_2" size="30" maxlength="15" value="<?php echo $membro->Celular_2->EditValue ?>"<?php echo $membro->Celular_2->EditAttributes() ?>>
</span>
<?php echo $membro->Celular_2->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
		</div>
		<div class="tab-pane" id="tab_membro2">
<div>
<?php if ($membro->Endereco->Visible) { // Endereco ?>
	<div id="r_Endereco" class="form-group">
		<label id="elh_membro_Endereco" for="x_Endereco" class="col-sm-2 control-label ewLabel"><?php echo $membro->Endereco->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Endereco->CellAttributes() ?>>
<span id="el_membro_Endereco">
<input type="text" data-field="x_Endereco" name="x_Endereco" id="x_Endereco" size="70" maxlength="60" value="<?php echo $membro->Endereco->EditValue ?>"<?php echo $membro->Endereco->EditAttributes() ?>>
</span>
<?php echo $membro->Endereco->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Complemento->Visible) { // Complemento ?>
	<div id="r_Complemento" class="form-group">
		<label id="elh_membro_Complemento" for="x_Complemento" class="col-sm-2 control-label ewLabel"><?php echo $membro->Complemento->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Complemento->CellAttributes() ?>>
<span id="el_membro_Complemento">
<input type="text" data-field="x_Complemento" name="x_Complemento" id="x_Complemento" size="30" maxlength="255" value="<?php echo $membro->Complemento->EditValue ?>"<?php echo $membro->Complemento->EditAttributes() ?>>
</span>
<?php echo $membro->Complemento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Bairro->Visible) { // Bairro ?>
	<div id="r_Bairro" class="form-group">
		<label id="elh_membro_Bairro" for="x_Bairro" class="col-sm-2 control-label ewLabel"><?php echo $membro->Bairro->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Bairro->CellAttributes() ?>>
<span id="el_membro_Bairro">
<input type="text" data-field="x_Bairro" name="x_Bairro" id="x_Bairro" size="30" maxlength="30" value="<?php echo $membro->Bairro->EditValue ?>"<?php echo $membro->Bairro->EditAttributes() ?>>
</span>
<?php echo $membro->Bairro->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Cidade->Visible) { // Cidade ?>
	<div id="r_Cidade" class="form-group">
		<label id="elh_membro_Cidade" for="x_Cidade" class="col-sm-2 control-label ewLabel"><?php echo $membro->Cidade->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Cidade->CellAttributes() ?>>
<span id="el_membro_Cidade">
<input type="text" data-field="x_Cidade" name="x_Cidade" id="x_Cidade" size="30" maxlength="30" value="<?php echo $membro->Cidade->EditValue ?>"<?php echo $membro->Cidade->EditAttributes() ?>>
</span>
<?php echo $membro->Cidade->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->UF->Visible) { // UF ?>
	<div id="r_UF" class="form-group">
		<label id="elh_membro_UF" for="x_UF" class="col-sm-2 control-label ewLabel"><?php echo $membro->UF->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $membro->UF->CellAttributes() ?>>
<span id="el_membro_UF">
<input type="text" data-field="x_UF" name="x_UF" id="x_UF" size="5" maxlength="2" value="<?php echo $membro->UF->EditValue ?>"<?php echo $membro->UF->EditAttributes() ?>>
</span>
<?php echo $membro->UF->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->CEP->Visible) { // CEP ?>
	<div id="r_CEP" class="form-group">
		<label id="elh_membro_CEP" for="x_CEP" class="col-sm-2 control-label ewLabel"><?php echo $membro->CEP->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $membro->CEP->CellAttributes() ?>>
<span id="el_membro_CEP">
<input type="text" data-field="x_CEP" name="x_CEP" id="x_CEP" size="10" maxlength="9" value="<?php echo $membro->CEP->EditValue ?>"<?php echo $membro->CEP->EditAttributes() ?>>
</span>
<?php echo $membro->CEP->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->GrauEscolaridade->Visible) { // GrauEscolaridade ?>
	<div id="r_GrauEscolaridade" class="form-group">
		<label id="elh_membro_GrauEscolaridade" for="x_GrauEscolaridade" class="col-sm-2 control-label ewLabel"><?php echo $membro->GrauEscolaridade->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->GrauEscolaridade->CellAttributes() ?>>
<span id="el_membro_GrauEscolaridade">
<select data-field="x_GrauEscolaridade" id="x_GrauEscolaridade" name="x_GrauEscolaridade"<?php echo $membro->GrauEscolaridade->EditAttributes() ?>>
<?php
if (is_array($membro->GrauEscolaridade->EditValue)) {
	$arwrk = $membro->GrauEscolaridade->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($membro->GrauEscolaridade->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fmembroadd.Lists["x_GrauEscolaridade"].Options = <?php echo (is_array($membro->GrauEscolaridade->EditValue)) ? ew_ArrayToJson($membro->GrauEscolaridade->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $membro->GrauEscolaridade->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Curso->Visible) { // Curso ?>
	<div id="r_Curso" class="form-group">
		<label id="elh_membro_Curso" for="x_Curso" class="col-sm-2 control-label ewLabel"><?php echo $membro->Curso->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Curso->CellAttributes() ?>>
<span id="el_membro_Curso">
<input type="text" data-field="x_Curso" name="x_Curso" id="x_Curso" size="30" maxlength="50" value="<?php echo $membro->Curso->EditValue ?>"<?php echo $membro->Curso->EditAttributes() ?>>
</span>
<?php echo $membro->Curso->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
		</div>
		<div class="tab-pane" id="tab_membro3">
<div>
<?php if ($membro->Nome_do_Pai->Visible) { // Nome_do_Pai ?>
	<div id="r_Nome_do_Pai" class="form-group">
		<label id="elh_membro_Nome_do_Pai" for="x_Nome_do_Pai" class="col-sm-2 control-label ewLabel"><?php echo $membro->Nome_do_Pai->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Nome_do_Pai->CellAttributes() ?>>
<span id="el_membro_Nome_do_Pai">
<input type="text" data-field="x_Nome_do_Pai" name="x_Nome_do_Pai" id="x_Nome_do_Pai" size="55" maxlength="100" value="<?php echo $membro->Nome_do_Pai->EditValue ?>"<?php echo $membro->Nome_do_Pai->EditAttributes() ?>>
</span>
<?php echo $membro->Nome_do_Pai->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Nome_da_Mae->Visible) { // Nome_da_Mae ?>
	<div id="r_Nome_da_Mae" class="form-group">
		<label id="elh_membro_Nome_da_Mae" for="x_Nome_da_Mae" class="col-sm-2 control-label ewLabel"><?php echo $membro->Nome_da_Mae->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Nome_da_Mae->CellAttributes() ?>>
<span id="el_membro_Nome_da_Mae">
<input type="text" data-field="x_Nome_da_Mae" name="x_Nome_da_Mae" id="x_Nome_da_Mae" size="55" maxlength="255" value="<?php echo $membro->Nome_da_Mae->EditValue ?>"<?php echo $membro->Nome_da_Mae->EditAttributes() ?>>
</span>
<?php echo $membro->Nome_da_Mae->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Data_Casamento->Visible) { // Data_Casamento ?>
	<div id="r_Data_Casamento" class="form-group">
		<label id="elh_membro_Data_Casamento" for="x_Data_Casamento" class="col-sm-2 control-label ewLabel"><?php echo $membro->Data_Casamento->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Data_Casamento->CellAttributes() ?>>
<span id="el_membro_Data_Casamento">
<input type="text" data-field="x_Data_Casamento" name="x_Data_Casamento" id="x_Data_Casamento" size="15" value="<?php echo $membro->Data_Casamento->EditValue ?>"<?php echo $membro->Data_Casamento->EditAttributes() ?>>
<?php if (!$membro->Data_Casamento->ReadOnly && !$membro->Data_Casamento->Disabled && @$membro->Data_Casamento->EditAttrs["readonly"] == "" && @$membro->Data_Casamento->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fmembroadd", "x_Data_Casamento", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $membro->Data_Casamento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Conjuge->Visible) { // Conjuge ?>
	<div id="r_Conjuge" class="form-group">
		<label id="elh_membro_Conjuge" for="x_Conjuge" class="col-sm-2 control-label ewLabel"><?php echo $membro->Conjuge->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Conjuge->CellAttributes() ?>>
<span id="el_membro_Conjuge">
<input type="text" data-field="x_Conjuge" name="x_Conjuge" id="x_Conjuge" size="55" maxlength="80" value="<?php echo $membro->Conjuge->EditValue ?>"<?php echo $membro->Conjuge->EditAttributes() ?>>
</span>
<?php echo $membro->Conjuge->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->N_Filhos->Visible) { // N_Filhos ?>
	<div id="r_N_Filhos" class="form-group">
		<label id="elh_membro_N_Filhos" for="x_N_Filhos" class="col-sm-2 control-label ewLabel"><?php echo $membro->N_Filhos->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->N_Filhos->CellAttributes() ?>>
<span id="el_membro_N_Filhos">
<input type="text" data-field="x_N_Filhos" name="x_N_Filhos" id="x_N_Filhos" size="5" maxlength="5" value="<?php echo $membro->N_Filhos->EditValue ?>"<?php echo $membro->N_Filhos->EditAttributes() ?>>
</span>
<?php echo $membro->N_Filhos->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Empresa_trabalha->Visible) { // Empresa_trabalha ?>
	<div id="r_Empresa_trabalha" class="form-group">
		<label id="elh_membro_Empresa_trabalha" for="x_Empresa_trabalha" class="col-sm-2 control-label ewLabel"><?php echo $membro->Empresa_trabalha->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Empresa_trabalha->CellAttributes() ?>>
<span id="el_membro_Empresa_trabalha">
<input type="text" data-field="x_Empresa_trabalha" name="x_Empresa_trabalha" id="x_Empresa_trabalha" size="55" maxlength="100" value="<?php echo $membro->Empresa_trabalha->EditValue ?>"<?php echo $membro->Empresa_trabalha->EditAttributes() ?>>
</span>
<?php echo $membro->Empresa_trabalha->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Fone_Empresa->Visible) { // Fone_Empresa ?>
	<div id="r_Fone_Empresa" class="form-group">
		<label id="elh_membro_Fone_Empresa" for="x_Fone_Empresa" class="col-sm-2 control-label ewLabel"><?php echo $membro->Fone_Empresa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Fone_Empresa->CellAttributes() ?>>
<span id="el_membro_Fone_Empresa">
<input type="text" data-field="x_Fone_Empresa" name="x_Fone_Empresa" id="x_Fone_Empresa" size="15" maxlength="15" value="<?php echo $membro->Fone_Empresa->EditValue ?>"<?php echo $membro->Fone_Empresa->EditAttributes() ?>>
</span>
<?php echo $membro->Fone_Empresa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Anotacoes->Visible) { // Anotacoes ?>
	<div id="r_Anotacoes" class="form-group">
		<label id="elh_membro_Anotacoes" for="x_Anotacoes" class="col-sm-2 control-label ewLabel"><?php echo $membro->Anotacoes->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Anotacoes->CellAttributes() ?>>
<span id="el_membro_Anotacoes">
<textarea data-field="x_Anotacoes" name="x_Anotacoes" id="x_Anotacoes" cols="70" rows="4"<?php echo $membro->Anotacoes->EditAttributes() ?>><?php echo $membro->Anotacoes->EditValue ?></textarea>
</span>
<?php echo $membro->Anotacoes->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
		</div>
		<div class="tab-pane" id="tab_membro4">
<div>
<?php if ($membro->Celula->Visible) { // Celula ?>
	<div id="r_Celula" class="form-group">
		<label id="elh_membro_Celula" for="x_Celula" class="col-sm-2 control-label ewLabel"><?php echo $membro->Celula->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Celula->CellAttributes() ?>>
<?php if ($membro->Celula->getSessionValue() <> "") { ?>
<span id="el_membro_Celula">
<span<?php echo $membro->Celula->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $membro->Celula->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_Celula" name="x_Celula" value="<?php echo ew_HtmlEncode($membro->Celula->CurrentValue) ?>">
<?php } else { ?>
<span id="el_membro_Celula">
<select data-field="x_Celula" id="x_Celula" name="x_Celula"<?php echo $membro->Celula->EditAttributes() ?>>
<?php
if (is_array($membro->Celula->EditValue)) {
	$arwrk = $membro->Celula->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($membro->Celula->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "celulas")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $membro->Celula->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_Celula',url:'celulasaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_Celula"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $membro->Celula->FldCaption() ?></span></button>
<?php } ?>
<script type="text/javascript">
fmembroadd.Lists["x_Celula"].Options = <?php echo (is_array($membro->Celula->EditValue)) ? ew_ArrayToJson($membro->Celula->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php echo $membro->Celula->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Nome_da_Familia->Visible) { // Nome_da_Familia ?>
	<div id="r_Nome_da_Familia" class="form-group">
		<label id="elh_membro_Nome_da_Familia" for="x_Nome_da_Familia" class="col-sm-2 control-label ewLabel"><?php echo $membro->Nome_da_Familia->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Nome_da_Familia->CellAttributes() ?>>
<span id="el_membro_Nome_da_Familia">
<input type="text" data-field="x_Nome_da_Familia" name="x_Nome_da_Familia" id="x_Nome_da_Familia" size="60" maxlength="100" value="<?php echo $membro->Nome_da_Familia->EditValue ?>"<?php echo $membro->Nome_da_Familia->EditAttributes() ?>>
</span>
<?php echo $membro->Nome_da_Familia->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Situacao->Visible) { // Situacao ?>
	<div id="r_Situacao" class="form-group">
		<label id="elh_membro_Situacao" for="x_Situacao" class="col-sm-2 control-label ewLabel"><?php echo $membro->Situacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Situacao->CellAttributes() ?>>
<span id="el_membro_Situacao">
<select data-field="x_Situacao" id="x_Situacao" name="x_Situacao"<?php echo $membro->Situacao->EditAttributes() ?>>
<?php
if (is_array($membro->Situacao->EditValue)) {
	$arwrk = $membro->Situacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($membro->Situacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "situacao_membro")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $membro->Situacao->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_Situacao',url:'situacao_membroaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_Situacao"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $membro->Situacao->FldCaption() ?></span></button>
<?php } ?>
<script type="text/javascript">
fmembroadd.Lists["x_Situacao"].Options = <?php echo (is_array($membro->Situacao->EditValue)) ? ew_ArrayToJson($membro->Situacao->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $membro->Situacao->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Data_batismo->Visible) { // Data_batismo ?>
	<div id="r_Data_batismo" class="form-group">
		<label id="elh_membro_Data_batismo" for="x_Data_batismo" class="col-sm-2 control-label ewLabel"><?php echo $membro->Data_batismo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Data_batismo->CellAttributes() ?>>
<span id="el_membro_Data_batismo">
<input type="text" data-field="x_Data_batismo" name="x_Data_batismo" id="x_Data_batismo" size="15" value="<?php echo $membro->Data_batismo->EditValue ?>"<?php echo $membro->Data_batismo->EditAttributes() ?>>
<?php if (!$membro->Data_batismo->ReadOnly && !$membro->Data_batismo->Disabled && @$membro->Data_batismo->EditAttrs["readonly"] == "" && @$membro->Data_batismo->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fmembroadd", "x_Data_batismo", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $membro->Data_batismo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Da_Igreja->Visible) { // Da_Igreja ?>
	<div id="r_Da_Igreja" class="form-group">
		<label id="elh_membro_Da_Igreja" for="x_Da_Igreja" class="col-sm-2 control-label ewLabel"><?php echo $membro->Da_Igreja->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Da_Igreja->CellAttributes() ?>>
<?php if ($membro->Da_Igreja->getSessionValue() <> "") { ?>
<span id="el_membro_Da_Igreja">
<span<?php echo $membro->Da_Igreja->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $membro->Da_Igreja->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_Da_Igreja" name="x_Da_Igreja" value="<?php echo ew_HtmlEncode($membro->Da_Igreja->CurrentValue) ?>">
<?php } else { ?>
<span id="el_membro_Da_Igreja">
<select data-field="x_Da_Igreja" id="x_Da_Igreja" name="x_Da_Igreja"<?php echo $membro->Da_Igreja->EditAttributes() ?>>
<?php
if (is_array($membro->Da_Igreja->EditValue)) {
	$arwrk = $membro->Da_Igreja->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($membro->Da_Igreja->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fmembroadd.Lists["x_Da_Igreja"].Options = <?php echo (is_array($membro->Da_Igreja->EditValue)) ? ew_ArrayToJson($membro->Da_Igreja->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php echo $membro->Da_Igreja->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->CargoMinisterial->Visible) { // CargoMinisterial ?>
	<div id="r_CargoMinisterial" class="form-group">
		<label id="elh_membro_CargoMinisterial" for="x_CargoMinisterial" class="col-sm-2 control-label ewLabel"><?php echo $membro->CargoMinisterial->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $membro->CargoMinisterial->CellAttributes() ?>>
<?php if ($membro->CargoMinisterial->getSessionValue() <> "") { ?>
<span id="el_membro_CargoMinisterial">
<span<?php echo $membro->CargoMinisterial->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $membro->CargoMinisterial->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_CargoMinisterial" name="x_CargoMinisterial" value="<?php echo ew_HtmlEncode($membro->CargoMinisterial->CurrentValue) ?>">
<?php } else { ?>
<span id="el_membro_CargoMinisterial">
<select data-field="x_CargoMinisterial" id="x_CargoMinisterial" name="x_CargoMinisterial"<?php echo $membro->CargoMinisterial->EditAttributes() ?>>
<?php
if (is_array($membro->CargoMinisterial->EditValue)) {
	$arwrk = $membro->CargoMinisterial->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($membro->CargoMinisterial->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "cargosministeriais")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $membro->CargoMinisterial->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_CargoMinisterial',url:'cargosministeriaisaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_CargoMinisterial"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $membro->CargoMinisterial->FldCaption() ?></span></button>
<?php } ?>
<script type="text/javascript">
fmembroadd.Lists["x_CargoMinisterial"].Options = <?php echo (is_array($membro->CargoMinisterial->EditValue)) ? ew_ArrayToJson($membro->CargoMinisterial->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php echo $membro->CargoMinisterial->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Admissao->Visible) { // Admissao ?>
	<div id="r_Admissao" class="form-group">
		<label id="elh_membro_Admissao" for="x_Admissao" class="col-sm-2 control-label ewLabel"><?php echo $membro->Admissao->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Admissao->CellAttributes() ?>>
<span id="el_membro_Admissao">
<input type="text" data-field="x_Admissao" name="x_Admissao" id="x_Admissao" size="15" value="<?php echo $membro->Admissao->EditValue ?>"<?php echo $membro->Admissao->EditAttributes() ?>>
<?php if (!$membro->Admissao->ReadOnly && !$membro->Admissao->Disabled && @$membro->Admissao->EditAttrs["readonly"] == "" && @$membro->Admissao->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fmembroadd", "x_Admissao", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $membro->Admissao->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Tipo_Admissao->Visible) { // Tipo_Admissao ?>
	<div id="r_Tipo_Admissao" class="form-group">
		<label id="elh_membro_Tipo_Admissao" for="x_Tipo_Admissao" class="col-sm-2 control-label ewLabel"><?php echo $membro->Tipo_Admissao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Tipo_Admissao->CellAttributes() ?>>
<span id="el_membro_Tipo_Admissao">
<select data-field="x_Tipo_Admissao" id="x_Tipo_Admissao" name="x_Tipo_Admissao"<?php echo $membro->Tipo_Admissao->EditAttributes() ?>>
<?php
if (is_array($membro->Tipo_Admissao->EditValue)) {
	$arwrk = $membro->Tipo_Admissao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($membro->Tipo_Admissao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "tipo_admissao")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $membro->Tipo_Admissao->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_Tipo_Admissao',url:'tipo_admissaoaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_Tipo_Admissao"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $membro->Tipo_Admissao->FldCaption() ?></span></button>
<?php } ?>
<script type="text/javascript">
fmembroadd.Lists["x_Tipo_Admissao"].Options = <?php echo (is_array($membro->Tipo_Admissao->EditValue)) ? ew_ArrayToJson($membro->Tipo_Admissao->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $membro->Tipo_Admissao->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Funcao->Visible) { // Funcao ?>
	<div id="r_Funcao" class="form-group">
		<label id="elh_membro_Funcao" for="x_Funcao" class="col-sm-2 control-label ewLabel"><?php echo $membro->Funcao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Funcao->CellAttributes() ?>>
<span id="el_membro_Funcao">
<select data-field="x_Funcao" id="x_Funcao" name="x_Funcao"<?php echo $membro->Funcao->EditAttributes() ?>>
<?php
if (is_array($membro->Funcao->EditValue)) {
	$arwrk = $membro->Funcao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($membro->Funcao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "funcoes_exerce")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $membro->Funcao->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_Funcao',url:'funcoes_exerceaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_Funcao"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $membro->Funcao->FldCaption() ?></span></button>
<?php } ?>
<script type="text/javascript">
fmembroadd.Lists["x_Funcao"].Options = <?php echo (is_array($membro->Funcao->EditValue)) ? ew_ArrayToJson($membro->Funcao->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $membro->Funcao->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($membro->Rede_Ministerial->Visible) { // Rede_Ministerial ?>
	<div id="r_Rede_Ministerial" class="form-group">
		<label id="elh_membro_Rede_Ministerial" for="x_Rede_Ministerial" class="col-sm-2 control-label ewLabel"><?php echo $membro->Rede_Ministerial->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Rede_Ministerial->CellAttributes() ?>>
<span id="el_membro_Rede_Ministerial">
<select data-field="x_Rede_Ministerial" id="x_Rede_Ministerial" name="x_Rede_Ministerial"<?php echo $membro->Rede_Ministerial->EditAttributes() ?>>
<?php
if (is_array($membro->Rede_Ministerial->EditValue)) {
	$arwrk = $membro->Rede_Ministerial->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($membro->Rede_Ministerial->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "rede_ministerial")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $membro->Rede_Ministerial->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_Rede_Ministerial',url:'rede_ministerialaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_Rede_Ministerial"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $membro->Rede_Ministerial->FldCaption() ?></span></button>
<?php } ?>
<script type="text/javascript">
fmembroadd.Lists["x_Rede_Ministerial"].Options = <?php echo (is_array($membro->Rede_Ministerial->EditValue)) ? ew_ArrayToJson($membro->Rede_Ministerial->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $membro->Rede_Ministerial->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
		</div>
		<div class="tab-pane" id="tab_membro5">
<div>
<?php if ($membro->Foto->Visible) { // Foto ?>
	<div id="r_Foto" class="form-group">
		<label id="elh_membro_Foto" class="col-sm-2 control-label ewLabel"><?php echo $membro->Foto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $membro->Foto->CellAttributes() ?>>
<span id="el_membro_Foto">
<div id="fd_x_Foto">
<span title="<?php echo $membro->Foto->FldTitle() ? $membro->Foto->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($membro->Foto->ReadOnly || $membro->Foto->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_Foto" name="x_Foto" id="x_Foto">
</span>
<input type="hidden" name="fn_x_Foto" id= "fn_x_Foto" value="<?php echo $membro->Foto->Upload->FileName ?>">
<input type="hidden" name="fa_x_Foto" id= "fa_x_Foto" value="0">
<input type="hidden" name="fs_x_Foto" id= "fs_x_Foto" value="50">
<input type="hidden" name="fx_x_Foto" id= "fx_x_Foto" value="<?php echo $membro->Foto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_Foto" id= "fm_x_Foto" value="<?php echo $membro->Foto->UploadMaxFileSize ?>">
</div>
<table id="ft_x_Foto" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $membro->Foto->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
		</div>
	</div>
</div>
</div>
<?php
	if (in_array("dizimos", explode(",", $membro->getCurrentDetailTable())) && $dizimos->DetailAdd) {
?>
<?php if ($membro->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("dizimos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "dizimosgrid.php" ?>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton btn-success" name="btnAction" id="btnAction" type="submit"><i class="fa fa-floppy-o"></i>&nbsp;<?php echo $Language->Phrase("AddBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fmembroadd.Init();
</script>
<?php
$membro_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$membro_add->Page_Terminate();
?>
