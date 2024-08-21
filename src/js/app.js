document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll('.formulario input');

    inputs.forEach(input => {
        if (input.value.trim() !== '') {
            input.classList.add('has-value');
        }

        input.addEventListener('blur', () => {
            if (input.value.trim() !== '') {
                input.classList.add('has-value');
            } else {
                input.classList.remove('has-value');
            }
        });
    });
});

let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', function(){
    iniciarApp()
})

function iniciarApp(){
    mostrarSeccion() // Muestra y oculta las secciones
    tabs() // Cambia la seccion cuando se presionen los tabs
    botonesPaginador() // Agrega o quita los botones del paginador
    paginaSiguiente()
    paginaAnterior()

    consultarAPI() //Consulta la API en el backend de PHP

    idCliente()
    nombreCliente() // Añade el nombre del cliente al objeto de cita
    seleccionarFecha() // Añade la fecha de cita en el objeto
    seleccionarHora() // Añade la hora de la cita en el objeto

    mostrarResumen() // Muestra el resumen de la cita
}

function mostrarSeccion(){

    // Ocultar la seccion que tenga la clase de mostrar
    const seccionAnterior = document.querySelector('.mostrar')
    if(seccionAnterior){
        seccionAnterior.classList.remove('mostrar')
    }
    
    // Seleccionar la seccion con el paso...
    const seccion = document.querySelector(`#paso-${paso}`)
    seccion.classList.add('mostrar')

    // Quita la clase actual al tab Anterior
    const tabAnterior = document.querySelector('.actual')
    if(tabAnterior){
        tabAnterior.classList.remove('actual')
    }

    // Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`)
    tab.classList.add('actual')
}

function tabs(){
    const botones = document.querySelectorAll('.tabs button')

    botones.forEach( boton => {
        boton.addEventListener('click', function(e){
            paso = parseInt( e.target.dataset.paso )

            mostrarSeccion()
            botonesPaginador()
        })
    })
}

function botonesPaginador(){

    const paginaAnterior = document.querySelector('#anterior')
    const paginaSiguiente = document.querySelector('#siguiente')

    if(paso === 1){
        paginaAnterior.classList.add('ocultar')
        paginaSiguiente.classList.remove('ocultar')
    }else if(paso === 3){
        paginaAnterior.classList.remove('ocultar')
        paginaSiguiente.classList.add('ocultar')

        mostrarResumen()
    }else{
        paginaAnterior.classList.remove('ocultar')
        paginaSiguiente.classList.remove('ocultar')
    }

    mostrarSeccion()
}

function paginaAnterior(){
    const paginaAnterior = document.querySelector('#anterior')
    paginaAnterior.addEventListener('click', function(){

        if(paso <= pasoInicial) return

        paso--

        botonesPaginador()
    })
}

function paginaSiguiente(){
    const paginaSiguiente = document.querySelector('#siguiente')
    paginaSiguiente.addEventListener('click', function(){

        if(paso >= pasoFinal) return

        paso++

        botonesPaginador()
    })
}

async function consultarAPI(){
    
    try{
        const url = '/api/servicios'
        const resultado = await fetch(url)
        const servicios = await resultado.json()
        mostrarServicios(servicios)
    }catch(error){
        console.log(error)
    }
}

function mostrarServicios(servicios){
    servicios.forEach( servicio => {
        const { id, nombre, precio } = servicio
        
        const nombreServicio = document.createElement('P')
        nombreServicio.classList.add('nombre-servicio')
        nombreServicio.textContent = nombre

        const precioServicio = document.createElement('P')
        precioServicio.classList.add('precio-servicio')
        precioServicio.textContent = formatearMoneda(precio)

        const servicioDiv = document.createElement('DIV')
        servicioDiv.classList.add('servicio')
        servicioDiv.dataset.idServicio = id
        servicioDiv.onclick = function(){
            seleccionarServicio(servicio)
        }

        servicioDiv.appendChild(nombreServicio)
        servicioDiv.appendChild(precioServicio)

        document.querySelector('#servicios').appendChild(servicioDiv)
    })
}

function formatearMoneda(number) {
    return new Intl.NumberFormat("es-co", {
        style: "currency",
        currency: "COP",
        minimumFractionDigits: 0,
    }).format(number);
}

