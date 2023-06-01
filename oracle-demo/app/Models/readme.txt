Tabla Cargo {
    se agrega campo ESTADO
}
Tabla Departamento {
    se agrega campo CANTIDAD_HUESPEDES
}

Tabla Sucursal {
   se arregla nombre de Sucursal (sucural)
}

Tabla Transportista {
   se cambia nombre de campo RUT por DNI (todos se llaman así en las demás tablas)
}

Tabla Usuario_test {
  Se elimina
}

Tabla Tipo_servicios {
  Se agregan campo deleted y deleted_at
}

Tabla cargos {
  Se agregan campo deleted y deleted_at
}


tabla empleados{
  fecha_nacimiento se cambia a date;
}