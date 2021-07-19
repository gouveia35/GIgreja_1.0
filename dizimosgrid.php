<?php include_once "usuariosinfo.php" ?>
<?php
// Create page object
if (!isset($dizimos_grid))
    $dizimos_grid = new cdizimos_grid();

// Page init
$dizimos_grid->Page_Init();

// Page main
$dizimos_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$dizimos_grid->Page_Render();
?>
<?php if ($dizimos->Export == "") { ?>
    <script type="text/javascript">

    // Page object
        var dizimos_grid = new ew_Page("dizimos_grid");
        dizimos_grid.PageID = "grid"; // Page ID
        var EW_PAGE_ID = dizimos_grid.PageID; // For backward compatibility

    // Form object
        var fdizimosgrid = new ew_Form("fdizimosgrid");
        fdizimosgrid.FormKeyCountName = '<?php echo $dizimos_grid->FormKeyCountName ?>';

    // Validate form
        fdizimosgrid.Validate = function () {
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
                    elm = this.GetElements("x" + infix + "_Descricao");
                    if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
                        return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dizimos->Descricao->FldCaption(), $dizimos->Descricao->ReqErrMsg)) ?>");
                    elm = this.GetElements("x" + infix + "_Receitas");
                    if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
                        return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dizimos->Receitas->FldCaption(), $dizimos->Receitas->ReqErrMsg)) ?>");
                    elm = this.GetElements("x" + infix + "_Receitas");
                    if (elm && !ew_CheckNumber(elm.value))
                        return this.OnError(elm, "<?php echo ew_JsEncode2($dizimos->Receitas->FldErrMsg()) ?>");
                    elm = this.GetElements("x" + infix + "_FormaPagto");
                    if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
                        return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dizimos->FormaPagto->FldCaption(), $dizimos->FormaPagto->ReqErrMsg)) ?>");
                    elm = this.GetElements("x" + infix + "_Dt_Lancamento");
                    if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
                        return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dizimos->Dt_Lancamento->FldCaption(), $dizimos->Dt_Lancamento->ReqErrMsg)) ?>");
                    elm = this.GetElements("x" + infix + "_Dt_Lancamento");
                    if (elm && !ew_CheckEuroDate(elm.value))
                        return this.OnError(elm, "<?php echo ew_JsEncode2($dizimos->Dt_Lancamento->FldErrMsg()) ?>");
                    elm = this.GetElements("x" + infix + "_Vencimento");
                    if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
                        return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dizimos->Vencimento->FldCaption(), $dizimos->Vencimento->ReqErrMsg)) ?>");
                    elm = this.GetElements("x" + infix + "_Vencimento");
                    if (elm && !ew_CheckEuroDate(elm.value))
                        return this.OnError(elm, "<?php echo ew_JsEncode2($dizimos->Vencimento->FldErrMsg()) ?>");

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
        fdizimosgrid.EmptyRow = function (infix) {
            var fobj = this.Form;
            if (ew_ValueChanged(fobj, infix, "Descricao", false))
                return false;
            if (ew_ValueChanged(fobj, infix, "Receitas", false))
                return false;
            if (ew_ValueChanged(fobj, infix, "FormaPagto", false))
                return false;
            if (ew_ValueChanged(fobj, infix, "Dt_Lancamento", false))
                return false;
            if (ew_ValueChanged(fobj, infix, "Vencimento", false))
                return false;
            return true;
        }

    // Form_CustomValidate event
        fdizimosgrid.Form_CustomValidate =
                function (fobj) { // DO NOT CHANGE THIS LINE!

                    // Your custom validation code here, return false if invalid. 
                    return true;
                }

    // Use JavaScript validation or not
    <?php if (EW_CLIENT_VALIDATE) { ?>
            fdizimosgrid.ValidateRequired = true;
    <?php } else { ?>
            fdizimosgrid.ValidateRequired = false;
    <?php } ?>

    // Dynamic selection lists
        fdizimosgrid.Lists["x_FormaPagto"] = {"LinkField": "x_Id", "Ajax": null, "AutoFill": false, "DisplayFields": ["x_Forma_Pagto", "", "", ""], "ParentFields": [], "FilterFields": [], "Options": []};

    // Form object for search
    </script>
<?php } ?>
<?php
if ($dizimos->CurrentAction == "gridadd") {
    if ($dizimos->CurrentMode == "copy") {
        $bSelectLimit = EW_SELECT_LIMIT;
        if ($bSelectLimit) {
            $dizimos_grid->TotalRecs = $dizimos->SelectRecordCount();
            $dizimos_grid->Recordset = $dizimos_grid->LoadRecordset($dizimos_grid->StartRec - 1, $dizimos_grid->DisplayRecs);
        } else {
            if ($dizimos_grid->Recordset = $dizimos_grid->LoadRecordset())
                $dizimos_grid->TotalRecs = $dizimos_grid->Recordset->RecordCount();
        }
        $dizimos_grid->StartRec = 1;
        $dizimos_grid->DisplayRecs = $dizimos_grid->TotalRecs;
    } else {
        $dizimos->CurrentFilter = "0=1";
        $dizimos_grid->StartRec = 1;
        $dizimos_grid->DisplayRecs = $dizimos->GridAddRowCount;
    }
    $dizimos_grid->TotalRecs = $dizimos_grid->DisplayRecs;
    $dizimos_grid->StopRec = $dizimos_grid->DisplayRecs;
} else {
    $bSelectLimit = EW_SELECT_LIMIT;
    if ($bSelectLimit) {
        if ($dizimos_grid->TotalRecs <= 0)
            $dizimos_grid->TotalRecs = $dizimos->SelectRecordCount();
    } else {
        if (!$dizimos_grid->Recordset && ($dizimos_grid->Recordset = $dizimos_grid->LoadRecordset()))
            $dizimos_grid->TotalRecs = $dizimos_grid->Recordset->RecordCount();
    }
    $dizimos_grid->StartRec = 1;
    $dizimos_grid->DisplayRecs = $dizimos_grid->TotalRecs; // Display all records
    if ($bSelectLimit)
        $dizimos_grid->Recordset = $dizimos_grid->LoadRecordset($dizimos_grid->StartRec - 1, $dizimos_grid->DisplayRecs);

    // Set no record found message
    if ($dizimos->CurrentAction == "" && $dizimos_grid->TotalRecs == 0) {
        if (!$Security->CanList())
            $dizimos_grid->setWarningMessage($Language->Phrase("NoPermission"));
        if ($dizimos_grid->SearchWhere == "0=101")
            $dizimos_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
        else
            $dizimos_grid->setWarningMessage($Language->Phrase("NoRecord"));
    }
}
$dizimos_grid->RenderOtherOptions();
?>
<?php $dizimos_grid->ShowPageHeader(); ?>
        <?php
        $dizimos_grid->ShowMessage();
        ?>
            <?php if ($dizimos_grid->TotalRecs > 0 || $dizimos->CurrentAction <> "") { ?>
    <div class="ewGrid">
        <div id="fdizimosgrid" class="ewForm form-inline">
                <?php if ($dizimos_grid->ShowOtherOptions) { ?>
                <div class="ewGridUpperPanel">
                <?php
                foreach ($dizimos_grid->OtherOptions as &$option)
                    $option->Render("body");
                ?>
                </div>
                <div class="clearfix"></div>
                            <?php } ?>
            <div id="gmp_dizimos" class="<?php if (ew_IsResponsiveLayout()) {
                                echo "table-responsive ";
                            } ?>ewGridMiddlePanel">
                <table id="tbl_dizimosgrid" class="table ewTable">
                            <?php echo $dizimos->TableCustomInnerHtml ?>
                    <thead><!-- Table header -->
                        <tr class="ewTableHeader">
                            <?php
