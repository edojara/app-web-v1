<?php
/**
 * Función para abreviar Universidad a Univ en visualización
 */
function abreviarInstitucion($nombre) {
    if (!$nombre) return $nombre;
    return str_replace('Universidad', 'Univ', $nombre);
}
?>
<div class="container">
    <div class="card">
        <div style="display: flex; justify-content: flex-start; margin-bottom: 15px;">
            <a href="?url=eventos" 
               style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; background: #f5f5f5; color: #333; text-decoration: none; border-radius: 6px; font-weight: 500; transition: all 0.3s; border: 1px solid #e0e0e0;"
               onmouseover="this.style.background='#e0e0e0'; this.style.transform='translateX(-4px)'"
               onmouseout="this.style.background='#f5f5f5'; this.style.transform='translateX(0)'">
                <span style="font-size: 18px;">←</span>
                <span>Volver a Eventos</span>
            </a>
        </div>
        
        <div style="text-align: center; margin-bottom: 25px;">
            <div style="display: inline-block; background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); padding: 4px 16px; border-radius: 20px; margin-bottom: 10px;">
                <span style="color: white; font-size: 14px; font-weight: 600;">✅ Check-in de Asistencia</span>
            </div>
            <h1 style="margin-bottom: 10px; font-size: 32px; color: #1a1a1a;"><?php echo htmlspecialchars($evento['nombre']); ?></h1>
            <div style="display: flex; gap: 20px; justify-content: center; align-items: center; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 8px; color: #1a1a1a;">
                    <span style="font-size: 20px;">📅</span>
                    <span style="font-size: 15px;"><?php echo date('d/m/Y', strtotime($evento['fecha_inicio'])); ?> - <?php echo date('d/m/Y', strtotime($evento['fecha_termino'])); ?></span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px; color: #1a1a1a;">
                    <span style="font-size: 20px;">📍</span>
                    <span style="font-size: 15px;"><?php echo htmlspecialchars($evento['lugar']); ?></span>
                </div>
            </div>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Selector de fecha -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
            <h3 style="margin-bottom: 15px; color: #1a1a1a; font-size: 18px; display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 22px;">📅</span> Seleccionar Fecha
            </h3>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <?php foreach ($fechas_evento as $fecha): 
                    $es_hoy = $fecha === date('Y-m-d');
                    $es_seleccionada = ($fecha === $fecha_seleccionada);
                    $checkins_fecha = $this->checkinModel->contarByEventoYFecha($evento_id, $fecha);
                ?>
                    <a href="?url=checkin&evento_id=<?php echo $evento_id; ?>&fecha=<?php echo $fecha; ?>" 
                       class="fecha-btn <?php echo $es_seleccionada ? 'active' : ''; ?>"
                       style="display: inline-flex; align-items: center; gap: 8px; padding: 14px 24px; 
                              background: <?php echo $es_seleccionada ? 'linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%)' : 'linear-gradient(135deg, #f5f7fa 0%, #e8ebf0 100%)'; ?>; 
                              color: <?php echo $es_seleccionada ? 'white' : '#1a1a1a'; ?>; 
                              text-decoration: none; 
                              border-radius: 10px; 
                              font-weight: 600;
                              transition: all 0.3s ease;
                              box-shadow: 0 2px 8px rgba(0,0,0,<?php echo $es_seleccionada ? '0.15' : '0.05'; ?>);
                              border: 2px solid <?php echo $es_seleccionada ? '#2e7d32' : 'transparent'; ?>;">
                        <span style="font-size: 20px;"><?php echo $es_seleccionada ? '✅' : '📆'; ?></span>
                        <div style="display: flex; flex-direction: column; align-items: flex-start;">
                            <?php if ($es_hoy): ?>
                                <span style="font-size: 12px; opacity: 0.85; font-weight: 700; letter-spacing: 0.5px;">HOY</span>
                            <?php endif; ?>
                            <span style="font-size: 16px;"><?php echo date('d/m/Y', strtotime($fecha)); ?></span>
                            <span style="font-size: 13px; opacity: 0.9;"><?php echo $checkins_fecha . ' check-ins'; ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <style>
        .fecha-btn:not(.active):hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12) !important;
            background: linear-gradient(135deg, #e8ebf0 0%, #d5dae1 100%) !important;
        }
        </style>

        <!-- Resumen del día -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 25px;">
            <div style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); padding: 20px; border-radius: 8px; border-left: 4px solid #2e7d32; box-shadow: 0 2px 8px rgba(46,125,50,0.15); transition: all 0.3s ease;" class="stat-card">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                    <div style="font-size: 13px; color: #1a1a1a; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Total Inscritos</div>
                    <span style="font-size: 28px;">👥</span>
                </div>
                <div style="font-size: 36px; font-weight: bold; color: #1a1a1a;"><?php echo $total_inscritos; ?></div>
            </div>
            <div style="background: linear-gradient(135deg, #c8e6c9 0%, #a5d6a7 100%); padding: 20px; border-radius: 8px; border-left: 4px solid #1b5e20; box-shadow: 0 2px 8px rgba(27,94,32,0.2); transition: all 0.3s ease;" class="stat-card">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                    <div style="font-size: 13px; color: #1a1a1a; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Check-ins Realizados</div>
                    <span style="font-size: 28px;">✅</span>
                </div>
                <div style="font-size: 36px; font-weight: bold; color: #1a1a1a;"><?php echo $checkins_hoy; ?></div>
            </div>
            <div style="background: linear-gradient(135deg, #a5d6a7 0%, #81c784 100%); padding: 20px; border-radius: 8px; border-left: 4px solid #388e3c; box-shadow: 0 2px 8px rgba(56,142,60,0.15); transition: all 0.3s ease;" class="stat-card">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                    <div style="font-size: 13px; color: #1a1a1a; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Pendientes</div>
                    <span style="font-size: 28px;">⏳</span>
                </div>
                <div style="font-size: 36px; font-weight: bold; color: #1a1a1a;"><?php echo $total_inscritos - $checkins_hoy; ?></div>
            </div>
        </div>

        <style>
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15) !important;
        }
        </style>

        <!-- Búsqueda rápida por RUT -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 25px; border-left: 4px solid #2e7d32;">
            <h3 style="color: #1a1a1a; margin-bottom: 15px; display: flex; align-items: center; gap: 8px; font-size: 18px;">
                <span style="font-size: 22px;">🔍</span> Check-in Rápido por RUT
            </h3>
            <div style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
                <div style="flex: 1; max-width: 500px;">
                    <input type="text" 
                           id="rutBusqueda" 
                           placeholder="Ingrese RUT del participante (ej: 12.345.678-9)" 
                           class="form-control"
                           style="width: 100%; padding: 10px 15px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 15px; transition: all 0.3s ease; color: #1a1a1a;"
                           onfocus="this.style.borderColor='#2e7d32'; this.style.boxShadow='0 0 0 3px rgba(46,125,50,0.1)'"
                           onblur="this.style.borderColor='#e0e0e0'; this.style.boxShadow='none'"
                           autofocus>
                </div>
                <button onclick="buscarYRegistrarCheckin()" 
                        class="btn btn-primary btn-checkin-rapido"
                        style="padding: 10px 24px; background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; font-size: 15px; box-shadow: 0 2px 8px rgba(46,125,50,0.3); white-space: nowrap;">
                    ✅ Registrar Check-in
                </button>
            </div>
            <div id="resultadoBusqueda" style="margin-top: 15px;"></div>
        </div>

        <style>
        .btn-checkin-rapido:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(46,125,50,0.4) !important;
        }
        .btn-checkin-rapido:active {
            transform: translateY(0);
        }
        </style>

        <!-- Lista de participantes -->
        <div style="margin-top: 25px;">
            <h2 style="color: #1a1a1a; margin-bottom: 15px; display: flex; align-items: center; gap: 8px; font-size: 22px;">
                <span style="font-size: 26px;">📋</span> Lista de Participantes
            </h2>
            
            <!-- Filtro de búsqueda -->
            <div style="margin-bottom: 15px;">
                <input 
                    type="text" 
                    id="searchInput" 
                    class="form-control" 
                    placeholder="🔍 Buscar por nombre, RUT o institución..." 
                    style="max-width: 500px; padding: 10px 15px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 15px; transition: all 0.3s ease; color: #1a1a1a;"
                    onfocus="this.style.borderColor='#2e7d32'; this.style.boxShadow='0 0 0 3px rgba(46,125,50,0.1)'"
                    onblur="this.style.borderColor='#e0e0e0'; this.style.boxShadow='none'">
            </div>
            
            <?php if (!empty($inscripciones)): ?>
                <!-- Contenedor de la tabla -->
                <div id="tableContainer"></div>
                
                <!-- Paginación -->
                <div class="pagination-container" style="margin-top: 25px;">
                    <div class="pagination-controls" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <label for="recordsPerPage" style="color: #1a1a1a; font-weight: 600; font-size: 14px;">Mostrar: </label>
                            <select id="recordsPerPage" 
                                    onchange="changeRecordsPerPage()" 
                                    class="form-control" 
                                    style="display: inline-block; width: auto; padding: 6px 30px 6px 10px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 14px; background: white; cursor: pointer; transition: all 0.3s ease; color: #1a1a1a;">
                                <option value="10">10</option>
                                <option value="20" selected>20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="all">Todos</option>
                            </select>
                            <span id="recordsInfo" style="color: #1a1a1a; font-size: 14px;"></span>
                        </div>
                        <div id="paginationButtons"></div>
                    </div>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: #999; padding: 40px;">
                    No hay participantes inscritos en este evento
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Estilos para paginación */
.pagination-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.pagination-controls > div {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

#paginationButtons {
    display: flex;
    gap: 8px;
}

