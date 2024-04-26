<?php

namespace App\Actions\Jetstream;

use App\Models\Company;
use App\Models\Layer;
use App\Models\Operation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    /**
     * Delete the given user.
     *
     * @param  mixed  $user
     * @return void
     */
    public function delete($user)
    {
        DB::beginTransaction();
        try {
            $users = User::where('id', '<>', $user->id)->where('parent_user_id', $user->id)->get();
            foreach ($users as $u) {
                $this->deleteItems($u);
                $this->deleteUser($u);
            }
            $this->deleteItems($user);
            $this->deleteUser($user);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

    private function deleteUser($user)
    {
        $user->deleteProfilePhoto();
        $user->tokens->each->delete();
        $user->delete();
        Log::info('User deleted: ' . json_encode($user));
    }

    private function deleteItems($user)
    {
        $user->company_id = null;
        $user->save();
        $operation_user = DB::table('operation_user')->where('user_id', $user->id)->get();
        foreach ($operation_user as $item) {
            DB::table('operation_user')->where('id', $item->id)->delete();
            Log::info('operation_user deleted: ' . json_encode($item));
        }

        $layers = Layer::withTrashed()->where('user_id', $user->id)->get();
        foreach ($layers as $l) {
            $l->forceDelete();
            Log::info('Operation deleted: ' . json_encode($l));
        }

        $companies = Company::withTrashed()->where('parent_user_id', $user->parent_user_id)->get();
        foreach ($companies as $c) {
            $operations = Operation::withTrashed()->where('company_id', $c->id)->get();
            foreach ($operations as $o) {
                $o->forceDelete();
                Log::info('Operation deleted: ' . json_encode($o));
            }
            $c->parent_user_id = null;
            $c->save();
        }
    }
}
