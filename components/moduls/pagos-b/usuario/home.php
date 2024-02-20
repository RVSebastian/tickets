<?php
 include '../../../db/cn.php';

   if (!isset($_SESSION)) {
    session_start();
    }
    $_SESSION['key']['permisos'] = 10;
    $fechaActual = date('Y-m-d');
    $fechaHaceUnMes = date('Y-m-d', strtotime('-3 month', strtotime($fechaActual))); // Resta un mes a la fecha actual
    $fechaunDia = date('Y-m-d', strtotime('+1 day', strtotime($fechaActual))); // Resta un mes a la fecha actual
   $usuario = $_SESSION['key']['usuario'];
   $autorizados = "SELECT * FROM pagos where estado='Autorizado' and usuario='$usuario' order by fecha desc";  
   $_SESSION['cuentas'] = array(
    1 => 'BANCOLOMBIA: 49784801986 CORRIENTE',
    2 => 'BANCOLOMBIA: 49796209967 AHORROS',
    3 => 'BANCOLOMBIA: 49796484542 AHORROS',
    4 => 'BANCO OCCIDENTE: 600882203 AHORROS',
    5 => 'BANCO OCCIDENTE: 600100234 CORRIENTE',
    6 => 'BBVA: 697910100007623 CORRIENTE',
    7 => 'BANCOLOMBIA: 49798522255 AHORROS',
    8 => 'DAVIVIENDA: 66800158801 AHORROS',
    9 => 'BANCO DE BOGOTA: 614247591 CORRIENTE',
    10 => 'BANCO AGRARIO: 614247591 CORRIENTE',
);
?>
<style>
@media only screen and (max-width: 757px) {
    .imagen_pago {
        margin: 90vh 0px auto !important;
    }
}

@media only screen and (max-width: 500px) {
    .top-modal {
        top: 10vh !important;
    }
}

@media only screen and (min-width: 500px) {
    .top-modal {
        top: 52vh !important;
    }
}

.bg-gray-800 {
    background-color: #0A2558 !important;
}

.Pendiente {
    background-color: #F1C40F;
}

.border-Pendiente {
    border-color: #F1C40F;
}

.Autorizado {
    background-color: #27AE60;
}

.border-Autorizado {
    border-color: #27AE60;
}

.Rechazado {
    background-color: #C0392B;
}

.border-Rechazado {
    border-color: #C0392B;
}

.text-Pendiente {
    color: #F1C40F;
}

.text-Autorizado {
    color: #27AE60;
}

.text-Rechazado {
    color: #C0392B;
}
</style>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script type="text/javascript" src="datatables/datatables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/.min.js"></script>
<script>
let intervalID;
var pagosData = null;


localStorage.removeItem('acordeonAbierto');

function checkViewPay() {
    const viewPayElement = $('.viewpay:not(.hidden)');
    return viewPayElement.length > 0;
}

// Función para guardar los estados de los acordeones abiertos en localStorage
function guardarAcordeonesAbiertos(targetID) {
    var acordeonAbierto = JSON.parse(localStorage.getItem('acordeonAbierto')) || [];

    // Verificar si el targetID ya está en la lista
    var index = acordeonAbierto.indexOf(targetID);

    if (index !== -1) {
        // El targetID ya existe, eliminarlo de la lista
        acordeonAbierto.splice(index, 1);
    } else {
        // El targetID no existe, agregarlo a la lista
        acordeonAbierto.push(targetID);

        // Mantener un máximo de 10 IDs guardados
        if (acordeonAbierto.length > 15) {
            acordeonAbierto.shift(); // Eliminar el primer elemento (el más antiguo)
        }
    }

    localStorage.setItem('acordeonAbierto', JSON.stringify(acordeonAbierto));
}



