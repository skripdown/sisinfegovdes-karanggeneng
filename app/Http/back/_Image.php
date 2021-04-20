<?php
/** @noinspection PhpUndefinedMethodInspection */
/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection PhpUndefinedClassInspection */


namespace App\Http\back;
use A6digital\Image\DefaultProfileImage;
use Illuminate\Support\Facades\Storage;
use Image;

class _Image {

    private static $path = [
        'citizen_profile' => 'app/public/citizen/profile/',
        'citizen_card' => 'app/public/citizen/card/',
        'admin_profile' => 'app/public/admin/profile/'
    ];

    public static $public_path = [
        'citizen_profile' => 'storage/citizen/profile/',
        'admin_profile' => 'storage/admin/profile/',
    ];

    private static $public_path_2 = [
        'citizen_profile' => 'citizen/profile/',
        'admin_profile' => 'admin/profile/',
    ];

    private static $palette = [
        '#0275d8', '#5cb85c', '#f0ad4e',
        '#d9534f','#0b5a79','#98a48a',
        '#7681de','#f8b93c','#a3cb05',
        '#279546','#a63642','#d61a43',
        '#cc6654','#db7b47','#766063'
    ];

    private static $allowed_doc = ['doc','docx','pdf'];

    public static function allowedDoc($file): bool {
        return in_array($file->extension(), self::$allowed_doc);
    }

    public static function setIdCardPic($file, $identity, $plan): string {
        $filename = 'tr'.time().$identity.$plan.'.'.$file->getClientOriginalExtension();
        $filepath = self::$path['citizen_card'].$filename;
        self::save($file, $filepath);
        return $filename;
    }

    public static function setProfile($file, $identity, $role = 'und', $adm = false):string {
        $filename = time().$identity.$role.'.'.$file->getClientOriginalExtension();
        if ($adm)
            self::save($file,self::$path['admin_profile'].$filename,false,true);
        else
            self::save($file,self::$path['citizen_profile'].$filename,false,true);
        return $filename;
    }

    public static function setDefaultProfile($name, $identity, $role = 'und',$adm = false): string {
        $length = count(self::$palette);
        $name   = explode(' ', $name);
        if (count($name) > 1)
            $name = $name[0].' '.$name[1];
        else
            $name = $name[0];
        try {
            $img = DefaultProfileImage::create($name, 350, self::$palette[rand(0, $length - 1)], '#fff');
            $filename = time().$identity.$role.'.png';
            $path = self::$path['citizen_profile'];
            if ($adm) $path = self::$path['admin_profile'];
            self::save($img->encode(),$path.$filename,true);
        } catch (Exception $e) {
            return 'ERROR!';
        }
        return $filename;
    }

    private static function save($file, $filepath, $resize = false, $corp = false) {
        $img = Image::make($file);
        if ($resize) $img->resize(350,350);
        if ($corp) $img->fit(350,350);

        $img->save(storage_path($filepath));
    }

    public static function remove($filename, $path) {
        if (Storage::exists(self::$path[$path].$filename)) {
            Storage::delete(self::$path[$path].$filename);
        }
    }

    public static function move($filename, $source, $target) {
        if (Storage::disk('public')->exists(self::$public_path_2[$source].$filename)) {
            Storage::disk('public')->move(self::$public_path_2[$source].$filename, self::$public_path_2[$target].$filename);
        }
    }
}
