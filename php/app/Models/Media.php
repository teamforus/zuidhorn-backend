<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Services
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $table = 'medias';

    protected $fillable = ['mediable_id', 'mediable_type', 'type', 
    'confirmed', 'ext', 'name', 'description'];

    // single and multiple - types
    protected static $single_types      = ['original', 'preview'];
    protected static $multiple_types    = [];

    // basic for multi resolution
    public function metas() {
        return $this->hasMany('App\Models\MediaMeta');
    }

    // get specific meta by type
    public function meta($type = 'img_th') {
        return $this->metas()->whereType($type)->first();
    }

    public function img_lg() {
        return $this->hasOne('App\Models\MediaMeta')->where(['type' => 'img_lg']);
    }

    public function img_md() {
        return $this->hasOne('App\Models\MediaMeta')->where(['type' => 'img_md']);
    }

    public function img_sm() {
        return $this->hasOne('App\Models\MediaMeta')->where(['type' => 'img_sm']);
    }

    public function img_th() {
        return $this->hasOne('App\Models\MediaMeta')->where(['type' => 'img_th']);
    }

    public function avatar() {
        return $this->hasOne('App\Models\MediaMeta')->where(['type' => 'avatar']);
    }

    public function banner() {
        return $this->hasOne('App\Models\MediaMeta')->where(['type' => 'banner']);
    }

    public function _original() {
        return $this->hasOne('App\Models\MediaMeta')->where(['type' => 'original']);
    }

    public function _preview() {
        return $this->hasOne('App\Models\MediaMeta')->where(['type' => 'preview']);
    }

    // get public url for specific meta
    public function urlPublic($type = 'img_th') {
        return $this->$type->urlPublic();
    }

    // Get all of the owning imageable models.
    public function mediable() {
        return $this->morphTo();
    }

    public static function multiplePath() {
        return public_path('uploads/' . date('Y/z/'));
    }

    public static function singlePath(){
        return public_path('uploads/single/');
    }

    public static function getUploadPath($type) {
        if(in_array($type, self::$single_types))
            return self::singlePath();
        elseif (in_array($type, self::$multiple_types))
            return self::multiplePath();

        return FALSE;
    }

    public static function confirmSingle($type, $mediable_type, $mediable_id, $media_id) {
        self::revokeSingle($type, $mediable_type, $mediable_id, $media_id);       

        $media = self::find($media_id);

        if ($media && !$media->confirmed) {
            $media->update([
                'confirmed'     => TRUE,
                'mediable_id'   => $mediable_id,
                'mediable_type' => $mediable_type,
                'type'          => $type,
                ]);
        }

        return $media;
    }

    public static function revokeSingle($type, $mediable_type, $mediable_id, $media_id = FALSE) {
        $old = self::where([
            'mediable_id'   => $mediable_id, 
            'mediable_type' => $mediable_type, 
            'type'          => $type, 
            'confirmed'     => TRUE
            ])->first();

        if ($old) {
            if ($media_id && $old->id == $media_id)
                return;

            $old->unlink();
        }
    }

    public static function confirmMedia($mediable_type, $mediable_id, $media_id = []) {
        if (is_array($media_id)) {

            $medias = self::where([
                'mediable_type' => $mediable_type, 
                'confirmed'     => 0
                ])->whereIn('id', $media_id)->get();

            foreach ($medias as $media) {
                $media->update([
                    'confirmed'     => TRUE,
                    'mediable_id'   => $mediable_id]);
            }
        }

        $old_medias = self::whereNotIn('id', $media_id)->where([
            'mediable_id'   => $mediable_id,
            'mediable_type' => $mediable_type
            ])->get();
        
        foreach ($old_medias as $media) {
            $media->unlink();
        }
    }

    // clear old/unused medias
    public static function clear() {
        $m = 60;
        $h = $m * 60;
        $d = $h * 60;
        
        $old_media = self::where('confirmed', 0)
        ->where('created_at', '<', date('Y-m-d H:i:s', time() - $h))->get();

        if (count($old_media) > 0) {
            foreach ($old_media as &$media) {
                $media->unlink();
            }
        }

        self::clearSingle();
        
        // had bug last time i checked
        // deleted even valid medias 
        // self::clearMultiple();
    }

    // ignore
    protected static function clearSingle() {
        $single_path = self::singlePath();
        $singles = scandir($single_path);

        foreach ($singles as $key => &$single) {
            if (is_file($single_path . $single) && $single != 'index.html') {
                $single = $single_path . $single;
            } else {
                unset($singles[$key]);
            }
        }

        $media_meta = MediaMeta::whereIn('type', self::$single_types)
        ->get()->map(function($item) {
            return $item->realPath();
        });

        $trashes = array_diff($singles, $media_meta->toArray());

        foreach ($trashes as &$trash) {
            echo "$trash\n";
            unlink($trash);
        }
    }

    // ignore
    protected static function clearMultiple() {
        $medias = self::clearOtherMediaRecursively(public_path('uploads/', '/'));
        $media_confirmed = Media::whereIn('type', self::$multiple_types)->lists('id');
        $media_confirmed = MediaMeta::whereIn('media_id', $media_confirmed)->lists('value')->toArray();
        $list_files = [];

        foreach ($medias as $key => $md) {
            $_dir_info = scandir($md);

            foreach ($_dir_info as $key_b => $value_b) {
                if (is_file($md . '/' . $value_b) && $value_b != 'index.html') {
                    $list_files[] = $md . '/' . $value_b;
                }
            }
        }

        foreach ($list_files as $key_c => $value_c) {
            if(in_array($value_c, $media_confirmed)) {
                unset($list_files[$key_c]);
            } else {
                if (file_exists($value_c))
                    unlink($value_c);
            }
        }
    }

    // ignore
    protected static function clearOtherMediaRecursively($_root, $_dir = '') {
        $paths = [];
        $sub_dirs = scandir($_root . $_dir);

        foreach ($sub_dirs as $index => $dir) {
            if (!is_numeric($dir) || !is_dir($_root . $_dir . $dir)) {
                unset($sub_dirs[$index]);
            } else {
                $paths[] = $_root . $_dir . $dir;
                $paths = array_merge($paths, self::clearOtherMediaRecursively($_root . $_dir, $dir . '/'));
            }
        }

        return $paths;
    }

    public function unlink() {
        foreach ($this->metas as &$value)
            $value->unlink();

        return $this->delete();
    }

    public function urlDelete() {
        return url('media/delete/' . $this->id);
    }

    public function hasAccess($type, User $user) {
        // admin is overlord here
        if ($user->admin)
            return TRUE;

        switch ($type) {
            case 'edit': {
                return $this->hasAccessEdit($user);
            }; break;
        }

        return FALSE;
    }

    private function hasAccessEdit(&$user) {
        switch ($this->mediable_type) {
            case \App\User::class: {
                return $this->mediable->id == $user->id;
            }; break;
        }
    }

    public static function uploadSingleFromUrl($url, $type, $mediable_type) {
        // generate unique temp name
        do {
            $tmp_name = md5(mt_rand(0, time()));
            $tmp_path = 'linkpeek_tmp/' . $tmp_name . '.png';
            $jpg_path = 'linkpeek_tmp/' . $tmp_name . '.jpg';
        } while (file_exists(storage_path('app/' . $tmp_path)));

        if (($file_content = @file_get_contents($url)) === FALSE)
            return FALSE;

        // download file and store as temporary
        Storage::put($tmp_path, $file_content);

        // file should be valid image
        if (!getimagesize(storage_path('app/' . $tmp_path))) {
            // delete temporary file
            Storage::delete($tmp_path);

            return FALSE;
        }

        // convert png to jpeg
        Image::make(storage_path('app/' . $tmp_path))
        ->save(storage_path('app/' . $jpg_path), 90);

        // delete temporary file
        Storage::delete($tmp_path);

        // generate media
        $resp = self::uploadSingleFromFile(
            storage_path('app/' . $jpg_path), $type, $mediable_type);

        // delete temporary file
        Storage::delete($jpg_path);

        // return response
        return $resp;
    }

    public static function uploadSingleFromFile($file, $type, $mediable_type) {
        // file extension
        $ext = File::extension($file);
        $name = File::name($file);

        // upload file
        return self::doUpload($type, $mediable_type, $file, $name, $ext);
    }

    public static function uploadSingle($type, $mediable_type, $input_name = 'image') {
        // check if file exists
        if (!(Input::hasFile($input_name) && Input::file($input_name)->isValid()))
            return ['success' => FALSE];

        // file info
        $path   = (string)Input::file($input_name);
        $name   = Input::file($input_name)->getClientOriginalName();
        $ext    = Input::file($input_name)->getClientOriginalExtension();
        $user   = Auth::user();

        // get clear name
        $name   = rtrim($name, '.' . $ext);

        // do upload
        return self::doUpload($type, $mediable_type, $path, $name, $ext, $user);
    }

    protected static function unique_file_name($path, $name, $ext) {
        if (!strcmp($path[count($path) - 1], '/'))
            $path .= '/';

        while(file_exists($path . $name . '.' . $ext)) {
            $nameExplode = explode('-', $name);

            if(is_numeric($nameExplode[count($nameExplode) - 1])) {
                $nameExplode[count($nameExplode) - 1]++;
                $name = implode('-', $nameExplode);
            } else {
                $name .= '-1';
            }
        }

        return $name;
    }

    public static function doUpload($type, $mediable_type, $file_path, $name, $ext, $user = NULL) {
        // get file upload path
        $upload_path = self::getUploadPath($type);
        // create path if not exists, and add index.html inside
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0775, TRUE);
            file_put_contents($upload_path . 'index.html', '');
        }

        // $upload_path, $name

        // get unique name
        $name = self::unique_file_name($upload_path, $name, $ext);

        // upload path
        $upload_path = $upload_path . $name . '.' . $ext;
        
        // move file
        File::copy($file_path, $upload_path);

        // media row
        $media = [
        'title'         => '',
        'description'   => '',
        'mediable_id'   => NULL,
        'mediable_type' => $mediable_type,
        'type'          => $type,
        'ext'           => $ext,
        'confirmed'     => FALSE,
        ];

        // media row create
        if ($media = Media::create($media)) {
            // size
            $size = [];

            // types
            switch ($type) {
                case 'original': {
                    $size = ['x' => 1920, 'y' => 1080];
                }; break;
                case 'preview': {
                    $size = ['x' => 200, 'y' => 130];
                }; break;
            }

            // resize and save image
            $image = Image::make($upload_path);
            
            if ($size)
                $image = $image->fit($size['x'], $size['y']);
            
            $image = $image->save($upload_path, 90)->encode('data-url');

            // make file data:name, extension and base64 encoded image
            $data = [
            'name'      => $name, 
            'ext'       => $ext, 
            'dataUrl'   => $image->encoded, 
            'mediaId'   => $media->id,
            ];

            // media meta row create
            $meta = MediaMeta::create([
                'media_id'  => $media->id,
                'type'      => $type,
                'value'     => ltrim(str_replace(public_path(), '', $upload_path), '/')
                ]);

            return $data;
        }

        return FALSE;
    }

    public static function singleMediaUrlData($id) {
        // get single medias of single type and given id
        $media = Media::whereId($id)->whereIn(
            'type', self::$single_types)->first();


        if ($media_meta = $media->meta($media->type)) {
            if(file_exists($real_path = $media_meta->realPath()))
                return Image::make($real_path)->encode('data-url')->encoded;
        }

        return FALSE;
    }

    public static function uploadPhotos($type, $mediable_type, $input_name = 'photo') {
        // check if file exists
        if (!(Input::hasFile($input_name) && Input::file($input_name)->isValid()))
            return ['success' => FALSE];

        // file info
        $path   = (string)Input::file($input_name);
        $name   = Input::file($input_name)->getClientOriginalName();
        $ext    = Input::file($input_name)->getClientOriginalExtension();

        // lowercase extension
        $ext = strtolower($ext);

        // get clear name
        $name   = rtrim($name, '.' . $ext);

        // do upload
        return self::doUploadPhotos($type, $path, $name, $ext);
    }

    public static function doUploadPhotos($type, $mediable_type, $file_path, $name, $ext) {
        // get file upload path
        $upload_path = self::getUploadPath($type);

        // create path if not exists, and add index.html inside
        if (!file_exists($upload_path)) {
            @mkdir($upload_path, 0777, TRUE);
            file_put_contents($upload_path . 'index.html', '');
            file_put_contents(implode('/', array_slice(explode('/', $upload_path), 0, -2)) . '/index.html', '');
        }

        // get unique name
        $name = self::unique_file_name($upload_path, str_slug($name), $ext);

        // move file
        File::copy($file_path, $upload_path . $name . '.' . $ext);

        // media row
        $media = [
        'title'         => '',
        'description'   => '',
        'mediable_id'   => NULL,
        'mediable_type' => $mediable_type,
        'ext'           => $ext,
        'confirmed'     => FALSE,
        ];

        // media row create
        if ($media = Media::create($media)) {
            // original file or bigger available
            $original_path = $upload_path . $name . '.' . $ext;
            
            // limit original image resolution to given size
            $wide = self::limitImageSize($original_path, 2048);

            // update wide flat
            $media->update(['wide' => $wide]);

            // make image variants
            $meta_details = self::doPhotoTypes($original_path, $upload_path, $name, $ext);

            array_push($meta_details, [
                'value'     => ltrim(str_replace(public_path(), '', $upload_path . $name . '.' . $ext), '/'),
                'type'      => "original",
                ]);

            foreach ($meta_details as $key => &$meta_detail) {
                // set proper media id
                $meta_detail['media_id'] = $media->id;

                // create meta
                MediaMeta::create($meta_detail);
            }

            $data_url = (string) Image::make($media->img_th->realPath())
            ->fit(140, 140)
            ->encode('data-url');

            // make file data:name, extension and base64 encoded image
            $data = [
            'name'      => $name, 
            'ext'       => $ext, 
            'dataUrl'   => $data_url, 
            'mediaId'   => $media->id,
            ];

            return $data;
        }

        return FALSE;
    }

    public static function limitImageSize($upload_path, $max_size = 2048) {
        // image
        $image = Image::make($upload_path);

        // file size
        $width = $image->width();
        $height = $image->height();

        // check both axes
        if (max($width, $height) > $max_size) {
            // resize paramethers
            $param_a = $width > $height ? $max_size : null;
            $param_b = $width < $height ? $max_size : null;

            // do resize and save
            $image->resize($param_a, $param_b, function ($constraint) {
                $constraint->aspectRatio();
            })->save($upload_path, 85);
        }

        // is image landscape?
        return $width > $height;
    }

    public static function doPhotoTypes($original_path, $upload_path, $name, $ext) {
        // output container
        $out = [];

        // upload types container
        $media_sizes = [];

        // types sizes and quality (x, y, quality)
        $media_sizes['th'] = [140, 140, 95];
        $media_sizes['sm'] = [320, 200, 90];
        $media_sizes['md'] = [640, 400, 80];
        $media_sizes['lg'] = [1280, 800, 75];

        // convert to png quality format
        if (strtolower($ext) == 'png') {
            foreach ($media_sizes as $key => &$val) {
                $val[2] = round(abs(($val[2] - 100) / 11.111111));
            }
        }


        // make types
        foreach ($media_sizes as $key => $val) {
            // file type upload path
            $local_path = $upload_path . $name . '-' . $key . '.' . $ext;

            // convert and save image
            Image::make($original_path)
            ->fit($val[0], $val[1])
            ->save($local_path, $val[2]);

            // fill output details for media meta
            array_push($out, [
                'value'     => ltrim(str_replace(public_path(), '', $local_path), '/'),
                'type'      => "img_$key",
                ]);
        }

        return $out;
    }

    public static function regenerateMedias() {
        // get target medias
        $medias = self::whereIn('type', self::$multiple_types)->get();

        // count medias
        $count = count($medias);

        // go through target medais
        foreach ($medias as $key => $media) {
            // echo debug
            echo sprintf("converting %s from %s\n", $key + 1, $count);

            // check media and original
            if (!$media || !$media->_original) {
                echo "can't find original for: " . $media->id . "\n";
                continue;
            }

            // get original path
            $original_path = $media->_original->realPath();

            // check file existence
            if (!is_file($original_path)) {
                echo "is not file: " . $original_path . "\n";
                continue;
            }

            // get name and extension
            $upload_path    = pathinfo($original_path, PATHINFO_DIRNAME) . '/';
            $ext            = pathinfo($original_path, PATHINFO_EXTENSION);
            $name           = pathinfo($original_path, PATHINFO_FILENAME);

            // image validation
            try {
                // do resize and get details
                $meta_details = self::doPhotoTypes($original_path, $upload_path, $name, $ext);
            } catch (FatalErrorException $e) {
                echo "cannot open: " . $original_path . "\n";
                continue;
            } catch (NotReadableException $e) {
                echo "cannot open: " . $original_path . "\n";
                continue;
            }

            // check existing metas
            foreach ($meta_details as $key => $meta_detail) {
                // raw meta
                $raw_meta = array(
                    'type'      => $meta_detail['type'],
                    'media_id'  => $media->id
                    );

                // get target meta or create it
                if (!MediaMeta::where($raw_meta)->first()) {
                    // set proper media id
                    $meta_detail['media_id'] = $media->id;

                    // create meta
                    MediaMeta::create($meta_detail);
                }
            }

            // we should keep the originals
            $types = collect($meta_details)->lists('type')->push('original');

            // get untracked metas
            $delete_metas = $media->metas()->whereNotIn('type', $types)->get();

            // unlink each untracked metas
            foreach ($delete_metas as $key => $value) {
                $value->unlink();
            }
        }
    }
}
