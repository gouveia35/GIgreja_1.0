<?php
if (!isset($EW_RELATIVE_PATH)) $EW_RELATIVE_PATH = "";
if (!isset($EW_ERROR_FN)) $EW_ERROR_FN = "ew_ErrorFn";
?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php

/**
 * PHPMaker 11 database helper class
 */

class csistemigreja_db {

	// Debug
	var $Debug = FALSE;

	// Language
	var $Language;

	// Database connection info
	var $Host = 'localhost';
	var $Port = 3306;
	var $Username = 'root';
	var $Password = 'vertrigo';
	var $DbName = 'sistemigreja';

	// ADODB (Access/SQL Server)
	var $CodePage = 65001; // Code page

	// Database
	var $StartQuote = "`";
	var $EndQuote = "`";

	/**
	 * MySQL charset (for SET NAMES statement, not used by default)
	 * Note: Read http://dev.mysql.com/doc/refman/5.0/en/charset-connection.html
	 * before using this setting.
	 */
	var $MySqlCharset = "utf8";
	var $Connection;

	// Constructor
	function __construct($langfolder = "", $langid = "", $info = NULL) {
		$args = func_get_args();
		if (count($args) == 1 && is_array($args[0])) { // $info only
			$langfolder = "";
			$langid = "";
			$info = $args[0];
		}

		// Debug
		if (defined("EW_DEBUG_ENABLED"))
			$this->Debug = EW_DEBUG_ENABLED;

		// Open connection
		if (!isset($this->Connection)) $this->Connection = $this->Connect($info);

		// Set up language object
		if ($langfolder <> "")
			$this->Language = new cLanguage($langfolder, $langid);
		elseif (isset($GLOBALS["Language"]))
			$this->Language = &$GLOBALS["Language"];
	}

	// Connect to database
	function &Connect($info = NULL) {
		$GLOBALS["ADODB_FETCH_MODE"] = ADODB_FETCH_BOTH;
		$conn = new mysqlt_driver_ADOConnection();
		$conn->debug = $this->Debug;
		$conn->debug_echo = FALSE;
		if (!$info) {
			$info = array("host" => $this->Host, "port" => $this->Port,
			"user" => $this->Username, "pass" => $this->Password, "db" => $this->DbName);
		}
		$conn->port = intval($info["port"]);
		if ($this->Debug)
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$conn->Connect($info["host"], $info["user"], $info["pass"], $info["db"]);
		if ($this->MySqlCharset <> "")
			$conn->Execute("SET NAMES '" . $this->MySqlCharset . "'");
		$conn->raiseErrorFn = '';
		return $conn;
	}

	// Quote name
	private function QuotedName($Name) {
		$Name = str_replace($this->EndQuote, $this->EndQuote . $this->EndQuote, $Name);
		return $this->StartQuote . $Name . $this->EndQuote;
	}

	// Executes the query, and returns the row(s) as JSON
	function ExecuteJson($SQL, $FirstOnly = TRUE) {
		$rs = $this->LoadRecordset($SQL);
		if ($rs && !$rs->EOF && $rs->FieldCount() > 0) {
			$res = ($FirstOnly) ? array($rs->fields) : $rs->GetRows();
			$rs->Close();
			return json_encode($res);
		}
		return "false";
	}

	// Execute UPDATE, INSERT, or DELETE statements
	function Execute($SQL, $fn = NULL) {
		$conn = &$this->Connection;
		if ($this->Debug)
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($SQL);
		$conn->raiseErrorFn = '';
		if (is_callable($fn) && $rs) {
			while (!$rs->EOF) {
				$fn($rs->fields);
				$rs->MoveNext();
			}
			$rs->MoveFirst();
		}
		return $rs;
	}

	// Executes the query, and returns the first column of the first row
	function ExecuteScalar($SQL) {
		$res = FALSE;
		$rs = $this->LoadRecordset($SQL);
		if ($rs && !$rs->EOF && $rs->FieldCount() > 0) {
			$res = $rs->fields[0];
			$rs->Close();
		}
		return $res;
	}

	// Executes the query, and returns the first row
	function ExecuteRow($SQL) {
		$res = FALSE;
		$rs = $this->LoadRecordset($SQL);
		if ($rs && !$rs->EOF) {
			$res = $rs->fields;
			$rs->Close();
		}
		return $res;
	}

