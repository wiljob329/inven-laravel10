<!DOCTYPE html>
<html>

<head>
    <title>{{ $titulo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: .6rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .imgHeader img {
            display: block;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="imgHeader">
        <img src="images/agm.png" width="150" style="margin-right:40%;margin-left:20%" />
        <img src="images/LOGO MINAGUAS.jpg" width="110" />
    </div>
    <table>
        <thead>
            <tr>
                <th colspan=8 style="text-align:center"> INVENTARIO DEPOSITO LIBERTADOR</th>
            </tr>
            <tr>
                <!-- Personaliza los encabezados según tus columnas -->
                <th>Codigo</th>
                <th>Descripcion</th>
                <th>Cantidad</th>
                <th>Unidad de Medida</th>
                <th>Categoria</th>
                <th>Deposito</th>
                <th>Activo</th>
                <th>Fecha Ingreso</th>
                <!-- Agrega más columnas según necesites -->
            </tr>
        </thead>
        <tbody>
            @foreach ($registros as $registro)
                <tr>
                    <!-- Personaliza las celdas según tus columnas -->
                    <td>{{ $registro->id }}</td>
                    <td>{{ $registro->descripcion }}</td>
                    <td>{{ $registro->cantidad }}</td>
                    <td>{{ $registro->unidad_medidas->unidad }}</td>
                    <td>{{ $registro->categoria->name }}</td>
                    <td>{{ $registro->deposito->name }}</td>
                    <td>
                        @if ($registro->activo)
                            SI
                        @else
                            NO
                        @endif
                    </td>
                    <td>{{ $registro->created_at->format('d/m/Y') }}</td>
                    <!-- Agrega más columnas según necesites -->
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
