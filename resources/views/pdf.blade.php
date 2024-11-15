<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-size: .7em;
            font-family: sans-serif;
        }

        .fontW {
            font-weight: bold;
        }

        .fontSize {
            font-size: .8em;
        }

        .lineh {
            line-height: 4.5em;
        }

        .wobt {
            border-top: 0px;
        }

        .wobr {
            border-right: 0px;
        }

        .wobl {
            border-left: 0px;
        }

        .wobb {
            border-bottom: 0px;
        }

        .tac {
            text-align: center;
        }

        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }


        table {
            border-spacing: 0px;
        }

        .space td {
            border: 0px;
        }

        .imgHeader {
            margin-left: 20%;
            margin-top: 0px;
            padding: 0px;
        }

        .imgHeader img {
            display: block;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="imgHeader">
        <img src="images/agm.png" width="150" style="margin-right:40%" />
        <img src="images/LOGO MINAGUAS.jpg" width="110" />
    </div>
    <table style="width:100%">
        <tr>
            <th colspan="10">
                NOTA DE ENTREGA DE MATERIAL DEL DIV. DE CONTROL Y GESTIÓN DE ALMACEN
            </th>
        </tr>
        <tr class="space" style="height: 10px">
            <td colspan="10"></td>
        </tr>
        <tr class="space" style="height: 10px">
            <td colspan="10"></td>
        </tr>
        <tr class="space" style="height: 10px">
            <td colspan="10"></td>
        </tr>
        <tr>
            <td colspan="5" style="width:50%; font-width: bold">
                DEPÓSITO: <span style="color:red">Aqui variable deposito</span>
            </td>
            <td colspan="3" style="width:30%;" class="wobl">FECHA: {{ date('d-m-Y', strtotime($record->fecha)) }}
            </td>
            <td colspan="2" class="wobl">No</td>
        </tr>
        <tr>
            <td colspan="3" class="wobt">SOLICITANTE: {{ $record->entregado_a }}</td>
            <td colspan="2" class="wobt wobl">CI: {{ $record->cedula }}</td>
            <td colspan="5" class="wobl wobt"><span>SEGÚN COMUNICACIÓN:</span>S/N</td>
        </tr>
        <tr>
            <td colspan="10" class="wobt">PARA SER USADO EN:</td>
        </tr>
        <tr class="space" style="height: 50px">
            <td colspan="10"></td>
        </tr>
        <tr class="space" style="height: 10px">
            <td colspan="10"></td>
        </tr>
        <tr class="space" style="height: 10px">
            <td colspan="10"></td>
        </tr>
        <tr class="space" style="height: 10px">
            <td colspan="10"></td>
        </tr>
        <tr class="space" style="height: 10px">
            <td colspan="10"></td>
        </tr>
        <tr class="space" style="height: 10px">
            <td colspan="10"></td>
        </tr>
        <tr>
            <td rowspan="2" class="tac" style="width:4%">No</td>
            <td rowspan="2" class="wobl tac" style="width:7%; padding:4px;">CÓDIGO DEL MATERIAL</td>
            <td rowspan="2" class="wobl tac" style="width:25%">DESCRIPCIÓN</td>
            <td rowspan="2" class="wobl tac">UNIDAD DE MEDIDA</td>
            <td rowspan="2" class="wobl tac">CANTIDAD DESPACHADA</td>
            <td colspan="2" class="wobl tac" style="width:10%">EL MATERIAL ES UN ACTIVO?</td>
            <td rowspan="2" class="wobl tac">DESTINO DEL MATERIAL DESPACHADO</td>
            <td colspan="2" class="wobl tac">CONDICIÓN DEL MATERIAL</td>
        </tr>
        <tr>
            <td class="wobt wobl tac" style="width:5%">SI</td>
            <td class="wobt wobl tac">NO</td>
            <td class="wobt wobl tac" style="width:10%">DESPACHADO</tdclass>
            <td class="wobt wobl tac">PRESTADO</td>
        </tr>

        @foreach ($record->articulos as $item)
            <tr style="height: 50px">
                <td class="tac wobt">{{ $item->id }}</td>
                <td class="tac wobt wobl">{{ $item->material->id }}</td>
                <td class="tac wobt wobl">{{ $item->material->descripcion }}</td>
                <td class="tac wobt wobl"></td>
                <td class="tac wobt wobl">{{ $item->cantidad }}</td>
                <td class="tac wobt wobl"></td>
                <td class="tac wobt wobl"></td>
                <td class="tac wobt wobl"></td>
                <td class="tac wobt wobl"></td>
                <td class="tac wobt wobl"></td>
            </tr>
        @endforeach

        <tr class="space" style="height: 10px">
            <td colspan="10"></td>
        </tr>
        <tr class="space" style="height: 10px">
            <td colspan="10"></td>
        </tr>
        <tr class="space" style="height: 10px">
            <td colspan="10"></td>
        </tr>
        <tr class="space">
            <td colspan="10" class="tac fontSize"><span class="fontW">Nota:</span> EL RECEPTOR DE ESTE MATERIAL
                ASUME
                LA
                RESPONSABILIDAD Y
                CUSTODIA DE
                AQUELLOS BIENES QUE TENGAN CONDICIÓN DE ACTIVO FIJO HASTA QUE SE LE ASIGNE UN CUSTODIO DEFINITIVO AL
                MISMO.</td>
        </tr>
        <tr class="space" style="height: 10px">
            <td colspan="10"></td>
        </tr>
        <tr class="space" style="height: 10px">
            <td colspan="10"></td>
        </tr>
        <tr class="space" style="height: 10px">
            <td colspan="10"></td>
        </tr>
        <tr class="space" style="height: 10px">
            <td colspan="10"></td>
        </tr>
        <tr class="space" style="height: 10px">
            <td colspan="10"></td>
        </tr>
        <tr class="space" style="height: 10px">
            <td colspan="10"></td>
        </tr>
        <tr class="space" style="height: 10px">
            <td colspan="10"></td>
        </tr>
        <tr class="space" style="height: 10px">
            <td colspan="10"></td>
        </tr>
        <tr>
            <th colspan="3" style="width:30%">
                ENCARGADO DEL DEPÓSITO
            </th>
            <th colspan="4" style="width:33%" class="wobl">
                APROBADO POR
            </th>
            <th colspan="3" style="width:34%" class="wobl">
                RECIBIDO POR
            </th>
        </tr>
        <tr>
            <td colspan="3" class="lineh wobt">Firma:</td>
            <td colspan="4" class="lineh wobt wobl">Firma:</td>
            <td colspan="3" class="lineh wobt wobl">Firma:</td>
        </tr>
        <tr>
            <td colspan="3" class="wobt">Nombre:<br />Cargo:<br />C.I:</td>
            <td colspan="4" class="wobt wobl">Nombre:<br />Cargo:<br />C.I:</td>
            <td colspan="3" class="wobt wobl">Nombre:<br />Cargo:<br />C.I:</td>
        </tr>

    </table>
</body>

</html>
