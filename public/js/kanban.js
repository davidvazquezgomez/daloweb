/**
 * DaloWeb — Kanban de Tareas
 * Vanilla JS + SortableJS
 */
const Kanban = (() => {
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    const BASE = "/admin/tareas";
    let tareaActual = null; // tarea seleccionada en detalle

    // ── Helpers ──────────────────────────────────────────
    function headers(method = "GET") {
        const h = {
            "X-CSRF-TOKEN": CSRF,
            Accept: "application/json",
        };
        if (method !== "GET") h["Content-Type"] = "application/json";
        return h;
    }

    async function request(url, method, body) {
        const res = await fetch(url, {
            method,
            headers: headers(method),
            body: body ? JSON.stringify(body) : undefined,
        });
        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            throw new Error(err.message || `Error ${res.status}`);
        }
        return res.status === 204 ? null : res.json();
    }

    function actualizarContadores() {
        document.querySelectorAll(".kanban__cards").forEach((col) => {
            const estado = col.dataset.estado;
            const count = col.children.length;
            const badge = document.querySelector(`[data-count="${estado}"]`);
            if (badge) badge.textContent = count;
        });
    }

    function formatFecha(dateStr) {
        if (!dateStr) return "";
        const d = new Date(dateStr);
        return d.toLocaleDateString("es-ES", {
            day: "2-digit",
            month: "short",
        });
    }

    // ── SortableJS init ────────────────────────────────
    function initSortable() {
        document.querySelectorAll(".kanban__cards").forEach((col) => {
            new Sortable(col, {
                group: "kanban",
                animation: 150,
                ghostClass: "kanban__card--ghost",
                dragClass: "kanban__card--drag",
                onEnd: async (evt) => {
                    const cardEl = evt.item;
                    const tareaId = cardEl.dataset.id;
                    const nuevoEstado = evt.to.dataset.estado;
                    const nuevaPos = evt.newIndex;

                    try {
                        await request(`${BASE}/${tareaId}/mover`, "PATCH", {
                            estado: nuevoEstado,
                            posicion: nuevaPos,
                        });
                        cardEl.className = `kanban__card kanban__card--${nuevoEstado}`;
                        actualizarContadores();
                    } catch (e) {
                        console.error("Error al mover:", e);
                        location.reload();
                    }
                },
            });
        });
    }

    // ── Modal Crear/Editar ─────────────────────────────
    function abrirModalCrear() {
        document.getElementById("modalTareaTitle").textContent = "Nueva tarea";
        document.getElementById("btnGuardarTarea").textContent = "Crear";
        document.getElementById("tareaId").value = "";
        document.getElementById("formTarea").reset();
        document.getElementById("tareaEstado").value = "pendiente";
        document.getElementById("modalTarea").style.display = "flex";
    }

    function abrirModalEditar(tarea) {
        document.getElementById("modalTareaTitle").textContent = "Editar tarea";
        document.getElementById("btnGuardarTarea").textContent = "Guardar";
        document.getElementById("tareaId").value = tarea.id;
        document.getElementById("tareaTitulo").value = tarea.titulo;
        document.getElementById("tareaDescripcion").value =
            tarea.descripcion || "";
        document.getElementById("tareaEstado").value = tarea.estado;
        document.getElementById("tareaAsignado").value = tarea.asignado_a || "";
        document.getElementById("tareaFecha").value = tarea.fecha_limite || "";
        document.getElementById("modalTarea").style.display = "flex";
    }

    function cerrarModal() {
        document.getElementById("modalTarea").style.display = "none";
    }

    async function guardarTarea(e) {
        e.preventDefault();
        const id = document.getElementById("tareaId").value;
        const datos = {
            titulo: document.getElementById("tareaTitulo").value,
            descripcion:
                document.getElementById("tareaDescripcion").value || null,
            estado: document.getElementById("tareaEstado").value,
            asignado_a: document.getElementById("tareaAsignado").value || null,
            fecha_limite: document.getElementById("tareaFecha").value || null,
        };

        try {
            if (id) {
                await request(`${BASE}/${id}`, "PUT", datos);
            } else {
                await request(BASE, "POST", datos);
            }
            cerrarModal();
            location.reload();
        } catch (e) {
            alert("Error: " + e.message);
        }
        return false;
    }

    // ── Modal Detalle ──────────────────────────────────
    async function abrirDetalle(tareaId) {
        // Buscar datos de la tarea en el DOM card
        const card = document.querySelector(
            `.kanban__card[data-id="${tareaId}"]`,
        );
        if (!card) return;

        // Obtener tarea completa del servidor
        try {
            const tarea = await request(`${BASE}/${tareaId}`, "GET");
            tareaActual = tarea;
            renderDetalle(tarea);
            document.getElementById("modalDetalle").style.display = "flex";
        } catch (e) {
            alert("Error al cargar la tarea");
        }
    }

    function renderDetalle(tarea) {
        document.getElementById("detalleTitulo").textContent = tarea.titulo;
        document.getElementById("detalleDescripcion").textContent =
            tarea.descripcion || "Sin descripción";

        const estadoLabels = {
            pendiente: "Pendiente",
            en_progreso: "En Progreso",
            completado: "Completado",
        };
        const badge = document.getElementById("detalleEstado");
        badge.textContent = estadoLabels[tarea.estado] || tarea.estado;
        badge.className = `detalle-badge detalle-badge--${tarea.estado}`;

        document.getElementById("detalleAsignado").textContent = tarea.asignado
            ? `Asignado a: ${tarea.asignado.nombre}`
            : "Sin asignar";
        document.getElementById("detalleFecha").textContent = tarea.fecha_limite
            ? `Fecha: ${formatFecha(tarea.fecha_limite)}`
            : "";

        renderComentarios(tarea.comentarios || []);
    }

    function renderComentarios(comentarios) {
        const lista = document.getElementById("listaComentarios");
        if (comentarios.length === 0) {
            lista.innerHTML =
                '<p class="comentarios__vacio">No hay comentarios aún.</p>';
            return;
        }
        lista.innerHTML = comentarios
            .map(
                (c) => `
            <div class="comentario" data-id="${c.id}">
                <div class="comentario__header">
                    <strong>${c.usuario ? c.usuario.nombre : "Usuario"}</strong>
                    <span class="comentario__fecha">${formatFecha(c.creado_en)}</span>
                    <button type="button" class="comentario__delete" onclick="Kanban.borrarComentario(${c.id})" title="Eliminar">&times;</button>
                </div>
                <p class="comentario__texto">${escapeHtml(c.contenido)}</p>
            </div>
        `,
            )
            .join("");
    }

    function escapeHtml(text) {
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }

    function cerrarDetalle() {
        document.getElementById("modalDetalle").style.display = "none";
        tareaActual = null;
    }

    function editarDesdeDetalle() {
        if (!tareaActual) return;
        const tarea = { ...tareaActual };
        cerrarDetalle();
        abrirModalEditar(tarea);
    }

    async function eliminarTarea() {
        if (!tareaActual) return;
        if (!confirm("¿Eliminar esta tarea?")) return;
        try {
            await request(`${BASE}/${tareaActual.id}`, "DELETE");
            cerrarDetalle();
            location.reload();
        } catch (e) {
            alert("Error: " + e.message);
        }
    }

    // ── Comentarios ────────────────────────────────────
    async function agregarComentario(e) {
        e.preventDefault();
        if (!tareaActual) return false;
        const input = document.getElementById("nuevoComentario");
        const contenido = input.value.trim();
        if (!contenido) return false;

        try {
            const c = await request(
                `${BASE}/${tareaActual.id}/comentarios`,
                "POST",
                { contenido },
            );
            tareaActual.comentarios = [c, ...(tareaActual.comentarios || [])];
            renderComentarios(tareaActual.comentarios);
            input.value = "";
        } catch (e) {
            alert("Error: " + e.message);
        }
        return false;
    }

    async function borrarComentario(comentarioId) {
        if (!tareaActual || !confirm("¿Eliminar este comentario?")) return;
        try {
            await request(
                `${BASE}/${tareaActual.id}/comentarios/${comentarioId}`,
                "DELETE",
            );
            tareaActual.comentarios = tareaActual.comentarios.filter(
                (c) => c.id !== comentarioId,
            );
            renderComentarios(tareaActual.comentarios);
        } catch (e) {
            alert("Error: " + e.message);
        }
    }

    // ── Init ───────────────────────────────────────────
    document.addEventListener("DOMContentLoaded", () => {
        initSortable();

        // Cerrar modales con Escape
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") {
                cerrarModal();
                cerrarDetalle();
            }
        });

        // Cerrar al clicar fuera
        document.querySelectorAll(".modal-overlay").forEach((overlay) => {
            overlay.addEventListener("click", (e) => {
                if (e.target === overlay) {
                    cerrarModal();
                    cerrarDetalle();
                }
            });
        });
    });

    return {
        abrirModalCrear,
        abrirDetalle,
        cerrarModal,
        cerrarDetalle,
        guardarTarea,
        editarDesdeDetalle,
        eliminarTarea,
        agregarComentario,
        borrarComentario,
    };
})();
