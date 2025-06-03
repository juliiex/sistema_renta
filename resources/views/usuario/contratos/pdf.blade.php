<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato de Arrendamiento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4a5568;
            padding-bottom: 10px;
        }
        .title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            color: #4a5568;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }
        .info-row {
            margin-bottom: 5px;
        }
        .label {
            font-weight: bold;
            margin-right: 5px;
        }
        .value {
            display: inline-block;
        }
        .terms {
            margin-top: 30px;
            text-align: justify;
        }
        .signature {
            margin-top: 50px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
        }
        .signature-image {
            max-height: 80px;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #000;
            width: 200px;
            display: inline-block;
        }
        .signature-name {
            font-weight: bold;
            margin-top: 5px;
        }
        .footer {
            margin-top: 50px;
            font-size: 10px;
            text-align: center;
            color: #718096;
        }
        .admin-signature {
            font-family: 'Times New Roman', Times, serif;
            font-style: italic;
            font-size: 24px;
            color: #000;
            margin-top: 15px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">CONTRATO DE ARRENDAMIENTO</div>
        <div class="subtitle">N° {{ $contrato->id }}</div>
    </div>

    <div class="section">
        <div class="section-title">1. PARTES CONTRATANTES</div>
        <div class="info-row">
            <span class="label">ARRENDADOR:</span>
            <span class="value">{{ $contrato->apartamento->edificio->propietario ?? 'Administración del Edificio' }}, en calidad de propietario o representante legal.</span>
        </div>
        <div class="info-row">
            <span class="label">ARRENDATARIO:</span>
            <span class="value">{{ $contrato->usuario->nombre }}, identificado con documento de identidad {{ $contrato->usuario->documento_identidad ?? 'No especificado' }}.</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">2. OBJETO DEL CONTRATO</div>
        <p>
            El ARRENDADOR entrega en arrendamiento al ARRENDATARIO, y este recibe al mismo título, el uso y goce del inmueble que se describe a continuación:
        </p>
        <div class="info-row">
            <span class="label">INMUEBLE:</span>
            <span class="value">Apartamento {{ $contrato->apartamento->numero_apartamento }}, Edificio {{ $contrato->apartamento->edificio->nombre }}.</span>
        </div>
        <div class="info-row">
            <span class="label">DIRECCIÓN:</span>
            <span class="value">{{ $contrato->apartamento->edificio->direccion ?? 'No especificada' }}</span>
        </div>
        <div class="info-row">
            <span class="label">CARACTERÍSTICAS:</span>
            <span class="value">Área de {{ $contrato->apartamento->tamaño }} m²</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">3. DURACIÓN Y PRÓRROGA</div>
        <div class="info-row">
            <span class="label">DURACIÓN:</span>
            <span class="value">{{ $contrato->fecha_inicio->diffInMonths($contrato->fecha_fin) }} meses.</span>
        </div>
        <div class="info-row">
            <span class="label">FECHA DE INICIO:</span>
            <span class="value">{{ $contrato->fecha_inicio->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="label">FECHA DE FINALIZACIÓN:</span>
            <span class="value">{{ $contrato->fecha_fin->format('d/m/Y') }}</span>
        </div>
        <p>
            El contrato se prorrogará automáticamente por periodos iguales al inicial, salvo que alguna de las partes manifieste su intención de no renovarlo con una antelación no inferior a treinta (30) días calendario a la fecha de terminación.
        </p>
    </div>

    <div class="section">
        <div class="section-title">4. CANON DE ARRENDAMIENTO Y FORMA DE PAGO</div>
        <div class="info-row">
            <span class="label">CANON MENSUAL:</span>
            <span class="value">${{ number_format($contrato->apartamento->precio, 0, ',', '.') }} pesos colombianos.</span>
        </div>
        <div class="info-row">
            <span class="label">FORMA DE PAGO:</span>
            <span class="value">El canon mensual se pagará por mes anticipado dentro de los primeros cinco (5) días calendario de cada mes.</span>
        </div>
    </div>

    <div class="terms">
        <div class="section-title">5. TÉRMINOS Y CONDICIONES</div>
        <ol>
            <li>El inmueble será destinado exclusivamente para vivienda del ARRENDATARIO y su familia, y no podrá cambiar su destinación sin el consentimiento previo y por escrito del ARRENDADOR.</li>
            <li>El ARRENDATARIO se obliga a cuidar el inmueble y a mantenerlo en el mismo estado en que lo recibió, salvo el deterioro natural por el uso y el paso del tiempo.</li>
            <li>El ARRENDATARIO no podrá ceder este contrato ni subarrendar total o parcialmente el inmueble objeto de este contrato.</li>
            <li>El ARRENDATARIO se obliga a pagar oportunamente los servicios públicos de agua, energía, gas, teléfono e internet que se causen durante la vigencia del contrato.</li>
            <li>El ARRENDATARIO se obliga a permitir al ARRENDADOR o a quien este designe, el ingreso al inmueble para verificar su estado de conservación, previa comunicación con tres (3) días de antelación.</li>
            <li>El ARRENDATARIO no podrá realizar mejoras, cambios o reformas al inmueble sin el consentimiento previo y por escrito del ARRENDADOR.</li>
            <li>El ARRENDADOR no responderá por el robo, hurto o daño de los bienes del ARRENDATARIO, causados por terceros, caso fortuito o fuerza mayor.</li>
        </ol>
    </div>

    <div class="signature">
        <div class="section-title">6. FIRMAS</div>
        <p>
            En constancia de lo anterior, las partes firman este contrato el día {{ \Carbon\Carbon::now()->format('d') }} del mes de {{ \Carbon\Carbon::now()->locale('es')->monthName }} de {{ \Carbon\Carbon::now()->format('Y') }}.
        </p>

        <table width="100%">
            <tr>
                <td width="50%" align="center">
                    <!-- FIRMA ARRENDADOR - IMAGEN CODIFICADA EN BASE64 -->
                    <img width="150" height="60" src="C:\Sistema_Renta\storage\app\public\firmas\firma_admin.png.png">
                    <div class="signature-name">EL ARRENDADOR</div>
                </td>
                <td width="50%" align="center">
                    @if($contrato->firma_imagen)
                        <img class="signature-image" src="{{ public_path('storage/' . $contrato->firma_imagen) }}" alt="Firma del Arrendatario">
                    @else
                        <div class="signature-line"></div>
                    @endif
                    <div class="signature-name">EL ARRENDATARIO<br>{{ $contrato->usuario->nombre }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Este documento es generado automáticamente y forma parte del sistema de gestión de arrendamiento.</p>
        <p>Fecha de emisión: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
