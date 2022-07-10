<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           NotificationsController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Http\Controllers\Front\Account;


use App\Abstracts\Http\Controllers\FrontController;
use App\Transformers\Users\NotificationTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

/**
 * Class NotificationsController
 * @package App\Http\Controllers\Front\Account
 */
class NotificationsController extends FrontController
{

    /**
     * @OA\Get (
     *      path="/account/notification/all",
     *      operationId="notification_fetch",
     *      tags={"Account"},
     *      summary="Fetch all user's notification",
     *      description="Fetch all user's notification",
     *      security={{"bearerAuth":{}}},
     *     @OA\Response(response=201,description="notifications marked"),
     *     @OA\Response(response=400, description="Bad request"),
     *     )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        //$user->unreadNotifications()->paginate()
        $user = $request->user('frontend');
        return api()->status(200)->data(fractal($user->unreadNotifications, new NotificationTransformer())->toArray())->respond();
    }

    /**
     * @OA\Post(
     *      path="/account/notification/mark",
     *      operationId="notification_mark",
     *      tags={"Account"},
     *      summary="Mark notifications as read",
     *      description="Mark notifications as read",
     *      security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *          name="notification_ids[]",
     *          description="Notification ids",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                type="string"
     *             )
     *          )
     *      ),
     *     @OA\Response(response=201,description="notifications marked"),
     *     @OA\Response(response=400, description="Bad request"),
     *     )
     * @param Request $request
     */
    public function markNotificationsAsViewed(Request $request)
    {
        $user = $request->user('frontend');
        $ids = $request->get('notification_ids');
        $notifications = $this->user()->unreadNotifications()->whereIn('id', $ids)->get();
        $notifications->each(function (DatabaseNotification $notification) use ($user) {
            $notification->markAsRead();
            //event(new NotificationRead($user->id, $notification->id));
        });
    }

    /**
     * @OA\Delete(
     *      path="/account/notification/delete",
     *      operationId="notification_delete",
     *      tags={"Account"},
     *      summary="Delete notification",
     *      description="Delete notification",
     *      security={{"bearerAuth":{}}},
     *       @OA\Parameter(
     *          name="notification_ids[]",
     *          description="Notification ids",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                type="string"
     *             )
     *          )
     *      ),
     *     @OA\Response(response=201,description="notifications deleted"),
     *     @OA\Response(response=400, description="Bad request"),
     *     )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteNotifications(Request $request): JsonResponse
    {
        $user = $request->user('frontend');
        $ids = $request->get('notification_ids', []);
        $user->notifications()->whereIn('id', $ids)->delete();

        return api()->status(201)->respond();
    }
}
