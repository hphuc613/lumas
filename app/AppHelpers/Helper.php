<?php

namespace App\AppHelpers;

use App\Http\Mail\SendMail;
use Exception;
use Illuminate\Support\Facades\Mail;
use Modules\Setting\Model\Setting;

class Helper{

    /**
     * @param $path
     *
     * @return array
     */
    public static function get_directories($path){
        $directories = [];
        $items       = scandir($path);
        foreach($items as $item){
            if($item == '..' || $item == '.'){
                continue;
            }
            if(is_dir($path . '/' . $item)){
                $directories[] = $item;
            }
        }

        return $directories;
    }

    /**
     * @return array
     */
    public static function config_menu_merge(){
        $modules    = self::get_directories(base_path('modules'));
        $activeMenu = [];
        foreach($modules as $key => $value){
            $urlPath = $value . '/Config/menu.php';
            if(file_exists(base_path('modules') . '/' . $urlPath)){
                $activeMenu[] = require(base_path('modules') . '/' . $urlPath);
            }
        }
        $activeMenu = collect($activeMenu)->sortBy('sort')->toArray();

        return $activeMenu;
    }

    /**
     * @return array
     */
    public static function config_permission_merge(){
        $modules = self::get_directories(base_path('modules'));
        $files   = [];
        $i       = 0;
        foreach($modules as $key => $value){
            $urlPath = $value . '/Config/permission.php';
            $file    = base_path('modules') . '/' . $urlPath;
            if(file_exists($file)){
                $files[(int)filemtime($file) + $i] = $file;
                $i++;
            }
        }
        ksort($files);
        $permissions = [];
        foreach($files as $file){
            $permissions[] = require($file);
        }

        return $permissions;
    }


