<?php

namespace App\Http\Middleware;


use App\Http\ResponseTrait;
use App\Repositories\System\UserRepository;
use App\Services\CRM\System\UserService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class CourierCheck
{
    use ResponseTrait;

    /**
     * @param Request $request
     * @param Closure $next
     * @param mixed ...$guards
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \ReflectionException
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {


        if (!auth()->user()) {
            return $this->responseError('Неавторизованный!', Response::HTTP_UNAUTHORIZED);
        }

        $userService = UserService::findOne(['id' => auth()->user()->id]);

        $permissions = $userService->mapPermissions();
        $filials = $userService->mapFilials();

        $actionName = str_replace("\\", "/", Route::current()->getActionName());

        $filialAccess = [];

        $access = false;

/*        foreach ($permissions as $permission) {

            if ($permission['actionName'] === $actionName) {

                if($permission['type'] === 'filials') {
                    $filialAccess = array_column($filials,'id');
                    $access = true;
                }elseif($permission['type'] === 'schedules') {

                }elseif($permission['type'] === 'all') {
                    $access = true;
                }

            }
        }*/


        $role = $userService->getRole();


        if($role['name'] === 'Курьер') {
            $access = true;
        }

   //     $request->request->add(['filialId' => $filialAccess]);

        if (!$access) {
            return $this->responseError('Не разрешено! В доступе отказано', Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
