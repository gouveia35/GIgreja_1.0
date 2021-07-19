
<?php
    require_once("zipar.class.php");
    require_once("db1.php");
?>

<html lang="pt-br">
   <head>
        <title>Upload e Download de Arquivos</title>
        <meta charset="utf-8">
   </head>

   <?php 
       if (isset($_POST['botao'])) {
         # code...
        $arq = $_FILES['arquivo']['name'];

        $arq = str_replace(" ", "_", $arq);
        $arq = str_replace("ç", "c", $arq);

        if (file_exists("arquivos/$arq")) {
          # code...
          $a = 1;

          while (file_exists("arquivos/[$a]$arq")) {
            # code...
            $a++;
          }
          $arq = "[".$a."]".$arq;
        }
        if (move_uploaded_file($_FILES['arquivo']['tmp_name'], "arquivos/".$arq)) {
          # code...
          $zip = new zipar();
          $zip->ziparArquivos($arq, $arq.".zip", "arquivos/");
          unlink("arquivos/$arq");

          $sqlInto = "INSERT INTO arquivos (nome) VALUES (:nome)";

          try{
            $into = $db->prepare($sqlInto);
            $into->bindValue(":nome",$arq.".zip",PDO::PARAM_STR);
            
            if ($into->execute()) {
              # code...
              echo '<div class="res"> Upload realizado com sucesso!!<span></span></div>';
            }
          }catch(PDOException $e){
            echo $e->getMessage();
          }

        }else{
          echo '<div class="res"> Erro ao realizar o Upload!!<span>X</span></div>';
        }
       }
  
   ?>

      <body>
        <form method="post" action="" enctype="multipart/form-data" name="Upload">
          <div id="nome_arquivo"> 
            <span class="nome_arquivo"></span>
            <span><img src="arquivos/imagens/lupa24x24.png" width="40"></span>
            <input type="file" name="arquivo" required>
            <input type="submit" name="botao" value="Enviar arquivo" />
          </div>
            
        </form>

        <table cellpadding="3" cellspacing="1" border="1">
          <thead>
            <tr>
              <td width="50">Código</td>
              <td width="400">Nome</td>
              <td width="130">Download</td>
            </tr>  
          </thead>

      <tbody>
           
          <?php
              $sqlReady = "SELECT * FROM arquivos";

          try{
            $ready = $db->prepare($sqlReady);
            $ready->execute();
          }catch(PDOException $e){
            echo $e->getMessage();
          }

          while ($rs = $ready->fetch(PDO::FETCH_OBJ)) { 
          ?>
            <tr>
              <td><?php echo $rs->id   ?></td>
              <td><?php echo $rs->nome ?></td>
              <td><a href="arquivos/<?php echo $rs->nome ?>">Download</a></td>
            </tr>

            <?php
          }
          ?>


      </tbody>

        </table>
      </body>
    </html>