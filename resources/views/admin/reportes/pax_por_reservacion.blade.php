<table>
    <thead>
      <tr> <td></td> </tr>
      <tr> <td></td> </tr>
      <tr> <td></td> </tr>
      <tr> <td></td> </tr>
      <tr>
        <td colspan="8" style="font-size:18px;font-weight:bold;height:35px;">
          Reporte de reservaciones {{$fecha}} ({{$data['mesas_disponibles']}} mesas disponibles)
        </td>

      </tr>
    </thead>

    <tbody>
      @php @$total_invitados = 0; @endphp
      @php @$total_pagados   = 0; @endphp
      @php @$total_por_pagar = 0; @endphp
      @php @$total_mesas     = 0; @endphp
      @php @$total_mujeres   = 0; @endphp
      @php @$total_hombres   = 0; @endphp
      @php @$total_celebra   = 0; @endphp

      @for ($id_area = 1; $id_area <= 2; $id_area++)
        @php @$sub_total_invitados = 0; @endphp
        @php @$sub_total_pagados   = 0; @endphp
        @php @$sub_total_por_pagar = 0; @endphp
        @php @$sub_total_mesas     = 0; @endphp
        @php @$sub_total_mujeres   = 0; @endphp
        @php @$sub_total_hombres   = 0; @endphp
        @php @$sub_total_celebra   = 0; @endphp
        @if (isset($data[$id_area]))
          <tr>
            <td colspan="5" style="text-align:left; font-weight:bold; font-size: 25px;">{{$id_area == 1 ? 'MESAS' : 'BARRAS'}}</td> 
          </tr>
          <tr>
            <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;width:25px;">No</th>
            <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;width:275px;" >Reservación</th>
            <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;">En Lista</th>

            @if ($data[$id_area][0]->de_pago)
              <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;">Pagados</th>
              <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;">Por pagar</th>
            @endif

            <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;">Mujeres</th>
            <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;">Hombres</th>

            @if ($id_area == 1)
              <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;">Mesas</th>
            @endif

            <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;">Celebración</th>
          </tr>

          @php @$no_lider = 1; @endphp
          @foreach($data[$id_area] as $key => $item)
            <tr>
              <th style="text-align:center;border:1px solid black;">{{$no_lider}}</th>

              <th style="text-align:center;border:1px solid black;">{{$item->lider}}</th>

              @php @$sub_total_invitados += $item->invitados; @endphp
              <th style="text-align:center;border:1px solid black;">{{$item->invitados ?: '-'}}</th>

              @if ($item->de_pago)
                @php @$sub_total_pagados += $item->pagados; @endphp
                <th style="text-align:center;border:1px solid black;">{{$item->pagados ?: '-'}}</th>

                @php @$por_pagar = $item->invitados - $item->pagados @endphp
                @php @$sub_total_por_pagar += $por_pagar; @endphp
                <th style="text-align:center;border:1px solid black;">{{$por_pagar ?: '-'}}</th>
              @endif
              
              @php @$sub_total_mujeres += $item->mujeres; @endphp
              <th style="text-align:center;border:1px solid black;">{{$item->mujeres ?: '-'}}</th>
              
              @php @$sub_total_hombres += $item->hombres; @endphp
              <th style="text-align:center;border:1px solid black;">{{$item->hombres ?: '-'}}</th>
              
              @if ($id_area == 1)
                @php @$sub_total_mesas += $item->tot_mesas; @endphp
                <th style="text-align:center;border:1px solid black;">{{$item->tot_mesas ?: '-'}}</th>
              @endif

              @php @$sub_total_celebra += $item->celebracion ? 1 : 0; @endphp
              <th style="text-align:center;border:1px solid black;">{{$item->celebracion ?: 'Ninguna'}}</th>
            </tr>

            @php $no_lider++; @endphp
          @endforeach
          <tr>
            <th colspan="2" style="text-align:right;border:1px solid black;font-weight:bold;background: #dfdfdf;">Subtotales:</th>
            <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;">{{$sub_total_invitados}}</th>

            @if ($data[$id_area][0]->de_pago)
              <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;">{{$sub_total_pagados}}</th>
              <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;">{{$sub_total_por_pagar}}</th>
            @endif 

            <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;">{{$sub_total_mujeres}}</th>
            <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;">{{$sub_total_hombres}}</th>

            @if ($id_area == 1)
              <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;">{{$sub_total_mesas}}</th>
            @endif

            <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;">{{$sub_total_celebra ?: '-'}}</th>
          </tr>

          @if (@$data[2])
            <tr> <td></td> </tr>
            <tr> <td></td> </tr>
          @endif
        @endif

        @php @$total_invitados += $sub_total_invitados; @endphp
        @php @$total_pagados   += $sub_total_pagados; @endphp
        @php @$total_por_pagar += $sub_total_por_pagar; @endphp
        @php @$total_mesas     += $sub_total_mesas; @endphp
        @php @$total_mujeres   += $sub_total_mujeres; @endphp
        @php @$total_hombres   += $sub_total_hombres; @endphp
        @php @$total_celebra   += $sub_total_celebra; @endphp
      @endfor

      <tr> <td></td> </tr>
      <tr> <td></td> </tr>
      <tr> <td></td> </tr>
      <tr> <td></td> </tr>
      <tr>
        <td colspan="5" style="text-align:left; font-weight:bold; font-size: 25px;">RESUMEN</td> 
      </tr>
      <tr>
        <th colspan="2" style="text-align:right;font-weight:bold;width: 25px"></th>
        <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;">En Lista</th>

        @if ($data[1][0]->de_pago)
          <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;">Pagados</th>
          <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;">Por pagar</th>
        @endif

        <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;">Mujeres</th>
        <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;">Hombres</th>
        <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;">Mesas</th>
        <th style="text-align:center;border:1px solid black;font-weight:bold;background: #dfdfdf;">Celebración</th>
      </tr>

      <tr>
        <th colspan="2" style="text-align:right;border:1px solid black;font-weight:bold;background: #dfdfdf;">Totales:</th>
        <th style="text-align:center;border:1px solid black;font-weight:bold;">{{$total_invitados}}</th>

        @if ($data[1][0]->de_pago)
          <th style="text-align:center;border:1px solid black;font-weight:bold;">{{$total_pagados}}</th>
          <th style="text-align:center;border:1px solid black;font-weight:bold;">{{$total_por_pagar}}</th>
        @endif 

        <th style="text-align:center;border:1px solid black;font-weight:bold;">{{$total_mujeres}}</th>
        <th style="text-align:center;border:1px solid black;font-weight:bold;">{{$total_hombres}}</th>
        <th style="text-align:center;border:1px solid black;font-weight:bold;">{{$total_mesas}}</th>
        <th style="text-align:center;border:1px solid black;font-weight:bold;">{{$total_celebra ?: '-'}}</th>
      </tr>
    </tbody>
</table>
