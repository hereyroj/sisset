<h4>Documento</h4>
<p>{{$peticionario->getUsuarioTipoDocumento->name}}</p>
<h4>Número documento</h4>
<p>{{$peticionario->numero_documento}}</p>
<h4>Nombre</h4>
<p>{{$peticionario->nombre_completo}}</p>
<h4>Teléfono</h4>
<p>{{$peticionario->telefono}}</p>
<h4>Correo notificación</h4>
<p>{{$peticionario->correo_notificacion}}</p>
<h4>Dpto residencia</h4>
<p>{{$peticionario->couldHaveDpto->name}}</p>
<h4>Municipio residencia</h4>
<p>{{$peticionario->couldHaveMunicipio->name}}</p>
<h4>Dirección residencia</h4>
<p>{{$peticionario->direccion_residencia}}</p>