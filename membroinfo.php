<?php

// Global variable for table object
$membro = NULL;

//
// Table class for membro
//
class cmembro extends cTable {
	var $Id_membro;
	var $Foto;
	var $Matricula;
	var $Nome;
	var $Sexo;
	var $DataNasc;
	var $Nacionalidade;
	var $EstadoCivil;
	var $CPF;
	var $RG;
	var $Profissao;
	var $_Email;
	var $TelefoneRes;
	var $Celular_1;
	var $Celular_2;
	var $Endereco;
	var $Complemento;
	var $Bairro;
	var $Cidade;
	var $UF;
	var $CEP;
	var $GrauEscolaridade;
	var $Curso;
	var $Nome_do_Pai;
	var $Nome_da_Mae;
	var $Data_Casamento;
	var $Conjuge;
	var $N_Filhos;
	var $Empresa_trabalha;
	var $Fone_Empresa;
	var $Anotacoes;
	var $Celula;
	var $Nome_da_Familia;
	var $Situacao;
	var $Data_batismo;
	var $Da_Igreja;
	var $CargoMinisterial;
	var $Admissao;
	var $Tipo_Admissao;
	var $Funcao;
	var $Rede_Ministerial;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'membro';
		$this->TableName = 'membro';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// Id_membro
		$this->Id_membro = new cField('membro', 'membro', 'x_Id_membro', 'Id_membro', '`Id_membro`', '`Id_membro`', 3, -1, FALSE, '`Id_membro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Id_membro->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Id_membro'] = &$this->Id_membro;

		// Foto
		$this->Foto = new cField('membro', 'membro', 'x_Foto', 'Foto', '`Foto`', '`Foto`', 200, -1, TRUE, '`Foto`', FALSE, FALSE, FALSE, 'IMAGE');
		$this->Foto->UploadAllowedFileExt = "bmp,jpg,gif,jpeg,png";
		$this->fields['Foto'] = &$this->Foto;

		// Matricula
		$this->Matricula = new cField('membro', 'membro', 'x_Matricula', 'Matricula', '`Matricula`', '`Matricula`', 200, -1, FALSE, '`Matricula`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Matricula'] = &$this->Matricula;

		// Nome
		$this->Nome = new cField('membro', 'membro', 'x_Nome', 'Nome', '`Nome`', '`Nome`', 200, -1, FALSE, '`Nome`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Nome'] = &$this->Nome;

		// Sexo
		$this->Sexo = new cField('membro', 'membro', 'x_Sexo', 'Sexo', '`Sexo`', '`Sexo`', 202, -1, FALSE, '`Sexo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Sexo'] = &$this->Sexo;