// Función para abrir los acordeones almacenados en localStorage
function abrirAcordeonesGuardados() {
    var acordeonAbierto = JSON.parse(localStorage.getItem('acordeonAbierto')) || [];
    for (var i = 0; i < acordeonAbierto.length; i++) {
        $('body ' + acordeonAbierto[i]).toggleClass('hidden');
        console.log('Acordeón abierto:', acordeonAbierto[i]);
    }
}

// Función para inicializar los acordeones y gestionar los clics
function inicializarAcordeones() {
    // Delegado de eventos para los botones de los acordeones
    $('body').on('click', '[data-accordion-target]', function() {
        var targetID = $(this).data('accordion-target');
        $(targetID).toggleClass('hidden');
        console.log(targetID);
        // Guardar el estado de los acordeones abiertos en localStorage
        guardarAcordeonesAbiertos(targetID);
    });
    console.log('acordeones_cargados');
}


function inicializarModals() {
    $('body').on('click', '[data-modal-toggle]', function(e) {
        e.stopPropagation();
        var targetID = $(this).data('modal-target');
        $('.backdrop').toggleClass('hidden');
        $('body').addClass('modal-open');
    });
    console.log('modals_cargados');
}
$(document).on('keyup', function(e) {
    if (e.key === 'F2') {
        cargador();
    }
});
$(document).on('keyup', function(e) {
    if (e.key === 'Escape') {
        $('.modal-open').removeClass('modal-open');
        $('.viewpay , .backdrop').addClass('hidden');
        $('#loading').hide();
    }
});
$(function() {
    function cargarModulo(url) {
        return new Promise(function(resolve, reject) {
            $(this).load(url, function(response, status, xhr) {
                if (status === "success") {
                    resolve(response); // Resuelve la promesa con la respuesta del módulo
                } else {
                    reject(); // Rechaza la promesa en caso de error de carga
                }
            });
        });
    }

    $.fn.getData = function() {
        return new Promise(function(resolve, reject) {
            // Realiza la solicitud AJAX para obtener pagosData
            $.ajax({
                url: './components/pagos/data.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    pagosData = data;
                    resolve(pagosData); // Resuelve la promesa con pagosData

                },
                error: function(error) {
                    console.error('Error al obtener pagosData: ', error);
                    reject(error);
                }
            });
        });
    };


    $.fn.cargarModuloHistorico = function() {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: './components/pagos/historico.php',
                method: 'POST',
                data: {
                    pagosData: JSON.stringify(pagosData)
                },
                dataType: 'html',
                success: function(response) {
                    $('#view-2').html(response);
                    resolve();
                },
                error: function(error) {
                    console.error('Error al cargar el módulo Historico: ', error);
                    reject(error);
                }
            });
        });
    };

    $.fn.cargarPendientes = function() {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: './components/pagos/pagos_pendientes.php',
                method: 'POST',
                data: {
                    pagosData: JSON.stringify(pagosData)
                },
                dataType: 'html',
                success: function(response) {
                    $('#view-3').html(response);
                    resolve();
                },
                error: function(error) {
                    console.error('Error al cargar el módulo Caja: ', error);
                    reject(error);
                }
            });
        });
    };

    $.fn.cargarModuloHome = function() {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: './components/pagos/usuario/table.php',
                method: 'POST',
                data: {
                    pagosData: JSON.stringify(pagosData)
                },
                dataType: 'html',
                success: function(response) {
                    $('#tablasuser').html(response);
                    resolve();
                },
                error: function(error) {
                    console.error('Error al cargar el módulo Home: ', error);
                    reject(error);
                }
            });
        });
    };


    $.fn.cargarContabilidad = function(pagosData) {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: './components/pagos/contable.php',
                method: 'POST',
                data: {
                    pagosData: JSON.stringify(pagosData)
                },
                dataType: 'html',
                success: function(response) {
                    $('#view-4').html(response);
                    resolve();
                },
                error: function(error) {
                    console.error('Error al cargar el módulo Contable: ', error);
                    reject(error);
                }
            });
        });
    };

    $.fn.pagos = function() {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: './components/pagos/viewpay.php',
                method: 'POST',
                data: {
                    pagosData: JSON.stringify(pagosData)
                },
                dataType: 'html',
                success: function(response) {
                    $('#pagos').html(response);
                    resolve();
                },
                error: function(error) {
                    console.error('Error al cargar el módulo Pagos: ', error);
                    reject(error);
                }
            });
        });
    };

    $.fn.search = function() {
        $('#loading').show();
        var valorBusqueda = $("#buscador-input").val();
        var date1 = $("#date1").val();
        var date2 = $("#date2").val();
        $.ajax({
                type: "POST",
                url: "./components/pagos/usuario/buscador",
                data: {
                    valorBusqueda: valorBusqueda,
                    date1: date1,
                    date2: date2,
                },
                beforeSend: function() {
                    $("#buscador-response").hide();
                },
            })
            .done(function(res) {
                $("#res").html(res);
                $("#buscador-response").slideDown(1000);
                $('#loading').hide();
            });
    };
});

