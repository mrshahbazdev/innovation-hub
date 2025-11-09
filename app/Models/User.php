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

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

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
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
 * Update the user's profile photo.
 * YEH NAYA FUNCTION HAI JO COMPRESSION ADD KAREGA.
 */
    public function updateProfilePhoto(UploadedFile $photo, string $storagePath = 'profile-photos')
    {
        tap($this->profile_photo_path, function ($previous) use ($photo, $storagePath) {

            // 1. Image ko read karein
            $image = Image::read($photo);

            // 2. Resize aur crop karein (400x400)
            $image->cover(400, 400);

            // 3. Compress karein (JPEG, 80% quality)
            // (YEH LINE AB THEEK HO GAYI HAI)
            // Profile photos hamesha JPEG honi chahiye (size bachanay ke liye)
            $compressedImage = $image->encode(new JpegEncoder(80));

            // 4. Naya filename (hamesha .jpg)
            // Hum hashName() ke saath '.jpg' add kar rahe hain
            $filename = $photo->hashName() . '.jpg';

            // 5. Nayi compressed image ko store karein
            Storage::disk($this->profilePhotoDisk())->put(
                $storagePath.'/'.$filename,
                (string) $compressedImage
            );

            // 6. Database mein naya path save karein
            $this->forceFill([
                'profile_photo_path' => $storagePath.'/'.$filename,
            ])->save();

            // 7. Purani photo delete karein
            if ($previous) {
                Storage::disk($this->profilePhotoDisk())->delete($previous);
            }
        });
    }
}
