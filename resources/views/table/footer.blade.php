@php
$hasBulkAction = [false];
@endphp
@foreach($this->getCachedTableBulkActions() as $index => $action)

    @php $hasBulkAction[] = $action->isAuthorized(); @endphp
@endforeach

<x-tables::row>

    @if( in_array(true, $hasBulkAction) )
            <x-tables::cell>

            </x-tables::cell>
    @endif



    @foreach ($columns as $column)
        <x-tables::cell
            wire:loading.remove.delay
            wire:target="{{ implode(',', \Filament\Tables\Table::LOADING_TARGETS) }}"
        >
            @for ($i = 0; $i < sizeof($calc_columns); $i++ )


                @if ($column->getName() === $calc_columns[$i])

                    <div class="filament-tables-column-wrapper px-2">
                        <div class="filament-tables-text-column px-4 py-2 flex w-full justify-start text-start">
                            <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                                <span class="font-medium">
                                    {{ money($records->sum($calc_columns[$i]), convert:  true) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            @endfor
        </x-tables::cell>
    @endforeach
</x-tables::row>