$(document).ready(function() {
    $(window).scroll(function() {
        // Almacenar la posición del scroll en una variable
        var scrollPosition = $(this).scrollTop();

        // Guardar la posición del scroll en una cookie o localStorage
        // para que puedas usarla después de recargar la página
        localStorage.setItem('scrollPosition', scrollPosition);
    });
    $('body #action_bar').slideDown(1000);
    inicializarAcordeones();
    inicializarModals();
    cargador();
    $('#loading').hide();
    $("#cambiarImagenButton").on("click", function() {
        $("#subir_pagos").fadeOut(500, function() {
            $("#subir_pagos, #backdroppagos").toggleClass("hidden");

        });
    });
    $("#session_historico").on("click", function() {
        $("#view-1, #view-2 ,#view-3,#view-4").hide();
        $("#view-2").fadeToggle("fast", "linear");
    });
    $("#session_home").on("click", function() {
        $("#view-1,#view-2 ,#view-3,#view-4").hide();
        $("#view-1").fadeToggle("fast", "linear");
    });
    $("#session_caja").on("click", function() {
        $("#view-1,#view-2 ,#view-3,#view-4").hide();
        $("#view-3").fadeToggle("fast", "linear");
    });
    $("#session_contable").on("click", function() {
        $("#view-1,#view-2 ,#view-3,#view-4").hide();
        $("#view-4").fadeToggle("fast", "linear");
    });


    $('#body tablasuser').on('click', '.consultador', function() {
        var formulario = $(this).closest('form');
        var formData = formulario.serialize();
        $.ajax({
            type: 'POST',
            url: './components/pagos/consult.php',
            data: formData,
            async: false,
            beforeSend: function() {
                $('#loading').show();
            },
            success: function(response) {
                $('#listado').html(response);
                $('#loading').hide();
            },
            error: function(xhr, status, error) {
                console.log(error);
            },
        });
        return false;
    });
    // consultar pagos del tercero
    $('body').on('click', '.consultador_pagos', function() {

        var formulario = $(this).closest('form');
        var formData = formulario.serialize();
        $.ajax({
            type: 'POST',
            url: './components/pagos/consult.php',
            data: formData,
            async: false,
            beforeSend: function() {
                $('#loading').show();
            },
            success: function(response) {
                $('.modal-open').removeClass('modal-open');
                $('.viewpay , .backdrop').addClass('hidden');
                $('#consultador_pagos_view').html(response);
                $('#consultador_pagos_view').removeClass('hidden');
                $('#loading').hide();
            },
            error: function(xhr, status, error) {
                console.log(error);
            },
        });
        return false;
    });

    // guardar recibo de caja
    $('body').on('click', '.enviador', function() {
        var formulario = $(this).closest('form');

        // Verificar campos requeridos
        var camposVacios = formulario.find(':required').filter(function() {
            return $(this).val() === '';
        });

        // Validar si hay campos vacíos
        if (camposVacios.length > 0) {
            // Mostrar mensaje de error o realizar alguna acción apropiada
            alert('Por favor, complete todos los campos.');
            return false; // Detener la ejecución
        }

        var formData = formulario.serialize();
        var reciboValue = formulario.find('input[name="recibo"]').val();
        var recibosJson = localStorage.getItem('recibos');
        if (recibosJson) {
            var recibos = JSON.parse(recibosJson);
            if (recibos.indexOf(reciboValue) !== -1) {
                alert('ERROR: Se encontro un diplicado en el recibo de caja.')
            } else {
                $.ajax({
                    type: 'POST',
                    url: './components/pagos/funciones.php',
                    data: formData,
                    async: false,
                    beforeSend: function() {
                        $('#loading').show();
                        $('.modal-open').removeClass('modal-open');
                        $('.viewpay , .backdrop').addClass('hidden');
                    },
                    success: function(response) {
                        cargador();
                        $('#loading').hide();
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    },
                });
            }
        } else {
            aler('Error: Error');
        }

        return false;
    });
    // validamos el pago
    $('body').on('click', '.validar_pago', function() {
        var formulario = $(this).closest('form');

        var s_valor = formulario.find('[name="valor"]').val();
        var s_documento = formulario.find('[name="documentot"]').val();
        var s_baucher = formulario.find('[name="codigo_baucher"]').val();
        var s_cuenta = formulario.find('[name="cuenta"]').val();

        // Verificar campos requeridos
        var camposVacios = formulario.find(':required').filter(function() {
            return $(this).val() === '';
        });

        // Validar si hay campos vacíos
        if (camposVacios.length > 0) {
            // Mostrar mensaje de error o realizar alguna acción apropiada
            alert('Por favor, complete todos los campos.');
            return false; // Detener la ejecución
        }
        // validacion de pagos

        var formData = formulario.serialize();
        const jsonData = localStorage.getItem("pages");


        if (jsonData) {
            const data = JSON.parse(jsonData);
            let pagoEncontrado = false; // Variable para rastrear si se encontró un pago autorizado

            for (const pago of data) {
                if (
                    pago.estado == 'Autorizado' &&
                    pago.valor === s_valor &&
                    pago.documentot === s_documento &&
                    pago.codigo_baucher === s_baucher &&
                    pago.cuenta === s_cuenta
                ) {
                    // Si se cumple la condición, muestra la alerta de error y marca que se encontró un pago autorizado
                    alert(
                        'Error: Se encontró un pago con el valor igual a $' +
                        pago.valor +
                        ' en el tercero ' +
                        pago.documentot +
                        ' en el pago #' +
                        pago.id
                    );

                    pagoEncontrado = true;
                    break; // Sale del bucle ya que se encontró un pago autorizado
                }
            }

            // Si no se encontró un pago autorizado, realiza la solicitud AJAX
            if (!pagoEncontrado) {
                $.ajax({
                    type: 'POST',
                    url: './components/pagos/funciones.php',
                    data: formData,
                    async: false,
                    beforeSend: function() {
                        $('#loading').show();
                        $('.modal-open').removeClass('modal-open');
                        $('.viewpay , .backdrop').addClass('hidden');
                    },
                    success: function(response) {
                        cargador();
                        $('#loading').hide();
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    },
                });
            }
        }


        return false; // Mover el return false aquí
    });


    // guardador de pagos
    $('body').on('click', '.crear_pago', function() {

        var formulario = $(this).closest('#form_create_pago');

        // Verificar campos requeridos
        var camposVacios = formulario.find(':required').filter(function() {
            return $(this).val() === '';
        });

        // Validar si hay campos vacíos
        if (camposVacios.length > 0) {
            // Mostrar mensaje de error o realizar alguna acción apropiada
            alert('Por favor, complete todos los campos.');
            return false; // Detener la ejecución
        }

        var formData = new FormData(formulario[0]);

        // Agregar archivo manualmente al formData
        var archivoInput = formulario.find('input[id="imagen1"]')[0];

        if (archivoInput.files.length > 0) {
            var archivo = archivoInput.files[0];
            formData.append('archivo', archivo);
        }


        $.ajax({
            type: 'POST',
            url: './components/pagos/funciones.php',
            data: formData,
            async: false,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#loading').show();
                $('#defaultModal , .modal-backdrop').removeClass(
                    'opacity-100 pointer-events-auto');
                $('.bg-gray-900 , #defaultModal').addClass('hidden');
                $('#defaultModal, .modal-backdrop').hide();
                $('.modal-open').removeClass('modal-open');
                $('.viewpay , .backdrop').addClass('hidden');
                $("#form_create_pago").trigger("reset");
                $('#prev1').attr('src', 'https://automarcol.com/image/SUBIR%20PAGO.png');
            },
            success: function(response) {
                cargador();
                $('#loading').hide();
            },
            error: function(xhr, status, error) {
                console.log('Erorr ' + error);
            },
        });

        return false;
    });

    $("#buscador-input , #date1 , #date2").on("change", function() {
        $("#buscador-response").search();
    });
});

