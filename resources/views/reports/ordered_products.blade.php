<x-layout>
    <h1 class="pt-4 pb-3">Статистика по проданным блюдам</h1>
    <p class="pb-5">Период : {{$from}} - {{$to}}</p>
    <h4 class="pb-5">Всего заказов : {{$ordersCount}}</h4>


    <div class="pb-5">
        <table class="table table-bordered table-hover">
            <tr>
                <th width="10%" class="text-center">#</th>
                <th width="10%" class="text-center">ID</th>
                <th width="20" class="text-center">Номер заказа</th>
                <th width="10%" class="text-center">Филиал</th>
                <th>Блюда</th>
            </tr>
            @foreach ($ordersMap as $key => $order)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$order['id']}}</td>
                    <td>{{$order['code']}}</td>
                    <td>{{$order['filial']}}</td>
                    <td class="products-td">
                        <table class="table table-bordered table-striped">
                            @foreach ($order['products'] as $product)
                                <tr>
                                    <td>{{$product['name']}}
                                        @if ($product['modification'])
                                            <i>({{$product['modification']}})</i>
                                        @endif
                                    </td>
                                    <td  width="10%" class="text-center">
                                      {{$product['quantity']}}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    <style>
        td.products-td {
            padding: 0;
        }

        td.products-td table.table {
            margin: 0;
        }
        td.products-td i {
            font-size: 12px;
            vertical-align: baseline;
        }
    </style>
</x-layout>

