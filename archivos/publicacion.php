<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/estilos.css">
<script>
  var pID=<?php echo $_POST["ID"]; ?>;
  var dueño;
  var usr;
  $(document).ready(function(){
    $.get("datosDeLaPublicacion.php?ID="+pID, function(datos){
      var jDatos= JSON.parse(datos);
      $("#titulo").text(jDatos.tit);
      $("#ciudad").text(jDatos.ciu);
      $("#categoria").text(jDatos.cat);
      $("#descripcion").text(jDatos.desc);
      if (jDatos.logueado) {
        if (jDatos.owner==jDatos.usr) {
          $(".delDueño").prop('hidden', false);
        } else {
          $(".noDueño").prop('hidden', false);
        }
      }
      dueño=jDatos.owner;
      usr=jDatos.usr;
      cargarPreguntas();
    });
  });
  $("#preguntaForm").submit(function(){
    var datosFormulario= $(this).serialize();
    $.post("publicacionValidar.php?pregunta="+pID, datosFormulario, publPregResp);
    return false;
  });
  function publPregResp(datos){
    if (datos=="exito") {
      $("#inpPregunta").val("");
      $("#cajaPreguntas").html('');
      cargarPreguntas();
    } else {
      alert(datos);
    }
  }
  $(document).on('submit','#respuestaForm', function(){
    var datosFormulario= $(this).serialize();
    $.post("publicacionValidar.php?respuesta=resp", datosFormulario, publResp);
    return false;
  });
  function publResp(datos){
    if (datos=="exito") {
      $("#cajaPreguntas").html('');
      cargarPreguntas();
    } else {
      alert(datos);
    }
  }
  function cargarPreguntas(){
    $.get("publicacionValidar.php?cargar="+pID, function(datos){
      var jDatos= JSON.parse(datos);
      for (var x in jDatos) {
        var user= $("<b></b>").append(jDatos[x][3]+" - ");
        if (jDatos[x][3]==usr) {
          var color= $("<span></span>").text(jDatos[x][3]).addClass("letraAzul");
          user= $("<b></b>").append(color," - ");
        }
        var crearPreg= $("<p></p>").append(user,jDatos[x][1]);
        var crearResp= "";
        if (jDatos[x][2]=="") {
          if (dueño==usr) {
            crearResp= $("<a></a>").addClass("puntero botonResp").text("Responder").attr("onclick","eventoResponder(this,"+jDatos[x][0]+")");
          }
        } else {
          var negrita= $("<b></b>").append(dueño+" - ");
          if (dueño==usr) {
            var color= $("<span></span>").text(dueño).addClass("letraAzul");
            negrita= $("<b></b>").append(color," - ");
          }
          crearResp= $("<p></p>").append(negrita,jDatos[x][2]);
        }
        var crearB= $("<div class='col-md-11 col-md-offset-1'></div>").append(crearResp);
        var crearA= $("<div class='row'></div>").append(crearB);
        var crearDiv= $("<div class='separar comentDiv'></div>").append(crearPreg,crearA);
        $("#cajaPreguntas").append(crearDiv);
      }
    });
  }
  function eventoResponder(yo,prueba){
    $(".botonResp").prop("hidden",false);
    $("#respuestaForm").remove();
    $(yo).prop("hidden",true);
    var inpConID= $('<input>').prop({"hidden":true, "name":"inpConID"}).val(prueba);
    var subBot=$('<button type="submit" class="btn btn-default">Publicar</button>');
    var divBa=$('<div class="col-md-3 col-md-offset-5"></div>').append(subBot);
    var divB=$('<div class="row"></div>').append(divBa);
    var inpResp=$('<textarea name="inpRespuesta" rows="3" class="form-control" placeholder="Escriba su respuesta" required style="resize: none;" maxlength="200" id="inpRespuesta"></textarea>');
    var divA=$('<div class="form-group"></div>').append(inpResp);
    var formulario=$('<form action="" method="post" id="respuestaForm"></form>').append(divA,divB,inpConID);
    $(yo).after(formulario);
  }
</script>
</head>
<body>
    <div class="row">
      <div class="col-md-10 col-md-offset-1 transparente">
        <div class="row publicacion">
          <div class="col-md-7 col-md-offset-1">
            <h3 id="titulo"></h3>
            <img src="css/dog-bag.jpg" style="max-width:400px;max-height:400px;" class="center-block">
            <div class="row separar">
              <label class="label label-primary" id="ciudad"></label>
              <label class="label label-info" id="categoria"></label>
            </div>
            <div class="">
              <p class="text-justify" id="descripcion"></p>
            </div>
          </div>
          <div class="col-md-3 col-md-offset-1">
            <div hidden class="delDueño">
              <button type="button" name="button" class="btn btn-default">Editar gauchada</button>
              <button type="button" name="button" class="btn btn-default">Ver postulantes</button>
            </div>
            <div hidden class="noDueño">
              <button type="button" name="button" class="btn btn-default">Postularse</button>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-10 col-md-offset-1">
            <h3>Preguntas</h3>
            <div class="separar" id="cajaPreguntas">

            </div>
            <div hidden class="noDueño">
              <form class="" action="" method="post" id="preguntaForm">
                <div class="form-group">
                  <textarea name="inpPregunta" rows="3" class="form-control" placeholder="Escriba su pregunta" required style="resize: none;" maxlength="200" id="inpPregunta"></textarea>
                </div>
                <div class="row">
                  <div class="col-sm-offset-5 col-sm-3">
                    <div class="form-group">
                      <button type="submit" class="btn btn-default">Publicar</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</body>
</html>