function cargador() {
    $('#loading').show();
    localStorage.removeItem("pages");
    if (!checkViewPay()) {
        console.log('Cargando módulos...');
        // Obtener pagosData y cargar los módulos una vez que se resuelva la promesa
        $('body').getData().then(function(pagosData) {
            var count = pagosData.length;
            localStorage.setItem("pages", JSON.stringify(pagosData));
            // Crear un array de promesas de carga de módulos
            var promises = [
                $('body').pagos(pagosData),
                $('body').cargarModuloHome(pagosData),
                $('body').cargarModuloHistorico(pagosData),
                $('body').cargarPendientes(pagosData),
                $('body').cargarContabilidad(pagosData)
            ];

            // Esperar a que todas las promesas se resuelvan
            return Promise.all(promises);
        }).then(function() {
            // Todos los módulos se han cargado con éxito
            console.log('Todos los módulos se han cargado exitosamente.');
            abrirAcordeonesGuardados(); // Abrir acordeones después de cargar módulos
            if ($('#buscador-input').val() !== '') {
                // El input tiene contenido
                $("body #buscador-response").search();
            }
        }).catch(function(error) {
            console.error('Ocurrió un error al cargar al menos uno de los módulos:', error);
        }).finally(function() {
            $('#loading').hide(); // Ocultar el indicador de carga
        });
    } else {
        console.log('Visualización de pago en progreso. Deteniendo la recarga.');
        clearInterval(intervalID);
        startInterval();
        $('#loading').hide(); // Ocultar el indicador de carga en caso de interrupción
    }
}