// Render list options
                            $dizimos_grid->RenderListOptions();

// Render list options (header, left)
                            $dizimos_grid->ListOptions->Render("header", "left");
                            ?>
                            <?php if ($dizimos->Descricao->Visible) { // Descricao  ?>
                                <?php if ($dizimos->SortUrl($dizimos->Descricao) == "") { ?>
                                    <th data-name="Descricao"><div id="elh_dizimos_Descricao" class="dizimos_Descricao"><div class="ewTableHeaderCaption"><?php echo $dizimos->Descricao->FldCaption() ?></div></div></th>
                                <?php } else { ?>
                                    <th data-name="Descricao"><div><div id="elh_dizimos_Descricao" class="dizimos_Descricao">
                                                <div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dizimos->Descricao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dizimos->Descricao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dizimos->Descricao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
                                            </div></div></th>
        <?php } ?>
    <?php } ?>		
                            <?php if ($dizimos->Receitas->Visible) { // Receitas  ?>
                                <?php if ($dizimos->SortUrl($dizimos->Receitas) == "") { ?>
                                    <th data-name="Receitas"><div id="elh_dizimos_Receitas" class="dizimos_Receitas"><div class="ewTableHeaderCaption"><?php echo $dizimos->Receitas->FldCaption() ?></div></div></th>
                                <?php } else { ?>
                                    <th data-name="Receitas"><div><div id="elh_dizimos_Receitas" class="dizimos_Receitas">
                                                <div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dizimos->Receitas->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dizimos->Receitas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dizimos->Receitas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
                                            </div></div></th>
        <?php } ?>
    <?php } ?>		
                            <?php if ($dizimos->FormaPagto->Visible) { // FormaPagto  ?>
                                <?php if ($dizimos->SortUrl($dizimos->FormaPagto) == "") { ?>
                                    <th data-name="FormaPagto"><div id="elh_dizimos_FormaPagto" class="dizimos_FormaPagto"><div class="ewTableHeaderCaption"><?php echo $dizimos->FormaPagto->FldCaption() ?></div></div></th>
                                <?php } else { ?>
                                    <th data-name="FormaPagto"><div><div id="elh_dizimos_FormaPagto" class="dizimos_FormaPagto">
                                                <div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dizimos->FormaPagto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dizimos->FormaPagto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dizimos->FormaPagto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
                                            </div></div></th>
        <?php } ?>
    <?php } ?>		
                            <?php if ($dizimos->Dt_Lancamento->Visible) { // Dt_Lancamento  ?>
                                <?php if ($dizimos->SortUrl($dizimos->Dt_Lancamento) == "") { ?>
                                    <th data-name="Dt_Lancamento"><div id="elh_dizimos_Dt_Lancamento" class="dizimos_Dt_Lancamento"><div class="ewTableHeaderCaption"><?php echo $dizimos->Dt_Lancamento->FldCaption() ?></div></div></th>
                                <?php } else { ?>
                                    <th data-name="Dt_Lancamento"><div><div id="elh_dizimos_Dt_Lancamento" class="dizimos_Dt_Lancamento">
                                                <div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dizimos->Dt_Lancamento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dizimos->Dt_Lancamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dizimos->Dt_Lancamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
                                            </div></div></th>
        <?php } ?>
    <?php } ?>		
                            <?php if ($dizimos->Vencimento->Visible) { // Vencimento  ?>
                                <?php if ($dizimos->SortUrl($dizimos->Vencimento) == "") { ?>
                                    <th data-name="Vencimento"><div id="elh_dizimos_Vencimento" class="dizimos_Vencimento"><div class="ewTableHeaderCaption"><?php echo $dizimos->Vencimento->FldCaption() ?></div></div></th>
                                <?php } else { ?>
                                    <th data-name="Vencimento"><div><div id="elh_dizimos_Vencimento" class="dizimos_Vencimento">
                                                <div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dizimos->Vencimento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dizimos->Vencimento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dizimos->Vencimento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
                                            </div></div></th>
                                <?php } ?>
    <?php } ?>		
    <?php
// Render list options (header, right)
    $dizimos_grid->ListOptions->Render("header", "right");
    ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $dizimos_grid->StartRec = 1;
                        $dizimos_grid->StopRec = $dizimos_grid->TotalRecs; // Show all records
// Restore number of post back records
                        if ($objForm) {
                            $objForm->Index = -1;
                            if ($objForm->HasValue($dizimos_grid->FormKeyCountName) && ($dizimos->CurrentAction == "gridadd" || $dizimos->CurrentAction == "gridedit" || $dizimos->CurrentAction == "F")) {
                                $dizimos_grid->KeyCount = $objForm->GetValue($dizimos_grid->FormKeyCountName);
                                $dizimos_grid->StopRec = $dizimos_grid->StartRec + $dizimos_grid->KeyCount - 1;
                            }
                        }
                        $dizimos_grid->RecCnt = $dizimos_grid->StartRec - 1;
                        if ($dizimos_grid->Recordset && !$dizimos_grid->Recordset->EOF) {
                            $dizimos_grid->Recordset->MoveFirst();
                            $bSelectLimit = EW_SELECT_LIMIT;
                            if (!$bSelectLimit && $dizimos_grid->StartRec > 1)
                                $dizimos_grid->Recordset->Move($dizimos_grid->StartRec - 1);
                        } elseif (!$dizimos->AllowAddDeleteRow && $dizimos_grid->StopRec == 0) {
                            $dizimos_grid->StopRec = $dizimos->GridAddRowCount;
                        }

