<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script type="text/javascript">
    function resetear(){
      axios.post('http://ticketlive.dvp/api/reset-password', {
        token: '<?=$_GET["token"];?>',
        email: 'cesar@inblackmark.com',
        password: 'ABC123485',
        password_confirmation: 'ABC123485'
      })
      .then(function (response) {
        console.log(response);
      })
      .catch(function (error) {
        console.log(error);
      });
    }
    function verificarCorreo(){
      axios.get('<?=base64_decode($_GET["verificarCorreo"]);?>')
      .then(function (response) {
        console.log(response);
      })
      .catch(function (error) {
        console.log(error);
      });
    }
    </script>
  </head>
  <body>
    <h1>Enviar datos</h1>
    <?php
    if(isset($_GET["token"])){
      ?>
      <button type="button" onclick="resetear();">Resetear la contraseña</button>
      <?php
    }else if(isset($_GET["verificarCorreo"])){
      ?>
      <button type="button" onclick="verificarCorreo();">Verificar correo</button>
      <?php
    }
    ?>

  </body>
</html>
