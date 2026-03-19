/**
 * DaloWeb — Módulo Demos
 * Vanilla JS
 */
const Demos = (() => {
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    const BASE = "/admin/demos";
    let datosLocales = [];

    function headers(method) {
        const h = { "X-CSRF-TOKEN": CSRF, Accept: "application/json" };
        if (method !== "GET" && method !== "DELETE")
            h["Content-Type"] = "application/json";
        return h;
    }

    function escapeHtml(text) {
        if (!text) return "";
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }

    // ── Render ─────────────────────────────────────────
    function renderGrid() {
        const grid = document.getElementById("demosGrid");
        if (!datosLocales.length) {
            grid.innerHTML =
                '<p class="demos-empty text-soft">No hay demos registradas.</p>';
            return;
        }

        grid.innerHTML = datosLocales
            .map((d) => {
                const techs = (d.tecnologias || [])
                    .map(
                        (t) =>
                            `<span class="demo-card__tech">${escapeHtml(t)}</span>`,
                    )
                    .join("");
                const techsHtml = techs
                    ? `<div class="demo-card__techs">${techs}</div>`
                    : "";
                const thumb = d.miniatura
                    ? `<img src="/${escapeHtml(d.miniatura)}" alt="${escapeHtml(d.titulo)}">`
                    : `<div class="demo-card__placeholder">
                        <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>
                        </svg>
                       </div>`;
                const visLabel =
                    d.visibilidad === "publica" ? "Pública" : "Privada";
                const visBtnClass =
                    d.visibilidad === "publica" ? "btn--success" : "btn--ghost";

                return `<div class="demo-card" data-id="${d.id}">
                    <div class="demo-card__thumb">
                        ${thumb}
                        <span class="demo-card__badge demo-card__badge--${d.visibilidad}">${visLabel}</span>
                    </div>
                    <div class="demo-card__body">
                        <h4 class="demo-card__title">${escapeHtml(d.titulo)}</h4>
                        <span class="badge badge--tipo">${d.tipo}</span>
                        ${techsHtml}
                    </div>
                    <div class="demo-card__actions">
                        <a href="/demo/${escapeHtml(d.slug)}" target="_blank" class="btn btn--sm btn--ghost" title="Ver demo">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        </a>
                        <button type="button" class="btn btn--sm btn--ghost" onclick="Demos.editar(${d.id})" title="Editar">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <button type="button" class="btn btn--sm ${visBtnClass}" onclick="Demos.toggleVisibilidad(${d.id})" title="Cambiar visibilidad">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                        <button type="button" class="btn btn--sm btn--danger" onclick="Demos.eliminar(${d.id}, '${escapeHtml(d.titulo).replace(/'/g, "\\'")}')" title="Eliminar">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                        </button>
                    </div>
                </div>`;
            })
            .join("");
    }

    // ── Modal ──────────────────────────────────────────
    function abrirModal(carpeta) {
        const form = document.getElementById("formDemo");
        form.reset();
        document.getElementById("demoId").value = "";
        document.getElementById("demoErrores").style.display = "none";
        document.getElementById("modalDemoTitle").textContent = "Nueva demo";
        document.getElementById("btnGuardarDemo").textContent = "Crear";

        if (carpeta) {
            document.getElementById("demoCarpeta").value = carpeta;
            document.getElementById("demoSlug").value = carpeta;
            document.getElementById("demoTitulo").value = carpeta
                .replace(/-/g, " ")
                .replace(/\b\w/g, (c) => c.toUpperCase());
        }

        document.getElementById("modalDemo").style.display = "flex";
    }

    function cerrarModal() {
        document.getElementById("modalDemo").style.display = "none";
    }

    function editar(id) {
        const demo = datosLocales.find((d) => d.id === id);
        if (!demo) return;

        document.getElementById("modalDemoTitle").textContent = "Editar demo";
        document.getElementById("btnGuardarDemo").textContent = "Guardar";
        document.getElementById("demoId").value = demo.id;
        document.getElementById("demoTitulo").value = demo.titulo;
        document.getElementById("demoSlug").value = demo.slug;
        document.getElementById("demoTipo").value = demo.tipo;
        document.getElementById("demoVisibilidad").value = demo.visibilidad;
        document.getElementById("demoCarpeta").value = demo.ruta_carpeta;
        document.getElementById("demoMiniatura").value = demo.miniatura || "";
        document.getElementById("demoTecnologias").value = (
            demo.tecnologias || []
        ).join(", ");
        document.getElementById("demoDescripcion").value =
            demo.descripcion || "";
        document.getElementById("demoErrores").style.display = "none";

        document.getElementById("modalDemo").style.display = "flex";
    }

    async function guardar(e) {
        e.preventDefault();
        const errBox = document.getElementById("demoErrores");
        errBox.style.display = "none";

        const id = document.getElementById("demoId").value;
        const techsRaw = document.getElementById("demoTecnologias").value;
        const tecnologias = techsRaw
            ? techsRaw
                  .split(",")
                  .map((t) => t.trim())
                  .filter(Boolean)
            : null;

        const datos = {
            titulo: document.getElementById("demoTitulo").value,
            slug: document.getElementById("demoSlug").value,
            tipo: document.getElementById("demoTipo").value,
            visibilidad: document.getElementById("demoVisibilidad").value,
            ruta_carpeta: document.getElementById("demoCarpeta").value,
            miniatura: document.getElementById("demoMiniatura").value || null,
            tecnologias,
            descripcion:
                document.getElementById("demoDescripcion").value || null,
        };

        const url = id ? `${BASE}/${id}` : BASE;
        const method = id ? "PUT" : "POST";

        try {
            const res = await fetch(url, {
                method,
                headers: headers(method),
                body: JSON.stringify(datos),
            });

            if (!res.ok) {
                const err = await res.json();
                if (err.errors) {
                    errBox.innerHTML = Object.values(err.errors)
                        .flat()
                        .join("<br>");
                } else {
                    errBox.textContent = err.message || "Error al guardar.";
                }
                errBox.style.display = "block";
                return false;
            }

            const saved = await res.json();

            if (id) {
                const idx = datosLocales.findIndex((d) => d.id === saved.id);
                if (idx !== -1) datosLocales[idx] = saved;
            } else {
                datosLocales.unshift(saved);
            }

            renderGrid();
            cerrarModal();
        } catch (err) {
            errBox.textContent = "Error de conexión.";
            errBox.style.display = "block";
        }
        return false;
    }

    // ── Toggle visibilidad ─────────────────────────────
    async function toggleVisibilidad(id) {
        try {
            const res = await fetch(`${BASE}/${id}/visibilidad`, {
                method: "PATCH",
                headers: headers("PATCH"),
            });
            if (!res.ok) {
                alert("Error al cambiar visibilidad.");
                return;
            }
            const updated = await res.json();
            const idx = datosLocales.findIndex((d) => d.id === id);
            if (idx !== -1) datosLocales[idx] = updated;
            renderGrid();
        } catch (err) {
            alert("Error de conexión.");
        }
    }

    // ── Eliminar ───────────────────────────────────────
    async function eliminar(id, titulo) {
        if (
            !confirm(
                `¿Eliminar la demo "${titulo}"?\nSe borrará también la carpeta y todos sus archivos.`,
            )
        )
            return;

        try {
            const res = await fetch(`${BASE}/${id}`, {
                method: "DELETE",
                headers: headers("DELETE"),
            });
            if (!res.ok) {
                alert("Error al eliminar.");
                return;
            }
            datosLocales = datosLocales.filter((d) => d.id !== id);
            renderGrid();
        } catch (err) {
            alert("Error de conexión.");
        }
    }

    // ── Sincronizar ────────────────────────────────────
    async function sincronizar() {
        const btn = document.getElementById("btnSincronizar");
        btn.disabled = true;
        try {
            const res = await fetch(`${BASE}/sincronizar`, {
                method: "POST",
                headers: headers("POST"),
            });
            if (!res.ok) {
                alert("Error al sincronizar.");
                return;
            }
            const data = await res.json();
            datosLocales = data.demos || [];
            renderGrid();
            if (data.registradas > 0) {
                alert(`Se registraron ${data.registradas} demo(s) nueva(s).`);
            }
        } catch (err) {
            alert("Error de conexión.");
        } finally {
            btn.disabled = false;
        }
    }

    // ── Auto-slug ──────────────────────────────────────
    function autoSlug() {
        const titulo = document.getElementById("demoTitulo");
        const slug = document.getElementById("demoSlug");
        if (document.getElementById("demoId").value) return; // No auto-slug al editar

        titulo.addEventListener("input", () => {
            slug.value = titulo.value
                .toLowerCase()
                .normalize("NFD")
                .replace(/[\u0300-\u036f]/g, "")
                .replace(/[^a-z0-9]+/g, "-")
                .replace(/^-|-$/g, "");
        });
    }

    // ── Init ───────────────────────────────────────────
    document.addEventListener("DOMContentLoaded", () => {
        datosLocales = DEMOS_DATA || [];
        autoSlug();
        renderGrid();

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") cerrarModal();
        });

        document.getElementById("modalDemo").addEventListener("click", (e) => {
            if (e.target.id === "modalDemo") cerrarModal();
        });
    });

    return {
        abrirModal,
        cerrarModal,
        editar,
        guardar,
        toggleVisibilidad,
        eliminar,
        sincronizar,
    };
})();