#paginationButtons .btn {
    min-width: 40px;
    padding: 8px 16px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    background: white;
    color: #555;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
}

#paginationButtons .btn:hover:not(.disabled) {
    background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
    color: white;
    border-color: #2e7d32;
    transform: translateY(-1px);
}

#paginationButtons .btn.active {
    background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
    color: white;
    border-color: #2e7d32;
}

#paginationButtons .btn.disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

/* Estilos globales para botones de registrar en tabla */
.btn-registrar:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(25,118,210,0.4) !important;
}

.btn-registrar:active {
    transform: translateY(0);
}
</style>

<script>
const inscripcionesData = <?php echo json_encode($inscripciones); ?>;
const eventoId = <?php echo $evento_id; ?>;
const fechaSeleccionada = '<?php echo $fecha_seleccionada; ?>';
let filteredData = [...inscripcionesData];
let currentPage = 1;
let recordsPerPage = 20;

// Función para renderizar la tabla
function renderTable() {
    const container = document.getElementById('tableContainer');
    if (!container) return;
    
    const totalRecords = filteredData.length;
    const totalPages = recordsPerPage === 'all' ? 1 : Math.ceil(totalRecords / recordsPerPage);
    
    if (currentPage > totalPages && totalPages > 0) {
        currentPage = totalPages;
    }
    
    const startIndex = recordsPerPage === 'all' ? 0 : (currentPage - 1) * recordsPerPage;
    const endIndex = recordsPerPage === 'all' ? totalRecords : startIndex + parseInt(recordsPerPage);
    const pageData = filteredData.slice(startIndex, endIndex);
    
    let html = '<div style="display: grid; grid-template-columns: 50px 2.5fr 1fr 2fr 1fr 120px; gap: 0; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border: 1px solid #e0e0e0;">';
    
    // Header
    html += '<div style="background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); color: white; padding: 12px; font-weight: 700; border-right: 1px solid rgba(255,255,255,0.2); font-size: 13px; text-transform: uppercase; letter-spacing: 0.3px;">#</div>';
    html += '<div style="background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); color: white; padding: 12px; font-weight: 700; border-right: 1px solid rgba(255,255,255,0.2); font-size: 13px; text-transform: uppercase; letter-spacing: 0.3px;">Nombre Completo</div>';
    html += '<div style="background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); color: white; padding: 12px; font-weight: 700; border-right: 1px solid rgba(255,255,255,0.2); font-size: 13px; text-transform: uppercase; letter-spacing: 0.3px;">RUT</div>';
    html += '<div style="background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); color: white; padding: 12px; font-weight: 700; border-right: 1px solid rgba(255,255,255,0.2); font-size: 13px; text-transform: uppercase; letter-spacing: 0.3px;">Institución</div>';
    html += '<div style="background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); color: white; padding: 12px; font-weight: 700; border-right: 1px solid rgba(255,255,255,0.2); text-align: center; font-size: 13px; text-transform: uppercase; letter-spacing: 0.3px;">Check-ins</div>';
    html += '<div style="background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); color: white; padding: 12px; font-weight: 700; text-align: center; font-size: 13px; text-transform: uppercase; letter-spacing: 0.3px;">Estado Hoy</div>';
    
    // Data rows
    pageData.forEach((inscripcion, index) => {
        const globalIndex = startIndex + index + 1;
        const bgColor = index % 2 == 0 ? '#fafbfc' : 'white';
        const institucionAbrev = inscripcion.institucion_nombre ? inscripcion.institucion_nombre.replace('Universidad', 'Univ') : 'Sin institución';
        const tieneCheckin = inscripcion.tiene_checkin_hoy;
        
        html += `<div style="background: ${bgColor}; padding: 12px; border-bottom: 1px solid #e8e8e8; border-right: 1px solid #e8e8e8; display: flex; align-items: center; font-weight: 600; color: #1a1a1a; font-size: 14px;">${globalIndex}</div>`;
        
        html += `<div style="background: ${bgColor}; padding: 12px; border-bottom: 1px solid #e8e8e8; border-right: 1px solid #e8e8e8; display: flex; align-items: center;">
            <a href="javascript:void(0)" 
               onclick="verDetalleParticipante(${inscripcion.participante_id})"
               style="color: #2e7d32; text-decoration: none; font-weight: 600; cursor: pointer; transition: all 0.2s ease; font-size: 14px;"
               onmouseover="this.style.color='#1b5e20'; this.style.textDecoration='underline'"
               onmouseout="this.style.color='#2e7d32'; this.style.textDecoration='none'"
               title="Ver detalles del participante">
                ${inscripcion.nombre_completo}
            </a>
        </div>`;
        
        html += `<div style="background: ${bgColor}; padding: 12px; border-bottom: 1px solid #e8e8e8; border-right: 1px solid #e8e8e8; display: flex; align-items: center; color: #1a1a1a; font-size: 14px;">${inscripcion.rut}</div>`;
        
        html += `<div style="background: ${bgColor}; padding: 12px; border-bottom: 1px solid #e8e8e8; border-right: 1px solid #e8e8e8; display: flex; align-items: center;">
            <span style="background: linear-gradient(135deg, #c8e6c9 0%, #a5d6a7 100%); padding: 4px 8px; border-radius: 4px; font-size: 13px; color: #1a1a1a; font-weight: 500;">
                ${institucionAbrev}
            </span>
        </div>`;
        
        html += `<div style="background: ${bgColor}; padding: 12px; border-bottom: 1px solid #e8e8e8; border-right: 1px solid #e8e8e8; display: flex; align-items: center; justify-content: center;">
            <span style="font-weight: 700; font-size: 16px; color: #1a1a1a;">${inscripcion.total_checkins}</span>
        </div>`;
        
        if (tieneCheckin) {
            html += `<div style="background: ${bgColor}; padding: 12px; border-bottom: 1px solid #e8e8e8; display: flex; align-items: center; justify-content: center;">
                <span style="background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 4px rgba(46,125,50,0.2);">
                    <span style="font-size: 13px;">✓</span> Presente
                </span>
            </div>`;
        } else {
            html += `<div style="background: ${bgColor}; padding: 12px; border-bottom: 1px solid #e8e8e8; display: flex; align-items: center; justify-content: center;">
                <button onclick="registrarCheckin(${inscripcion.id})" 
                        class="btn btn-primary btn-registrar" 
                        style="padding: 6px 12px; font-size: 12px; background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%); color: white; border: none; border-radius: 4px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(25,118,210,0.2);">
                    ✅ Registrar
                </button>
            </div>`;
        }
    });
    
    html += '</div>';
    container.innerHTML = html;
    
    updatePaginationControls(totalPages, totalRecords, startIndex + 1, Math.min(endIndex, totalRecords));
}

