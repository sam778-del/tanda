<?php


namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait UploadTrait
{
    public function uploadFile(UploadedFile $file, $folder, $rename = false, $disk = null)
    {
        if (! $file instanceof  UploadedFile) {
            return false;
        }

        if (is_null($disk)) {
            $disk = 'public';
        }

        if ($rename) {
            $imageName = $file->getClientOriginalName();
            return $file->storeAs($folder, time().$imageName, $disk);
        }
        return $file->store($folder, $disk);
    }

    public function deleteFile($path = null, $disk = null)
    {
        if (is_null($disk)) {
            $disk = 'public';
        }
        Storage::disk($disk)->delete($path);
    }

    public function resizeAndUpload(UploadedFile $file, $width, $height, $folder, $disk = null)
    {
        if (! $file instanceof  UploadedFile) {
            return false;
        }

        if (is_null($disk)) {
            $disk = 'public';
        }

        $thumbnailImage = Image::make($file);
        $thumbnailPath = storage_path().'/app/public/'.$folder.'/';

        $thumbnailImage->resize($width, $height)->insert(public_path('afialink.png'), 'bottom-right');
        $fileName = time().$file->getClientOriginalName();
        $imagePath = $thumbnailPath.$fileName;
        $thumbnailImage->save($imagePath);
        return $folder.'/'.$fileName;
    }

    public function updateUploadImage($file, $column, $path, $resize = false)
    {
        if ($file) {
            $this->deleteFile($column);
            $imagePath = $resize ? $this->resizeAndUpload($file, 500, 500, $path) : $this->uploadFile($file, $path);
            if (! $imagePath) {
                return false;
            }
            return $imagePath;
        } else {
            $image = explode('storage/', $column);
            $imagePath = $image[1];
            return $imagePath;
        }
    }

    public function formatImageUrlToPath($image, $key)
    {
        $file = explode('/'.$key, $image);
        return $key.$file[1];
    }
}