function startInterval() {
    intervalID = setInterval(cargador, 120000);
}
startInterval();
</script>


<div tabindex="201000" style="z-index: 22000 !important">
    <div id="consultador_pagos_view">

    </div>
</div>
<div id="loading" tabindex="201000" style="z-index: 22000 !important">
    <div class="" style="display: flex; justify-content:center;">
        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
        <lottie-player src="https://assets3.lottiefiles.com/packages/lf20_20lywwls.json" background="transparent"
            speed="1"
            style="width: 20%; height: 20%; position:aboslute;top:50%; left:50%; position: fixed;z-index: 20000 !important;"
            loop autoplay>
        </lottie-player>
    </div>
</div>
<script>
function close_pagos_modal() {
    $('.modal-open').removeClass('modal-open');
    $('.viewpay , .backdrop').addClass('hidden');
}

function close_buscador() {
    $('#buscador-response').hide();
}
</script>
<audio id="notificacionAudio" src="https://automarcol.com/image/notificacion.wav"></audio>
<div id="subir_pagos" tabindex="20000" style='z-index: 20000 !important;' aria-hidden="true"
    class="viewpay hidden w-full p-4 pt-0 mt-0  absolute inset-0 transition-2">
    <div class="absolute w-full h-full max-w-5xl h-5xl rounded overflow-y-auto top-0 left-0 right-0 z-50 top-modal"
        style="margin:0px; left: 50%; transform: translate(-50%, -43%); position: fixed;">
        <div class="bg-white rounded-lg shadow rounded border-b-3">
            <div class="space-y-4 pt-0 mt-0 overflow-y-auto text-justify ">
                <form method="POST" enctype="multipart/form-data" id="form_create_pago" name="post"
                    data-modal-target="defaultModal" data-modal-hide="defaultModal">
                    <div class="relative w-full h-full max-w-7xl">
                        <div class="relative">
                            <div class="flex flex-row flex-wrap">
                                <div class="sm:basis-11/12 md:basis-2/4 my-auto">
                                    <input required type="file" id="imagen1" name="imagen1" accept="image/*"
                                        onchange="loadFile(event,img='prev1')" style="display:none;" />
                                    <label for="imagen1">
                                        <img class='rounded mx-auto mt-3 p-4 rounded imagen_pago'
                                            src="https://automarcol.com/image/SUBIR%20PAGO.png" id="prev1"
                                            style="height: 40rem !important; margin: 0 auto; whidth: 20rem">
                                    </label>
                                </div>
                                <div class="sm:basis-11/12 md:basis-2/4 bg-gray-100">
                                    <div class="flex items-start justify-between p-4 pb-0 rounded-t ">
                                        <button type="button"
                                            class="text-slate-600 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                            onclick="close_pagos_modal();">
                                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor"
                                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <div class="px-6 pt-3">
                                        <label for="cuenta"
                                            class="block text-sm font-medium text-gray-900 ">Cuenta</label>
                                        <select required id="cuenta" name='cuenta'
                                            class="bg-gray-50 border border-gray-300 mb-2 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                            <option value="BANCOLOMBIA: 49784801986 CORRIENTE">BANCOLOMBIA:
                                                49784801986 CORRIENTE
                                            </option>
                                            <option value="BANCOLOMBIA: 49796209967 AHORROS">BANCOLOMBIA:
                                                49796209967 AHORROS
                                            </option>
                                            <option value="BANCOLOMBIA: 49796484542 AHORROS">BANCOLOMBIA:
                                                49796484542 AHORROS
                                            </option>
                                            <option value="BANCO OCCIDENTE: 600882203 AHORROS">BANCO OCCIDENTE:
                                                600882203 AHORROS
                                            </option>
                                            <option value="BANCO OCCIDENTE: 600100234 CORRIENTE">BANCO
                                                OCCIDENTE: 600100234
                                                CORRIENTE</option>
                                            <option value="BBVA: 697910100007623 CORRIENTE">BBVA:
                                                697910100007623 CORRIENTE</option>
                                            <option value="BANCOLOMBIA: 49798522255 AHORROS">BANCOLOMBIA:
                                                49798522255 AHORROS
                                            </option>
                                            <option value="DAVIVIENDA: 66800158801 AHORROS">DAVIVIENDA:
                                                66800158801 AHORROS</option>
                                            <option value="BANCO DE BOGOTA: 614247591 CORRIENTE">BANCO DE
                                                BOGOTA: 614247591
                                                CORRIENTE</option>
                                        </select>
                                        <div class="mb-2">
                                            <label for="small-input"
                                                class="block mb-2 text-sm font-medium text-gray-900">Valor</label>
                                            <input required type="text" id="small-input" name='valor'
                                                oninput="formatNumber(this)"
                                                class="block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div class="mb-2">
                                            <label for="tercero" class="block text-sm font-medium text-gray-900 ">Tipo
                                                de
                                                Tercero</label>
                                            <select required id="tercero" name='tipo_tercero'
                                                class="bg-gray-50 border border-gray-300 mb-2 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                <option value="CEDULA">Cedula</option>
                                                <option value="NIT">Nit</option>
                                            </select>
                                            <label for="small-input"
                                                class="block mb-2 text-sm font-medium text-gray-900">Numero de
                                                Documento o Nit</label>
                                            <input required type="text" id="small-input" name='documentot'
                                                class="block w-full  p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div class="mb-2">
                                            <label for="small-input"
                                                class="block mb-2 text-sm font-medium text-gray-900">Nombre
                                                Completo o Razon Social</label>
                                            <input required type="text" id="small-input" name='nombret'
                                                class="block w-full  p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div class="mb-2">
                                            <label for="small-input"
                                                class="block mb-2 text-sm font-medium text-gray-900">Fecha
                                                Pago</label>
                                            <input type="date" name="fecha_pago"
                                                class="block w-full  p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500"
                                                required />
                                        </div>
                                        <!--
                                        
                                     
                                        <div class="mb-2">
                                            <label for="small-input"
                                                class="block mb-2 text-sm font-medium text-gray-900">Numero de
                                                Factura -
                                                opcional</label>
                                            <input type="text" id="small-input" name='e_factura'
                                                class="block w-full  p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        -->
                                        <div class="mb-3">
                                            <label for="small-input"
                                                class="block mb-2 text-sm font-medium text-gray-900">Nota</label>
                                            <textarea cols="10" rows="10" id="small-input" name='otro' id='otro'
                                                class="block w-full max-h-20 p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500"></textarea>
                                        </div>

                                    </div>
                                    <input type="hidden" name="usuario" value='<?php echo  $usuario ?>'>
                                    <input type="hidden" name="guardar" value='true'>
                                    <div
                                        class="flex flex-row items-center p-6 space-x-3 bg-gray-200 border-t border-gray-200">
                                        <button type="submit"
                                            class="crear_pago text-white p-2 bg-slate-700 hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded-lg text-sm px-5 py-4.5 text-center">Guardar</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="hidden opacity-50 fixed inset-0 z-40 bg-gray-900 backdrop" id="backdroppagos"
    onclick="closeModal('pagos');"></div>
