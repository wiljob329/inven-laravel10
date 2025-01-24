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
                NOTA DE RECEPCION DE MATERIAL DEL DIV. DE CONTROL Y GESTIÓN DE ALMACEN
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
            <td colspan="7" style="width:50%; font-width: bold">
                DEPÓSITO: {{ $record->articulos[0]->material->deposito->name }}
            </td>
            <td colspan="3" style="width:30%;" class="wobl">FECHA: {{ date('d-m-Y', strtotime($record->fecha)) }}
            </td>
            {{--
                <td colspan="2" class="wobl">CASO:
                    @if ($record->caso == '1x10')
                      {{ $record->caso }} {{ $record->codigo_uxd }}
                    @else
                        {{ $record->caso }}
                    @endif
                </td>
            --}}
        </tr>
        <tr>
            <td colspan="7" class="wobt">
                @if ($record->proveedor)
                    ENTREGADO POR PROVEEDOR: {{ $record->proveedor->name }}
                @else
                    ENTREGADO POR CUADRILLA: {{ $record->cuadrilla->nombre }}
                @endif
            </td>
            <td colspan="3" class="wobl wobt">SEGUN COMUNICADO: {{ $record->codigo_nota_entrega }} </td>
        </tr>
        <tr>
            {{--
               <td colspan="5" class="wobt">DESTINO: {{ $record->destino }} </td>
            <td colspan="3" class="wobt wobl">VEHICULO: {{ $record->vehiculo->tipo }} </td>
            <td colspan="2" class="wobt wobl">PLACA: {{ $record->vehiculo->placa }} </td>
           --}}
            <td colspan="10" class="wobt">RECIBIDO POR: {{ auth()->user()->name }} </td>
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
            <td rowspan="2" class="wobl tac">CANTIDAD RECIBIDA</td>
            <td colspan="2" class="wobl tac" style="width:10%">EL MATERIAL ES UN ACTIVO?</td>
            <td colspan="3" class="wobl tac" style="width:10%">CONDICIÓN DEL MATERIAL</td>
        </tr>
        <tr>
            <td class="wobt wobl tac">SI</td>
            <td class="wobt wobl tac">NO</td>
            <!-- <td class="wobt wobl tac" style="width:10%">DESPACHADO</tdclass> -->
            <td class="wobt wobl tac" colspan="3">RECIBIDO</td>
        </tr>

        @foreach ($record->articulos as $item)
            <tr style="height: 50px">
                <td class="tac wobt">{{ $item->id }}</td>
                <td class="tac wobt wobl">{{ $item->material->id }}</td>
                <td class="tac wobt wobl">{{ $item->material->descripcion }}</td>
                <td class="tac wobt wobl">{{ $item->material->unidad_medidas->unidad }}</td>
                <td class="tac wobt wobl">{{ $item->cantidad }}</td>
                @if ($item->material->activo)
                    <td class="tac wobt wobl">X</td>
                    <td class="tac wobt wobl"></td>
                    <td class="tac wobt wobl" colspan="3"></td>
                @else
                    <td class="tac wobt wobl"></td>
                    <td class="tac wobt wobl">X</td>
                    <td class="tac wobt wobl" colspan="3"></td>
                @endif
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
            <th colspan="4">
                RECIBE
            </th>
            <th colspan="6" class="wobl">
                ENTREGA
            </th>
        </tr>
        <tr>
            <td colspan="4" class="lineh wobt">Firma:</td>
            <td colspan="6" class="lineh wobt wobl">Firma:</td>
        </tr>
        <tr>
            <td colspan="4" class="wobt">
                NOMBRE: <span style="font-width: bold">{{ auth()->user()->name }}</span><br />
                CARGO: <span style="font-width: bold">{{ auth()->user()->cargo }}</span><br />
                C.I: <span style="font-width: bold">{{ auth()->user()->cedula }}</span>
            </td>
            <td colspan="6" class="wobt wobl">
                @if ($record->proveedor)
                    PROVEEDOR: <span style="font-width: bold">{{ $record->proveedor->name }}</span><br />
                    RIF: <span style="font-width: bold">{{ $record->proveedor->rif }}</span>
                @else
                    CUADRILLA: <span style="font-width: bold">{{ $record->cuadrilla->nombre }}</span><br />
                @endif
            </td>
        </tr>

    </table>
</body>

</html>
