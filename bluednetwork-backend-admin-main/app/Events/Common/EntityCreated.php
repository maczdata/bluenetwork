<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           EntityCreated.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Events\Common;

use Illuminate\Queue\SerializesModels;

class EntityCreated
{
   use SerializesModels;

   public $entity;

   public $freshCreation;

   public function __construct($entity, $freshCreation)
   {
      $this->entity = $entity;

      $this->freshCreation = $freshCreation;
   }
}