// Actualizar controles de paginación
function updatePaginationControls(totalPages, totalRecords, startRecord, endRecord) {
    const container = document.getElementById('paginationButtons');
    const infoContainer = document.getElementById('recordsInfo');
    
    if (!container) return;
    
    infoContainer.textContent = `Mostrando ${startRecord} a ${endRecord} de ${totalRecords} registros`;
    
    if (recordsPerPage === 'all' || totalPages <= 1) {
        container.innerHTML = '';
        return;
    }
    
    let html = '';
    html += `<button class="btn btn-sm btn-outline-primary" onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>← Anterior</button>`;
    
    const maxButtons = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
    let endPage = Math.min(totalPages, startPage + maxButtons - 1);
    
    if (endPage - startPage < maxButtons - 1) {
        startPage = Math.max(1, endPage - maxButtons + 1);
    }
    
    for (let i = startPage; i <= endPage; i++) {
        html += `<button class="btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'}" onclick="goToPage(${i})">${i}</button>`;
    }
    
    html += `<button class="btn btn-sm btn-outline-primary" onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>Siguiente →</button>`;
    
    container.innerHTML = html;
}

function goToPage(page) {
    currentPage = page;
    renderTable();
}

function changeRecordsPerPage() {
    const select = document.getElementById('recordsPerPage');
    recordsPerPage = select.value === 'all' ? 'all' : parseInt(select.value);
    currentPage = 1;
    renderTable();
}

