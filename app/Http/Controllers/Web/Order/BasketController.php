<?php


namespace App\Http\Controllers\Web\Order;

use App\Enums\BasketSource;
use App\Enums\BasketStatus;
use App\Enums\DeliveryType;
use App\Enums\PaymentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Order\AddBasketItem;
use App\Http\Requests\Web\Order\AddItemsBasket;
use App\Http\Requests\Web\Order\ChangeModification;
use App\Http\Requests\Web\Order\ChangeQuantity;
use App\Http\Requests\Web\Order\DeleteBasketItem;
use App\Http\Requests\Web\Order\UpdateBasket;
use App\Http\Requests\Web\Order\UpdateBasketItem;
use App\Http\Requests\Web\Order\UpdateCallNotificaitonBasket;
use App\Http\Requests\Web\Order\UpdateCourierBasket;
use App\Http\Requests\Web\Order\UpdatePaymentTypeBasket;
use App\Http\Requests\Web\Order\UpdatePickUpBasket;
use App\Http\Requests\Web\Order\UpdatePromoCodeBasket;
use App\Http\Resources\Web\BasketResource;
use App\Http\Resources\Web\OrderResource;
use App\Models\Order\BasketItem;
use App\Models\Order\Order;
use App\Services\CRM\Order\BasketItemService;
use App\Services\CRM\Order\BasketService;
use App\Services\CRM\Order\OrderService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;


/**
 * Class BasketController
 * @package App\Http\Controllers\Web\Order
 */
class BasketController extends Controller
{
    private BasketService $service;


    /**
     * BasketController constructor.
     * @param BasketService $service
     * @param string $resource
     */
    public function __construct(BasketService $service, string $resource = BasketResource::class)
    {

        $this->resource = $resource;
        $this->service = $service;

    }

    /**
     * @throws AuthenticationException
     * @throws \App\Repositories\RepositoryException
     */
    public function basketInit(): void
    {
        $uuid = request()->input('uuid');

        try {
            $clientId = auth()->user()->id;

            try {

                try {

                    /**
                     * @var BasketService $basketService
                     */
                    $basketService = $this->service::findOne([
                        'clientId'     => $clientId,
                        'basketSource' => BasketSource::Web,
                        'status'       => BasketStatus::inProcess,
                    ]);
                } catch (\Throwable $e) {

                    $basketService = $this->service::findOne([
                        'uuid'         => $uuid,
                        'clientId'     => null,
                        'basketSource' => BasketSource::Web,
                        'status'       => BasketStatus::inProcess,
                    ]);

                    $basketService->edit(['clientId' => $clientId]);
                }

            } catch (\Throwable $e) {

                $basketService = $this->service::add([
                    'status'       => BasketStatus::inProcess,
                    'clientId'     => $clientId,
                    'basketSource' => BasketSource::Web,
                    'paymentType'  => PaymentType::Terminal,
                ]);
            }

        } catch (\Throwable $e) {


            if ($uuid) {
                try {

                    /**
                     * @var BasketService $basketService
                     */
                    $basketService = $this->service::findOne([
                        'uuid'         => $uuid,
                        'clientId'     => null,
                        'basketSource' => BasketSource::Web,
                        'status'       => BasketStatus::inProcess,
                    ]);

                } catch (\Throwable $e) {

                    $basketService = $this->service::add([
                        'status'       => BasketStatus::inProcess,
                        'uuid'         => $uuid,
                        'basketSource' => BasketSource::Web,
                        'paymentType'  => PaymentType::Terminal,
                    ]);
                }
            } else {
                throw new AuthenticationException();
            }

        }


        $this->service = $basketService;
    }

    /**
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws \App\Repositories\RepositoryException
     */
    public function refreshAndReturnBasket(): JsonResponse
    {
        if (count($this->service->getRepository()->getArray()) < 1) {
            $this->basketInit();
        }
        try {
            $basketModel = $this->service->refreshAndReturnBasket();
        } catch (\Exception $e) {
            return $this->responseError('Ошибка обновления и пересчёта корзины: ' . $e->getMessage());
        }

        return $this->respondWithItem($basketModel);
    }

