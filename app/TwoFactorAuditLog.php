<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwoFactorAuditLog extends Model
{
   protected $table = 'two_factor_audit_logs';

   public $timestamps = false;

   const EVENT_ENROLLED        = 'enrolled';
   const EVENT_DISABLED        = 'disabled';
   const EVENT_ADMIN_RESET     = 'admin_reset';
   const EVENT_RECOVERY_USED   = 'recovery_used';
   const EVENT_LOCKED_OUT      = 'locked_out';
   const EVENT_ROLE_TOGGLED    = 'role_requirement_toggled';

   protected $fillable = [
      'user_id',
      'actor_id',
      'event',
      'meta',
      'created_at',
   ];

   protected function casts(): array
   {
      return [
         'meta'       => 'array',
         'created_at' => 'datetime',
      ];
   }

   public function user()
   {
      return $this->belongsTo('App\User', 'user_id');
   }

   public function actor()
   {
      return $this->belongsTo('App\User', 'actor_id');
   }

   /**
    * @param int      $userId  account the event is about
    * @param string   $event   one of the EVENT_* constants
    * @param int|null $actorId who performed it; null means system or the user themselves
    */
   public static function record($userId, $event, $actorId = null, ?array $meta = null): self
   {
      return self::create([
         'user_id'    => $userId,
         'actor_id'   => $actorId,
         'event'      => $event,
         'meta'       => $meta,
         'created_at' => now(),
      ]);
   }
}
