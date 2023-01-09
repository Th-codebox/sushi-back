<?php


namespace App\Libraries\TeleStore;


use App\Libraries\TeleStore\Exceptions\PhoneEventException;
use App\Models\System\User;
use App\Models\System\WorkSchedule;
use App\Models\System\WorkSpace;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AccountRouter
{
    public function getPhoneAccountByUser(User $user)
    {
        /** @var WorkSchedule $workSchedule */
        $workSchedule = $user->getCurrentWorkSchedule;
        if ($user->getCurrentWorkSchedule) {
            return $workSchedule->workSpace->phone_account;
        }
    }


    /**
     * @param $phone_account
     * @return User
     */
    public function getUsersByPhoneAccount($phone_account)
    {
        if (empty($phone_account)) return [];
        $users = [];

        try {
            $service = new \App\Services\CRM\System\WorkSpaceService();
            $users[] = $service->getUsersByPhoneAccount($phone_account);
        } catch (ModelNotFoundException $e) {

        }


        //$users[] = User::find(1);

        return $users;

    }

    public function getFilialByPhoneAccount($account)
    {
        try {
            $workSpace = WorkSpace::where('phone_account', $account)->firstOrFail();
            return $workSpace->filial;
        } catch (ModelNotFoundException $e) {
            throw new PhoneEventException("Рабочее место для аккаунта {$account} не найдено");
        }
    }

    public function getAccountsByFilial($filialId)
    {
        /** @var WorkSpace[] $workSpaces */
        $workSpaces = WorkSpace::query()
            ->whereNotNull('phone_account')
            ->where('filial_id', '=', $filialId)
            ->get();

        $data = [];

        foreach ($workSpaces as $workSpace) {
            $data[] = $workSpace->phone_account;
        }

        return $data;

    }

}