    /**
     * @param array $array
     *
     * @return string
     */
    public static function getModal($array = []){
        if(!empty($array)){
            $class    = $array['class'] ?? null;
            $id       = $array['id'] ?? 'form-modal';
            $tabindex = $array['tabindex'] ?? '-1';
            $title    = $array['title'] ?? 'Title';
            if($tabindex !== false){
                $html = '<div class="modal fade ' . $class . '" id="' . $id . '" tabindex="' . $tabindex . '" role="dialog" aria-hidden="true">';
            }else{
                $html = '<div class="modal fade ' . $class . '" id="' . $id . '" role="dialog" aria-hidden="true">';
            }
            $html .= '<div class="modal-dialog">';
            $html .= '<div class="modal-content">';
            $html .= '<div class="modal-header"><h5>' . $title . '</h5></div>';
            $html .= '<div class="modal-body">';

            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }else{
            $html = '<div class="modal fade" id="form-modal" tabindex="-1" role="dialog" aria-labelledby="form-modal" aria-hidden="true">';
            $html .= '<div class="modal-dialog">';
            $html .= '<div class="modal-content">';
            $html .= '<div class="modal-header">';
            $html .= '<h5>Create</h5>';
            $html .= '</div>';
            $html .= '<div class="modal-body">';

            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
        return $html;
    }

    /**
     * @param $string
     * @param array $options
     *
     * @return bool|false|string|string[]|null
     */
    public static function slug($string, $options = []){
        //Bản đồ chuyển ngữ
        $slugTransliterationMap = [
            'á' => 'a',
            'à' => 'a',
            'ả' => 'a',
            'ã' => 'a',
            'ạ' => 'a',
            'â' => 'a',
            'ă' => 'a',
            'Á' => 'A',
            'À' => 'A',
            'Ả' => 'A',
            'Ã' => 'A',
            'Ạ' => 'A',
            'Â' => 'A',
            'Ă' => 'A',
            'ấ' => 'a',
            'ầ' => 'a',
            'ẩ' => 'a',
            'ẫ' => 'a',
            'ậ' => 'a',
            'Ấ' => 'A',
            'Ầ' => 'A',
            'Ẩ' => 'A',
            'Ẫ' => 'A',
            'Ậ' => 'A',
            'ắ' => 'a',
            'ằ' => 'a',
            'ẳ' => 'a',
            'ẵ' => 'a',
            'ặ' => 'a',
            'Ắ' => 'A',
            'Ằ' => 'A',
            'Ẳ' => 'A',
            'Ẵ' => 'A',
            'Ặ' => 'A',
            'đ' => 'd',
            'Đ' => 'D',
            'é' => 'e',
            'è' => 'e',
            'ẻ' => 'e',
            'ẽ' => 'e',
            'ẹ' => 'e',
            'ê' => 'e',
            'É' => 'E',
            'È' => 'E',
            'Ẻ' => 'E',
            'Ẽ' => 'E',
            'Ẹ' => 'E',
            'Ê' => 'E',
            'ế' => 'e',
            'ề' => 'e',
            'ể' => 'e',
            'ễ' => 'e',
            'ệ' => 'e',
            'Ế' => 'E',
            'Ề' => 'E',
            'Ể' => 'E',
            'Ễ' => 'E',
            'Ệ' => 'E',
            'í' => 'i',
            'ì' => 'i',
            'ỉ' => 'i',
            'ĩ' => 'i',
            'ị' => 'i',
            'Í' => 'I',
            'Ì' => 'I',
            'Ỉ' => 'I',
            'Ĩ' => 'I',
            'Ị' => 'I',
            'ó' => 'o',
            'ò' => 'o',
            'ỏ' => 'o',
            'õ' => 'o',
            'ọ' => 'o',
            'ô' => 'o',
            'ơ' => 'o',
            'Ó' => 'O',
            'Ò' => 'O',
            'Ỏ' => 'O',
            'Õ' => 'O',
            'Ọ' => 'O',
            'Ô' => 'O',
            'Ơ' => 'O',
            'ố' => 'o',
            'ồ' => 'o',
            'ổ' => 'o',
            'ỗ' => 'o',
            'ộ' => 'o',
            'Ố' => 'O',
            'Ồ' => 'O',
            'Ổ' => 'O',
            'Ỗ' => 'O',
            'Ộ' => 'O',
            'ớ' => 'o',
            'ờ' => 'o',
            'ở' => 'o',
            'ỡ' => 'o',
            'ợ' => 'o',
            'Ớ' => 'O',
            'Ờ' => 'O',
            'Ở' => 'O',
            'Ỡ' => 'O',
            'Ợ' => 'O',
            'ú' => 'u',
            'ù' => 'u',
            'ủ' => 'u',
            'ũ' => 'u',
            'ụ' => 'u',
            'ư' => 'u',
            'Ú' => 'U',
            'Ù' => 'U',
            'Ủ' => 'U',
            'Ũ' => 'U',
            'Ụ' => 'U',
            'Ư' => 'U',
            'ứ' => 'u',
            'ừ' => 'u',
            'ử' => 'u',
            'ữ' => 'u',
            'ự' => 'u',
            'Ứ' => 'U',
            'Ừ' => 'U',
            'Ử' => 'U',
            'Ữ' => 'U',
            'Ự' => 'U',
            'ý' => 'y',
            'ỳ' => 'y',
            'ỷ' => 'y',
            'ỹ' => 'y',
            'ỵ' => 'y',
            'Ý' => 'Y',
            'Ỳ' => 'Y',
            'Ỷ' => 'Y',
            'Ỹ' => 'Y',
            'Ỵ' => 'Y'
        ];

        //Ghép cài đặt do người dùng yêu cầu với cài đặt mặc định của hàm
        $options = array_merge([
                                   'delimiter'     => '-',
                                   'transliterate' => true,
                                   'replacements'  => [],
                                   'lowercase'     => true,
                                   'encoding'      => 'UTF-8'
                               ], $options);

        //Chuyển ngữ các ký tự theo bản đồ chuyển ngữ
        if($options['transliterate']){
            $string = str_replace(array_keys($slugTransliterationMap), $slugTransliterationMap, $string);
        }

        //Nếu có bản đồ chuyển ngữ do người dùng cung cấp thì thực hiện chuyển ngữ
        if(is_array($options['replacements']) && !empty($options['replacements'])){
            $string = str_replace(array_keys($options['replacements']), $options['replacements'], $string);
        }

        //Thay thế các ký tự không phải ký tự latin
        $string = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $string);

        //Chỉ giữ lại một ký tự phân cách giữa 2 từ
        $string = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1',
                               trim($string, $options['delimiter']));

        //Chuyển sang chữ thường nếu có yêu cầu
        if($options['lowercase']){
            $string = mb_strtolower($string, $options['encoding']);
        }

        //Trả kết quả
        return $string;
    }

    /**
     * @param $index
     * @return mixed|string
     */
    public static function segment($index){
        $path     = request()->path();
        $path_arr = explode('/', $path);

        return $path_arr[$index] ?? '/';
    }

    /**
     * @param $mail_to
     * @param $subject
     * @param $title
     * @param $body
     * @param null $template
     * @return bool
     */
    public static function sendMail($mail_to, $subject, $title, $body, $template = null){
        /** Send email */
        if(empty($template)){
            $template = 'Base::mail.send_test_mail';
        }
        $mail = new SendMail();
        $mail->to($mail_to)->subject($subject)->title($title)->body($body)->view($template);

        try{
            Mail::send($mail);
        }catch(Exception $e){
            return false;
        }

        return true;
    }

    /**
     * @param $string
     * @return bool
     */
    public static function isJson($string){
        try{
            json_decode($string);
        }catch(Exception $e){
            return false;
        }
        return true;
    }

    /**
     * @param $key
     * @return null
     */
    public static function getSetting($key){
        $data = Setting::where('key', $key)->first();
        return !empty($data) ? $data->value : null;
    }
}
