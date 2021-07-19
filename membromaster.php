<?php

// Foto
// Matricula
// Nome
// Sexo
// EstadoCivil
// CPF
// CargoMinisterial
// Funcao

?>
<?php if ($membro->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $membro->TableCaption() ?></h4> -->
<table id="tbl_membromaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($membro->Foto->Visible) { // Foto ?>
		<tr id="r_Foto">
			<td><?php echo $membro->Foto->FldCaption() ?></td>
			<td<?php echo $membro->Foto->CellAttributes() ?>>
<span id="el_membro_Foto" class="form-group">
<span>
<?php echo ew_GetFileViewTag($membro->Foto, $membro->Foto->ListViewValue()) ?>
</span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($membro->Matricula->Visible) { // Matricula ?>
		<tr id="r_Matricula">
			<td><?php echo $membro->Matricula->FldCaption() ?></td>
			<td<?php echo $membro->Matricula->CellAttributes() ?>>
<span id="el_membro_Matricula" class="form-group">
<span<?php echo $membro->Matricula->ViewAttributes() ?>>
<?php echo $membro->Matricula->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($membro->Nome->Visible) { // Nome ?>
		<tr id="r_Nome">
			<td><?php echo $membro->Nome->FldCaption() ?></td>
			<td<?php echo $membro->Nome->CellAttributes() ?>>
<span id="el_membro_Nome" class="form-group">
<span<?php echo $membro->Nome->ViewAttributes() ?>>
<?php echo $membro->Nome->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($membro->Sexo->Visible) { // Sexo ?>
		<tr id="r_Sexo">
			<td><?php echo $membro->Sexo->FldCaption() ?></td>
			<td<?php echo $membro->Sexo->CellAttributes() ?>>
<span id="el_membro_Sexo" class="form-group">
<span<?php echo $membro->Sexo->ViewAttributes() ?>>
<?php echo $membro->Sexo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($membro->EstadoCivil->Visible) { // EstadoCivil ?>
		<tr id="r_EstadoCivil">
			<td><?php echo $membro->EstadoCivil->FldCaption() ?></td>
			<td<?php echo $membro->EstadoCivil->CellAttributes() ?>>
<span id="el_membro_EstadoCivil" class="form-group">
<span<?php echo $membro->EstadoCivil->ViewAttributes() ?>>
<?php echo $membro->EstadoCivil->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($membro->CPF->Visible) { // CPF ?>
		<tr id="r_CPF">
			<td><?php echo $membro->CPF->FldCaption() ?></td>
			<td<?php echo $membro->CPF->CellAttributes() ?>>
<span id="el_membro_CPF" class="form-group">
<span<?php echo $membro->CPF->ViewAttributes() ?>>
<?php echo $membro->CPF->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($membro->CargoMinisterial->Visible) { // CargoMinisterial ?>
		<tr id="r_CargoMinisterial">
			<td><?php echo $membro->CargoMinisterial->FldCaption() ?></td>
			<td<?php echo $membro->CargoMinisterial->CellAttributes() ?>>
<span id="el_membro_CargoMinisterial" class="form-group">
<span<?php echo $membro->CargoMinisterial->ViewAttributes() ?>>
<?php echo $membro->CargoMinisterial->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($membro->Funcao->Visible) { // Funcao ?>
		<tr id="r_Funcao">
			<td><?php echo $membro->Funcao->FldCaption() ?></td>
			<td<?php echo $membro->Funcao->CellAttributes() ?>>
<span id="el_membro_Funcao" class="form-group">
<span<?php echo $membro->Funcao->ViewAttributes() ?>>
<?php echo $membro->Funcao->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
