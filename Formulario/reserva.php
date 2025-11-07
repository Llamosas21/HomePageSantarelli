<?php
$datos_prueba = [];
if (isset($_GET['test']) && $_GET['test'] == '1') {
    $datos_prueba = [
        'nombre' => 'Juan',
        'apellido' => 'Pérez',
        'telefono' => '+5491112345678',
        'email' => 'juan.perez@dominio.com',
        'partida' => 'Av. de Mayo 575, CABA', // <-- AGREGADO
        
        // --- Valores ajustados del File 1 ---
        'destino' => 'Palacio Barolo', 
        'fecha_salida' => date('Y-m-d\TH:i', strtotime('+1 day')),
        'fecha_regreso' => date('Y-m-d\TH:i', strtotime('+3 days')),
        
        // --- Valores de Micro (adaptados para la estructura de tabla) ---
        'micro' => ['40 personas','60 personas'], 
        'cantidad_micros' => [2,2],
    ];
}

$fecha_actual_iso = date('Y-m-d\TH:i');
$selected_destino = $datos_prueba['destino'] ?? '';

// --- Lógica para procesar micros (ahora desde $datos_prueba) ---
$old_micros = $datos_prueba['micro'] ?? [];
$old_cantidades = $datos_prueba['cantidad_micros'] ?? [];

$micros_agrupados = [];
if (!empty($old_micros)) {
    foreach ($old_micros as $index => $tipo) {
        if (empty($tipo)) continue;
        if (!isset($micros_agrupados[$tipo])) $micros_agrupados[$tipo] = 0;
        $micros_agrupados[$tipo] += (int)($old_cantidades[$index] ?? 1);
    }
}

