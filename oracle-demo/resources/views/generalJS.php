<script>
    //tipo = texto_min, texto, numero, celular, telefono, rut, estado,
    function validaCampos(texto, id, tipo = 'texto', obligatorio = true, msg = "Campo Obligatorio") {

        if ($('#' + id)) {

            if (texto !== '') {

                switch (tipo) {
                    case 'texto_min':
                        texto = texto.trim();
                        if (texto.length < 3) {
                            $("#" + id).css('border-color', 'red');
                            $("#invalid_" + id).html('El largo Mínimo de 3 Caracteres');
                            return 0;
                        } else {
                            if (texto.length <= 254) {
                                $("#" + id).css('border-color', 'green');
                                $("#invalid_" + id).html('');
                                return 1;
                            } else {
                                $("#" + id).css('border-color', 'red');
                                $("#invalid_" + id).html('Supera largo máximo permitido');
                                return 0;
                            }
                        }
                        break;
                    case 'texto_min_descripcion':
                        texto = texto.trim();
                        if (texto.length < 3) {
                            $("#" + id).css('border-color', 'red');
                            $("#invalid_" + id).html('El largo Mínimo de 3 Caracteres');
                            return 0;
                        } else {
                            $("#" + id).css('border-color', 'green');
                            $("#invalid_" + id).html('');
                            return 1;
                        }
                        break;
                    case 'nombres':
                        texto = notNumber(texto, id);
                        texto = texto.trim();
                        if (texto.length < 3) {
                            $("#" + id).css('border-color', 'red');
                            $("#invalid_" + id).html('El largo Mínimo de 3 Caracteres');
                            return 0;
                        } else {
                            if (texto.length <= 254) {
                                $("#" + id).css('border-color', 'green');
                                $("#invalid_" + id).html('');
                                return 1;
                            } else {
                                $("#" + id).css('border-color', 'red');
                                $("#invalid_" + id).html('Supera largo máximo permitido');
                                return 0;
                            }
                        }
                        break;
                    case 'moneda':
                        texto = texto.trim();
                        texto = formateaMoneda(texto);
                        $("#" + id).val(texto)
                        if (texto.length < 2) {
                            $("#" + id).css('border-color', 'red');
                            $("#invalid_" + id).html('El valor mínimo debe ser 0');
                            return 0;
                        } else {
                            if (texto.length <= 12) {
                                $("#" + id).css('border-color', 'green');
                                $("#invalid_" + id).html('');
                                return 1;
                            } else {
                                $("#" + id).css('border-color', 'red');
                                $("#invalid_" + id).html('Supera largo máximo permitido');
                                return 0;
                            }
                        }
                        break;
                    case 'numero':
                        texto = formatNumber(texto)
                        $("#" + id).val(texto)
                        if (texto != '') {
                            if (texto.length >= 1 && texto.length <= 11) {
                                $("#" + id).css('border-color', 'green');
                                $("#invalid_" + id).html('');
                                return 1;
                            } else {
                                $("#" + id).css('border-color', 'red');
                                $("#invalid_" + id).html('Supera largo máximo permitido');
                                return 0;
                            }
                        } else {
                            $("#" + id).css('border-color', 'red');
                            $("#invalid_" + id).html(msg);
                            return 0;
                        }
                        break;
                    case 'tarjeta':
                        texto = soloNumeros(texto)
                        $("#" + id).val(texto)
                        if (texto != '') {
                            if (texto.length == 16) {
                                return 1;
                            } else {
                                $("#" + id).css('border-color', 'red');
                                $("#invalid_" + id).html('Ingrese N° de Tarjeta Válido');
                                return 0;
                            }
                        } else {
                            $("#" + id).css('border-color', 'red');
                            $("#invalid_" + id).html('Ingrese N° de Tarjeta Válido');
                            return 0;
                        }
                        break;
                    case 'celular':
                        let cel = checkNumero(texto);
                        $('#celular').val(cel)
                        $("#" + id).val(soloNumeros(cel))
                        let celval = formatCelular(cel);
                        if (celval == false) {
                            $("#" + id).css('border-color', 'red');
                            $("#invalid_" + id).html('N° Incorrecto. Ej: +5691234XXXX');
                            return 0;
                        } else {
                            $("#" + id).css('border-color', 'green');
                            $("#invalid_" + id).html('');
                            return 1;
                        }
                        break;

                    case 'telefono':
                        $("#" + id).val(soloNumeros(texto))
                        if (texto.length == 8) {
                            $("#" + id).css('border-color', 'green');
                            $("#invalid_" + id).html('');
                            return 1;
                        } else {
                            $("#" + id).css('border-color', 'red');
                            $("#invalid_" + id).html('N° Incorrecto. Ej: 22531XXXX');
                            return 0;
                        }
                        break;
                    case 'rut':
                        let valRut = Rut(texto, id);
                        if (valRut == false) {
                            $("#" + id).css('border-color', 'red');
                            $("#invalid_" + id).html('Formato de Rut Inválido');
                            return 0;
                        } else {
                            texto = texto.replaceAll('.', '')
                            texto = texto.replace('-', '')
                            if (texto == '111111111' || texto == '222222222' || texto == '333333333' || texto == '444444444' || texto == '555555555' || texto == '666666666' || texto == '777777777' || texto == '888888888' || texto == '999999999') {
                                $("#" + id).css('border-color', 'red');
                                $("#invalid_" + id).html('Rut Inválido');
                                return 0;
                            } else {
                                $("#" + id).css('border-color', 'green');
                                $("#invalid_" + id).html('');
                                return 1;
                            }
                        }
                        break;
                    case 'rut_validacion':
                        let valRutV = Rut(texto, id);
                        if (valRutV == false) {
                            $("#" + id).css('border-color', 'red');
                            $("#invalid_" + id).html('Formato de Rut Inválido');
                            return 0;
                        } else {
                            texto = texto.replaceAll('.', '')
                            texto = texto.replace('-', '')
                            if (texto == '222222222' || texto == '333333333' || texto == '444444444' || texto == '555555555' || texto == '666666666' || texto == '777777777' || texto == '888888888' || texto == '999999999') {
                                $("#" + id).css('border-color', 'red');
                                $("#invalid_" + id).html('Rut Inválido');
                                return 0;
                            } else {
                                $("#" + id).css('border-color', 'green');
                                $("#invalid_" + id).html('');
                                return 1;
                            }
                        }
                        break;
                    case 'estado':

                        if (texto == '1' || texto == '0' || texto == 1 || texto == 0) {
                            $("#" + id).css('border-color', 'green');
                            $("#invalid_" + id).html('');
                            return 1;
                        } else {
                            $('#' + id).css('border-color', 'red');
                            $('#invalid_' + id).html('Seleccione opción Válida');
                            return 0;
                        }
                        break;
                    case 'select':

                        if (texto.length > 0) {
                            $("#" + id).css('border-color', 'green');
                            $("#invalid_" + id).html('');
                            return 1;
                        } else {
                            $("#" + id).css('border-color', 'red');
                            $("#invalid_" + id).html(msg);
                            return 0;
                        }
                        break;
                    case 'select_avance':
                        if (texto.length >= 0) {
                            $("#" + id).css('border-color', 'green');
                            $("#invalid_" + id).html('');
                            return 1;
                        } else {
                            $("#" + id).css('border-color', 'red');
                            $("#invalid_" + id).html(msg);
                            return 0;
                        }
                        break;
                    case 'url':
                        if (texto.length >= 0) {
                            if (urlVal(texto)) {
                                $("#" + id).css('border-color', 'green');
                                $("#invalid_" + id).html('');
                                return 1;
                            } else {
                                $("#" + id).css('border-color', 'red');
                                $("#invalid_" + id).html(msg);
                                return 0;
                            }
                        } else {
                            $("#" + id).css('border-color', 'red');
                            $("#invalid_" + id).html(msg);
                            return 0;
                        }
                        break;
                    case 'checkbox':
                        let respuesta = cuentaCheckbox(1, msg);
                        return respuesta
                        break;
                    default:
                        texto = texto.trim();
                        if (texto.length > 0) {
                            $("#" + id).css('border-color', 'green');
                            $("#invalid_" + id).html('');
                            return 1;
                        } else {
                            $("#" + id).css('border-color', 'red');
                            $("#invalid_" + id).html(msg);
                            return 0;
                        }
                        break;
                }

            } else {
                if (obligatorio == false) {
                    $("#" + id).css('border-color', '');
                    $("#invalid_" + id).html('');
                    return 1;
                } else {
                    $("#" + id).css('border-color', 'red');
                    $("#invalid_" + id).html(msg);
                    return 0;
                }
            }
        } else {
            toastr["error"](`No existe ID de Campo ${id}`, "Error de Validación")
            return 0;
        }



    }

    function quitarEstilos(id) {
        $("#" + id).css('border-color', '');
        $("#invalid_" + id).html('');
    }

    function vaciarCampo(id) {
        $("#" + id).val('');
        $("#" + id).css('border-color', '');
        $("#invalid_" + id).html('');
    }

    function validaCorreo(correo, id, msg = 'Campo Obligatorio', obligatorio = true) {
        if ($("#" + id)) {
            if (correo !== '') {
                if (IsEmail(correo)) {
                    $("#" + id).css('border-color', 'green');
                    $("#invalid_" + id).html('');
                    return 1;
                } else {
                    $("#" + id).css('border-color', 'red');
                    $("#invalid_" + id).html(msg);
                    return 0;
                }
            } else {
                if (obligatorio == false) {
                    $("#" + id).css('border-color', '');
                    $("#invalid_" + id).html('');
                    return 1;
                } else {
                    $("#" + id).css('border-color', 'red');
                    $("#invalid_" + id).html('Campo Obligatorio');
                    return 0;
                }

            }
        }
    }

    function IsEmail(email) {
        let regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(email)) {
            return false;
        } else {
            return true;
        }
    }

    function formatNumber(costo) {
        costo = costo.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        costo = "" + costo;
        return costo;
    }
    

    function formateaMoneda(costo) {

        costo = costo.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        costo = "$" + costo;
        return costo;
    }

    function resetform() {
        $("#contratos_pendientes").attr('hidden', true);
        // window.location = window.location.pathname.replace('#contratos_pendientes', '')
        $("form select").each(function() {
            this.selectedIndex = 0
        });
        $("form input[type=text] ,form input[type=date] ,form input[type=email] , form textarea").each(function() {
            this.value = ''
        });
    }

    function soloNumeros(celular) {
        celular = celular.replace(/[^0-9+]/g, '');
        return celular
    }
    function soloNumerosTarjeta(celular) {
        celular = celular.replace(/[^0-9-+]/g, '');
        return celular
    }
    function maximoCuotas(maximo) {
        maximo = maximo.replace(/[^0-9]/g, '');
        return maximo
    }

    function formatCelular(phone) {
        phone = phone.split(' ').join('');
        if (!(/\+569\d{8}/.test(phone))) {
            return false
        }
        return true;
    }

    function cuentaCheckbox(cant_esperada = 1, msg = 'Debe seleccionar al menos 1') {
        let contador = 0;
        $(".checkbox").each(function() {
            if ($(this).is(":checked")) {
                contador++;
            }
        });
        if (parseInt(contador) < parseInt(cant_esperada)) {
            if ($("#msg_chk")) {
                $("#msg_chk").attr('hidden', false);
            }
            return 0;
        } else {
            $("#msg_chk").attr('hidden', true);
            return 1;
        }
    }

    function padTo2Digits(num) {
        return num.toString().padStart(2, '0');
    }

    function checkURL(url) {
        if (url.length == 0) {
            return url;
        } else {
            let string = url;
            if (!~string.indexOf("http")) {
                string = "http://" + string;
            }
            url = string;
            return url;
        }
    }

    function checkNumero(numero) {
        if (numero.length == 0) {
            return numero;
        } else if (numero.length < 4) {
            numero = '+569';
        }

        let string = numero;
        if (!~string.indexOf("+569")) {
            string = "+569" + string;
        }
        numero = string;
        return numero;
    }

    function urlVal(url) {
        let regex = /^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})).?)(?::\d{2,5})?(?:[\/?#]\S*)?$/i;
        if (!regex.test(url)) {
            return false;
        } else {
            return true;
        }

    }

    function notNumber(string, id) {
        string = string.replace(/[^a-zA-Z\s]/g, '');
        $("#" + id).val(string)
        return string

    }
    // return this.optional(b)||/^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})).?)(?::\d{2,5})?(?:[\/?#]\S*)?$/i.test(a)}

    function cargandoBtn(estado_btn, id_btn, id_span_fa, id_span_spinner, texto, texto_valida = 'Cargando...') {
        if (estado_btn == 'true') {
            $(`#${id_span_spinner}`).attr('hidden', false);
            $(`#${id_span_fa}`).attr('hidden', true);
            $(`#${id_btn}`).html(texto_valida);
            $(`#${id_btn}`).attr('disabled', true)
        } else {
            $(`#${id_span_spinner}`).attr('hidden', true);
            $(`#${id_span_fa}`).attr('hidden', false);
            $(`#${id_btn}`).html(texto);
            $(`#${id_btn}`).attr('disabled', false)

        }
    }
</script>