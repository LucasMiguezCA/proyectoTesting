{{-- filepath: resources/views/filament/resources/tareas-resource/widgets/calendario-tareas.blade.php --}}


<div class="mb-4">
    <h3 class="text-lg font-bold mb-2 text-gray-200">Calendario semanal de tareas</h3>
    <table class="w-full table-fixed rounded-lg overflow-hidden shadow border border-gray-700">
        <thead class="bg-gray-800">
            <tr>
                @foreach(['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'] as $dia)
                    <th class="min-w-[140px] px-4 py-3 text-center text-xs font-semibold text-gray-200 uppercase tracking-wider
                        @if(isset($hoy) && Str::ascii($hoy) === Str::ascii($dia))
                            bg-yellow-600 text-gray-900
                        @endif
                    ">
                        {{ $dia }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-gray-900">
            <tr>
                @foreach(['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'] as $dia)
                    <td class="min-w-[140px] px-4 py-6 align-top text-sm text-gray-100 text-center border-t border-gray-700
                        @if(isset($hoy) && Str::ascii($hoy) === Str::ascii($dia))
                            bg-yellow-600 text-gray-900
                        @endif
                        @php
                            $tareas = $tareasPorDiaOrdenado[$dia] ?? collect();
                        @endphp
                        @if($tareas->count())
                            <ul class="space-y-2">
                                @foreach($tareas as $tarea)
                                    <li class="flex flex-col items-center gap-1">
                                        <span class="truncate font-medium">{{ $tarea->nombre }}</span>
                                        @if($tarea->completada)
                                            <span class="inline-block px-2 py-0.5 rounded bg-green-700 text-green-100 text-xs">Completada</span>
                                        @endif
                                        <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($tarea->fecha_vencimiento)->format('d/m/Y') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-gray-500 text-xs">Sin tareas</span>
                        @endif
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>