<div id="action_bar" style="display: none;"
    class="fixed z-50 w-full h-16 max-w-lg -translate-x-1/4 rounded-full bottom-4 left-1/2 bg-gray-200 border-gray-600">
    <div class="grid h-full max-w-lg grid-cols-5 mx-auto">
        <button data-tooltip-target="tooltip-home" type="button" id="session_home"
            class="inline-flex flex-col items-center justify-center px-5 rounded-l-full hover:bg-gray-50  group">
            <svg class="w-5 h-5 mb-2 text-gray-700  group-hover:text-blue-600" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
            </svg>
            <span class="sr-only">Home</span>
        </button>
        <div id="tooltip-home" role="tooltip"
            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
            Home
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div>
        <button data-tooltip-target="tooltip-wallet" <?php if ($_SESSION['key']['permisos'] < 8) {
            echo 'disabled';
        } ?> type="button" data-tooltip-target="tooltip-settings" type="button" id="session_historico"
            class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50  group">
            <svg class="w-6 h-6 text-gray-800  group-hover:text-blue-600" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                    d="M10 6v4l3.276 3.276M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span class="sr-only">Historico</span>
        </button>
        <div id="tooltip-wallet" role="tooltip"
            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
            Historico
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div>
        <div class="flex items-center justify-center">
            <button data-tooltip-target="tooltip-new" type="button" id="cambiarImagenButton"
                aria-controls="speed-dial-menu-default" aria-expanded="false"
                class="inline-flex items-center justify-center w-10 h-10 font-medium bg-blue-900 rounded-full hover:bg-blue-700 group focus:ring-4 focus:ring-blue-300 focus:outline-none">
                <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
                <span class="sr-only">New item</span>
            </button>
        </div>
        <div id="tooltip-new" role="tooltip"
            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
            Crear nuevo pago
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div>
        <button data-tooltip-target="tooltip-settings" type="button" <?php if ($_SESSION['key']['permisos'] < 8) {
            echo 'disabled';
        } ?> id="session_caja" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50  group">
            <svg class="w-6 h-6 text-gray-800  group-hover:text-blue-600" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11.905 1.316 15.633 6M18 10h-5a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2h5m0-5a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1m0-5V7a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h15a1 1 0 0 0 1-1v-3m-6.367-9L7.905 1.316 2.352 6h9.281Z" />
            </svg>
            <span class="sr-only">Caja</span>
        </button>
        <div id="tooltip-settings" role="tooltip"
            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
            Caja
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div>
        <button data-tooltip-target="tooltip-profile" type="button" id="session_contable" <?php if ($_SESSION['key']['permisos'] < 8) {
            echo 'disabled';
        } ?> class="inline-flex flex-col items-center justify-center px-5 rounded-r-full hover:bg-gray-50  group">

            <svg class="w-6 h-6 text-gray-800   group-hover:text-blue-600" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M1 12v5m5-9v9m5-5v5m5-9v9M1 7l5-6 5 6 5-6" />
            </svg>

            <span class="sr-only">Contabilidad</span>
        </button>
        <div id="tooltip-profile" role="tooltip"
            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
            Contabilidad
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div>
    </div>
