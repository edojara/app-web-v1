// Toggle de campo de contraseña para selección de tipo de autenticación
function togglePasswordField() {
    const authType = document.getElementById('auth_type');
    const passwordField = document.getElementById('password_field');
    const passwordInput = document.getElementById('password');
    
    if (!authType || !passwordField) return;
    
    if (authType.value === 'google') {
        passwordField.classList.add('hidden');
        if (passwordInput) passwordInput.removeAttribute('required');
    } else {
        passwordField.classList.remove('hidden');
        if (passwordInput) passwordInput.setAttribute('required', 'required');
    }
}

// Función para abreviar nombres de instituciones en visualización
function abreviarInstitucion(nombre) {
    if (!nombre) return nombre;
    return nombre.replace(/Universidad/g, 'Univ');
}

// Aplicar abreviación a elementos con clase .institucion-nombre
function aplicarAbreviacionInstituciones() {
    document.querySelectorAll('.institucion-nombre').forEach(element => {
        const texto = element.textContent || element.innerText;
        const textoAbreviado = abreviarInstitucion(texto);
        if (texto !== textoAbreviado) {
            element.textContent = textoAbreviado;
        }
    });
}

// Escuchar cambios en el selector de auth_type
document.addEventListener('DOMContentLoaded', () => {
    const authType = document.getElementById('auth_type');
    if (authType) {
        authType.addEventListener('change', togglePasswordField);
        togglePasswordField(); // Ejecutar una vez al cargar la página
    }
    
    // Aplicar abreviación de instituciones
    aplicarAbreviacionInstituciones();
    
    // Observar cambios en el DOM para aplicar abreviación dinámicamente
    const observer = new MutationObserver(() => {
        aplicarAbreviacionInstituciones();
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});

// Función para confirmar eliminación
function confirmDelete(userName) {
    return confirm(`¿Está seguro de que desea eliminar al usuario "${userName}"? Esta acción no se puede deshacer.`);
}

// Función para mostrar detalles del participante en modal
function verDetalleParticipante(id) {
    console.log('verDetalleParticipante llamado con id:', id);
    // Crear el modal si no existe
    let modal = document.getElementById('participanteModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'participanteModal';
        modal.className = 'modal';
        modal.innerHTML = `
            <div class="modal-content" style="max-width: 800px;">
                <div class="modal-header">
                    <h2>Detalle del Participante</h2>
                    <span class="close" onclick="cerrarModalParticipante()">&times;</span>
                </div>
                <div id="participanteModalBody" style="padding: 20px;">
                    <div style="text-align: center; padding: 40px;">
                        <p>Cargando...</p>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
    
    // Mostrar modal
    modal.style.display = 'block';
    
    // Cargar datos del participante
    fetch(`?url=participantes/view&id=${id}&ajax=1`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('participanteModalBody').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('participanteModalBody').innerHTML = 
                '<div style="text-align: center; padding: 40px; color: red;">Error al cargar los datos</div>';
        });
}

function cerrarModalParticipante() {
    const modal = document.getElementById('participanteModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Cerrar modal al hacer clic fuera
window.addEventListener('click', function(event) {
    const modal = document.getElementById('participanteModal');
    if (event.target === modal) {
        cerrarModalParticipante();
    }
});

// Función para mostrar/ocultar alertas automáticamente
document.addEventListener('DOMContentLoaded', () => {
    const alertas = document.querySelectorAll('.alerta');
    alertas.forEach(alerta => {
        // Mostrar durante 5 segundos automáticamente para alertas de éxito
        if (alerta.classList.contains('alerta-exito')) {
            setTimeout(() => {
                alerta.style.opacity = '0';
                alerta.style.transition = 'opacity 0.3s ease';
                setTimeout(() => alerta.style.display = 'none', 300);
            }, 5000);
        }
    });
});

// Función para formatear fechas
function formatDate(dateString) {
    if (!dateString || dateString === 'Nunca') return dateString;
    
    const options = {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    };
    
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', options);
}

// Función para copiar al portapapeles
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Copiado al portapapeles');
    }).catch(err => {
        console.error('Error al copiar:', err);
    });
}

// Función para validar formularios
function validateForm(formSelector) {
    const form = document.querySelector(formSelector);
    if (!form) return true;
    
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = '#e74c3c';
            isValid = false;
        } else {
            input.style.borderColor = '';
        }
    });
    
    return isValid;
}

// Monitorear cambios en inputs para remover estilos de error
document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('input', () => {
            if (input.value.trim()) {
                input.style.borderColor = '';
            }
        });
    });
});

// Scroll suave
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
});