    /**
     * @param UpdateBasket $request
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws \App\Repositories\RepositoryException
     */
    public function update(UpdateBasket $request): JsonResponse
    {

        $this->basketInit();

        $data = $request->all();

        try {
            $this->service->edit($data);
        } catch (\Throwable $e) {
            return $this->responseError($e->getMessage());
        }

        return $this->refreshAndReturnBasket();
    }


    /**
     * @param UpdateBasket $request
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws \App\Repositories\RepositoryException
     */
    public function changeRecipient(UpdateBasket $request): JsonResponse
    {
        $this->basketInit();

        $data = $request->all();

        try {
            $this->service->edit($data);
        } catch (\Throwable $e) {
            return $this->responseError($e->getMessage());
        }

        return $this->refreshAndReturnBasket();
    }


    /**
     * @param UpdatePickUpBasket $request
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws \App\Repositories\RepositoryException
     */
    public function changePickUp(UpdatePickUpBasket $request): JsonResponse
    {
        $this->basketInit();

        $data = $request->all();

        try {
            $data['deliveryType'] = DeliveryType::Self;
            $this->service->edit($data);
        } catch (\Throwable $e) {
            return $this->responseError($e->getMessage());
        }

        return $this->refreshAndReturnBasket();
    }

    /**
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws \App\Repositories\RepositoryException
     */
    public function createOrder(): JsonResponse
    {
        $this->basketInit();

        try {
            $orderModel = $this->service->createOrder();
        } catch (\Throwable $e) {
            return $this->responseError($e->getMessage());
        }

        $this->resource = OrderResource::class;

        return $this->respondWithItem($orderModel);
    }

    /**
     * @param UpdateCallNotificaitonBasket $request
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws \App\Repositories\RepositoryException
     */
    public function updateCallNotification(UpdateCallNotificaitonBasket $request): JsonResponse
    {
        $this->basketInit();

        $data = $request->all();

        try {
            $this->service->edit($data);
        } catch (\Throwable $e) {
            return $this->responseError($e->getMessage());
        }

        return $this->refreshAndReturnBasket();
    }

    /**
     * @param UpdatePaymentTypeBasket $request
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws \App\Repositories\RepositoryException
     */
    public function changePaymentType(UpdatePaymentTypeBasket $request): JsonResponse
    {
        $this->basketInit();

        $data = $request->all();

        try {
            $this->service->edit($data);
        } catch (\Throwable $e) {
            return $this->responseError($e->getMessage());
        }

        return $this->refreshAndReturnBasket();
    }

    /**
     * @param UpdateCourierBasket $request
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws \App\Repositories\RepositoryException
     */
    public function changeCourier(UpdateCourierBasket $request): JsonResponse
    {
        $this->basketInit();

        $data = $request->all();

        try {
            $data['deliveryType'] = DeliveryType::Delivery;
            $this->service->edit($data);
        } catch (\Throwable $e) {
            return $this->responseError($e->getMessage());
        }

        return $this->refreshAndReturnBasket();
    }

    /**
     * @param UpdatePromoCodeBasket $request
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws \App\Repositories\RepositoryException
     */
    public function addPromoCode(UpdatePromoCodeBasket $request): JsonResponse
    {
        $this->basketInit();

        $data = $request->all();

        try {
            $this->service->edit($data);
        } catch (\Throwable $e) {
            return $this->responseError($e->getMessage());
        }

        return $this->refreshAndReturnBasket();
    }

    /**
     * @param AddBasketItem $request
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function addBasketItem(AddBasketItem $request): JsonResponse
    {
        $this->basketInit();

        $data = $request->all();

        try {
            $this->service->addBasketItem($data);
        } catch (\Exception $e) {
            return $this->responseError('Ошибка добавление заказа: ' . $e->getMessage());
        }

        return $this->refreshAndReturnBasket();
    }

    /**
     * @param int $id
     * @param UpdateBasketItem $request
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     * @throws \Throwable
     */
    public function updateBasketItem(int $id, UpdateBasketItem $request): JsonResponse
    {
        $this->basketInit();

        $data = $request->all();

        $this->service->updateBasketItem($id, $data);

        return $this->refreshAndReturnBasket();
    }