// --- Definición de precios (tomada del File 1) ---
$precios_micros = [
    '20 personas' => ['precio' => 25000.00, 'texto' => '20 personas ($25.000)'],
    '40 personas' => ['precio' => 45000.00, 'texto' => '40 personas ($45.000)'],
    '60 personas' => ['precio' => 60000.00, 'texto' => '60 personas ($60.000)']
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reserva Santarelli</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
    <div id="modal-overlay" class="modal-overlay">
        <div class="modal-content">
            <svg class="modal-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h2 id="modal-texto">Formulario enviado con éxito</h2>
        </div>
    </div>
    <div class="container">
    <h1 class="main-title">Nueva Reserva</h1>
    <p class="subtitle">Complete los datos para solicitar un nuevo presupuesto.</p>
    
    <div id="mensaje" style="display:none; margin-bottom: 20px;"></div>

    <form id="reservaForm">
        
        <div class="form-section">
        <h2>Datos del Cliente</h2>
        <div class="form-grid">
            <div>
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required
                    value="<?php echo htmlspecialchars($datos_prueba['nombre'] ?? ''); ?>" />
            </div>
            <div>
                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" required
                    value="<?php echo htmlspecialchars($datos_prueba['apellido'] ?? ''); ?>" />
            </div>
            <div>
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" required 
                    value="<?php echo htmlspecialchars($datos_prueba['telefono'] ?? ''); ?>" />
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required
                    value="<?php echo htmlspecialchars($datos_prueba['email'] ?? ''); ?>" />
            </div>
        </div>
        
        <div style="margin-top: 15px;">
            <label for="partida">Lugar de salida</label>
            <input type="text" id="partida" name="partida" required
                value="<?php echo htmlspecialchars($datos_prueba['partida'] ?? ''); ?>" />
        </div>
        </div>

        <hr />

        <div class="form-section">
            <h2>Detalles del Viaje</h2>
            <div>
            <label for="destino">Destino</label>
            <select id="destino" name="destino" required>
                <option value="">Seleccione un destino...</option>
                <option value="Museo Benito Quinquela Martín" <?php echo ($selected_destino == 'Museo Benito Quinquela Martín') ? 'selected' : ''; ?>>Museo Benito Quinquela Martín</option>
                <option value="Manzana de las Luces" <?php echo ($selected_destino == 'Manzana de las Luces') ? 'selected' : ''; ?>>Manzana de las Luces</option>
                <option value="Palacio Barolo" <?php echo ($selected_destino == 'Palacio Barolo') ? 'selected' : ''; ?>>Palacio Barolo</option>
                <option value="Cementerio de la Chacarita" <?php echo ($selected_destino == 'Cementerio de la Chacarita') ? 'selected' : ''; ?>>Cementerio de la Chacarita</option>
                <option value="Jardín Japonés" <?php echo ($selected_destino == 'Jardín Japonés') ? 'selected' : ''; ?>>Jardín Japonés</option>
                <option value="El Zanjón de Granados" <?php echo ($selected_destino == 'El Zanjón de Granados') ? 'selected' : ''; ?>>El Zanjón de Granados</option>
            </select>
            </div>
            <div class="form-grid" style="margin-top: 15px;">
            <div>
                <label for="fecha_salida">Fecha y hora de salida</label>
                <input type="datetime-local" id="fecha_salida" name="fecha_salida" required
                        min="<?php echo $fecha_actual_iso; ?>"
                        value="<?php echo htmlspecialchars($datos_prueba['fecha_salida'] ?? ''); ?>" />
            </div>
            <div>
                <label for="fecha_regreso">Fecha y hora de regreso</label>
                <input type="datetime-local" id="fecha_regreso" name="fecha_regreso" required
                        min="<?php echo $fecha_actual_iso; ?>"
                        value="<?php echo htmlspecialchars($datos_prueba['fecha_regreso'] ?? ''); ?>" />
            </div>
            </div>
        </div>
        
        <hr />
        
        <div class="form-section">
            <h2>Micros y Presupuesto</h2>

            <div class="micro-selector-grid">
                <div>
                    <label for="micro_tipo_selector">Tipo de micro</label>
                    <select id="micro_tipo_selector">
                        <option value="">Seleccionar para agregar...</option>
                        <option value="20 personas" data-precio="25000.00">20 personas ($25.000)</option>
                        <option value="40 personas" data-precio="45000.00">40 personas ($45.000)</option>
                        <option value="60 personas" data-precio="60000.00">60 personas ($60.000)</option>
                    </select>
                </div>
                <div>
                    <label>Cantidad</label>
                    <input type="number" id="micro_cantidad_selector" min="1" max="10" value="1" title="Debe ser entre 1 y 10 micros." />
                </div>
                <button type="button" id="btn-add-micro" class="btn-add">Agregar</button>
            </div>

            <table id="micros-table">
                <thead style="<?php echo empty($micros_agrupados) ? 'display: none;' : ''; ?>">
                    <tr>
                        <th>Tipo de Micro</th>
                        <th>Cantidad</th>
                        <th>Precio Unit.</th>
                        <th>Subtotal</th>
                        <th>Quitar</th>
                    </tr>
                </thead>
                <tbody id="micros-table-body">
                    <?php
                    // Llenar la tabla con los datos (ahora desde datos_prueba)
                    if (!empty($micros_agrupados)) {
                        foreach ($micros_agrupados as $tipo => $cantidad) {
                            if (!isset($precios_micros[$tipo])) continue;
                            $data = $precios_micros[$tipo];
                            $precio_unit = $data['precio'];
                            $subtotal = $precio_unit * $cantidad;
                    ?>
                    <tr data-tipo="<?php echo htmlspecialchars($tipo); ?>" data-precio-unit="<?php echo $precio_unit; ?>">
                        <td><?php echo htmlspecialchars($tipo); ?></td>
                        <td>
                            <input type="number" name="cantidad_micros[]" value="<?php echo htmlspecialchars($cantidad); ?>" min="1" max="10" required title="Debe ser entre 1 y 10 micros." />
                        </td>
                        <td><?php echo '$' . number_format($precio_unit, 0, ',', '.'); ?></td>
                        <td class="subtotal"><?php echo '$' . number_format($subtotal, 0, ',', '.'); ?></td>
                        <td><button type="button" class="btn-remove-micro">X</button></td>
                        <input type="hidden" name="micro[]" value="<?php echo htmlspecialchars($tipo); ?>">
                    </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
            
            <div id="micros-table-empty" style="text-align: center; color: #9CA3AF; padding: 20px; border: 1px dashed #475569; border-radius: 8px; <?php echo !empty($micros_agrupados) ? 'display: none;' : ''; ?>">
                Aún no se han agregado micros.
            </div>

            <div id="total-micros-error" class="error" style="display: none; margin-bottom: 15px; margin-top: 10px;"></div>
            <div style="margin-top: 15px;">
                <label for="monto">Monto total estimado</label>
                <input type="text" id="monto" name="monto" readonly placeholder="Se calculará automáticamente"/>
            </div>
        </div>
        
        <button type="submit" id="submitButton">
            <span id="buttonText">Enviar Reserva</span>
            <span id="buttonSpinner" style="display:none;">Enviando...</span>
        </button>

    </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // --- Selectores de elementos ---
            const form = document.getElementById('reservaForm');
            const montoInput = document.getElementById('monto');
            const totalMicrosError = document.getElementById('total-micros-error');
            const submitButton = document.getElementById('submitButton');
            const buttonText = document.getElementById('buttonText');
            const buttonSpinner = document.getElementById('buttonSpinner');
            
            // --- AHORA SELECCIONAMOS AMBOS TIPOS DE MENSAJE ---
            const mensajeErrorDiv = document.getElementById('mensaje'); // Para errores
            const modalOverlay = document.getElementById('modal-overlay'); // Para éxito
            const modalTexto = document.getElementById('modal-texto');
            
            // Elementos de la tabla
            const addButton = document.getElementById('btn-add-micro');
            const tipoSelector = document.getElementById('micro_tipo_selector');
            const cantidadSelector = document.getElementById('micro_cantidad_selector');
            const table = document.getElementById('micros-table');
            const tableBody = document.getElementById('micros-table-body');
            const emptyMessage = document.getElementById('micros-table-empty');
            
            const limiteTotalMicros = 10;

            // Formateador de moneda
            const formatter = new Intl.NumberFormat('es-AR', { 
                style: 'currency', 
                currency: 'ARS', 
                minimumFractionDigits: 0 
            });

            // --- Funciones de Tabla (SIN CAMBIOS) ---
            function updateTable() {
                let montoTotal = 0;
                let cantidadTotalMicros = 0;
                const rows = tableBody.querySelectorAll('tr');

                rows.forEach(row => {
                    const cantidadInput = row.querySelector('input[type="number"]');
                    const cantidad = parseInt(cantidadInput.value || 0);
                    const precioUnit = parseFloat(row.dataset.precioUnit || 0);
                    
                    const subtotal = precioUnit * cantidad;
                    row.querySelector('.subtotal').textContent = formatter.format(subtotal);
                    
                    montoTotal += subtotal;
                    cantidadTotalMicros += cantidad;
                });

                if (montoTotal > 0) {
                    montoInput.value = formatter.format(montoTotal);
                } else {
                    montoInput.value = "";
                }

                if (cantidadTotalMicros > limiteTotalMicros) {
                    totalMicrosError.textContent = `Error: El total de micros (${cantidadTotalMicros}) supera el límite de 10.`;
                    totalMicrosError.style.display = 'block';
                    submitButton.disabled = true;
                    submitButton.style.opacity = 0.5;
                } else {
                    totalMicrosError.style.display = 'none';
                    submitButton.disabled = false;
                    submitButton.style.opacity = 1;
                }
                
                const hasRows = rows.length > 0;
                table.querySelector('thead').style.display = hasRows ? '' : 'table-header-group';
                emptyMessage.style.display = hasRows ? 'none' : 'block';

                return cantidadTotalMicros; 
            }

            function agregarOsumarMicro() {
                const tipo = tipoSelector.value;
                if (tipo === "") { 
                    tipoSelector.focus();
                    return;
                }

                const selectedOption = tipoSelector.options[tipoSelector.selectedIndex];
                const tipoTextoLabel = tipo;
                const precioUnit = parseFloat(selectedOption.dataset.precio || 0);
                const cantidadAAgregar = parseInt(cantidadSelector.value || 1);

                const existingRow = tableBody.querySelector(`tr[data-tipo="${tipo}"]`);

                if (existingRow) {
                    const cantidadInput = existingRow.querySelector('input[type="number"]');
                    let newQuantity = parseInt(cantidadInput.value) + cantidadAAgregar;
                    newQuantity = Math.min(newQuantity, 10);
                    cantidadInput.value = newQuantity;
                } else {
                    const newRow = document.createElement('tr');
                    newRow.dataset.tipo = tipo;
                    newRow.dataset.precioUnit = precioUnit;
                    
                    newRow.innerHTML = `
                        <td>${tipoTextoLabel}</td>
                        <td>
                            <input type="number" name="cantidad_micros[]" value="${cantidadAAgregar}" min="1" max="10" required title="Debe ser entre 1 y 10 micros." />
                        </td>
                        <td>${formatter.format(precioUnit)}</td>
                        <td class="subtotal">${formatter.format(precioUnit * cantidadAAgregar)}</td>
                        <td><button type="button" class="btn-remove-micro">X</button></td>
                        <input type="hidden" name="micro[]" value="${tipo}">
                    `;
                    tableBody.appendChild(newRow);
                }
                
                tipoSelector.value = "";
                cantidadSelector.value = "1";
                updateTable();
            }
            
            // --- Event Listeners de la tabla (COPIADOS PARA COMPLETITUD) ---
            tableBody.addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-remove-micro')) {
                    e.target.closest('tr').remove();
                    updateTable();
                }
            });

            tableBody.addEventListener('input', e => {
                if (e.target.type === 'number') {
                    let value = parseInt(e.target.value);
                    if (isNaN(value) || value < 1) {
                        e.target.value = 1;
                    }
                    if (value > 10) {
                        e.target.value = 10;
                    }
                    updateTable();
                }
            });
            
            tableBody.addEventListener('blur', e => {
                 if (e.target.type === 'number' && e.target.value === "") {
                    e.target.value = 1;
                    updateTable();
                 }
            }, true);

            cantidadSelector.addEventListener('input', e => {
                let value = parseInt(e.target.value);
                if (value > 10) e.target.value = 10;
                if (value < 1) e.target.value = 1;
            });

            cantidadSelector.addEventListener('blur', e => {
                if (e.target.value === "") e.target.value = 1;
            });
            // --- Fin Event Listeners de la tabla ---


            function mostrarMensaje(tipo, texto) {
                if (tipo === 'exito') {
                    modalTexto.textContent = texto;
                    requestAnimationFrame(() => {
                        modalOverlay.style.display = 'flex';
                        requestAnimationFrame(() => {
                            modalOverlay.classList.add('visible');
                        });
                    });
                    
                    // Redirección después de 2.5 segundos
                    setTimeout(() => {
                        modalOverlay.classList.remove('visible');
                        setTimeout(() => {
                            window.location.href = '../index.php';
                        }, 300);
                    }, 2200); // 2.2 segundos antes de iniciar la transición de salida

                } else { // 'error'
                    mensajeErrorDiv.className = tipo; // Asigna clase 'error'
                    mensajeErrorDiv.innerHTML = texto; 
                    mensajeErrorDiv.style.display = 'block';
                    // Hace scroll para que el usuario vea el error
                    mensajeErrorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }

            function mostrarCargando(cargando) {
                if (cargando) {
                    submitButton.disabled = true;
                    buttonText.style.display = 'none';
                    buttonSpinner.style.display = 'inline';
                } else {
                    submitButton.disabled = false;
                    buttonText.style.display = 'inline';
                    buttonSpinner.style.display = 'none';
                }
            }
            
            // --- Evento Submit (MODIFICADO) ---
            form.addEventListener('submit', function(event) {
                event.preventDefault(); 
                
                // Ocultar mensajes de error antiguos al re-intentar
                mensajeErrorDiv.style.display = 'none';
                totalMicrosError.style.display = 'none';

                // 1. Validaciones
                const fechaSalidaVal = document.getElementById('fecha_salida').value;
                const fechaRegresoVal = document.getElementById('fecha_regreso').value;

                if (fechaRegresoVal < fechaSalidaVal) {
                    mostrarMensaje('error', 'La fecha de regreso no puede ser anterior a la de salida.');
                    return;
                }

                const totalFinal = updateTable();
                if (totalFinal > limiteTotalMicros) {
                    // Ya no usamos mostrarMensaje, usamos el div específico
                    totalMicrosError.textContent = `Error: El total de micros (${totalFinal}) supera el límite de 10.`;
                    totalMicrosError.style.display = 'block';
                    totalMicrosError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return; 
                }
                
                const rows = tableBody.querySelectorAll('tr');
                if (rows.length === 0) {
                    mostrarMensaje('error', 'Debe agregar al menos un micro a la reserva.');
                    return;
                }

                // 2. Mostrar Cargando
                mostrarCargando(true);
                
                // 3. Construir Objeto de Datos (Modificado)
                const data = {
                    nombre: document.getElementById('nombre').value,
                    apellido: document.getElementById('apellido').value,
                    telefono: document.getElementById('telefono').value,
                    email: document.getElementById('email').value,
                    partida: document.getElementById('partida').value, // <-- AGREGADO
                    destino: document.getElementById('destino').value,
                    fecha_salida: fechaSalidaVal,
                    fecha_regreso: fechaRegresoVal,
                    monto: montoInput.value.replace(/[$. ]/g, '').replace(/,/g, '.'),
                    micro: [], 
                    cantidad_micros: []
                };
                rows.forEach(row => {
                    data.micro.push(row.querySelector('input[name="micro[]"]').value);
                    data.cantidad_micros.push(parseInt(row.querySelector('input[name="cantidad_micros[]"]').value));
                });
                
                // 4. Fetch (SIN CAMBIOS)
                const apiUrl = 'http://127.0.0.1:8000/api/reservas';

                fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data) 
                })
                .then(response => response.json().then(json => ({ status: response.status, json })))
                .then(res => {
                    
                    if (res.status === 201) {
                        mostrarMensaje('exito', 'Formulario enviado con éxito');
                    } 
                    else if (res.status === 422) { 
                        mostrarCargando(false); // Detener spinner
                        let errores = 'Por favor corrige los siguientes errores:<ul>';
                        for (const campo in res.json.errors) {
                            let campoNombre = campo.split('.')[0];
                            errores += `<li>${res.json.errors[campo][0]} (campo: ${campoNombre})</li>`;
                        }
                        errores += '</ul>';
                        mostrarMensaje('error', errores);
                    } 
                    else { 
                        mostrarCargando(false); // Detener spinner
                        mostrarMensaje('error', 'No se pudo procesar la solicitud. Por favor, intente más tarde.');
                    }
                })
                .catch(error => {
                    console.error('Error de red:', error);
                    mostrarCargando(false);
                    mostrarMensaje('error', 'No se pudo procesar la solicitud. Por favor, intente más tarde.');
                });
            });
            updateTable();
        });
    </script>
</body>
</html>