function filterParticipantes() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    
    filteredData = inscripcionesData.filter(inscripcion => {
        return inscripcion.nombre_completo.toLowerCase().includes(searchTerm) ||
               inscripcion.rut.toLowerCase().includes(searchTerm) ||
               (inscripcion.institucion_nombre && inscripcion.institucion_nombre.toLowerCase().includes(searchTerm));
    });
    
    currentPage = 1;
    renderTable();
}

// Registrar check-in individual
function registrarCheckin(inscripcionId) {
    if (confirm('¿Confirmar check-in para este participante?')) {
        // Guardar estado actual de la tabla
        sessionStorage.setItem('currentPage', currentPage);
        sessionStorage.setItem('searchTerm', document.getElementById('searchInput').value);
        sessionStorage.setItem('itemsPerPage', itemsPerPage);
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '?url=checkin/registrar';
        
        const inputInscripcion = document.createElement('input');
        inputInscripcion.type = 'hidden';
        inputInscripcion.name = 'inscripcion_id';
        inputInscripcion.value = inscripcionId;
        
        const inputEvento = document.createElement('input');
        inputEvento.type = 'hidden';
        inputEvento.name = 'evento_id';
        inputEvento.value = eventoId;
        
        const inputFecha = document.createElement('input');
        inputFecha.type = 'hidden';
        inputFecha.name = 'fecha';
        inputFecha.value = fechaSeleccionada;
        
        form.appendChild(inputInscripcion);
        form.appendChild(inputEvento);
        form.appendChild(inputFecha);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Búsqueda y registro rápido por RUT
function buscarYRegistrarCheckin() {
    const rutInput = document.getElementById('rutBusqueda');
    const rut = rutInput.value.trim();
    const resultado = document.getElementById('resultadoBusqueda');
    
    if (!rut) {
        resultado.innerHTML = '<div class="alert alert-error">Por favor ingrese un RUT</div>';
        return;
    }
    
    resultado.innerHTML = '<div style="color: #666;">🔄 Procesando...</div>';
    
    fetch('?url=checkin/buscarPorRut', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `rut=${encodeURIComponent(rut)}&evento_id=${eventoId}&fecha=${fechaSeleccionada}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultado.innerHTML = `
                <div class="alert alert-success">
                    <strong>✅ ${data.message}</strong><br>
                    Participante: ${data.participante.nombre_completo}<br>
                    Hora: ${data.hora}
                </div>
            `;
            rutInput.value = '';
            
            // Guardar estado actual de la tabla
            sessionStorage.setItem('currentPage', currentPage);
            sessionStorage.setItem('searchTerm', document.getElementById('searchInput').value);
            sessionStorage.setItem('itemsPerPage', itemsPerPage);
            
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            resultado.innerHTML = `<div class="alert alert-error">${data.message}</div>`;
        }
    })
    .catch(error => {
        resultado.innerHTML = '<div class="alert alert-error">Error al procesar la solicitud</div>';
    });
}

// Event listener para Enter en campo de RUT
document.addEventListener('DOMContentLoaded', function() {
    // Restaurar estado de la tabla si existe
    const savedPage = sessionStorage.getItem('currentPage');
    const savedSearch = sessionStorage.getItem('searchTerm');
    const savedItemsPerPage = sessionStorage.getItem('itemsPerPage');
    
    if (savedPage) {
        currentPage = parseInt(savedPage);
        sessionStorage.removeItem('currentPage');
    }
    
    if (savedSearch) {
        document.getElementById('searchInput').value = savedSearch;
        filterParticipantes();
        sessionStorage.removeItem('searchTerm');
    }
    
    if (savedItemsPerPage) {
        itemsPerPage = parseInt(savedItemsPerPage);
        document.getElementById('itemsPerPage').value = itemsPerPage;
        sessionStorage.removeItem('itemsPerPage');
    }
    
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', filterParticipantes);
    }
    
    const rutInput = document.getElementById('rutBusqueda');
    if (rutInput) {
        rutInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                buscarYRegistrarCheckin();
            }
        });
    }
    
    // Renderizar tabla inicial
    if (inscripcionesData.length > 0) {
        renderTable();
    }
});
</script>