		// DataNasc
		$this->DataNasc = new cField('membro', 'membro', 'x_DataNasc', 'DataNasc', '`DataNasc`', 'DATE_FORMAT(`DataNasc`, \'%d/%m/%Y\')', 133, 7, FALSE, '`DataNasc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->DataNasc->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['DataNasc'] = &$this->DataNasc;

		// Nacionalidade
		$this->Nacionalidade = new cField('membro', 'membro', 'x_Nacionalidade', 'Nacionalidade', '`Nacionalidade`', '`Nacionalidade`', 200, -1, FALSE, '`Nacionalidade`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Nacionalidade'] = &$this->Nacionalidade;

		// EstadoCivil
		$this->EstadoCivil = new cField('membro', 'membro', 'x_EstadoCivil', 'EstadoCivil', '`EstadoCivil`', '`EstadoCivil`', 202, -1, FALSE, '`EstadoCivil`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['EstadoCivil'] = &$this->EstadoCivil;

		// CPF
		$this->CPF = new cField('membro', 'membro', 'x_CPF', 'CPF', '`CPF`', '`CPF`', 200, -1, FALSE, '`CPF`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['CPF'] = &$this->CPF;

		// RG
		$this->RG = new cField('membro', 'membro', 'x_RG', 'RG', '`RG`', '`RG`', 200, -1, FALSE, '`RG`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['RG'] = &$this->RG;

		// Profissao
		$this->Profissao = new cField('membro', 'membro', 'x_Profissao', 'Profissao', '`Profissao`', '`Profissao`', 200, -1, FALSE, '`Profissao`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Profissao'] = &$this->Profissao;

		// Email
		$this->_Email = new cField('membro', 'membro', 'x__Email', 'Email', '`Email`', '`Email`', 200, -1, FALSE, '`Email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Email'] = &$this->_Email;

		// TelefoneRes
		$this->TelefoneRes = new cField('membro', 'membro', 'x_TelefoneRes', 'TelefoneRes', '`TelefoneRes`', '`TelefoneRes`', 200, -1, FALSE, '`TelefoneRes`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['TelefoneRes'] = &$this->TelefoneRes;

		// Celular_1
		$this->Celular_1 = new cField('membro', 'membro', 'x_Celular_1', 'Celular_1', '`Celular_1`', '`Celular_1`', 200, -1, FALSE, '`Celular_1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Celular_1'] = &$this->Celular_1;

		// Celular_2
		$this->Celular_2 = new cField('membro', 'membro', 'x_Celular_2', 'Celular_2', '`Celular_2`', '`Celular_2`', 200, -1, FALSE, '`Celular_2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Celular_2'] = &$this->Celular_2;

		// Endereco
		$this->Endereco = new cField('membro', 'membro', 'x_Endereco', 'Endereco', '`Endereco`', '`Endereco`', 200, -1, FALSE, '`Endereco`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Endereco'] = &$this->Endereco;

		// Complemento
		$this->Complemento = new cField('membro', 'membro', 'x_Complemento', 'Complemento', '`Complemento`', '`Complemento`', 200, -1, FALSE, '`Complemento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Complemento'] = &$this->Complemento;

		// Bairro
		$this->Bairro = new cField('membro', 'membro', 'x_Bairro', 'Bairro', '`Bairro`', '`Bairro`', 200, -1, FALSE, '`Bairro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Bairro'] = &$this->Bairro;

		// Cidade
		$this->Cidade = new cField('membro', 'membro', 'x_Cidade', 'Cidade', '`Cidade`', '`Cidade`', 200, -1, FALSE, '`Cidade`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Cidade'] = &$this->Cidade;

		// UF
		$this->UF = new cField('membro', 'membro', 'x_UF', 'UF', '`UF`', '`UF`', 200, -1, FALSE, '`UF`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['UF'] = &$this->UF;

		// CEP
		$this->CEP = new cField('membro', 'membro', 'x_CEP', 'CEP', '`CEP`', '`CEP`', 200, -1, FALSE, '`CEP`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['CEP'] = &$this->CEP;

		// GrauEscolaridade
		$this->GrauEscolaridade = new cField('membro', 'membro', 'x_GrauEscolaridade', 'GrauEscolaridade', '`GrauEscolaridade`', '`GrauEscolaridade`', 16, -1, FALSE, '`GrauEscolaridade`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->GrauEscolaridade->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['GrauEscolaridade'] = &$this->GrauEscolaridade;

		// Curso
		$this->Curso = new cField('membro', 'membro', 'x_Curso', 'Curso', '`Curso`', '`Curso`', 200, -1, FALSE, '`Curso`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Curso'] = &$this->Curso;

		// Nome_do_Pai
		$this->Nome_do_Pai = new cField('membro', 'membro', 'x_Nome_do_Pai', 'Nome_do_Pai', '`Nome_do_Pai`', '`Nome_do_Pai`', 200, -1, FALSE, '`Nome_do_Pai`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Nome_do_Pai'] = &$this->Nome_do_Pai;

		// Nome_da_Mae
		$this->Nome_da_Mae = new cField('membro', 'membro', 'x_Nome_da_Mae', 'Nome_da_Mae', '`Nome_da_Mae`', '`Nome_da_Mae`', 200, -1, FALSE, '`Nome_da_Mae`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Nome_da_Mae'] = &$this->Nome_da_Mae;

		// Data_Casamento
		$this->Data_Casamento = new cField('membro', 'membro', 'x_Data_Casamento', 'Data_Casamento', '`Data_Casamento`', 'DATE_FORMAT(`Data_Casamento`, \'%d/%m/%Y\')', 133, 7, FALSE, '`Data_Casamento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Data_Casamento->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['Data_Casamento'] = &$this->Data_Casamento;

		// Conjuge
		$this->Conjuge = new cField('membro', 'membro', 'x_Conjuge', 'Conjuge', '`Conjuge`', '`Conjuge`', 200, -1, FALSE, '`Conjuge`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Conjuge'] = &$this->Conjuge;

		// N_Filhos
		$this->N_Filhos = new cField('membro', 'membro', 'x_N_Filhos', 'N_Filhos', '`N_Filhos`', '`N_Filhos`', 200, -1, FALSE, '`N_Filhos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['N_Filhos'] = &$this->N_Filhos;

		// Empresa_trabalha
		$this->Empresa_trabalha = new cField('membro', 'membro', 'x_Empresa_trabalha', 'Empresa_trabalha', '`Empresa_trabalha`', '`Empresa_trabalha`', 200, -1, FALSE, '`Empresa_trabalha`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Empresa_trabalha'] = &$this->Empresa_trabalha;

		// Fone_Empresa
		$this->Fone_Empresa = new cField('membro', 'membro', 'x_Fone_Empresa', 'Fone_Empresa', '`Fone_Empresa`', '`Fone_Empresa`', 200, -1, FALSE, '`Fone_Empresa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Fone_Empresa'] = &$this->Fone_Empresa;

		// Anotacoes
		$this->Anotacoes = new cField('membro', 'membro', 'x_Anotacoes', 'Anotacoes', '`Anotacoes`', '`Anotacoes`', 201, -1, FALSE, '`Anotacoes`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Anotacoes'] = &$this->Anotacoes;

		// Celula
		$this->Celula = new cField('membro', 'membro', 'x_Celula', 'Celula', '`Celula`', '`Celula`', 3, -1, FALSE, '`Celula`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Celula'] = &$this->Celula;

		// Nome_da_Familia
		$this->Nome_da_Familia = new cField('membro', 'membro', 'x_Nome_da_Familia', 'Nome_da_Familia', '`Nome_da_Familia`', '`Nome_da_Familia`', 200, -1, FALSE, '`Nome_da_Familia`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Nome_da_Familia'] = &$this->Nome_da_Familia;

		// Situacao
		$this->Situacao = new cField('membro', 'membro', 'x_Situacao', 'Situacao', '`Situacao`', '`Situacao`', 16, -1, FALSE, '`Situacao`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Situacao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Situacao'] = &$this->Situacao;

		// Data_batismo
		$this->Data_batismo = new cField('membro', 'membro', 'x_Data_batismo', 'Data_batismo', '`Data_batismo`', 'DATE_FORMAT(`Data_batismo`, \'%d/%m/%Y\')', 133, 7, FALSE, '`Data_batismo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Data_batismo->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['Data_batismo'] = &$this->Data_batismo;

		// Da_Igreja
		$this->Da_Igreja = new cField('membro', 'membro', 'x_Da_Igreja', 'Da_Igreja', '`Da_Igreja`', '`Da_Igreja`', 3, -1, FALSE, '`Da_Igreja`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Da_Igreja->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Da_Igreja'] = &$this->Da_Igreja;

		// CargoMinisterial
		$this->CargoMinisterial = new cField('membro', 'membro', 'x_CargoMinisterial', 'CargoMinisterial', '`CargoMinisterial`', '`CargoMinisterial`', 16, -1, FALSE, '`CargoMinisterial`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->CargoMinisterial->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['CargoMinisterial'] = &$this->CargoMinisterial;

		// Admissao
		$this->Admissao = new cField('membro', 'membro', 'x_Admissao', 'Admissao', '`Admissao`', 'DATE_FORMAT(`Admissao`, \'%d/%m/%Y\')', 133, 7, FALSE, '`Admissao`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Admissao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['Admissao'] = &$this->Admissao;

		// Tipo_Admissao
		$this->Tipo_Admissao = new cField('membro', 'membro', 'x_Tipo_Admissao', 'Tipo_Admissao', '`Tipo_Admissao`', '`Tipo_Admissao`', 16, -1, FALSE, '`Tipo_Admissao`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Tipo_Admissao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Tipo_Admissao'] = &$this->Tipo_Admissao;

		// Funcao
		$this->Funcao = new cField('membro', 'membro', 'x_Funcao', 'Funcao', '`Funcao`', '`Funcao`', 16, -1, FALSE, '`Funcao`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Funcao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Funcao'] = &$this->Funcao;

		// Rede_Ministerial
		$this->Rede_Ministerial = new cField('membro', 'membro', 'x_Rede_Ministerial', 'Rede_Ministerial', '`Rede_Ministerial`', '`Rede_Ministerial`', 16, -1, FALSE, '`Rede_Ministerial`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Rede_Ministerial->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Rede_Ministerial'] = &$this->Rede_Ministerial;
	}

	// Multiple column sort
	function UpdateSort(&$ofld, $ctrl) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ctrl) {
				$sOrderBy = $this->getSessionOrderBy();
				if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
					$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
				} else {
					if ($sOrderBy <> "") $sOrderBy .= ", ";
					$sOrderBy .= $sSortField . " " . $sThisSort;
				}
				$this->setSessionOrderBy($sOrderBy); // Save to Session
			} else {
				$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "cargosministeriais") {
			if ($this->CargoMinisterial->getSessionValue() <> "")
				$sMasterFilter .= "`id_cgm`=" . ew_QuotedValue($this->CargoMinisterial->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		if ($this->getCurrentMasterTable() == "igrejas") {
			if ($this->Da_Igreja->getSessionValue() <> "")
				$sMasterFilter .= "`Id_igreja`=" . ew_QuotedValue($this->Da_Igreja->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		if ($this->getCurrentMasterTable() == "celulas") {
			if ($this->Celula->getSessionValue() <> "")
				$sMasterFilter .= "`Id_celula`=" . ew_QuotedValue($this->Celula->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "cargosministeriais") {
			if ($this->CargoMinisterial->getSessionValue() <> "")
				$sDetailFilter .= "`CargoMinisterial`=" . ew_QuotedValue($this->CargoMinisterial->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		if ($this->getCurrentMasterTable() == "igrejas") {
			if ($this->Da_Igreja->getSessionValue() <> "")
				$sDetailFilter .= "`Da_Igreja`=" . ew_QuotedValue($this->Da_Igreja->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		if ($this->getCurrentMasterTable() == "celulas") {
			if ($this->Celula->getSessionValue() <> "")
				$sDetailFilter .= "`Celula`=" . ew_QuotedValue($this->Celula->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_cargosministeriais() {
		return "`id_cgm`=@id_cgm@";
	}

	// Detail filter
	function SqlDetailFilter_cargosministeriais() {
		return "`CargoMinisterial`=@CargoMinisterial@";
	}

	// Master filter
	function SqlMasterFilter_igrejas() {
		return "`Id_igreja`=@Id_igreja@";
	}

	// Detail filter
	function SqlDetailFilter_igrejas() {
		return "`Da_Igreja`=@Da_Igreja@";
	}

	// Master filter
	function SqlMasterFilter_celulas() {
		return "`Id_celula`=@Id_celula@";
	}

	// Detail filter
	function SqlDetailFilter_celulas() {
		return "`Celula`=@Celula@";
	}

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "dizimos") {
			$sDetailUrl = $GLOBALS["dizimos"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&fk_Id_membro=" . urlencode($this->Id_membro->CurrentValue);
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "membrolist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`membro`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`membro`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;

		// Cascade Update detail table 'dizimos'
		$bCascadeUpdate = FALSE;
		$rscascade = array();
		if (!is_null($rsold) && (isset($rs['Id_membro']) && $rsold['Id_membro'] <> $rs['Id_membro'])) { // Update detail field 'id_discipulo'
			$bCascadeUpdate = TRUE;
			$rscascade['id_discipulo'] = $rs['Id_membro']; 
		}
		if ($bCascadeUpdate) {
			if (!isset($GLOBALS["dizimos"])) $GLOBALS["dizimos"] = new cdizimos();
			$GLOBALS["dizimos"]->Update($rscascade, "`id_discipulo` = " . ew_QuotedValue($rsold['Id_membro'], EW_DATATYPE_NUMBER));
		}
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('Id_membro', $rs))
				ew_AddFilter($where, ew_QuotedName('Id_membro') . '=' . ew_QuotedValue($rs['Id_membro'], $this->Id_membro->FldDataType));
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;

		// Cascade delete detail table 'dizimos'
		if (!isset($GLOBALS["dizimos"])) $GLOBALS["dizimos"] = new cdizimos();
		$rscascade = array();
		$GLOBALS["dizimos"]->Delete($rscascade, "`id_discipulo` = " . ew_QuotedValue($rs['Id_membro'], EW_DATATYPE_NUMBER));
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`Id_membro` = @Id_membro@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->Id_membro->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@Id_membro@", ew_AdjustSql($this->Id_membro->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "membrolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "membrolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("membroview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("membroview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "membroadd.php?" . $this->UrlParm($parm);
		else
			return "membroadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("membroedit.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("membroedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("membroadd.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("membroadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("membrodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->Id_membro->CurrentValue)) {
			$sUrl .= "Id_membro=" . urlencode($this->Id_membro->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["Id_membro"]; // Id_membro

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->Id_membro->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->Id_membro->setDbValue($rs->fields('Id_membro'));
		$this->Foto->Upload->DbValue = $rs->fields('Foto');
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

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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
		// Id_membro

		$this->Id_membro->ViewValue = $this->Id_membro->CurrentValue;
		$this->Id_membro->ViewCustomAttributes = "";

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

		// Id_membro
		$this->Id_membro->LinkCustomAttributes = "";
		$this->Id_membro->HrefValue = "";
		$this->Id_membro->TooltipValue = "";

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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// Id_membro
		$this->Id_membro->EditAttrs["class"] = "form-control";
		$this->Id_membro->EditCustomAttributes = "";

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
		}

		// Nome_da_Familia
		$this->Nome_da_Familia->EditAttrs["class"] = "form-control";
		$this->Nome_da_Familia->EditCustomAttributes = "";
		$this->Nome_da_Familia->EditValue = ew_HtmlEncode($this->Nome_da_Familia->CurrentValue);

		// Situacao
		$this->Situacao->EditAttrs["class"] = "form-control";
		$this->Situacao->EditCustomAttributes = "";

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
		}

		// Admissao
		$this->Admissao->EditAttrs["class"] = "form-control";
		$this->Admissao->EditCustomAttributes = "";
		$this->Admissao->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->Admissao->CurrentValue, 7));

		// Tipo_Admissao
		$this->Tipo_Admissao->EditAttrs["class"] = "form-control";
		$this->Tipo_Admissao->EditCustomAttributes = "";

		// Funcao
		$this->Funcao->EditAttrs["class"] = "form-control";
		$this->Funcao->EditCustomAttributes = "";

		// Rede_Ministerial
		$this->Rede_Ministerial->EditAttrs["class"] = "form-control";
		$this->Rede_Ministerial->EditCustomAttributes = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->Foto->Exportable) $Doc->ExportCaption($this->Foto);
					if ($this->Matricula->Exportable) $Doc->ExportCaption($this->Matricula);
					if ($this->Nome->Exportable) $Doc->ExportCaption($this->Nome);
					if ($this->Sexo->Exportable) $Doc->ExportCaption($this->Sexo);
					if ($this->DataNasc->Exportable) $Doc->ExportCaption($this->DataNasc);
					if ($this->Nacionalidade->Exportable) $Doc->ExportCaption($this->Nacionalidade);
					if ($this->EstadoCivil->Exportable) $Doc->ExportCaption($this->EstadoCivil);
					if ($this->CPF->Exportable) $Doc->ExportCaption($this->CPF);
					if ($this->RG->Exportable) $Doc->ExportCaption($this->RG);
					if ($this->Profissao->Exportable) $Doc->ExportCaption($this->Profissao);
					if ($this->_Email->Exportable) $Doc->ExportCaption($this->_Email);
					if ($this->TelefoneRes->Exportable) $Doc->ExportCaption($this->TelefoneRes);
					if ($this->Celular_1->Exportable) $Doc->ExportCaption($this->Celular_1);
					if ($this->Celular_2->Exportable) $Doc->ExportCaption($this->Celular_2);
					if ($this->Endereco->Exportable) $Doc->ExportCaption($this->Endereco);
					if ($this->Complemento->Exportable) $Doc->ExportCaption($this->Complemento);
					if ($this->Bairro->Exportable) $Doc->ExportCaption($this->Bairro);
					if ($this->Cidade->Exportable) $Doc->ExportCaption($this->Cidade);
					if ($this->UF->Exportable) $Doc->ExportCaption($this->UF);
					if ($this->CEP->Exportable) $Doc->ExportCaption($this->CEP);
					if ($this->GrauEscolaridade->Exportable) $Doc->ExportCaption($this->GrauEscolaridade);
					if ($this->Curso->Exportable) $Doc->ExportCaption($this->Curso);
					if ($this->Nome_do_Pai->Exportable) $Doc->ExportCaption($this->Nome_do_Pai);
					if ($this->Nome_da_Mae->Exportable) $Doc->ExportCaption($this->Nome_da_Mae);
					if ($this->Data_Casamento->Exportable) $Doc->ExportCaption($this->Data_Casamento);
					if ($this->Conjuge->Exportable) $Doc->ExportCaption($this->Conjuge);
					if ($this->N_Filhos->Exportable) $Doc->ExportCaption($this->N_Filhos);
					if ($this->Empresa_trabalha->Exportable) $Doc->ExportCaption($this->Empresa_trabalha);
					if ($this->Fone_Empresa->Exportable) $Doc->ExportCaption($this->Fone_Empresa);
					if ($this->Anotacoes->Exportable) $Doc->ExportCaption($this->Anotacoes);
					if ($this->Celula->Exportable) $Doc->ExportCaption($this->Celula);
					if ($this->Nome_da_Familia->Exportable) $Doc->ExportCaption($this->Nome_da_Familia);
					if ($this->Situacao->Exportable) $Doc->ExportCaption($this->Situacao);
					if ($this->Data_batismo->Exportable) $Doc->ExportCaption($this->Data_batismo);
					if ($this->Da_Igreja->Exportable) $Doc->ExportCaption($this->Da_Igreja);
					if ($this->CargoMinisterial->Exportable) $Doc->ExportCaption($this->CargoMinisterial);
					if ($this->Admissao->Exportable) $Doc->ExportCaption($this->Admissao);
					if ($this->Tipo_Admissao->Exportable) $Doc->ExportCaption($this->Tipo_Admissao);
					if ($this->Funcao->Exportable) $Doc->ExportCaption($this->Funcao);
					if ($this->Rede_Ministerial->Exportable) $Doc->ExportCaption($this->Rede_Ministerial);
				} else {
					if ($this->Foto->Exportable) $Doc->ExportCaption($this->Foto);
					if ($this->Matricula->Exportable) $Doc->ExportCaption($this->Matricula);
					if ($this->Nome->Exportable) $Doc->ExportCaption($this->Nome);
					if ($this->Sexo->Exportable) $Doc->ExportCaption($this->Sexo);
					if ($this->DataNasc->Exportable) $Doc->ExportCaption($this->DataNasc);
					if ($this->Nacionalidade->Exportable) $Doc->ExportCaption($this->Nacionalidade);
					if ($this->EstadoCivil->Exportable) $Doc->ExportCaption($this->EstadoCivil);
					if ($this->CPF->Exportable) $Doc->ExportCaption($this->CPF);
					if ($this->RG->Exportable) $Doc->ExportCaption($this->RG);
					if ($this->Profissao->Exportable) $Doc->ExportCaption($this->Profissao);
					if ($this->_Email->Exportable) $Doc->ExportCaption($this->_Email);
					if ($this->TelefoneRes->Exportable) $Doc->ExportCaption($this->TelefoneRes);
					if ($this->Celular_1->Exportable) $Doc->ExportCaption($this->Celular_1);
					if ($this->Celular_2->Exportable) $Doc->ExportCaption($this->Celular_2);
					if ($this->Endereco->Exportable) $Doc->ExportCaption($this->Endereco);
					if ($this->Complemento->Exportable) $Doc->ExportCaption($this->Complemento);
					if ($this->Bairro->Exportable) $Doc->ExportCaption($this->Bairro);
					if ($this->Cidade->Exportable) $Doc->ExportCaption($this->Cidade);
					if ($this->UF->Exportable) $Doc->ExportCaption($this->UF);
					if ($this->CEP->Exportable) $Doc->ExportCaption($this->CEP);
					if ($this->GrauEscolaridade->Exportable) $Doc->ExportCaption($this->GrauEscolaridade);
					if ($this->Curso->Exportable) $Doc->ExportCaption($this->Curso);
					if ($this->Nome_do_Pai->Exportable) $Doc->ExportCaption($this->Nome_do_Pai);
					if ($this->Nome_da_Mae->Exportable) $Doc->ExportCaption($this->Nome_da_Mae);
					if ($this->Data_Casamento->Exportable) $Doc->ExportCaption($this->Data_Casamento);
					if ($this->Conjuge->Exportable) $Doc->ExportCaption($this->Conjuge);
					if ($this->N_Filhos->Exportable) $Doc->ExportCaption($this->N_Filhos);
					if ($this->Empresa_trabalha->Exportable) $Doc->ExportCaption($this->Empresa_trabalha);
					if ($this->Fone_Empresa->Exportable) $Doc->ExportCaption($this->Fone_Empresa);
					if ($this->Celula->Exportable) $Doc->ExportCaption($this->Celula);
					if ($this->Nome_da_Familia->Exportable) $Doc->ExportCaption($this->Nome_da_Familia);
					if ($this->Situacao->Exportable) $Doc->ExportCaption($this->Situacao);
					if ($this->Data_batismo->Exportable) $Doc->ExportCaption($this->Data_batismo);
					if ($this->Da_Igreja->Exportable) $Doc->ExportCaption($this->Da_Igreja);
					if ($this->CargoMinisterial->Exportable) $Doc->ExportCaption($this->CargoMinisterial);
					if ($this->Admissao->Exportable) $Doc->ExportCaption($this->Admissao);
					if ($this->Tipo_Admissao->Exportable) $Doc->ExportCaption($this->Tipo_Admissao);
					if ($this->Funcao->Exportable) $Doc->ExportCaption($this->Funcao);
					if ($this->Rede_Ministerial->Exportable) $Doc->ExportCaption($this->Rede_Ministerial);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->Foto->Exportable) $Doc->ExportField($this->Foto);
						if ($this->Matricula->Exportable) $Doc->ExportField($this->Matricula);
						if ($this->Nome->Exportable) $Doc->ExportField($this->Nome);
						if ($this->Sexo->Exportable) $Doc->ExportField($this->Sexo);
						if ($this->DataNasc->Exportable) $Doc->ExportField($this->DataNasc);
						if ($this->Nacionalidade->Exportable) $Doc->ExportField($this->Nacionalidade);
						if ($this->EstadoCivil->Exportable) $Doc->ExportField($this->EstadoCivil);
						if ($this->CPF->Exportable) $Doc->ExportField($this->CPF);
						if ($this->RG->Exportable) $Doc->ExportField($this->RG);
						if ($this->Profissao->Exportable) $Doc->ExportField($this->Profissao);
						if ($this->_Email->Exportable) $Doc->ExportField($this->_Email);
						if ($this->TelefoneRes->Exportable) $Doc->ExportField($this->TelefoneRes);
						if ($this->Celular_1->Exportable) $Doc->ExportField($this->Celular_1);
						if ($this->Celular_2->Exportable) $Doc->ExportField($this->Celular_2);
						if ($this->Endereco->Exportable) $Doc->ExportField($this->Endereco);
						if ($this->Complemento->Exportable) $Doc->ExportField($this->Complemento);
						if ($this->Bairro->Exportable) $Doc->ExportField($this->Bairro);
						if ($this->Cidade->Exportable) $Doc->ExportField($this->Cidade);
						if ($this->UF->Exportable) $Doc->ExportField($this->UF);
						if ($this->CEP->Exportable) $Doc->ExportField($this->CEP);
						if ($this->GrauEscolaridade->Exportable) $Doc->ExportField($this->GrauEscolaridade);
						if ($this->Curso->Exportable) $Doc->ExportField($this->Curso);
						if ($this->Nome_do_Pai->Exportable) $Doc->ExportField($this->Nome_do_Pai);
						if ($this->Nome_da_Mae->Exportable) $Doc->ExportField($this->Nome_da_Mae);
						if ($this->Data_Casamento->Exportable) $Doc->ExportField($this->Data_Casamento);
						if ($this->Conjuge->Exportable) $Doc->ExportField($this->Conjuge);
						if ($this->N_Filhos->Exportable) $Doc->ExportField($this->N_Filhos);
						if ($this->Empresa_trabalha->Exportable) $Doc->ExportField($this->Empresa_trabalha);
						if ($this->Fone_Empresa->Exportable) $Doc->ExportField($this->Fone_Empresa);
						if ($this->Anotacoes->Exportable) $Doc->ExportField($this->Anotacoes);
						if ($this->Celula->Exportable) $Doc->ExportField($this->Celula);
						if ($this->Nome_da_Familia->Exportable) $Doc->ExportField($this->Nome_da_Familia);
						if ($this->Situacao->Exportable) $Doc->ExportField($this->Situacao);
						if ($this->Data_batismo->Exportable) $Doc->ExportField($this->Data_batismo);
						if ($this->Da_Igreja->Exportable) $Doc->ExportField($this->Da_Igreja);
						if ($this->CargoMinisterial->Exportable) $Doc->ExportField($this->CargoMinisterial);
						if ($this->Admissao->Exportable) $Doc->ExportField($this->Admissao);
						if ($this->Tipo_Admissao->Exportable) $Doc->ExportField($this->Tipo_Admissao);
						if ($this->Funcao->Exportable) $Doc->ExportField($this->Funcao);
						if ($this->Rede_Ministerial->Exportable) $Doc->ExportField($this->Rede_Ministerial);
					} else {
						if ($this->Foto->Exportable) $Doc->ExportField($this->Foto);
						if ($this->Matricula->Exportable) $Doc->ExportField($this->Matricula);
						if ($this->Nome->Exportable) $Doc->ExportField($this->Nome);
						if ($this->Sexo->Exportable) $Doc->ExportField($this->Sexo);
						if ($this->DataNasc->Exportable) $Doc->ExportField($this->DataNasc);
						if ($this->Nacionalidade->Exportable) $Doc->ExportField($this->Nacionalidade);
						if ($this->EstadoCivil->Exportable) $Doc->ExportField($this->EstadoCivil);
						if ($this->CPF->Exportable) $Doc->ExportField($this->CPF);
						if ($this->RG->Exportable) $Doc->ExportField($this->RG);
						if ($this->Profissao->Exportable) $Doc->ExportField($this->Profissao);
						if ($this->_Email->Exportable) $Doc->ExportField($this->_Email);
						if ($this->TelefoneRes->Exportable) $Doc->ExportField($this->TelefoneRes);
						if ($this->Celular_1->Exportable) $Doc->ExportField($this->Celular_1);
						if ($this->Celular_2->Exportable) $Doc->ExportField($this->Celular_2);
						if ($this->Endereco->Exportable) $Doc->ExportField($this->Endereco);
						if ($this->Complemento->Exportable) $Doc->ExportField($this->Complemento);
						if ($this->Bairro->Exportable) $Doc->ExportField($this->Bairro);
						if ($this->Cidade->Exportable) $Doc->ExportField($this->Cidade);
						if ($this->UF->Exportable) $Doc->ExportField($this->UF);
						if ($this->CEP->Exportable) $Doc->ExportField($this->CEP);
						if ($this->GrauEscolaridade->Exportable) $Doc->ExportField($this->GrauEscolaridade);
						if ($this->Curso->Exportable) $Doc->ExportField($this->Curso);
						if ($this->Nome_do_Pai->Exportable) $Doc->ExportField($this->Nome_do_Pai);
						if ($this->Nome_da_Mae->Exportable) $Doc->ExportField($this->Nome_da_Mae);
						if ($this->Data_Casamento->Exportable) $Doc->ExportField($this->Data_Casamento);
						if ($this->Conjuge->Exportable) $Doc->ExportField($this->Conjuge);
						if ($this->N_Filhos->Exportable) $Doc->ExportField($this->N_Filhos);
						if ($this->Empresa_trabalha->Exportable) $Doc->ExportField($this->Empresa_trabalha);
						if ($this->Fone_Empresa->Exportable) $Doc->ExportField($this->Fone_Empresa);
						if ($this->Celula->Exportable) $Doc->ExportField($this->Celula);
						if ($this->Nome_da_Familia->Exportable) $Doc->ExportField($this->Nome_da_Familia);
						if ($this->Situacao->Exportable) $Doc->ExportField($this->Situacao);
						if ($this->Data_batismo->Exportable) $Doc->ExportField($this->Data_batismo);
						if ($this->Da_Igreja->Exportable) $Doc->ExportField($this->Da_Igreja);
						if ($this->CargoMinisterial->Exportable) $Doc->ExportField($this->CargoMinisterial);
						if ($this->Admissao->Exportable) $Doc->ExportField($this->Admissao);
						if ($this->Tipo_Admissao->Exportable) $Doc->ExportField($this->Tipo_Admissao);
						if ($this->Funcao->Exportable) $Doc->ExportField($this->Funcao);
						if ($this->Rede_Ministerial->Exportable) $Doc->ExportField($this->Rede_Ministerial);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