function seleccionarServicio(servicio){
    const { id } = servicio
    const {servicios} = cita

    // Identificar el elemento que se esta seleccionando
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`)

    // Comprobar si el servicio ya fue agregado
    if( servicios.some( agregado => agregado.id === id ) ){
        // Eliminarlo
        cita.servicios = servicios.filter( agregado => agregado.id !== id )
        divServicio.classList.remove('seleccionado')
    }else{
        // Agregarlo
        cita.servicios = [...servicios, servicio]
        divServicio.classList.add('seleccionado')
    }
    
}

function idCliente(){
    cita.id = document.querySelector('#id').value
}

function nombreCliente(){
    cita.nombre = document.querySelector('#nombre').value
}

function seleccionarFecha(){
    const inputFecha = document.querySelector('#fecha')
    inputFecha.addEventListener('input', function(e){

        const dia = new Date(e.target.value).getUTCDay()

        if( [0].includes(dia)){
            e.target.value = ''
            mostrarAlerta('Domingos no permitidos', 'error', '.formulario-crear')
        }else{
            cita.fecha = e.target.value
        }
    })
}

function seleccionarHora(){
    const inputHora = document.querySelector('#hora')
    inputHora.addEventListener('input', function(e){

        const horaCita = e.target.value
        const hora = horaCita.split(":")[0]
        if(hora < 10 || hora > 22){
            e.target.value = ''
            mostrarAlerta('Hora no valida', 'error','.formulario-crear')
        }else{
            cita.hora = e.target.value
        }
    })
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true){
    
    // Previene que se generen mas de 1 alerta
    const alertaPrevia = document.querySelector('.alerta')
    if(alertaPrevia){
        alertaPrevia.remove()
    }
    
    // Scripting para crear la alerta
    const alerta = document.createElement('DIV')
    alerta.textContent = mensaje
    alerta.classList.add('alerta')
    alerta.classList.add(tipo)

    const referencia = document.querySelector(elemento)
    referencia.appendChild(alerta)

    if(desaparece){
        // Eliminar la alerta
        setTimeout(()=>{
            alerta.remove()
        }, 2500)
    }
}

function mostrarResumen(){
    const resumen = document.querySelector('.contenido-resumen')

    // Limpiar el contenido de Resumen
    while(resumen.firstChild){
        resumen.removeChild(resumen.firstChild)
    }

    if(Object.values(cita).includes('') || cita.servicios.length == 0){
        mostrarAlerta('Faltan datos de Servicios, Fecha u Hora', 'error', '.contenido-resumen', false)
        return
    }

    // Formatear el div de resumen
    const { nombre, fecha, hora, servicios } = cita

    // Heading para Cita en resumen 
    const headingCita = document.createElement('H3')
    headingCita.textContent = 'Resumen de Cita'
    resumen.appendChild(headingCita) 

    const resumenCita = document.createElement('DIV')
    resumenCita.classList.add('resumen-cita')

    const nombreCliente = document.createElement('P')
    nombreCliente.innerHTML = `<span>Nombre: </span> ${nombre}`

    // Formatear la fecha en español
    const fechaObj = new Date(fecha)
    const mes = fechaObj.getMonth()
    const dia = fechaObj.getDate() + 2
    const year = fechaObj.getFullYear()

    const fechaUTC = new Date( Date.UTC(year, mes, dia) )

    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }
    const fechaFormateada = fechaUTC.toLocaleDateString('es-CO', opciones)

    const fechaCita = document.createElement('P')
    fechaCita.innerHTML = `<span>Fecha: </span> ${fechaFormateada}`

    const horaCita = document.createElement('P')
    horaCita.innerHTML = `<span>Hora: </span> ${hora}`

    resumenCita.appendChild(nombreCliente)
    resumenCita.appendChild(fechaCita)
    resumenCita.appendChild(horaCita)
    
    resumen.appendChild(resumenCita)

    // Heading para servicios en resumen 
    const headingServicios = document.createElement('H3')
    headingServicios.textContent = 'Resumen de Servicios'
    resumen.appendChild(headingServicios) 

    // Titulos de Servicio y Precio
    const titulos = document.createElement('DIV')
    titulos.classList.add('titulos')

    const tituloServicio = document.createElement('P')
    tituloServicio.innerHTML = `<span>Servicios: </span>`

    const tituloPrecio = document.createElement('P')
    tituloPrecio.innerHTML = `<span>Precios: </span>`

    titulos.appendChild(tituloServicio)
    titulos.appendChild(tituloPrecio)

    resumen.appendChild(titulos)

    // Iterando y mostrando los servicios
    servicios.forEach( servicio =>{
        const { id, precio, nombre } = servicio
        const contenedorServicio = document.createElement('DIV')
        contenedorServicio.classList.add('contenedor-servicio')

        const textoServicio = document.createElement('P')
        textoServicio.textContent = nombre

        const precioServicio = document.createElement('P')
        precioServicio.innerHTML = `${formatearMoneda(precio)}`

        contenedorServicio.appendChild(textoServicio)
        contenedorServicio.appendChild(precioServicio)

        resumen.appendChild(contenedorServicio)

    })

    // Boton para Crear una Cita
    const botonReservar = document.createElement('BUTTON')
    botonReservar.classList.add('boton')
    botonReservar.textContent = 'Reservar Cita'
    botonReservar.onclick = reservarCita

    resumen.appendChild(botonReservar)
}

async function reservarCita(){
    
    const { id, fecha, hora, servicios } = cita;

    const idServicios = servicios.map( servicio => servicio.id )

    const datos = new FormData();
    datos.append('usuarioId', id)
    datos.append('fecha', fecha)
    datos.append('hora', hora)
    datos.append('servicios', idServicios)

    try {

        // Peticion hacia la API
        const url = '/api/citas'
        const respuesta = fetch(url, {
            method: 'POST',
            body: datos
        })

        const resultado = await (await respuesta).json()
        
        if(resultado.resultado){
            Swal.fire({
                icon: "success",
                title: "Cita Creada",
                text: "Tu cita fue creada correctamente",
                button: 'OK'
            }).then( () => {

                setTimeout(() => {
                    window.location.reload()
                }, 1500);
                
            })
        }
            
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Hubo un error al guardar la cita"
        }).then( () =>{

            setTimeout(() => {
                window.location.reload()
            }, 1500);

        } )
    }

    
    // console.log([...datos])
}


