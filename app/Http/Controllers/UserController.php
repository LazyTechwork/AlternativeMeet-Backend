<?php

namespace App\Http\Controllers;

use App\GlobalParams;
use App\Helpers\GlobalUtils;
use App\Http\Resources\UserResource;
use App\Rules\AgeRule;
use App\Rules\DistanceRule;
use App\Rules\NameRule;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use VK\Client\VKApiClient;

class UserController extends Controller
{
    /**
     * Search processor
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function search(Request $request)
    {
        $validator = validator($request->all(), [
            'user' => ['required', 'exists:users,vk_id'],
            'from_age' => ['required', new AgeRule()],
            'to_age' => ['required', 'gt:from_age', new AgeRule()],
            'distance' => ['required', new DistanceRule()],
            'sex' => ['required', 'in:0,1,2']
        ]);

        if ($validator->fails())
            return GlobalUtils::validatiorErrorResponse($validator);

        $cu = User::whereVkId($request->get('user'))->first();
        Auth::loginUsingId($cu->id);

        $now = Carbon::now(); // Current date
        $minbirth = $now->subYears($request->get('from_age')); // Current - years min
        $maxbirth = $now->subYears($request->get('to_age')); // Current - years max
        $distance = $request->get('distance');

        $users = User::where('sex', $request->get('sex'))
            ->whereBetween('birthday', [$minbirth, $maxbirth])->get(); // Getting all users

        $users = $users->filter(function (User $user) use ($distance, $cu) { // Filtering by distance
            $user->setAttribute('calculatedDistance', $user->distance($cu->geo));
            return $user->getAttribute('calculatedDistance') <= $distance;
        });

        $users = $users->shuffle();

        return response()->json([
            'status' => 'ok',
            'users' => UserResource::collection($users),
            'count' => $users->count()
        ])->setStatusCode(200); // Sending response
    }

    public function register(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->isRegistered())
            return response()->json([
                'status' => 'error',
                'messages' => ['Данный пользователь уже зарегистрирован'],
                'user' => UserResource::make($user)
            ])->setStatusCode(403);

        $validator = validator($request->all(), [
            'vk_id' => ['required', 'integer', 'exists:users,vk_id'],
            'firstname' => ['required', 'string', 'max:255', new NameRule()],
            'lastname' => ['required', 'string', 'max:255', new NameRule()],
            'birthday' => ['required', 'date_format:d.m.Y'],
            'sex' => ['required', 'in:0,1,2'],
            'geo_lat' => ['required', 'numeric'],
            'geo_long' => ['required', 'numeric'],
            'agefrom' => ['required', new AgeRule()],
            'ageto' => ['required', new AgeRule()]
        ]);

        if ($validator->fails())
            return GlobalUtils::validatiorErrorResponse($validator);

        if ($user->vk_id !== $request->get('vk_id'))
            return response()
                ->json(['status' => 'error', 'messages' => ['Данные авторизации не совпадают с переданными данными']])
                ->setStatusCode(400);

        $user->update([
            'vk_id' => $request->get('vk_id'),
            'firstname' => e($request->get('firstname')),
            'lastname' => e($request->get('lastname')),
            'birthday' => Carbon::createFromFormat('d.m.Y', $request->get('birthday')),
            'sex' => $request->get('sex'),
            'geo' => [$request->get('geo_lat'), $request->get('geo_long')],
            'agefrom' => $request->get('agefrom'),
            'ageto' => $request->get('ageto')
        ]);

        return response()->json(['status' => 'ok', 'user' => UserResource::make($user)]);
    }

    public function updateDescription(Request $request)
    {
        $validator = validator($request->all(), [
            'description' => ['required', 'string', 'max:1000']
        ]);

        if ($validator->fails())
            return GlobalUtils::validatiorErrorResponse($validator);

        $user = Auth::user();

        if ($user->canChangeDescription()) {
            $user->update(['description' => e($request->get('description'))]);
            $user = $user->fresh();
            return response()
                ->json(['status' => 'ok', 'user' => $user])->setStatusCode(200);
        } else
            return response()
                ->json(['status' => 'error', 'messages' => ['Невозможно сменить описание. Задержка между сменами описания - ' . GlobalParams::DESCRIPTION_CHANGE_DELAY . '.']])
                ->setStatusCode(422);

    }

    public function updatePhoto(Request $request)
    {
        $validator = validator($request->all(), [
            'photo' => ['required', 'image', 'max:5000']
        ]);

        if ($validator->fails())
            return GlobalUtils::validatiorErrorResponse($validator);

        $avatar = $request->file('photo');

        $vk = new VKApiClient('5.103');
        $token = env('VK_PHOTOGROUP_TOKEN');

        $user = Auth::user();

        if (!$user->canChangeAvatar())
            return response()
                ->json(['status' => 'error', 'messages' => ['Невозможно сменить фото профиля. Задержка между сменами фото профиля - ' . GlobalParams::AVATAR_CHANGE_DELAY . '.']])
                ->setStatusCode(422);

        try {
            $upload_server = $vk->photos()->getMessagesUploadServer($token, ['peer_id' => 1]); // Get upload server
            $photo = $vk->getRequest()->upload($upload_server, 'photo', $avatar->getRealPath()); // Uploading photo
            $photo = $vk->photos()->saveMessagesPhoto($token, [ // Saving photo and getting result
                'server' => $photo['server'],
                'photo' => $photo['photo'],
                'hash' => $photo['hash'],
            ]);
            return response()->json(['status' => 'ok', 'item' => $photo])->setStatusCode(200); // Returning photo object
        } catch (\Exception $exception) {
            return response()
                ->json(['status' => 'error', 'messages' => [$exception->getMessage()]])->setStatusCode(422); // If one of methods failed - throwing error response
        }
    }
}