	// Load recordset
	function &LoadRecordset($SQL) {
		$conn = &$this->Connection;
		if ($this->Debug)
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($SQL);
		$conn->raiseErrorFn = '';
		return $rs;
	}

	// Table CSS class name
	var $TableClass = "table table-bordered table-striped ewDbTable";

	// Get result in HTML table
	// options: fieldcaption(bool|array), horizontal(bool), tablename(string|array), tableclass(string)
	function ExecuteHtml($SQL, $options = NULL) {
		$ar = is_array($options) ? $options : array();
		$horizontal = (array_key_exists("horizontal", $ar) && $ar["horizontal"]);
		$rs = $this->LoadRecordset($SQL);
		if (!$rs || $rs->EOF || $rs->FieldCount() < 1)
			return "";
		$html = "";
		$class = (array_key_exists("tableclass", $ar) && $ar["tableclass"]) ? $ar["tableclass"] : $this->TableClass;
		if ($rs->RecordCount() > 1 || $horizontal) { // Horizontal table
			$cnt = $rs->FieldCount();
			$html = "<table class=\"" . $class . "\">";
			$html .= "<thead><tr>";
			$row = &$rs->fields;
			foreach ($row as $key => $value) {
				if (!is_numeric($key))
					$html .= "<th>" . $this->GetFieldCaption($key, $ar) . "</th>";
			}
			$html .= "</tr></thead>";
			$html .= "<tbody>";
			$rowcnt = 0;
			while (!$rs->EOF) {
				$html .= "<tr>";
				$row = &$rs->fields;
				foreach ($row as $key => $value) {
					if (!is_numeric($key))
						$html .= "<td>" . $value . "</td>";
				}
				$html .= "</tr>";
				$rs->MoveNext();
			}
			$html .= "</tbody></table>";
		} else { // Single row, vertical table
			$html = "<table class=\"" . $class . "\"><tbody>";
			$row = &$rs->fields;
			foreach ($row as $key => $value) {
				if (!is_numeric($key)) {
					$html .= "<tr>";
					$html .= "<td>" . $this->GetFieldCaption($key, $ar) . "</td>";
					$html .= "<td>" . $value . "</td></tr>";
				}
			}
			$html .= "</tbody></table>";
		}
		return $html;
	}

	function GetFieldCaption($key, $ar) {
		$caption = "";
		if (!is_array($ar))
			return $key;
		$tablename = @$ar["tablename"];
		$usecaption = (array_key_exists("fieldcaption", $ar) && $ar["fieldcaption"]);
		if ($usecaption) {
			if (is_array($ar["fieldcaption"])) {
				$caption = @$ar["fieldcaption"][$key];
			} elseif (isset($this->Language)) {
				if (is_array($tablename)) {
					foreach ($tablename as $tbl) {
						$caption = @$this->Language->FieldPhrase($tbl, $key, "FldCaption");
						if ($caption <> "")
							break;
					}
				} elseif ($tablename <> "") {
					$caption = @$this->Language->FieldPhrase($tablename, $key, "FldCaption");
				}
			}
		}
		return ($caption <> "") ? $caption : $key;
	}
}

// Connection/Query error handler
if (!function_exists("ew_ErrorFn")) {

	// Connection/Query error handler
	function ew_ErrorFn($DbType, $ErrorType, $ErrorNo, $ErrorMsg, $Param1, $Param2, $Object) {
		if ($ErrorType == 'CONNECT') {
			if ($DbType == "ado_access" || $DbType == "ado_mssql") {
				$msg = "Failed to connect to database. Error: " . $ErrorMsg;
			} else {
				$msg = "Failed to connect to $Param2 at $Param1. Error: " . $ErrorMsg;
			}
		} elseif ($ErrorType == 'EXECUTE') {
			if (defined("EW_DEBUG_ENABLED") && EW_DEBUG_ENABLED) {
				$msg = "Failed to execute SQL: $Param1. Error: " . $ErrorMsg;
			} else {
				$msg = "Failed to execute SQL. Error: " . $ErrorMsg;
			}
		}
		if (function_exists("ew_AddMessage") && defined("EW_SESSION_FAILURE_MESSAGE"))
			ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $msg);
		else
			echo "<div class=\"alert alert-danger ewError\">" . $msg . "</div>";
	}
}
?>
