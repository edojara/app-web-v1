-- Insertar inscripciones de demostración para probar funcionalidades
-- Usuario ID 10 realiza todas las inscripciones

-- Evento 21: Encuentro Nacional de Educación Rural (8 participantes)
INSERT INTO inscripciones (evento_id, participante_id, user_id, observaciones) VALUES
(21, 37, 10, 'Confirmado - Grupo delegación norte'),
(21, 38, 10, 'Confirmado - Requiere alojamiento'),
(21, 39, 10, 'Confirmado'),
(21, 40, 10, 'Confirmado - Viaja con acompañante'),
(21, 42, 10, 'Confirmado - Requiere certificado'),
(21, 45, 10, 'Confirmado'),
(21, 48, 10, 'Confirmado - Delegación zona central'),
(21, 51, 10, 'Lista de espera');

-- Evento 20: Workshop de Gamificación en el Aula (6 participantes)
INSERT INTO inscripciones (evento_id, participante_id, user_id, observaciones) VALUES
(20, 41, 10, 'Confirmado - Nivel avanzado'),
(20, 43, 10, 'Confirmado'),
(20, 44, 10, 'Confirmado - Primera vez'),
(20, 46, 10, 'Confirmado - Experiencia previa'),
(20, 49, 10, 'Confirmado'),
(20, 52, 10, 'Confirmado - Grupo docentes secundaria');

-- Evento 19: Simposio de Evaluación Formativa (10 participantes)
INSERT INTO inscripciones (evento_id, participante_id, user_id, observaciones) VALUES
(19, 37, 10, 'Confirmado - Ponente'),
(19, 39, 10, 'Confirmado'),
(19, 41, 10, 'Confirmado - Moderador panel'),
(19, 43, 10, 'Confirmado'),
(19, 45, 10, 'Confirmado - Asistente'),
(19, 47, 10, 'Confirmado'),
(19, 50, 10, 'Confirmado - Coordina taller'),
(19, 53, 10, 'Confirmado'),
(19, 55, 10, 'Confirmado - Observador'),
(19, 56, 10, 'Confirmado');

-- Evento 18: Conferencia Internacional de Neuroeducación (12 participantes)
INSERT INTO inscripciones (evento_id, participante_id, user_id, observaciones) VALUES
(18, 38, 10, 'Confirmado - VIP'),
(18, 40, 10, 'Confirmado'),
(18, 42, 10, 'Confirmado - Grupo investigación'),
(18, 44, 10, 'Confirmado'),
(18, 46, 10, 'Confirmado - Requiere traducción simultánea'),
(18, 48, 10, 'Confirmado'),
(18, 49, 10, 'Confirmado - Delegación internacional'),
(18, 51, 10, 'Confirmado'),
(18, 52, 10, 'Confirmado'),
(18, 54, 10, 'Confirmado - Poster científico'),
(18, 55, 10, 'Confirmado'),
(18, 56, 10, 'Confirmado - Presentación oral');

-- Evento 17: Taller de Liderazgo Educativo (5 participantes)
INSERT INTO inscripciones (evento_id, participante_id, user_id, observaciones) VALUES
(17, 37, 10, 'Confirmado - Directivo'),
(17, 41, 10, 'Confirmado - Jefe UTP'),
(17, 45, 10, 'Confirmado'),
(17, 50, 10, 'Confirmado - Inspector general'),
(17, 54, 10, 'Confirmado');

-- Evento 16: Summit Educativo Latinoamericano (15 participantes)
INSERT INTO inscripciones (evento_id, participante_id, user_id, observaciones) VALUES
(16, 37, 10, 'Confirmado - Delegado Chile'),
(16, 38, 10, 'Confirmado'),
(16, 39, 10, 'Confirmado - Grupo coordinador'),
(16, 40, 10, 'Confirmado'),
(16, 41, 10, 'Confirmado - Expositor'),
(16, 42, 10, 'Confirmado'),
(16, 43, 10, 'Confirmado - Comité organizador'),
(16, 44, 10, 'Confirmado'),
(16, 46, 10, 'Confirmado'),
(16, 47, 10, 'Confirmado - Prensa acreditada'),
(16, 48, 10, 'Confirmado'),
(16, 50, 10, 'Confirmado'),
(16, 52, 10, 'Confirmado - Invitado especial'),
(16, 53, 10, 'Confirmado'),
(16, 56, 10, 'Confirmado');

-- Evento 15: Congreso de Educación STEM (7 participantes)
INSERT INTO inscripciones (evento_id, participante_id, user_id, observaciones) VALUES
(15, 38, 10, 'Confirmado - Profesor matemáticas'),
(15, 42, 10, 'Confirmado - Profesor física'),
(15, 44, 10, 'Confirmado'),
(15, 46, 10, 'Confirmado - Coordinador STEM'),
(15, 49, 10, 'Confirmado'),
(15, 51, 10, 'Confirmado - Laboratorio ciencias'),
(15, 55, 10, 'Confirmado');

-- Evento 14: Foro de Innovación Pedagógica 2025 (9 participantes)
INSERT INTO inscripciones (evento_id, participante_id, user_id, observaciones) VALUES
(14, 37, 10, 'Asistió - Evaluación excelente'),
(14, 39, 10, 'Asistió'),
(14, 41, 10, 'Asistió - Participó activamente'),
(14, 43, 10, 'Asistió'),
(14, 45, 10, 'Asistió - Certificado entregado'),
(14, 47, 10, 'Asistió'),
(14, 50, 10, 'Asistió'),
(14, 52, 10, 'Asistió - Feedback positivo'),
(14, 54, 10, 'Asistió');

-- Evento 13: Ceremonia de Certificación Docente 2024 (20 participantes - evento masivo)
INSERT INTO inscripciones (evento_id, participante_id, user_id, observaciones) VALUES
(13, 37, 10, 'Certificado emitido'),
(13, 38, 10, 'Certificado emitido'),
(13, 39, 10, 'Certificado emitido'),
(13, 40, 10, 'Certificado emitido'),
(13, 41, 10, 'Certificado emitido'),
(13, 42, 10, 'Certificado emitido'),
(13, 43, 10, 'Certificado emitido'),
(13, 44, 10, 'Certificado emitido'),
(13, 45, 10, 'Certificado emitido'),
(13, 46, 10, 'Certificado emitido'),
(13, 47, 10, 'Certificado emitido'),
(13, 48, 10, 'Certificado emitido'),
(13, 49, 10, 'Certificado emitido'),
(13, 50, 10, 'Certificado emitido'),
(13, 51, 10, 'Certificado emitido'),
(13, 52, 10, 'Certificado emitido'),
(13, 53, 10, 'Certificado emitido'),
(13, 54, 10, 'Certificado emitido'),
(13, 55, 10, 'Certificado emitido'),
(13, 56, 10, 'Certificado emitido');

-- Evento 12: Seminario: Educación y Sostenibilidad (4 participantes)
INSERT INTO inscripciones (evento_id, participante_id, user_id, observaciones) VALUES
(12, 40, 10, 'Asistió - Proyecto medio ambiente'),
(12, 43, 10, 'Asistió'),
(12, 48, 10, 'Asistió - Material complementario solicitado'),
(12, 53, 10, 'Asistió');
