<?php

// Global variable for table object
$print_cartaoficio = NULL;

//
// Table class for print_cartaoficio
//
class cprint_cartaoficio extends cTable {
	var $Id_membro;
	var $Nome;
	var $Sexo;
	var $Nacionalidade;
	var $EstadoCivil;
	var $CPF;
	var $RG;
	var $Matricula;
	var $Admissao;
	var $CargoMinisterial;
	var $Da_Igreja;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'print_cartaoficio';
		$this->TableName = 'print_cartaoficio';
		$this->TableType = 'CUSTOMVIEW';
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
		$this->Id_membro = new cField('print_cartaoficio', 'print_cartaoficio', 'x_Id_membro', 'Id_membro', 'membro.Id_membro', 'membro.Id_membro', 3, -1, FALSE, 'membro.Id_membro', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Id_membro->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Id_membro'] = &$this->Id_membro;

		// Nome
		$this->Nome = new cField('print_cartaoficio', 'print_cartaoficio', 'x_Nome', 'Nome', 'membro.Nome', 'membro.Nome', 200, -1, FALSE, 'membro.Nome', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Nome'] = &$this->Nome;

		// Sexo
		$this->Sexo = new cField('print_cartaoficio', 'print_cartaoficio', 'x_Sexo', 'Sexo', 'membro.Sexo', 'membro.Sexo', 202, -1, FALSE, 'membro.Sexo', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Sexo'] = &$this->Sexo;

		// Nacionalidade
		$this->Nacionalidade = new cField('print_cartaoficio', 'print_cartaoficio', 'x_Nacionalidade', 'Nacionalidade', 'membro.Nacionalidade', 'membro.Nacionalidade', 200, -1, FALSE, 'membro.Nacionalidade', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Nacionalidade'] = &$this->Nacionalidade;

		// EstadoCivil
		$this->EstadoCivil = new cField('print_cartaoficio', 'print_cartaoficio', 'x_EstadoCivil', 'EstadoCivil', 'membro.EstadoCivil', 'membro.EstadoCivil', 202, -1, FALSE, 'membro.EstadoCivil', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['EstadoCivil'] = &$this->EstadoCivil;

		// CPF
		$this->CPF = new cField('print_cartaoficio', 'print_cartaoficio', 'x_CPF', 'CPF', 'membro.CPF', 'membro.CPF', 200, -1, FALSE, 'membro.CPF', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['CPF'] = &$this->CPF;

		// RG
		$this->RG = new cField('print_cartaoficio', 'print_cartaoficio', 'x_RG', 'RG', 'membro.RG', 'membro.RG', 200, -1, FALSE, 'membro.RG', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['RG'] = &$this->RG;

		// Matricula
		$this->Matricula = new cField('print_cartaoficio', 'print_cartaoficio', 'x_Matricula', 'Matricula', 'membro.Matricula', 'membro.Matricula', 200, -1, FALSE, 'membro.Matricula', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Matricula'] = &$this->Matricula;

		// Admissao
		$this->Admissao = new cField('print_cartaoficio', 'print_cartaoficio', 'x_Admissao', 'Admissao', 'membro.Admissao', 'DATE_FORMAT(membro.Admissao, \'%d/%m/%Y\')', 133, 7, FALSE, 'membro.Admissao', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Admissao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['Admissao'] = &$this->Admissao;

		// CargoMinisterial
		$this->CargoMinisterial = new cField('print_cartaoficio', 'print_cartaoficio', 'x_CargoMinisterial', 'CargoMinisterial', 'cargosministeriais.Cargo_Ministerial', 'cargosministeriais.Cargo_Ministerial', 200, -1, FALSE, 'cargosministeriais.Cargo_Ministerial', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['CargoMinisterial'] = &$this->CargoMinisterial;

		// Da_Igreja
		$this->Da_Igreja = new cField('print_cartaoficio', 'print_cartaoficio', 'x_Da_Igreja', 'Da_Igreja', 'igrejas.Igreja', 'igrejas.Igreja', 200, -1, FALSE, 'igrejas.Igreja', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Da_Igreja'] = &$this->Da_Igreja;
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

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "membro INNER JOIN cargosministeriais ON cargosministeriais.id_cgm = membro.CargoMinisterial INNER JOIN igrejas ON igrejas.Id_igreja = membro.Da_Igreja";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT membro.Nome, membro.Sexo, membro.Nacionalidade, membro.EstadoCivil, membro.CPF, membro.RG, membro.Matricula, membro.Admissao, membro.Id_membro, cargosministeriais.Cargo_Ministerial AS CargoMinisterial, igrejas.Igreja AS Da_Igreja FROM " . $this->getSqlFrom();
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
	var $UpdateTable = "membro INNER JOIN cargosministeriais ON cargosministeriais.id_cgm = membro.CargoMinisterial INNER JOIN igrejas ON igrejas.Id_igreja = membro.Da_Igreja";

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
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
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
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
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
			return "print_cartaoficiolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "print_cartaoficiolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("print_cartaoficioview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("print_cartaoficioview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "print_cartaoficioadd.php?" . $this->UrlParm($parm);
		else
			return "print_cartaoficioadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("print_cartaoficioedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("print_cartaoficioadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("print_cartaoficiodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
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

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
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

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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
		// Id_membro

		if (strval($this->Id_membro->CurrentValue) <> "") {
			$sFilterWrk = "`Id_membro`" . ew_SearchString("=", $this->Id_membro->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `Id_membro`, `Nome` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `membro`";
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

		// Matricula
		$this->Matricula->EditAttrs["class"] = "form-control";
		$this->Matricula->EditCustomAttributes = "";
		$this->Matricula->EditValue = ew_HtmlEncode($this->Matricula->CurrentValue);

		// Admissao
		$this->Admissao->EditAttrs["class"] = "form-control";
		$this->Admissao->EditCustomAttributes = "";
		$this->Admissao->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->Admissao->CurrentValue, 7));

		// CargoMinisterial
		$this->CargoMinisterial->EditAttrs["class"] = "form-control";
		$this->CargoMinisterial->EditCustomAttributes = "";
		$this->CargoMinisterial->EditValue = ew_HtmlEncode($this->CargoMinisterial->CurrentValue);

		// Da_Igreja
		$this->Da_Igreja->EditAttrs["class"] = "form-control";
		$this->Da_Igreja->EditCustomAttributes = "";
		$this->Da_Igreja->EditValue = ew_HtmlEncode($this->Da_Igreja->CurrentValue);

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
					if ($this->Nome->Exportable) $Doc->ExportCaption($this->Nome);
					if ($this->Sexo->Exportable) $Doc->ExportCaption($this->Sexo);
					if ($this->Nacionalidade->Exportable) $Doc->ExportCaption($this->Nacionalidade);
					if ($this->EstadoCivil->Exportable) $Doc->ExportCaption($this->EstadoCivil);
					if ($this->CPF->Exportable) $Doc->ExportCaption($this->CPF);
					if ($this->RG->Exportable) $Doc->ExportCaption($this->RG);
					if ($this->Matricula->Exportable) $Doc->ExportCaption($this->Matricula);
					if ($this->Admissao->Exportable) $Doc->ExportCaption($this->Admissao);
					if ($this->CargoMinisterial->Exportable) $Doc->ExportCaption($this->CargoMinisterial);
					if ($this->Da_Igreja->Exportable) $Doc->ExportCaption($this->Da_Igreja);
				} else {
					if ($this->Nome->Exportable) $Doc->ExportCaption($this->Nome);
					if ($this->Sexo->Exportable) $Doc->ExportCaption($this->Sexo);
					if ($this->Nacionalidade->Exportable) $Doc->ExportCaption($this->Nacionalidade);
					if ($this->EstadoCivil->Exportable) $Doc->ExportCaption($this->EstadoCivil);
					if ($this->CPF->Exportable) $Doc->ExportCaption($this->CPF);
					if ($this->RG->Exportable) $Doc->ExportCaption($this->RG);
					if ($this->Matricula->Exportable) $Doc->ExportCaption($this->Matricula);
					if ($this->Admissao->Exportable) $Doc->ExportCaption($this->Admissao);
					if ($this->CargoMinisterial->Exportable) $Doc->ExportCaption($this->CargoMinisterial);
					if ($this->Da_Igreja->Exportable) $Doc->ExportCaption($this->Da_Igreja);
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
						if ($this->Nome->Exportable) $Doc->ExportField($this->Nome);
						if ($this->Sexo->Exportable) $Doc->ExportField($this->Sexo);
						if ($this->Nacionalidade->Exportable) $Doc->ExportField($this->Nacionalidade);
						if ($this->EstadoCivil->Exportable) $Doc->ExportField($this->EstadoCivil);
						if ($this->CPF->Exportable) $Doc->ExportField($this->CPF);
						if ($this->RG->Exportable) $Doc->ExportField($this->RG);
						if ($this->Matricula->Exportable) $Doc->ExportField($this->Matricula);
						if ($this->Admissao->Exportable) $Doc->ExportField($this->Admissao);
						if ($this->CargoMinisterial->Exportable) $Doc->ExportField($this->CargoMinisterial);
						if ($this->Da_Igreja->Exportable) $Doc->ExportField($this->Da_Igreja);
					} else {
						if ($this->Nome->Exportable) $Doc->ExportField($this->Nome);
						if ($this->Sexo->Exportable) $Doc->ExportField($this->Sexo);
						if ($this->Nacionalidade->Exportable) $Doc->ExportField($this->Nacionalidade);
						if ($this->EstadoCivil->Exportable) $Doc->ExportField($this->EstadoCivil);
						if ($this->CPF->Exportable) $Doc->ExportField($this->CPF);
						if ($this->RG->Exportable) $Doc->ExportField($this->RG);
						if ($this->Matricula->Exportable) $Doc->ExportField($this->Matricula);
						if ($this->Admissao->Exportable) $Doc->ExportField($this->Admissao);
						if ($this->CargoMinisterial->Exportable) $Doc->ExportField($this->CargoMinisterial);
						if ($this->Da_Igreja->Exportable) $Doc->ExportField($this->Da_Igreja);
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
