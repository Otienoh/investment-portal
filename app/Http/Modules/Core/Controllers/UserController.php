<?php

namespace App\Http\Modules\Core\Controllers;

use App\Models\User\User;
use App\Models\User\UserNotices;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;

class UserController extends Controller
{
    public function getProfile(int $id): JsonResponse|Redirector|RedirectResponse|Application
    {
        /** @var User $user_model */
        $user_model = User::query()->where(['user_id' => $id])->first();
        if (!$user_model) {
            return redirect('/');
        }

        return response()->json($user_model->getProfile());
    }

    public function viewNotice(Request $request): JsonResponse
    {
        $notice_id = $request->all()['id'];
        /** @var UserNotices $notice_model */
        $notice_model = UserNotices::query()->find($notice_id);
        if (!$notice_model) {
            return response()->json(['message' => 'Not found notice'], 404);
        }
        $notice_model->viewed = true;
        $notice_model->save();
        return response()->json([]);
    }

    public function getUser()
    {
        $qwe = '';
    }
}
