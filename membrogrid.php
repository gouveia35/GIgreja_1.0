<?php include_once "usuariosinfo.php" ?>
<?php

// Create page object
if (!isset($membro_grid)) $membro_grid = new cmembro_grid();

// Page init
$membro_grid->Page_Init();

// Page main
$membro_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$membro_grid->Page_Render();
?>
<?php if ($membro->Export == "") { ?>
<script type="text/javascript">

// Page object
var membro_grid = new ew_Page("membro_grid");
membro_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = membro_grid.PageID; // For backward compatibility

// Form object
var fmembrogrid = new ew_Form("fmembrogrid");
fmembrogrid.FormKeyCountName = '<?php echo $membro_grid->FormKeyCountName ?>';

// Validate form
fmembrogrid.Validate = function() {
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
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_Nome");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->Nome->FldCaption(), $membro->Nome->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Sexo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->Sexo->FldCaption(), $membro->Sexo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_EstadoCivil");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->EstadoCivil->FldCaption(), $membro->EstadoCivil->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_CPF");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->CPF->FldCaption(), $membro->CPF->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_CargoMinisterial");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->CargoMinisterial->FldCaption(), $membro->CargoMinisterial->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Funcao");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $membro->Funcao->FldCaption(), $membro->Funcao->ReqErrMsg)) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fmembrogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "Foto", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Matricula", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Nome", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Sexo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "EstadoCivil", false)) return false;
	if (ew_ValueChanged(fobj, infix, "CPF", false)) return false;
	if (ew_ValueChanged(fobj, infix, "CargoMinisterial", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Funcao", false)) return false;
	return true;
}

// Form_CustomValidate event
fmembrogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmembrogrid.ValidateRequired = true;
<?php } else { ?>
fmembrogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmembrogrid.Lists["x_CargoMinisterial"] = {"LinkField":"x_id_cgm","Ajax":null,"AutoFill":false,"DisplayFields":["x_Cargo_Ministerial","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmembrogrid.Lists["x_Funcao"] = {"LinkField":"x_Id","Ajax":null,"AutoFill":false,"DisplayFields":["x_Funcao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($membro->CurrentAction == "gridadd") {
	if ($membro->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$membro_grid->TotalRecs = $membro->SelectRecordCount();
			$membro_grid->Recordset = $membro_grid->LoadRecordset($membro_grid->StartRec-1, $membro_grid->DisplayRecs);
		} else {
			if ($membro_grid->Recordset = $membro_grid->LoadRecordset())
				$membro_grid->TotalRecs = $membro_grid->Recordset->RecordCount();
		}
		$membro_grid->StartRec = 1;
		$membro_grid->DisplayRecs = $membro_grid->TotalRecs;
	} else {
		$membro->CurrentFilter = "0=1";
		$membro_grid->StartRec = 1;
		$membro_grid->DisplayRecs = $membro->GridAddRowCount;
	}
	$membro_grid->TotalRecs = $membro_grid->DisplayRecs;
	$membro_grid->StopRec = $membro_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($membro_grid->TotalRecs <= 0)
			$membro_grid->TotalRecs = $membro->SelectRecordCount();
	} else {
		if (!$membro_grid->Recordset && ($membro_grid->Recordset = $membro_grid->LoadRecordset()))
			$membro_grid->TotalRecs = $membro_grid->Recordset->RecordCount();
	}
	$membro_grid->StartRec = 1;
	$membro_grid->DisplayRecs = $membro_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$membro_grid->Recordset = $membro_grid->LoadRecordset($membro_grid->StartRec-1, $membro_grid->DisplayRecs);

	// Set no record found message
	if ($membro->CurrentAction == "" && $membro_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$membro_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($membro_grid->SearchWhere == "0=101")
			$membro_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$membro_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$membro_grid->RenderOtherOptions();
?>
<?php $membro_grid->ShowPageHeader(); ?>
<?php
$membro_grid->ShowMessage();
?>
<?php if ($membro_grid->TotalRecs > 0 || $membro->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fmembrogrid" class="ewForm form-inline">
<?php if ($membro_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel">
<?php
	foreach ($membro_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_membro" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_membrogrid" class="table ewTable">
<?php echo $membro->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$membro_grid->RenderListOptions();

// Render list options (header, left)
$membro_grid->ListOptions->Render("header", "left");
?>
<?php if ($membro->Foto->Visible) { // Foto ?>
	<?php if ($membro->SortUrl($membro->Foto) == "") { ?>
		<th data-name="Foto"><div id="elh_membro_Foto" class="membro_Foto"><div class="ewTableHeaderCaption"><?php echo $membro->Foto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Foto"><div><div id="elh_membro_Foto" class="membro_Foto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membro->Foto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($membro->Foto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membro->Foto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($membro->Matricula->Visible) { // Matricula ?>
	<?php if ($membro->SortUrl($membro->Matricula) == "") { ?>
		<th data-name="Matricula"><div id="elh_membro_Matricula" class="membro_Matricula"><div class="ewTableHeaderCaption"><?php echo $membro->Matricula->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Matricula"><div><div id="elh_membro_Matricula" class="membro_Matricula">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membro->Matricula->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($membro->Matricula->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membro->Matricula->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($membro->Nome->Visible) { // Nome ?>
	<?php if ($membro->SortUrl($membro->Nome) == "") { ?>
		<th data-name="Nome"><div id="elh_membro_Nome" class="membro_Nome"><div class="ewTableHeaderCaption"><?php echo $membro->Nome->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Nome"><div><div id="elh_membro_Nome" class="membro_Nome">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membro->Nome->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($membro->Nome->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membro->Nome->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($membro->Sexo->Visible) { // Sexo ?>
	<?php if ($membro->SortUrl($membro->Sexo) == "") { ?>
		<th data-name="Sexo"><div id="elh_membro_Sexo" class="membro_Sexo"><div class="ewTableHeaderCaption"><?php echo $membro->Sexo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Sexo"><div><div id="elh_membro_Sexo" class="membro_Sexo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membro->Sexo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($membro->Sexo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membro->Sexo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($membro->EstadoCivil->Visible) { // EstadoCivil ?>
	<?php if ($membro->SortUrl($membro->EstadoCivil) == "") { ?>
		<th data-name="EstadoCivil"><div id="elh_membro_EstadoCivil" class="membro_EstadoCivil"><div class="ewTableHeaderCaption"><?php echo $membro->EstadoCivil->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="EstadoCivil"><div><div id="elh_membro_EstadoCivil" class="membro_EstadoCivil">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membro->EstadoCivil->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($membro->EstadoCivil->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membro->EstadoCivil->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($membro->CPF->Visible) { // CPF ?>
	<?php if ($membro->SortUrl($membro->CPF) == "") { ?>
		<th data-name="CPF"><div id="elh_membro_CPF" class="membro_CPF"><div class="ewTableHeaderCaption"><?php echo $membro->CPF->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="CPF"><div><div id="elh_membro_CPF" class="membro_CPF">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membro->CPF->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($membro->CPF->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membro->CPF->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($membro->CargoMinisterial->Visible) { // CargoMinisterial ?>
	<?php if ($membro->SortUrl($membro->CargoMinisterial) == "") { ?>
		<th data-name="CargoMinisterial"><div id="elh_membro_CargoMinisterial" class="membro_CargoMinisterial"><div class="ewTableHeaderCaption"><?php echo $membro->CargoMinisterial->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="CargoMinisterial"><div><div id="elh_membro_CargoMinisterial" class="membro_CargoMinisterial">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membro->CargoMinisterial->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($membro->CargoMinisterial->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membro->CargoMinisterial->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($membro->Funcao->Visible) { // Funcao ?>
	<?php if ($membro->SortUrl($membro->Funcao) == "") { ?>
		<th data-name="Funcao"><div id="elh_membro_Funcao" class="membro_Funcao"><div class="ewTableHeaderCaption"><?php echo $membro->Funcao->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Funcao"><div><div id="elh_membro_Funcao" class="membro_Funcao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $membro->Funcao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($membro->Funcao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($membro->Funcao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$membro_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$membro_grid->StartRec = 1;
$membro_grid->StopRec = $membro_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($membro_grid->FormKeyCountName) && ($membro->CurrentAction == "gridadd" || $membro->CurrentAction == "gridedit" || $membro->CurrentAction == "F")) {
		$membro_grid->KeyCount = $objForm->GetValue($membro_grid->FormKeyCountName);
		$membro_grid->StopRec = $membro_grid->StartRec + $membro_grid->KeyCount - 1;
	}
}
$membro_grid->RecCnt = $membro_grid->StartRec - 1;
if ($membro_grid->Recordset && !$membro_grid->Recordset->EOF) {
	$membro_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $membro_grid->StartRec > 1)
		$membro_grid->Recordset->Move($membro_grid->StartRec - 1);
} elseif (!$membro->AllowAddDeleteRow && $membro_grid->StopRec == 0) {
	$membro_grid->StopRec = $membro->GridAddRowCount;
}

// Initialize aggregate
$membro->RowType = EW_ROWTYPE_AGGREGATEINIT;
$membro->ResetAttrs();
$membro_grid->RenderRow();
if ($membro->CurrentAction == "gridadd")
	$membro_grid->RowIndex = 0;
if ($membro->CurrentAction == "gridedit")
	$membro_grid->RowIndex = 0;
while ($membro_grid->RecCnt < $membro_grid->StopRec) {
	$membro_grid->RecCnt++;
	if (intval($membro_grid->RecCnt) >= intval($membro_grid->StartRec)) {
		$membro_grid->RowCnt++;
		if ($membro->CurrentAction == "gridadd" || $membro->CurrentAction == "gridedit" || $membro->CurrentAction == "F") {
			$membro_grid->RowIndex++;
			$objForm->Index = $membro_grid->RowIndex;
			if ($objForm->HasValue($membro_grid->FormActionName))
				$membro_grid->RowAction = strval($objForm->GetValue($membro_grid->FormActionName));
			elseif ($membro->CurrentAction == "gridadd")
				$membro_grid->RowAction = "insert";
			else
				$membro_grid->RowAction = "";
		}

		// Set up key count
		$membro_grid->KeyCount = $membro_grid->RowIndex;

		// Init row class and style
		$membro->ResetAttrs();
		$membro->CssClass = "";
		if ($membro->CurrentAction == "gridadd") {
			if ($membro->CurrentMode == "copy") {
				$membro_grid->LoadRowValues($membro_grid->Recordset); // Load row values
				$membro_grid->SetRecordKey($membro_grid->RowOldKey, $membro_grid->Recordset); // Set old record key
			} else {
				$membro_grid->LoadDefaultValues(); // Load default values
				$membro_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$membro_grid->LoadRowValues($membro_grid->Recordset); // Load row values
		}
		$membro->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($membro->CurrentAction == "gridadd") // Grid add
			$membro->RowType = EW_ROWTYPE_ADD; // Render add
		if ($membro->CurrentAction == "gridadd" && $membro->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$membro_grid->RestoreCurrentRowFormValues($membro_grid->RowIndex); // Restore form values
		if ($membro->CurrentAction == "gridedit") { // Grid edit
			if ($membro->EventCancelled) {
				$membro_grid->RestoreCurrentRowFormValues($membro_grid->RowIndex); // Restore form values
			}
			if ($membro_grid->RowAction == "insert")
				$membro->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$membro->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($membro->CurrentAction == "gridedit" && ($membro->RowType == EW_ROWTYPE_EDIT || $membro->RowType == EW_ROWTYPE_ADD) && $membro->EventCancelled) // Update failed
			$membro_grid->RestoreCurrentRowFormValues($membro_grid->RowIndex); // Restore form values
		if ($membro->RowType == EW_ROWTYPE_EDIT) // Edit row
			$membro_grid->EditRowCnt++;
		if ($membro->CurrentAction == "F") // Confirm row
			$membro_grid->RestoreCurrentRowFormValues($membro_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$membro->RowAttrs = array_merge($membro->RowAttrs, array('data-rowindex'=>$membro_grid->RowCnt, 'id'=>'r' . $membro_grid->RowCnt . '_membro', 'data-rowtype'=>$membro->RowType));

		// Render row
		$membro_grid->RenderRow();

		// Render list options
		$membro_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($membro_grid->RowAction <> "delete" && $membro_grid->RowAction <> "insertdelete" && !($membro_grid->RowAction == "insert" && $membro->CurrentAction == "F" && $membro_grid->EmptyRow())) {
?>
	<tr<?php echo $membro->RowAttributes() ?>>
<?php

// Render list options (body, left)
$membro_grid->ListOptions->Render("body", "left", $membro_grid->RowCnt);
?>
	<?php if ($membro->Foto->Visible) { // Foto ?>
		<td data-name="Foto"<?php echo $membro->Foto->CellAttributes() ?>>
<?php if ($membro_grid->RowAction == "insert") { // Add record ?>
<span id="el<?php echo $membro_grid->RowCnt ?>_membro_Foto" class="form-group membro_Foto">
<div id="fd_x<?php echo $membro_grid->RowIndex ?>_Foto">
<span title="<?php echo $membro->Foto->FldTitle() ? $membro->Foto->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($membro->Foto->ReadOnly || $membro->Foto->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_Foto" name="x<?php echo $membro_grid->RowIndex ?>_Foto" id="x<?php echo $membro_grid->RowIndex ?>_Foto">
</span>
<input type="hidden" name="fn_x<?php echo $membro_grid->RowIndex ?>_Foto" id= "fn_x<?php echo $membro_grid->RowIndex ?>_Foto" value="<?php echo $membro->Foto->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $membro_grid->RowIndex ?>_Foto" id= "fa_x<?php echo $membro_grid->RowIndex ?>_Foto" value="0">
<input type="hidden" name="fs_x<?php echo $membro_grid->RowIndex ?>_Foto" id= "fs_x<?php echo $membro_grid->RowIndex ?>_Foto" value="50">
<input type="hidden" name="fx_x<?php echo $membro_grid->RowIndex ?>_Foto" id= "fx_x<?php echo $membro_grid->RowIndex ?>_Foto" value="<?php echo $membro->Foto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $membro_grid->RowIndex ?>_Foto" id= "fm_x<?php echo $membro_grid->RowIndex ?>_Foto" value="<?php echo $membro->Foto->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $membro_grid->RowIndex ?>_Foto" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-field="x_Foto" name="o<?php echo $membro_grid->RowIndex ?>_Foto" id="o<?php echo $membro_grid->RowIndex ?>_Foto" value="<?php echo ew_HtmlEncode($membro->Foto->OldValue) ?>">
<?php } elseif ($membro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span>
<?php echo ew_GetFileViewTag($membro->Foto, $membro->Foto->ListViewValue()) ?>
</span>
<?php } else  { // Edit record ?>
<span id="el<?php echo $membro_grid->RowCnt ?>_membro_Foto" class="form-group membro_Foto">
<div id="fd_x<?php echo $membro_grid->RowIndex ?>_Foto">
<span title="<?php echo $membro->Foto->FldTitle() ? $membro->Foto->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($membro->Foto->ReadOnly || $membro->Foto->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_Foto" name="x<?php echo $membro_grid->RowIndex ?>_Foto" id="x<?php echo $membro_grid->RowIndex ?>_Foto">
</span>
<input type="hidden" name="fn_x<?php echo $membro_grid->RowIndex ?>_Foto" id= "fn_x<?php echo $membro_grid->RowIndex ?>_Foto" value="<?php echo $membro->Foto->Upload->FileName ?>">
<?php if (@$_POST["fa_x<?php echo $membro_grid->RowIndex ?>_Foto"] == "0") { ?>
<input type="hidden" name="fa_x<?php echo $membro_grid->RowIndex ?>_Foto" id= "fa_x<?php echo $membro_grid->RowIndex ?>_Foto" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $membro_grid->RowIndex ?>_Foto" id= "fa_x<?php echo $membro_grid->RowIndex ?>_Foto" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $membro_grid->RowIndex ?>_Foto" id= "fs_x<?php echo $membro_grid->RowIndex ?>_Foto" value="50">
<input type="hidden" name="fx_x<?php echo $membro_grid->RowIndex ?>_Foto" id= "fx_x<?php echo $membro_grid->RowIndex ?>_Foto" value="<?php echo $membro->Foto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $membro_grid->RowIndex ?>_Foto" id= "fm_x<?php echo $membro_grid->RowIndex ?>_Foto" value="<?php echo $membro->Foto->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $membro_grid->RowIndex ?>_Foto" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php } ?>
<a id="<?php echo $membro_grid->PageObjName . "_row_" . $membro_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($membro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_Id_membro" name="x<?php echo $membro_grid->RowIndex ?>_Id_membro" id="x<?php echo $membro_grid->RowIndex ?>_Id_membro" value="<?php echo ew_HtmlEncode($membro->Id_membro->CurrentValue) ?>">
<input type="hidden" data-field="x_Id_membro" name="o<?php echo $membro_grid->RowIndex ?>_Id_membro" id="o<?php echo $membro_grid->RowIndex ?>_Id_membro" value="<?php echo ew_HtmlEncode($membro->Id_membro->OldValue) ?>">
<?php } ?>
<?php if ($membro->RowType == EW_ROWTYPE_EDIT || $membro->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_Id_membro" name="x<?php echo $membro_grid->RowIndex ?>_Id_membro" id="x<?php echo $membro_grid->RowIndex ?>_Id_membro" value="<?php echo ew_HtmlEncode($membro->Id_membro->CurrentValue) ?>">
<?php } ?>
	<?php if ($membro->Matricula->Visible) { // Matricula ?>
		<td data-name="Matricula"<?php echo $membro->Matricula->CellAttributes() ?>>
<?php if ($membro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $membro_grid->RowCnt ?>_membro_Matricula" class="form-group membro_Matricula">
<input type="text" data-field="x_Matricula" name="x<?php echo $membro_grid->RowIndex ?>_Matricula" id="x<?php echo $membro_grid->RowIndex ?>_Matricula" size="30" maxlength="20" value="<?php echo $membro->Matricula->EditValue ?>"<?php echo $membro->Matricula->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_Matricula" name="o<?php echo $membro_grid->RowIndex ?>_Matricula" id="o<?php echo $membro_grid->RowIndex ?>_Matricula" value="<?php echo ew_HtmlEncode($membro->Matricula->OldValue) ?>">
<?php } ?>
<?php if ($membro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $membro_grid->RowCnt ?>_membro_Matricula" class="form-group membro_Matricula">
<input type="text" data-field="x_Matricula" name="x<?php echo $membro_grid->RowIndex ?>_Matricula" id="x<?php echo $membro_grid->RowIndex ?>_Matricula" size="30" maxlength="20" value="<?php echo $membro->Matricula->EditValue ?>"<?php echo $membro->Matricula->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($membro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $membro->Matricula->ViewAttributes() ?>>
<?php echo $membro->Matricula->ListViewValue() ?></span>
<input type="hidden" data-field="x_Matricula" name="x<?php echo $membro_grid->RowIndex ?>_Matricula" id="x<?php echo $membro_grid->RowIndex ?>_Matricula" value="<?php echo ew_HtmlEncode($membro->Matricula->FormValue) ?>">
<input type="hidden" data-field="x_Matricula" name="o<?php echo $membro_grid->RowIndex ?>_Matricula" id="o<?php echo $membro_grid->RowIndex ?>_Matricula" value="<?php echo ew_HtmlEncode($membro->Matricula->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($membro->Nome->Visible) { // Nome ?>
		<td data-name="Nome"<?php echo $membro->Nome->CellAttributes() ?>>
<?php if ($membro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $membro_grid->RowCnt ?>_membro_Nome" class="form-group membro_Nome">
<input type="text" data-field="x_Nome" name="x<?php echo $membro_grid->RowIndex ?>_Nome" id="x<?php echo $membro_grid->RowIndex ?>_Nome" size="65" maxlength="60" value="<?php echo $membro->Nome->EditValue ?>"<?php echo $membro->Nome->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_Nome" name="o<?php echo $membro_grid->RowIndex ?>_Nome" id="o<?php echo $membro_grid->RowIndex ?>_Nome" value="<?php echo ew_HtmlEncode($membro->Nome->OldValue) ?>">
<?php } ?>
<?php if ($membro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $membro_grid->RowCnt ?>_membro_Nome" class="form-group membro_Nome">
<input type="text" data-field="x_Nome" name="x<?php echo $membro_grid->RowIndex ?>_Nome" id="x<?php echo $membro_grid->RowIndex ?>_Nome" size="65" maxlength="60" value="<?php echo $membro->Nome->EditValue ?>"<?php echo $membro->Nome->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($membro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $membro->Nome->ViewAttributes() ?>>
<?php echo $membro->Nome->ListViewValue() ?></span>
<input type="hidden" data-field="x_Nome" name="x<?php echo $membro_grid->RowIndex ?>_Nome" id="x<?php echo $membro_grid->RowIndex ?>_Nome" value="<?php echo ew_HtmlEncode($membro->Nome->FormValue) ?>">
<input type="hidden" data-field="x_Nome" name="o<?php echo $membro_grid->RowIndex ?>_Nome" id="o<?php echo $membro_grid->RowIndex ?>_Nome" value="<?php echo ew_HtmlEncode($membro->Nome->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($membro->Sexo->Visible) { // Sexo ?>
		<td data-name="Sexo"<?php echo $membro->Sexo->CellAttributes() ?>>
<?php if ($membro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $membro_grid->RowCnt ?>_membro_Sexo" class="form-group membro_Sexo">
<div id="tp_x<?php echo $membro_grid->RowIndex ?>_Sexo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $membro_grid->RowIndex ?>_Sexo" id="x<?php echo $membro_grid->RowIndex ?>_Sexo" value="{value}"<?php echo $membro->Sexo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $membro_grid->RowIndex ?>_Sexo" data-repeatcolumn="5" class="ewItemList">
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
<label class="radio-inline"><input type="radio" data-field="x_Sexo" name="x<?php echo $membro_grid->RowIndex ?>_Sexo" id="x<?php echo $membro_grid->RowIndex ?>_Sexo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $membro->Sexo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $membro->Sexo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_Sexo" name="o<?php echo $membro_grid->RowIndex ?>_Sexo" id="o<?php echo $membro_grid->RowIndex ?>_Sexo" value="<?php echo ew_HtmlEncode($membro->Sexo->OldValue) ?>">
<?php } ?>
<?php if ($membro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $membro_grid->RowCnt ?>_membro_Sexo" class="form-group membro_Sexo">
<div id="tp_x<?php echo $membro_grid->RowIndex ?>_Sexo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $membro_grid->RowIndex ?>_Sexo" id="x<?php echo $membro_grid->RowIndex ?>_Sexo" value="{value}"<?php echo $membro->Sexo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $membro_grid->RowIndex ?>_Sexo" data-repeatcolumn="5" class="ewItemList">
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
<label class="radio-inline"><input type="radio" data-field="x_Sexo" name="x<?php echo $membro_grid->RowIndex ?>_Sexo" id="x<?php echo $membro_grid->RowIndex ?>_Sexo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $membro->Sexo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $membro->Sexo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($membro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $membro->Sexo->ViewAttributes() ?>>
<?php echo $membro->Sexo->ListViewValue() ?></span>
<input type="hidden" data-field="x_Sexo" name="x<?php echo $membro_grid->RowIndex ?>_Sexo" id="x<?php echo $membro_grid->RowIndex ?>_Sexo" value="<?php echo ew_HtmlEncode($membro->Sexo->FormValue) ?>">
<input type="hidden" data-field="x_Sexo" name="o<?php echo $membro_grid->RowIndex ?>_Sexo" id="o<?php echo $membro_grid->RowIndex ?>_Sexo" value="<?php echo ew_HtmlEncode($membro->Sexo->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($membro->EstadoCivil->Visible) { // EstadoCivil ?>
		<td data-name="EstadoCivil"<?php echo $membro->EstadoCivil->CellAttributes() ?>>
<?php if ($membro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $membro_grid->RowCnt ?>_membro_EstadoCivil" class="form-group membro_EstadoCivil">
<select data-field="x_EstadoCivil" id="x<?php echo $membro_grid->RowIndex ?>_EstadoCivil" name="x<?php echo $membro_grid->RowIndex ?>_EstadoCivil"<?php echo $membro->EstadoCivil->EditAttributes() ?>>
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
if (@$emptywrk) $membro->EstadoCivil->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-field="x_EstadoCivil" name="o<?php echo $membro_grid->RowIndex ?>_EstadoCivil" id="o<?php echo $membro_grid->RowIndex ?>_EstadoCivil" value="<?php echo ew_HtmlEncode($membro->EstadoCivil->OldValue) ?>">
<?php } ?>
<?php if ($membro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $membro_grid->RowCnt ?>_membro_EstadoCivil" class="form-group membro_EstadoCivil">
<select data-field="x_EstadoCivil" id="x<?php echo $membro_grid->RowIndex ?>_EstadoCivil" name="x<?php echo $membro_grid->RowIndex ?>_EstadoCivil"<?php echo $membro->EstadoCivil->EditAttributes() ?>>
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
if (@$emptywrk) $membro->EstadoCivil->OldValue = "";
?>
</select>
</span>
<?php } ?>
<?php if ($membro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $membro->EstadoCivil->ViewAttributes() ?>>
<?php echo $membro->EstadoCivil->ListViewValue() ?></span>
<input type="hidden" data-field="x_EstadoCivil" name="x<?php echo $membro_grid->RowIndex ?>_EstadoCivil" id="x<?php echo $membro_grid->RowIndex ?>_EstadoCivil" value="<?php echo ew_HtmlEncode($membro->EstadoCivil->FormValue) ?>">
<input type="hidden" data-field="x_EstadoCivil" name="o<?php echo $membro_grid->RowIndex ?>_EstadoCivil" id="o<?php echo $membro_grid->RowIndex ?>_EstadoCivil" value="<?php echo ew_HtmlEncode($membro->EstadoCivil->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($membro->CPF->Visible) { // CPF ?>
		<td data-name="CPF"<?php echo $membro->CPF->CellAttributes() ?>>
<?php if ($membro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $membro_grid->RowCnt ?>_membro_CPF" class="form-group membro_CPF">
<input type="text" data-field="x_CPF" name="x<?php echo $membro_grid->RowIndex ?>_CPF" id="x<?php echo $membro_grid->RowIndex ?>_CPF" size="30" maxlength="15" value="<?php echo $membro->CPF->EditValue ?>"<?php echo $membro->CPF->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_CPF" name="o<?php echo $membro_grid->RowIndex ?>_CPF" id="o<?php echo $membro_grid->RowIndex ?>_CPF" value="<?php echo ew_HtmlEncode($membro->CPF->OldValue) ?>">
<?php } ?>
<?php if ($membro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $membro_grid->RowCnt ?>_membro_CPF" class="form-group membro_CPF">
<input type="text" data-field="x_CPF" name="x<?php echo $membro_grid->RowIndex ?>_CPF" id="x<?php echo $membro_grid->RowIndex ?>_CPF" size="30" maxlength="15" value="<?php echo $membro->CPF->EditValue ?>"<?php echo $membro->CPF->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($membro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $membro->CPF->ViewAttributes() ?>>
<?php echo $membro->CPF->ListViewValue() ?></span>
<input type="hidden" data-field="x_CPF" name="x<?php echo $membro_grid->RowIndex ?>_CPF" id="x<?php echo $membro_grid->RowIndex ?>_CPF" value="<?php echo ew_HtmlEncode($membro->CPF->FormValue) ?>">
<input type="hidden" data-field="x_CPF" name="o<?php echo $membro_grid->RowIndex ?>_CPF" id="o<?php echo $membro_grid->RowIndex ?>_CPF" value="<?php echo ew_HtmlEncode($membro->CPF->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($membro->CargoMinisterial->Visible) { // CargoMinisterial ?>
		<td data-name="CargoMinisterial"<?php echo $membro->CargoMinisterial->CellAttributes() ?>>
<?php if ($membro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($membro->CargoMinisterial->getSessionValue() <> "") { ?>
<span id="el<?php echo $membro_grid->RowCnt ?>_membro_CargoMinisterial" class="form-group membro_CargoMinisterial">
<span<?php echo $membro->CargoMinisterial->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $membro->CargoMinisterial->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $membro_grid->RowIndex ?>_CargoMinisterial" name="x<?php echo $membro_grid->RowIndex ?>_CargoMinisterial" value="<?php echo ew_HtmlEncode($membro->CargoMinisterial->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $membro_grid->RowCnt ?>_membro_CargoMinisterial" class="form-group membro_CargoMinisterial">
<select data-field="x_CargoMinisterial" id="x<?php echo $membro_grid->RowIndex ?>_CargoMinisterial" name="x<?php echo $membro_grid->RowIndex ?>_CargoMinisterial"<?php echo $membro->CargoMinisterial->EditAttributes() ?>>
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
if (@$emptywrk) $membro->CargoMinisterial->OldValue = "";
?>
</select>
<script type="text/javascript">
fmembrogrid.Lists["x_CargoMinisterial"].Options = <?php echo (is_array($membro->CargoMinisterial->EditValue)) ? ew_ArrayToJson($membro->CargoMinisterial->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<input type="hidden" data-field="x_CargoMinisterial" name="o<?php echo $membro_grid->RowIndex ?>_CargoMinisterial" id="o<?php echo $membro_grid->RowIndex ?>_CargoMinisterial" value="<?php echo ew_HtmlEncode($membro->CargoMinisterial->OldValue) ?>">
<?php } ?>
<?php if ($membro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($membro->CargoMinisterial->getSessionValue() <> "") { ?>
<span id="el<?php echo $membro_grid->RowCnt ?>_membro_CargoMinisterial" class="form-group membro_CargoMinisterial">
<span<?php echo $membro->CargoMinisterial->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $membro->CargoMinisterial->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $membro_grid->RowIndex ?>_CargoMinisterial" name="x<?php echo $membro_grid->RowIndex ?>_CargoMinisterial" value="<?php echo ew_HtmlEncode($membro->CargoMinisterial->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $membro_grid->RowCnt ?>_membro_CargoMinisterial" class="form-group membro_CargoMinisterial">
<select data-field="x_CargoMinisterial" id="x<?php echo $membro_grid->RowIndex ?>_CargoMinisterial" name="x<?php echo $membro_grid->RowIndex ?>_CargoMinisterial"<?php echo $membro->CargoMinisterial->EditAttributes() ?>>
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
if (@$emptywrk) $membro->CargoMinisterial->OldValue = "";
?>
</select>
<script type="text/javascript">
fmembrogrid.Lists["x_CargoMinisterial"].Options = <?php echo (is_array($membro->CargoMinisterial->EditValue)) ? ew_ArrayToJson($membro->CargoMinisterial->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($membro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $membro->CargoMinisterial->ViewAttributes() ?>>
<?php echo $membro->CargoMinisterial->ListViewValue() ?></span>
<input type="hidden" data-field="x_CargoMinisterial" name="x<?php echo $membro_grid->RowIndex ?>_CargoMinisterial" id="x<?php echo $membro_grid->RowIndex ?>_CargoMinisterial" value="<?php echo ew_HtmlEncode($membro->CargoMinisterial->FormValue) ?>">
<input type="hidden" data-field="x_CargoMinisterial" name="o<?php echo $membro_grid->RowIndex ?>_CargoMinisterial" id="o<?php echo $membro_grid->RowIndex ?>_CargoMinisterial" value="<?php echo ew_HtmlEncode($membro->CargoMinisterial->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($membro->Funcao->Visible) { // Funcao ?>
		<td data-name="Funcao"<?php echo $membro->Funcao->CellAttributes() ?>>
<?php if ($membro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $membro_grid->RowCnt ?>_membro_Funcao" class="form-group membro_Funcao">
<select data-field="x_Funcao" id="x<?php echo $membro_grid->RowIndex ?>_Funcao" name="x<?php echo $membro_grid->RowIndex ?>_Funcao"<?php echo $membro->Funcao->EditAttributes() ?>>
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
if (@$emptywrk) $membro->Funcao->OldValue = "";
?>
</select>
<script type="text/javascript">
fmembrogrid.Lists["x_Funcao"].Options = <?php echo (is_array($membro->Funcao->EditValue)) ? ew_ArrayToJson($membro->Funcao->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_Funcao" name="o<?php echo $membro_grid->RowIndex ?>_Funcao" id="o<?php echo $membro_grid->RowIndex ?>_Funcao" value="<?php echo ew_HtmlEncode($membro->Funcao->OldValue) ?>">
<?php } ?>
<?php if ($membro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $membro_grid->RowCnt ?>_membro_Funcao" class="form-group membro_Funcao">
<select data-field="x_Funcao" id="x<?php echo $membro_grid->RowIndex ?>_Funcao" name="x<?php echo $membro_grid->RowIndex ?>_Funcao"<?php echo $membro->Funcao->EditAttributes() ?>>
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
if (@$emptywrk) $membro->Funcao->OldValue = "";
?>
</select>
<script type="text/javascript">
fmembrogrid.Lists["x_Funcao"].Options = <?php echo (is_array($membro->Funcao->EditValue)) ? ew_ArrayToJson($membro->Funcao->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($membro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $membro->Funcao->ViewAttributes() ?>>
<?php echo $membro->Funcao->ListViewValue() ?></span>
<input type="hidden" data-field="x_Funcao" name="x<?php echo $membro_grid->RowIndex ?>_Funcao" id="x<?php echo $membro_grid->RowIndex ?>_Funcao" value="<?php echo ew_HtmlEncode($membro->Funcao->FormValue) ?>">
<input type="hidden" data-field="x_Funcao" name="o<?php echo $membro_grid->RowIndex ?>_Funcao" id="o<?php echo $membro_grid->RowIndex ?>_Funcao" value="<?php echo ew_HtmlEncode($membro->Funcao->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$membro_grid->ListOptions->Render("body", "right", $membro_grid->RowCnt);
?>
	</tr>
<?php if ($membro->RowType == EW_ROWTYPE_ADD || $membro->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fmembrogrid.UpdateOpts(<?php echo $membro_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($membro->CurrentAction <> "gridadd" || $membro->CurrentMode == "copy")
		if (!$membro_grid->Recordset->EOF) $membro_grid->Recordset->MoveNext();
}
?>
<?php
	if ($membro->CurrentMode == "add" || $membro->CurrentMode == "copy" || $membro->CurrentMode == "edit") {
		$membro_grid->RowIndex = '$rowindex$';
		$membro_grid->LoadDefaultValues();

		// Set row properties
		$membro->ResetAttrs();
		$membro->RowAttrs = array_merge($membro->RowAttrs, array('data-rowindex'=>$membro_grid->RowIndex, 'id'=>'r0_membro', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($membro->RowAttrs["class"], "ewTemplate");
		$membro->RowType = EW_ROWTYPE_ADD;

		// Render row
		$membro_grid->RenderRow();

		// Render list options
		$membro_grid->RenderListOptions();
		$membro_grid->StartRowCnt = 0;
?>
	<tr<?php echo $membro->RowAttributes() ?>>
<?php

// Render list options (body, left)
$membro_grid->ListOptions->Render("body", "left", $membro_grid->RowIndex);
?>
	<?php if ($membro->Foto->Visible) { // Foto ?>
		<td data-name="Foto">
<span id="el$rowindex$_membro_Foto" class="form-group membro_Foto">
<div id="fd_x<?php echo $membro_grid->RowIndex ?>_Foto">
<span title="<?php echo $membro->Foto->FldTitle() ? $membro->Foto->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($membro->Foto->ReadOnly || $membro->Foto->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_Foto" name="x<?php echo $membro_grid->RowIndex ?>_Foto" id="x<?php echo $membro_grid->RowIndex ?>_Foto">
</span>
<input type="hidden" name="fn_x<?php echo $membro_grid->RowIndex ?>_Foto" id= "fn_x<?php echo $membro_grid->RowIndex ?>_Foto" value="<?php echo $membro->Foto->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $membro_grid->RowIndex ?>_Foto" id= "fa_x<?php echo $membro_grid->RowIndex ?>_Foto" value="0">
<input type="hidden" name="fs_x<?php echo $membro_grid->RowIndex ?>_Foto" id= "fs_x<?php echo $membro_grid->RowIndex ?>_Foto" value="50">
<input type="hidden" name="fx_x<?php echo $membro_grid->RowIndex ?>_Foto" id= "fx_x<?php echo $membro_grid->RowIndex ?>_Foto" value="<?php echo $membro->Foto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $membro_grid->RowIndex ?>_Foto" id= "fm_x<?php echo $membro_grid->RowIndex ?>_Foto" value="<?php echo $membro->Foto->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $membro_grid->RowIndex ?>_Foto" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-field="x_Foto" name="o<?php echo $membro_grid->RowIndex ?>_Foto" id="o<?php echo $membro_grid->RowIndex ?>_Foto" value="<?php echo ew_HtmlEncode($membro->Foto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($membro->Matricula->Visible) { // Matricula ?>
		<td data-name="Matricula">
<?php if ($membro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_membro_Matricula" class="form-group membro_Matricula">
<input type="text" data-field="x_Matricula" name="x<?php echo $membro_grid->RowIndex ?>_Matricula" id="x<?php echo $membro_grid->RowIndex ?>_Matricula" size="30" maxlength="20" value="<?php echo $membro->Matricula->EditValue ?>"<?php echo $membro->Matricula->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_membro_Matricula" class="form-group membro_Matricula">
<span<?php echo $membro->Matricula->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $membro->Matricula->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Matricula" name="x<?php echo $membro_grid->RowIndex ?>_Matricula" id="x<?php echo $membro_grid->RowIndex ?>_Matricula" value="<?php echo ew_HtmlEncode($membro->Matricula->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_Matricula" name="o<?php echo $membro_grid->RowIndex ?>_Matricula" id="o<?php echo $membro_grid->RowIndex ?>_Matricula" value="<?php echo ew_HtmlEncode($membro->Matricula->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($membro->Nome->Visible) { // Nome ?>
		<td data-name="Nome">
<?php if ($membro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_membro_Nome" class="form-group membro_Nome">
<input type="text" data-field="x_Nome" name="x<?php echo $membro_grid->RowIndex ?>_Nome" id="x<?php echo $membro_grid->RowIndex ?>_Nome" size="65" maxlength="60" value="<?php echo $membro->Nome->EditValue ?>"<?php echo $membro->Nome->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_membro_Nome" class="form-group membro_Nome">
<span<?php echo $membro->Nome->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $membro->Nome->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Nome" name="x<?php echo $membro_grid->RowIndex ?>_Nome" id="x<?php echo $membro_grid->RowIndex ?>_Nome" value="<?php echo ew_HtmlEncode($membro->Nome->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_Nome" name="o<?php echo $membro_grid->RowIndex ?>_Nome" id="o<?php echo $membro_grid->RowIndex ?>_Nome" value="<?php echo ew_HtmlEncode($membro->Nome->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($membro->Sexo->Visible) { // Sexo ?>
		<td data-name="Sexo">
<?php if ($membro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_membro_Sexo" class="form-group membro_Sexo">
<div id="tp_x<?php echo $membro_grid->RowIndex ?>_Sexo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $membro_grid->RowIndex ?>_Sexo" id="x<?php echo $membro_grid->RowIndex ?>_Sexo" value="{value}"<?php echo $membro->Sexo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $membro_grid->RowIndex ?>_Sexo" data-repeatcolumn="5" class="ewItemList">
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
<label class="radio-inline"><input type="radio" data-field="x_Sexo" name="x<?php echo $membro_grid->RowIndex ?>_Sexo" id="x<?php echo $membro_grid->RowIndex ?>_Sexo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $membro->Sexo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $membro->Sexo->OldValue = "";
?>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_membro_Sexo" class="form-group membro_Sexo">
<span<?php echo $membro->Sexo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $membro->Sexo->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Sexo" name="x<?php echo $membro_grid->RowIndex ?>_Sexo" id="x<?php echo $membro_grid->RowIndex ?>_Sexo" value="<?php echo ew_HtmlEncode($membro->Sexo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_Sexo" name="o<?php echo $membro_grid->RowIndex ?>_Sexo" id="o<?php echo $membro_grid->RowIndex ?>_Sexo" value="<?php echo ew_HtmlEncode($membro->Sexo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($membro->EstadoCivil->Visible) { // EstadoCivil ?>
		<td data-name="EstadoCivil">
<?php if ($membro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_membro_EstadoCivil" class="form-group membro_EstadoCivil">
<select data-field="x_EstadoCivil" id="x<?php echo $membro_grid->RowIndex ?>_EstadoCivil" name="x<?php echo $membro_grid->RowIndex ?>_EstadoCivil"<?php echo $membro->EstadoCivil->EditAttributes() ?>>
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
if (@$emptywrk) $membro->EstadoCivil->OldValue = "";
?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_membro_EstadoCivil" class="form-group membro_EstadoCivil">
<span<?php echo $membro->EstadoCivil->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $membro->EstadoCivil->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_EstadoCivil" name="x<?php echo $membro_grid->RowIndex ?>_EstadoCivil" id="x<?php echo $membro_grid->RowIndex ?>_EstadoCivil" value="<?php echo ew_HtmlEncode($membro->EstadoCivil->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_EstadoCivil" name="o<?php echo $membro_grid->RowIndex ?>_EstadoCivil" id="o<?php echo $membro_grid->RowIndex ?>_EstadoCivil" value="<?php echo ew_HtmlEncode($membro->EstadoCivil->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($membro->CPF->Visible) { // CPF ?>
		<td data-name="CPF">
<?php if ($membro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_membro_CPF" class="form-group membro_CPF">
<input type="text" data-field="x_CPF" name="x<?php echo $membro_grid->RowIndex ?>_CPF" id="x<?php echo $membro_grid->RowIndex ?>_CPF" size="30" maxlength="15" value="<?php echo $membro->CPF->EditValue ?>"<?php echo $membro->CPF->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_membro_CPF" class="form-group membro_CPF">
<span<?php echo $membro->CPF->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $membro->CPF->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_CPF" name="x<?php echo $membro_grid->RowIndex ?>_CPF" id="x<?php echo $membro_grid->RowIndex ?>_CPF" value="<?php echo ew_HtmlEncode($membro->CPF->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_CPF" name="o<?php echo $membro_grid->RowIndex ?>_CPF" id="o<?php echo $membro_grid->RowIndex ?>_CPF" value="<?php echo ew_HtmlEncode($membro->CPF->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($membro->CargoMinisterial->Visible) { // CargoMinisterial ?>
		<td data-name="CargoMinisterial">
<?php if ($membro->CurrentAction <> "F") { ?>
<?php if ($membro->CargoMinisterial->getSessionValue() <> "") { ?>
<span id="el$rowindex$_membro_CargoMinisterial" class="form-group membro_CargoMinisterial">
<span<?php echo $membro->CargoMinisterial->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $membro->CargoMinisterial->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $membro_grid->RowIndex ?>_CargoMinisterial" name="x<?php echo $membro_grid->RowIndex ?>_CargoMinisterial" value="<?php echo ew_HtmlEncode($membro->CargoMinisterial->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_membro_CargoMinisterial" class="form-group membro_CargoMinisterial">
<select data-field="x_CargoMinisterial" id="x<?php echo $membro_grid->RowIndex ?>_CargoMinisterial" name="x<?php echo $membro_grid->RowIndex ?>_CargoMinisterial"<?php echo $membro->CargoMinisterial->EditAttributes() ?>>
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
if (@$emptywrk) $membro->CargoMinisterial->OldValue = "";
?>
</select>
<script type="text/javascript">
fmembrogrid.Lists["x_CargoMinisterial"].Options = <?php echo (is_array($membro->CargoMinisterial->EditValue)) ? ew_ArrayToJson($membro->CargoMinisterial->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_membro_CargoMinisterial" class="form-group membro_CargoMinisterial">
<span<?php echo $membro->CargoMinisterial->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $membro->CargoMinisterial->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_CargoMinisterial" name="x<?php echo $membro_grid->RowIndex ?>_CargoMinisterial" id="x<?php echo $membro_grid->RowIndex ?>_CargoMinisterial" value="<?php echo ew_HtmlEncode($membro->CargoMinisterial->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_CargoMinisterial" name="o<?php echo $membro_grid->RowIndex ?>_CargoMinisterial" id="o<?php echo $membro_grid->RowIndex ?>_CargoMinisterial" value="<?php echo ew_HtmlEncode($membro->CargoMinisterial->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($membro->Funcao->Visible) { // Funcao ?>
		<td data-name="Funcao">
<?php if ($membro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_membro_Funcao" class="form-group membro_Funcao">
<select data-field="x_Funcao" id="x<?php echo $membro_grid->RowIndex ?>_Funcao" name="x<?php echo $membro_grid->RowIndex ?>_Funcao"<?php echo $membro->Funcao->EditAttributes() ?>>
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
if (@$emptywrk) $membro->Funcao->OldValue = "";
?>
</select>
<script type="text/javascript">
fmembrogrid.Lists["x_Funcao"].Options = <?php echo (is_array($membro->Funcao->EditValue)) ? ew_ArrayToJson($membro->Funcao->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_membro_Funcao" class="form-group membro_Funcao">
<span<?php echo $membro->Funcao->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $membro->Funcao->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Funcao" name="x<?php echo $membro_grid->RowIndex ?>_Funcao" id="x<?php echo $membro_grid->RowIndex ?>_Funcao" value="<?php echo ew_HtmlEncode($membro->Funcao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_Funcao" name="o<?php echo $membro_grid->RowIndex ?>_Funcao" id="o<?php echo $membro_grid->RowIndex ?>_Funcao" value="<?php echo ew_HtmlEncode($membro->Funcao->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$membro_grid->ListOptions->Render("body", "right", $membro_grid->RowCnt);
?>
<script type="text/javascript">
fmembrogrid.UpdateOpts(<?php echo $membro_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($membro->CurrentMode == "add" || $membro->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $membro_grid->FormKeyCountName ?>" id="<?php echo $membro_grid->FormKeyCountName ?>" value="<?php echo $membro_grid->KeyCount ?>">
<?php echo $membro_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($membro->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $membro_grid->FormKeyCountName ?>" id="<?php echo $membro_grid->FormKeyCountName ?>" value="<?php echo $membro_grid->KeyCount ?>">
<?php echo $membro_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($membro->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fmembrogrid">
</div>
<?php

// Close recordset
if ($membro_grid->Recordset)
	$membro_grid->Recordset->Close();
?>
</div>
</div>
<?php } ?>
<?php if ($membro_grid->TotalRecs == 0 && $membro->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($membro_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($membro->Export == "") { ?>
<script type="text/javascript">
fmembrogrid.Init();
$(document).ready(function($) {	$("#ajuda").click(function() {	bootbox.dialog({title: "Informações de Ajuda", message: '<?php echo $help ?>', buttons: { success: { label: "Fechar" }}}); });});
</script>
<?php } ?>
<?php
$membro_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$membro_grid->Page_Terminate();
?>
