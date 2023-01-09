<x-layout>
    <h1 class="pt-4 pb-3">Статистика по курьерам</h1>
    <p class="pb-5">Период : {{$from}} - {{$to}}</p>

    <div class="pb-5">
        <table class="table table-bordered table-hover">
            <tr>
                <th width="30%">Курьер</th>
                <th>Кол-во заказов</th>
            </tr>
            @foreach ($couriersMap as $item)
                <tr>
                    <td>{{$item->name}}</td>
                    <td>{{$item->completeOrdersCount}}</td>
                </tr>
            @endforeach
        </table>
    </div>
</x-layout>
