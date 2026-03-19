<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo mensaje de contacto</title>
</head>
<body style="margin:0;padding:0;background:#0f0f1a;font-family:'Segoe UI',system-ui,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:2rem 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                       style="background:#1a1a2e;border-radius:10px;overflow:hidden;border:1px solid #2a2a40;">
                    {{-- Header --}}
                    <tr>
                        <td style="padding:1.5rem 2rem;background:#22223a;border-bottom:1px solid #2a2a40;">
                            <span style="font-size:1.2rem;font-weight:700;color:#e8e8f0;">
                                Dalo<span style="color:#6c5ce7;">Web</span>
                            </span>
                            <span style="float:right;font-size:.85rem;color:#a0a0b8;">Formulario de contacto</span>
                        </td>
                    </tr>

                    {{-- Cuerpo --}}
                    <tr>
                        <td style="padding:2rem;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding:.5rem 0;color:#a0a0b8;font-size:.85rem;width:100px;">Nombre</td>
                                    <td style="padding:.5rem 0;color:#e8e8f0;">{{ $nombre }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:.5rem 0;color:#a0a0b8;font-size:.85rem;">Email</td>
                                    <td style="padding:.5rem 0;color:#e8e8f0;">{{ $email }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:.5rem 0;color:#a0a0b8;font-size:.85rem;">Tipo</td>
                                    <td style="padding:.5rem 0;color:#e8e8f0;">{{ $tipo }}</td>
                                </tr>
                            </table>

                            <hr style="border:none;border-top:1px solid #2a2a40;margin:1.25rem 0;">

                            <p style="color:#a0a0b8;font-size:.85rem;margin-bottom:.5rem;">Mensaje</p>
                            <p style="color:#e8e8f0;line-height:1.6;white-space:pre-wrap;">{{ $mensaje }}</p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:1rem 2rem;text-align:center;font-size:.75rem;color:#666;border-top:1px solid #2a2a40;">
                            &copy; {{ date('Y') }} DaloWeb &mdash; Enviado desde el formulario de contacto
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
