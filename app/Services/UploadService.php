<?php
namespace App\Services;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;



class UploadService
{

    public static function upload_admin_photo($data)
    {
        $admin_photo_url = "";

        if($data->hasFile('admin_photo')):
            $file = $data->file('admin_photo');
            $extension =  $file->getClientOriginalExtension();

            $filename ="admin_photo".time().'.'.$extension;

            if($filename):
                $file->move('media/admin_photo/',$filename);
            else:
                return "error";
            endif;

            $path = "media/admin_photo/";

            $admin_photo_url = URL::to('').'/'.$path.$filename;

            return $admin_photo_url;
        else:
            return "file_not_found";
        endif;
    }

    public static function upload_employe_photo($data)
    {
        $employe_photo_url = "";

        if($data->hasFile('employe_photo')):
            $file = $data->file('employe_photo');
            $extension =  $file->getClientOriginalExtension();

            $filename ="employe_photo".time().'.'.$extension;

            if($filename):
                $file->move('media/employe_photo/',$filename);
            else:
                return "error";
            endif;

            $path = "media/employe_photo/";

            $employe_photo_url = URL::to('').'/'.$path.$filename;

            return $employe_photo_url;
        else:
            return "file_not_found";
        endif;
    }

    public static function upload_folder_file($data)
    {
        $folder_file_url = "";

        if($data->hasFile('file_url')):
            $file = $data->file('file_url');
            $extension =  $file->getClientOriginalExtension();

            $filename ="folder_file".time().'.'.$extension;

            if($filename):
                $file->move('media/folder_file/',$filename);
            else:
                return "error";
            endif;

            $path = "media/folder_file/";

            $folder_file_url = URL::to('').'/'.$path.$filename;

            return $folder_file_url;
        else:
            return "file_not_found";
        endif;
    }


    public static function upload_document_courriers($data)
    {
        $document_courriers_url = "";

        if($data->hasFile('document_courriers')):
            $file = $data->file('document_courriers');
            $extension =  $file->getClientOriginalExtension();

            $filename ="document_courriers".time().'.'.$extension;

            if($filename):
                $file->move('media/document_courriers/',$filename);
            else:
                return "error";
            endif;

            $path = "media/document_courriers/";

            $document_courriers_url = URL::to('').'/'.$path.$filename;

            return $document_courriers_url;
        else:
            return "file_not_found";
        endif;
    }


    public static function upload_document_courriers_send($data)
    {

        if($data->hasFile('document_courriers_send')):
            $file = $data->file('document_courriers_send');
            $extension =  $file->getClientOriginalExtension();

            $filename ="document_courriers_send".time().'.'.$extension;

            if($filename):
                $file->move('media/document_courriers_send/',$filename);
            else:
                return "error";
            endif;

            $path = "media/document_courriers_send/";

            $document_courriers_send_url = URL::to('').'/'.$path.$filename;

            return $document_courriers_send_url;
        else:
            return "file_not_found";
        endif;
    }

    public static function upload_permission_file($data)
    {
        $permission_file = "";

        if($data->hasFile('permission_file')):
            $file = $data->file('permission_file');
            $extension =  $file->getClientOriginalExtension();

            $filename ="permission_file".time().'.'.$extension;

            if($filename):
                $file->move('media/permission_file/',$filename);
            else:
                return "error";
            endif;

            $path = "media/permission_file/";

            $permission_file = URL::to('').'/'.$path.$filename;

            return $permission_file;
        else:
            return "file_not_found";
        endif;
    }

}
