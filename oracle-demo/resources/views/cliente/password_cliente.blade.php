@extends('layout.admin.layout_admin')
@section('contenido-admin')

<div class="min-height-200px">
	<div class="page-header">
		<div class="row">
			<div class="col-md-6 col-sm-12">
				<div class="title">
					<h4><?= isset($title) ? $title  : '' ?></h4>
				</div>
				<nav aria-label="breadcrumb" role="navigation">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="/gestion-reservas">Dashboard</a></li>
						<li class="breadcrumb-item"><a href="/gestion-reservas/mi-perfil">Mi Perfil</a></li>
						<li class="breadcrumb-item active" aria-current="page"><?= isset($title) ? $title  : '' ?> </li>
					</ol>
				</nav>
			</div>
		</div>

	</div>
	<!-- Simple Datatable start -->
	<div class="card-box mb-30">
		<div class="pd-20">
			<h4 class="text-blue h4 text-center"><?= isset($title_form) ? $title_form : 'Formulario de Cambio de Contraseña' ?></h4>
			<hr>
		</div>
		<div class="p-4">
			<form action="<?= isset($action) ? $action : '' ?>" method="post" id="formulario">
				{{ csrf_field() }}
				<input type="hidden" name="form" id="form" value="1">
				<div class="row">
					<div class="col-lg-3 col-md-2"></div>
					<div class="col-lg-6 col-md-8">
						<div class="form-group">
							<label for="old_password">Contraseña Actual <span class="text-danger">*</span></label>
							<input type="password" id="old_password" name="old_password" class="form-control" placeholder="Ingrese Contraseña Actual...">
							<small id="invalid_old_password" class="text-danger"></small>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-3 col-md-2"></div>
					<div class="col-lg-6 col-md-8">
						<div class="form-group">
							<label for="password">Nueva Contraseña <span class="text-danger">*</span></label>
							<input type="password" id="password" name="password" class="form-control" placeholder="Ingrese Nueva Contraseña...">
							<small id="invalid_password" class="text-danger"></small>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-3 col-md-2"></div>
					<div class="col-lg-6 col-md-8">
						<div class="form-group">
							<label for="password_confirm">Confirmar Contraseña <span class="text-danger">*</span></label>
							<input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Confirme Nueva Contraseña...">
							<small id="invalid_password_confirm" class="text-danger"></small>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 text-right">
						<hr>
						<button class="btn btn-primary" type="button" id="btn_submit"><i class="fa fa-save"></i> Modificar</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>



@section('js_content')
@include('./generalJS')
<script>
	$(document).ready(function() {
		validaCampos($('#old_password').val(), 'old_password', '', true);
		validaPassword($('#password').val(), 'password');
		validaPassword($('#password_confirm').val(), 'password_confirm');
		$('#password').keyup(function() {
			validaPassword($('#password').val(), 'password');
		});
		$('#password_confirm').keyup(function() {
			validaPassword($('#password_confirm').val(), 'password_confirm');
		});
		$('#old_password').keyup(function() {
			validaCampos($('#old_password').val(), 'old_password', '', true);
		});

		function validaPassword(texto, id, msg = 'Campo Obligatorio') {
			if ($("#" + id)) {
				if (texto !== '') {
					if (texto.length < 4) {
						$("#" + id).css('border-color', 'red');
						$("#invalid_" + id).text('Contraseña debe tener al menos 4 Caracteres');
						return 0;
					} else {
						if (id == 'password_confirm') {
							if ($('#password').val() !== texto) {
								$("#" + id).css('border-color', 'red');
								$("#invalid_" + id).text('Las Contraseñas No Coínciden');
								return 0;
							} else {
								$("#" + id).css('border-color', 'green');
								$("#invalid_" + id).text('');
								return 1;
							}
						} else {
							if (id == 'password') {
								if ($('#password_confirm').val() !== texto) {
									$("#" + id).css('border-color', 'green');
									$("#invalid_" + id).text('');
									$("#password_confirm").css('border-color', 'red');
									$("#invalid_password_confirm").text('Las Contraseñas No Coínciden');
									return 0;
								} else {
									$("#" + id).css('border-color', 'green');
									$("#invalid_" + id).text('');
									$("#password_confirm").css('border-color', 'green');
									$("#invalid_password_confirm").text('');
									return 1;
								}
							}
						}
					}
				} else {
					$("#" + id).css('border-color', 'red');
					$("#invalid_" + id).text(msg);
					return 0;
				}
			}
		}

		$("#btn_submit").click(function() {
			let old_password = validaCampos($('#old_password').val(), 'old_password', '', true);
			let password = validaPassword($("#password").val(), 'password');
			let password_confirm = validaPassword($("#password_confirm").val(), 'password_confirm');
			if (old_password == 1 && password == 1 && password_confirm == 1) {
				cargando('Validando Información...')
				$("#formulario").submit();
			} else {
				toastr["error"](`Se encontraron 1 o más Campos con Problemas. Corrija e Intente nuevamente`, "Error de Validación")
			}
		});
	});
</script>
@endsection
@endsection