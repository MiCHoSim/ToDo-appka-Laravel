<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
     * Odošli notifikáciu pre overenie emailovej adresy.
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
     * Ziskaj všetky zdieľané úlohy uživateľa
     *
     * @return BelongsToMany
     */
    public function toDoItems(): BelongsToMany
    {
        return $this->belongsToMany(ToDoItem::class)->orderBy('term');
    }

    /**
     * Skontroluj či Úloha patrí prihlasenému užívateľovi
     *
     * @return bool
     */
    public function userHasThisTask(ToDoItem $task) :bool
    {
        return ToDoItemUser::where('to_do_item_user.to_do_item_id', $task->id)->where('to_do_item_user.user_id',  Auth::id())->exists();//$this->id)->exists();
    }

    /**
     * Skontroluj či je prihlasený autor Úlohy
     *
     * @return bool
     */
    public function userAutorThisTask(ToDoItem $task) :bool
    {
        return ToDoItem::where('to_do_items.id', $task->id)->where('to_do_items.autor_id', Auth::id())->exists();
    }

    /**
     * Vráti pole uživateľov, kde kluč je id a hodnota je meno uživateľa/ okrem prihlaseného uživateľa
     * @return array
     */
    public function getUsersPairs() : array
    {
        return DB::table('users')->where('id',  '!=',Auth::id())->orderBy('name')->pluck('name','id')->toArray();
    }
}
