<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           DashboardController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     20/08/2021, 7:30 PM
 */

namespace App\Http\Controllers\Control\Account;

use App\Repositories\Finance\OrderRepository;
use App\Repositories\Users\UserRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use App\Abstracts\Http\Controllers\ControlController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Exceptions\RepositoryException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class DashboardController extends ControlController
{
    public function __construct(
        protected Carbon          $endDate,
        protected Carbon          $startDate,
        protected Carbon          $lastEndDate,
        protected Carbon          $lastStartDate,
        protected UserRepository  $userRepository,
        protected OrderRepository $orderRepository
    )
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return Factory|View|Application
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     */
    public function index(Request $request): Factory|View|Application
    {
        $this->setStartEndDate();

        $statistics = [
            'total_users' => [
                'previous' => $previous = $this->getUsersBetweenDates($this->lastStartDate, $this->lastEndDate, $request)->count(),
                'current' => $current = $this->getUsersBetweenDates($this->startDate, $this->endDate, $request)->count(),
                'progress' => getPercentageChange($previous, $current),
            ],
            'total_orders' => [
                'previous' => $previous = $this->previousOrders()->count(),
                'current' => $current = $this->currentOrders()->count(),
                'progress' => getPercentageChange($previous, $current),
            ],
            'total_sales' => [
                'previous' => $totalSalesPrevious = $this->previousOrders()->whereStatus('completed')->sum('grand_total'),
                'current' => $totalSalesCurrent = $this->currentOrders()->whereStatus('completed')->sum('grand_total'),
                'progress' => getPercentageChange($totalSalesPrevious, $totalSalesCurrent),
            ],
            'avg_sales' => [
                'previous' => $avgSalesPrevious = $this->previousOrders()->whereStatus('completed')->avg('grand_total'),
                'current' => $avgSalesCurrent = $this->currentOrders()->whereStatus('completed')->avg('grand_total'),
                'progress' => getPercentageChange($avgSalesPrevious, $avgSalesCurrent),
            ],

            //'users_with_most_sales' => $this->getUserWithMostPurchase($request),
            //'latestOrder' => $this->getLatestOrder($request),
        ];
        foreach (core()->getTimeInterval($this->startDate, $this->endDate) as $interval) {
            $statistics['sale_graph']['label'][] = $interval['start']->format('d M');

            $total = $this->getOrdersBetweenDate($interval['start'], $interval['end'])->sum('grand_total');

            $statistics['sale_graph']['total'][] = $total;
            $statistics['sale_graph']['formated_total'][] = core()->formatBasePrice($total);
        }
        $latestOrder = $this->getLatestOrder($request);
        
        return view($this->_config['view'], compact('statistics', 'latestOrder'));
    }


    /**
     * Return users with most purchase.
     *
     * @param Request $request
     * @return Collection
     */
    public function getUserWithMostPurchase(Request $request): Collection
    {
        return $this->orderRepository->getModel()
            ->select(DB::raw('SUM(grand_total) as total_grand_total'))
            ->addSelect(DB::raw('COUNT(id) as total_orders'))
            //->addSelect('id', 'user_id', 'user_email', 'user_first_name', 'user_last_name')
            ->where('created_at', '>=', $this->startDate)
            ->where('created_at', '<=', $this->endDate)
            ->orderBy('total_grand_total', 'DESC')
            ->groupBy('user_id')
            ->limit(5)
            ->get();
    }

    public function getLatestOrder(Request $request): Collection
    {
        return $this->orderRepository->getModel()
            //->addSelect('id', 'user_id', 'user_email', 'user_first_name', 'user_last_name')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();
    }
    /**
     * Sets start and end date
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function setStartEndDate()
    {
        $this->startDate = request()->get('start')
            ? Carbon::createFromTimeString(request()->get('start') . " 00:00:01")
            : Carbon::createFromTimeString(Carbon::now()->subDays(60)->format('Y-m-d') . " 00:00:01");

        $this->endDate = request()->get('end')
            ? Carbon::createFromTimeString(request()->get('end') . " 23:59:59")
            : Carbon::now();

        if ($this->endDate > Carbon::now()) {
            $this->endDate = Carbon::now();
        }

        $this->lastStartDate = clone $this->startDate;
        $this->lastEndDate = clone $this->startDate;

        $this->lastStartDate->subDays($this->startDate->diffInDays($this->endDate));
    }

    /**
     * Returns previous order query
     *
     * @return OrderRepository
     */
    private function previousOrders(): OrderRepository
    {
        return $this->getOrdersBetweenDate($this->lastStartDate, $this->lastEndDate);
    }

    /**
     * Returns current order query
     *
     * @return OrderRepository
     */
    private function currentOrders(): OrderRepository
    {
        return $this->getOrdersBetweenDate($this->startDate, $this->endDate);
    }

    /**
     * Returns orders between two dates
     *
     * @param Carbon $start
     * @param Carbon $end
     * @return OrderRepository
     */
    private function getOrdersBetweenDate(Carbon $start, Carbon $end): OrderRepository
    {
        return $this->orderRepository->scopeQuery(function ($query) use ($start, $end) {
            return $query->where('created_at', '>=', $start)->where('created_at', '<=', $end);
        });
    }

    /**
     * Returns users between two dates
     *
     * @param Carbon $start
     * @param Carbon $end
     * @return UserRepository
     */
    private function getUsersBetweenDates(Carbon $start, Carbon $end): UserRepository
    {
        return $this->userRepository->scopeQuery(function ($query) use ($start, $end) {
            return $query->where('created_at', '>=', $start)->where('created_at', '<=', $end);
        });
    }
}
