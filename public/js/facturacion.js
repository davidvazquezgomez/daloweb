/**
 * DaloWeb — Módulo Facturación
 * Vanilla JS + Chart.js
 */
const Facturacion = (() => {
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    const BASE = "/admin/facturacion";
    let chart = null;
    let datosLocales = { gastos: [], ingresos: [] };
    let usuariosMap = {};

    function headers(method) {
        const h = { "X-CSRF-TOKEN": CSRF, Accept: "application/json" };
        if (method !== "GET") h["Content-Type"] = "application/json";
        return h;
    }

    function escapeHtml(text) {
        if (!text) return "";
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }

    function formatImporte(n) {
        return (
            Number(n).toLocaleString("es-ES", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }) + " €"
        );
    }

    function formatFecha(dateStr) {
        if (!dateStr) return "-";
        const d = new Date(dateStr);
        return d.toLocaleDateString("es-ES", {
            day: "2-digit",
            month: "short",
            year: "numeric",
        });
    }

    // ── Chart ──────────────────────────────────────────
    function initChart(mensual) {
        const ctx = document.getElementById("chartMensual");
        if (!ctx) return;

        const meses = [
            "Ene",
            "Feb",
            "Mar",
            "Abr",
            "May",
            "Jun",
            "Jul",
            "Ago",
            "Sep",
            "Oct",
            "Nov",
            "Dic",
        ];

        chart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: meses,
                datasets: [
                    {
                        label: "Ingresos",
                        data: mensual.map((m) => m.ingresos),
                        backgroundColor: "rgba(34, 197, 94, .6)",
                        borderColor: "#22c55e",
                        borderWidth: 1,
                        borderRadius: 4,
                    },
                    {
                        label: "Gastos",
                        data: mensual.map((m) => m.gastos),
                        backgroundColor: "rgba(239, 68, 68, .6)",
                        borderColor: "#ef4444",
                        borderWidth: 1,
                        borderRadius: 4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: "#a0a0b8", font: { size: 12 } },
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) =>
                                `${ctx.dataset.label}: ${formatImporte(ctx.raw)}`,
                        },
                    },
                },
                scales: {
                    x: {
                        ticks: { color: "#a0a0b8" },
                        grid: { color: "rgba(42, 42, 64, .5)" },
                    },
                    y: {
                        ticks: {
                            color: "#a0a0b8",
                            callback: (v) => v + " €",
                        },
                        grid: { color: "rgba(42, 42, 64, .5)" },
                    },
                },
            },
        });
    }

    function actualizarResumen() {
        const tI = datosLocales.ingresos.reduce(
            (s, i) => s + parseFloat(i.importe),
            0,
        );
        const tG = datosLocales.gastos.reduce(
            (s, g) => s + parseFloat(g.importe),
            0,
        );
        document.getElementById("totalIngresos").textContent =
            formatImporte(tI);
        document.getElementById("totalGastos").textContent = formatImporte(tG);
        document.getElementById("totalBalance").textContent = formatImporte(
            tI - tG,
        );

        actualizarChart();
    }

    function actualizarChart() {
        if (!chart) return;

        const ingMensual = new Array(12).fill(0);
        const gasMensual = new Array(12).fill(0);

        datosLocales.ingresos.forEach((i) => {
            const mes = new Date(i.fecha).getMonth();
            ingMensual[mes] += parseFloat(i.importe);
        });
        datosLocales.gastos.forEach((g) => {
            const mes = new Date(g.fecha).getMonth();
            gasMensual[mes] += parseFloat(g.importe);
        });

        chart.data.datasets[0].data = ingMensual;
        chart.data.datasets[1].data = gasMensual;
        chart.update();
    }

    // ── Render tablas ──────────────────────────────────
    function renderIngresos() {
        const tbody = document.getElementById("tablaIngresos");
        if (!datosLocales.ingresos.length) {
            tbody.innerHTML =
                '<tr><td colspan="6" class="text-center text-soft">Sin ingresos registrados.</td></tr>';
            return;
        }
        tbody.innerHTML = datosLocales.ingresos
            .map(
                (i) => `
            <tr data-id="${i.id}">
                <td>${escapeHtml(i.concepto)}</td>
                <td class="text-soft">${i.cliente ? escapeHtml([i.cliente.nombre, i.cliente.apellido].filter(Boolean).join(" ")) : usuariosMap[i.cliente_id] || "-"}</td>
                <td><span class="badge badge--tipo">${i.tipo}</span></td>
                <td class="importe importe--positivo">${formatImporte(i.importe)}</td>
                <td class="text-soft">${formatFecha(i.fecha)}</td>
                <td class="acciones-cell">
                    <button type="button" class="btn btn--sm btn--ghost" onclick="Facturacion.editar('ingreso', ${i.id})" title="Editar">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    <button type="button" class="btn btn--sm btn--danger" onclick="Facturacion.eliminar('ingreso', ${i.id})" title="Eliminar">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                    </button>
                </td>
            </tr>
        `,
            )
            .join("");
    }

    function renderGastos() {
        const tbody = document.getElementById("tablaGastos");
        if (!datosLocales.gastos.length) {
            tbody.innerHTML =
                '<tr><td colspan="5" class="text-center text-soft">Sin gastos registrados.</td></tr>';
            return;
        }
        tbody.innerHTML = datosLocales.gastos
            .map(
                (g) => `
            <tr data-id="${g.id}">
                <td>
                    ${escapeHtml(g.concepto)}
                    ${g.recurrente ? '<span class="badge badge--recurrente" title="Recurrente">↻</span>' : ""}
                </td>
                <td><span class="badge badge--cat">${g.categoria}</span></td>
                <td class="importe importe--negativo">${formatImporte(g.importe)}</td>
                <td class="text-soft">${formatFecha(g.fecha)}</td>
                <td class="acciones-cell">
                    <button type="button" class="btn btn--sm btn--ghost" onclick="Facturacion.editar('gasto', ${g.id})" title="Editar">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    <button type="button" class="btn btn--sm btn--danger" onclick="Facturacion.eliminar('gasto', ${g.id})" title="Eliminar">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                    </button>
                </td>
            </tr>
        `,
            )
            .join("");
    }

    // ── Modales ────────────────────────────────────────
    function abrirModal(tipo) {
        const modal = document.getElementById(
            tipo === "ingreso" ? "modalIngreso" : "modalGasto",
        );
        const form = document.getElementById(
            tipo === "ingreso" ? "formIngreso" : "formGasto",
        );
        const title = document.getElementById(
            tipo === "ingreso" ? "modalIngresoTitle" : "modalGastoTitle",
        );
        const btnGuardar = document.getElementById(
            tipo === "ingreso" ? "btnGuardarIngreso" : "btnGuardarGasto",
        );

        form.reset();
        document.getElementById(tipo + "Id").value = "";
        document.getElementById(tipo + "Errores").style.display = "none";
        title.textContent =
            tipo === "ingreso" ? "Nuevo ingreso" : "Nuevo gasto";
        btnGuardar.textContent = "Crear";

        if (tipo === "gasto") {
            toggleRecurrente();
        }

        modal.style.display = "flex";
    }

    function cerrarModal(tipo) {
        document.getElementById(
            tipo === "ingreso" ? "modalIngreso" : "modalGasto",
        ).style.display = "none";
    }

    function editar(tipo, id) {
        const lista =
            tipo === "ingreso" ? datosLocales.ingresos : datosLocales.gastos;
        const item = lista.find((x) => x.id === id);
        if (!item) return;

        const title = document.getElementById(
            tipo === "ingreso" ? "modalIngresoTitle" : "modalGastoTitle",
        );
        const btnGuardar = document.getElementById(
            tipo === "ingreso" ? "btnGuardarIngreso" : "btnGuardarGasto",
        );

        title.textContent =
            tipo === "ingreso" ? "Editar ingreso" : "Editar gasto";
        btnGuardar.textContent = "Guardar";
        document.getElementById(tipo + "Id").value = item.id;
        document.getElementById(tipo + "Concepto").value = item.concepto;
        document.getElementById(tipo + "Importe").value = item.importe;
        document.getElementById(tipo + "Fecha").value = item.fecha
            ? item.fecha.substring(0, 10)
            : "";

        if (tipo === "ingreso") {
            document.getElementById("ingresoClienteId").value =
                item.cliente_id || "";
            document.getElementById("ingresoTipo").value = item.tipo;
            document.getElementById("ingresoFactura").value =
                item.numero_factura || "";
            document.getElementById("ingresoNotas").value = item.notas || "";
        } else {
            document.getElementById("gastoCategoria").value = item.categoria;
            document.getElementById("gastoRecurrente").checked =
                !!item.recurrente;
            toggleRecurrente();
            document.getElementById("gastoNotas").value = item.notas || "";
        }

        document.getElementById(tipo + "Errores").style.display = "none";
        document.getElementById(
            tipo === "ingreso" ? "modalIngreso" : "modalGasto",
        ).style.display = "flex";
    }

    function toggleRecurrente() {
        const checked = document.getElementById("gastoRecurrente").checked;
        const fechaGroup = document.getElementById("gastoFechaGroup");
        const fechaInput = document.getElementById("gastoFecha");
        if (checked) {
            fechaGroup.style.display = "none";
            fechaInput.removeAttribute("required");
            fechaInput.value = "";
        } else {
            fechaGroup.style.display = "";
            fechaInput.setAttribute("required", "");
        }
    }

    async function guardar(tipo, e) {
        e.preventDefault();
        const errBox = document.getElementById(tipo + "Errores");
        errBox.style.display = "none";

        const id = document.getElementById(tipo + "Id").value;
        let url, method, datos;

        if (tipo === "ingreso") {
            datos = {
                concepto: document.getElementById("ingresoConcepto").value,
                cliente_id:
                    document.getElementById("ingresoClienteId").value || null,
                tipo: document.getElementById("ingresoTipo").value,
                importe: parseFloat(
                    document.getElementById("ingresoImporte").value,
                ),
                fecha: document.getElementById("ingresoFecha").value,
                numero_factura:
                    document.getElementById("ingresoFactura").value || null,
                notas: document.getElementById("ingresoNotas").value || null,
            };
            url = id ? `${BASE}/ingresos/${id}` : `${BASE}/ingresos`;
            method = id ? "PUT" : "POST";
        } else {
            const esRecurrente =
                document.getElementById("gastoRecurrente").checked;
            datos = {
                concepto: document.getElementById("gastoConcepto").value,
                categoria: document.getElementById("gastoCategoria").value,
                importe: parseFloat(
                    document.getElementById("gastoImporte").value,
                ),
                recurrente: esRecurrente,
                notas: document.getElementById("gastoNotas").value || null,
            };
            if (!esRecurrente || id) {
                datos.fecha = document.getElementById("gastoFecha").value;
            }
            url = id ? `${BASE}/gastos/${id}` : `${BASE}/gastos`;
            method = id ? "PUT" : "POST";
        }

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

            if (tipo === "ingreso") {
                if (id) {
                    const idx = datosLocales.ingresos.findIndex(
                        (x) => x.id === saved.id,
                    );
                    if (idx !== -1) datosLocales.ingresos[idx] = saved;
                } else {
                    datosLocales.ingresos.unshift(saved);
                }
                renderIngresos();
            } else {
                if (id) {
                    const idx = datosLocales.gastos.findIndex(
                        (x) => x.id === saved.id,
                    );
                    if (idx !== -1) datosLocales.gastos[idx] = saved;
                } else if (Array.isArray(saved)) {
                    saved.forEach((g) => datosLocales.gastos.unshift(g));
                } else {
                    datosLocales.gastos.unshift(saved);
                }
                renderGastos();
            }

            actualizarResumen();
            cerrarModal(tipo);
        } catch (err) {
            errBox.textContent = "Error de conexión.";
            errBox.style.display = "block";
        }
        return false;
    }

    async function eliminar(tipo, id) {
        const label = tipo === "ingreso" ? "este ingreso" : "este gasto";
        if (!confirm(`¿Eliminar ${label}?`)) return;

        const url =
            tipo === "ingreso"
                ? `${BASE}/ingresos/${id}`
                : `${BASE}/gastos/${id}`;

        try {
            const res = await fetch(url, {
                method: "DELETE",
                headers: headers("DELETE"),
            });
            if (!res.ok) {
                alert("Error al eliminar.");
                return;
            }

            if (tipo === "ingreso") {
                datosLocales.ingresos = datosLocales.ingresos.filter(
                    (x) => x.id !== id,
                );
                renderIngresos();
            } else {
                datosLocales.gastos = datosLocales.gastos.filter(
                    (x) => x.id !== id,
                );
                renderGastos();
            }
            actualizarResumen();
        } catch (err) {
            alert("Error de conexión.");
        }
    }

    function cambiarAnio(anio) {
        window.location.href = `${BASE}?anio=${anio}`;
    }

    // ── Init ───────────────────────────────────────────
    document.addEventListener("DOMContentLoaded", () => {
        datosLocales.gastos = FACTURACION_DATA.gastos || [];
        datosLocales.ingresos = FACTURACION_DATA.ingresos || [];

        (FACTURACION_DATA.usuarios || []).forEach((u) => {
            usuariosMap[u.id] = [u.nombre, u.apellido]
                .filter(Boolean)
                .join(" ");
        });

        initChart(FACTURACION_DATA.mensual || []);

        // Cerrar modales con Escape
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") {
                cerrarModal("ingreso");
                cerrarModal("gasto");
            }
        });

        // Cerrar al clicar fuera
        document.querySelectorAll(".modal-overlay").forEach((overlay) => {
            overlay.addEventListener("click", (e) => {
                if (e.target === overlay) {
                    cerrarModal("ingreso");
                    cerrarModal("gasto");
                }
            });
        });
    });

    return {
        abrirModal,
        cerrarModal,
        editar,
        guardar,
        eliminar,
        cambiarAnio,
        toggleRecurrente,
    };
})();
