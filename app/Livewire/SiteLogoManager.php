<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\JpegEncoder; // <-- 1. JPEG ENCODER IMPORT KAREIN

#[Layout('layouts.app')]
class SiteLogoManager extends Component
{
    use WithFileUploads;

    public $logo;
    public $currentLogoUrl;
    private $logoPath = 'public/site-logo'; // Path ko alag se define karein

    public function mount()
    {
        $this->loadCurrentLogo();
    }

    /**
     * Naya logo save karne ka function (Updated)
     */
    public function saveLogo()
    {
        $this->validate([
            'logo' => 'required|image|max:2048',
        ]);

        // 1. Image ko read karein
        $image = Image::read($this->logo);

        // 2. Resize karein
        $image->resize(500, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // 3. Extension check karein aur sahi encoder istemal karein
        $extension = strtolower($this->logo->getClientOriginalExtension());
        $filename = 'logo.' . $extension; // Naya filename extension ke saath

        if ($extension === 'jpg' || $extension === 'jpeg') {
            // JPEG ko 80% quality par save karein
            $compressedImage = $image->encode(new JpegEncoder(80));
        } else {
            // Baaqi sab (PNG, GIF) ko PNG (quality 9) save karein
            $compressedImage = $image->encode(new PngEncoder(9));
            $filename = 'logo.png'; // Force PNG agar JPG nahi hai
        }

        // 4. Purana logo delete karein (agar mojood hai)
        Storage::delete([$this->logoPath . '.png', $this->logoPath . '.jpg']);

        // 5. Naya logo save karein
        $path = $this->logoPath . '.' . ($extension === 'jpg' || $extension === 'jpeg' ? $extension : 'png');
        Storage::put($path, (string) $compressedImage);

        // 6. Preview refresh karein
        $this->loadCurrentLogo();
        $this->logo = null;
        session()->flash('message', 'Logo updated successfully.');
    }

    /**
     * Purana logo dhoond kar load karein
     */
    private function loadCurrentLogo()
    {
        if (Storage::exists($this->logoPath . '.png')) {
            $this->currentLogoUrl = Storage::url($this->logoPath . '.png') . '?t=' . time();
        } elseif (Storage::exists($this->logoPath . '.jpg')) {
            $this->currentLogoUrl = Storage::url($this->logoPath . '.jpg') . '?t=' . time();
        } else {
            $this->currentLogoUrl = 'https://via.placeholder.com/150'; // Default image
        }
    }

    public function render()
    {
        return view('livewire.site-logo-manager');
    }
}
