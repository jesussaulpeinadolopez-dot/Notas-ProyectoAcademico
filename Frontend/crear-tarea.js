async function guardarTarea() {
    // Captura de datos (Paso 1.1)
    const nombre = document.getElementById('nombreTarea').value;
    const descripcion = document.getElementById('descripcionTarea').value;
    const fechaActual = new Date().toISOString().split('T')[0]; // Formato YYYY-MM-DD [cite: 114]

    try {
        // Envío de solicitud HTTP POST (Paso 1.2)
        const respuesta = await axios.post('/api/tareas', {
            nombre: nombre,
            descripcion: descripcion,
            fecha_creacion: fechaActual,
            estado: 'Pendiente', // Estado inicial [cite: 115]
            id_usuario: 3 // ID de ejemplo según tu reporte [cite: 116]
        });

        // Verificación del código de estado 201 Created (Paso 1.7 y 1.8)
        if (respuesta.status === 201) {
            alert(respuesta.data.mensaje); // "Tarea creada correctamente" [cite: 156, 185]
            window.location.href = 'usuario.html'; // Redirección al tablero [cite: 219]
        }
    } catch (error) {
        console.error("Error en la transferencia de información:", error);
        alert("Error al crear la tarea. Verifique la conexión con el servidor.");
    }
}