<x-layout>
    <h1 class="pt-4 pb-3">Статистика по заказам</h1>
    <p class="pb-5">Период : {{$from}} - {{$to}}</p>

    <p class="pb-3">Всего заказов: <b>{{$total}}</b> <br>
        Из них завершено: <b>{{$complete}}</b>
    </p>

    <div class="pb-5">
        <table class="table table-bordered table-hover">
            <tr>
                <th width="30%">Источник заказа</th>
                <th>Кол-во заказов</th>
            </tr>
            @foreach ($sourceMap as $item)
                <tr>
                    <td>{{$item->basket_source}}</td>
                    <td>{{$item->count}}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <div style="margin-bottom: 30px">

        <table class="table table-bordered table-hover">
            <tr>
                <th width="30%">Телефон</th>
                <th>Кол-во заказов</th>
            </tr>
            @foreach ($phonesMap as $item)
                <tr>
                    <td>{{$item->call_phone}}</td>
                    <td>{{$item->count}}</td>
                </tr>
            @endforeach
        </table>
    </div>
</x-layout>