</div>

<div class="pt-5" style="margin:0;">
    <div id="pagos"></div>
    <div id="view-1">
        <div class="basis-3/4 px-5 pt-3 pb-2 bg-white rounded mx-5 mb-6">
            <form id="buscador-pagos" onsubmit="return false;">
                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="relative">
                        <div class="absolute inset-y-0 md:pb-6 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input type="search" id="buscador-input"
                            class="p-4 pl-10 block py-2.5 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder="Buscar por Tercero" required>

                    </div>

                    <div class="grid md:grid-cols-2 md:gap-6">
                        <div class="relative z-0 w-full mb-6 group">
                            <div class="relative max-w-sm">
                                <input type="date" id="date1" value="<?php echo $fechaHaceUnMes; ?>"
                                    class="block py-2.5 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder="Select date">
                            </div>
                        </div>

                        <div class="relative z-0 w-full mb-6 group">
                            <div class="relative max-w-sm">
                                <input type="date" id="date2" value="<?php echo $fechaunDia; ?>"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder="Select date">
                            </div>

                        </div>
                    </div>
                </div>
            </form>

        </div>
        <div id="buscador-response" style="display: none;" class="p-5 pt-0">
            <div id="res"></div>
        </div>

        <div class="home-content pt-0 mt-0" style="padding-top: 0;" id="listado"></div>
        <div class="home-content pt-0 mt-0" style="padding-top: 0;" id="tablasuser">
        </div>
    </div>
    <div id="view-2" class="hidden">
        <div class="home-content pt-0 mt-0" style="padding-top: 0;">
            <div id="content"></div>
        </div>
    </div>
    <div id="view-3" class="hidden">
        <div class="home-content pt-0 mt-0" style="padding-top: 0;">
            <div id="content"></div>
        </div>
    </div>
    <div id="view-4" class="hidden">
        <div class="home-content pt-0 mt-0" style="padding-top: 0;">
            <div id="content"></div>
        </div>
    </div>


</div>




<script>
function formatNumber(input) {
    let value = input.value.replace(/[^\d.]/g, '');

    // Remueve puntos decimales duplicados
    value = value.replace(/\.(?=.*\.)/g, '');

    // Formatea el número con comas de miles (opcional)
    let parts = value.toString().split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    input.value = parts.join('.');
}


var loadFile = function(event, img) {
    var output = document.getElementById(img);
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
        URL.revokeObjectURL(output.src) // free memory
    }

};
</script>