<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * They sent a notification to verify their email address.
     *
     * @return void
     */
    public function sendEmailVerificationNotification(): void
    {
        VerifyEmail::toMailUsing(static function ($notifiable, string $verificationUrl) {
            return (new MailMessage)
                ->subject('Overenie emailovej adresy')
                ->line('Pre overenie Vašej emailovej adresy stlačte tlačidlo nižšie.')
                ->action('Overiť emailovu adresu', $verificationUrl)
                ->line('Pokiaľ ste nevytvarali uživateľský účet, není nutná žiadna akcia.');
        });

        parent::sendEmailVerificationNotification();
    }

    /**
     * Get all of the user's shared tasks
     *
     * @return BelongsToMany
     */
    public function toDoItems(): BelongsToMany
    {
        return $this->belongsToMany(ToDoItem::class);//->orderBy('term');
    }

    /**
     * Check if the Job belongs to the logged in user
     *
     * @return bool
     */
    public function userHasThisTask(ToDoItem $task) :bool
    {
        return $task->users()->where('to_do_item_id', $task->id)->where('user_id', Auth::id())->exists();
    }
}
