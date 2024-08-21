document.addEventListener("DOMContentLoaded",(function(){document.querySelectorAll(".formulario input").forEach((e=>{""!==e.value.trim()&&e.classList.add("has-value"),e.addEventListener("blur",(()=>{""!==e.value.trim()?e.classList.add("has-value"):e.classList.remove("has-value")}))}))}));let paso=1;const pasoInicial=1,pasoFinal=3,cita={id:"",nombre:"",fecha:"",hora:"",servicios:[]};function iniciarApp(){mostrarSeccion(),tabs(),botonesPaginador(),paginaSiguiente(),paginaAnterior(),consultarAPI(),idCliente(),nombreCliente(),seleccionarFecha(),seleccionarHora(),mostrarResumen()}function mostrarSeccion(){const e=document.querySelector(".mostrar");e&&e.classList.remove("mostrar");document.querySelector(`#paso-${paso}`).classList.add("mostrar");const t=document.querySelector(".actual");t&&t.classList.remove("actual");document.querySelector(`[data-paso="${paso}"]`).classList.add("actual")}function tabs(){document.querySelectorAll(".tabs button").forEach((e=>{e.addEventListener("click",(function(e){paso=parseInt(e.target.dataset.paso),mostrarSeccion(),botonesPaginador()}))}))}function botonesPaginador(){const e=document.querySelector("#anterior"),t=document.querySelector("#siguiente");1===paso?(e.classList.add("ocultar"),t.classList.remove("ocultar")):3===paso?(e.classList.remove("ocultar"),t.classList.add("ocultar"),mostrarResumen()):(e.classList.remove("ocultar"),t.classList.remove("ocultar")),mostrarSeccion()}function paginaAnterior(){document.querySelector("#anterior").addEventListener("click",(function(){paso<=pasoInicial||(paso--,botonesPaginador())}))}function paginaSiguiente(){document.querySelector("#siguiente").addEventListener("click",(function(){paso>=pasoFinal||(paso++,botonesPaginador())}))}async function consultarAPI(){try{const e="http://localhost:3000/api/servicios",t=await fetch(e);mostrarServicios(await t.json())}catch(e){console.log(e)}}function mostrarServicios(e){e.forEach((e=>{const{id:t,nombre:a,precio:o}=e,n=document.createElement("P");n.classList.add("nombre-servicio"),n.textContent=a;const c=document.createElement("P");c.classList.add("precio-servicio"),c.textContent=formatearMoneda(o);const r=document.createElement("DIV");r.classList.add("servicio"),r.dataset.idServicio=t,r.onclick=function(){seleccionarServicio(e)},r.appendChild(n),r.appendChild(c),document.querySelector("#servicios").appendChild(r)}))}function formatearMoneda(e){return new Intl.NumberFormat("es-co",{style:"currency",currency:"COP",minimumFractionDigits:0}).format(e)}function seleccionarServicio(e){const{id:t}=e,{servicios:a}=cita,o=document.querySelector(`[data-id-servicio="${t}"]`);a.some((e=>e.id===t))?(cita.servicios=a.filter((e=>e.id!==t)),o.classList.remove("seleccionado")):(cita.servicios=[...a,e],o.classList.add("seleccionado"))}function idCliente(){cita.id=document.querySelector("#id").value}function nombreCliente(){cita.nombre=document.querySelector("#nombre").value}function seleccionarFecha(){document.querySelector("#fecha").addEventListener("input",(function(e){const t=new Date(e.target.value).getUTCDay();[0].includes(t)?(e.target.value="",mostrarAlerta("Domingos no permitidos","error",".formulario-crear")):cita.fecha=e.target.value}))}function seleccionarHora(){document.querySelector("#hora").addEventListener("input",(function(e){const t=e.target.value.split(":")[0];t<10||t>22?(e.target.value="",mostrarAlerta("Hora no valida","error",".formulario-crear")):cita.hora=e.target.value}))}function mostrarAlerta(e,t,a,o=!0){const n=document.querySelector(".alerta");n&&n.remove();const c=document.createElement("DIV");c.textContent=e,c.classList.add("alerta"),c.classList.add(t);document.querySelector(a).appendChild(c),o&&setTimeout((()=>{c.remove()}),2500)}function mostrarResumen(){const e=document.querySelector(".contenido-resumen");for(;e.firstChild;)e.removeChild(e.firstChild);if(Object.values(cita).includes("")||0==cita.servicios.length)return void mostrarAlerta("Faltan datos de Servicios, Fecha u Hora","error",".contenido-resumen",!1);const{nombre:t,fecha:a,hora:o,servicios:n}=cita,c=document.createElement("H3");c.textContent="Resumen de Cita",e.appendChild(c);const r=document.createElement("DIV");r.classList.add("resumen-cita");const i=document.createElement("P");i.innerHTML=`<span>Nombre: </span> ${t}`;const s=new Date(a),d=s.getMonth(),l=s.getDate()+2,u=s.getFullYear(),m=new Date(Date.UTC(u,d,l)).toLocaleDateString("es-CO",{weekday:"long",year:"numeric",month:"long",day:"numeric"}),p=document.createElement("P");p.innerHTML=`<span>Fecha: </span> ${m}`;const v=document.createElement("P");v.innerHTML=`<span>Hora: </span> ${o}`,r.appendChild(i),r.appendChild(p),r.appendChild(v),e.appendChild(r);const h=document.createElement("H3");h.textContent="Resumen de Servicios",e.appendChild(h);const f=document.createElement("DIV");f.classList.add("titulos");const C=document.createElement("P");C.innerHTML="<span>Servicios: </span>";const L=document.createElement("P");L.innerHTML="<span>Precios: </span>",f.appendChild(C),f.appendChild(L),e.appendChild(f),n.forEach((t=>{const{id:a,precio:o,nombre:n}=t,c=document.createElement("DIV");c.classList.add("contenedor-servicio");const r=document.createElement("P");r.textContent=n;const i=document.createElement("P");i.innerHTML=`${formatearMoneda(o)}`,c.appendChild(r),c.appendChild(i),e.appendChild(c)}));const S=document.createElement("BUTTON");S.classList.add("boton"),S.textContent="Reservar Cita",S.onclick=reservarCita,e.appendChild(S)}async function reservarCita(){const{id:e,fecha:t,hora:a,servicios:o}=cita,n=o.map((e=>e.id)),c=new FormData;c.append("usuarioId",e),c.append("fecha",t),c.append("hora",a),c.append("servicios",n);try{const e=fetch("http://localhost:3000/api/citas",{method:"POST",body:c});(await(await e).json()).resultado&&Swal.fire({icon:"success",title:"Cita Creada",text:"Tu cita fue creada correctamente",button:"OK"}).then((()=>{setTimeout((()=>{window.location.reload()}),1500)}))}catch(e){Swal.fire({icon:"error",title:"Error",text:"Hubo un error al guardar la cita"}).then((()=>{setTimeout((()=>{window.location.reload()}),1500)}))}}document.addEventListener("DOMContentLoaded",(function(){iniciarApp()}));