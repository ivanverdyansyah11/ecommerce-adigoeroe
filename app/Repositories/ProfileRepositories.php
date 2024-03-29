<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Reseller;
use App\Models\User;
use App\Utils\UploadFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProfileRepositories
{
    public function __construct(
        protected readonly User $user,
        protected readonly Admin $admin,
        protected readonly ResellerRepositories $reseller,
        protected readonly CustomerRepositories $customer,
        protected readonly UploadFile $uploadFile,
    ) {}

    public function update($request)
    {
        DB::beginTransaction();
        try {
            $user = $this->user->where('email', $request['email'])->first();
            if ($user['role'] == 'reseller') {
                $reseller = $this->reseller->findByUserId($user->id);
            } elseif($user['role'] == 'customer') {
                $customer = $this->customer->findByUserId($user->id);
            }

            if ($user['role'] == 'super_admin' || $user['role'] == 'admin') {
                if (isset($request["photo_ktp"])) {
                    $this->uploadFile->deleteExistFile("assets/images/admin/" . $user->admin->photo_ktp);
                    $filename = $this->uploadFile->uploadSingleFile($request['photo_ktp'], 'assets/images/admin');
                    $request['photo_ktp'] = $filename;
                } else {
                    $request['photo_ktp'] = $user->admin->photo_ktp;
                }
            } elseif($user['role'] == 'reseller') {
                if (isset($request["photo_ktp"])) {
                    $this->uploadFile->deleteExistFile("assets/images/reseller/" . $reseller->photo_ktp);
                    $filename = $this->uploadFile->uploadSingleFile($request['photo_ktp'], 'assets/images/reseller');
                    $request['photo_ktp'] = $filename;
                } else {
                    $request['photo_ktp'] = $reseller->photo_ktp;
                }
            } else {
                if (isset($request["photo_ktp"])) {
                    $this->uploadFile->deleteExistFile("assets/images/customer/" . $customer->photo_ktp);
                    $filename = $this->uploadFile->uploadSingleFile($request['photo_ktp'], 'assets/images/customer');
                    $request['photo_ktp'] = $filename;
                } else {
                    $request['photo_ktp'] = $customer->photo_ktp;
                }
            }
            if (isset($request["image"])) {
                $this->uploadFile->deleteExistFile("assets/images/profile/" . $user->image);
                $filename = $this->uploadFile->uploadSingleFile($request['image'], 'assets/images/profile');
                $request['image'] = $filename;
            } else {
                $request['image'] = $user->image;
            }
            if ($user['role'] == 'super_admin' || $user['role'] == 'admin') {
                $user->admin->update(Arr::except($request, ['email', 'image', 'password', 'confirmation_password']));
            } elseif($user['role'] == 'reseller') {
                $reseller->update(Arr::except($request, ['email', 'image', 'password', 'confirmation_password']));
            } else {
                $customer->update(Arr::except($request, ['email', 'image', 'password', 'confirmation_password']));
            }
            DB::commit();
            if ($request['password'] != null) {
                return $user->update(Arr::only($request, ['email', 'image', 'password']));
            } else {
                return $user->update(Arr::only($request, ['email', 'image']));
            }
        } catch (\Exception $e) {
            logger($e->getMessage());
            DB::rollBack();
            throw $e;
        }
    }

    public function logout($request): bool
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return true;
    }
}
