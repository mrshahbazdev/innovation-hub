<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\JpegEncoder;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles; // <-- 1. YEH IMPORT ADD KAREIN

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles; // <-- 2. YEH TRAIT ADD KAREIN

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function updateProfilePhoto(UploadedFile $photo, string $storagePath = 'profile-photos')
    {
        tap($this->profile_photo_path, function ($previous) use ($photo, $storagePath) {
            $image = Image::read($photo);
            $image->cover(400, 400);
            $compressedImage = $image->encode(new JpegEncoder(80));
            $filename = $photo->hashName() . '.jpg';
            Storage::disk($this->profilePhotoDisk())->put(
                $storagePath.'/'.$filename,
                (string) $compressedImage
            );
            $this->forceFill([
                'profile_photo_path' => $storagePath.'/'.$filename,
            ])->save();
            if ($previous) {
                Storage::disk($this->profilePhotoDisk())->delete($previous);
            }
        });
    }

    public function canAccessPanel(Panel $panel): bool
    {
       return $this->is_admin || $this->hasPermissionTo('access_admin_panel');
    }
}