// Initialize aggregate
                        $dizimos->RowType = EW_ROWTYPE_AGGREGATEINIT;
                        $dizimos->ResetAttrs();
                        $dizimos_grid->RenderRow();
                        if ($dizimos->CurrentAction == "gridadd")
                            $dizimos_grid->RowIndex = 0;
                        if ($dizimos->CurrentAction == "gridedit")
                            $dizimos_grid->RowIndex = 0;
                        while ($dizimos_grid->RecCnt < $dizimos_grid->StopRec) {
                            $dizimos_grid->RecCnt++;
                            if (intval($dizimos_grid->RecCnt) >= intval($dizimos_grid->StartRec)) {
                                $dizimos_grid->RowCnt++;
                                if ($dizimos->CurrentAction == "gridadd" || $dizimos->CurrentAction == "gridedit" || $dizimos->CurrentAction == "F") {
                                    $dizimos_grid->RowIndex++;
                                    $objForm->Index = $dizimos_grid->RowIndex;
                                    if ($objForm->HasValue($dizimos_grid->FormActionName))
                                        $dizimos_grid->RowAction = strval($objForm->GetValue($dizimos_grid->FormActionName));
                                    elseif ($dizimos->CurrentAction == "gridadd")
                                        $dizimos_grid->RowAction = "insert";
                                    else
                                        $dizimos_grid->RowAction = "";
                                }

                                // Set up key count
                                $dizimos_grid->KeyCount = $dizimos_grid->RowIndex;

                                // Init row class and style
                                $dizimos->ResetAttrs();
                                $dizimos->CssClass = "";
                                if ($dizimos->CurrentAction == "gridadd") {
                                    if ($dizimos->CurrentMode == "copy") {
                                        $dizimos_grid->LoadRowValues($dizimos_grid->Recordset); // Load row values
                                        $dizimos_grid->SetRecordKey($dizimos_grid->RowOldKey, $dizimos_grid->Recordset); // Set old record key
                                    } else {
                                        $dizimos_grid->LoadDefaultValues(); // Load default values
                                        $dizimos_grid->RowOldKey = ""; // Clear old key value
                                    }
                                } else {
                                    $dizimos_grid->LoadRowValues($dizimos_grid->Recordset); // Load row values
                                }
                                $dizimos->RowType = EW_ROWTYPE_VIEW; // Render view
                                if ($dizimos->CurrentAction == "gridadd") // Grid add
                                    $dizimos->RowType = EW_ROWTYPE_ADD; // Render add
                                if ($dizimos->CurrentAction == "gridadd" && $dizimos->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
                                    $dizimos_grid->RestoreCurrentRowFormValues($dizimos_grid->RowIndex); // Restore form values
                                if ($dizimos->CurrentAction == "gridedit") { // Grid edit
                                    if ($dizimos->EventCancelled) {
                                        $dizimos_grid->RestoreCurrentRowFormValues($dizimos_grid->RowIndex); // Restore form values
                                    }
                                    if ($dizimos_grid->RowAction == "insert")
                                        $dizimos->RowType = EW_ROWTYPE_ADD; // Render add
                                    else
                                        $dizimos->RowType = EW_ROWTYPE_EDIT; // Render edit
                                }
                                if ($dizimos->CurrentAction == "gridedit" && ($dizimos->RowType == EW_ROWTYPE_EDIT || $dizimos->RowType == EW_ROWTYPE_ADD) && $dizimos->EventCancelled) // Update failed
                                    $dizimos_grid->RestoreCurrentRowFormValues($dizimos_grid->RowIndex); // Restore form values
                                if ($dizimos->RowType == EW_ROWTYPE_EDIT) // Edit row
                                    $dizimos_grid->EditRowCnt++;
                                if ($dizimos->CurrentAction == "F") // Confirm row
                                    $dizimos_grid->RestoreCurrentRowFormValues($dizimos_grid->RowIndex); // Restore form values

                                    
// Set up row id / data-rowindex
                                $dizimos->RowAttrs = array_merge($dizimos->RowAttrs, array('data-rowindex' => $dizimos_grid->RowCnt, 'id' => 'r' . $dizimos_grid->RowCnt . '_dizimos', 'data-rowtype' => $dizimos->RowType));

                                // Render row
                                $dizimos_grid->RenderRow();

                                // Render list options
                                $dizimos_grid->RenderListOptions();

                                // Skip delete row / empty row for confirm page
                                if ($dizimos_grid->RowAction <> "delete" && $dizimos_grid->RowAction <> "insertdelete" && !($dizimos_grid->RowAction == "insert" && $dizimos->CurrentAction == "F" && $dizimos_grid->EmptyRow())) {
                                    ?>
                                    <tr<?php echo $dizimos->RowAttributes() ?>>
                                        <?php
// Render list options (body, left)
                                        $dizimos_grid->ListOptions->Render("body", "left", $dizimos_grid->RowCnt);
                                        ?>
                <?php if ($dizimos->Descricao->Visible) { // Descricao  ?>
                                            <td data-name="Descricao"<?php echo $dizimos->Descricao->CellAttributes() ?>>
                                                <?php if ($dizimos->RowType == EW_ROWTYPE_ADD) { // Add record  ?>
                                                    <span id="el<?php echo $dizimos_grid->RowCnt ?>_dizimos_Descricao" class="form-group dizimos_Descricao">
                                                        <input type="text" data-field="x_Descricao" name="x<?php echo $dizimos_grid->RowIndex ?>_Descricao" id="x<?php echo $dizimos_grid->RowIndex ?>_Descricao" size="60" maxlength="60" value="<?php echo $dizimos->Descricao->EditValue ?>"<?php echo $dizimos->Descricao->EditAttributes() ?>>
                                                    </span>
                                                    <input type="hidden" data-field="x_Descricao" name="o<?php echo $dizimos_grid->RowIndex ?>_Descricao" id="o<?php echo $dizimos_grid->RowIndex ?>_Descricao" value="<?php echo ew_HtmlEncode($dizimos->Descricao->OldValue) ?>">
                                                <?php } ?>
                                                <?php if ($dizimos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
                                                    <span id="el<?php echo $dizimos_grid->RowCnt ?>_dizimos_Descricao" class="form-group dizimos_Descricao">
                                                        <input type="text" data-field="x_Descricao" name="x<?php echo $dizimos_grid->RowIndex ?>_Descricao" id="x<?php echo $dizimos_grid->RowIndex ?>_Descricao" size="60" maxlength="60" value="<?php echo $dizimos->Descricao->EditValue ?>"<?php echo $dizimos->Descricao->EditAttributes() ?>>
                                                    </span>
                    <?php } ?>
                                                <?php if ($dizimos->RowType == EW_ROWTYPE_VIEW) { // View record  ?>
                                                    <span<?php echo $dizimos->Descricao->ViewAttributes() ?>>
                                                <?php echo $dizimos->Descricao->ListViewValue() ?></span>
                                                    <input type="hidden" data-field="x_Descricao" name="x<?php echo $dizimos_grid->RowIndex ?>_Descricao" id="x<?php echo $dizimos_grid->RowIndex ?>_Descricao" value="<?php echo ew_HtmlEncode($dizimos->Descricao->FormValue) ?>">
                                                    <input type="hidden" data-field="x_Descricao" name="o<?php echo $dizimos_grid->RowIndex ?>_Descricao" id="o<?php echo $dizimos_grid->RowIndex ?>_Descricao" value="<?php echo ew_HtmlEncode($dizimos->Descricao->OldValue) ?>">
                    <?php } ?>
                                                <a id="<?php echo $dizimos_grid->PageObjName . "_row_" . $dizimos_grid->RowCnt ?>"></a></td>
                                <?php } ?>
                                <?php if ($dizimos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
                                    <input type="hidden" data-field="x_Id" name="x<?php echo $dizimos_grid->RowIndex ?>_Id" id="x<?php echo $dizimos_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($dizimos->Id->CurrentValue) ?>">
                                    <input type="hidden" data-field="x_Id" name="o<?php echo $dizimos_grid->RowIndex ?>_Id" id="o<?php echo $dizimos_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($dizimos->Id->OldValue) ?>">
                                <?php } ?>
                                    <?php if ($dizimos->RowType == EW_ROWTYPE_EDIT || $dizimos->CurrentMode == "edit") { ?>
                                    <input type="hidden" data-field="x_Id" name="x<?php echo $dizimos_grid->RowIndex ?>_Id" id="x<?php echo $dizimos_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($dizimos->Id->CurrentValue) ?>">
                <?php } ?>
                <?php if ($dizimos->Receitas->Visible) { // Receitas  ?>
                                    <td data-name="Receitas"<?php echo $dizimos->Receitas->CellAttributes() ?>>
                                        <?php if ($dizimos->RowType == EW_ROWTYPE_ADD) { // Add record  ?>
                                            <span id="el<?php echo $dizimos_grid->RowCnt ?>_dizimos_Receitas" class="form-group dizimos_Receitas">
                                                <input type="text" data-field="x_Receitas" name="x<?php echo $dizimos_grid->RowIndex ?>_Receitas" id="x<?php echo $dizimos_grid->RowIndex ?>_Receitas" size="30" value="<?php echo $dizimos->Receitas->EditValue ?>"<?php echo $dizimos->Receitas->EditAttributes() ?>>
                                            </span>
                                            <input type="hidden" data-field="x_Receitas" name="o<?php echo $dizimos_grid->RowIndex ?>_Receitas" id="o<?php echo $dizimos_grid->RowIndex ?>_Receitas" value="<?php echo ew_HtmlEncode($dizimos->Receitas->OldValue) ?>">
                                        <?php } ?>
                                        <?php if ($dizimos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
                                            <span id="el<?php echo $dizimos_grid->RowCnt ?>_dizimos_Receitas" class="form-group dizimos_Receitas">
                                                <input type="text" data-field="x_Receitas" name="x<?php echo $dizimos_grid->RowIndex ?>_Receitas" id="x<?php echo $dizimos_grid->RowIndex ?>_Receitas" size="30" value="<?php echo $dizimos->Receitas->EditValue ?>"<?php echo $dizimos->Receitas->EditAttributes() ?>>
                                            </span>
                    <?php } ?>
                                        <?php if ($dizimos->RowType == EW_ROWTYPE_VIEW) { // View record  ?>
                                            <span<?php echo $dizimos->Receitas->ViewAttributes() ?>>
                                        <?php echo $dizimos->Receitas->ListViewValue() ?></span>
                                            <input type="hidden" data-field="x_Receitas" name="x<?php echo $dizimos_grid->RowIndex ?>_Receitas" id="x<?php echo $dizimos_grid->RowIndex ?>_Receitas" value="<?php echo ew_HtmlEncode($dizimos->Receitas->FormValue) ?>">
                                            <input type="hidden" data-field="x_Receitas" name="o<?php echo $dizimos_grid->RowIndex ?>_Receitas" id="o<?php echo $dizimos_grid->RowIndex ?>_Receitas" value="<?php echo ew_HtmlEncode($dizimos->Receitas->OldValue) ?>">
                                        <?php } ?>
                                    </td>
                <?php } ?>
                                            <?php if ($dizimos->FormaPagto->Visible) { // FormaPagto  ?>
                                    <td data-name="FormaPagto"<?php echo $dizimos->FormaPagto->CellAttributes() ?>>
                                                <?php if ($dizimos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
                                            <span id="el<?php echo $dizimos_grid->RowCnt ?>_dizimos_FormaPagto" class="form-group dizimos_FormaPagto">
                                                <select data-field="x_FormaPagto" id="x<?php echo $dizimos_grid->RowIndex ?>_FormaPagto" name="x<?php echo $dizimos_grid->RowIndex ?>_FormaPagto"<?php echo $dizimos->FormaPagto->EditAttributes() ?>>
                                                    <?php
                                                    if (is_array($dizimos->FormaPagto->EditValue)) {
                                                        $arwrk = $dizimos->FormaPagto->EditValue;
                                                        $rowswrk = count($arwrk);
                                                        $emptywrk = TRUE;
                                                        for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
                                                            $selwrk = (strval($dizimos->FormaPagto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
                                                            if ($selwrk <> "")
                                                                $emptywrk = FALSE;
                                                            ?>
                                                            <option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
                                                            <?php echo $arwrk[$rowcntwrk][1] ?>
                                                            </option>
                                <?php
                            }
                        }
                        if (@$emptywrk)
                            $dizimos->FormaPagto->OldValue = "";
                        ?>
                                                </select>
                                                <script type="text/javascript">
                                                    fdizimosgrid.Lists["x_FormaPagto"].Options = <?php echo (is_array($dizimos->FormaPagto->EditValue)) ? ew_ArrayToJson($dizimos->FormaPagto->EditValue, 1) : "[]" ?>;
                                                </script>
                                            </span>
                                            <input type="hidden" data-field="x_FormaPagto" name="o<?php echo $dizimos_grid->RowIndex ?>_FormaPagto" id="o<?php echo $dizimos_grid->RowIndex ?>_FormaPagto" value="<?php echo ew_HtmlEncode($dizimos->FormaPagto->OldValue) ?>">
                                                <?php } ?>
                                                <?php if ($dizimos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
                                            <span id="el<?php echo $dizimos_grid->RowCnt ?>_dizimos_FormaPagto" class="form-group dizimos_FormaPagto">
                                                <select data-field="x_FormaPagto" id="x<?php echo $dizimos_grid->RowIndex ?>_FormaPagto" name="x<?php echo $dizimos_grid->RowIndex ?>_FormaPagto"<?php echo $dizimos->FormaPagto->EditAttributes() ?>>
                                                    <?php
                                                    if (is_array($dizimos->FormaPagto->EditValue)) {
                                                        $arwrk = $dizimos->FormaPagto->EditValue;
                                                        $rowswrk = count($arwrk);
                                                        $emptywrk = TRUE;
                                                        for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
                                                            $selwrk = (strval($dizimos->FormaPagto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
                                                            if ($selwrk <> "")
                                                                $emptywrk = FALSE;
                                                            ?>
                                                            <option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
                                <?php echo $arwrk[$rowcntwrk][1] ?>
                                                            </option>
                                <?php
                            }
                        }
                        if (@$emptywrk)
                            $dizimos->FormaPagto->OldValue = "";
                        ?>
                                                </select>
                                                <script type="text/javascript">
                                                    fdizimosgrid.Lists["x_FormaPagto"].Options = <?php echo (is_array($dizimos->FormaPagto->EditValue)) ? ew_ArrayToJson($dizimos->FormaPagto->EditValue, 1) : "[]" ?>;
                                                </script>
                                            </span>
                                    <?php } ?>
                                    <?php if ($dizimos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
                                            <span<?php echo $dizimos->FormaPagto->ViewAttributes() ?>>
                                            <?php echo $dizimos->FormaPagto->ListViewValue() ?></span>
                                            <input type="hidden" data-field="x_FormaPagto" name="x<?php echo $dizimos_grid->RowIndex ?>_FormaPagto" id="x<?php echo $dizimos_grid->RowIndex ?>_FormaPagto" value="<?php echo ew_HtmlEncode($dizimos->FormaPagto->FormValue) ?>">
                                            <input type="hidden" data-field="x_FormaPagto" name="o<?php echo $dizimos_grid->RowIndex ?>_FormaPagto" id="o<?php echo $dizimos_grid->RowIndex ?>_FormaPagto" value="<?php echo ew_HtmlEncode($dizimos->FormaPagto->OldValue) ?>">
                                            <?php } ?>
                                    </td>
                <?php } ?>
                                        <?php if ($dizimos->Dt_Lancamento->Visible) { // Dt_Lancamento  ?>
                                    <td data-name="Dt_Lancamento"<?php echo $dizimos->Dt_Lancamento->CellAttributes() ?>>
                    <?php if ($dizimos->RowType == EW_ROWTYPE_ADD) { // Add record  ?>
                                            <span id="el<?php echo $dizimos_grid->RowCnt ?>_dizimos_Dt_Lancamento" class="form-group dizimos_Dt_Lancamento">
                                                <input type="text" data-field="x_Dt_Lancamento" name="x<?php echo $dizimos_grid->RowIndex ?>_Dt_Lancamento" id="x<?php echo $dizimos_grid->RowIndex ?>_Dt_Lancamento" size="15" value="<?php echo $dizimos->Dt_Lancamento->EditValue ?>"<?php echo $dizimos->Dt_Lancamento->EditAttributes() ?>>
                                            <?php if (!$dizimos->Dt_Lancamento->ReadOnly && !$dizimos->Dt_Lancamento->Disabled && @$dizimos->Dt_Lancamento->EditAttrs["readonly"] == "" && @$dizimos->Dt_Lancamento->EditAttrs["disabled"] == "") { ?>
                                                    <script type="text/javascript">
                                                        ew_CreateCalendar("fdizimosgrid", "x<?php echo $dizimos_grid->RowIndex ?>_Dt_Lancamento", "%d/%m/%Y");
                                                    </script>
                        <?php } ?>
                                            </span>
                                            <input type="hidden" data-field="x_Dt_Lancamento" name="o<?php echo $dizimos_grid->RowIndex ?>_Dt_Lancamento" id="o<?php echo $dizimos_grid->RowIndex ?>_Dt_Lancamento" value="<?php echo ew_HtmlEncode($dizimos->Dt_Lancamento->OldValue) ?>">
                                            <?php } ?>
                                        <?php if ($dizimos->RowType == EW_ROWTYPE_EDIT) { // Edit record  ?>
                                            <span id="el<?php echo $dizimos_grid->RowCnt ?>_dizimos_Dt_Lancamento" class="form-group dizimos_Dt_Lancamento">
                                                <input type="text" data-field="x_Dt_Lancamento" name="x<?php echo $dizimos_grid->RowIndex ?>_Dt_Lancamento" id="x<?php echo $dizimos_grid->RowIndex ?>_Dt_Lancamento" size="15" value="<?php echo $dizimos->Dt_Lancamento->EditValue ?>"<?php echo $dizimos->Dt_Lancamento->EditAttributes() ?>>
                                                <?php if (!$dizimos->Dt_Lancamento->ReadOnly && !$dizimos->Dt_Lancamento->Disabled && @$dizimos->Dt_Lancamento->EditAttrs["readonly"] == "" && @$dizimos->Dt_Lancamento->EditAttrs["disabled"] == "") { ?>
                                                    <script type="text/javascript">
                                                        ew_CreateCalendar("fdizimosgrid", "x<?php echo $dizimos_grid->RowIndex ?>_Dt_Lancamento", "%d/%m/%Y");
                                                    </script>
                                            <?php } ?>
                                            </span>
                                    <?php } ?>
                                    <?php if ($dizimos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
                                            <span<?php echo $dizimos->Dt_Lancamento->ViewAttributes() ?>>
                                            <?php echo $dizimos->Dt_Lancamento->ListViewValue() ?></span>
                                            <input type="hidden" data-field="x_Dt_Lancamento" name="x<?php echo $dizimos_grid->RowIndex ?>_Dt_Lancamento" id="x<?php echo $dizimos_grid->RowIndex ?>_Dt_Lancamento" value="<?php echo ew_HtmlEncode($dizimos->Dt_Lancamento->FormValue) ?>">
                                            <input type="hidden" data-field="x_Dt_Lancamento" name="o<?php echo $dizimos_grid->RowIndex ?>_Dt_Lancamento" id="o<?php echo $dizimos_grid->RowIndex ?>_Dt_Lancamento" value="<?php echo ew_HtmlEncode($dizimos->Dt_Lancamento->OldValue) ?>">
                                            <?php } ?>
                                    </td>
                <?php } ?>
                                        <?php if ($dizimos->Vencimento->Visible) { // Vencimento  ?>
                                    <td data-name="Vencimento"<?php echo $dizimos->Vencimento->CellAttributes() ?>>
                    <?php if ($dizimos->RowType == EW_ROWTYPE_ADD) { // Add record  ?>
                                            <span id="el<?php echo $dizimos_grid->RowCnt ?>_dizimos_Vencimento" class="form-group dizimos_Vencimento">
                                                <input type="text" data-field="x_Vencimento" name="x<?php echo $dizimos_grid->RowIndex ?>_Vencimento" id="x<?php echo $dizimos_grid->RowIndex ?>_Vencimento" size="15" value="<?php echo $dizimos->Vencimento->EditValue ?>"<?php echo $dizimos->Vencimento->EditAttributes() ?>>
                                            <?php if (!$dizimos->Vencimento->ReadOnly && !$dizimos->Vencimento->Disabled && @$dizimos->Vencimento->EditAttrs["readonly"] == "" && @$dizimos->Vencimento->EditAttrs["disabled"] == "") { ?>
                                                    <script type="text/javascript">
                                                        ew_CreateCalendar("fdizimosgrid", "x<?php echo $dizimos_grid->RowIndex ?>_Vencimento", "%d/%m/%Y");
                                                    </script>
                        <?php } ?>
                                            </span>
                                            <input type="hidden" data-field="x_Vencimento" name="o<?php echo $dizimos_grid->RowIndex ?>_Vencimento" id="o<?php echo $dizimos_grid->RowIndex ?>_Vencimento" value="<?php echo ew_HtmlEncode($dizimos->Vencimento->OldValue) ?>">
                                            <?php } ?>
                                        <?php if ($dizimos->RowType == EW_ROWTYPE_EDIT) { // Edit record  ?>
                                            <span id="el<?php echo $dizimos_grid->RowCnt ?>_dizimos_Vencimento" class="form-group dizimos_Vencimento">
                                                <input type="text" data-field="x_Vencimento" name="x<?php echo $dizimos_grid->RowIndex ?>_Vencimento" id="x<?php echo $dizimos_grid->RowIndex ?>_Vencimento" size="15" value="<?php echo $dizimos->Vencimento->EditValue ?>"<?php echo $dizimos->Vencimento->EditAttributes() ?>>
                                                <?php if (!$dizimos->Vencimento->ReadOnly && !$dizimos->Vencimento->Disabled && @$dizimos->Vencimento->EditAttrs["readonly"] == "" && @$dizimos->Vencimento->EditAttrs["disabled"] == "") { ?>
                                                    <script type="text/javascript">
                                                        ew_CreateCalendar("fdizimosgrid", "x<?php echo $dizimos_grid->RowIndex ?>_Vencimento", "%d/%m/%Y");
                                                    </script>
                                            <?php } ?>
                                            </span>
                                    <?php } ?>
                                    <?php if ($dizimos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
                                            <span<?php echo $dizimos->Vencimento->ViewAttributes() ?>>
                                        <?php echo $dizimos->Vencimento->ListViewValue() ?></span>
                                            <input type="hidden" data-field="x_Vencimento" name="x<?php echo $dizimos_grid->RowIndex ?>_Vencimento" id="x<?php echo $dizimos_grid->RowIndex ?>_Vencimento" value="<?php echo ew_HtmlEncode($dizimos->Vencimento->FormValue) ?>">
                                            <input type="hidden" data-field="x_Vencimento" name="o<?php echo $dizimos_grid->RowIndex ?>_Vencimento" id="o<?php echo $dizimos_grid->RowIndex ?>_Vencimento" value="<?php echo ew_HtmlEncode($dizimos->Vencimento->OldValue) ?>">
                                    <?php } ?>
                                    </td>
                <?php } ?>
                <?php
// Render list options (body, right)
                $dizimos_grid->ListOptions->Render("body", "right", $dizimos_grid->RowCnt);
                ?>
                                </tr>
                                <?php if ($dizimos->RowType == EW_ROWTYPE_ADD || $dizimos->RowType == EW_ROWTYPE_EDIT) { ?>
                                    <script type="text/javascript">
                                        fdizimosgrid.UpdateOpts(<?php echo $dizimos_grid->RowIndex ?>);
                                    </script>
                                <?php } ?>
                                <?php
                            }
                        } // End delete row checking
                        if ($dizimos->CurrentAction <> "gridadd" || $dizimos->CurrentMode == "copy")
                            if (!$dizimos_grid->Recordset->EOF)
                                $dizimos_grid->Recordset->MoveNext();
                    }
                    ?>
                    <?php
                    if ($dizimos->CurrentMode == "add" || $dizimos->CurrentMode == "copy" || $dizimos->CurrentMode == "edit") {
                        $dizimos_grid->RowIndex = '$rowindex$';
                        $dizimos_grid->LoadDefaultValues();

                        // Set row properties
                        $dizimos->ResetAttrs();
                        $dizimos->RowAttrs = array_merge($dizimos->RowAttrs, array('data-rowindex' => $dizimos_grid->RowIndex, 'id' => 'r0_dizimos', 'data-rowtype' => EW_ROWTYPE_ADD));
                        ew_AppendClass($dizimos->RowAttrs["class"], "ewTemplate");
                        $dizimos->RowType = EW_ROWTYPE_ADD;

                        // Render row
                        $dizimos_grid->RenderRow();

                        // Render list options
                        $dizimos_grid->RenderListOptions();
                        $dizimos_grid->StartRowCnt = 0;
                        ?>
                        <tr<?php echo $dizimos->RowAttributes() ?>>
        <?php
// Render list options (body, left)
        $dizimos_grid->ListOptions->Render("body", "left", $dizimos_grid->RowIndex);
        ?>
        <?php if ($dizimos->Descricao->Visible) { // Descricao  ?>
                                <td data-name="Descricao">
            <?php if ($dizimos->CurrentAction <> "F") { ?>
                                        <span id="el$rowindex$_dizimos_Descricao" class="form-group dizimos_Descricao">
                                            <input type="text" data-field="x_Descricao" name="x<?php echo $dizimos_grid->RowIndex ?>_Descricao" id="x<?php echo $dizimos_grid->RowIndex ?>_Descricao" size="60" maxlength="60" value="<?php echo $dizimos->Descricao->EditValue ?>"<?php echo $dizimos->Descricao->EditAttributes() ?>>
                                        </span>
            <?php } else { ?>
                                        <span id="el$rowindex$_dizimos_Descricao" class="form-group dizimos_Descricao">
                                            <span<?php echo $dizimos->Descricao->ViewAttributes() ?>>
                                                <p class="form-control-static"><?php echo $dizimos->Descricao->ViewValue ?></p></span>
                                        </span>
                                        <input type="hidden" data-field="x_Descricao" name="x<?php echo $dizimos_grid->RowIndex ?>_Descricao" id="x<?php echo $dizimos_grid->RowIndex ?>_Descricao" value="<?php echo ew_HtmlEncode($dizimos->Descricao->FormValue) ?>">
            <?php } ?>
                                    <input type="hidden" data-field="x_Descricao" name="o<?php echo $dizimos_grid->RowIndex ?>_Descricao" id="o<?php echo $dizimos_grid->RowIndex ?>_Descricao" value="<?php echo ew_HtmlEncode($dizimos->Descricao->OldValue) ?>">
                                </td>
                                <?php } ?>
        <?php if ($dizimos->Receitas->Visible) { // Receitas  ?>
                                <td data-name="Receitas">
            <?php if ($dizimos->CurrentAction <> "F") { ?>
                                        <span id="el$rowindex$_dizimos_Receitas" class="form-group dizimos_Receitas">
                                            <input type="text" data-field="x_Receitas" name="x<?php echo $dizimos_grid->RowIndex ?>_Receitas" id="x<?php echo $dizimos_grid->RowIndex ?>_Receitas" size="30" value="<?php echo $dizimos->Receitas->EditValue ?>"<?php echo $dizimos->Receitas->EditAttributes() ?>>
                                        </span>
            <?php } else { ?>
                                        <span id="el$rowindex$_dizimos_Receitas" class="form-group dizimos_Receitas">
                                            <span<?php echo $dizimos->Receitas->ViewAttributes() ?>>
                                                <p class="form-control-static"><?php echo $dizimos->Receitas->ViewValue ?></p></span>
                                        </span>
                                        <input type="hidden" data-field="x_Receitas" name="x<?php echo $dizimos_grid->RowIndex ?>_Receitas" id="x<?php echo $dizimos_grid->RowIndex ?>_Receitas" value="<?php echo ew_HtmlEncode($dizimos->Receitas->FormValue) ?>">
            <?php } ?>
                                    <input type="hidden" data-field="x_Receitas" name="o<?php echo $dizimos_grid->RowIndex ?>_Receitas" id="o<?php echo $dizimos_grid->RowIndex ?>_Receitas" value="<?php echo ew_HtmlEncode($dizimos->Receitas->OldValue) ?>">
                                </td>
                                        <?php } ?>
                                        <?php if ($dizimos->FormaPagto->Visible) { // FormaPagto ?>
                                <td data-name="FormaPagto">
                                            <?php if ($dizimos->CurrentAction <> "F") { ?>
                                        <span id="el$rowindex$_dizimos_FormaPagto" class="form-group dizimos_FormaPagto">
                                            <select data-field="x_FormaPagto" id="x<?php echo $dizimos_grid->RowIndex ?>_FormaPagto" name="x<?php echo $dizimos_grid->RowIndex ?>_FormaPagto"<?php echo $dizimos->FormaPagto->EditAttributes() ?>>
                                                <?php
                                                if (is_array($dizimos->FormaPagto->EditValue)) {
                                                    $arwrk = $dizimos->FormaPagto->EditValue;
                                                    $rowswrk = count($arwrk);
                                                    $emptywrk = TRUE;
                                                    for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
                                                        $selwrk = (strval($dizimos->FormaPagto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
                                                        if ($selwrk <> "")
                                                            $emptywrk = FALSE;
                                                        ?>
                                                        <option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
                        <?php echo $arwrk[$rowcntwrk][1] ?>
                                                        </option>
                        <?php
                    }
                }
                if (@$emptywrk)
                    $dizimos->FormaPagto->OldValue = "";
                ?>
                                            </select>
                                            <script type="text/javascript">
                                                fdizimosgrid.Lists["x_FormaPagto"].Options = <?php echo (is_array($dizimos->FormaPagto->EditValue)) ? ew_ArrayToJson($dizimos->FormaPagto->EditValue, 1) : "[]" ?>;
                                            </script>
                                        </span>
                                <?php } else { ?>
                                        <span id="el$rowindex$_dizimos_FormaPagto" class="form-group dizimos_FormaPagto">
                                            <span<?php echo $dizimos->FormaPagto->ViewAttributes() ?>>
                                                <p class="form-control-static"><?php echo $dizimos->FormaPagto->ViewValue ?></p></span>
                                        </span>
                                        <input type="hidden" data-field="x_FormaPagto" name="x<?php echo $dizimos_grid->RowIndex ?>_FormaPagto" id="x<?php echo $dizimos_grid->RowIndex ?>_FormaPagto" value="<?php echo ew_HtmlEncode($dizimos->FormaPagto->FormValue) ?>">
                                        <?php } ?>
                                    <input type="hidden" data-field="x_FormaPagto" name="o<?php echo $dizimos_grid->RowIndex ?>_FormaPagto" id="o<?php echo $dizimos_grid->RowIndex ?>_FormaPagto" value="<?php echo ew_HtmlEncode($dizimos->FormaPagto->OldValue) ?>">
                                </td>
                                    <?php } ?>
                                    <?php if ($dizimos->Dt_Lancamento->Visible) { // Dt_Lancamento ?>
                                <td data-name="Dt_Lancamento">
                                    <?php if ($dizimos->CurrentAction <> "F") { ?>
                                        <span id="el$rowindex$_dizimos_Dt_Lancamento" class="form-group dizimos_Dt_Lancamento">
                                            <input type="text" data-field="x_Dt_Lancamento" name="x<?php echo $dizimos_grid->RowIndex ?>_Dt_Lancamento" id="x<?php echo $dizimos_grid->RowIndex ?>_Dt_Lancamento" size="15" value="<?php echo $dizimos->Dt_Lancamento->EditValue ?>"<?php echo $dizimos->Dt_Lancamento->EditAttributes() ?>>
                <?php if (!$dizimos->Dt_Lancamento->ReadOnly && !$dizimos->Dt_Lancamento->Disabled && @$dizimos->Dt_Lancamento->EditAttrs["readonly"] == "" && @$dizimos->Dt_Lancamento->EditAttrs["disabled"] == "") { ?>
                                                <script type="text/javascript">
                                                    ew_CreateCalendar("fdizimosgrid", "x<?php echo $dizimos_grid->RowIndex ?>_Dt_Lancamento", "%d/%m/%Y");
                                                </script>
                <?php } ?>
                                        </span>
                                <?php } else { ?>
                                        <span id="el$rowindex$_dizimos_Dt_Lancamento" class="form-group dizimos_Dt_Lancamento">
                                            <span<?php echo $dizimos->Dt_Lancamento->ViewAttributes() ?>>
                                                <p class="form-control-static"><?php echo $dizimos->Dt_Lancamento->ViewValue ?></p></span>
                                        </span>
                                        <input type="hidden" data-field="x_Dt_Lancamento" name="x<?php echo $dizimos_grid->RowIndex ?>_Dt_Lancamento" id="x<?php echo $dizimos_grid->RowIndex ?>_Dt_Lancamento" value="<?php echo ew_HtmlEncode($dizimos->Dt_Lancamento->FormValue) ?>">
                                        <?php } ?>
                                    <input type="hidden" data-field="x_Dt_Lancamento" name="o<?php echo $dizimos_grid->RowIndex ?>_Dt_Lancamento" id="o<?php echo $dizimos_grid->RowIndex ?>_Dt_Lancamento" value="<?php echo ew_HtmlEncode($dizimos->Dt_Lancamento->OldValue) ?>">
                                </td>
                                    <?php } ?>
                                    <?php if ($dizimos->Vencimento->Visible) { // Vencimento ?>
                                <td data-name="Vencimento">
                                    <?php if ($dizimos->CurrentAction <> "F") { ?>
                                        <span id="el$rowindex$_dizimos_Vencimento" class="form-group dizimos_Vencimento">
                                            <input type="text" data-field="x_Vencimento" name="x<?php echo $dizimos_grid->RowIndex ?>_Vencimento" id="x<?php echo $dizimos_grid->RowIndex ?>_Vencimento" size="15" value="<?php echo $dizimos->Vencimento->EditValue ?>"<?php echo $dizimos->Vencimento->EditAttributes() ?>>
                <?php if (!$dizimos->Vencimento->ReadOnly && !$dizimos->Vencimento->Disabled && @$dizimos->Vencimento->EditAttrs["readonly"] == "" && @$dizimos->Vencimento->EditAttrs["disabled"] == "") { ?>
                                                <script type="text/javascript">
                                                    ew_CreateCalendar("fdizimosgrid", "x<?php echo $dizimos_grid->RowIndex ?>_Vencimento", "%d/%m/%Y");
                                                </script>
                <?php } ?>
                                        </span>
                                <?php } else { ?>
                                        <span id="el$rowindex$_dizimos_Vencimento" class="form-group dizimos_Vencimento">
                                            <span<?php echo $dizimos->Vencimento->ViewAttributes() ?>>
                                                <p class="form-control-static"><?php echo $dizimos->Vencimento->ViewValue ?></p></span>
                                        </span>
                                        <input type="hidden" data-field="x_Vencimento" name="x<?php echo $dizimos_grid->RowIndex ?>_Vencimento" id="x<?php echo $dizimos_grid->RowIndex ?>_Vencimento" value="<?php echo ew_HtmlEncode($dizimos->Vencimento->FormValue) ?>">
            <?php } ?>
                                    <input type="hidden" data-field="x_Vencimento" name="o<?php echo $dizimos_grid->RowIndex ?>_Vencimento" id="o<?php echo $dizimos_grid->RowIndex ?>_Vencimento" value="<?php echo ew_HtmlEncode($dizimos->Vencimento->OldValue) ?>">
                                </td>
                        <?php } ?>
                        <?php
// Render list options (body, right)
                        $dizimos_grid->ListOptions->Render("body", "right", $dizimos_grid->RowCnt);
                        ?>
                        <script type="text/javascript">
                            fdizimosgrid.UpdateOpts(<?php echo $dizimos_grid->RowIndex ?>);
                        </script>
                        </tr>
                    <?php
                }
                ?>
                    </tbody>
                </table>
                <?php if ($dizimos->CurrentMode == "add" || $dizimos->CurrentMode == "copy") { ?>
                    <input type="hidden" name="a_list" id="a_list" value="gridinsert">
                    <input type="hidden" name="<?php echo $dizimos_grid->FormKeyCountName ?>" id="<?php echo $dizimos_grid->FormKeyCountName ?>" value="<?php echo $dizimos_grid->KeyCount ?>">
                    <?php echo $dizimos_grid->MultiSelectKey ?>
                <?php } ?>
    <?php if ($dizimos->CurrentMode == "edit") { ?>
                    <input type="hidden" name="a_list" id="a_list" value="gridupdate">
                    <input type="hidden" name="<?php echo $dizimos_grid->FormKeyCountName ?>" id="<?php echo $dizimos_grid->FormKeyCountName ?>" value="<?php echo $dizimos_grid->KeyCount ?>">
                <?php echo $dizimos_grid->MultiSelectKey ?>
            <?php } ?>
            <?php if ($dizimos->CurrentMode == "") { ?>
                    <input type="hidden" name="a_list" id="a_list" value="">
            <?php } ?>
                <input type="hidden" name="detailpage" value="fdizimosgrid">
            </div>
    <?php
// Close recordset
    if ($dizimos_grid->Recordset)
        $dizimos_grid->Recordset->Close();
    ?>
        </div>
    </div>
    <?php } ?>
    <?php if ($dizimos_grid->TotalRecs == 0 && $dizimos->CurrentAction == "") { // Show other options ?>
    <div class="ewListOtherOptions">
    <?php
    foreach ($dizimos_grid->OtherOptions as &$option) {
        $option->ButtonClass = "";
        $option->Render("body", "");
    }
    ?>
    </div>
    <div class="clearfix"></div>
<?php } ?>
<?php if ($dizimos->Export == "") { ?>
    <script type="text/javascript">
        fdizimosgrid.Init();
        $(document).ready(function ($) {
            $("#ajuda").click(function () {
                bootbox.dialog({title: "Informações de Ajuda", message: '<?php echo $help ?>', buttons: {success: {label: "Fechar"}}});
            });
        });
    </script>
<?php } ?>
<?php
$dizimos_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
    echo ew_DebugMsg();
?>
<?php
$dizimos_grid->Page_Terminate();
?>
