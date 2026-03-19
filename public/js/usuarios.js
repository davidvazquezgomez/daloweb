/**
 * DaloWeb — Módulo Usuarios
 * Vanilla JS
 */
const Usuarios = (() => {
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    const BASE = "/admin/usuarios";
    let timerBusqueda = null;

    function headers(method) {
        const h = { "X-CSRF-TOKEN": CSRF, Accept: "application/json" };
        if (method !== "GET") h["Content-Type"] = "application/json";
        return h;
    }

    function escapeHtml(text) {
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }

    function formatFecha(dateStr, conHora) {
        if (!dateStr) return conHora ? "Nunca" : "-";
        const d = new Date(dateStr);
        const opciones = conHora
            ? {
                  day: "2-digit",
                  month: "short",
                  year: "numeric",
                  hour: "2-digit",
                  minute: "2-digit",
              }
            : { day: "2-digit", month: "short", year: "numeric" };
        return d.toLocaleDateString("es-ES", opciones);
    }

    // ── Búsqueda AJAX ──────────────────────────────────
    function buscar(texto) {
        const params = texto ? `?busqueda=${encodeURIComponent(texto)}` : "";
        fetch(`${BASE}${params}`, { headers: headers("GET") })
            .then((r) => r.json())
            .then((paginator) => renderTabla(paginator))
            .catch(() => {});
    }

    function renderTabla(paginator) {
        const tbody = document.getElementById("tablaUsuariosBody");
        const usuarios = paginator.data;

        if (!usuarios.length) {
            tbody.innerHTML =
                '<tr><td colspan="6" class="text-center text-soft">No se encontraron usuarios.</td></tr>';
            document.getElementById("paginacionUsuarios").innerHTML = "";
            return;
        }

        tbody.innerHTML = usuarios
            .map((u) => {
                const nombreCompleto = [u.nombre, u.apellido]
                    .filter(Boolean)
                    .join(" ");
                const inicial = u.nombre.charAt(0).toUpperCase();
                const rolLabel = u.rol === "admin" ? "Admin" : "Usuario";
                const deleteBtn =
                    u.id !== AUTH_ID
                        ? `<button type="button" class="btn btn--sm btn--ghost" onclick="Usuarios.abrirModalEditarDesdeTabla(${u.id})" title="Editar">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                          </button>
                          <button type="button" class="btn btn--sm btn--danger" onclick="Usuarios.eliminar(${u.id}, '${escapeHtml(u.nombre)}')" title="Eliminar">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2" />
                            </svg>
                          </button>`
                        : "";
                return `<tr>
                    <td><a href="${BASE}/${u.id}" class="usuario-link"><span class="usuario-avatar">${inicial}</span>${escapeHtml(nombreCompleto)}</a></td>
                    <td class="text-soft">${escapeHtml(u.correo)}</td>
                    <td><span class="badge badge--${u.rol}">${rolLabel}</span></td>
                    <td class="text-soft">${formatFecha(u.ultimo_acceso, true)}</td>
                    <td class="text-soft">${formatFecha(u.creado_en, false)}</td>
                    <td>${deleteBtn}</td>
                </tr>`;
            })
            .join("");

        // Paginación
        const pag = document.getElementById("paginacionUsuarios");
        if (paginator.last_page <= 1) {
            pag.innerHTML = "";
            return;
        }
        let links = "";
        paginator.links.forEach((link) => {
            if (!link.url) {
                links += `<span class="paginacion-btn paginacion-btn--disabled">${link.label}</span>`;
            } else if (link.active) {
                links += `<span class="paginacion-btn paginacion-btn--active">${link.label}</span>`;
            } else {
                links += `<a href="#" class="paginacion-btn" data-url="${escapeHtml(link.url)}">${link.label}</a>`;
            }
        });
        pag.innerHTML = `<div class="paginacion"><nav class="paginacion-nav">${links}</nav></div>`;

        // Listeners paginación
        pag.querySelectorAll("a[data-url]").forEach((a) => {
            a.addEventListener("click", (e) => {
                e.preventDefault();
                fetch(a.dataset.url, { headers: headers("GET") })
                    .then((r) => r.json())
                    .then((p) => renderTabla(p))
                    .catch(() => {});
            });
        });
    }

    // ── Modal Crear ────────────────────────────────────
    function abrirModalCrear() {
        document.getElementById("formUsuario").reset();
        document.getElementById("usuarioErrores").style.display = "none";
        document.getElementById("modalUsuario").style.display = "flex";
    }

    function cerrarModal() {
        document.getElementById("modalUsuario").style.display = "none";
    }

    async function crear(e) {
        e.preventDefault();
        const errBox = document.getElementById("usuarioErrores");
        errBox.style.display = "none";

        const datos = {
            nombre: document.getElementById("uNombre").value,
            apellido: document.getElementById("uApellido").value || null,
            correo: document.getElementById("uCorreo").value,
            contrasena: document.getElementById("uContrasena").value,
            rol: document.getElementById("uRol").value,
            dni_cif: document.getElementById("uDniCif").value || null,
            telefono: document.getElementById("uTelefono").value || null,
            direccion: document.getElementById("uDireccion").value || null,
            codigo_postal:
                document.getElementById("uCodigoPostal").value || null,
            ciudad: document.getElementById("uCiudad").value || null,
            provincia: document.getElementById("uProvincia").value || null,
        };

        try {
            const res = await fetch(BASE, {
                method: "POST",
                headers: headers("POST"),
                body: JSON.stringify(datos),
            });

            if (!res.ok) {
                const err = await res.json();
                if (err.errors) {
                    errBox.innerHTML = Object.values(err.errors)
                        .flat()
                        .join("<br>");
                } else {
                    errBox.textContent =
                        err.message || "Error al crear usuario.";
                }
                errBox.style.display = "block";
                return false;
            }

            cerrarModal();
            buscar(document.getElementById("busquedaUsuarios").value.trim());
        } catch (err) {
            errBox.textContent = "Error de conexión.";
            errBox.style.display = "block";
        }
        return false;
    }

    // ── Modal Editar (en página show) ─────────────────
    function abrirModalEditar() {
        const overlay = document.getElementById("modalEditarUsuario");
        if (!overlay) return;
        document.getElementById("editarErrores").style.display = "none";
        overlay.style.display = "flex";
    }

    function cerrarModalEditar() {
        const overlay = document.getElementById("modalEditarUsuario");
        if (overlay) overlay.style.display = "none";
    }

    async function actualizar(e) {
        e.preventDefault();
        const errBox = document.getElementById("editarErrores");
        errBox.style.display = "none";

        const datos = {
            nombre: document.getElementById("eNombre").value,
            apellido: document.getElementById("eApellido").value || null,
            correo: document.getElementById("eCorreo").value,
            contrasena: document.getElementById("eContrasena").value || null,
            rol: document.getElementById("eRol").value,
            dni_cif: document.getElementById("eDniCif").value || null,
            telefono: document.getElementById("eTelefono").value || null,
            direccion: document.getElementById("eDireccion").value || null,
            codigo_postal: document.getElementById("eCodigoPostal").value || null,
            ciudad: document.getElementById("eCiudad").value || null,
            provincia: document.getElementById("eProvincia").value || null,
        };

        try {
            const res = await fetch(`${BASE}/${USUARIO_ID}`, {
                method: "PUT",
                headers: headers("PUT"),
                body: JSON.stringify(datos),
            });

            if (!res.ok) {
                const err = await res.json();
                if (err.errors) {
                    errBox.innerHTML = Object.values(err.errors).flat().join("<br>");
                } else {
                    errBox.textContent = err.message || "Error al actualizar.";
                }
                errBox.style.display = "block";
                return false;
            }

            window.location.reload();
        } catch (err) {
            errBox.textContent = "Error de conexión.";
            errBox.style.display = "block";
        }
        return false;
    }

    function abrirModalEditarDesdeTabla(id) {
        window.location.href = `${BASE}/${id}`;
    }

    // ── Eliminar ───────────────────────────────────────
    async function eliminar(id, nombre) {
        if (!confirm(`¿Eliminar al usuario "${nombre}"?`)) return;

        try {
            const res = await fetch(`${BASE}/${id}`, {
                method: "DELETE",
                headers: headers("DELETE"),
            });

            if (!res.ok) {
                const err = await res.json();
                alert(err.error || "Error al eliminar.");
                return;
            }

            buscar(document.getElementById("busquedaUsuarios").value.trim());
        } catch (err) {
            alert("Error de conexión.");
        }
    }

    // ── Init ───────────────────────────────────────────
    document.addEventListener("DOMContentLoaded", () => {
        const inputBusqueda = document.getElementById("busquedaUsuarios");
        if (inputBusqueda) {
            inputBusqueda.addEventListener("input", () => {
                clearTimeout(timerBusqueda);
                timerBusqueda = setTimeout(() => {
                    buscar(inputBusqueda.value.trim());
                }, 300);
            });
        }

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") {
                cerrarModal();
                cerrarModalEditar();
            }
        });

        const overlay = document.getElementById("modalUsuario");
        if (overlay) {
            overlay.addEventListener("click", (e) => {
                if (e.target === overlay) cerrarModal();
            });
        }

        const overlayEditar = document.getElementById("modalEditarUsuario");
        if (overlayEditar) {
            overlayEditar.addEventListener("click", (e) => {
                if (e.target === overlayEditar) cerrarModalEditar();
            });
        }
    });

    return {
        abrirModalCrear,
        cerrarModal,
        crear,
        eliminar,
        abrirModalEditar,
        cerrarModalEditar,
        actualizar,
        abrirModalEditarDesdeTabla,
    };
})();
