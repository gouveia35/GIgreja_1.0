<?php

// Global variable for table object
$smtp = NULL;

//
// Table class for smtp
//
class csmtp extends cTable {
	var $Id;
	var $SMTP;
	var $SMTP_Porta;
	var $SMTP_Usuario;
	var $SMTP_Senha;
	var $Email_de_Envio;
	var $Email_de_Recebimento;
	var $Seguranca;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'smtp';
		$this->TableName = 'smtp';
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

		// Id
		$this->Id = new cField('smtp', 'smtp', 'x_Id', 'Id', '`Id`', '`Id`', 3, -1, FALSE, '`Id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Id'] = &$this->Id;

		// SMTP
		$this->SMTP = new cField('smtp', 'smtp', 'x_SMTP', 'SMTP', '`SMTP`', '`SMTP`', 200, -1, FALSE, '`SMTP`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['SMTP'] = &$this->SMTP;

		// SMTP_Porta
		$this->SMTP_Porta = new cField('smtp', 'smtp', 'x_SMTP_Porta', 'SMTP_Porta', '`SMTP_Porta`', '`SMTP_Porta`', 200, -1, FALSE, '`SMTP_Porta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['SMTP_Porta'] = &$this->SMTP_Porta;

		// SMTP_Usuario
		$this->SMTP_Usuario = new cField('smtp', 'smtp', 'x_SMTP_Usuario', 'SMTP_Usuario', '`SMTP_Usuario`', '`SMTP_Usuario`', 200, -1, FALSE, '`SMTP_Usuario`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['SMTP_Usuario'] = &$this->SMTP_Usuario;

		// SMTP_Senha
		$this->SMTP_Senha = new cField('smtp', 'smtp', 'x_SMTP_Senha', 'SMTP_Senha', '`SMTP_Senha`', '`SMTP_Senha`', 200, -1, FALSE, '`SMTP_Senha`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['SMTP_Senha'] = &$this->SMTP_Senha;

		// Email_de_Envio
		$this->Email_de_Envio = new cField('smtp', 'smtp', 'x_Email_de_Envio', 'Email_de_Envio', '`Email_de_Envio`', '`Email_de_Envio`', 200, -1, FALSE, '`Email_de_Envio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Email_de_Envio'] = &$this->Email_de_Envio;

		// Email_de_Recebimento
		$this->Email_de_Recebimento = new cField('smtp', 'smtp', 'x_Email_de_Recebimento', 'Email_de_Recebimento', '`Email_de_Recebimento`', '`Email_de_Recebimento`', 200, -1, FALSE, '`Email_de_Recebimento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Email_de_Recebimento'] = &$this->Email_de_Recebimento;

		// Seguranca
		$this->Seguranca = new cField('smtp', 'smtp', 'x_Seguranca', 'Seguranca', '`Seguranca`', '`Seguranca`', 202, -1, FALSE, '`Seguranca`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Seguranca'] = &$this->Seguranca;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`smtp`";
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
	var $UpdateTable = "`smtp`";

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
			if (array_key_exists('Id', $rs))
				ew_AddFilter($where, ew_QuotedName('Id') . '=' . ew_QuotedValue($rs['Id'], $this->Id->FldDataType));
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
		return "`Id` = @Id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->Id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@Id@", ew_AdjustSql($this->Id->CurrentValue), $sKeyFilter); // Replace key value
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
			return "smtplist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "smtplist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("smtpview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("smtpview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "smtpadd.php?" . $this->UrlParm($parm);
		else
			return "smtpadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("smtpedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("smtpadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("smtpdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->Id->CurrentValue)) {
			$sUrl .= "Id=" . urlencode($this->Id->CurrentValue);
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
			$arKeys[] = @$_GET["Id"]; // Id

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
			$this->Id->CurrentValue = $key;
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
		$this->Id->setDbValue($rs->fields('Id'));
		$this->SMTP->setDbValue($rs->fields('SMTP'));
		$this->SMTP_Porta->setDbValue($rs->fields('SMTP_Porta'));
		$this->SMTP_Usuario->setDbValue($rs->fields('SMTP_Usuario'));
		$this->SMTP_Senha->setDbValue($rs->fields('SMTP_Senha'));
		$this->Email_de_Envio->setDbValue($rs->fields('Email_de_Envio'));
		$this->Email_de_Recebimento->setDbValue($rs->fields('Email_de_Recebimento'));
		$this->Seguranca->setDbValue($rs->fields('Seguranca'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// Id

		$this->Id->CellCssStyle = "white-space: nowrap;";

		// SMTP
		// SMTP_Porta
		// SMTP_Usuario
		// SMTP_Senha
		// Email_de_Envio
		// Email_de_Recebimento
		// Seguranca
		// Id

		$this->Id->ViewValue = $this->Id->CurrentValue;
		$this->Id->ViewCustomAttributes = "";

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

		// Id
		$this->Id->LinkCustomAttributes = "";
		$this->Id->HrefValue = "";
		$this->Id->TooltipValue = "";

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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// Id
		$this->Id->EditAttrs["class"] = "form-control";
		$this->Id->EditCustomAttributes = "";

		// SMTP
		$this->SMTP->EditAttrs["class"] = "form-control";
		$this->SMTP->EditCustomAttributes = "";
		$this->SMTP->EditValue = ew_HtmlEncode($this->SMTP->CurrentValue);

		// SMTP_Porta
		$this->SMTP_Porta->EditAttrs["class"] = "form-control";
		$this->SMTP_Porta->EditCustomAttributes = "";
		$this->SMTP_Porta->EditValue = ew_HtmlEncode($this->SMTP_Porta->CurrentValue);

		// SMTP_Usuario
		$this->SMTP_Usuario->EditAttrs["class"] = "form-control";
		$this->SMTP_Usuario->EditCustomAttributes = "";
		$this->SMTP_Usuario->EditValue = ew_HtmlEncode($this->SMTP_Usuario->CurrentValue);

		// SMTP_Senha
		$this->SMTP_Senha->EditAttrs["class"] = "form-control";
		$this->SMTP_Senha->EditCustomAttributes = "";
		$this->SMTP_Senha->EditValue = ew_HtmlEncode($this->SMTP_Senha->CurrentValue);

		// Email_de_Envio
		$this->Email_de_Envio->EditAttrs["class"] = "form-control";
		$this->Email_de_Envio->EditCustomAttributes = "";
		$this->Email_de_Envio->EditValue = ew_HtmlEncode($this->Email_de_Envio->CurrentValue);

		// Email_de_Recebimento
		$this->Email_de_Recebimento->EditAttrs["class"] = "form-control";
		$this->Email_de_Recebimento->EditCustomAttributes = "";
		$this->Email_de_Recebimento->EditValue = ew_HtmlEncode($this->Email_de_Recebimento->CurrentValue);

		// Seguranca
		$this->Seguranca->EditAttrs["class"] = "form-control";
		$this->Seguranca->EditCustomAttributes = "";
		$arwrk = array();
		$arwrk[] = array($this->Seguranca->FldTagValue(1), $this->Seguranca->FldTagCaption(1) <> "" ? $this->Seguranca->FldTagCaption(1) : $this->Seguranca->FldTagValue(1));
		$arwrk[] = array($this->Seguranca->FldTagValue(2), $this->Seguranca->FldTagCaption(2) <> "" ? $this->Seguranca->FldTagCaption(2) : $this->Seguranca->FldTagValue(2));
		array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
		$this->Seguranca->EditValue = $arwrk;

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
					if ($this->SMTP->Exportable) $Doc->ExportCaption($this->SMTP);
					if ($this->SMTP_Porta->Exportable) $Doc->ExportCaption($this->SMTP_Porta);
					if ($this->SMTP_Usuario->Exportable) $Doc->ExportCaption($this->SMTP_Usuario);
					if ($this->SMTP_Senha->Exportable) $Doc->ExportCaption($this->SMTP_Senha);
					if ($this->Email_de_Envio->Exportable) $Doc->ExportCaption($this->Email_de_Envio);
					if ($this->Email_de_Recebimento->Exportable) $Doc->ExportCaption($this->Email_de_Recebimento);
					if ($this->Seguranca->Exportable) $Doc->ExportCaption($this->Seguranca);
				} else {
					if ($this->SMTP->Exportable) $Doc->ExportCaption($this->SMTP);
					if ($this->SMTP_Porta->Exportable) $Doc->ExportCaption($this->SMTP_Porta);
					if ($this->SMTP_Usuario->Exportable) $Doc->ExportCaption($this->SMTP_Usuario);
					if ($this->SMTP_Senha->Exportable) $Doc->ExportCaption($this->SMTP_Senha);
					if ($this->Email_de_Envio->Exportable) $Doc->ExportCaption($this->Email_de_Envio);
					if ($this->Email_de_Recebimento->Exportable) $Doc->ExportCaption($this->Email_de_Recebimento);
					if ($this->Seguranca->Exportable) $Doc->ExportCaption($this->Seguranca);
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
						if ($this->SMTP->Exportable) $Doc->ExportField($this->SMTP);
						if ($this->SMTP_Porta->Exportable) $Doc->ExportField($this->SMTP_Porta);
						if ($this->SMTP_Usuario->Exportable) $Doc->ExportField($this->SMTP_Usuario);
						if ($this->SMTP_Senha->Exportable) $Doc->ExportField($this->SMTP_Senha);
						if ($this->Email_de_Envio->Exportable) $Doc->ExportField($this->Email_de_Envio);
						if ($this->Email_de_Recebimento->Exportable) $Doc->ExportField($this->Email_de_Recebimento);
						if ($this->Seguranca->Exportable) $Doc->ExportField($this->Seguranca);
					} else {
						if ($this->SMTP->Exportable) $Doc->ExportField($this->SMTP);
						if ($this->SMTP_Porta->Exportable) $Doc->ExportField($this->SMTP_Porta);
						if ($this->SMTP_Usuario->Exportable) $Doc->ExportField($this->SMTP_Usuario);
						if ($this->SMTP_Senha->Exportable) $Doc->ExportField($this->SMTP_Senha);
						if ($this->Email_de_Envio->Exportable) $Doc->ExportField($this->Email_de_Envio);
						if ($this->Email_de_Recebimento->Exportable) $Doc->ExportField($this->Email_de_Recebimento);
						if ($this->Seguranca->Exportable) $Doc->ExportField($this->Seguranca);
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
