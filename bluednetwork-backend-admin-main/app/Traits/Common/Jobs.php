<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Jobs.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/04/2021, 4:31 AM
 */

namespace App\Traits\Common;

trait Jobs
{
   /**
    * Dispatch a command to its appropriate handler in the current process.
    *
    * @param mixed $job
    * @param mixed $handler
    * @return mixed
    */
   public function dispatchNow($job, $handler = NULL)
   {
      return dispatch_now($job, $handler);
   }

   /**
    * Dispatch a job to its appropriate handler and return a response array for ajax calls.
    *
    * @param mixed $job
    * @return mixed
    */
   public function ajaxDispatch($job)
   {
      $response = [];
      try {
         $data = $this->dispatch($job);

         $response = array_merge($response, [
            'success' => true,
            'error' => false,
            'data' => $data,
            'message' => '',
         ]);
      } catch (\Exception $exception) {
         logger()->error('ajax dispatch error : ' . $exception);
         $response = array_merge($response, [
            'success' => false,
            'error' => true,
            'data' => NULL,
            'message' => $exception->getMessage(),
         ]);
      }

      return $response;
   }

   /**
    * Dispatch a job to its appropriate handler.
    *
    * @param mixed $job
    * @return mixed
    */
   public function dispatch($job)
   {
      $function = $this->getDispatchFunction();

      return dispatch_now($job);
   }

   /**
    * @return string
    */
   public function getDispatchFunction()
   {
      $config = config('queue.default');

      return ($config == 'sync') ? 'dispatch_now' : 'dispatch';
   }
}