    /**
     * @param AddItemsBasket $request
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws \App\Repositories\RepositoryException
     */
    public function addItems(AddItemsBasket $request): JsonResponse
    {
        $this->basketInit();

        $data = $request->all();

        try {
            $this->service->edit($data);
        } catch (\Throwable $e) {
            return $this->responseError('Ошибка обновления корзины');
        }

        return $this->refreshAndReturnBasket();
    }

    /**
     * @param DeleteBasketItem $request
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws \App\Repositories\RepositoryException
     */
    public function deleteItem(DeleteBasketItem $request): JsonResponse
    {
        $this->basketInit();

        $data = $request->all();

        try {
            $this->service->deleteByRequest($data);
        } catch (\Throwable $e) {
            return $this->responseError('Ошибка удаления товара корзины: ' . $e->getMessage());
        }

        return $this->refreshAndReturnBasket();
    }


    /**
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws \App\Repositories\RepositoryException
     */
    public function deleteItems(): JsonResponse
    {
        $this->basketInit();

        try {
            $this->service->deleteAllItems();
        } catch (\Throwable $e) {
            return $this->responseError('Ошибка очистки товаров корзины: ' . $e->getMessage());
        }

        return $this->refreshAndReturnBasket();
    }

    /**
     * @param int $orderId
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws \App\Repositories\RepositoryException
     */
    public function orderBasketItemRepeat(int $orderId): JsonResponse
    {
        try {
            /**
             * @var Order $orderModel
             */
            $orderModel = OrderService::findOne(['id' => $orderId])->getRepository()->getModel();
            $this->deleteItems();

            $this->service->edit(['items' => $orderModel->basket->items->toArray()]);

        } catch (\Throwable $e) {
            return $this->responseError('Заказ не существует');
        }


        return $this->refreshAndReturnBasket();
    }


    /**
     * @param string $groupIds
     * @param ChangeQuantity $request
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function changeQuantity(string $groupIds, ChangeQuantity $request): JsonResponse
    {
        $this->basketInit();

        $data = $request->all();

        $basketItemsIds = explode('-', $groupIds);

        if (is_array($basketItemsIds)) {

            try {
                $id = array_pop($basketItemsIds);
                $basketItemService = BasketItemService::findOne(['id' => $id]);
            } catch (\Throwable $e) {
                return $this->responseError('Хэш эллементов корзины передан неверно');

            }
            if ($data['type'] === 'up') {
                $basketItemService->copy();
            } else {
                $basketItemService->delete();
            }
            $this->service->events();
        }

        return $this->refreshAndReturnBasket();
    }


    /**
     * @param string $groupIds
     * @param ChangeModification $request
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     * @throws \Throwable
     */
    public function changeModification(string $groupIds, ChangeModification $request): JsonResponse
    {
        $this->basketInit();

        $data = $request->all();

        $basketItemsIds = explode('-', $groupIds);

        if (is_array($basketItemsIds)) {


            $basketItemService = BasketItemService::findList(['id' => $basketItemsIds]);

            foreach ($basketItemService as $item) {
                /**
                 * @var BasketItemService $item
                 */
                $item->getRepository()->update(['modificationMenuItemId' => $data['modificationId']]);
            }
            $this->service->events();

        }

        return $this->refreshAndReturnBasket();
    }

    /**
     * @param string $groupIds
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function deleteFromHash(string $groupIds): JsonResponse
    {
        $this->basketInit();


        $basketItemsIds = explode('-', $groupIds);

        if (is_array($basketItemsIds)) {


            $basketItemService = BasketItemService::findList(['id' => $basketItemsIds]);

            foreach ($basketItemService as $item) {
                /**
                 * @var BasketItemService $item
                 */
                $item->delete();
            }

            $this->service->events();

        }

        return $this->refreshAndReturnBasket();
    }


}
