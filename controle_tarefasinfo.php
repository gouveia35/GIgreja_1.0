<?php

// Global variable for table object
$controle_tarefas = NULL;

//
// Table class for controle_tarefas
//
class ccontrole_tarefas extends cTable {
	var $Id_tarefas;
	var $Descricao;
	var $DuracaoEstimada;
	var $Prioridade;
	var $Anotacoes;
	var $Concluida_em;
	var $Completa;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'controle_tarefas';
		$this->TableName = 'controle_tarefas';
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

		// Id_tarefas
		$this->Id_tarefas = new cField('controle_tarefas', 'controle_tarefas', 'x_Id_tarefas', 'Id_tarefas', '`Id_tarefas`', '`Id_tarefas`', 3, -1, FALSE, '`Id_tarefas`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Id_tarefas->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Id_tarefas'] = &$this->Id_tarefas;

		// Descricao
		$this->Descricao = new cField('controle_tarefas', 'controle_tarefas', 'x_Descricao', 'Descricao', '`Descricao`', '`Descricao`', 200, -1, FALSE, '`Descricao`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Descricao'] = &$this->Descricao;

		// DuracaoEstimada
		$this->DuracaoEstimada = new cField('controle_tarefas', 'controle_tarefas', 'x_DuracaoEstimada', 'DuracaoEstimada', '`DuracaoEstimada`', '`DuracaoEstimada`', 200, -1, FALSE, '`DuracaoEstimada`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['DuracaoEstimada'] = &$this->DuracaoEstimada;

		// Prioridade
		$this->Prioridade = new cField('controle_tarefas', 'controle_tarefas', 'x_Prioridade', 'Prioridade', '`Prioridade`', '`Prioridade`', 16, -1, FALSE, '`Prioridade`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Prioridade->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Prioridade'] = &$this->Prioridade;

		// Anotacoes
		$this->Anotacoes = new cField('controle_tarefas', 'controle_tarefas', 'x_Anotacoes', 'Anotacoes', '`Anotacoes`', '`Anotacoes`', 200, -1, FALSE, '`Anotacoes`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Anotacoes'] = &$this->Anotacoes;

		// Concluida_em
		$this->Concluida_em = new cField('controle_tarefas', 'controle_tarefas', 'x_Concluida_em', 'Concluida_em', '`Concluida_em`', 'DATE_FORMAT(`Concluida_em`, \'%d/%m/%Y\')', 133, 7, FALSE, '`Concluida_em`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Concluida_em->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['Concluida_em'] = &$this->Concluida_em;

		// Completa
		$this->Completa = new cField('controle_tarefas', 'controle_tarefas', 'x_Completa', 'Completa', '`Completa`', '`Completa`', 16, -1, FALSE, '`Completa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Completa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Completa'] = &$this->Completa;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`controle_tarefas`";
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
	var $UpdateTable = "`controle_tarefas`";

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
			if (array_key_exists('Id_tarefas', $rs))
				ew_AddFilter($where, ew_QuotedName('Id_tarefas') . '=' . ew_QuotedValue($rs['Id_tarefas'], $this->Id_tarefas->FldDataType));
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
		return "`Id_tarefas` = @Id_tarefas@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->Id_tarefas->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@Id_tarefas@", ew_AdjustSql($this->Id_tarefas->CurrentValue), $sKeyFilter); // Replace key value
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
			return "controle_tarefaslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "controle_tarefaslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("controle_tarefasview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("controle_tarefasview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "controle_tarefasadd.php?" . $this->UrlParm($parm);
		else
			return "controle_tarefasadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("controle_tarefasedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("controle_tarefasadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("controle_tarefasdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->Id_tarefas->CurrentValue)) {
			$sUrl .= "Id_tarefas=" . urlencode($this->Id_tarefas->CurrentValue);
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
			$arKeys[] = @$_GET["Id_tarefas"]; // Id_tarefas

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
			$this->Id_tarefas->CurrentValue = $key;
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
		$this->Id_tarefas->setDbValue($rs->fields('Id_tarefas'));
		$this->Descricao->setDbValue($rs->fields('Descricao'));
		$this->DuracaoEstimada->setDbValue($rs->fields('DuracaoEstimada'));
		$this->Prioridade->setDbValue($rs->fields('Prioridade'));
		$this->Anotacoes->setDbValue($rs->fields('Anotacoes'));
		$this->Concluida_em->setDbValue($rs->fields('Concluida_em'));
		$this->Completa->setDbValue($rs->fields('Completa'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// Id_tarefas

		$this->Id_tarefas->CellCssStyle = "white-space: nowrap;";

		// Descricao
		// DuracaoEstimada
		// Prioridade
		// Anotacoes
		// Concluida_em
		// Completa
		// Id_tarefas

		$this->Id_tarefas->ViewValue = $this->Id_tarefas->CurrentValue;
		$this->Id_tarefas->ViewCustomAttributes = "";

		// Descricao
		$this->Descricao->ViewValue = $this->Descricao->CurrentValue;
		$this->Descricao->ViewCustomAttributes = "";

		// DuracaoEstimada
		$this->DuracaoEstimada->ViewValue = $this->DuracaoEstimada->CurrentValue;
		$this->DuracaoEstimada->ViewCustomAttributes = "";

		// Prioridade
		if (strval($this->Prioridade->CurrentValue) <> "") {
			$sFilterWrk = "`Id_prior`" . ew_SearchString("=", $this->Prioridade->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `Id_prior`, `Prioridade` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `prioridade`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->Prioridade, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->Prioridade->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->Prioridade->ViewValue = $this->Prioridade->CurrentValue;
			}
		} else {
			$this->Prioridade->ViewValue = NULL;
		}
		$this->Prioridade->ViewCustomAttributes = "";

		// Anotacoes
		$this->Anotacoes->ViewValue = $this->Anotacoes->CurrentValue;
		$this->Anotacoes->ViewCustomAttributes = "";

		// Concluida_em
		$this->Concluida_em->ViewValue = $this->Concluida_em->CurrentValue;
		$this->Concluida_em->ViewValue = ew_FormatDateTime($this->Concluida_em->ViewValue, 7);
		$this->Concluida_em->ViewCustomAttributes = "";

		// Completa
		if (strval($this->Completa->CurrentValue) <> "") {
			$this->Completa->ViewValue = "";
			$arwrk = explode(",", strval($this->Completa->CurrentValue));
			$cnt = count($arwrk);
			for ($ari = 0; $ari < $cnt; $ari++) {
				switch (trim($arwrk[$ari])) {
					case $this->Completa->FldTagValue(1):
						$this->Completa->ViewValue .= $this->Completa->FldTagCaption(1) <> "" ? $this->Completa->FldTagCaption(1) : trim($arwrk[$ari]);
						break;
					default:
						$this->Completa->ViewValue .= trim($arwrk[$ari]);
				}
				if ($ari < $cnt-1) $this->Completa->ViewValue .= ew_ViewOptionSeparator($ari);
			}
		} else {
			$this->Completa->ViewValue = NULL;
		}
		$this->Completa->ViewCustomAttributes = "";

		// Id_tarefas
		$this->Id_tarefas->LinkCustomAttributes = "";
		$this->Id_tarefas->HrefValue = "";
		$this->Id_tarefas->TooltipValue = "";

		// Descricao
		$this->Descricao->LinkCustomAttributes = "";
		$this->Descricao->HrefValue = "";
		$this->Descricao->TooltipValue = "";

		// DuracaoEstimada
		$this->DuracaoEstimada->LinkCustomAttributes = "";
		$this->DuracaoEstimada->HrefValue = "";
		$this->DuracaoEstimada->TooltipValue = "";

		// Prioridade
		$this->Prioridade->LinkCustomAttributes = "";
		$this->Prioridade->HrefValue = "";
		$this->Prioridade->TooltipValue = "";

		// Anotacoes
		$this->Anotacoes->LinkCustomAttributes = "";
		$this->Anotacoes->HrefValue = "";
		$this->Anotacoes->TooltipValue = "";

		// Concluida_em
		$this->Concluida_em->LinkCustomAttributes = "";
		$this->Concluida_em->HrefValue = "";
		$this->Concluida_em->TooltipValue = "";

		// Completa
		$this->Completa->LinkCustomAttributes = "";
		$this->Completa->HrefValue = "";
		$this->Completa->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// Id_tarefas
		$this->Id_tarefas->EditAttrs["class"] = "form-control";
		$this->Id_tarefas->EditCustomAttributes = "";

		// Descricao
		$this->Descricao->EditAttrs["class"] = "form-control";
		$this->Descricao->EditCustomAttributes = "";
		$this->Descricao->EditValue = ew_HtmlEncode($this->Descricao->CurrentValue);

		// DuracaoEstimada
		$this->DuracaoEstimada->EditAttrs["class"] = "form-control";
		$this->DuracaoEstimada->EditCustomAttributes = "";
		$this->DuracaoEstimada->EditValue = ew_HtmlEncode($this->DuracaoEstimada->CurrentValue);

		// Prioridade
		$this->Prioridade->EditCustomAttributes = "";

		// Anotacoes
		$this->Anotacoes->EditAttrs["class"] = "form-control";
		$this->Anotacoes->EditCustomAttributes = "";
		$this->Anotacoes->EditValue = ew_HtmlEncode($this->Anotacoes->CurrentValue);

		// Concluida_em
		$this->Concluida_em->EditAttrs["class"] = "form-control";
		$this->Concluida_em->EditCustomAttributes = "";
		$this->Concluida_em->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->Concluida_em->CurrentValue, 7));

		// Completa
		$this->Completa->EditCustomAttributes = "";
		$arwrk = array();
		$arwrk[] = array($this->Completa->FldTagValue(1), $this->Completa->FldTagCaption(1) <> "" ? $this->Completa->FldTagCaption(1) : $this->Completa->FldTagValue(1));
		$this->Completa->EditValue = $arwrk;

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
					if ($this->Descricao->Exportable) $Doc->ExportCaption($this->Descricao);
					if ($this->DuracaoEstimada->Exportable) $Doc->ExportCaption($this->DuracaoEstimada);
					if ($this->Prioridade->Exportable) $Doc->ExportCaption($this->Prioridade);
					if ($this->Anotacoes->Exportable) $Doc->ExportCaption($this->Anotacoes);
					if ($this->Concluida_em->Exportable) $Doc->ExportCaption($this->Concluida_em);
					if ($this->Completa->Exportable) $Doc->ExportCaption($this->Completa);
				} else {
					if ($this->Descricao->Exportable) $Doc->ExportCaption($this->Descricao);
					if ($this->DuracaoEstimada->Exportable) $Doc->ExportCaption($this->DuracaoEstimada);
					if ($this->Prioridade->Exportable) $Doc->ExportCaption($this->Prioridade);
					if ($this->Anotacoes->Exportable) $Doc->ExportCaption($this->Anotacoes);
					if ($this->Concluida_em->Exportable) $Doc->ExportCaption($this->Concluida_em);
					if ($this->Completa->Exportable) $Doc->ExportCaption($this->Completa);
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
						if ($this->Descricao->Exportable) $Doc->ExportField($this->Descricao);
						if ($this->DuracaoEstimada->Exportable) $Doc->ExportField($this->DuracaoEstimada);
						if ($this->Prioridade->Exportable) $Doc->ExportField($this->Prioridade);
						if ($this->Anotacoes->Exportable) $Doc->ExportField($this->Anotacoes);
						if ($this->Concluida_em->Exportable) $Doc->ExportField($this->Concluida_em);
						if ($this->Completa->Exportable) $Doc->ExportField($this->Completa);
					} else {
						if ($this->Descricao->Exportable) $Doc->ExportField($this->Descricao);
						if ($this->DuracaoEstimada->Exportable) $Doc->ExportField($this->DuracaoEstimada);
						if ($this->Prioridade->Exportable) $Doc->ExportField($this->Prioridade);
						if ($this->Anotacoes->Exportable) $Doc->ExportField($this->Anotacoes);
						if ($this->Concluida_em->Exportable) $Doc->ExportField($this->Concluida_em);
						if ($this->Completa->Exportable) $Doc->ExportField($this->Completa);
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
