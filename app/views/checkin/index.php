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

<!-- Modal de confirmación de check-in -->
<div id="modalConfirmacionCheckin" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(4px);">
    <div style="background-color: white; margin: 5% auto; padding: 0; border-radius: 12px; width: 90%; max-width: 500px; box-shadow: 0 8px 32px rgba(0,0,0,0.3); animation: modalSlideIn 0.3s ease;">
        <div style="background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); color: white; padding: 1.5rem; border-radius: 12px 12px 0 0; display: flex; align-items: center; gap: 12px;">
            <span style="font-size: 2rem;">✅</span>
            <h2 style="margin: 0; font-size: 1.5rem; font-weight: 600;">Confirmar Check-in</h2>
        </div>
        <div style="padding: 2rem;">
            <div id="datosParticipante" style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; border-left: 4px solid #2e7d32;">
                <!-- Los datos se llenarán dinámicamente -->
            </div>
            
            <div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;">
                <p style="margin: 0; color: #856404; font-size: 0.95rem; display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 1.5rem;">⚠️</span>
                    <span>¿Confirma que desea registrar el check-in para este participante?</span>
                </p>
            </div>
            
            <!-- Checkbox para impresión -->
            <div style="margin-bottom: 1.5rem; padding: 1rem; background: #e3f2fd; border-radius: 8px;" id="opcionImpresion">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 0.95rem; color: #1565c0;">
                    <input type="checkbox" id="imprimirCredencial" checked style="width: 18px; height: 18px; cursor: pointer;">
                    <span>🖨️ Imprimir credencial de acreditación</span>
                </label>
            </div>
            
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button onclick="cerrarModalConfirmacion()" 
                        style="padding: 12px 24px; background: #e0e0e0; color: #333; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; font-size: 15px;">
                    Cancelar
                </button>
                <button onclick="confirmarCheckin()" 
                        style="padding: 12px 24px; background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; font-size: 15px; box-shadow: 0 2px 8px rgba(46,125,50,0.3);">
                    ✅ Confirmar y Acreditar
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

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
    
    // Desktop table
    let html = '<div class="desktop-view" style="display: grid; grid-template-columns: 50px 2.5fr 1fr 2fr 1fr 120px; gap: 0; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border: 1px solid #e0e0e0;">';
    
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
    
    // Mobile cards
    html += '<div class="mobile-view" style="display: none;">';
    
    pageData.forEach((inscripcion, index) => {
        const globalIndex = startIndex + index + 1;
        const institucionAbrev = inscripcion.institucion_nombre ? inscripcion.institucion_nombre.replace('Universidad', 'Univ') : 'Sin institución';
        const tieneCheckin = inscripcion.tiene_checkin_hoy;
        
        html += `<div style="background: white; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 4px solid ${tieneCheckin ? '#2e7d32' : '#1976d2'};">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                <div style="flex: 1;">
                    <a href="javascript:void(0)" 
                       onclick="verDetalleParticipante(${inscripcion.participante_id})"
                       style="color: #2e7d32; text-decoration: none; font-weight: bold; font-size: 1.05rem; display: block; margin-bottom: 0.25rem;">
                        ${inscripcion.nombre_completo}
                    </a>
                    <div style="color: #666; font-size: 0.9rem;">RUT: ${inscripcion.rut}</div>
                </div>
                <div style="background: #e3f2fd; color: #1976d2; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85rem; font-weight: bold; margin-left: 0.5rem;">#${globalIndex}</div>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="color: #666; font-size: 0.9rem; min-width: 100px;">🏛️ Institución:</span>
                    <span style="background: linear-gradient(135deg, #c8e6c9 0%, #a5d6a7 100%); padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; color: #1a1a1a; font-weight: 500;">
                        ${institucionAbrev}
                    </span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="color: #666; font-size: 0.9rem; min-width: 100px;">📊 Check-ins:</span>
                    <span style="font-weight: bold; color: #1a1a1a; font-size: 1.1rem;">${inscripcion.total_checkins}</span>
                </div>
            </div>
            
            <div style="padding-top: 0.75rem; border-top: 1px solid #eee;">
                ${tieneCheckin ? 
                    `<div style="text-align: center; background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); color: white; padding: 0.75rem; border-radius: 6px; font-weight: bold; box-shadow: 0 2px 4px rgba(46,125,50,0.2);">
                        ✓ Presente
                    </div>` :
                    `<button onclick="registrarCheckin(${inscripcion.id})" 
                            class="btn btn-primary" 
                            style="width: 100%; padding: 0.75rem; font-size: 1rem; background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%); color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(25,118,210,0.2);">
                        ✅ Registrar Check-in
                    </button>`
                }
            </div>
        </div>`;
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

// Variable global para almacenar el ID de inscripción temporalmente
let inscripcionIdTemporal = null;

// Registrar check-in individual
function registrarCheckin(inscripcionId) {
    // Buscar los datos del participante
    const inscripcion = inscripcionesData.find(i => i.id === inscripcionId);
    
    if (!inscripcion) {
        alert('No se encontraron los datos del participante');
        return;
    }
    
    // Guardar el ID para usarlo después
    inscripcionIdTemporal = inscripcionId;
    
    // Mostrar los datos en el modal
    const datosHTML = `
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
            <div style="background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold;">
                ${inscripcion.nombre_completo.charAt(0)}
            </div>
            <div style="flex: 1;">
                <div style="font-size: 1.2rem; font-weight: 700; color: #1a1a1a; margin-bottom: 4px;">
                    ${inscripcion.nombre_completo}
                </div>
            </div>
        </div>
        
        <div style="display: grid; gap: 12px;">
            <div style="display: flex; gap: 8px;">
                <span style="color: #666; font-weight: 600; min-width: 100px;">📋 RUT:</span>
                <span style="color: #1a1a1a; font-weight: 500;">${inscripcion.rut}</span>
            </div>
            <div style="display: flex; gap: 8px;">
                <span style="color: #666; font-weight: 600; min-width: 100px;">🏛️ Institución:</span>
                <span style="color: #1a1a1a; font-weight: 500;">${inscripcion.institucion_nombre || 'Sin institución'}</span>
            </div>
            <div style="display: flex; gap: 8px;">
                <span style="color: #666; font-weight: 600; min-width: 100px;">📊 Check-ins:</span>
                <span style="color: #1a1a1a; font-weight: 700; font-size: 1.1rem;">${inscripcion.total_checkins}</span>
            </div>
        </div>
    `;
    
    document.getElementById('datosParticipante').innerHTML = datosHTML;
    document.getElementById('modalConfirmacionCheckin').style.display = 'block';
}

// Confirmar el check-in desde el modal
function confirmarCheckin() {
    if (!inscripcionIdTemporal) return;
    
    // Verificar si se debe imprimir
    const debeImprimir = document.getElementById('imprimirCredencial').checked;
    
    if (debeImprimir) {
        // Generar e imprimir PDF antes de enviar el formulario
        generarPDFCredencial(inscripcionIdTemporal);
    }
    
    // Guardar estado actual de la tabla
    sessionStorage.setItem('currentPage', currentPage);
    sessionStorage.setItem('searchTerm', document.getElementById('searchInput').value);
    sessionStorage.setItem('recordsPerPage', recordsPerPage);
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '?url=checkin/registrar';
    
    const inputInscripcion = document.createElement('input');
    inputInscripcion.type = 'hidden';
    inputInscripcion.name = 'inscripcion_id';
    inputInscripcion.value = inscripcionIdTemporal;
    
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

// Generar PDF de credencial
function generarPDFCredencial(inscripcionId) {
    const inscripcion = inscripcionesData.find(i => i.id === inscripcionId);
    if (!inscripcion) return;
    
    // Crear ventana de impresión con el diseño de la credencial
    const ventanaImpresion = window.open('', '', 'width=800,height=600');
    
    const contenidoHTML = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Credencial - ${inscripcion.nombre_completo}</title>
            <style>
                @page {
                    size: A6 landscape;
                    margin: 0;
                }
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                body {
                    font-family: 'Arial', sans-serif;
                    width: 148mm;
                    height: 105mm;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    background: #f5f5f5;
                }
                .credencial {
                    width: 140mm;
                    height: 97mm;
                    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
                    border: 3px solid #2e7d32;
                    border-radius: 12px;
                    padding: 20px;
                    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                    position: relative;
                }
                .header {
                    background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
                    color: white;
                    padding: 15px;
                    border-radius: 8px;
                    margin-bottom: 15px;
                    text-align: center;
                }
                .header h1 {
                    font-size: 24px;
                    margin-bottom: 5px;
                }
                .header p {
                    font-size: 14px;
                    opacity: 0.9;
                }
                .contenido {
                    padding: 10px;
                }
                .nombre {
                    font-size: 28px;
                    font-weight: bold;
                    color: #1a1a1a;
                    margin-bottom: 15px;
                    text-align: center;
                    text-transform: uppercase;
                }
                .datos {
                    display: grid;
                    gap: 10px;
                    margin-bottom: 15px;
                }
                .dato {
                    display: flex;
                    padding: 8px;
                    background: #f8f9fa;
                    border-left: 4px solid #2e7d32;
                    border-radius: 4px;
                }
                .dato-label {
                    font-weight: bold;
                    color: #666;
                    min-width: 120px;
                }
                .dato-valor {
                    color: #1a1a1a;
                    font-weight: 500;
                }
                .footer {
                    position: absolute;
                    bottom: 15px;
                    left: 20px;
                    right: 20px;
                    text-align: center;
                    font-size: 12px;
                    color: #666;
                    padding-top: 10px;
                    border-top: 2px solid #e0e0e0;
                }
                .checkmark {
                    position: absolute;
                    top: 20px;
                    right: 20px;
                    font-size: 48px;
                    color: #2e7d32;
                }
                @media print {
                    body {
                        background: white;
                    }
                    .credencial {
                        box-shadow: none;
                    }
                }
            </style>
        </head>
        <body>
            <div class="credencial">
                <div class="checkmark">✅</div>
                <div class="header">
                    <h1>ACREDITACIÓN</h1>
                    <p><?php echo htmlspecialchars($evento['nombre']); ?></p>
                </div>
                <div class="contenido">
                    <div class="nombre">${inscripcion.nombre_completo}</div>
                    <div class="datos">
                        <div class="dato">
                            <div class="dato-label">RUT:</div>
                            <div class="dato-valor">${inscripcion.rut}</div>
                        </div>
                        <div class="dato">
                            <div class="dato-label">Institución:</div>
                            <div class="dato-valor">${inscripcion.institucion_nombre || 'Sin institución'}</div>
                        </div>
                        <div class="dato">
                            <div class="dato-label">Fecha:</div>
                            <div class="dato-valor"><?php echo date('d/m/Y', strtotime($fecha_seleccionada)); ?></div>
                        </div>
                        <div class="dato">
                            <div class="dato-label">Lugar:</div>
                            <div class="dato-valor"><?php echo htmlspecialchars($evento['lugar']); ?></div>
                        </div>
                    </div>
                </div>
                <div class="footer">
                    Sistema de Acreditación | Generado: ${new Date().toLocaleString('es-CL')}
                </div>
            </div>
        </body>
        </html>
    `;
    
    ventanaImpresion.document.write(contenidoHTML);
    ventanaImpresion.document.close();
    
    // Esperar a que se cargue y luego imprimir
    ventanaImpresion.onload = function() {
        setTimeout(() => {
            ventanaImpresion.print();
            // Cerrar la ventana después de imprimir (o cancelar)
            setTimeout(() => {
                ventanaImpresion.close();
            }, 100);
        }, 250);
    };
}

// Cerrar modal de confirmación
function cerrarModalConfirmacion() {
    document.getElementById('modalConfirmacionCheckin').style.display = 'none';
    inscripcionIdTemporal = null;
}

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    const modal = document.getElementById('modalConfirmacionCheckin');
    if (event.target == modal) {
        cerrarModalConfirmacion();
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
            sessionStorage.setItem('recordsPerPage', recordsPerPage);
            
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
    const savedRecordsPerPage = sessionStorage.getItem('recordsPerPage');
    
    if (savedPage) {
        currentPage = parseInt(savedPage);
        sessionStorage.removeItem('currentPage');
    }
    
    if (savedSearch) {
        document.getElementById('searchInput').value = savedSearch;
        filterParticipantes();
        sessionStorage.removeItem('searchTerm');
    }
    
    if (savedRecordsPerPage) {
        recordsPerPage = savedRecordsPerPage === 'all' ? 'all' : parseInt(savedRecordsPerPage);
        document.getElementById('recordsPerPage').value = recordsPerPage;
        sessionStorage.removeItem('recordsPerPage');
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
