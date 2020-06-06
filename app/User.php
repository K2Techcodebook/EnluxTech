<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Traits\HasMeta;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Image;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasImage;
use Carbon\Carbon;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, Notifiable, HasMeta, InteractsWithMedia, HasImage, SoftDeletes, HasMeta;

    public function grantMeToken(){
      $token          =  $this->createToken('MyApp');

      return [
        'token'       => $token->accessToken,
        'token_type'  => 'Bearer',
        'expires_at'  => Carbon::parse(
            $token->token->expires_at
        )->toDateTimeString(),
      ];
    }

    public function registerMediaCollections(): void{
      $mimes = ['image/jpeg', 'image/png', 'image/gif'];
      $this->addMediaCollection('avatar')
      ->acceptsMimeTypes($mimes)
      ->singleFile()->useDisk('avatars')
      ->registerMediaConversions($this->convertionCallback());
    }

    private function convertionCallback(){
      return (function (Media $media = null) {
        $this->addMediaConversion('thumb')->nonQueued()
        ->width(368)->height(232);
        $this->addMediaConversion('medium')->nonQueued()
        ->width(400)->height(400);
      });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
